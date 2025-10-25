<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::latest()->paginate(20);
        return view('admin.blog-categories.index', compact('categories'));
    }

    public function show(BlogCategory $blogCategory)
    {
        return view('admin.blog-categories.show', compact('blogCategory'));
    }

    public function create()
    {
        return view('admin.blog-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en'        => 'required|string|max:255',
            'name_ar'        => 'required|string|max:255',
            'description_en' => 'nullable|string|max:1000',
            'description_ar' => 'nullable|string|max:1000',
        ]);

        // Prepare multilingual data
        $multilingualData = [
            'name' => [
                'en' => $validated['name_en'],
                'ar' => $validated['name_ar'],
            ],
            'description' => [
                'en' => $validated['description_en'] ?? null,
                'ar' => $validated['description_ar'] ?? null,
            ],
            'slug' => Str::slug($validated['name_en']) // Generate slug from English name
        ];

        // Check for unique slug
        $slugCount = BlogCategory::where('slug', $multilingualData['slug'])->count();
        if ($slugCount > 0) {
            $multilingualData['slug'] = $multilingualData['slug'] . '-' . ($slugCount + 1);
        }

        BlogCategory::create($multilingualData);

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(BlogCategory $blogCategory)
    {
        // Get raw multilingual data
        $blogCategory->name_en = $blogCategory->getNames()['en'] ?? '';
        $blogCategory->name_ar = $blogCategory->getNames()['ar'] ?? '';
        $blogCategory->description_en = $blogCategory->getDescriptions()['en'] ?? '';
        $blogCategory->description_ar = $blogCategory->getDescriptions()['ar'] ?? '';

        return view('admin.blog-categories.edit', compact('blogCategory'));
    }

    public function update(Request $request, BlogCategory $blogCategory)
    {
        $validated = $request->validate([
            'name_en'        => 'required|string|max:255',
            'name_ar'        => 'required|string|max:255',
            'description_en' => 'nullable|string|max:1000',
            'description_ar' => 'nullable|string|max:1000',
        ]);

        // Prepare multilingual data
        $multilingualData = [
            'name' => [
                'en' => $validated['name_en'],
                'ar' => $validated['name_ar'],
            ],
            'description' => [
                'en' => $validated['description_en'] ?? null,
                'ar' => $validated['description_ar'] ?? null,
            ],
        ];

        // Update slug only if English name changed
        if ($blogCategory->getNames()['en'] !== $validated['name_en']) {
            $newSlug = Str::slug($validated['name_en']);
            
            // Check for unique slug
            $slugCount = BlogCategory::where('slug', $newSlug)
                ->where('id', '!=', $blogCategory->id)
                ->count();
                
            $multilingualData['slug'] = $slugCount > 0 ? $newSlug . '-' . ($slugCount + 1) : $newSlug;
        }

        $blogCategory->update($multilingualData);

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(BlogCategory $blogCategory)
    {
        $blogCategory->delete();

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}