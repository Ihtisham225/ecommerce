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
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::query()
                ->select(['id', 'title', 'is_active', 'is_featured', 'created_at'])->latest();
                
            // Status filter
            if ($request->filled('status')) {
                if ($request->status === 'active') {
                    $products->where(function ($q) {
                        $q->where('is_active', true)
                        ->orWhere('is_active', 1)
                        ->orWhere('is_active', '1');
                    });
                } elseif ($request->status === 'draft') {
                    $products->where(function ($q) {
                        $q->where('is_active', false)
                        ->orWhere('is_active', 0)
                        ->orWhere('is_active', '0');
                    });
                }
            }
            // Featured filter
            if ($request->filled('featured')) {
                if ($request->featured == 1 || $request->featured === '1' || $request->featured === true) {
                    $products->where(function ($q) {
                        $q->whereIn('is_featured', [true, 1, '1']);
                    });
                } elseif ($request->featured == 0 || $request->featured === '0' || $request->featured === false) {
                    $products->where(function ($q) {
                        $q->whereIn('is_featured', [false, 0, '0']);
                    });
                }
            }
            
            // Date range filter
            if ($request->has('date_range') && $request->date_range !== '') {
                $now = now();
                switch ($request->date_range) {
                    case 'today':
                        $products->whereDate('created_at', $now->toDateString());
                        break;
                    case 'yesterday':
                        $products->whereDate('created_at', $now->subDay()->toDateString());
                        break;
                    case 'week':
                        $products->whereBetween('created_at', [
                            $now->startOfWeek(),
                            $now->endOfWeek()
                        ]);
                        break;
                    case 'month':
                        $products->whereBetween('created_at', [
                            $now->startOfMonth(),
                            $now->endOfMonth()
                        ]);
                        break;
                    case 'year':
                        $products->whereBetween('created_at', [
                            $now->startOfYear(),
                            $now->endOfYear()
                        ]);
                        break;
                }
            }
            
            return DataTables::of($products)
                ->addColumn('title', function ($row) {
                    try {
                        $title = $row->translate('title');
                    } catch (\Throwable $e) {
                        $title = $row->title ?? null;
                    }
                    return e($title ?: 'Untitled');
                })
                ->addColumn('status', function ($row) {
                    $isActive = (bool) $row->is_active;
                    $label = $isActive ? 'Active' : 'Draft';
                    $color = $isActive ? 'green' : 'gray';
                    $icon = $isActive
                        ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                        : 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z';

                    return <<<HTML
                        <button data-id="{$row->id}" data-type="status"
                                class="toggle-status inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium 
                                    bg-{$color}-100 text-{$color}-800 dark:bg-{$color}-900 dark:text-{$color}-200 
                                    hover:bg-{$color}-200 dark:hover:bg-{$color}-800 transition duration-150 ease-in-out">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{$icon}"></path>
                            </svg>
                            {$label}
                        </button>
                    HTML;
                })
                ->addColumn('featured', function ($row) {
                    $isFeatured = (bool) $row->is_featured;
                    $label = $isFeatured ? 'Featured' : 'Standard';
                    $color = $isFeatured ? 'yellow' : 'gray';
                    $icon = $isFeatured
                        ? 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'
                        : 'M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z';

                    return <<<HTML
                        <button data-id="{$row->id}" data-type="featured"
                                class="toggle-feature inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium 
                                    bg-{$color}-100 text-{$color}-800 dark:bg-{$color}-900 dark:text-{$color}-200 
                                    hover:bg-{$color}-200 dark:hover:bg-{$color}-800 transition duration-150 ease-in-out">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{$icon}"></path>
                            </svg>
                            {$label}
                        </button>
                    HTML;
                })
                ->addColumn('actions', function ($row) {
                    $showUrl = route('admin.products.show', $row->id);
                    $editUrl = route('admin.products.edit', $row->id);

                    return <<<HTML
                        <div class="flex justify-center gap-2">
                            <a href="{$showUrl}" 
                            class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition duration-150 ease-in-out">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 
                                        9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 
                                        0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View
                            </a>
                            <a href="{$editUrl}" 
                            class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-md transition duration-150 ease-in-out">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 
                                        2h11a2 2 0 002-2v-5m-1.414-9.414a2 
                                        2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>
                            <button data-id="{$row->id}" 
                                    class="delete-btn inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md transition duration-150 ease-in-out">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 
                                        21H7.862a2 2 0 01-1.995-1.858L5 
                                        7m5 4v6m4-6v6m1-10V4a1 1 
                                        0 00-1-1h-4a1 1 0 00-1 
                                        1v3M4 7h16"></path>
                                </svg>
                                Delete
                            </button>
                        </div>
                    HTML;
                })
                ->editColumn('created_at', fn($row) => $row->created_at?->format('Y-m-d H:i'))
                ->rawColumns(['status', 'featured', 'actions'])
                ->make(true);
        }

        return view('admin.products.index');
    }

    public function create()
    {
        $draft = Product::create([
            'title' => ['en' => 'Untitled Product'], // minimal JSON
            'is_active' => 0,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.products.edit', $draft->id);
    }

    public function show(Product $product)
    {
        $product->load([
            'categories:id,name',
            'brand:id,name',
            'collections:id,title',
            'variants:id,product_id,title,sku,barcode,price,compare_at_price,cost,stock_quantity,track_quantity,taxable,options,image_id',
            'variants.image:id,file_path',
            'documents',
            'options:id,product_id,name,values',
            'shipping',
            'tags:id,name,slug',
        ]);

        $storeSetting = \App\Models\StoreSetting::where('user_id', auth()->id())->first();
        $currencyCode = $storeSetting?->currency_code ?? 'USD';
        $currencySymbol = $currencySymbols[$currencyCode] ?? $currencyCode;

        return view('admin.products.show', compact('product', 'currencySymbol', 'storeSetting'));
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

        // Currency
        $currencySymbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'PKR' => '₨',
            'INR' => '₹',
            'AED' => 'د.إ',
            'SAR' => '﷼',
            'CAD' => '$',
            'AUD' => '$',
            'KWD' => 'K.D',
        ];

        $storeSetting = \App\Models\StoreSetting::where('user_id', auth()->id())->first();
        $currencyCode = $storeSetting?->currency_code ?? 'USD';
        $currencySymbol = $currencySymbols[$currencyCode] ?? $currencyCode;

        return view('admin.products.form', compact('product', 'currencySymbol', 'storeSetting'));
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
        
        // convert empty strings to nulls
        foreach (['weight', 'width', 'height', 'length'] as $f) {
            if ($request->has($f) && in_array($request->$f, ['null', ''])) {
                $request->merge([$f => null]);
            }
        }

        //organization data
        if ($request->has('organizationData') && is_string($request->organizationData)) {
            $decoded = json_decode($request->organizationData, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request->merge(['organizationData' => $decoded]);
            } else {
                $request->merge(['organizationData' => []]);
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
            'cost' => 'nullable|numeric|min:0',
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
                'cost' => $validated['cost'] ?? $product->cost,
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

    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|string|in:publish,unpublish,feature,unfeature,delete',
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:products,id',
        ]);

        $action = $validated['action'];
        $ids = $validated['ids'];

        // optional: authorize admin actions here
        // $this->authorize('update', Product::class);

        DB::beginTransaction();

        try {
            switch ($action) {
                case 'publish':
                    Product::whereIn('id', $ids)->update(['is_active' => true]);
                    $message = 'Selected products have been published.';
                    break;

                case 'unpublish':
                    Product::whereIn('id', $ids)->update(['is_active' => false]);
                    $message = 'Selected products have been unpublished.';
                    break;

                case 'feature':
                    Product::whereIn('id', $ids)->update(['is_featured' => true]);
                    $message = 'Selected products are now featured.';
                    break;

                case 'unfeature':
                    Product::whereIn('id', $ids)->update(['is_featured' => false]);
                    $message = 'Selected products have been unfeatured.';
                    break;

                case 'delete':
                    Product::whereIn('id', $ids)->delete();
                    $message = 'Selected products have been deleted.';
                    break;

                default:
                    return response()->json(['success' => false, 'message' => 'Invalid action.']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while performing the action.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle a single product’s "active" or "featured" status
     */
    public function toggle(Request $request, Product $product)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:status,featured',
        ]);

        $type = $validated['type'];

        if ($type === 'status') {
            $product->is_active = !$product->is_active;
        } elseif ($type === 'featured') {
            $product->is_featured = !$product->is_featured;
        }

        $product->save();

        return response()->json([
            'success' => true,
            'message' => ucfirst($type) . ' updated successfully.',
            'product' => $product,
        ]);
    }

    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            // Store product name for success message
            $productName = $product->translate('title', 'en') ?? 'Product';
            $variantCount = $product->variants()->count();

            Log::info("Starting product deletion", [
                'product_id' => $product->id,
                'product_name' => $productName,
                'variant_count' => $variantCount
            ]);

            // Arrays to collect file paths for cleanup
            $filesToDelete = [];
            $directoriesToCleanup = [];

            // Delete variants and their related data
            if ($product->variants()->exists()) {
                Log::info("Processing variants for deletion", [
                    'product_id' => $product->id,
                    'variant_count' => $product->variants()->count()
                ]);
                
                $product->variants()->each(function ($variant) use (&$filesToDelete, &$directoriesToCleanup) {
                    Log::info("Processing variant", [
                        'variant_id' => $variant->id,
                        'variant_title' => $variant->title,
                        'has_image' => !is_null($variant->image)
                    ]);

                    // Delete variant-specific images
                    if ($variant->image) {
                        Log::info("Variant has image, collecting files", [
                            'variant_id' => $variant->id,
                            'image_id' => $variant->image->id
                        ]);
                        
                        $this->collectDocumentFiles($variant->image, $filesToDelete);
                        
                        // Track directories for potential cleanup
                        if ($variant->image->path) {
                            $dir = dirname($variant->image->path);
                            if (!in_array($dir, $directoriesToCleanup)) {
                                $directoriesToCleanup[] = $dir;
                            }
                        }
                        
                        // Delete the image record from database
                        $variant->image->delete();
                        Log::info("Variant image record deleted", [
                            'variant_id' => $variant->id,
                            'image_id' => $variant->image->id
                        ]);
                    }
                    
                    // Delete variant stock records
                    if ($variant->stock()->exists()) {
                        $variant->stock()->delete();
                    }
                    
                    $variant->delete();
                    Log::info("Variant deleted", ['variant_id' => $variant->id]);
                });
            }

            // Delete product options
            if ($product->options()->exists()) {
                $product->options()->delete();
                Log::info("Product options deleted", ['product_id' => $product->id]);
            }

            // Delete product shipping information
            if ($product->shipping) {
                $product->shipping->delete();
                Log::info("Product shipping deleted", ['product_id' => $product->id]);
            }

            // Detach relationships (many-to-many)
            $product->tags()->detach();
            $product->categories()->detach();
            $product->collections()->detach();
            Log::info("Relationships detached", ['product_id' => $product->id]);

            // Delete product images/documents and collect file paths
            if ($product->documents()->exists()) {
                $documentCount = $product->documents()->count();
                Log::info("Processing product documents", [
                    'product_id' => $product->id,
                    'document_count' => $documentCount
                ]);
                
                $product->documents()->each(function ($document) use (&$filesToDelete, &$directoriesToCleanup) {
                    Log::info("Processing document", [
                        'document_id' => $document->id,
                        'document_type' => $document->document_type,
                        'path' => $document->path
                    ]);
                    
                    $this->collectDocumentFiles($document, $filesToDelete);
                    
                    // Track directories for potential cleanup
                    if ($document->path) {
                        $dir = dirname($document->path);
                        if (!in_array($dir, $directoriesToCleanup)) {
                            $directoriesToCleanup[] = $dir;
                        }
                    }
                    
                    $document->delete();
                    Log::info("Document record deleted", ['document_id' => $document->id]);
                });
            } else {
                Log::info("No documents found for product", ['product_id' => $product->id]);
            }

            // Delete translations
            if ($product->translations()->exists()) {
                $product->translations()->delete();
                Log::info("Translations deleted", ['product_id' => $product->id]);
            }

            // Delete the product itself
            $product->delete();
            Log::info("Product record deleted", ['product_id' => $product->id]);

            DB::commit();
            Log::info("Database transaction committed", ['product_id' => $product->id]);

            // Log collected files before deletion
            Log::info("Files collected for deletion", [
                'product_id' => $product->id,
                'file_count' => count($filesToDelete),
                'files' => $filesToDelete
            ]);

            // Now delete all collected files from storage
            $deletionResult = $this->deleteFilesFromStorage($filesToDelete);
            
            // Optional: Clean up empty directories
            $this->cleanupEmptyDirectories($directoriesToCleanup);

            Log::info("Product deletion completed", [
                'product_id' => $product->id,
                'files_deleted' => $deletionResult['deleted_count'],
                'files_failed' => $deletionResult['failed_count']
            ]);

            return response()->json([
                'success' => true,
                'message' => "Product '{$productName}' and all related data deleted successfully.",
                'files_deleted' => $deletionResult['deleted_count'],
                'files_failed' => $deletionResult['failed_count'],
                'variants_deleted' => $variantCount,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Product deletion failed', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product. Please try again.',
            ], 500);
        }
    }

    /**
     * Collect all file paths from a document
     */
    private function collectDocumentFiles($document, array &$filesToDelete)
    {
        $fileFields = ['path', 'thumbnail_path', 'original_path', 'small_path', 'medium_path', 'large_path'];
        
        foreach ($fileFields as $field) {
            if (!empty($document->{$field})) {
                $filesToDelete[] = $document->{$field};
                Log::info("Collected file path", [
                    'document_id' => $document->id,
                    'field' => $field,
                    'path' => $document->{$field}
                ]);
            }
        }
    }

    /**
     * Delete files from storage with detailed reporting
     */
    private function deleteFilesFromStorage(array $files): array
    {
        $result = [
            'deleted_count' => 0,
            'failed_count' => 0,
            'failed_files' => []
        ];

        if (empty($files)) {
            Log::info("No files to delete from storage");
            return $result;
        }

        // Remove duplicates
        $files = array_unique($files);
        
        Log::info("Starting file deletion from storage", [
            'total_files' => count($files),
            'files' => $files
        ]);

        // Check storage disk configuration
        $disk = config('filesystems.default');
        Log::info("Storage disk configuration", [
            'default_disk' => $disk,
            'disks' => config('filesystems.disks.'.$disk)
        ]);

        foreach ($files as $filePath) {
            try {
                // Skip empty paths
                if (empty($filePath)) {
                    Log::warning("Skipping empty file path");
                    continue;
                }

                Log::info("Attempting to delete file", ['file_path' => $filePath]);

                // Check if file exists and delete it
                if (Storage::exists($filePath)) {
                    Log::info("File exists, deleting", ['file_path' => $filePath]);
                    Storage::delete($filePath);
                    $result['deleted_count']++;
                    Log::info("File deleted successfully", ['file_path' => $filePath]);
                } else {
                    Log::warning("File does not exist in storage", ['file_path' => $filePath]);
                }
            } catch (\Exception $e) {
                Log::error("Failed to delete file", [
                    'file_path' => $filePath,
                    'error' => $e->getMessage()
                ]);
                $result['failed_count']++;
                $result['failed_files'][] = $filePath;
            }
        }

        Log::info("File deletion completed", [
            'deleted_count' => $result['deleted_count'],
            'failed_count' => $result['failed_count'],
            'failed_files' => $result['failed_files']
        ]);

        return $result;
    }

    /**
     * Clean up empty directories
     */
    private function cleanupEmptyDirectories(array $directories)
    {
        Log::info("Starting directory cleanup", ['directories' => $directories]);
        
        foreach ($directories as $directory) {
            try {
                Log::info("Checking directory", ['directory' => $directory]);
                
                // Check if directory exists and is empty
                if (Storage::exists($directory)) {
                    $allFiles = Storage::allFiles($directory);
                    Log::info("Directory contents", [
                        'directory' => $directory,
                        'file_count' => count($allFiles)
                    ]);
                    
                    if (empty($allFiles)) {
                        Storage::deleteDirectory($directory);
                        Log::info("Directory deleted", ['directory' => $directory]);
                    } else {
                        Log::info("Directory not empty, skipping", ['directory' => $directory]);
                    }
                } else {
                    Log::info("Directory does not exist", ['directory' => $directory]);
                }
            } catch (\Exception $e) {
                Log::error("Failed to cleanup directory", [
                    'directory' => $directory,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
