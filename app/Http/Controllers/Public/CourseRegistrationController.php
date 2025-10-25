<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CompanyRegistration;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\CourseSchedule;
use App\Traits\EmailHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseRegistrationController extends Controller
{
    use EmailHelper;

    // Show registration form for a specific schedule
    public function create(CourseSchedule $schedule)
    {
        $today = Carbon::today();

        // Check if schedule is open for enrollment
        if ($today->gt($schedule->start_date)) {
            return redirect()->back()->with('error', __('Enrollment for this schedule is closed.'));
        }

        // Retrieve the course via the relationship
        $course = $schedule->course;

        return view('public.course-registrations.form', compact('schedule', 'course'));
    }

    // Store a registration for a specific schedule
    public function store(Request $request, CourseSchedule $schedule)
    {
        $user = Auth::user();

        // Check if already registered for this schedule
        if (
            CourseRegistration::where('course_schedule_id', $schedule->id)
            ->where('user_id', $user->id)
            ->exists()
        ) {
            return redirect()->back()->with('error', __('You are already registered for this course schedule.'));
        }

        // Ensure schedule is still open
        $today = Carbon::today();
        if ($today->gte($schedule->start_date)) {
            return redirect()->back()->with('error', __('Enrollment for this schedule is closed.'));
        }

        // Create new registration
        $registration = CourseRegistration::create([
            'course_schedule_id' => $schedule->id,
            'user_id'            => $user->id,
            'status'             => 'pending',
            'notes'              => $request->notes ?? null,
        ]);

        // Send confirmation email
        if ($user->email) {
            $subject = __('Course Schedule Registration Submitted');
            $body = __('Hello :name,<br><br>Your registration for the course <strong>:course</strong> (Schedule: :schedule) has been submitted successfully.<br>The current status is <strong>:status</strong>.<br><br>Thank you!<br>Team', [
                'name'     => $user->name,
                'course'   => $schedule->course->title,
                'schedule' => $schedule->course_date,
                'status'   => ucfirst($registration->status),
            ]);

            // Assuming you have an EmailHelper trait/method
            $this->sendEmail($user->email, $subject, $body);
        }

        return redirect()->route('courses.show', $schedule->course)
            ->with('success', __('Your registration for this schedule has been submitted successfully!'));
    }

    /**
     * Show the company registration wizard form
     */
    public function companyForm(CourseSchedule $schedule)
    {
        // Fetch distinct company names and contact persons for dropdowns
        $companies = CompanyRegistration::select('id', 'company_name')
            ->whereNotNull('company_name')
            ->distinct()
            ->orderBy('company_name')
            ->get();

        $contacts = CompanyRegistration::select('id', 'full_name', 'email')
            ->whereNotNull('full_name')
            ->distinct()
            ->orderBy('full_name')
            ->get();

        return view('public.course-registrations.company-register', compact('schedule', 'companies', 'contacts'));
    }

    /**
     * Handle submission of the company registration
     */
    public function companyRegister(Request $request, CourseSchedule $schedule)
    {
        $validated = $request->validate([
            // Company
            'country' => 'required|string',
            'company_name' => 'required|string|max:255',
            'website' => 'nullable|url',
            'nature_of_business' => 'nullable|string',
            'postal_address' => 'required|string',

            // Contact
            'salutation' => 'required|string',
            'full_name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'email' => 'required|email',
            'telephone' => 'required|string',
            'mobile' => 'required|string',

            // Participants
            'number_of_participants' => 'required|integer|min:1',
            'participants' => 'required|array|min:1',
            'participants.*.salutation' => 'required|string',
            'participants.*.full_name' => 'required|string',
            'participants.*.participant_number' => 'nullable|string',
            'participants.*.email' => 'required|email',
            'participants.*.mobile' => 'required|string',
            'participants.*.city_of_living' => 'required|string',

            'heard_from' => 'nullable|string',
        ]);

        // Create main company registration record
        $registration = CompanyRegistration::create([
            'course_schedule_id' => $schedule->id,
            'course_code' => $schedule->course->code,
            'course_title' => $schedule->course->title,
            'course_date' => $schedule->course_date,
            'venue' => $schedule->venue,
            'language' => $schedule->language,
            'country' => $validated['country'],
            'company_name' => $validated['company_name'],
            'website' => $validated['website'] ?? null,
            'nature_of_business' => $validated['nature_of_business'] ?? null,
            'postal_address' => $validated['postal_address'],
            'salutation' => $validated['salutation'],
            'full_name' => $validated['full_name'],
            'job_title' => $validated['job_title'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'mobile' => $validated['mobile'],
            'number_of_participants' => $validated['number_of_participants'],
            'heard_from' => $validated['heard_from'] ?? null,
        ]);

        // Save participants
        foreach ($validated['participants'] as $participant) {
            $registration->participants()->create($participant);
        }

        return redirect()
            ->route('courses.show', $schedule->course)
            ->with('success', 'Your Company Registration Form has been submitted successfully. We will contact you soon.');
    }

    /**
     * Return company details for autofill
     */
    public function getCompanyDetails($id)
    {
        $company = CompanyRegistration::select(
            'id',
            'country',
            'company_name',
            'website',
            'nature_of_business',
            'postal_address'
        )->find($id);

        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        return response()->json($company);
    }

    /**
     * Return contact person details for autofill
     */
    public function getContactDetails($id)
    {
        $contact = CompanyRegistration::select(
            'id',
            'salutation',
            'full_name',
            'job_title',
            'email',
            'telephone',
            'mobile'
        )->find($id);

        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }

        return response()->json($contact);
    }
}
