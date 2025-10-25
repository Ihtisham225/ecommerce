<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class CourseCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 
        'slug', 
        'description',
        'parent_id',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
    ];

    // Accessors for multilingual fields
    public function getNameAttribute($value)
    {
        $name = json_decode($value, true);
        return $name[App::currentLocale()] ?? $name['en'] ?? '';
    }

    public function getDescriptionAttribute($value)
    {
        if (!$value) return null;
        
        $description = json_decode($value, true);
        return $description[App::currentLocale()] ?? $description['en'] ?? '';
    }

    // Get raw multilingual array
    public function getNames()
    {
        // Safely handle cases where the attribute doesn't exist or is empty
        $value = $this->attributes['name'] ?? null;

        if (empty($value)) {
            return [];
        }

        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function getDescriptions()
    {
        $value = $this->attributes['description'] ?? null;

        if (empty($value)) {
            return [];
        }

        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    // 🔹 Parent Category
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    // 🔹 Child Categories
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    // Scopes
    public function scopeWithCourses($query)
    {
        return $query->with('courses');
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }
}