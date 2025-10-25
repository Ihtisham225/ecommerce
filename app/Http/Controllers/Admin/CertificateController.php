<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CompanyRegistration;
use App\Models\CompanyRegistrationParticipant;
use App\Models\User;
use App\Models\Course;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    // Show all certificates
    public function index(Request $request)
    {
        $authUser = Auth::user();

        if ($authUser->hasRole('admin')) {
            // Admin: can view all certificates
            $certificatesQuery = Certificate::with(['user', 'participant.registration', 'course'])->latest();
            $users   = User::pluck('name', 'id');
            $courses = Course::pluck('title', 'id');
        } else {
            // Check if this user is a company representative
            $company = CompanyRegistration::where('email', $authUser->email)->first();

            if ($company) {
                // Show all certificates for this company's participants
                $participantIds = CompanyRegistrationParticipant::where('company_registration_id', $company->id)
                    ->pluck('id');

                $certificatesQuery = Certificate::with(['participant.registration', 'course'])
                    ->whereIn('company_registration_participant_id', $participantIds)
                    ->latest();

                $users = collect(); // none for company reps

                $courseIds = Certificate::whereIn('company_registration_participant_id', $participantIds)
                    ->pluck('course_id')->unique();

                $courses = Course::whereIn('id', $courseIds)->pluck('title', 'id');

            } else {
                // Check if the user is a participant
                $participant = CompanyRegistrationParticipant::where('email', $authUser->email)->first();

                if ($participant) {
                    // Show only this participant's certificates
                    $certificatesQuery = Certificate::with(['participant.registration', 'course'])
                        ->where('company_registration_participant_id', $participant->id)
                        ->latest();

                    $users = collect();
                    $courseIds = Certificate::where('company_registration_participant_id', $participant->id)
                        ->pluck('course_id')->unique();

                    $courses = Course::whereIn('id', $courseIds)->pluck('title', 'id');
                } else {
                    // Regular customer (registered individually)
                    $certificatesQuery = Certificate::with(['user', 'course'])
                        ->where('user_id', $authUser->id)
                        ->latest();

                    $users = collect();
                    $courseIds = Certificate::where('user_id', $authUser->id)
                        ->pluck('course_id')->unique();

                    $courses = Course::whereIn('id', $courseIds)->pluck('title', 'id');
                }
            }
        }

        // Apply filters
        if ($request->filled('course_id')) {
            $certificatesQuery->where('course_id', $request->course_id);
        }

        if ($authUser->hasRole('admin') && $request->filled('user_id')) {
            $certificatesQuery->where('user_id', $request->user_id);
        }

        if ($request->filled('is_active')) {
            $certificatesQuery->where('is_active', $request->is_active);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $certificatesQuery->where(function ($q) use ($search, $authUser) {
                $q->where('title->en', 'like', "%{$search}%")
                ->orWhere('title->ar', 'like', "%{$search}%")
                ->orWhereHas('course', fn($sub) => $sub->where('title', 'like', "%{$search}%"))
                ->orWhereHas('participant', fn($sub) => $sub->where('full_name', 'like', "%{$search}%"));

                if ($authUser->hasRole('admin')) {
                    $q->orWhereHas('user', fn($sub) => $sub->where('name', 'like', "%{$search}%"));
                }
            });
        }

        $certificates = $certificatesQuery->paginate(15)->withQueryString();

        return view('admin.certificates.index', compact('certificates', 'users', 'courses', 'authUser'));
    }

    // create form
    public function create()
    {
        if (! Auth::user()->hasRole('admin')) {
            abort(403);
        }

        // Normal users
        $appUsers = \App\Models\User::select('id', 'name', 'email')
            ->get()
            ->map(function ($user) {
                return (object) [
                    'id' => 'user_' . $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'type' => 'user',
                    'company' => null,
                ];
            });

        // Company participants
        $participants = CompanyRegistrationParticipant::with('registration')
            ->get()
            ->map(function ($participant) {
                return (object) [
                    'id' => 'participant_' . $participant->id,
                    'name' => $participant->full_name,
                    'email' => $participant->email,
                    'type' => 'participant',
                    'company' => optional($participant->registration)->company_name,
                ];
            });

        // Merge both types
        $users = $appUsers->merge($participants);

        $courses = Course::all();
        $documents = Document::whereNull('documentable_type')
            ->where('document_type', 'certificate_file')
            ->get();

        return view('admin.certificates.create', compact('users', 'courses', 'documents'));
    }

    // show certificate
    public function show(Certificate $certificate)
    {
        return view('admin.certificates.show', compact('certificate'));
    }

    // Store new certificate
    public function store(Request $request)
    {
        if (! Auth::user()->hasRole('admin')) {
            abort(403);
        }

        // Basic validation (no direct `exists` because id is prefixed)
        $validated = $request->validate([
            'user_id'       => 'required|string',
            'course_id'     => 'required|exists:courses,id',
            'title.en'      => 'required|string|max:255',
            'title.ar'      => 'required|string|max:255',
            'issued_at'     => 'nullable|date',
            'is_active'     => 'boolean',
            'certificate_document_id' => 'nullable|integer|exists:documents,id',
            'new_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Detect user type
        $userType = null;
        $userId = null;

        if (str_starts_with($validated['user_id'], 'user_')) {
            $userType = 'user';
            $userId = (int) str_replace('user_', '', $validated['user_id']);
        } elseif (str_starts_with($validated['user_id'], 'participant_')) {
            $userType = 'participant';
            $userId = (int) str_replace('participant_', '', $validated['user_id']);
        } else {
            abort(400, 'Invalid user identifier.');
        }

        // Create certificate
        $certificate = Certificate::create([
            // If your Certificate model has columns for both, store conditionally
            'user_id'                     => $userType === 'user' ? $userId : null,
            'company_registration_participant_id' => $userType === 'participant' ? $userId : null,
            'course_id'                   => $validated['course_id'],
            'title'                       => [
                'en' => $validated['title']['en'],
                'ar' => $validated['title']['ar'],
            ],
            'issued_at'                   => $validated['issued_at'],
            'is_active'                   => $validated['is_active'] ?? false,
        ]);

        // Attach existing certificate document
        if (!empty($validated['certificate_document_id'])) {
            Document::where('id', $validated['certificate_document_id'])
                ->update([
                    'documentable_id'   => $certificate->id,
                    'documentable_type' => Certificate::class,
                    'document_type'     => 'certificate_file',
                ]);
        }

        // Upload new certificate document
        if ($request->hasFile('new_certificate')) {
            $file = $request->file('new_certificate');
            $path = $file->store('certificates', 'public');

            $certificate->certificateFile()->create([
                'name'          => $file->getClientOriginalName(),
                'file_path'     => $path,
                'file_type'     => $file->getClientOriginalExtension(),
                'size'          => $file->getSize(),
                'mime_type'     => $file->getMimeType(),
                'document_type' => 'certificate_file',
            ]);
        }

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate created successfully.');
    }

    // Edit certificate
    public function edit(Certificate $certificate)
    {
        if (! Auth::user()->hasRole('admin')) {
            abort(403);
        }

        // Build unified users list
        $appUsers = User::select('id', 'name', 'email')
            ->get()
            ->map(function ($user) {
                return (object) [
                    'id' => 'user_' . $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'type' => 'user',
                    'company' => null,
                ];
            });

        $participants = CompanyRegistrationParticipant::with('registration')
            ->get()
            ->map(function ($participant) {
                return (object) [
                    'id' => 'participant_' . $participant->id,
                    'name' => $participant->full_name,
                    'email' => $participant->email,
                    'type' => 'participant',
                    'company' => optional($participant->registration)->company_name,
                ];
            });

        $users = $appUsers->merge($participants);

        $courses = Course::all();
        $documents = Document::whereNull('documentable_type')
            ->where('document_type', 'certificate_file')
            ->get();

        // Determine which should be selected
        if ($certificate->user_id) {
            $certificate->combined_user_id = 'user_' . $certificate->user_id;
        } elseif ($certificate->company_registration_participant_id) {
            $certificate->combined_user_id = 'participant_' . $certificate->company_registration_participant_id;
        } else {
            $certificate->combined_user_id = null;
        }

        return view('admin.certificates.edit', compact('certificate', 'users', 'courses', 'documents'));
    }

    // Update certificate
    public function update(Request $request, Certificate $certificate)
    {
        if (! Auth::user()->hasRole('admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'user_id'       => 'required|string',
            'course_id'     => 'required|exists:courses,id',
            'title.en'      => 'required|string|max:255',
            'title.ar'      => 'required|string|max:255',
            'issued_at'     => 'nullable|date',
            'is_active'     => 'boolean',
            'certificate_document_id' => 'nullable|integer|exists:documents,id',
            'new_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'remove_certificate' => 'nullable|boolean',
        ]);

        // Detect user type (user_5 or participant_12)
        $userType = null;
        $userId = null;

        if (str_starts_with($validated['user_id'], 'user_')) {
            $userType = 'user';
            $userId = (int) str_replace('user_', '', $validated['user_id']);
        } elseif (str_starts_with($validated['user_id'], 'participant_')) {
            $userType = 'participant';
            $userId = (int) str_replace('participant_', '', $validated['user_id']);
        } else {
            abort(400, 'Invalid user identifier.');
        }

        // Update certificate record
        $certificate->update([
            'user_id'   => $userType === 'user' ? $userId : null,
            'company_registration_participant_id' => $userType === 'participant' ? $userId : null,
            'course_id' => $validated['course_id'],
            'title'     => [
                'en' => $validated['title']['en'],
                'ar' => $validated['title']['ar'],
            ],
            'issued_at' => $validated['issued_at'],
            'is_active' => $validated['is_active'] ?? false,
        ]);

        // Remove existing certificate file if requested
        if (!empty($validated['remove_certificate']) && $certificate->certificateFile) {
            Document::where('id', $certificate->certificateFile->id)->update([
                'documentable_id'   => null,
                'documentable_type' => null,
            ]);
        }

        // Attach existing certificate document (if selected)
        if (!empty($validated['certificate_document_id'])) {
            Document::where('id', $validated['certificate_document_id'])->update([
                'documentable_id'   => $certificate->id,
                'documentable_type' => Certificate::class,
                'document_type'     => 'certificate_file',
            ]);
        }

        // Upload new certificate document (overrides all)
        if ($request->hasFile('new_certificate')) {
            $file = $request->file('new_certificate');
            $path = $file->store('certificates', 'public');

            $documentData = [
                'name'          => $file->getClientOriginalName(),
                'file_path'     => $path,
                'file_type'     => $file->getClientOriginalExtension(),
                'size'          => $file->getSize(),
                'mime_type'     => $file->getMimeType(),
                'document_type' => 'certificate_file',
            ];

            if ($certificate->certificateFile) {
                $certificate->certificateFile->update($documentData);
            } else {
                $certificate->certificateFile()->create($documentData);
            }
        }

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate updated successfully.');
    }

    // Delete certificate
    public function destroy(Certificate $certificate)
    {
        if (! Auth::user()->hasRole('admin')) {
            abort(403);
        }

        if ($certificate->certificate_path && Storage::disk('public')->exists($certificate->certificate_path)) {
            Storage::disk('public')->delete($certificate->certificate_path);
        }

        $certificate->delete();

        return redirect()->route('admin.certificates.index')
                         ->with('success', 'Certificate deleted successfully.');
    }

    public function download($id)
    {
        $certificate = \App\Models\Certificate::with(['user', 'course.country'])->findOrFail($id);

        $pdf = Pdf::loadView('admin.certificates.pdf', compact('certificate'))
                ->setPaper('a4', 'landscape'); // landscape fits certificates better

        $filename = 'certificate_' . $certificate->user->name . '.pdf';

        return $pdf->download($filename);
    }
}
