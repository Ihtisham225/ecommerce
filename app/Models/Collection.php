<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = ['title', 'description', 'slug', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_collection');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', 1);
    }

    public function translate($field, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();

        $translation = $this->translations()
            ->where('field', $field)
            ->where('locale', $locale)
            ->value('value');

        return $translation ?: $this->{$field}[$locale] ?? $this->{$field}['en'] ?? null;
    }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translatable');
    }
}

