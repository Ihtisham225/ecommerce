<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;

class CourseSchedule extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'venue',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'days',
        'cost',
        'session',
        'language',
        'nature',
        'type',
        'country_id',
        'instructor_id',
        'is_active',
    ];

    protected $casts = [
        'title'       => 'array',
        'venue'       => 'array',
        'start_date'  => 'date:Y-m-d',
        'end_date'    => 'date:Y-m-d',
        'start_time'  => 'datetime:H:i:s',
        'end_time'    => 'datetime:H:i:s',
        'cost'        => 'decimal:3',
        'is_active'   => 'boolean',
    ];

    // Relationships
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(CourseRegistration::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(CourseEvaluation::class);
    }

    // Accessors for multilingual fields
    public function getTitleAttribute($value)
    {
        $title = json_decode($value, true);
        return $title[App::currentLocale()] ?? $title['en'] ?? '';
    }

    public function getFormattedDateAttribute(): string
    {
        if (!$this->start_date) return '';
        $end = $this->end_date ? $this->end_date->format('M d, Y') : '';
        return $this->start_date->format('M d, Y') . ($end ? ' - ' . $end : '');
    }

    public function getFormattedTimeAttribute(): string
    {
        if (!$this->start_time) {
            return '';
        }

        return $this->start_time->format('g:i A') . ' - ' . $this->end_time->format('g:i A');
    }

    public function getVenueAttribute($value)
    {
        if (!$value) return null;
        $venue = json_decode($value, true);
        return $venue[App::currentLocale()] ?? $venue['en'] ?? '';
    }

    // Raw JSON getters
    public function getTitles()
    {
        return json_decode($this->attributes['title'], true);
    }

    public function getVenues()
    {
        return json_decode($this->attributes['venue'], true);
    }

    // Helper
    public function getFormattedCostAttribute(): string
    {
        return $this->cost ? number_format($this->cost, 3) . ' KWD' : __('Free');
    }

    protected static function booted()
    {
        static::deleting(function ($schedule) {
            // List of document relationships on the model
            $relations = ['flyer', 'outline'];

            foreach ($relations as $relation) {
                $document = $schedule->$relation;

                if ($document && !empty($document->file_path)) {
                    $path = public_path('storage/' . $document->file_path);

                    // Delete the physical file if it exists
                    if (file_exists($path)) {
                        @unlink($path); // @ prevents warning if already deleted
                    }

                    // Delete the database record
                    $document->delete();
                }
            }
        });
    }

    public function outline()
    {
        return $this->morphOne(Document::class, 'documentable')
            ->where('document_type', 'outline');
    }
    
    

    public function flyer()
    {
        return $this->morphOne(Document::class, 'documentable')
            ->where('document_type', 'flyer');
    }

    // public function invoice()
    // {
    //     return $this->morphOne(Document::class, 'documentable')
    //         ->where('document_type', 'invoice');
    // }

    // public function coverLetter()
    // {
    //     return $this->morphOne(Document::class, 'documentable')
    //         ->where('document_type', 'cover_letter');
    // }

    // public function completeDocument()
    // {
    //     return $this->morphOne(Document::class, 'documentable')
    //         ->where('document_type', 'complete_document');
    // }

    // public function attendanceSheet()
    // {
    //     return $this->morphOne(Document::class, 'documentable')
    //         ->where('document_type', 'attendance_sheet');
    // }

    // public function certificates()
    // {
    //     return $this->morphOne(Document::class, 'documentable')
    //         ->where('document_type', 'certificates');
    // }

    // public function courseEvaluation()
    // {
    //     return $this->morphOne(Document::class, 'documentable')
    //         ->where('document_type', 'course_evaluation');
    // }
}

