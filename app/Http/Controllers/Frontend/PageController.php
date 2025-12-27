<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Page;
use App\Models\Product;
use App\Models\Sponsor;
use Illuminate\Support\Facades\Artisan;

class PageController extends Controller
{
    public function welcome()
    {
        // Run storage:link if the symbolic link doesn't exist
        if (!file_exists(public_path('storage'))) {
            try {
                Artisan::call('storage:link');
            } catch (\Exception $e) {
                // Fallback for shared hosting that blocks Artisan::call
                $target = storage_path('app/public');
                $link = public_path('storage');

                if (!file_exists($link)) {
                    @symlink($target, $link);
                }
            }
        }

        // Featured Products
        $featuredProducts = Product::with(['brand', 'documents'])
            ->active()
            ->featured()
            ->published()
            ->inStock()
            ->take(8)
            ->get();

        // Latest Products
        $latestProducts = Product::with(['brand', 'documents'])
            ->active()
            ->published()
            ->inStock()
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Categories for slider
        $categories = Category::active()
            ->topLevel()
            ->ordered()
            ->take(10)
            ->get();

        // Collections for slider
        $collections = Collection::active()
            ->take(10)
            ->get();

        // Brands
        $brands = Brand::active()
            ->take(12)
            ->get();

        // Best Selling Products (you might need to implement this scope)
        $bestSellingProducts = Product::with(['brand', 'documents'])
            ->active()
            ->published()
            ->inStock()
            ->take(8)
            ->get();

        return view('frontend.welcome', compact(
            'featuredProducts',
            'latestProducts',
            'categories',
            'collections',
            'brands',
            'bestSellingProducts'
        ));
    }

    public function show(Page $page)
    {
        if (!$page->is_published) {
            abort(404);
        }

        return view('page', compact('page'));
    }

    public function instituteProfile()
    {
        $sponsors = Sponsor::all();
        return view('frontend.institute-profile', compact('sponsors'));
    }

    public function whoWeAre()
    {
        return view('frontend.who-we-are');
    }

    public function qatar()
    {
        return view('workshops.qatar');
    }

    public function processPlantShutdown()
    {
        return view('workshops.process-plant-shutdown');
    }

    public function schedule2025()
    {
        return view('schedules.2025-2026');
    }

    public function schedule2024()
    {
        return view('schedules.2024-2025');
    }

    public function consultations()
    {
        return view('frontend.consultations');
    }
}
