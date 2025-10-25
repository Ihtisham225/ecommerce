<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of published courses (public).
     */
    public function index(Request $request)
    {
        // --- Load filter data ---
        $categories = CourseCategory::all();

        // --- Build base query ---
        $query = Course::published()
            ->latest()
            ->with('courseCategory')
            ->withCount('schedules');

        // --- Filter by Category ---
        if ($request->filled('category')) {
            $query->where('course_category_id', $request->category);
        }

        // --- Pagination ---
        $courses = $query->paginate(12);

        // --- Return view ---
        return view('public.courses.index', compact('courses', 'categories'));
    }

    /**
     * Show a list of courses with their available schedules (public).
     */
    public function schedule(Request $request)
    {
        // Build base query: eager load category + count schedules
        $query = Course::published()
            ->latest()
            ->with('CourseCategory')
            ->withCount('schedules');

        // --- Filters ---
        if ($request->filled('CourseCategory')) {
            $query->where('course_category_id', $request->category);
        }

        // --- Sort latest schedules first ---
        $courses = $query->paginate(15);

        // --- Load filter data ---
        $categories = CourseCategory::all();

        return view('public.courses.schedule', compact(
            'courses',
            'categories',
        ));
    }


    /**
     * Show a single course details (public).
     */
    public function show(Course $course)
    {
        abort_unless($course->is_published, 404);

        // Related courses (same category)
        $relatedCourses = Course::published()
            ->where('id', '!=', $course->id)
            ->where('course_category_id', $course->course_category_id)
            ->latest()
            ->take(3)
            ->get();

        // Load all schedules with related models
        $course->load([
            'schedules.instructor',
            'schedules.country.countryFlag',
            'schedules.flyer',
            'schedules.outline',
        ]);

        // Auth-based checks per schedule
        $userRegistrations = [];
        $userEvaluations = [];

        if (auth()->check()) {
            $userId = auth()->id();

            $userRegistrations = \App\Models\CourseRegistration::where('user_id', $userId)
                ->whereIn('course_schedule_id', $course->schedules->pluck('id'))
                ->get()
                ->keyBy('course_schedule_id');

            $userEvaluations = \App\Models\CourseEvaluation::where('user_id', $userId)
                ->whereIn('course_schedule_id', $course->schedules->pluck('id'))
                ->get()
                ->keyBy('course_schedule_id');
        }

        return view('public.courses.show', [
            'course'            => $course,
            'relatedCourses'    => $relatedCourses,
            'userRegistrations' => $userRegistrations,
            'userEvaluations'   => $userEvaluations,
        ]);
    }
}
