<?php
// app/Http/Controllers/Public/BlogCommentController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BlogCommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, $slug)
    {
        // Require authentication
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to comment.');
        }

        // Find the blog post
        $blog = Blog::where('slug', $slug)
                    ->where('published', true)
                    ->firstOrFail();

        // Validate the request
        $validator = Validator::make($request->all(), [
            'comment'   => 'required|string|min:5|max:1000',
            'parent_id' => 'nullable|exists:blog_comments,id',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('blogs.show', $blog->slug)
                ->withErrors($validator)
                ->withInput()
                ->withFragment('comments');
        }

        // Create the comment
        BlogComment::create([
            'blog_id'    => $blog->id,
            'user_id'    => Auth::id(),
            'parent_id'  => $request->parent_id,
            'comment'    => $request->comment,
            'approved'   => false, // Comments need approval by default
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()
            ->route('blogs.show', $blog->slug)
            ->with('success', 'Your comment has been submitted and is awaiting moderation.')
            ->withFragment('comments');
    }

    /**
     * Store a reply to a comment.
     */
    public function storeReply(Request $request, $slug, $commentId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to reply.');
        }

        $blog = Blog::where('slug', $slug)
                    ->where('published', true)
                    ->firstOrFail();

        $parentComment = BlogComment::where('blog_id', $blog->id)
                                    ->where('id', $commentId)
                                    ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|min:5|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('blogs.show', $blog->slug)
                ->withErrors($validator)
                ->withInput()
                ->withFragment('comment-' . $commentId);
        }

        BlogComment::create([
            'blog_id'    => $blog->id,
            'user_id'    => Auth::id(),
            'parent_id'  => $commentId,
            'comment'    => $request->comment,
            'approved'   => false,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()
            ->route('blogs.show', $blog->slug)
            ->with('success', 'Your reply has been submitted and is awaiting moderation.')
            ->withFragment('comment-' . $commentId);
    }

    /**
     * Show the form for editing a comment.
     */
    public function edit($slug, $commentId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $blog = Blog::where('slug', $slug)
                    ->where('published', true)
                    ->firstOrFail();

        $comment = BlogComment::where('blog_id', $blog->id)
                              ->where('id', $commentId)
                              ->firstOrFail();

        // Ensure the logged-in user owns the comment
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('frontend.blogs.comments.edit', compact('blog', 'comment'));
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, $slug, $commentId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $blog = Blog::where('slug', $slug)
                    ->where('published', true)
                    ->firstOrFail();

        $comment = BlogComment::where('blog_id', $blog->id)
                              ->where('id', $commentId)
                              ->firstOrFail();

        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|min:5|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('blogs.comments.edit', [$blog->slug, $comment->id])
                ->withErrors($validator)
                ->withInput();
        }

        $comment->update([
            'comment'  => $request->comment,
            'approved' => false, // needs re-approval
        ]);

        return redirect()
            ->route('blogs.show', $blog->slug)
            ->with('success', 'Your comment has been updated and is awaiting moderation.')
            ->withFragment('comment-' . $comment->id);
    }
}
