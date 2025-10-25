<?php
// app/Http/Controllers/Admin/BlogCommentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogCommentController extends Controller
{
    /**
     * Display a listing of the comments.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $query = BlogComment::with(['blog', 'parent', 'user']);
            $blogs = Blog::published()->pluck('title', 'id');
        } else {
            // Customers: only their own comments
            $query = BlogComment::with(['blog', 'parent'])
                ->where('user_id', $user->id);
            $blogs = Blog::whereHas('comments', fn($q) => $q->where('user_id', $user->id))
                        ->pluck('title', 'id');
        }

        // Filters
        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('approved', false);
            }
        }

        if ($request->filled('blog_id')) {
            $query->where('blog_id', $request->blog_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search, $user) {
                $q->where('comment', 'like', "%{$search}%")
                ->orWhereHas('blog', fn($bq) => $bq->where('title', 'like', "%{$search}%"));
                
                if ($user->hasRole('admin')) {
                    $q->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%")
                                                    ->orWhere('email', 'like', "%{$search}%"));
                }
            });
        }

        $comments = $query->latest()->paginate(20);

        return view('admin.blog-comments.index', compact('comments', 'blogs', 'user'));
    }

    /**
     * Display a single blog comment with its replies.
     */
    public function show(Request $request, BlogComment $blogComment)
    {
        // Eager load user, blog, and replies with their users
        $blogComment->load(['user', 'blog', 'replies.user']);

        // For admin filter: get all users who commented on this blog
        $users = User::whereHas('blogComments', function ($q) use ($blogComment) {
            $q->where('blog_id', $blogComment->blog_id);
        })->get();

        $selectedUserId = $request->query('user');

        // If filtering by user, filter replies as well
        if ($selectedUserId) {
            $blogComment->replies = $blogComment->replies->where('user_id', $selectedUserId);
        }

        return view('admin.blog-comments.show', [
            'blog' => $blogComment->blog,
            'comments' => collect([$blogComment]), // wrap single comment for Blade loop
            'users' => $users,
            'selectedUserId' => $selectedUserId,
        ]);
    }

    /**
     * Show the form for editing the specified comment.
     */
    public function edit(BlogComment $blogComment)
    {
        $blogComment->load(['blog', 'parent', 'replies', 'user']);
        return view('admin.blog-comments.edit', compact('blogComment'));
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, BlogComment $blogComment)
    {
        $validated = $request->validate([
            'comment'  => 'required|string|min:5|max:1000',
            'approved' => 'boolean',
        ]);
        
        $blogComment->update($validated);

        return redirect()
            ->route('admin.blog-comments.index')
            ->with('success', 'Comment updated successfully.');
    }

    /**
     * Approve the specified comment.
     */
    public function approve(BlogComment $comment)
    {
        $comment->update(['approved' => true]);

        return redirect()
            ->route('admin.blog-comments.index')
            ->with('success', 'Comment approved successfully.');
    }

    /**
     * Bulk approve comments.
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'comment_ids'   => 'required|array',
            'comment_ids.*' => 'exists:blog_comments,id',
        ]);

        BlogComment::whereIn('id', $request->comment_ids)
            ->update(['approved' => true]);

        return redirect()
            ->route('admin.blog-comments.index')
            ->with('success', 'Selected comments approved successfully.');
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(BlogComment $comment)
    {
        $comment->delete();

        return redirect()
            ->route('admin.blog-comments.index')
            ->with('success', 'Comment deleted successfully.');
    }

    /**
     * Bulk delete comments.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'comment_ids'   => 'required|array',
            'comment_ids.*' => 'exists:blog_comments,id',
        ]);

        BlogComment::whereIn('id', $request->comment_ids)->delete();

        return redirect()
            ->route('admin.blog-comments.index')
            ->with('success', 'Selected comments deleted successfully.');
    }

    /**
     * Show deleted comments.
     */
    public function trashed()
    {
        $comments = BlogComment::onlyTrashed()
            ->with(['blog', 'parent', 'user'])
            ->latest()
            ->paginate(20);

        return view('admin.blog-comments.trashed', compact('comments'));
    }

    /**
     * Restore a deleted comment.
     */
    public function restore($id)
    {
        $comment = BlogComment::onlyTrashed()->findOrFail($id);
        $comment->restore();

        return redirect()
            ->route('admin.blog-comments.trashed')
            ->with('success', 'Comment restored successfully.');
    }

    /**
     * Permanently delete a comment.
     */
    public function forceDelete($id)
    {
        $comment = BlogComment::onlyTrashed()->findOrFail($id);
        $comment->forceDelete();

        return redirect()
            ->route('admin.blog-comments.trashed')
            ->with('success', 'Comment permanently deleted.');
    }

    /**
     * Get comment statistics.
     */
    public function stats()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            $totalComments    = BlogComment::count();
            $approvedComments = BlogComment::where('approved', true)->count();
            $pendingComments  = BlogComment::where('approved', false)->count();
            $recentComments   = BlogComment::with(['blog', 'user'])->latest()->take(10)->get();
            $commentsByBlog   = Blog::withCount('comments')
                                    ->having('comments_count', '>', 0)
                                    ->orderBy('comments_count', 'desc')
                                    ->take(10)
                                    ->get();
        } else {
            $totalComments    = BlogComment::where('user_id', $user->id)->count();
            $approvedComments = BlogComment::where('user_id', $user->id)->where('approved', true)->count();
            $pendingComments  = BlogComment::where('user_id', $user->id)->where('approved', false)->count();
            $recentComments   = BlogComment::with('blog')->where('user_id', $user->id)->latest()->take(10)->get();
            $commentsByBlog   = Blog::whereHas('comments', fn($q) => $q->where('user_id', $user->id))
                                    ->withCount(['comments' => fn($q) => $q->where('user_id', $user->id)])
                                    ->get();
        }

        return view('admin.blog-comments.stats', compact(
            'totalComments',
            'approvedComments',
            'pendingComments',
            'recentComments',
            'commentsByBlog',
            'user'
        ));
    }
}
