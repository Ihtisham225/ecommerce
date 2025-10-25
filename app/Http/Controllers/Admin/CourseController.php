<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseSchedule;
use App\Models\Document;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    protected array $docFields = [
        ['id' => 'image_document_id',          'file' => 'new_image',           'relation' => 'image',         'type' => 'image'],
        ['id' => 'outline_document_id',        'file' => 'new_outline',         'relation' => 'outline',       'type' => 'outline'],
        ['id' => 'flyer_document_id',          'file' => 'new_flyer',           'relation' => 'flyer',         'type' => 'flyer'],
        ['id' => 'cover_letter_document_id',   'file' => 'new_cover_letter',    'relation' => 'coverLetter',   'type' => 'cover_letter'],
        ['id' => 'complete_document_id',       'file' => 'new_complete_document','relation' => 'completeDocument','type' => 'complete_document'],
        ['id' => 'attendance_sheet_document_id','file' => 'new_attendance_sheet','relation' => 'attendanceSheet','type' => 'attendance_sheet'],
        ['id' => 'certificates_document_id',   'file' => 'new_certificates',    'relation' => 'certificates',  'type' => 'certificates'],
        ['id' => 'invoice_document_id',        'file' => 'new_invoice',              'relation' => 'invoice',  'type' => 'invoice'],
        ['id' => 'course_evaluation_document_id','file' => 'new_course_evaluation','relation' => 'courseEvaluation','type' => 'course_evaluation'],
    ];

    public function index(Request $request)
    {
        $query = Course::query();

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('course_category_id', $request->category_id);
        }

        // Filter by instructor
        if ($request->filled('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        // Search by title
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(title, '$.\"".app()->getLocale()."\"')) LIKE ?", ["%{$search}%"]);
        }

        $courses = $query->latest()->paginate(20)->appends($request->query());

        // Dropdown data
        $categories = \App\Models\CourseCategory::pluck('name', 'id');
        $instructors = \App\Models\Instructor::pluck('name', 'id');

        return view('admin.courses.index', compact('courses', 'categories', 'instructors'));
    }

    public function show(Course $course)
    {
        return view('admin.courses.show', compact('course'));
    }

    public function create()
    {
        $categories = CourseCategory::all();
        $instructors = Instructor::all();
        $countries = Country::all();
        $documents = Document::where('documentable_type', null)->get();
        return view('admin.courses.create', compact('categories','instructors', 'countries', 'documents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // --- Course fields ---
            'course_category_id' => 'required|exists:course_categories,id',
            'title_en'           => 'required|string|max:255',
            'title_ar'           => 'required|string|max:255',
            'description_en'     => 'nullable|string',
            'description_ar'     => 'nullable|string',
            'featured'           => 'boolean',
            'is_published'       => 'boolean',

            // --- Schedule fields (nested) ---
            'schedules'                  => 'required|array|min:1',
            'schedules.*.title_en'       => 'required|string|max:255',
            'schedules.*.title_ar'       => 'nullable|string|max:255',
            'schedules.*.venue_en'       => 'nullable|string|max:255',
            'schedules.*.venue_ar'       => 'nullable|string|max:255',
            'schedules.*.start_date'     => 'required|date',
            'schedules.*.end_date'       => 'nullable|date|after_or_equal:schedules.*.start_date',
            'schedules.*.start_time'     => 'nullable|date_format:H:i',
            'schedules.*.end_time'       => 'nullable|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.days'           => 'nullable|string|max:100',
            'schedules.*.cost'           => 'nullable|numeric|min:0',
            'schedules.*.session'        => 'nullable|string|max:100',
            'schedules.*.nature'         => 'nullable|string|max:100',
            'schedules.*.language'       => 'nullable|string|max:100',
            'schedules.*.type'           => 'nullable|string|max:100',
            'schedules.*.country_id'     => 'nullable|exists:countries,id',
            'schedules.*.instructor_id'  => 'required|exists:instructors,id',
            'schedules.*.is_active'      => 'boolean',

            // --- Documents ---
            'image_document_id'   => 'nullable|integer|exists:documents,id',
            'new_image'           => 'nullable|mimes:jpg,jpeg,png,webp|max:5120',

            // schedule documents
            'schedules.*.outline_document_id' => 'nullable|integer|exists:documents,id',
            'schedules.*.flyer_document_id'   => 'nullable|integer|exists:documents,id',
            'schedules.*.new_outline'         => 'nullable|mimes:pdf,doc,docx|max:5120',
            'schedules.*.new_flyer'           => 'nullable|mimes:pdf,jpg,jpeg,png,webp|max:5120',
        ]);

        DB::beginTransaction();

        try {
            // --- Generate unique slug ---
            $slugBase = \Str::slug($validated['title_en']);
            $slug = $slugBase;
            $count = 1;
            while (Course::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $count++;
            }

            // --- Create Course ---
            $course = Course::create([
                'course_category_id' => $validated['course_category_id'],
                'title' => ['en' => $validated['title_en'], 'ar' => $validated['title_ar']],
                'description' => [
                    'en' => $validated['description_en'] ?? null,
                    'ar' => $validated['description_ar'] ?? null,
                ],
                'featured'     => $validated['featured'] ?? false,
                'is_published' => $validated['is_published'] ?? false,
                'slug'         => $slug,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Handle Course Image
            |--------------------------------------------------------------------------
            */
            if ($request->file('new_image') instanceof \Illuminate\Http\UploadedFile) {
                if ($course->image) {
                    Storage::disk('public')->delete($course->image->file_path);
                    $course->image->delete();
                }

                $file = $request->file('new_image');
                $path = $file->store('uploads/courses', 'public');

                $course->image()->create([
                    'name'           => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'file_path'      => $path,
                    'file_type'      => $this->getFileType($file),
                    'mime_type'      => $file->getMimeType(),
                    'size'           => $file->getSize(),
                    'document_type'  => 'image',
                ]);
            } elseif (!empty($validated['image_document_id'])) {
                $doc = \App\Models\Document::find($validated['image_document_id']);
                if ($doc && is_null($doc->documentable_type)) {
                    $doc->update([
                        'documentable_id'   => $course->id,
                        'documentable_type' => Course::class,
                        'document_type'     => 'image',
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Create Schedules
            |--------------------------------------------------------------------------
            */
            foreach ($validated['schedules'] as $index => $data) {
                $schedule = $course->schedules()->create([
                    'title'        => ['en' => $data['title_en'], 'ar' => $data['title_ar'] ?? null],
                    'venue'        => ['en' => $data['venue_en'] ?? null, 'ar' => $data['venue_ar'] ?? null],
                    'start_date'   => $data['start_date'],
                    'end_date'     => $data['end_date'] ?? null,
                    'start_time'   => $data['start_time'] ?? null,
                    'end_time'     => $data['end_time'] ?? null,
                    'days'         => $data['days'] ?? null,
                    'cost'         => $data['cost'] ?? null,
                    'language'     => $data['language'] ?? null,
                    'session'      => $data['session'] ?? null,
                    'nature'       => $data['nature'] ?? null,
                    'type'         => $data['type'] ?? null,
                    'country_id'   => $data['country_id'] ?? null,
                    'instructor_id'=> $data['instructor_id'],
                    'is_active'    => $data['is_active'] ?? true,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Handle Outline Document
                |--------------------------------------------------------------------------
                */
                $uploadedOutline = $request->file("schedules.$index.new_outline");

                if ($uploadedOutline instanceof \Illuminate\Http\UploadedFile) {
                    if ($schedule->outline) {
                        Storage::disk('public')->delete($schedule->outline->file_path);
                        $schedule->outline->delete();
                    }

                    $path = $uploadedOutline->store('uploads/outlines', 'public');
                    $schedule->outline()->create([
                        'name'           => pathinfo($uploadedOutline->getClientOriginalName(), PATHINFO_FILENAME),
                        'file_path'      => $path,
                        'file_type'      => $this->getFileType($uploadedOutline),
                        'mime_type'      => $uploadedOutline->getMimeType(),
                        'size'           => $uploadedOutline->getSize(),
                        'document_type'  => 'outline',
                    ]);
                } elseif (!empty($data['outline_document_id'])) {
                    $doc = \App\Models\Document::find($data['outline_document_id']);
                    if ($doc && is_null($doc->documentable_type)) {
                        $doc->update([
                            'documentable_id'   => $schedule->id,
                            'documentable_type' => CourseSchedule::class,
                            'document_type'     => 'outline',
                        ]);
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Handle Flyer Document
                |--------------------------------------------------------------------------
                */
                $uploadedFlyer = $request->file("schedules.$index.new_flyer");

                if ($uploadedFlyer instanceof \Illuminate\Http\UploadedFile) {
                    if ($schedule->flyer) {
                        Storage::disk('public')->delete($schedule->flyer->file_path);
                        $schedule->flyer->delete();
                    }

                    $path = $uploadedFlyer->store('uploads/flyers', 'public');
                    $schedule->flyer()->create([
                        'name'           => pathinfo($uploadedFlyer->getClientOriginalName(), PATHINFO_FILENAME),
                        'file_path'      => $path,
                        'file_type'      => $this->getFileType($uploadedFlyer),
                        'mime_type'      => $uploadedFlyer->getMimeType(),
                        'size'           => $uploadedFlyer->getSize(),
                        'document_type'  => 'flyer',
                    ]);
                } elseif (!empty($data['flyer_document_id'])) {
                    $doc = \App\Models\Document::find($data['flyer_document_id']);
                    if ($doc && is_null($doc->documentable_type)) {
                        $doc->update([
                            'documentable_id'   => $schedule->id,
                            'documentable_type' => CourseSchedule::class,
                            'document_type'     => 'flyer',
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.courses.index')
                ->with('success', __('Course and schedules created successfully.'));
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Course store failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->withErrors(['error' => 'Failed to create course.']);
        }
    }

    public function edit(Course $course)
    {
        $categories  = CourseCategory::all();
        $instructors = Instructor::all();
        $countries   = Country::all();

        // Fetch unassigned or course-related documents
        $documents = Document::whereNull('documentable_type')
            ->orWhere(function ($query) use ($course) {
                $query->where('documentable_type', Course::class)
                    ->where('documentable_id', $course->id);
            })
            ->get();

        // Prepare multilingual data for the main course
        $course->title_en       = $course->getTitles()['en'] ?? '';
        $course->title_ar       = $course->getTitles()['ar'] ?? '';
        $course->description_en = $course->getDescriptions()['en'] ?? '';
        $course->description_ar = $course->getDescriptions()['ar'] ?? '';

        // ✅ Eager load related models (including flyer & outline)
        $course->load([
            'schedules.flyer',
            'schedules.outline',
        ]);

        // ✅ Format schedules for the edit form
        $schedules = $course->schedules->map(function ($schedule) {
            return [
                'id'            => $schedule->id,
                'title'         => $schedule->getTitles(),
                'venue'         => $schedule->getVenues(),
                'start_date'    => $schedule->start_date 
                                    ? \Carbon\Carbon::parse($schedule->start_date)->format('Y-m-d') 
                                    : '',
                'end_date'      => $schedule->end_date 
                                    ? \Carbon\Carbon::parse($schedule->end_date)->format('Y-m-d') 
                                    : '',
                'start_time'    => $schedule->start_time 
                                    ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') 
                                    : '',
                'end_time'      => $schedule->end_time 
                                    ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') 
                                    : '',
                'days'          => $schedule->days,
                'cost'          => $schedule->cost,
                'language'      => $schedule->language ?? '',
                'session'       => $schedule->session,
                'instructor_id' => $schedule->instructor_id,
                'country_id'    => $schedule->country_id,
                'nature'        => $schedule->nature,
                'type'          => $schedule->type,
                // ✅ Include flyer and outline for convenience
                'flyer'         => $schedule->flyer,
                'outline'       => $schedule->outline,
            ];
        })->toArray();

        return view('admin.courses.edit', compact(
            'course',
            'categories',
            'instructors',
            'countries',
            'documents',
            'schedules'
        ));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            // --- Course fields ---
            'course_category_id' => 'required|exists:course_categories,id',
            'title_en'           => 'required|string|max:255',
            'title_ar'           => 'required|string|max:255',
            'description_en'     => 'nullable|string',
            'description_ar'     => 'nullable|string',
            'featured'           => 'boolean',
            'is_published'       => 'boolean',

            // --- Schedules ---
            'schedules'                 => 'required|array|min:1',
            'schedules.*.id'            => 'nullable|integer|exists:course_schedules,id',
            'schedules.*.title_en'      => 'required|string|max:255',
            'schedules.*.title_ar'      => 'nullable|string|max:255',
            'schedules.*.venue_en'      => 'nullable|string|max:255',
            'schedules.*.venue_ar'      => 'nullable|string|max:255',
            'schedules.*.start_date'    => 'required|date',
            'schedules.*.end_date'      => 'nullable|date',
            'schedules.*.start_time'    => 'nullable|date_format:H:i',
            'schedules.*.end_time'      => 'nullable|date_format:H:i',
            'schedules.*.days'          => 'nullable|string|max:100',
            'schedules.*.cost'          => 'nullable|numeric|min:0',
            'schedules.*.session'       => 'nullable|string|max:100',
            'schedules.*.nature'        => 'nullable|string|max:100',
            'schedules.*.language'      => 'nullable|string|max:100',
            'schedules.*.type'          => 'nullable|string|max:100',
            'schedules.*.country_id'    => 'nullable|exists:countries,id',
            'schedules.*.instructor_id' => 'required|exists:instructors,id',
            'schedules.*.is_active'     => 'boolean',

            // Files + flags
            'schedules.*.outline_document_id' => 'nullable|integer|exists:documents,id',
            'schedules.*.flyer_document_id'   => 'nullable|integer|exists:documents,id',
            'schedules.*.new_outline'         => 'nullable|mimes:pdf,doc,docx|max:5120',
            'schedules.*.new_flyer'           => 'nullable|mimes:pdf,jpg,jpeg,png,webp|max:5120',
            'schedules.*.remove_outline'      => 'nullable|boolean',
            'schedules.*.remove_flyer'        => 'nullable|boolean',

            // --- Course image ---
            'image_document_id' => 'nullable|integer|exists:documents,id',
            'new_image'         => 'nullable|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        DB::beginTransaction();

        try {
            // --- Update Course base data ---
            $courseData = [
                'course_category_id' => $validated['course_category_id'],
                'title' => [
                    'en' => $validated['title_en'],
                    'ar' => $validated['title_ar'],
                ],
                'description' => [
                    'en' => $validated['description_en'] ?? null,
                    'ar' => $validated['description_ar'] ?? null,
                ],
                'featured'     => $validated['featured'] ?? false,
                'is_published' => $validated['is_published'] ?? false,
            ];

            // regenerate slug only if title changed
            if (($course->title['en'] ?? null) !== $validated['title_en']) {
                $slugBase = \Str::slug($validated['title_en']);
                $slug = $slugBase;
                $count = 1;
                while (\App\Models\Course::where('slug', $slug)->where('id', '!=', $course->id)->exists()) {
                    $slug = $slugBase . '-' . $count++;
                }
                $courseData['slug'] = $slug;
            }

            $course->update($courseData);

            // --- Handle Course image (upload or attach) ---
            if ($request->file('new_image') instanceof \Illuminate\Http\UploadedFile) {
                if ($course->image) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($course->image->file_path);
                    $course->image->delete();
                }
                $file = $request->file('new_image');
                $path = $file->store('uploads/courses', 'public');
                $course->image()->create([
                    'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'file_path' => $path,
                    'file_type' => $this->getFileType($file) ?? null,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'document_type' => 'image',
                ]);
            } elseif (!empty($validated['image_document_id'])) {
                $doc = Document::find($validated['image_document_id']);
                if ($doc) {
                    // Detach previous image if exists
                    if ($course->image && $course->image->id !== $doc->id) {
                        $course->image->update([
                            'documentable_id' => null,
                            'documentable_type' => null,
                        ]);
                    }

                    // Attach new document
                    $doc->update([
                        'documentable_id' => $course->id,
                        'documentable_type' => Course::class,
                        'document_type' => 'image',
                    ]);
                }
            }

            // --- Schedules handling ---
            $existingIds = $course->schedules()->pluck('id')->toArray();
            $newIds = [];

            foreach ($validated['schedules'] as $index => $data) {
                // Prepare schedule data
                $scheduleData = [
                    'title'        => ['en' => $data['title_en'], 'ar' => $data['title_ar'] ?? null],
                    'venue'        => ['en' => $data['venue_en'] ?? null, 'ar' => $data['venue_ar'] ?? null],
                    'start_date'   => $data['start_date'],
                    'end_date'     => $data['end_date'] ?? null,
                    'start_time'   => $data['start_time'] ?? null,
                    'end_time'     => $data['end_time'] ?? null,
                    'days'         => $data['days'] ?? null,
                    'cost'         => $data['cost'] ?? null,
                    'language'     => $data['language'] ?? null,
                    'session'      => $data['session'] ?? null,
                    'nature'       => $data['nature'] ?? null,
                    'type'         => $data['type'] ?? null,
                    'country_id'   => $data['country_id'] ?? null,
                    'instructor_id'=> $data['instructor_id'],
                    'is_active'    => $data['is_active'] ?? true,
                ];

                if (!empty($data['id']) && in_array($data['id'], $existingIds)) {
                    // update existing
                    $schedule = $course->schedules()->where('id', $data['id'])->first();
                    $schedule->update($scheduleData);
                } else {
                    // create new
                    $schedule = $course->schedules()->create($scheduleData);
                }

                $newIds[] = $schedule->id;

                /*
                * ---------------------
                * FLYER (per-schedule)
                * ---------------------
                */
                $removeFlyer = !empty($data['remove_flyer']) && ($data['remove_flyer'] == 1 || $data['remove_flyer'] === true);
                $uploadedFlyer = $request->file("schedules.$index.new_flyer");

                if ($removeFlyer) {
                    if ($schedule->flyer) {
                        Storage::disk('public')->delete($schedule->flyer->file_path);
                        $schedule->flyer->delete();
                    }
                } elseif ($uploadedFlyer instanceof \Illuminate\Http\UploadedFile) {
                    if ($schedule->flyer) {
                        Storage::disk('public')->delete($schedule->flyer->file_path);
                        $schedule->flyer->delete();
                    }
                    $path = $uploadedFlyer->store('uploads/flyers', 'public');
                    $schedule->flyer()->create([
                        'name'          => pathinfo($uploadedFlyer->getClientOriginalName(), PATHINFO_FILENAME),
                        'file_path'     => $path,
                        'file_type'     => $this->getFileType($uploadedFlyer) ?? null,
                        'mime_type'     => $uploadedFlyer->getMimeType(),
                        'size'          => $uploadedFlyer->getSize(),
                        'document_type' => 'flyer',
                    ]);
                } elseif (!empty($data['flyer_document_id'])) {
                    $doc = Document::find($data['flyer_document_id']);
                    if ($doc) {
                        // Detach any existing flyer
                        if ($schedule->flyer && $schedule->flyer->id !== $doc->id) {
                            $schedule->flyer->update([
                                'documentable_id' => null,
                                'documentable_type' => null,
                            ]);
                        }

                        // Attach the selected existing document
                        $doc->update([
                            'documentable_id'   => $schedule->id,
                            'documentable_type' => CourseSchedule::class,
                            'document_type'     => 'flyer',
                        ]);
                    }
                }

                /*
                * ---------------------
                * OUTLINE (per-schedule)
                * ---------------------
                */
                $removeOutline = !empty($data['remove_outline']) && ($data['remove_outline'] == 1 || $data['remove_outline'] === true);
                $uploadedOutline = $request->file("schedules.$index.new_outline");

                if ($removeOutline) {
                    if ($schedule->outline) {
                        Storage::disk('public')->delete($schedule->outline->file_path);
                        $schedule->outline->delete();
                    }
                } elseif ($uploadedOutline instanceof \Illuminate\Http\UploadedFile) {
                    if ($schedule->outline) {
                        Storage::disk('public')->delete($schedule->outline->file_path);
                        $schedule->outline->delete();
                    }
                    $path = $uploadedOutline->store('uploads/outlines', 'public');
                    $schedule->outline()->create([
                        'name'          => pathinfo($uploadedOutline->getClientOriginalName(), PATHINFO_FILENAME),
                        'file_path'     => $path,
                        'file_type'     => $this->getFileType($uploadedOutline) ?? null,
                        'mime_type'     => $uploadedOutline->getMimeType(),
                        'size'          => $uploadedOutline->getSize(),
                        'document_type' => 'outline',
                    ]);
                } elseif (!empty($data['outline_document_id'])) {
                    $doc = Document::find($data['outline_document_id']);
                    if ($doc) {
                        if ($schedule->outline && $schedule->outline->id !== $doc->id) {
                            $schedule->outline->update([
                                'documentable_id' => null,
                                'documentable_type' => null,
                            ]);
                        }

                        $doc->update([
                            'documentable_id'   => $schedule->id,
                            'documentable_type' => CourseSchedule::class,
                            'document_type'     => 'outline',
                        ]);
                    }
                }
            }

            // --- Delete removed schedules safely ---
            $keptIds = array_filter($newIds); // only valid IDs
            $toDelete = array_diff($existingIds, $keptIds);
            
            if (!empty($validated['schedules']) && !empty($toDelete)) {
                $course->schedules()
                    ->whereIn('id', $toDelete)
                    ->get()
                    ->each(function ($schedule) {
                        if ($schedule->outline) {
                            Storage::disk('public')->delete($schedule->outline->file_path);
                            $schedule->outline->delete();
                        }
                        if ($schedule->flyer) {
                            Storage::disk('public')->delete($schedule->flyer->file_path);
                            $schedule->flyer->delete();
                        }
                        $schedule->delete();
                    });
            }

            DB::commit();

            return redirect()
                ->route('admin.courses.index')
                ->with('success', __('Course and schedules updated successfully.'));
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Course update failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->withErrors(['error' => 'Failed to update course.']);
        }
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully.');
    }

    private function getFileType($file)
    {
        $mime = $file->getMimeType();
        
        if (Str::contains($mime, 'image')) {
            return 'image';
        } elseif (Str::contains($mime, 'pdf')) {
            return 'pdf';
        } elseif (Str::contains($mime, 'word')) {
            return 'word';
        } elseif (Str::contains($mime, 'excel') || Str::contains($mime, 'sheet')) {
            return 'excel';
        } elseif (Str::contains($mime, 'powerpoint') || Str::contains($mime, 'presentation')) {
            return 'powerpoint';
        } else {
            return 'other';
        }
    }


     /**
     * Uploads file and updateOrCreate the morphOne relation.
     * $relationMethod is the method name on Course model (e.g. 'outline', 'coverLetter')
     * $documentType is the string stored in documents.document_type (e.g. 'outline', 'cover_letter')
     */
    protected function handleDocumentUpload(Request $request, Course $course, string $inputName, string $relationMethod, string $documentType)
    {
        if (! $request->hasFile($inputName)) {
            return;
        }

        $file = $request->file($inputName);
        $path = $file->store('documents', 'public');

        // delete previous file safely
        $existing = $course->{$relationMethod}()->first();
        if ($existing && !empty($existing->file_path) && Storage::disk('public')->exists($existing->file_path)) {
            Storage::disk('public')->delete($existing->file_path);
        }

        $fileType = method_exists($this, 'getFileType') ? $this->getFileType($file) : $file->getClientOriginalExtension();

        $course->{$relationMethod}()->updateOrCreate([], [
            'name'          => $file->getClientOriginalName(),
            'file_path'     => $path,
            'file_type'     => $fileType,
            'mime_type'     => $file->getMimeType(),
            'size'          => $file->getSize(),
            'document_type' => $documentType,
        ]);
    }


    /**
     * Attach an existing Document record by id to this course for the given document_type.
     * This will detach (orphan) any other document of the same type currently attached to this course.
     */
    protected function attachExistingDocument(int $documentId, Course $course, string $relationMethod, string $documentType)
    {
        // orphan any other document of this type attached to the course (but keep their DB record)
        Document::where('documentable_type', Course::class)
            ->where('documentable_id', $course->id)
            ->where('document_type', $documentType)
            ->where('id', '!=', $documentId)
            ->update([
                'documentable_id'   => null,
                'documentable_type' => null,
            ]);

        // attach the chosen document id (re-assign it to this course)
        Document::where('id', $documentId)->update([
            'documentable_id'   => $course->id,
            'documentable_type' => Course::class,
            'document_type'     => $documentType,
        ]);

        // If you want the relation method to immediately reflect this in the model instance,
        // you can optionally reload the relation: $course->load($relationMethod);
    }

}