<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\OrderItem;
use App\Models\StoreSetting;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'mainImage', 'galleryImages'])
            ->published()
            ->active();

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Brand filter
        if ($request->has('brand')) {
            $query->whereHas('brand', function($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        // Price range filter
        if ($request->has('min_price') || $request->has('max_price')) {
            $query->whereBetween('price', [
                $request->min_price ?? 0,
                $request->max_price ?? 999999
            ]);
        }

        // Stock filter
        if ($request->has('stock')) {
            if ($request->stock === 'in_stock') {
                $query->inStock();
            } elseif ($request->stock === 'out_of_stock') {
                $query->where('stock_status', 'out_of_stock');
            }
        }

        // Sort options
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'featured':
                $query->featured();
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12);
        $categories = Category::withCount('products')->get();
        $brands = Brand::withCount('products')->get();

        // Currency symbols for display
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

        // Get store setting
        $storeSetting = StoreSetting::first();
        $currencyCode = $storeSetting?->currency_code ?? 'USD';
        $currencySymbol = $currencySymbols[$currencyCode] ?? $currencyCode;

        $pageTitle = __('All Products');

        return view('frontend.products.index', compact('products', 'categories', 'brands', 'currencySymbol', 'pageTitle'));
    }

    public function newArrivals(Request $request)
    {
        $query = Product::with(['brand', 'mainImage', 'galleryImages'])
            ->published()
            ->active()
            ->orderBy('created_at', 'desc'); // Show newest first

        // Apply same filters as index
        $query = $this->applyFilters($query, $request);
        
        $products = $query->paginate(12);
        $categories = Category::withCount('products')->get();
        $brands = Brand::withCount('products')->get();
        
        $currencySymbol = $this->getCurrencySymbol();

        $pageTitle = __('New Arrivals');
        
        return view('frontend.products.index', compact('products', 'categories', 'brands', 'currencySymbol', 'pageTitle'))
            ->with('pageTitle', __('New Arrivals'));
    }
    
    public function bestSellers(Request $request)
    {
        // Get product IDs with highest sales
        $bestSellerIds = OrderItem::select('product_id')
            ->selectRaw('COUNT(*) as sales_count')
            ->groupBy('product_id')
            ->orderBy('sales_count', 'desc')
            ->limit(100) // Get top 100 selling products
            ->pluck('product_id');
        
        $query = Product::with(['brand', 'mainImage', 'galleryImages'])
            ->published()
            ->active()
            ->whereIn('id', $bestSellerIds)
            ->when($bestSellerIds->isNotEmpty(), function ($q) use ($bestSellerIds) {
                // Preserve the order of best sellers
                $idsOrdered = $bestSellerIds->implode(',');
                return $q->orderByRaw("FIELD(id, $idsOrdered)");
            });
        
        // If no best sellers yet, fall back to featured products
        if ($bestSellerIds->isEmpty()) {
            $query->featured()->latest();
        }
        
        // Apply filters
        $query = $this->applyFilters($query, $request);
        
        $products = $query->paginate(12);
        $categories = Category::withCount('products')->get();
        $brands = Brand::withCount('products')->get();
        
        $currencySymbol = $this->getCurrencySymbol();

        $pageTitle = __('Best Sellers');
        
        return view('frontend.products.index', compact('products', 'categories', 'brands', 'currencySymbol', 'pageTitle'))
            ->with('pageTitle', __('Best Sellers'));
    }
    
    public function sale(Request $request)
    {
        $query = Product::with(['brand', 'mainImage', 'galleryImages'])
            ->published()
            ->active()
            ->whereNotNull('compare_at_price')
            ->where('compare_at_price', '>', 0)
            ->whereColumn('compare_at_price', '>', 'price'); // Products with sale price
        
        // Apply filters
        $query = $this->applyFilters($query, $request);
        
        $products = $query->paginate(12);
        $categories = Category::withCount('products')->get();
        $brands = Brand::withCount('products')->get();
        
        $currencySymbol = $this->getCurrencySymbol();

        $pageTitle = __('Sale');
        
        return view('frontend.products.index', compact('products', 'categories', 'brands', 'currencySymbol', 'pageTitle'))
            ->with('pageTitle', __('Sale'));
    }

    public function show($slug)
    {
        $product = Product::with([
            'brand', 
            'categories',
            'documents',
            'variants.options',
            'options.values'
        ])->where('slug', $slug)->firstOrFail();

        // Related products
        $relatedProducts = Product::whereHas('categories', function($query) use ($product) {
            $query->whereIn('categories.id', $product->categories->pluck('id'));
        })
        ->where('id', '!=', $product->id)
        ->published()
        ->active()
        ->limit(4)
        ->get();

        // Currency symbols
       // Currency symbols for display
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

        // Get store setting
        $storeSetting = StoreSetting::where('user_id', auth()->id())->first();
        $currencyCode = $storeSetting?->currency_code ?? 'USD';
        $currencySymbol = $currencySymbols[$currencyCode] ?? $currencyCode;
        $productDecimals = $currencyCode === 'KWD' ? 3 : 2;

        return view('frontend.products.show', compact('product', 'relatedProducts', 'currencySymbol', 'productDecimals'));
    }

    /**
     * Helper method to apply filters to queries
     */
    private function applyFilters($query, Request $request)
    {
        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        // Category filter
        if ($request->has('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        // Brand filter
        if ($request->has('brand')) {
            $query->whereHas('brand', function($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }
        
        // Price range filter
        if ($request->has('min_price') || $request->has('max_price')) {
            $query->whereBetween('price', [
                $request->min_price ?? 0,
                $request->max_price ?? 999999
            ]);
        }
        
        // Stock filter
        if ($request->has('stock')) {
            if ($request->stock === 'in_stock') {
                $query->inStock();
            } elseif ($request->stock === 'out_of_stock') {
                $query->where('stock_status', 'out_of_stock');
            }
        }
        
        // Sort options
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'featured':
                $query->featured();
                break;
            default:
                // For sale page, show highest discount first
                if ($request->routeIs('products.sale')) {
                    $query->orderByRaw('((compare_at_price - price) / compare_at_price * 100) DESC');
                } else {
                    $query->latest();
                }
        }
        
        return $query;
    }
    
    /**
     * Helper method to get currency symbol
     */
    private function getCurrencySymbol()
    {
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
        
        $storeSetting = StoreSetting::first();
        $currencyCode = $storeSetting?->currency_code ?? 'USD';
        return $currencySymbols[$currencyCode] ?? $currencyCode;
    }
}
