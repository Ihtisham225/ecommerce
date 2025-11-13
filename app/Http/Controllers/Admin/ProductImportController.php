<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Document;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class ProductImportController extends Controller
{
    // Upload endpoint: save CSV and return metadata (total rows, path)
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        try {
            Storage::disk('public')->makeDirectory('imports');

            $path = $request->file('file')->store('imports', 'public');
            $fullPath = Storage::disk('public')->path($path);

            if (!file_exists($fullPath)) {
                return response()->json(['message' => 'Failed to store uploaded file.'], 500);
            }

            // Count rows (excluding header)
            $csv = Reader::createFromPath($fullPath, 'r');
            $csv->setHeaderOffset(0);

            $total = 0;
            foreach ($csv as $row) {
                $total++;
            }

            return response()->json([
                'message' => 'File uploaded successfully',
                'path' => $path,
                'total' => $total,
            ]);

        } catch (\Exception $e) {
            Log::error('Upload failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Upload failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Process a chunk of rows from the uploaded CSV.
     * - uses external_id (Woo ID) for deduplication
     * - queues variations that reference parents not yet present (persisted to a pending JSON file)
     */
    public function processChunk(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
            'offset' => 'required|integer|min:0',
            'limit' => 'required|integer|min:1|max:200',
        ]);

        try {
            $path = $request->input('path');
            $offset = (int)$request->input('offset');
            $limit = (int)$request->input('limit');

            $fullPath = Storage::disk('public')->path($path);

            if (!file_exists($fullPath)) {
                return response()->json(['message' => 'CSV file not found on server.'], 404);
            }

            $csv = Reader::createFromPath($fullPath, 'r');
            $csv->setHeaderOffset(0);

            $stmt = (new Statement())->offset($offset)->limit($limit);
            $records = $stmt->process($csv);

            $processed = 0;
            $errors = [];
            $currentRow = $offset + 1;

            // Pending file path for variations that arrive before their parent product
            $pendingPath = $this->getPendingPathFromCsvPath($path);

            // Load any existing pending map for quicker re-check after this chunk
            $pending = $this->loadPendingVariations($pendingPath);

            foreach ($records as $index => $record) {
                try {
                    $type = strtolower(trim($record['Type'] ?? 'simple'));

                    if ($type === 'variable') {
                        // Create or update variable parent product
                        $product = $this->createOrUpdateVariableProduct($record, $currentRow);
                        $processed++;

                        // After creating parent, try to attach any pending variations
                        $extId = $record['ID'] ?? null;
                        if ($extId) {
                            if (!empty($pending[$extId])) {
                                foreach ($pending[$extId] as $pendingVariation) {
                                    try {
                                        $this->processVariation($product, $pendingVariation, $currentRow);
                                    } catch (\Exception $e) {
                                        Log::error('Processing pending variation failed', [
                                            'parent_external_id' => $extId,
                                            'error' => $e->getMessage()
                                        ]);
                                    }
                                }
                                // Remove processed pending variations
                                unset($pending[$extId]);
                            }
                        }

                    } elseif ($type === 'variation') {
                        // Variation processing - DON'T count as processed product
                        $parentField = $record['Parent'] ?? ($record['Parent ID'] ?? null);
                        $parentExternalId = $this->extractParentId($parentField);

                        $parentProduct = null;
                        if ($parentExternalId) {
                            $parentProduct = Product::where('external_id', $parentExternalId)->first();
                        }

                        if (!$parentProduct) {
                            // Also try by parent SKU
                            $possibleParentSku = $record['Parent SKU'] ?? null;
                            if ($possibleParentSku) {
                                $parentProduct = Product::where('sku', $possibleParentSku)->first();
                            }
                        }

                        if ($parentProduct) {
                            // Parent exists: process variation
                            $this->processVariation($parentProduct, $record, $currentRow);
                        } else {
                            // Parent not present: queue for later
                            if ($parentExternalId) {
                                $pending[$parentExternalId][] = $record;
                                $this->savePendingVariations($pendingPath, $pending);
                            } else {
                                Log::warning('Skipping variation with no parent info', ['record' => $record]);
                                $errors[] = "Row {$currentRow}: Variation skipped - parent not found or parent ID missing.";
                            }
                        }
                        // Don't increment $processed for variations

                    } else {
                        // Simple product
                        $this->createOrUpdateSimpleProduct($record, $currentRow);
                        $processed++;
                    }

                    $currentRow++;

                } catch (\Exception $e) {
                    $errorMsg = "Row {$currentRow}: " . $e->getMessage();
                    Log::error('Import row failed', ['error' => $e->getMessage(), 'record' => $record]);
                    $errors[] = $errorMsg;
                    $currentRow++;
                }
            }

            // Save pending variations back
            $this->savePendingVariations($pendingPath, $pending);

            return response()->json([
                'processed' => $processed,
                'errors' => $errors,
                'message' => $processed > 0 ? "Processed {$processed} products" : "No products processed",
            ]);

        } catch (\Exception $e) {
            Log::error('Chunk processing failed', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Chunk processing failed: ' . $e->getMessage(),
                'processed' => 0,
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    /**
     * Create or update a simple product (non-variable)
     */
    protected function createOrUpdateSimpleProduct(array $record, int $currentRow): Product
    {
        DB::beginTransaction();
        try {
            $title = $record['Name'] ?? $record['name'] ?? null;
            if (!$title) {
                throw new Exception('Missing product name');
            }

            $externalId = $record['ID'] ?? null;
            $sku = $record['SKU'] ?? null;

            // Try to find existing product by external_id first, then by SKU, then by title slug
            $product = $this->findExistingProduct($externalId, $sku, $title);

            $price = $record['Regular price'] ?? ($record['Price'] ?? 0);
            $isPublished = (isset($record['Published']) ? (string)$record['Published'] === '1' : true);
            $isFeatured = (isset($record['Is featured?']) ? (string)$record['Is featured?'] === '1' : false);
            $imagesRaw = $record['Images'] ?? $record['Image'] ?? '';
            $categoriesRaw = $record['Categories'] ?? $record['Category'] ?? '';
            $brandFromCsv = $record['Brands'] ?? $record['Brand'] ?? '';

            $stockQuantity = $this->parseStockQuantity($record);
            $stockStatus = $this->determineStockStatus($record, $stockQuantity);
            $trackStock = $this->shouldTrackStock($record);

            $description = $this->cleanDescription($record['Description'] ?? ($record['Short description'] ?? ''));

            // Generate unique sku/slug if missing
            $unique = Product::generateUniqueSkuAndSlug($title, $product->id ?? null);

            $data = [
                'title' => ['en' => $title],
                'description' => ['en' => $description],
                'price' => $price ?: 0,
                'sku' => $sku ?: $unique['sku'],
                'slug' => $product->slug ?? $unique['slug'],
                'type' => 'simple',
                'stock_quantity' => $stockQuantity,
                'track_stock' => $trackStock,
                'stock_status' => $stockStatus,
                'is_active' => true,
                'is_published' => $isPublished,
                'is_featured' => $isFeatured,
                'published_at' => now(),
                'platform' => 'woocommerce',
                'external_id' => $externalId,
                'raw_data' => $record,
                'has_options' => false,
            ];

            if ($product) {
                $product->update($data);
            } else {
                $product = Product::create($data);
            }

            // Brand
            if (!empty($brandFromCsv)) {
                $brandNames = array_filter(array_map('trim', preg_split('/[,|>]/', $brandFromCsv)));
                $primaryBrand = null;

                foreach ($brandNames as $brandName) {
                    if (empty($brandName)) continue;
                    $brand = Brand::firstOrCreate(
                        ['slug' => Str::slug($brandName)],
                        ['name' => $brandName]
                    );
                    if (!$primaryBrand) $primaryBrand = $brand;
                }

                if ($primaryBrand) {
                    $product->brand()->associate($primaryBrand);
                    $product->save();
                }
            }

            // Categories
            if (!empty($categoriesRaw)) {
                $categoryNames = array_filter(array_map('trim', preg_split('/[,|>]/', $categoriesRaw)));
                foreach ($categoryNames as $catName) {
                    if (empty($catName)) continue;
                    $cat = Category::firstOrCreate(
                        ['name' => $catName],
                        ['slug' => Str::slug($catName)]
                    );
                    $product->categories()->syncWithoutDetaching([$cat->id]);
                }
            }

            DB::commit();

            // Images (outside transaction)
            $this->processProductImages($product, $imagesRaw);

            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Create or update a variable (parent) product.
     */
    protected function createOrUpdateVariableProduct(array $record, int $currentRow): Product
    {
        DB::beginTransaction();
        try {
            $title = $record['Name'] ?? $record['name'] ?? null;
            if (!$title) {
                throw new Exception('Missing product name');
            }

            $externalId = $record['ID'] ?? null;
            $sku = $record['SKU'] ?? null;

            // Try find existing by external_id or sku or title
            $product = $this->findExistingProduct($externalId, $sku, $title);

            $price = $record['Regular price'] ?? ($record['Price'] ?? 0);
            $isPublished = (isset($record['Published']) ? (string)$record['Published'] === '1' : true);
            $isFeatured = (isset($record['Is featured?']) ? (string)$record['Is featured?'] === '1' : false);
            $imagesRaw = $record['Images'] ?? $record['Image'] ?? '';
            $categoriesRaw = $record['Categories'] ?? $record['Category'] ?? '';
            $brandFromCsv = $record['Brands'] ?? $record['Brand'] ?? '';

            $description = $this->cleanDescription($record['Description'] ?? ($record['Short description'] ?? ''));

            $unique = Product::generateUniqueSkuAndSlug($title, $product->id ?? null);

            $data = [
                'title' => ['en' => $title],
                'description' => ['en' => $description],
                'price' => $price ?: 0,
                'sku' => $sku ?: $unique['sku'],
                'slug' => $product->slug ?? $unique['slug'],
                'type' => 'variable',
                'stock_quantity' => 0,
                'track_stock' => false,
                'stock_status' => 'in_stock',
                'is_active' => true,
                'is_published' => $isPublished,
                'is_featured' => $isFeatured,
                'published_at' => now(),
                'platform' => 'woocommerce',
                'external_id' => $externalId,
                'raw_data' => $record,
                'has_options' => true, // THIS IS CRITICAL
            ];

            if ($product) {
                $product->update($data);
            } else {
                $product = Product::create($data);
            }

            // Brand
            if (!empty($brandFromCsv)) {
                $brandNames = array_filter(array_map('trim', preg_split('/[,|>]/', $brandFromCsv)));
                $primaryBrand = null;

                foreach ($brandNames as $brandName) {
                    if (empty($brandName)) continue;
                    $brand = Brand::firstOrCreate(
                        ['slug' => Str::slug($brandName)],
                        ['name' => $brandName]
                    );
                    if (!$primaryBrand) $primaryBrand = $brand;
                }

                if ($primaryBrand) {
                    $product->brand()->associate($primaryBrand);
                    $product->save();
                }
            }

            // Categories
            if (!empty($categoriesRaw)) {
                $categoryNames = array_filter(array_map('trim', preg_split('/[,|>]/', $categoriesRaw)));
                foreach ($categoryNames as $catName) {
                    if (empty($catName)) continue;
                    $cat = Category::firstOrCreate(
                        ['name' => $catName],
                        ['slug' => Str::slug($catName)]
                    );
                    $product->categories()->syncWithoutDetaching([$cat->id]);
                }
            }

            // Extract options (Attribute 1 name + values etc.)
            $options = $this->extractOptionsFromAttributes($record);
            if (!empty($options)) {
                $this->syncOptions($product, $options);
            }

            DB::commit();

            // images outside transaction
            $this->processProductImages($product, $imagesRaw);

            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Sync product options (like Color, Size).
     * Expects $options = [
     *   ['id' => optional, 'name' => 'Color', 'values' => ['Red','Blue']],
     *   ...
     * ]
     */
    protected function syncOptions(Product $product, array $options): void
    {
        $incomingIds = [];

        foreach ($options as $opt) {
            // Normalize structure
            if (!is_array($opt)) continue;

            $name = trim($opt['name'] ?? '');
            $values = $opt['values'] ?? [];

            // If values come as comma/string, convert to array
            if (is_string($values) && $values !== '') {
                $values = array_filter(array_map('trim', preg_split('/[,|]/', $values)));
            } elseif (is_array($values)) {
                $values = array_filter(array_map(function ($v) {
                    return is_null($v) ? null : trim((string)$v);
                }, $values));
            } else {
                $values = [];
            }

            // skip empty names
            if ($name === '') continue;

            $data = [
                'name' => $name,
                // ensure values is a plain array (no associative keys) and reindex
                'values' => array_values(array_filter($values, fn($v) => $v !== '')),
            ];

            // Update existing option by id if provided and belongs to this product
            if (!empty($opt['id'])) {
                $option = $product->options()->find($opt['id']);
                if ($option) {
                    $option->update($data);
                    $incomingIds[] = $option->id;
                    continue;
                }
            }

            // Try to find by name (avoid creating duplicates when import has same option name)
            $existingByName = $product->options()->where('name', $name)->first();
            if ($existingByName) {
                $existingByName->update($data);
                $incomingIds[] = $existingByName->id;
                continue;
            }

            // Create new option
            $newOption = $product->options()->create($data);
            if ($newOption) {
                $incomingIds[] = $newOption->id;
            }
        }

        // Remove deleted options: keep only incomingIds
        if (!empty($incomingIds)) {
            $product->options()->whereNotIn('id', $incomingIds)->delete();
        } else {
            // If nothing incoming, delete all options
            $product->options()->delete();
        }
    }

    /**
     * Process a variation row and attach to the given parent product.
     * Accepts attribute columns or Attribute n name/value pairs on the variation row itself.
     */
    protected function processVariation(Product $parentProduct, array $record, int $currentRow): void
    {
        Log::info('Processing variation', [
            'parent_id' => $parentProduct->id,
            'parent_external_id' => $parentProduct->external_id,
            'variation_external_id' => $record['ID'] ?? null,
            'variation_name' => $record['Name'] ?? null,
        ]);
        
        DB::beginTransaction();
        try {
            // Variation-specific fields
            $variationSku = $record['SKU'] ?? null;
            $externalId = $record['ID'] ?? null;
            $price = $record['Regular price'] ?? ($record['Price'] ?? 0);
            $stockQuantity = $this->parseStockQuantity($record);
            $trackStock = $this->shouldTrackStock($record);

            // Get the first product image to use as variant image
            $variantImageId = $this->getFirstProductImageId($parentProduct);

            // Build variation options:
            // - Prefer Attribute n name and Attribute n value(s) (variation row usually has single value),
            // - Fallback to parsing variation name and matching to parent's option names.
            $optionsForVariant = [];

            // Try attribute columns on variation row
            for ($i = 1; $i <= 10; $i++) {
                $attrNameCol = "Attribute {$i} name";
                $attrValueCol = "Attribute {$i} value(s)";

                if (!empty($record[$attrNameCol]) && isset($record[$attrValueCol])) {
                    $name = trim($record[$attrNameCol]);
                    $value = trim($record[$attrValueCol]);
                    if ($name !== '' && $value !== '') {
                        // Variation row may contain single value (not comma list)
                        $optionsForVariant[$name] = $value;
                    }
                }
            }

            // If no attribute columns, attempt to match using parent's options and the variation title
            if (empty($optionsForVariant)) {
                $variationTitle = $record['Name'] ?? $record['name'] ?? '';
                $parentTitle = $parentProduct->title['en'] ?? '';
                $parsedValues = $this->extractOptionsFromVariationName($variationTitle, $parentTitle);

                // Map parsed values to parent's option names in order
                $parentOptions = $parentProduct->options()->pluck('values', 'name')->toArray();
                $flatParentOptionNames = array_keys($parentOptions);

                foreach ($parsedValues as $idx => $val) {
                    $optionName = $flatParentOptionNames[$idx] ?? null;
                    if ($optionName) {
                        $optionsForVariant[$optionName] = $val;
                    } else {
                        // If no matching option name, push into a numeric key to keep the value
                        $optionsForVariant['option_' . ($idx + 1)] = $val;
                    }
                }
            }

            // Build variant array for syncVariants
            $variantData = [
                'id' => null, // no id: will create new variant, but if SKU exists we try to update
                'title' => $record['Name'] ?? ($variationSku ?: collect($optionsForVariant)->implode(' / ')),
                'sku' => $variationSku,
                'barcode' => $record['Barcode'] ?? null,
                'image_id' => $variantImageId, // Use the first product image instead of null
                'price' => $price ?: 0,
                'compare_at_price' => $record['Compare at price'] ?? null,
                'cost' => $record['Cost'] ?? null,
                'stock_quantity' => $stockQuantity,
                'track_quantity' => $trackStock,
                'taxable' => true,
                'options' => $optionsForVariant,
            ];

            // If SKU present and variant exists, update instead of duplicating
            if (!empty($variationSku)) {
                $existingVariant = $parentProduct->variants()->where('sku', $variationSku)->first();
                if ($existingVariant) {
                    $variantData['id'] = $existingVariant->id;
                }
            } elseif (!empty($externalId)) {
                // try matching by external_id saved inside variant's raw_data if you store it — skipped here for brevity
            }

            // Use syncVariants (keeps or replaces variants) - we will merge single variant into existing ones
            // To avoid wiping out other variants, we fetch existing variants and merge/update.
            $this->syncSingleVariant($parentProduct, $variantData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception("Failed to process variation: " . $e->getMessage());
        }
    }

    /**
     * Get the first image ID from the parent product to use as variant image
     */
    protected function getFirstProductImageId(Product $product): ?int
    {
        try {
            // Assuming your Product model has a relationship to documents/images
            // Adjust the relationship name based on your actual implementation
            $firstImage = $product->documents()
                ->whereIn('document_type', ['main', 'gallery'])
                ->orderByRaw("FIELD(document_type, 'main', 'gallery')") // Prefer main image first
                ->orderBy('id')
                ->first();

            return $firstImage ? $firstImage->id : null;
        } catch (\Exception $e) {
            Log::warning('Failed to get first product image', [
                'product_id' => $product->id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Find existing product by external id, SKU, or slug derived from title.
     */
    protected function findExistingProduct($externalId = null, $sku = null, $title = null)
    {
        if ($externalId) {
            $p = Product::where('external_id', $externalId)->first();
            if ($p) return $p;
        }

        if ($sku) {
            $p = Product::where('sku', $sku)->first();
            if ($p) return $p;
        }

        if ($title) {
            $slug = Str::slug($title);
            $p = Product::where('slug', 'like', $slug . '%')->first();
            if ($p) return $p;
        }

        return null;
    }

    /**
     * Sync a single variant into the parent product without deleting other variants.
     * If id provided - update; if SKU matches existing - update; else create.
     */
    protected function syncSingleVariant(Product $product, array $variantData)
    {
        // Try to find by id if provided
        if (!empty($variantData['id'])) {
            $variant = ProductVariant::find($variantData['id']);
            if ($variant && $variant->product_id === $product->id) {
                $variant->update($this->prepareVariantPayload($variantData, $product));
                return $variant;
            }
        }

        // Try find by SKU
        if (!empty($variantData['sku'])) {
            $variant = $product->variants()->where('sku', $variantData['sku'])->first();
            if ($variant) {
                $variant->update($this->prepareVariantPayload($variantData, $product));
                return $variant;
            }
        }

        // Otherwise create - ensure we have an image_id
        $payload = $this->prepareVariantPayload($variantData, $product);
        
        // If no image_id was found, try to get the first product image again
        if (empty($payload['image_id'])) {
            $payload['image_id'] = $this->getFirstProductImageId($product);
        }
        
        return $product->variants()->create($payload);
    }

    protected function prepareVariantPayload(array $v, Product $product): array
    {
        // Build label from options if title missing
        $variantLabel = $v['title'] ?? '';
        if (empty($variantLabel) && !empty($v['options']) && is_array($v['options'])) {
            $variantLabel = collect($v['options'])->filter(fn($o) => !empty($o))->implode(' / ');
        }

        // generate SKU if not provided
        if (empty($v['sku'])) {
            $baseTitle = $product->title['en'] ?? 'Untitled';
            $skuTitle = trim($baseTitle . ' ' . $variantLabel);
            $generated = ProductVariant::generateUniqueSkuFromParent($product, $skuTitle, $v['id'] ?? null);
            $v['sku'] = $generated['sku'];
        } else {
            // ensure uniqueness via ProductVariant helper
            $generated = ProductVariant::generateUniqueSkuFromParent($product, $v['sku'], $v['id'] ?? null);
            $v['sku'] = $generated['sku'];
        }

        return [
            'title' => $variantLabel,
            'sku' => $v['sku'],
            'barcode' => $v['barcode'] ?? null,
            'price' => $v['price'] ?? 0,
            'compare_at_price' => $v['compare_at_price'] ?? null,
            'cost' => $v['cost'] ?? null,
            'stock_quantity' => $v['stock_quantity'] ?? 0,
            'track_quantity' => $v['track_quantity'] ?? false,
            'taxable' => $v['taxable'] ?? false,
            'options' => $v['options'] ?? [],
            'image_id' => $v['image_id'] ?? null, // This will be set in processVariation or syncSingleVariant
            'is_active' => true,
        ];
    }

    /**
     * Helper: pending file path derived from CSV path
     */
    protected function getPendingPathFromCsvPath(string $csvPath): string
    {
        $basename = pathinfo($csvPath, PATHINFO_BASENAME);
        $pendingFilename = 'pending_variations_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $basename) . '.json';
        return storage_path('app/imports/' . $pendingFilename);
    }

    protected function loadPendingVariations(string $pendingPath): array
    {
        if (file_exists($pendingPath)) {
            $contents = @file_get_contents($pendingPath);
            if ($contents) {
                $decoded = json_decode($contents, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return $decoded;
                }
            }
        }
        return [];
    }

    protected function savePendingVariations(string $pendingPath, array $pending)
    {
        // ensure directory exists
        $dir = dirname($pendingPath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        file_put_contents($pendingPath, json_encode($pending));
    }

    /**
     * parse stock quantity helpers (kept from your original controller)
     */
    protected function parseStockQuantity($record): int
    {
        if (!is_array($record)) {
            return 0;
        }

        $stock = $record['Stock'] ?? $record['Stock quantity'] ?? $record['Quantity'] ?? null;

        if ($stock === null || $stock === '') {
            return 0;
        }

        if (is_numeric($stock)) {
            return max(0, (int)$stock);
        }

        $stock = trim((string)$stock);
        if (is_numeric($stock)) {
            return max(0, (int)$stock);
        }

        return 0;
    }

    protected function determineStockStatus($record, int $stockQuantity): string
    {
        if (!is_array($record)) {
            return $stockQuantity > 0 ? 'in_stock' : 'out_of_stock';
        }

        $stockStatus = $record['In stock?'] ?? $record['Stock status'] ?? null;

        if ($stockStatus !== null) {
            $stockStatus = strtolower(trim((string)$stockStatus));

            if (in_array($stockStatus, ['instock', 'in stock', '1', 'yes', 'true'])) {
                return 'in_stock';
            } elseif (in_array($stockStatus, ['outofstock', 'out of stock', '0', 'no', 'false'])) {
                return 'out_of_stock';
            } elseif (in_array($stockStatus, ['onbackorder', 'on backorder'])) {
                return 'on_backorder';
            }
        }

        return $stockQuantity > 0 ? 'in_stock' : 'out_of_stock';
    }

    protected function shouldTrackStock($record): bool
    {
        if (!is_array($record)) {
            return false;
        }

        $manageStock = $record['Manage stock?'] ?? $record['Manage stock'] ?? null;

        if ($manageStock !== null) {
            $manageStock = strtolower(trim((string)$manageStock));
            return in_array($manageStock, ['yes', '1', 'true']);
        }

        $stockQuantity = $this->parseStockQuantity($record);
        return $stockQuantity > 0;
    }

    protected function cleanDescription(?string $raw): string
    {
        if (empty($raw)) return '';

        $html = str_replace(['\\r\\n', '\\n', '\\r', '\n', '\r\n'], "\n", $raw);
        $html = preg_replace('/\s+data-(start|end)="[^"]*"/i', '', $html);
        $html = preg_replace("/>\s+</", '><', $html);
        $html = preg_replace('/<p[^>]*>\s*<p[^>]*>/', '<p>', $html);
        $html = preg_replace('/<\/p>\s*<\/p>/', '</p>', $html);
        return trim($html);
    }

    // Image processing kept mostly the same but more defensive
    protected function processProductImages(Product $product, string $imagesRaw): void
    {
        $imageUrls = array_filter(array_map('trim', explode(',', (string)$imagesRaw)));

        foreach ($imageUrls as $index => $url) {
            if (empty($url)) continue;

            try {
                $resp = Http::timeout(15)->get($url);
                if ($resp->successful()) {
                    $content = $resp->body();

                    $imageInfo = @getimagesizefromstring($content);
                    if (!$imageInfo) {
                        Log::warning("Downloaded content is not an image", ['url' => $url]);
                        continue;
                    }

                    $mime = $imageInfo['mime'];
                    $ext = $this->getExtensionFromMime($mime);

                    $filename = 'documents/' . Str::uuid() . '.' . $ext;
                    Storage::disk('public')->put($filename, $content);
                    $fileSize = Storage::disk('public')->size($filename);

                    Document::create([
                        'name' => basename($url),
                        'file_path' => $filename,
                        'file_type' => $ext,
                        'document_type' => $index === 0 ? 'main' : 'gallery',
                        'documentable_id' => $product->id,
                        'documentable_type' => Product::class,
                        'mime_type' => $mime,
                        'size' => $fileSize,
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning("Image download failed for URL: {$url}", ['error' => $e->getMessage()]);
            }
        }
    }

    protected function getExtensionFromMime(string $mime): string
    {
        $mimeMap = [
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/svg+xml' => 'svg',
        ];

        return $mimeMap[$mime] ?? 'jpg';
    }

    /**
     * Parse attributes for a parent product into options array
     */
    protected function extractOptionsFromAttributes(array $record): array
    {
        $options = [];

        for ($i = 1; $i <= 10; $i++) {
            $attrName = $record["Attribute {$i} name"] ?? null;
            $attrValues = $record["Attribute {$i} value(s)"] ?? null;

            if ($attrName && $attrValues) {
                // Some CSVs put single value for parent as comma separated; others may have pipes
                $values = array_filter(array_map('trim', preg_split('/[,|]/', $attrValues)));
                if (!empty($values)) {
                    $options[] = [
                        'name' => $attrName,
                        'values' => $values,
                    ];
                }
            }
        }

        return $options;
    }

    protected function extractOptionsFromVariationName(string $variationName, string $parentName): array
    {
        $optionsPart = trim(str_ireplace($parentName, '', $variationName));
        $optionsPart = trim($optionsPart, ' -:');
        if ($optionsPart === '') return [];

        $optionValues = preg_split('/[,|]/', $optionsPart);
        $optionValues = array_map('trim', $optionValues);
        $optionValues = array_filter($optionValues);

        return array_values($optionValues);
    }

    protected function extractParentId(?string $parentField): ?string
    {
        if (empty($parentField)) {
            return null;
        }

        // Handle "id:9358" format
        if (preg_match('/id:(\d+)/i', $parentField, $matches)) {
            return $matches[1];
        }

        // Handle numeric values
        if (is_numeric($parentField)) {
            return (string)$parentField;
        }

        // Try to extract any numbers from the field
        if (preg_match('/(\d+)/', $parentField, $matches)) {
            return $matches[1];
        }

        return null;
    }
}