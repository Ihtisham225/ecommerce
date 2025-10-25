<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    /**
     * Display a listing of documents.
     */
    public function index()
    {
        $query = Document::query();

        // Apply document type filter
        if (request()->has('document_type') && request('document_type') != '') {
            $query->where('document_type', request('document_type'));
        }

        // Apply file type filter (MIME type)
        if (request()->has('file_type') && request('file_type') != '') {
            $query->where('mime_type', 'like', '%' . request('file_type') . '%');
        }

        // Apply search by name filter
        if (request()->has('search') && request('search') != '') {
            $searchTerm = request('search');
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        $documents = $query->latest()->paginate(10);

        foreach ($documents as $document) {
            $fileUrl = config('app.url') . Storage::url($document->file_path);

            // Build QR code using the Builder constructor
            $builder = new Builder(
                writer: new PngWriter(),
                data: $fileUrl,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::Low,
                size: 150,
                margin: 10,
                roundBlockSizeMode: RoundBlockSizeMode::Margin
            );

            $result = $builder->build();

            // Convert PNG content to base64 for Blade
            $document->qrCode = base64_encode($result->getString());
        }

        return view('admin.documents.index', compact('documents'));
    }

    /**
     * Show the documents preview.
     */
    public function show(Document $document)
    {
        $filePath = $document->file_path;
        $disk = $document->disk ?? 'public';

        if (!Storage::disk($disk)->exists($filePath)) {
            abort(404, 'File not found.');
        }

        $mimeType = Storage::disk($disk)->mimeType($filePath);
        $fileName = $document->name;

        // Images & PDFs → preview inline
        if (str_starts_with($mimeType, 'image/') || $mimeType === 'application/pdf') {
            return response()->file(Storage::disk($disk)->path($filePath), [
                'Content-Type'        => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            ]);
        }

        // Word, Excel, PowerPoint → force download (browser won’t preview reliably)
        return response()->download(Storage::disk($disk)->path($filePath), $fileName, [
            'Content-Type' => $mimeType,
        ]);
    }

    /**
     * Show the form for creating a new document.
     */
    public function create()
    {
        return view('admin.documents.create');
    }

    /**
     * Store newly uploaded documents in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'documents'     => 'required|array',
            'documents.*'   => 'file|mimes:pdf,jpg,jpeg,png,webp,doc,docx,xls,xlsx,ppt,pptx|max:102400',
            'document_type' => 'required',
        ]);

        foreach ($request->file('documents') as $file) {
            $path = $file->store('documents', 'public');
            
            $document = new Document();
            $document->name = $file->getClientOriginalName();
            $document->file_path = $path;
            $document->file_type = $this->getFileType($file);
            $document->document_type = $request->input('document_type');
            $document->mime_type = $file->getMimeType();
            $document->size = $file->getSize();
            $document->save();
        }

        return redirect()
            ->route('admin.documents.index')
            ->with('success', 'Documents uploaded successfully.');
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit(Document $document)
    {
        return view('admin.documents.edit', compact('document'));
    }

    /**
     * Update the specified document.
     */
    public function update(Request $request, Document $document)
    {
        $request->validate([
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png,webp,doc,docx,xls,xlsx,ppt,pptx|max:102400',
            'document_type' => 'required',
        ]);

        if ($request->hasFile('documents')) {
            // Delete old file
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $file = $request->file('documents')[0]; // allow updating with a single file
            $path = $file->store('documents', 'public');

            $document->name = $file->getClientOriginalName();
            $document->file_path = $path;
            $document->file_type = $this->getFileType($file);
            $document->mime_type = $file->getMimeType();
            $document->size = $file->getSize();
        }

        $document->document_type = $request->input('document_type');
        $document->save();

        return redirect()
            ->route('admin.documents.index')
            ->with('success', 'Document updated successfully.');
    }

    /**
     * Remove the specified document.
     */
    public function destroy(Document $document)
    {
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }
        
        $document->delete();
        
        return redirect()
            ->route('admin.documents.index')
            ->with('success', 'Document deleted successfully.');
    }

    /**
     * Detect file type from mime.
     */
    private function getFileType($file)
    {
        $mime = $file->getMimeType();
        
        if (Str::contains($mime, 'image')) {
            return 'image';
        } elseif (Str::contains($mime, 'pdf')) {
            return 'pdf';
        } elseif (Str::contains($mime, 'word')) {
            return 'word';
        } elseif (Str::contains($mime, 'excel') || Str::contains($mime, 'sheet')) {
            return 'excel';
        } elseif (Str::contains($mime, 'powerpoint') || Str::contains($mime, 'presentation')) {
            return 'powerpoint';
        } else {
            return 'other';
        }
    }
}
