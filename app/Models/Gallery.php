<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'description', 'year', 'layout', 'featured', 'is_active'];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'is_active' => 'boolean',
    ];

    public function media(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    // 🔹 Accessors
    public function getTitle(string $lang = 'en')
    {
        return $this->title[$lang] ?? '';
    }

    public function getDescription(string $lang = 'en')
    {
        return $this->description[$lang] ?? '';
    }

    // Helper for filtering
    public function scopeFilter($query, $filters)
    {
        if (!empty($filters['year'])) {
            $query->where('year', $filters['year']);
        }
        if (!empty($filters['layout'])) {
            $query->where('layout', $filters['layout']);
        }
        if (!empty($filters['featured'])) {
            $query->where('featured', true);
        }
        return $query;
    }
}

