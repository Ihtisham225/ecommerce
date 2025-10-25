<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use App\Models\Country;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SponsorController extends Controller
{
    public function index(Request $request)
    {
        $query = Sponsor::query();

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by country
        if ($request->filled('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        // Search by name (multilingual-aware)
        if ($request->filled('search')) {
            $search = $request->search;
            $locale = app()->getLocale();
            $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"$locale\"')) LIKE ?", ["%{$search}%"]);
        }

        $sponsors = $query->latest()->paginate(20)->appends($request->query());

        // Fetch all countries for filter dropdown
        $countries = Country::all();

        return view('admin.sponsors.index', compact('sponsors', 'countries'));
    }


    public function show(Sponsor $sponsor)
    {
        $sponsor->load('country', 'sponsorLogo');
        return view('admin.sponsors.show', compact('sponsor'));
    }

    public function create()
    {
        $countries = Country::all();
        $documents = Document::where('document_type', 'sponsor_logo')->get();
        $locales = config('app.available_locales', ['en' => 'English']);
        
        return view('admin.sponsors.create', compact('countries', 'documents', 'locales'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'country_id' => 'nullable|exists:countries,id',
            'is_active' => 'boolean',
            'logo_document_id' => 'nullable|integer|exists:documents,id',
            'new_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ]);

        $sponsor = Sponsor::create([
            'name' => [
                'en' => $validated['name']['en'],
                'ar' => $validated['name']['ar'],
            ],
            'description' => [
                'en' => $validated['description']['en'] ?? null,
                'ar' => $validated['description']['ar'] ?? null,
            ],
            'website' => $validated['website'] ?? null,
            'contact_email' => $validated['contact_email'] ?? null,
            'contact_phone' => $validated['contact_phone'] ?? null,
            'country_id' => $validated['country_id'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Attach selected logo
        if (!empty($validated['logo_document_id'])) {
            Document::where('id', $validated['logo_document_id'])
                ->update([
                    'documentable_id' => $sponsor->id,
                    'documentable_type' => Sponsor::class,
                    'document_type' => 'sponsor_logo',
                ]);
        }

        // Upload new logo
        if ($request->hasFile('new_logo')) {
            $file = $request->file('new_logo');
            $path = $file->store('documents', 'public');

            $sponsor->sponsorLogo()->create([
                'name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'document_type' => 'sponsor_logo',
            ]);
        }

        return redirect()->route('admin.sponsors.index')
            ->with('success', 'Sponsor created successfully.');
    }


    public function edit(Sponsor $sponsor)
    {
        $countries = Country::all();
        $documents = Document::where('document_type', 'sponsor_logo')->get();
        $sponsor->load('sponsorLogo');
        $locales = config('app.available_locales', ['en' => 'English']);
        
        // Get multilingual data
        $nameData = $sponsor->getNames();
        $descriptionData = $sponsor->getDescriptions();
        
        return view('admin.sponsors.edit', compact('sponsor', 'countries', 'documents', 'locales', 'nameData', 'descriptionData'));
    }

    public function update(Request $request, Sponsor $sponsor)
    {
        $validated = $request->validate([
            'name.en'          => 'required|string|max:255',
            'name.ar'          => 'required|string|max:255',
            'description.en'   => 'nullable|string',
            'description.ar'   => 'nullable|string',
            'website'          => 'nullable|url|max:255',
            'contact_email'    => 'nullable|email|max:255',
            'contact_phone'    => 'nullable|string|max:50',
            'country_id'       => 'nullable|exists:countries,id',
            'is_active'        => 'boolean',
            'logo_document_id' => 'nullable|integer|exists:documents,id',
            'new_logo'         => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'remove_logo'      => 'nullable|boolean',
        ]);

        // --- Update main fields ---
        $sponsor->update([
            'name' => [
                'en' => $validated['name']['en'],
                'ar' => $validated['name']['ar'],
            ],
            'description' => [
                'en' => $validated['description']['en'] ?? null,
                'ar' => $validated['description']['ar'] ?? null,
            ],
            'website'       => $validated['website'] ?? null,
            'contact_email' => $validated['contact_email'] ?? null,
            'contact_phone' => $validated['contact_phone'] ?? null,
            'country_id'    => $validated['country_id'] ?? null,
            'is_active'     => $validated['is_active'] ?? true,
        ]);

        // --- Remove logo if requested ---
        if (!empty($validated['remove_logo']) && $sponsor->sponsorLogo) {
            Document::where('id', $sponsor->sponsorLogo->id)->update([
                'documentable_id'   => null,
                'documentable_type' => null,
            ]);
        }

        // --- Attach existing logo ---
        if (!empty($validated['logo_document_id'])) {
            Document::where('id', $validated['logo_document_id'])->update([
                'documentable_id'   => $sponsor->id,
                'documentable_type' => Sponsor::class,
                'document_type'     => 'sponsor_logo',
            ]);
        }

        // --- Upload new logo (overrides) ---
        if ($request->hasFile('new_logo')) {
            $file = $request->file('new_logo');
            $path = $file->store('documents', 'public');

            $data = [
                'name'          => $file->getClientOriginalName(),
                'file_path'     => $path,
                'file_type'     => $file->getClientOriginalExtension(),
                'size'          => $file->getSize(),
                'mime_type'     => $file->getMimeType(),
                'document_type' => 'sponsor_logo',
            ];

            if ($sponsor->sponsorLogo) {
                $sponsor->sponsorLogo->update($data);
            } else {
                $sponsor->sponsorLogo()->create($data);
            }
        }

        return redirect()->route('admin.sponsors.index')
            ->with('success', 'Sponsor updated successfully.');
    }


    public function destroy(Sponsor $sponsor)
    {
        // Delete logo if attached
        if ($sponsor->sponsorLogo) {
            $sponsor->sponsorLogo->delete();
        }

        $sponsor->delete();

        return redirect()->route('admin.sponsors.index')
            ->with('success', 'Sponsor deleted successfully.');
    }
}