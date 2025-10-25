<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseRegistration;
use App\Models\CourseSchedule;
use App\Traits\EmailHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseRegistrationController extends Controller
{
    use EmailHelper;

    /**
     * Display a listing of registrations.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            // Admin sees all registrations across all schedules
            $schedules = CourseSchedule::with('course')->get()
                ->pluck('display_title', 'id');

            $query = CourseRegistration::with([
                'courseSchedule.course.instructor',
                'user'
            ])->latest();
        } else {
            // Customers see only their own registrations
            $schedules = CourseSchedule::whereHas('registrations', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
                ->with('course')
                ->get()
                ->pluck('display_title', 'id');

            $query = CourseRegistration::with(['courseSchedule.course'])
                ->where('user_id', $user->id)
                ->latest();
        }

        // Filter by schedule
        if ($request->filled('course_schedule_id')) {
            $query->where('course_schedule_id', $request->course_schedule_id);
        }

        // Search by schedule/course title or user name
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search, $user) {
                // Match schedule title or parent course title
                $q->whereHas('courseSchedule', function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhereHas('course', function ($courseSub) use ($search) {
                            $courseSub->where('title', 'like', "%{$search}%");
                        });
                });

                // Admins can also search by user name
                if ($user->hasRole('admin')) {
                    $q->orWhereHas('user', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    });
                }
            });
        }

        $registrations = $query->paginate(15)->withQueryString();

        return view('admin.course-registrations.index', compact('registrations', 'schedules', 'user'));
    }

    /**
     * Show a single registration.
     */
    public function show(CourseRegistration $courseRegistration)
    {
        $user = Auth::user();

        if (! $user->hasRole('admin') && $courseRegistration->user_id !== $user->id) {
            abort(403);
        }

        $courseRegistration->load(['courseSchedule.course', 'user']);

        return view('admin.course-registrations.show', compact('courseRegistration'));
    }

    /**
     * Update registration status (admin only).
     */
    public function update(Request $request, CourseRegistration $courseRegistration)
    {
        if (! Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $courseRegistration->status = $request->status;
        $courseRegistration->save();

        // Send email notification
        if ($courseRegistration->user && $courseRegistration->user->email) {
            $schedule = $courseRegistration->courseSchedule;
            $courseTitle = $schedule->course->title ?? __('Unknown Course');
            $scheduleTitle = $schedule->title ?? __('Schedule');

            $subject = __('Your course registration status has been updated');
            $body = __(
                'Hello :name,<br><br>Your registration for <strong>:course</strong> (:schedule) has been updated to <strong>:status</strong>.<br><br>Thank you.',
                [
                    'name' => $courseRegistration->user->name,
                    'course' => $courseTitle,
                    'schedule' => $scheduleTitle,
                    'status' => ucfirst($courseRegistration->status),
                ]
            );

            $this->sendEmail($courseRegistration->user->email, $subject, $body);
        }

        return redirect()->back()->with('success', __('Status updated successfully.'));
    }

    /**
     * Delete a registration (admin only).
     */
    public function destroy(CourseRegistration $registration)
    {
        if (! Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $registration->delete();

        return redirect()->back()->with('success', __('Registration deleted successfully.'));
    }
}
