<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\Document;
use App\Traits\EmailHelper;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    use EmailHelper;

    public function index(Request $request)
    {
        
        $query = Instructor::query();

        // $this->sendEmail('uihtisham0@gmail.com', 'Welcome to ' . config('app.name'), 'This is test email from infotech support');

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Search by name (multilingual-aware)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"".app()->getLocale()."\"')) LIKE ?", ["%{$search}%"]);
        }

        $instructors = $query->latest()->paginate(20)->appends($request->query());

        return view('admin.instructors.index', compact('instructors'));
    }

    public function show(Instructor $instructor)
    {
        return view('admin.instructors.show', compact('instructor'));
    }

    public function create()
    {
        $documents = Document::where('document_type', 'cv')->orWhere('document_type', 'profile_picture')->get();
        return view('admin.instructors.create', compact('documents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en'           => 'required|string|max:255',
            'name_ar'           => 'required|string|max:255',
            'email'             => 'nullable|email|max:255',
            'phone'             => 'nullable|string|max:50',
            'bio_en'            => 'nullable|string',
            'bio_ar'            => 'nullable|string',
            'specialization_en' => 'nullable|string|max:255',
            'specialization_ar' => 'nullable|string|max:255',
            'is_active'         => 'boolean',

            // 🔹 updated fields
            'profile_picture_id' => 'nullable|integer|exists:documents,id',
            'new_profile_picture'         => 'nullable|image|max:2048', // jpg, png, webp
            'cv_id'              => 'nullable|integer|exists:documents,id',
            'new_cv'                      => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        $multilingualData = [
            'name' => [
                'en' => $validated['name_en'],
                'ar' => $validated['name_ar'],
            ],
            'email'          => $validated['email'] ?? null,
            'phone'          => $validated['phone'] ?? null,
            'bio'            => [
                'en' => $validated['bio_en'] ?? null,
                'ar' => $validated['bio_ar'] ?? null,
            ],
            'specialization' => [
                'en' => $validated['specialization_en'] ?? null,
                'ar' => $validated['specialization_ar'] ?? null,
            ],
            'is_active'      => $validated['is_active'] ?? true,
        ];

        $instructor = Instructor::create($multilingualData);

        // 🔹 Attach selected profile picture
        if (!empty($validated['profile_picture_id'])) {
            Document::where('id', $validated['profile_picture_id'])
                ->update([
                    'documentable_id'   => $instructor->id,
                    'documentable_type' => Instructor::class,
                    'document_type'     => 'profile_picture',
                ]);
        }

        // 🔹 Upload new profile picture
        if ($request->hasFile('new_profile_picture')) {
            $file = $request->file('new_profile_picture');
            $path = $file->store('documents', 'public');

            $instructor->profilePicture()->updateOrCreate([], [
                'name'          => $file->getClientOriginalName(),
                'file_path'     => $path,
                'file_type'     => $file->getClientOriginalExtension(),
                'mime_type' => $file->getMimeType(),
                'size'     => $file->getSize(),
                'document_type' => 'profile_picture',
            ]);
        }

        // 🔹 Attach selected CV
        if (!empty($validated['cv_id'])) {
            Document::where('id', $validated['cv_id'])
                ->update([
                    'documentable_id'   => $instructor->id,
                    'documentable_type' => Instructor::class,
                    'document_type'     => 'cv',
                ]);
        }

        // 🔹 Upload new CV
        if ($request->hasFile('new_cv')) {
            $file = $request->file('new_cv');
            $path = $file->store('documents', 'public');

            $instructor->cv()->updateOrCreate([], [
                'name'          => $file->getClientOriginalName(),
                'file_path'     => $path,
                'file_type'     => $file->getClientOriginalExtension(),
                'size'     => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'document_type' => 'cv',
            ]);
        }

        return redirect()->route('admin.instructors.index')
            ->with('success', 'Instructor created successfully.');
    }


    public function edit(Instructor $instructor)
    {
        // Extract raw multilingual fields
        $instructor->name_en = $instructor->getNames()['en'] ?? '';
        $instructor->name_ar = $instructor->getNames()['ar'] ?? '';
        $instructor->bio_en = $instructor->getBios()['en'] ?? '';
        $instructor->bio_ar = $instructor->getBios()['ar'] ?? '';
        $instructor->specialization_en = $instructor->getSpecializations()['en'] ?? '';
        $instructor->specialization_ar = $instructor->getSpecializations()['ar'] ?? '';

        $documents = Document::where('document_type', 'cv')->orWhere('document_type', 'profile_picture')->get();

        return view('admin.instructors.edit', compact('instructor', 'documents'));
    }

    public function update(Request $request, Instructor $instructor)
    {
        $validated = $request->validate([
            'name_en'           => 'required|string|max:255',
            'name_ar'           => 'required|string|max:255',
            'email'             => 'nullable|email|max:255',
            'phone'             => 'nullable|string|max:50',
            'bio_en'            => 'nullable|string',
            'bio_ar'            => 'nullable|string',
            'specialization_en' => 'nullable|string|max:255',
            'specialization_ar' => 'nullable|string|max:255',
            'is_active'         => 'boolean',

            // Documents
            'profile_picture_id' => 'nullable|integer|exists:documents,id',
            'new_profile_picture' => 'nullable|image|max:2048',
            'cv_id'              => 'nullable|integer|exists:documents,id',
            'new_cv'             => 'nullable|mimes:pdf,doc,docx|max:5120',

            // Removal flags
            'remove_profile_picture' => 'nullable|boolean',
            'remove_cv'             => 'nullable|boolean',
        ]);

        // Update multilingual info
        $instructor->update([
            'name' => ['en' => $validated['name_en'], 'ar' => $validated['name_ar']],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'bio' => ['en' => $validated['bio_en'] ?? null, 'ar' => $validated['bio_ar'] ?? null],
            'specialization' => ['en' => $validated['specialization_en'] ?? null, 'ar' => $validated['specialization_ar'] ?? null],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // ---- PROFILE PICTURE ----

        // Remove existing
        if (!empty($validated['remove_profile_picture']) && $instructor->profilePicture) {
            Document::where('id', $instructor->profilePicture->id)->update([
                'documentable_id' => null,
                'documentable_type' => null,
            ]);
        }

        // Attach selected existing
        if (!empty($validated['profile_picture_id'])) {
            Document::where('id', $validated['profile_picture_id'])->update([
                'documentable_id' => $instructor->id,
                'documentable_type' => Instructor::class,
                'document_type' => 'profile_picture',
            ]);
        }

        // Upload new profile picture (overrides selection)
        if ($request->hasFile('new_profile_picture')) {
            $file = $request->file('new_profile_picture');
            $path = $file->store('documents', 'public');

            $documentData = [
                'name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'document_type' => 'profile_picture',
            ];

            if ($instructor->profilePicture) {
                $instructor->profilePicture->update($documentData);
            } else {
                $instructor->profilePicture()->create($documentData);
            }
        }

        // ---- CV ----

        if (!empty($validated['remove_cv']) && $instructor->cv) {
            Document::where('id', $instructor->cv->id)->update([
                'documentable_id' => null,
                'documentable_type' => null,
            ]);
        }

        // Attach selected existing CV
        if (!empty($validated['cv_id'])) {
            Document::where('id', $validated['cv_id'])->update([
                'documentable_id' => $instructor->id,
                'documentable_type' => Instructor::class,
                'document_type' => 'cv',
            ]);
        }

        // Upload new CV (overrides selection)
        if ($request->hasFile('new_cv')) {
            $file = $request->file('new_cv');
            $path = $file->store('documents', 'public');

            $documentData = [
                'name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'document_type' => 'cv',
            ];

            if ($instructor->cv) {
                $instructor->cv->update($documentData);
            } else {
                $instructor->cv()->create($documentData);
            }
        }

        return redirect()->route('admin.instructors.index')
            ->with('success', 'Instructor updated successfully.');
    }


    public function destroy(Instructor $instructor)
    {
        // Also delete CV if attached
        if ($instructor->cv) {
            $instructor->cv->delete();
        }

        $instructor->delete();

        return redirect()->route('admin.instructors.index')
            ->with('success', 'Instructor deleted successfully.');
    }
}
