<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductMediaController extends Controller
{
    public function index(Product $product)
    {
        return response()->json($product->documents()->get());
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'file' => 'required|file|max:5120', // accept any file type, not just images
        ]);

        $file = $request->file('file');

        $path = $file->store('products', 'public');

        $document = $product->documents()->create([
            'name'             => $file->getClientOriginalName(),
            'file_path'        => $path,
            'file_type'        => $file->getClientOriginalExtension(),
            'mime_type'        => $file->getMimeType(),
            'size'             => $file->getSize(),
            'document_type'    => 'product_media',
            'documentable_id'  => $product->id,
            'documentable_type'=> Product::class,
        ]);

        return response()->json([
            'media'   => $document,
            'message' => 'Media uploaded successfully.',
        ]);
    }

    public function destroy(Product $product, Document $media)
    {
        // Ensure the document actually belongs to this product
        if ($media->documentable_id !== $product->id || $media->documentable_type !== Product::class) {
            return response()->json(['message' => 'Invalid document reference.'], 403);
        }

        Storage::disk('public')->delete($media->file_path);
        $media->delete();

        return response()->json(['message' => 'Media deleted successfully.']);
    }
}
