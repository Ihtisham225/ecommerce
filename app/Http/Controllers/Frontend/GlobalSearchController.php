<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Course,
    CourseCategory,
    CourseSchedule,
    Country
};
use Illuminate\Support\Str;
use Carbon\Carbon;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));
        if (empty($query)) {
            return response()->json([]);
        }

        $results = [
            'courses' => $this->searchCourses($query),
            'categories' => $this->searchCategories($query),
        ];

        return response()->json($results);
    }

    /**
     * Main search logic (used by both AJAX + full page)
     */
    public function fullSearch(Request $request)
    {
        $query = trim($request->input('q', ''));
        $model = $request->input('model');

        $results = [];

        if ($model === 'courses') {
            $results['courses'] = $this->searchCourses($query);
        } elseif ($model === 'categories') {
            $results['categories'] = $this->searchCategories($query);
        } else {
            $results = [
                'courses' => $this->searchCourses($query),
                'categories' => $this->searchCategories($query),
            ];
        }

        $totalResults = collect($results)->flatten(1)->count();

        return view('frontend.global-search.search-results', [
            'results' => $results,
            'query' => $query,
            'model' => $model,
            'totalResults' => $totalResults,
        ]);
    }

    /**
     * 🔍 Smart course search (AI-like)
     */
    private function searchCourses(string $query)
    {
        $queryLower = strtolower($query);
        $words = explode(' ', $queryLower);

        // Detect month names (for schedule-based search)
        $months = collect(range(1, 12))->mapWithKeys(fn($m) => [
            strtolower(Carbon::create()->month($m)->format('F')) => $m
        ]);
        $foundMonth = null;
        foreach ($months as $name => $num) {
            if (str_contains($queryLower, $name)) {
                $foundMonth = $num;
                break;
            }
        }

        // Detect possible country keywords
        $countryName = null;
        $country = Country::whereRaw('LOWER(name) LIKE ?', ["%$queryLower%"])->first();
        if ($country) {
            $countryName = strtolower($country->name);
        }

        // Base query
        $courses = Course::with(['courseCategory', 'schedules.country'])
            ->where(function ($q) use ($queryLower, $words) {
                $q->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(title, '$.en'))) LIKE ?", ["%$queryLower%"])
                    ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(title, '$.ar'))) LIKE ?", ["%$queryLower%"])
                    ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(description, '$.en'))) LIKE ?", ["%$queryLower%"])
                    ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(description, '$.ar'))) LIKE ?", ["%$queryLower%"]);

                // Extra fuzzy matching for individual words
                foreach ($words as $word) {
                    $q->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(title, '$.en'))) LIKE ?", ["%$word%"])
                        ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(description, '$.en'))) LIKE ?", ["%$word%"]);
                }
            });

        // Filter by category name if matched
        $categoryMatches = CourseCategory::whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.en'))) LIKE ?", ["%$queryLower%"])
            ->pluck('id');
        if ($categoryMatches->count()) {
            $courses->orWhereIn('course_category_id', $categoryMatches);
        }

        // Filter by schedule month (e.g., “September courses”)
        if ($foundMonth) {
            $courses->whereHas('schedules', function ($q) use ($foundMonth) {
                $q->whereMonth('start_date', $foundMonth)
                    ->orWhereMonth('end_date', $foundMonth);
            });
        }

        // Filter by country (e.g., “courses in Kuwait”)
        if ($countryName) {
            $courses->whereHas('schedules.country', function ($q) use ($countryName) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%$countryName%"]);
            });
        }

        $results = $courses->limit(10)->get([
            'id',
            'slug',
            'title',
            'description',
            'course_category_id'
        ])->map(function ($course) {
            return [
                'id' => $course->id,
                'slug' => $course->slug,
                'title' => $course->title,
                'description' => $course->short_description,
                'category' => $course->courseCategory?->name,
                'next_schedule' => optional($course->nextSchedule())->formatted_date ?? '',
                'country' => optional(optional($course->nextSchedule())->country)->name ?? '',
            ];
        });

        // If no results, suggest similar courses
        if ($results->isEmpty()) {
            $suggestions = Course::with('courseCategory')
                ->inRandomOrder()
                ->limit(5)
                ->get(['id', 'slug', 'title', 'description', 'course_category_id'])
                ->map(fn($c) => [
                    'id' => $c->id,
                    'slug' => $c->slug,
                    'title' => $c->title,
                    'description' => $c->short_description,
                    'category' => $c->courseCategory?->name,
                    'suggestion' => true,
                ]);
            return $suggestions;
        }

        return $results;
    }

    /**
     * 🔍 Course category search
     */
    private function searchCategories(string $query)
    {
        $queryLower = strtolower($query);

        return CourseCategory::withCount('courses')
            ->where(function ($q) use ($queryLower) {
                $q->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.en'))) LIKE ?", ["%$queryLower%"])
                    ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(name, '$.ar'))) LIKE ?", ["%$queryLower%"])
                    ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(description, '$.en'))) LIKE ?", ["%$queryLower%"])
                    ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(description, '$.ar'))) LIKE ?", ["%$queryLower%"]);
            })
            ->limit(10)
            ->get(['id', 'slug', 'name', 'description'])
            ->map(fn($cat) => [
                'id' => $cat->id,
                'slug' => $cat->slug, // ✅ for route binding
                'name' => $cat->name,
                'description' => $cat->description,
                'course_count' => $cat->courses_count,
            ]);
    }
}
