<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', // stored as JSON or string fallback
        'description', // stored as JSON or string fallback
        'sku', 'type',
        'price', 'compare_at_price', 'stock_quantity',
        'track_stock', 'stock_status', 'is_active', 'is_featured',
        'published_at', 'slug', 'meta_title', 'meta_description',
        'brand_id', 'external_id', 'platform', 'handle',
        'raw_data', 'created_by'
    ];

    protected $casts = [
        'track_stock' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'raw_data' => 'array',
        'published_at' => 'datetime',
        // if you store title/description as JSON columns, cast:
        'title' => 'array',
        'description' => 'array',
    ];

    // ---------- Translations (morph) ----------
    public function translations()
    {
        return $this->morphMany(\App\Models\Translation::class, 'translatable');
    }

    /**
     * Get translated string for a field with fallback:
     * - checks translations table for locale
     * - checks JSON column (title[locale])
     * - falls back to 'en' or scalar value
     */
    public function translate(string $field, ?string $locale = null)
    {
        $locale = $locale ?? app()->getLocale();

        // 1) check translations morph table
        $val = $this->translations()
            ->where('field', $field)
            ->where('locale', $locale)
            ->value('value');

        if ($val !== null) return $val;

        // 2) check JSON column if present
        $fieldVal = $this->{$field} ?? null;

        if (is_array($fieldVal)) {
            if (isset($fieldVal[$locale])) return $fieldVal[$locale];
            if (isset($fieldVal['en'])) return $fieldVal['en'];
        }

        // 3) fallback to scalar value
        if (is_string($fieldVal)) return $fieldVal;

        return null;
    }

    // Helper to set translations (array like ['title' => ['en'=>'..','ar'=>'..'], 'description' => [...]])
    public function setTranslationsFromRequest(array $data = [])
    {
        foreach (['title', 'description'] as $field) {
            if (!isset($data[$field]) || !is_array($data[$field])) continue;

            foreach ($data[$field] as $locale => $value) {
                // upsert into translations table
                $this->translations()->updateOrCreate(
                    ['field' => $field, 'locale' => $locale],
                    ['value' => $value]
                );
            }

            // Optionally, also store JSON column so you have quick access (optional)
            // Merge with existing array to avoid nulling others
            $current = $this->{$field} ?? [];
            if (!is_array($current)) $current = [];
            $this->update([$field => array_merge($current, $data[$field])]);
        }
    }

    // ---------- Documents (images) ----------
    public function documents()
    {
        return $this->morphMany(\App\Models\Document::class, 'documentable');
    }

    public function mainImage()
    {
        return $this->documents()->where('document_type', 'main')->orderByDesc('id');
    }

    public function galleryImages()
    {
        return $this->documents()->where('document_type', 'gallery')->orderBy('id');
    }

    // ---------- Relations ----------
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // ... other relations left as-is

    // ---------- Scopes ----------
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_status', 'in_stock');
    }

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }
}
