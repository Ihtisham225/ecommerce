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

    public function ajaxDestroy(Document $document)
    {
        try {
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete document.',
            ], 500);
        }
    }

    public function ajaxUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // max 10MB
            'document_type' => 'required|in:main,gallery',
            'product_id' => 'required|exists:products,id',
        ]);

        $file = $request->file('file');
        $path = $file->store('documents', 'public');

        $document = Document::create([
            'name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(), // file extension
            'document_type' => $request->document_type,        // main or gallery
            'documentable_id' => $request->product_id,
            'documentable_type' => \App\Models\Product::class,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        return response()->json([
            'success' => true,
            'document' => $document, // includes `url`, `fileSize`, `fileIcon`
        ]);
    }

    public function setAsMain(Document $document, Request $request)
    {
        try {
            $productId = $request->product_id;

            // Get current main image
            $currentMain = Document::where('documentable_id', $productId)
                    ->where('documentable_type', Product::class)
                    ->where('document_type', 'main')
                    ->first();

            // If there's a current main image, move it to gallery
            if ($currentMain) {
                $currentMain->update(['document_type' => 'gallery']);
            }

            // Update the selected document to be main
            $document->update([
                'document_type' => 'main',
                'documentable_id' => $productId,
                'documentable_type' => Product::class,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Image set as main successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to set image as main.',
            ], 500);
        }
    }

    public function attachExistingImages(Request $request, Product $product)
    {
        try {
            $request->validate([
                'main_document_id' => 'nullable|integer|exists:documents,id',
                'gallery_document_ids' => 'nullable|array',
                'gallery_document_ids.*' => 'integer|exists:documents,id',
            ]);

            // Handle main image
            if ($request->has('main_document_id')) {
                // Demote current main to gallery
                $product->documents()->where('document_type', 'main')->update(['document_type' => 'gallery']);
                
                // Attach and set as main
                Document::where('id', $request->main_document_id)->update([
                    'documentable_id' => $product->id,
                    'documentable_type' => Product::class,
                    'document_type' => 'main',
                ]);
            }

            // Handle gallery images
            if ($request->has('gallery_document_ids')) {
                Document::whereIn('id', $request->gallery_document_ids)->update([
                    'documentable_id' => $product->id,
                    'documentable_type' => Product::class,
                    'document_type' => 'gallery',
                ]);
            }

            // Get updated documents
            $documents = $product->documents()->get();

            return response()->json([
                'success' => true,
                'message' => 'Images attached successfully.',
                'documents' => $documents,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to attach images.',
            ], 500);
        }
    }

    public function getUnattachedImages()
    {
        $unattachedDocs = Document::whereNull('documentable_id')->get();
        
        return response()->json([
            'success' => true,
            'documents' => $unattachedDocs,
        ]);
    }
}
