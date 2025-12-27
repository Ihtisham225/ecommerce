<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        // Base query with relationships
        $posts = Blog::with(['author', 'blogCategory', 'blogImage'])
            ->published();

        // Apply filters if they exist in the request
        if ($request->has('category') && !empty($request->category)) {
            $posts->where('blog_category_id', $request->category);
        }
        
        if ($request->has('author') && !empty($request->author)) {
            $posts->where('author_id', $request->author);
        }
        
        if ($request->has('date') && !empty($request->date)) {
            $now = now();
            
            switch($request->date) {
                case 'month':
                    $posts->whereYear('published_at', $now->year)
                        ->whereMonth('published_at', $now->month);
                    break;
                case 'quarter':
                    $quarter = ceil($now->month / 3);
                    $startMonth = ($quarter - 1) * 3 + 1;
                    $endMonth = $startMonth + 2;
                    
                    $posts->whereYear('published_at', $now->year)
                        ->whereBetween('published_at', [
                            $now->copy()->month($startMonth)->startOfMonth(),
                            $now->copy()->month($endMonth)->endOfMonth()
                        ]);
                    break;
                case 'year':
                    $posts->whereYear('published_at', $now->year);
                    break;
            }
        }
        
        if ($request->has('status') && !empty($request->status)) {
            switch($request->status) {
                case 'featured':
                    $posts->featured();
                    break;
                case 'recent':
                    $posts->latest();
                    break;
                case 'popular':
                    $posts->orderBy('views', 'desc');
                    break;
            }
        } else {
            $posts->latest();
        }

        // Get the results
        $posts = $posts->paginate($request->view == 'list' ? 12 : 9);

        // Sidebar + filters data
        $categories = BlogCategory::withCount('blogs')->get();

        $authors = User::whereHas('blogs', function ($q) {
            $q->published();
        })->get();

        // Collect tags from published blogs and count popularity
        $popularTags = Blog::published()
            ->pluck('tags')
            ->flatten()
            ->filter()
            ->countBy()
            ->sortDesc()
            ->keys()
            ->take(20);

        // Hero section stats
        $totalPosts = Blog::published()->count();
        $totalAuthors = User::whereHas('blogs', function ($q) {
            $q->published();
        })->count();
        $totalCategories = BlogCategory::count();

        return view('frontend.blogs.index', compact(
            'posts',
            'categories',
            'authors',
            'popularTags',
            'totalPosts',
            'totalAuthors',
            'totalCategories'
        ));
    }

    public function show(Blog $post)
    {
        // Get the blog post by slug
        $post = Blog::with(['author', 'blogCategory', 'blogImage', 'approvedComments'])
            ->published()
            ->firstOrFail();

        // Increment views
        $post->incrementViews();

        // Sidebar data
        $categories = BlogCategory::withCount('blogs as posts_count')->get();

        $recentPosts = Blog::published()
            ->latest()
            ->take(5)
            ->get();

        // Collect all tags and find the most common ones
        $popularTags = Blog::published()
            ->pluck('tags')
            ->flatten()
            ->filter()
            ->countBy()
            ->sortDesc()
            ->keys()
            ->take(10);

        return view('frontend.blogs.show', compact('post', 'categories', 'recentPosts', 'popularTags'));
    }
}
