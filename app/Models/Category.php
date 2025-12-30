<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'slug',
        'parent_id',
        'position',
        'is_active',
        'name', // English fallback
        'description', // English fallback
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'position' => 'integer',
    ];

    protected $appends = [
        'localized_name',
        'localized_description',
    ];

    /** Boot */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $englishName = $category->translate('name', 'en') ?? $category->name;
                $category->slug = Str::slug($englishName);
            }
            
            if (is_null($category->position)) {
                $maxPosition = static::where('parent_id', $category->parent_id)->max('position');
                $category->position = $maxPosition ? $maxPosition + 1 : 1;
            }
        });

        static::saving(function ($category) {
            if (!empty($category->slug)) {
                $category->slug = Str::slug($category->slug);
            }
        });
    }

    /** Translation Methods */
    public function translate(string $field, ?string $locale = null): ?string
    {
        $locale = $locale ?? app()->getLocale();

        // Check translations table
        $translation = $this->translations()
            ->where('field', $field)
            ->where('locale', $locale)
            ->first();

        if ($translation) {
            return $translation->value;
        }

        // Fallback to English
        if ($locale !== 'en') {
            $englishTranslation = $this->translations()
                ->where('field', $field)
                ->where('locale', 'en')
                ->first();

            if ($englishTranslation) {
                return $englishTranslation->value;
            }
        }

        // Fallback to database column (English)
        return $this->{$field} ?? null;
    }

    public function getLocalizedNameAttribute(): ?string
    {
        return $this->translate('name');
    }

    public function getLocalizedDescriptionAttribute(): ?string
    {
        return $this->translate('description');
    }

    /** Relationships */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')
                    ->orderBy('position')
                    ->orderBy('id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_category')
                    ->withTimestamps();
    }

    public function publishedProducts(): BelongsToMany
    {
        return $this->products()->published();
    }

    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    /** Scopes */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position')->orderBy('id');
    }
}