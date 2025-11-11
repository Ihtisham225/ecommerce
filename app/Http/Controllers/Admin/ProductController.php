<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Document;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::query()->select(['id', 'sku', 'price', 'is_active', 'is_featured', 'created_at']);
            return DataTables::of($products)
                ->addColumn('title', function ($row) {
                    return $row->translate('title') ?? 'Untitled';
                })
                ->addColumn('status', function ($row) {
                    if ($row->is_active) {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Active</span>';
                    }
                    return '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700">Draft</span>';
                })
                ->addColumn('featured', function ($row) {
                    return $row->is_featured ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Featured</span>' : '';
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.products.edit', $row->id);
                    return view('admin.products.partials._datatable_actions', compact('editUrl','row'))->render();
                })
                ->rawColumns(['status','featured','actions'])
                ->make(true);
        }

        return view('admin.products.index');
    }

    public function create()
    {
        $existing = Product::where('is_active', 0)
            ->where('created_by', Auth::id())
            ->latest()
            ->first();

        if ($existing) {
            return redirect()->route('admin.products.edit', $existing->id);
        }

        $draft = Product::create([
            'title' => ['en' => 'Untitled Product'], // minimal JSON
            'is_active' => 0,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.products.edit', $draft->id);
    }

    public function edit(Product $product)
    {
        $product->load([
            'categories:id,name',
            'brand:id,name',
            'collections:id,title',  // load collections
            'variants:id,product_id,title,sku,barcode,price,compare_at_price,cost,stock_quantity,track_quantity,taxable,options,image_id',
            'variants.image:id,file_path', // optional: only needed fields
            'documents',
            'options:id,product_id,name,values',
            'shipping',
            'tags:id,name,slug', // load tags
        ]);

        // Ensure options and translations are arrays
        $product->options = $product->options ?? [];
        $product->has_options = (bool) $product->has_options;
        $product->title = $product->title ?? ['en' => ''];
        $product->description = $product->description ?? ['en' => ''];

        // Flatten shipping data for Alpine
        $product->requires_shipping = $product->shipping?->requires_shipping ?? false;
        $product->weight = $product->shipping?->weight ?? null;
        $product->width = $product->shipping?->width ?? null;
        $product->height = $product->shipping?->height ?? null;
        $product->length = $product->shipping?->length ?? null;

        // Flatten organization sidebar data for Alpine
        $product->category_id = $product->categories->first()?->id ?? null;
        $product->brand_id = $product->brand?->id ?? null;
        $product->collection_id = $product->collections->first()?->id ?? null;
        $product->tags = $product->tags->pluck('name')->toArray();
        $product->is_active = (bool) $product->is_active;
        $product->is_featured = (bool) $product->is_featured;

        return view('admin.products.form', compact('product'));
    }

    /**
     * Autosave (for drafts). Keeps product as draft (is_active = 0).
     */
    public function autosave(Request $request, Product $product)
    {
        // 🧩 Convert JSON strings back to arrays before validation
        foreach (['title', 'description'] as $field) {
            if ($request->has($field) && is_string($request->$field)) {
                $decoded = json_decode($request->$field, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $request->merge([$field => $decoded]);
                }
            }
        }
        
        $validated = $request->validate([
            'title' => 'nullable|array',
            'title.*' => 'nullable|string|max:191',
            'description' => 'nullable|array',
            'description.*' => 'nullable|string',
            'sku' => ['nullable', 'string', Rule::unique('products', 'sku')->ignore($product->id)],
            'price' => 'nullable|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
            'track_stock' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'has_options' => 'nullable|boolean',
            'charge_tax' => 'nullable|boolean',

            // Shipping fields
            'requires_shipping' => 'nullable|boolean',
            'weight' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',

            // Images
            'new_main_image' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:5120',
            'new_gallery.*'  => 'nullable|file|mimes:jpeg,png,jpg,webp|max:5120',
            'gallery_remove_ids' => 'nullable|array',
            'gallery_remove_ids.*' => 'integer|exists:documents,id',
            'existing_main_document_id' => 'nullable|integer|exists:documents,id',
            'existing_gallery_ids' => 'nullable|array',
            'existing_gallery_ids.*' => 'integer|exists:documents,id',

            //variants and options
            'options_json' => 'nullable|string',
            'variants_json' => 'nullable|string',

            //organization sidebar
            'organizationData'          => 'nullable|array',
            'organizationData.category_id'   => 'nullable|integer|exists:categories,id',
            'organizationData.brand_id'      => 'nullable|integer|exists:brands,id',
            'organizationData.collection_id' => 'nullable|integer|exists:collections,id',
            'organizationData.tags'          => 'nullable|string',
            'organizationData.is_active'     => 'nullable|boolean',
            'organizationData.is_featured'   => 'nullable|boolean',

            // SEO Fields
            'meta_title' => 'nullable|string|max:191',
            'meta_description' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $validatedTitle = $validated['title']['en'] ?? $product->title['en'] ?? 'Untitled';
            $skuAndSlug = Product::generateUniqueSkuAndSlug($validatedTitle, $product->id);
                
            // extract organization data
            $org = $validated['organizationData'] ?? [];

            $categoryId   = $org['category_id'] ?? null;
            $brandId      = $org['brand_id'] ?? null;
            $collectionId = $org['collection_id'] ?? null;
            $isActive     = isset($org['is_active']) ? (bool) $org['is_active'] : $product->is_active;
            $isFeatured   = isset($org['is_featured']) ? (bool) $org['is_featured'] : $product->is_featured;
            $tags         = $org['tags'] ?? null;

            // 🔹 Update core fields
            $product->update([
                'sku' => $skuAndSlug['sku'],
                'slug' => $skuAndSlug['slug'],
                'price' => $validated['price'] ?? $product->price,
                'compare_at_price' => $validated['compare_at_price'] ?? $product->compare_at_price,
                'stock_quantity' => $validated['stock_quantity'] ?? $product->stock_quantity,
                'track_stock' => (bool) ($validated['track_stock'] ?? $product->track_stock),
                'brand_id'       => $brandId,
                'is_active'      => $isActive,
                'is_featured'    => $isFeatured,
                'is_published' => $isActive,
                'published_at' => now(),
                'has_options' => (bool) ($validated['has_options'] ?? false),
                'charge_tax' => $validated['charge_tax'] ?? $product->charge_tax,
                'type' => ($validated['has_options'] ?? false) ? 'variable' : 'simple',
            ]);

            // 🔹 Category sync
            if ($categoryId) {
                $product->categories()->sync([$categoryId]);
            }

            // 🔹 Collection sync
            if ($collectionId) {
                $product->collections()->sync([$collectionId]);
            }

            // 🔹 Tags handling (comma-separated string)
            if (!empty($tags)) {
                $tagsArray = collect(explode(',', $tags))
                    ->map(fn($tag) => trim($tag))
                    ->filter()
                    ->unique();

                $tagIds = $tagsArray->map(fn($tagName) => \App\Models\Tag::firstOrCreate(
                    ['slug' => \Illuminate\Support\Str::slug($tagName)],
                    ['name' => $tagName]
                )->id);

                $product->tags()->sync($tagIds);
            } else {
                $product->tags()->sync([]); // clear tags if empty
            }

            // store shipping
            $product->shipping()->updateOrCreate(
                ['product_id' => $product->id], // lookup condition
                [
                    'requires_shipping' => (bool) ($validated['requires_shipping'] ?? false),
                    'weight' => ($validated['requires_shipping'] ?? false) ? ($validated['weight'] ?? null) : null,
                    'width'  => ($validated['requires_shipping'] ?? false) ? ($validated['width'] ?? null) : null,
                    'height' => ($validated['requires_shipping'] ?? false) ? ($validated['height'] ?? null) : null,
                    'length' => ($validated['requires_shipping'] ?? false) ? ($validated['length'] ?? null) : null,
                ]
            );

            // 🔹 Translations
            if ($request->has('title')) {
                $product->setTranslationsFromRequest($request->only(['title', 'description']));
            }

            // 🔹 Category sync
            if ($request->filled('category_id')) {
                $product->categories()->sync([$request->input('category_id')]);
            }

            // 🔹 Images
            $this->syncProductImages($product, $request, $validated);

            // 🔹 Variants + Options
            $variants = json_decode($request->input('variants_json', '[]'), true);
            $options = json_decode($request->input('options_json', '[]'), true);

            $validatedVariants = collect($variants)->map(fn($v) => [
                'id' => $v['id'] ?? null,
                'title' => $v['title'] ?? null,
                'sku' => $v['sku'] ?? null,
                'barcode' => $v['barcode'] ?? null,
                'image_id' => $v['image_id'] ?? null,
                'price' => $v['price'] ?? 0,
                'compare_at_price' => $v['compare_at_price'] ?? null,
                'cost' => $v['cost'] ?? null,
                'stock_quantity' => $v['quantity'] ?? 0,
                'track_quantity' => $v['track_quantity'] ?? false,
                'taxable' => $v['taxable'] ?? false,
                'options' => $v['options'] ?? [],
            ])->toArray();

            // sync all opions like color, size
            $this->syncOptions($product, $options);

            // sync all variant records (create/update/delete)
            $this->syncVariants($product, $validatedVariants);

            // SEO Handling
            $seoTitle = $validated['meta_title']
                ?? $product->title['en']
                ?? $product->title[array_key_first($product->title)] ?? null;

            $seoDescription = $validated['meta_description']
                ?? strip_tags(Str::limit($product->description['en'] ?? '', 160))
                ?? null;

            $product->update([
                'meta_title' => $seoTitle,
                'meta_description' => $seoDescription,
            ]);
            
            DB::commit();

            // ✅ Reload product with relations for frontend
            $product->refresh()->load([
                'categories',
                'brand',
                'collections',
                'variants:id,product_id,title,sku,barcode,price,compare_at_price,cost,stock_quantity,track_quantity,taxable,options,image_id',
                'variants.image:id,file_path', // only DB columns
                'documents', // optionally select id, file_path if you want
                'options',
                'shipping',
                'tags',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Draft autosaved successfully.',
                'updated_at' => now()->toDateTimeString(),
                'product' => $product,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Autosave failed.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sync product options (like Color, Size)
     */
    protected function syncOptions(Product $product, array $options)
    {
        // Collect IDs for cleanup later
        $incomingIds = [];

        foreach ($options as $opt) {
            // Skip empty option names (e.g. user deleted all)
            if (empty($opt['name'])) {
                continue;
            }

            $data = [
                'name'   => $opt['name'],
                'values' => $opt['values'] ?? [],
            ];

            // Update existing option
            if (!empty($opt['id'])) {
                $option = $product->options()->find($opt['id']);
                if ($option) {
                    $option->update($data);
                    $incomingIds[] = $option->id;
                    continue;
                }
            }

            // Create new
            $newOption = $product->options()->create($data);
            $incomingIds[] = $newOption->id;
        }

        // Remove deleted options
        if (!empty($incomingIds)) {
            $product->options()->whereNotIn('id', $incomingIds)->delete();
        } else {
            $product->options()->delete();
        }
    }

    /**
     * Sync variants array: each variant item may have id (update) or no id (create).
     * Each item: ['id'=>..., 'sku'=>'', 'price'=>'', 'stock_quantity'=>int, 'options'=>[]]
     */
    protected function syncVariants(Product $product, array $variants)
    {
        $incomingIds = [];

        foreach ($variants as $v) {
            // Build human-readable variant label (e.g. "Red / XL")
            $variantLabel = '';
            if (!empty($v['options']) && is_array($v['options'])) {
                $variantLabel = collect($v['options'])
                    ->filter(fn($opt) => !empty($opt))
                    ->implode(' / ');
            }

            // Generate unique SKU based on parent product SKU + variant title/options
            $baseTitle = $product->title['en'] ?? 'Untitled';
            $variantTitle = $v['title'] ?? $variantLabel ?? '';
            $skuTitle = trim($baseTitle . ' ' . $variantTitle);
            $generated = ProductVariant::generateUniqueSkuFromParent($product, $skuTitle, $v['id'] ?? null);

            $data = [
                'title'            => $v['title'] ?? $variantLabel,
                'sku'              => $generated['sku'], // Auto-generated only
                'barcode'          => $v['barcode'] ?? null,
                'price'            => $v['price'] ?? 0,
                'compare_at_price' => $v['compare_at_price'] ?? null,
                'cost'             => $v['cost'] ?? null,
                'stock_quantity'   => $v['quantity'] ?? 0,
                'track_quantity'   => $v['track_quantity'] ?? false,
                'taxable'          => $v['taxable'] ?? false,
                'options'          => $v['options'] ?? [],
                'image_id'         => $v['image_id'] ?? null,
                'is_active'        => true,
            ];

            if (!empty($v['id'])) {
                $variant = ProductVariant::find($v['id']);
                if ($variant && $variant->product_id === $product->id) {
                    $variant->update($data);
                    $incomingIds[] = $variant->id;
                    continue;
                }
            }

            $newVariant = $product->variants()->create($data);
            $incomingIds[] = $newVariant->id;
        }

        // Delete removed variants
        if (!empty($incomingIds)) {
            $product->variants()->whereNotIn('id', $incomingIds)->delete();
        } else {
            $product->variants()->delete();
        }
    }

    private function syncProductImages(Product $product, Request $request, array $validated): void
    {
        // 1️⃣ Remove selected gallery images
        if (!empty($validated['gallery_remove_ids'])) {
            $docsToRemove = Document::whereIn('id', $validated['gallery_remove_ids'])
                ->where('documentable_type', Product::class)
                ->where('documentable_id', $product->id)
                ->get();

            foreach ($docsToRemove as $doc) {
                if ($doc->file_path && Storage::disk('public')->exists($doc->file_path)) {
                    Storage::disk('public')->delete($doc->file_path);
                }
                $doc->delete();
            }
        }

        // 2️⃣ Reassign existing main image
        if (!empty($validated['existing_main_document_id'])) {
            $product->documents()->where('document_type', 'main')->update(['document_type' => 'gallery']);
            Document::where('id', $validated['existing_main_document_id'])
                ->update([
                    'documentable_id' => $product->id,
                    'documentable_type' => Product::class,
                    'document_type' => 'main',
                ]);
        }

        // 3️⃣ Upload new main image
        if ($request->hasFile('new_main_image')) {
            if ($oldMain = $product->documents()->where('document_type', 'main')->first()) {
                if ($oldMain->file_path && Storage::disk('public')->exists($oldMain->file_path)) {
                    Storage::disk('public')->delete($oldMain->file_path);
                }
                $oldMain->delete();
            }

            $file = $request->file('new_main_image');
            $path = $file->store('documents', 'public');

            $product->documents()->create([
                'name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'document_type' => 'main',
            ]);
        }

        // 4️⃣ Upload new gallery images
        if ($request->hasFile('new_gallery')) {
            foreach ($request->file('new_gallery') as $file) {
                $path = $file->store('documents', 'public');

                $product->documents()->create([
                    'name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'document_type' => 'gallery',
                ]);
            }
        }

        // 5️⃣ Attach existing gallery images
        if (!empty($validated['existing_gallery_ids'])) {
            Document::whereIn('id', $validated['existing_gallery_ids'])
                ->update([
                    'documentable_id' => $product->id,
                    'documentable_type' => Product::class,
                    'document_type' => 'gallery',
                ]);
        }
    }
}
