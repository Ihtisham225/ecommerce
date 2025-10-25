<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyRegistration;
use App\Models\Course;
use App\Traits\EmailHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyRegistrationController extends Controller
{
    use EmailHelper;

    public function index(Request $request)
    {
        $query = CompanyRegistration::with('courseSchedule');

        // Filters
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('course_title', 'like', "%{$search}%");
            });
        }

        $registrations = $query->latest()->paginate(10);
        $courses = Course::pluck('title', 'id');

        return view('admin.company-registrations.index', compact('registrations', 'courses'));
    }

    public function show(CompanyRegistration $companyRegistration)
    {
        $companyRegistration->load('courseSchedule', 'participants');
        return view('admin.company-registrations.show', compact('companyRegistration'));
    }

    public function update(Request $request, CompanyRegistration $companyRegistration)
    {
        // --- Restrict to admin users only ---
        if (! Auth::user()->hasRole('admin')) {
            abort(403);
        }

        // --- Validate request ---
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        // --- Update registration status ---
        $companyRegistration->status = $request->status;
        $companyRegistration->save();

        // --- Build participant summary (only Employee No. & Name) ---
        $participants = $companyRegistration->participants ?? collect();
        $participantList = '';

        if ($participants->count()) {
            $participantList .= '<br><br><strong>List of Participants:</strong>';
            $participantList .= '<table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 100%; margin-top: 6px;">';
            $participantList .= '<thead>
                                    <tr style="background-color: #f3f4f6;">
                                        <th align="left">Employee No.</th>
                                        <th align="left">Full Name</th>
                                    </tr>
                                </thead><tbody>';

            foreach ($participants as $p) {
                $participantList .= sprintf(
                    '<tr>
                        <td>%s</td>
                        <td>%s</td>
                    </tr>',
                    e($p->participant_number ?? '-'),
                    e($p->full_name ?? '-')
                );
            }

            $participantList .= '</tbody></table>';
        }

        // --- Send email notification (if company contact email exists) ---
        if ($companyRegistration->email) {
            $subject = __('Company Registration Status Update');

            $body = __(
                'Dear :name,<br><br>
                We would like to inform you that the status of your company registration for the course <strong>:course</strong> has been updated to <strong>:status</strong>.<br><br>
                Please find the list of registered participants below for your reference.
                :participants
                <br><br>
                Should you have any questions or require further assistance, please do not hesitate to contact us.<br><br>
                Best regards,<br>',
                [
                    'name' => e($companyRegistration->full_name ?? $companyRegistration->company_name),
                    'course' => e($companyRegistration->course_title ?? 'N/A'),
                    'status' => ucfirst($companyRegistration->status),
                    'participants' => $participantList,
                ]
            );

            // --- Send email using EmailHelper ---
            $this->sendEmail($companyRegistration->email, $subject, $body);
        }

        return redirect()->back()->with('success', __('Status updated successfully.'));
    }

    public function destroy(CompanyRegistration $companyRegistration)
    {
        $companyRegistration->delete();
        return back()->with('success', 'Company registration deleted successfully.');
    }
}

