<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEvaluation;
use App\Models\CourseEvaluationQuestion;
use App\Models\CourseEvaluationResponse;
use App\Models\CourseSchedule;
use App\Traits\EmailHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseEvaluationController extends Controller
{
    use EmailHelper;
    
    // Show evaluation form for a specific schedule
    public function create(CourseSchedule $courseSchedule)
    {
        $user = Auth::user();

        // Ensure user is enrolled and confirmed in this schedule
        $registration = $courseSchedule->registrations()
            ->where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->first();

        if (!$registration) {
            return redirect()->back()
                ->with('error', __('You must be enrolled and approved for this course schedule to submit an evaluation.'));
        }

        // Ensure user hasn’t already submitted
        if (
            CourseEvaluation::where('course_schedule_id', $courseSchedule->id)
                ->where('user_id', $user->id)
                ->exists()
        ) {
            return redirect()->back()
                ->with('error', __('You have already submitted an evaluation for this course schedule.'));
        }

        // Ensure course schedule has ended before allowing evaluation
        $today = Carbon::today();
        if ($today->lt($courseSchedule->end_date)) {
            return redirect()->back()
                ->with('error', __('You can only submit an evaluation after the course has ended.'));
        }

        // Get active evaluation questions
        $questions = CourseEvaluationQuestion::where('is_active', true)->get();

        // Load the related course for display purposes
        $course = $courseSchedule->course;

        return view('public.course-evaluations.form', compact('courseSchedule', 'course', 'questions'));
    }

    // Store the evaluation
    public function store(Request $request, CourseSchedule $courseSchedule)
    {
        $user = Auth::user();
        $course = $courseSchedule->course;

        // Get active question IDs
        $questionIds = CourseEvaluationQuestion::where('is_active', true)->pluck('id')->toArray();

        // Validate user input
        $validated = $request->validate([
            'responses'   => 'required|array',
            'responses.*' => 'required|string|max:255',
        ]);

        // Create evaluation record
        $evaluation = CourseEvaluation::create([
            'course_schedule_id' => $courseSchedule->id,
            'user_id'            => $user->id,
        ]);

        // Store individual responses
        foreach ($validated['responses'] as $questionId => $answer) {
            if (in_array($questionId, $questionIds)) {
                CourseEvaluationResponse::create([
                    'course_evaluation_id' => $evaluation->id,
                    'question_id'          => $questionId,
                    'answer'               => $answer,
                ]);
            }
        }

        // Send thank-you email
        if ($user->email) {
            $subject = __('We received your course evaluation');
            $body = __('Hello :name,<br><br>Thank you for submitting your feedback for the course <strong>:course</strong> (Schedule: :schedule_date).<br>We appreciate your time and input!<br><br>Best regards,<br>Team', [
                'name'          => $user->name,
                'course'        => $course->title,
                'schedule_date' => $courseSchedule->course_date,
            ]);

            $this->sendEmail($user->email, $subject, $body);
        }

        return redirect()->route('schedules.show', $courseSchedule)
            ->with('success', __('Thank you! Your feedback for this schedule has been submitted.'));
    }

}
