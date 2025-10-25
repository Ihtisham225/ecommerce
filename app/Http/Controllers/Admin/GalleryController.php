<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::query();

        // Filter by year
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // Filter by layout
        if ($request->filled('layout')) {
            $query->where('layout', $request->layout);
        }

        // Filter by featured
        if ($request->filled('featured')) {
            $query->where('featured', true);
        }

        // Search by title (multilingual support)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $search = $request->search;
                $q->whereJsonContains('title->en', $search)
                ->orWhereJsonContains('title->' . app()->getLocale(), $search);
            });
        }

        $galleries = $query->latest()->paginate(20)->appends($request->query());

        return view('admin.galleries.index', compact('galleries'));
    }


    public function create()
    {
        $documents = Document::where('documentable_type', null)->where('document_type', 'gallery_media')->get();
        return view('admin.galleries.create', ['documents' => $documents]);
    }

    public function show(Gallery $gallery)
    {
        return view('admin.galleries.show', ['gallery' => $gallery]);
    }

    public function store(Request $request)
    {
        $request->merge([
            'title_en' => mb_substr($request->input('title_en'), 0, 255),
            'title_ar' => mb_substr($request->input('title_ar'), 0, 255),
        ]);
        
        $validated = $request->validate([
            'title_en'       => 'required|string|max:255',
            'title_ar'       => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'year'           => 'required|digits:4',
            'layout'         => 'required|in:grid,slider,mixed',
            'featured'       => 'sometimes|boolean',
            'is_active'      => 'sometimes|boolean',

            //media
            'attach_media'   => 'nullable|array',
            'attach_media.*' => 'exists:documents,id',
            'new_media'      => 'nullable|array',
            'new_media.*'    => 'file|max:102400|mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm',
        ]);

        $gallery = Gallery::create([
            'title' => [
                'en' => $validated['title_en'],
                'ar' => $validated['title_ar'],
            ],
            'description' => [
                'en' => $validated['description_en'] ?? '',
                'ar' => $validated['description_ar'] ?? '',
            ],
            'year'      => $validated['year'],
            'layout'    => $validated['layout'],
            'featured'  => $validated['featured'] ?? false,
            'is_active' => $request->boolean('is_active'),
        ]);

        // Attach existing documents from library
        if (!empty($validated['attach_media'])) {
            Document::whereIn('id', $validated['attach_media'])
                ->update([
                    'documentable_id'   => $gallery->id,
                    'documentable_type' => Gallery::class,
                    'document_type'     => 'gallery_media',
                ]);
        }

        // Upload new media
        if ($request->hasFile('new_media')) {
            foreach ($request->file('new_media') as $file) {
                $path = $file->store('documents', 'public');

                $gallery->media()->create([
                    'name'             => $file->getClientOriginalName(),
                    'file_path'        => $path,
                    'file_type'        => $file->getClientOriginalExtension(),
                    'mime_type'        => $file->getMimeType(),
                    'size'             => $file->getSize(),
                    'document_type'    => 'gallery_media',
                ]);
            }
        }

        return redirect()->route('admin.galleries.index')->with('success', 'Gallery created successfully.');
    }

    public function edit(Gallery $gallery)
    {
        $documents = Document::where('documentable_type', null)->where('document_type', 'gallery_media')->get();
        return view('admin.galleries.edit', ['gallery' => $gallery, 'documents' => $documents]);
    }

    public function update(Request $request, Gallery $gallery)
    {
        $request->merge([
            'title_en' => mb_substr($request->input('title_en'), 0, 255),
            'title_ar' => mb_substr($request->input('title_ar'), 0, 255),
        ]);
        
        $validated = $request->validate([
            'title_en'       => 'required|string|max:255',
            'title_ar'       => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'year'           => 'required|digits:4',
            'layout'         => 'required|in:grid,slider,mixed',
            'featured'       => 'sometimes|boolean',
            'is_active'      => 'sometimes|boolean',

            // Media
            'attach_media'   => 'nullable|array',
            'attach_media.*' => 'exists:documents,id',
            'remove_media'   => 'nullable|array',
            'remove_media.*' => 'exists:documents,id',
            'new_media'      => 'nullable|array',
            'new_media.*'    => 'file|max:102400|mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm',
        ]);

        // 🔹 Update gallery fields
        $gallery->update([
            'title' => [
                'en' => $validated['title_en'],
                'ar' => $validated['title_ar'],
            ],
            'description' => [
                'en' => $validated['description_en'] ?? '',
                'ar' => $validated['description_ar'] ?? '',
            ],
            'year'      => $validated['year'],
            'layout'    => $validated['layout'],
            'featured'  => $validated['featured'] ?? $gallery->featured,   // keep old if not provided
            'is_active' => $validated['is_active'] ?? $gallery->is_active, // keep old if not provided
        ]);

        // 🔹 Remove media (DB + files)
        if (!empty($validated['remove_media'])) {
            $toRemove = Document::whereIn('id', $validated['remove_media'])
                ->where('documentable_id', $gallery->id)
                ->where('documentable_type', Gallery::class)
                ->get();

            foreach ($toRemove as $doc) {
                \Storage::disk('public')->delete($doc->file_path); // remove file from storage
                $doc->delete(); // remove record from DB
            }
        }

        // 🔹 Attach existing documents
        if (!empty($validated['attach_media'])) {
            Document::whereIn('id', $validated['attach_media'])
                ->update([
                    'documentable_id'   => $gallery->id,
                    'documentable_type' => Gallery::class,
                    'document_type'     => 'gallery_media',
                ]);
        }

        // 🔹 Upload new media
        if ($request->hasFile('new_media')) {
            foreach ($request->file('new_media') as $file) {
                $path = $file->store('documents', 'public');

                $gallery->media()->create([
                    'name'             => $file->getClientOriginalName(),
                    'file_path'        => $path,
                    'file_type'        => $file->getClientOriginalExtension(),
                    'mime_type'        => $file->getMimeType(),
                    'size'             => $file->getSize(),
                    'document_type'    => 'gallery_media',
                ]);
            }
        }

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery updated successfully.');
    }

    public function destroy(Gallery $gallery)
    {
        $gallery->media()->delete();
        $gallery->delete();
        return back()->with('success', 'Gallery deleted successfully.');
    }
}
