<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_category_id',
        'title',
        'slug',
        'description',
        'featured',
        'is_published',
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'featured' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function activeSchedules()
    {
        return $this->schedules()
            ->where('is_active', true)
            ->whereDate('end_date', '>=', now());
    }

    public function nextSchedule()
    {
        return $this->activeSchedules()
            ->orderBy('start_date')
            ->first();
    }

    public function courseCategory(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class);
    }

    // Accessors for multilingual fields
    public function getTitleAttribute($value)
    {
        $title = json_decode($value, true);
        return $title[App::currentLocale()] ?? $title['en'] ?? '';
    }

    public function getDescriptionAttribute($value)
    {
        if (!$value) return null;
        $description = json_decode($value, true);
        return $description[App::currentLocale()] ?? $description['en'] ?? '';
    }

    // Get raw multilingual data
    public function getTitles()
    {
        return json_decode($this->attributes['title'], true);
    }

    public function getDescriptions()
    {
        return $this->attributes['description'] ? json_decode($this->attributes['description'], true) : [];
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->whereNotNull('date')
                     ->whereDate('date', '>=', now());
    }


    public function getShortDescriptionAttribute(): string
    {
        return $this->excerpt ?: Str::limit(strip_tags($this->description), 120);
    }

    protected static function booted()
    {
        static::creating(function ($course) {
            if (empty($course->slug)) {
                $titles = json_decode($course->title, true);
                $course->slug = Str::slug($titles['en']);
            }
        });

        static::deleting(function ($course) {
            $image = $course->image;

            if ($image && !empty($image->file_path)) {
                $path = public_path('storage/' . $image->file_path);
                if (file_exists($path)) {
                    unlink($path);
                }
                $image->delete();
            }
        });
    }

    public function image()
    {
        return $this->morphOne(Document::class, 'documentable')
            ->where('document_type', 'image');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(CourseSchedule::class);
    }

}