<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Pagination\LengthAwarePaginator;

class SubscriberController extends Controller
{
    /**
     * Display a list of all published course categories (public).
     */
    public function index(Request $request)
    {
        $categories = Category::active()
            ->withCount(['products' => function ($q) {
                $q->published();
            }])
            ->get();

        // 🔹 Search by name
        if ($request->filled('search')) {
            $search = strtolower($request->get('search'));
            $locale = App::currentLocale();
            
            $categories = $categories->filter(function ($category) use ($search, $locale) {
                $name = $category->getLocalizedName($locale);
                return stripos($name, $search) !== false;
            });
        }

        // 🔹 Sorting
        $sort = $request->get('sort', 'name_asc');
        $locale = App::currentLocale();
        
        $isDescending = in_array($sort, ['name_desc', 'products_count']);
        
        $categories = $categories->sortBy(function ($category) use ($sort, $locale) {
            switch ($sort) {
                case 'name_desc':
                case 'name_asc':
                    return strtolower($category->getLocalizedName($locale));
                case 'products_count':
                    return $category->products_count;
                default:
                    return strtolower($category->getLocalizedName($locale));
            }
        }, SORT_REGULAR, $isDescending);

        // 🔹 Manual pagination
        $page = $request->get('page', 1);
        $perPage = 12;
        $offset = ($page - 1) * $perPage;
        
        $paginatedCategories = new LengthAwarePaginator(
            $categories->slice($offset, $perPage)->values(),
            $categories->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('frontend.categories.index', ['categories' => $paginatedCategories]);
    }

    /**
     * Display a specific category with its published products.
     */
    public function show(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->first();
        // Ensure category is active
        if (!$category->is_active) {
            abort(404);
        }

        // 🔹 Load all published products in this category
        $productsQuery = $category->products()
            ->published()
            ->with(['categories' => function ($query) {
                $query->active();
            }]);

        // --- Optional Filters ---
        if ($request->filled('search')) {
            $search = strtolower($request->get('search'));
            $locale = App::currentLocale();
            
            // We'll filter in PHP for safety
            $allProducts = $productsQuery->get();
            
            $filteredProducts = $allProducts->filter(function ($product) use ($search, $locale) {
                $title = $product->getLocalizedTitle($locale);
                return stripos($title, $search) !== false;
            });
            
            // Apply sorting to filtered results
            $sort = $request->get('sort', 'latest');
            
            $filteredProducts = $this->sortProducts($filteredProducts, $sort, $locale);
            
            // Manual pagination for filtered results
            $page = $request->get('page', 1);
            $perPage = 12;
            $offset = ($page - 1) * $perPage;
            
            $products = new LengthAwarePaginator(
                $filteredProducts->slice($offset, $perPage)->values(),
                $filteredProducts->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            // Apply sorting directly to query
            $sort = $request->get('sort', 'latest');
            $locale = App::currentLocale();
            
            switch ($sort) {
                case 'title_asc':
                    $productsQuery->orderByRaw(
                        'COALESCE(JSON_UNQUOTE(NULLIF(JSON_EXTRACT(title, ?), \'\')), title) ASC',
                        ["$.{$locale}"]
                    );
                    break;
                case 'title_desc':
                    $productsQuery->orderByRaw(
                        'COALESCE(JSON_UNQUOTE(NULLIF(JSON_EXTRACT(title, ?), \'\')), title) DESC',
                        ["$.{$locale}"]
                    );
                    break;
                case 'price_asc':
                    $productsQuery->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $productsQuery->orderBy('price', 'desc');
                    break;
                case 'latest':
                default:
                    $productsQuery->latest();
                    break;
            }
            
            $products = $productsQuery->paginate(12)->appends($request->query());
        }

        // 🔹 Related / sibling categories
        $relatedCategories = Category::active()
            ->where('parent_id', $category->parent_id)
            ->where('id', '!=', $category->id)
            ->withCount(['products' => function ($q) {
                $q->published();
            }])
            ->take(6)
            ->get();

        return view('frontend.categories.show', [
            'category' => $category,
            'products' => $products,
            'relatedCategories' => $relatedCategories,
        ]);
    }

    /**
     * Helper method to sort products collection
     */
    private function sortProducts($products, $sort, $locale)
    {
        $isDescending = in_array($sort, ['title_desc', 'price_desc', 'latest']);
        
        return $products->sortBy(function ($product) use ($sort, $locale) {
            switch ($sort) {
                case 'title_asc':
                case 'title_desc':
                    return strtolower($product->getLocalizedTitle($locale));
                case 'price_asc':
                case 'price_desc':
                    return $product->price ?? 0;
                case 'latest':
                    return $product->created_at;
                default:
                    return strtolower($product->getLocalizedTitle($locale));
            }
        }, SORT_REGULAR, $isDescending);
    }
}