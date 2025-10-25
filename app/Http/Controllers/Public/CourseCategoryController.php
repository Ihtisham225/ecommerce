<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CourseCategoryController extends Controller
{
    /**
     * Display a list of all published course categories (public).
     */
    public function index(Request $request)
    {
        $query = CourseCategory::withCount(['courses' => function ($q) {
            $q->published();
        }]);

        // 🔹 Search by name (localized JSON field)
        if ($request->filled('search')) {
            $search = $request->get('search');
            $locale = App::currentLocale();
            $query->where(function ($q) use ($search, $locale) {
                $q->where("name->$locale", 'like', "%{$search}%")
                  ->orWhere("name->en", 'like', "%{$search}%");
            });
        }

        // 🔹 Sorting
        switch ($request->get('sort')) {
            case 'name_asc':
                $query->orderBy('name->' . App::currentLocale(), 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name->' . App::currentLocale(), 'desc');
                break;
            default:
                $query->orderBy('name->' . App::currentLocale());
                break;
        }

        // 🔹 Paginate results
        $categories = $query->paginate(12)->appends($request->query());

        return view('public.course-categories.index', compact('categories'));
    }

    /**
     * Display a specific category with its published courses.
     */
    public function show(CourseCategory $courseCategory, Request $request)
    {
        // 🔹 Load all published courses in this category
        $coursesQuery = Course::published()
            ->where('course_category_id', $courseCategory->id);

        // --- Optional Filters ---
        if ($request->filled('search')) {
            $search = $request->get('search');
            $locale = App::currentLocale();
            $coursesQuery->where(function ($q) use ($search, $locale) {
                $q->where("title->$locale", 'like', "%{$search}%")
                  ->orWhere("title->en", 'like', "%{$search}%");
            });
        }

        if ($request->filled('sort')) {
            switch ($request->get('sort')) {
                case 'title_asc':
                    $coursesQuery->orderBy('title->' . App::currentLocale(), 'asc');
                    break;
                case 'title_desc':
                    $coursesQuery->orderBy('title->' . App::currentLocale(), 'desc');
                    break;
                case 'latest':
                    $coursesQuery->latest();
                    break;
                default:
                    $coursesQuery->latest();
                    break;
            }
        } else {
            $coursesQuery->latest();
        }

        // 🔹 Paginate courses
        $courses = $coursesQuery->paginate(12)->appends($request->query());

        // 🔹 Related / sibling categories
        $relatedCategories = CourseCategory::where('parent_id', $courseCategory->parent_id)
            ->where('id', '!=', $courseCategory->id)
            ->take(6)
            ->get();

        return view('public.course-categories.show', [
            'category' => $courseCategory,
            'courses' => $courses,
            'relatedCategories' => $relatedCategories,
        ]);
    }
}
