<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::query();

        // Filter by author
        if ($request->filled('author_id')) {
            $query->where('author_id', $request->author_id);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('blog_category_id', $request->category_id);
        }

        // Filter by published status
        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('published', true)
                    ->where('published_at', '<=', now());
            } elseif ($request->status === 'draft') {
                $query->where(function($q){
                    $q->where('published', false)
                    ->orWhere('published_at', '>', now());
                });
            }
        }

        // Filter by featured
        if ($request->filled('featured')) {
            $query->where('featured', $request->featured);
        }

        // Search by multilingual title, content, or excerpt
        if ($request->filled('search')) {
            $search = $request->search;
            $locale = app()->getLocale();
            $query->where(function($q) use ($search, $locale) {
                $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(title, '$.\"$locale\"')) LIKE ?", ["%{$search}%"])
                ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(excerpt, '$.\"$locale\"')) LIKE ?", ["%{$search}%"])
                ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(content, '$.\"$locale\"')) LIKE ?", ["%{$search}%"]);
            });
        }

        $blogs = $query->latest()->paginate(20)->appends($request->query());

        $authors = \App\Models\User::pluck('name', 'id');
        $categories = \App\Models\BlogCategory::pluck('name', 'id');

        return view('admin.blogs.index', compact('blogs', 'authors', 'categories'));
    }


    public function show(Blog $blog)
    {
        return view('admin.blogs.show', compact('blog'));
    }

    public function create()
    {
        $categories = BlogCategory::all();
        $authors = User::role(['admin', 'staff'])->get();
        $documents = Document::whereNull('documentable_id')
        ->orWhere('document_type', 'blog_image')
        ->get();
        return view('admin.blogs.create', compact('categories', 'authors', 'documents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'excerpt_en' => 'nullable|string|max:500',
            'excerpt_ar' => 'nullable|string|max:500',
            'content_en' => 'required|string',
            'content_ar' => 'required|string',
            'tags_en' => 'nullable|string',
            'tags_ar' => 'nullable|string',
            'published_at' => 'nullable|date',
            'author_id' => 'required|exists:users,id',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'featured' => 'boolean',
            'published' => 'boolean',
            'meta_title_en' => 'nullable|string|max:255',
            'meta_title_ar' => 'nullable|string|max:255',
            'meta_description_en' => 'nullable|string|max:500',
            'meta_description_ar' => 'nullable|string|max:500',
            'blog_image_id' => 'nullable|exists:documents,id',
            'new_blog_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        // Generate unique slug from English title
        $slugBase = Str::slug($validated['title_en']);
        $slug = $slugBase;
        $count = 1;
        while (Blog::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $count++;
        }

        // Prepare multilingual blog data
        $blogData = [
            'slug' => $slug,
            'title' => [
                'en' => $validated['title_en'],
                'ar' => $validated['title_ar'],
            ],
            'excerpt' => [
                'en' => $validated['excerpt_en'] ?? null,
                'ar' => $validated['excerpt_ar'] ?? null,
            ],
            'content' => [
                'en' => $validated['content_en'],
                'ar' => $validated['content_ar'],
            ],
            'meta_title' => [
                'en' => $validated['meta_title_en'] ?? null,
                'ar' => $validated['meta_title_ar'] ?? null,
            ],
            'meta_description' => [
                'en' => $validated['meta_description_en'] ?? null,
                'ar' => $validated['meta_description_ar'] ?? null,
            ],
            'tags' => [
                'en' => $validated['tags_en'] ? array_map('trim', explode(',', $validated['tags_en'])) : null,
                'ar' => $validated['tags_ar'] ? array_map('trim', explode(',', $validated['tags_ar'])) : null,
            ],
            'published_at' => $validated['published_at'] ?? null,
            'author_id' => $validated['author_id'],
            'blog_category_id' => $validated['blog_category_id'],
            'featured' => $validated['featured'] ?? false,
            'published' => $validated['published'] ?? false,
            
        ];

        $blog = Blog::create($blogData);

        // 🔹 Attach selected blog image
        if (!empty($validated['blog_image_id'])) {
            Document::where('id', $validated['blog_image_id'])
                ->update([
                    'documentable_id'   => $blog->id,
                    'documentable_type' => Blog::class,
                    'document_type'     => 'blog_image',
                ]);
        }

        // 🔹 Upload new blog image
        if ($request->hasFile('new_blog_image')) {
            $file = $request->file('new_blog_image');
            $path = $file->store('blog-images', 'public');

            $blog->blogImage()->create([
                'name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'document_type' => 'blog_image',
            ]);
        }

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post created successfully.');
    }

    public function edit(Blog $blog)
    {
        $categories = BlogCategory::all();
        $authors = User::role(['admin', 'staff'])->get();
        $documents = Document::whereNull('documentable_id')
        ->orWhere('documentable_type', '!=', Blog::class)
        ->get();
        return view('admin.blogs.edit', compact('blog', 'categories', 'authors', 'documents'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'excerpt_en' => 'nullable|string|max:500',
            'excerpt_ar' => 'nullable|string|max:500',
            'content_en' => 'required|string',
            'content_ar' => 'required|string',
            'tags_en' => 'nullable|string',
            'tags_ar' => 'nullable|string',
            'published_at' => 'nullable|date',
            'author_id' => 'required|exists:users,id',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'featured' => 'boolean',
            'published' => 'boolean',
            'meta_title_en' => 'nullable|string|max:255',
            'meta_title_ar' => 'nullable|string|max:255',
            'meta_description_en' => 'nullable|string|max:500',
            'meta_description_ar' => 'nullable|string|max:500',
            'blog_image_id' => 'nullable|exists:documents,id',
            'new_blog_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'remove_blog_image' => 'nullable|boolean',
        ]);

        // Prepare multilingual blog data
        $blogData = [
            'title' => [
                'en' => $validated['title_en'],
                'ar' => $validated['title_ar'],
            ],
            'excerpt' => [
                'en' => $validated['excerpt_en'] ?? null,
                'ar' => $validated['excerpt_ar'] ?? null,
            ],
            'content' => [
                'en' => $validated['content_en'],
                'ar' => $validated['content_ar'],
            ],
            'meta_title' => [
                'en' => $validated['meta_title_en'] ?? null,
                'ar' => $validated['meta_title_ar'] ?? null,
            ],
            'meta_description' => [
                'en' => $validated['meta_description_en'] ?? null,
                'ar' => $validated['meta_description_ar'] ?? null,
            ],
            'tags' => [
                'en' => $validated['tags_en'] ? array_map('trim', explode(',', $validated['tags_en'])) : null,
                'ar' => $validated['tags_ar'] ? array_map('trim', explode(',', $validated['tags_ar'])) : null,
            ],
            'published_at' => $validated['published_at'] ?? $blog->published_at,
            'author_id' => $validated['author_id'],
            'blog_category_id' => $validated['blog_category_id'],
            'featured' => $validated['featured'] ?? false,
            'published' => $validated['published'] ?? false
        ];

        // Update the blog
        $blog->update($blogData);

        // ---- BLOG IMAGE ----

        // Remove existing image
        if (!empty($validated['remove_blog_image']) && $blog->blogImage) {
            Document::where('id', $blog->blogImage->id)->update([
                'documentable_id' => null,
                'documentable_type' => null,
            ]);
        }

        // Attach selected existing image
        if (!empty($validated['blog_image_id'])) {
            Document::where('id', $validated['blog_image_id'])->update([
                'documentable_id' => $blog->id,
                'documentable_type' => Blog::class,
                'document_type' => 'blog_image',
            ]);
        }

        // Upload new blog image (overrides selection)
        if ($request->hasFile('new_blog_image')) {
            $file = $request->file('new_blog_image');
            $path = $file->store('blog-images', 'public');

            $documentData = [
                'name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'document_type' => 'blog_image',
            ];

            if ($blog->blogImage) {
                $blog->blogImage->update($documentData);
            } else {
                $blog->blogImage()->create($documentData);
            }
        }

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog post updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        // Delete featured image if exists
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }
        
        $blog->delete();
        return redirect()->route('admin.blogs.index')->with('success', 'Blog post deleted successfully.');
    }
}