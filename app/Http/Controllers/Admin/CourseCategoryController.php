<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseCategoryController extends Controller
{
    public function index()
    {
        $categories = CourseCategory::with('parent')->latest()->paginate(20);
        return view('admin.course-categories.index', compact('categories'));
    }

    public function show(CourseCategory $courseCategory)
    {
        $courseCategory->load(['parent', 'children', 'courses']);
        return view('admin.course-categories.show', compact('courseCategory'));
    }

    public function create()
    {
        $allCategories = CourseCategory::with('children')->get();
        $courseCategory = new CourseCategory();

        return view('admin.course-categories.create', compact('allCategories', 'courseCategory'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en'        => 'required|string|max:255',
            'name_ar'        => 'required|string|max:255',
            'description_en' => 'nullable|string|max:1000',
            'description_ar' => 'nullable|string|max:1000',
            'parent_id'      => 'nullable|exists:course_categories,id',
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
            'slug' => Str::slug($validated['name_en']),
            'parent_id' => $validated['parent_id'] ?? null,
        ];

        // Ensure unique slug
        $slugCount = CourseCategory::where('slug', $multilingualData['slug'])->count();
        if ($slugCount > 0) {
            $multilingualData['slug'] .= '-' . ($slugCount + 1);
        }

        CourseCategory::create($multilingualData);

        return redirect()->route('admin.course-categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(CourseCategory $courseCategory)
    {
        $allCategories = CourseCategory::with('children')->where('id', '!=', $courseCategory->id)->get();

        return view('admin.course-categories.edit', compact('allCategories', 'courseCategory'));
    }

    public function update(Request $request, CourseCategory $courseCategory)
    {
        $validated = $request->validate([
            'name_en'        => 'required|string|max:255',
            'name_ar'        => 'required|string|max:255',
            'description_en' => 'nullable|string|max:1000',
            'description_ar' => 'nullable|string|max:1000',
            'parent_id'      => 'nullable|exists:course_categories,id|not_in:' . $courseCategory->id,
        ]);

        $multilingualData = [
            'name' => [
                'en' => $validated['name_en'],
                'ar' => $validated['name_ar'],
            ],
            'description' => [
                'en' => $validated['description_en'] ?? null,
                'ar' => $validated['description_ar'] ?? null,
            ],
            'parent_id' => $validated['parent_id'] ?? null,
        ];

        // Update slug only if English name changed
        if ($courseCategory->getNames()['en'] !== $validated['name_en']) {
            $newSlug = Str::slug($validated['name_en']);
            $slugCount = CourseCategory::where('slug', $newSlug)
                ->where('id', '!=', $courseCategory->id)
                ->count();

            $multilingualData['slug'] = $slugCount > 0
                ? $newSlug . '-' . ($slugCount + 1)
                : $newSlug;
        }

        $courseCategory->update($multilingualData);

        return redirect()->route('admin.course-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(CourseCategory $courseCategory)
    {
        $courseCategory->delete();

        return redirect()->route('admin.course-categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}