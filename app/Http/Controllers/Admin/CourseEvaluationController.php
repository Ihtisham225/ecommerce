<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEvaluation;
use App\Models\CourseEvaluationQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\CourseEvaluationExport;
use App\Models\User;

class CourseEvaluationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // base query
        $query = CourseEvaluation::with(['course', 'user']);

        if (!$user->hasRole('admin')) {
            // Customers: only their own evaluations
            $query->where('user_id', $user->id);
        }

        // filter by course (works for both admin + customer)
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // search only for admin (by user or course)
        if ($user->hasRole('admin') && $request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })->orWhereHas('course', function($q2) use ($search) {
                    $q2->where('title', 'like', "%{$search}%");
                });
            });
        }

        $evaluations = $query->orderBy('created_at', 'desc')
                            ->paginate(15)
                            ->appends($request->query());

        // responses counts only needed for admin
        $usersCounts = [];
        if ($user->hasRole('admin')) {
            $courseIds = $evaluations->pluck('course_id')->unique()->filter()->values()->all();
            if (!empty($courseIds)) {
                $usersCounts = CourseEvaluation::whereIn('course_id', $courseIds)
                    ->select('course_id', DB::raw('COUNT(DISTINCT user_id) as users_count'))
                    ->groupBy('course_id')
                    ->pluck('users_count', 'course_id')
                    ->toArray();
            }
        }

        // course dropdown
        $courses = Course::pluck('title', 'id');

        return view('admin.course-evaluations.index', compact('evaluations', 'courses', 'usersCounts'));
    }

    public function show(CourseEvaluation $courseEvaluation, Request $request)
    {
        $user = auth()->user();
        $course = $courseEvaluation->course;

        $query = CourseEvaluation::with(['user', 'responses.question'])
            ->where('course_id', $course->id);

        if ($user->hasRole('admin')) {
            // Admin: can filter by user
            $selectedUserId = $request->query('user');
            if ($selectedUserId) {
                $query->where('user_id', $selectedUserId);
            }
        } else {
            // Customer: only their own
            $query->where('user_id', $user->id);
            $selectedUserId = $user->id;
        }

        $evaluations = $query->get();

        // Admin sees all users for filter
        $users = [];
        if ($user->hasRole('admin')) {
            $users = User::whereIn('id', CourseEvaluation::where('course_id', $course->id)->pluck('user_id'))
                ->get();
        }

        return view('admin.course-evaluations.show', compact(
            'course',
            'evaluations',
            'users',
            'selectedUserId'
        ));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function edit(CourseEvaluationQuestion $evaluation)
    {
        //
    }

    public function update(Request $request, CourseEvaluationQuestion $question)
    {
        //
    }

    public function destroy(CourseEvaluationQuestion $evaluation)
    {
        //
    }
}
