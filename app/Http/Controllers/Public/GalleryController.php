<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['year', 'layout', 'featured']);

        $galleries = Gallery::with('media')
            ->where('is_active', true)
            ->filter($filters)
            ->latest()
            ->paginate(12);

        $featuredGalleries = Gallery::with('media')
            ->where('is_active', true)
            ->where('featured', true)
            ->latest()
            ->take(10)
            ->get();

        $years = Gallery::select('year')
            ->where('is_active', true)
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('public.galleries.index', compact('galleries', 'featuredGalleries', 'years', 'filters'));
    }

    public function show(Gallery $gallery)
    {
        if (!$gallery->is_active) {
            abort(404);
        }

        $gallery->load('media');
        return view('public.galleries.show', compact('gallery'));
    }
}
