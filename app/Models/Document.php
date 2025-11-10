<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    protected $fillable = [
        'name',
        'file_path',
        'file_type',
        'document_type',
        'documentable_id',
        'documentable_type',
        'mime_type',
        'size',
    ];

    protected $appends = ['url'];

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getFileSizeAttribute()
    {
        $bytes = $this->size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    public function getFileIconAttribute()
    {
        $extension = pathinfo($this->file_path, PATHINFO_EXTENSION);
        
        switch ($extension) {
            case 'pdf':
                return 'file-pdf';
            case 'doc':
            case 'docx':
                return 'file-word';
            case 'xls':
            case 'xlsx':
                return 'file-excel';
            case 'ppt':
            case 'pptx':
                return 'file-powerpoint';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'webp':
                return 'file-image';
            default:
                return 'file';
        }
    }

    // Accessors
    public function getUrlAttribute()
    {
        return Storage::disk('public')->url($this->file_path);
    }

    public function scopeType($q, $type)
    {
        return $q->where('document_type', $type);
    }
}