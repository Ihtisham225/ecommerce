<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Document;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $query = Country::query();

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
            $locale = app()->getLocale();
            $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"$locale\"')) LIKE ?", ["%{$search}%"]);
        }

        $countries = $query->latest()->paginate(20)->appends($request->query());

        return view('admin.countries.index', compact('countries'));
    }


    public function show(Country $country)
    {
        $country->load('sponsors', 'countryFlag');
        
        // Extract multilingual data for display
        $country->names = $country->getNames();
        
        return view('admin.countries.show', compact('country'));
    }

    public function create()
    {
        $documents = Document::where('document_type', 'country_flag')->get();
        return view('admin.countries.create', compact('documents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'code' => 'required|string|max:10',
            'currency' => 'nullable|string|max:255',
            'currency_code' => 'nullable|string|max:10',
            'is_active' => 'boolean',
            'flag_document_id' => 'nullable|integer|exists:documents,id',
            'new_flag' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ]);

        

        // Check if country with same code already exists
        $existingCountry = Country::where('code', $validated['code'])->first();

        if ($existingCountry) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'A country with this code already exists.');
        }

        $country = Country::create([
            'name' => [
                'en' => $validated['name']['en'],
                'ar' => $validated['name']['ar'],
            ],
            'code' => $validated['code'],
            'currency' => $validated['currency'],
            'currency_code' => $validated['currency_code'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Attach selected flag
        if (!empty($validated['flag_document_id'])) {
            Document::where('id', $validated['flag_document_id'])
                ->update([
                    'documentable_id' => $country->id,
                    'documentable_type' => Country::class,
                    'document_type' => 'country_flag',
                ]);
        }

        // Upload new flag
        if ($request->hasFile('new_flag')) {
            $file = $request->file('new_flag');
            $path = $file->store('documents', 'public');

            $country->countryFlag()->create([
                'name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'document_type' => 'country_flag',
            ]);
        }

        return redirect()->route('admin.countries.index')
            ->with('success', 'Country created successfully.');
    }

    public function edit(Country $country)
    {
        $documents = Document::where('document_type', 'country_flag')->get();
        $country->load('countryFlag');
        
        // Extract multilingual data for form
        $country->name_en = $country->getNames()['en'] ?? '';
        $country->name_ar = $country->getNames()['ar'] ?? '';
        
        return view('admin.countries.edit', compact('country', 'documents'));
    }

    public function update(Request $request, Country $country)
    {
        $validated = $request->validate([
            'name.en'        => 'required|string|max:255',
            'name.ar'        => 'required|string|max:255',
            'code'           => 'required|string|max:10',
            'currency'       => 'nullable|string|max:255',
            'currency_code'  => 'nullable|string|max:10',
            'is_active'      => 'boolean',
            'flag_document_id' => 'nullable|integer|exists:documents,id',
            'new_flag'       => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'remove_flag'    => 'nullable|boolean',
        ]);

        // Update main fields
        $country->update([
            'name' => [
                'en' => $validated['name']['en'],
                'ar' => $validated['name']['ar'],
            ],
            'code'          => strtoupper($validated['code']),
            'currency'      => $validated['currency'] ?? null,
            'currency_code' => $validated['currency_code'] ? strtoupper($validated['currency_code']) : null,
            'is_active'     => $validated['is_active'] ?? true,
        ]);

        // Remove flag if requested
        if (!empty($validated['remove_flag']) && $country->countryFlag) {
            Document::where('id', $country->countryFlag->id)->update([
                'documentable_id'   => null,
                'documentable_type' => null,
            ]);
        }

        // Attach selected existing flag
        if (!empty($validated['flag_document_id'])) {
            Document::where('id', $validated['flag_document_id'])->update([
                'documentable_id'   => $country->id,
                'documentable_type' => Country::class,
                'document_type'     => 'country_flag',
            ]);
        }

        // Upload new flag (overrides both remove & existing)
        if ($request->hasFile('new_flag')) {
            $file = $request->file('new_flag');
            $path = $file->store('documents', 'public');

            $documentData = [
                'name'          => $file->getClientOriginalName(),
                'file_path'     => $path,
                'file_type'     => $file->getClientOriginalExtension(),
                'size'     => $file->getSize(),
                'mime_type'     => $file->getMimeType(),
                'document_type' => 'country_flag',
            ];

            if ($country->countryFlag) {
                $country->countryFlag->update($documentData);
            } else {
                $country->countryFlag()->create($documentData);
            }
        }

        return redirect()->route('admin.countries.index')
            ->with('success', 'Country updated successfully.');
    }


    public function destroy(Country $country)
    {
        // Check if country has sponsors
        if ($country->sponsors()->count() > 0) {
            return redirect()->route('admin.countries.index')
                ->with('error', 'Cannot delete country with associated sponsors. Please reassign or delete the sponsors first.');
        }

        // Delete flag if exists
        if ($country->countryFlag) {
            $country->countryFlag->delete();
        }

        $country->delete();

        return redirect()->route('admin.countries.index')
            ->with('success', 'Country deleted successfully.');
    }
}