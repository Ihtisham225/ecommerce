<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Country;
use App\Models\Course;
use App\Models\Page;
use App\Models\MenuItem;
use App\Models\SiteSetting;
use App\Models\Sponsor;
use Illuminate\Http\Request;
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

        // Get menu items from database
        $menuItems = MenuItem::with('children')
            ->whereNull('parent_id')
            ->where('is_published', true)
            ->orderBy('order')
            ->get()
            ->toArray();
        
        $countries = Country::all();
        $sponsors = Sponsor::all();
        $blogs = Blog::latest()->take(10)->get();
        $courses = Course::latest()->take(10)->get();
            
        // Get welcome section data from site settings
        $welcomeData = [
            'title' => SiteSetting::getValue('welcome_title', 'Welcome'),
            'description' => SiteSetting::getValue('welcome_description', 'Develop your skills by keeping up with the latest training techniques!'),
            'ctaText' => SiteSetting::getValue('welcome_cta_text', 'Check now'),
            'ctaLink' => SiteSetting::getValue('welcome_cta_link', '/courses'),
            'backgroundImage' => SiteSetting::getValue('welcome_background_image'),
        ];
        
        return view('public.welcome', compact('menuItems', 'welcomeData', 'countries', 'sponsors', 'blogs', 'courses'));
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
        return view('public.institute-profile', compact('sponsors'));
    }

    public function whoWeAre()
    {
        return view('public.who-we-are');
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
        return view('public.consultations');
    }
}