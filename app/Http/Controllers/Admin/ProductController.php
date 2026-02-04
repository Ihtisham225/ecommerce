<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StoreSetting;
use App\Models\GoogleMerchantSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;
use App\Services\GoogleMerchantService;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected $merchantService;
    protected $googleSettings;

    public function __construct(GoogleMerchantService $merchantService)
    {
        $this->merchantService = $merchantService;
        $this->googleSettings = GoogleMerchantSetting::first();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::query()
            ->select(['id', 'title', 'is_active', 'is_featured', 'stock_status', 'google_last_synced', 'google_status', 'created_at'])->latest();

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

            // Stock status filter
            if ($request->filled('stock_status')) {
                $products->where('stock_status', $request->stock_status);
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
                ->addColumn('stock_status', function ($row) {
                    $isInStock = $row->stock_status === 'in_stock';
                    $label = $isInStock ? 'In Stock' : 'Out of Stock';
                    $color = $isInStock ? 'green' : 'red';
                    $icon = $isInStock
                        ? 'M5 13l4 4L19 7'
                        : 'M6 18L18 6M6 6l12 12';

                    return <<<HTML
                        <button data-id="{$row->id}" data-type="stock_status"
                                class="toggle-stock-status inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium 
                                    bg-{$color}-100 text-{$color}-800 dark:bg-{$color}-900 dark:text-{$color}-200 
                                    hover:bg-{$color}-200 dark:hover:bg-{$color}-800 transition duration-150 ease-in-out">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{$icon}"></path>
                            </svg>
                            {$label}
                        </button>
                    HTML;
                })
                ->addColumn('google_sync', function ($row) {
                    $status = $row->google_status ?? 'not_synced';
                    $lastSynced = $row->google_last_synced;
                    
                    $statusLabels = [
                        'active' => 'Active',
                        'pending' => 'Pending',
                        'error' => 'Error',
                        'disapproved' => 'Disapproved',
                        'not_synced' => 'Not Synced'
                    ];
                    
                    $statusColors = [
                        'active' => 'green',
                        'pending' => 'yellow',
                        'error' => 'red',
                        'disapproved' => 'orange',
                        'not_synced' => 'gray'
                    ];
                    
                    $label = $statusLabels[$status] ?? 'Not Synced';
                    $color = $statusColors[$status] ?? 'gray';
                    
                    $statusHtml = <<<HTML
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                    bg-{$color}-100 text-{$color}-800 dark:bg-{$color}-900 dark:text-{$color}-200">
                            {$label}
                        </span>
                    HTML;
                    
                    $lastSyncHtml = '';
                    if ($lastSynced) {
                        $lastSyncHtml = <<<HTML
                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Last: {$lastSynced->format('M d, H:i')}
                            </div>
                        HTML;
                    }
                    
                    $syncButton = <<<HTML
                        <button data-id="{$row->id}" 
                                class="sync-google-btn inline-flex items-center px-2 py-1 mt-1 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded transition duration-150">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Sync
                        </button>
                    HTML;
                    
                    return <<<HTML
                        <div class="text-center">
                            <div>{$statusHtml}</div>
                            {$lastSyncHtml}
                            {$syncButton}
                        </div>
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
                            </a>
                            <a href="{$editUrl}" 
                            class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-md transition duration-150 ease-in-out">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 
                                        2h11a2 2 0 002-2v-5m-1.414-9.414a2 
                                        2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
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
                            </button>
                        </div>
                    HTML;
                })
                ->editColumn('created_at', fn($row) => $row->created_at?->format('Y-m-d H:i'))
                ->rawColumns(['status', 'featured', 'stock_status', 'google_sync', 'actions'])
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

        $storeSetting = \App\Models\StoreSetting::first();
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
            'KWD' => 'KD',
        ];

        $storeSetting = StoreSetting::first();
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

            // Auto-sync if enabled in settings
            if ($this->shouldAutoSync()) {
                $result = $this->merchantService->syncProduct($product);
                
                if (!$result['success']) {
                    Log::error('Google Merchant sync failed for product ' . $product->id . ': ' . $result['message']);
                }
            }

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
     * Check if auto-sync is enabled in Google Merchant settings
     */
    private function shouldAutoSync(): bool
    {
        if (!$this->googleSettings) {
            return false;
        }
        
        return $this->googleSettings->is_enabled && 
               $this->googleSettings->auto_sync;
    }

    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|string|in:publish,unpublish,feature,unfeature,out_of_stock,in_stock,delete',
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

                case 'out_of_stock':
                    Product::whereIn('id', $ids)->update(['stock_status' => 'out_of_stock']);
                    $message = 'Selected products have been marked as out of stock.';
                    break;

                case 'in_stock':
                    Product::whereIn('id', $ids)->update(['stock_status' => 'in_stock']);
                    $message = 'Selected products have been marked as in stock.';
                    break;

                case 'delete':
                    $products = Product::whereIn('id', $ids)->get();

                    foreach ($products as $product) {
                        $product->deleteCompletely();
                    }

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
            'type' => 'required|string|in:status,featured,stock_status', // Add stock_status
        ]);

        $type = $validated['type'];

        if ($type === 'status') {
            $product->is_active = !$product->is_active;
        } elseif ($type === 'featured') {
            $product->is_featured = !$product->is_featured;
        } elseif ($type === 'stock_status') {
            // Toggle between in_stock and out_of_stock
            $product->stock_status = $product->stock_status === 'in_stock' ? 'out_of_stock' : 'in_stock';
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
        $product->deleteCompletely();
    }

    /**
     * Enhanced search products for order items
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        $searchTerm = '%' . $query . '%';

        // Use raw SQL for better JSON searching if needed
        $products = Product::with(['variants', 'mainImage'])
            ->where(function ($q) use ($searchTerm) {
                // Search in product title (handles JSON and translations)
                $q->where(function ($q2) use ($searchTerm) {
                    // Direct JSON field search
                    $q2->where('title->en', 'LIKE', $searchTerm)
                        ->orWhere('title->ar', 'LIKE', $searchTerm);
                })
                    ->orWhere('sku', 'LIKE', $searchTerm)
                    ->orWhereHas('translations', function ($q2) use ($searchTerm) {
                        // Search in translations table
                        $q2->where('field', 'title')
                            ->where('value', 'LIKE', $searchTerm);
                    });
            })
            ->orWhereHas('variants', function ($q) use ($searchTerm) {
                // Search in variant titles, SKUs, and barcodes
                $q->where('title', 'LIKE', $searchTerm)
                    ->orWhere('sku', 'LIKE', $searchTerm)
                    ->orWhere('barcode', 'LIKE', $searchTerm);
            })
            ->active()
            ->orderByRaw("
                CASE 
                    WHEN sku LIKE ? THEN 1
                    WHEN title->>'$.en' LIKE ? THEN 2
                    ELSE 3
                END
            ", [$query . '%', $query . '%'])
            ->limit(50)
            ->get()
            ->map(function ($product) {
                return $this->formatProductForSearch($product);
            });

        return response()->json($products);
    }

    /**
     * Format product data for search results
     */
    private function formatProductForSearch(Product $product)
    {
        // Get main image URL
        $imageUrl = null;
        if ($product->mainImage && $product->mainImage->first()) {
            $imageUrl = $product->mainImage->first()->file_url;
        }

        // Format variants with additional data
        $variants = $product->variants->map(function ($variant) use ($product) {
            $variantImage = $variant->image ? $variant->image->file_url : null;

            return [
                'id' => $variant->id,
                'title' => $variant->title,
                'sku' => $variant->sku,
                'barcode' => $variant->barcode,
                'price' => $variant->price ?? $product->price,
                'compare_at_price' => $variant->compare_at_price ?? $product->compare_at_price,
                'cost' => $variant->cost ?? $product->cost,
                'stock_quantity' => $variant->stock_quantity ?? $product->stock_quantity,
                'track_quantity' => $variant->track_quantity ?? $product->track_stock,
                'image' => $variantImage ?: null,
                'options' => $variant->options ?? [],
            ];
        });

        return [
            'id' => $product->id,
            'title' => $product->translate('title') ?? 'Untitled Product',
            'sku' => $product->sku,
            'price' => (float) $product->price,
            'compare_at_price' => $product->compare_at_price ? (float) $product->compare_at_price : null,
            'cost' => $product->cost ? (float) $product->cost : null,
            'stock_quantity' => (int) $product->stock_quantity,
            'track_stock' => (bool) $product->track_stock,
            'has_options' => (bool) $product->has_options,
            'image' => $imageUrl,
            'variants' => $variants,
            'requires_shipping' => (bool) $product->requires_shipping,
            'charge_tax' => (bool) $product->charge_tax,
            'status' => $product->is_active ? 'active' : 'inactive',
        ];
    }

    /**
     * Sync single product to Google Merchant
     */
    public function syncToGoogle(Product $product)
    {
        try {
            if (!$this->merchantService->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Google Merchant is not configured'
                ], 400);
            }
            
            $result = $this->merchantService->syncProduct($product);
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product synced to Google Merchant'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $result['message']
            ], 500);
            
        } catch (\Exception $e) {
            \Log::error('Google Sync Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Sync error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk sync products to Google Merchant
     */
    public function bulkSyncToGoogle(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);
        
        if (!$this->merchantService->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'Google Merchant is not configured'
            ], 400);
        }
        
        $products = Product::whereIn('id', $validated['product_ids'])->get();
        
        $results = [
            'total' => $products->count(),
            'successful' => 0,
            'failed' => 0,
            'errors' => []
        ];
        
        foreach ($products as $product) {
            $result = $this->merchantService->syncProduct($product);
            
            if ($result['success']) {
                $results['successful']++;
            } else {
                $results['failed']++;
                $results['errors'][] = [
                    'product_id' => $product->id,
                    'product_name' => $product->title,
                    'error' => $result['message']
                ];
            }
            
            // Small delay to avoid rate limiting
            usleep(50000); // 0.05 seconds
        }
        
        return response()->json([
            'success' => true,
            'message' => "Synced {$results['successful']} of {$results['total']} products to Google Merchant",
            'results' => $results
        ]);
    }
}
