<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'vendor', 'mainImage', 'galleryImages'])
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
        $vendors = Vendor::active()->get();

        // Currency symbols for display
        $currencySymbols = config('currencies.symbols', ['KWD' => 'KD', 'USD' => '$', 'EUR' => '€']);

        return view('frontend.products.index', compact('products', 'categories', 'brands', 'vendors', 'currencySymbols'));
    }

    public function show($slug)
    {
        $product = Product::with([
            'brand', 
            'vendor', 
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
        $productCurrency = $product->vendor?->currency_code ?? 'KWD';
        $currencySymbols = config('currencies.symbols', ['KWD' => 'KD', 'USD' => '$', 'EUR' => '€']);
        $productSymbol = $currencySymbols[$productCurrency] ?? $productCurrency;
        $productDecimals = $productCurrency === 'KWD' ? 3 : 2;

        return view('frontend.products.show', compact('product', 'relatedProducts', 'productSymbol', 'productDecimals'));
    }
}
