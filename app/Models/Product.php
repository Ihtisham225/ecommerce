<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'description', 'sku', 'type',
        'price', 'compare_at_price', 'stock_quantity',
        'track_stock', 'stock_status', 'is_active', 'is_featured',
        'published_at', 'slug', 'meta_title', 'meta_description',
        'category_id', 'brand_id', 'external_id', 'platform', 'handle',
        'raw_data', 'created_by'
    ];

    protected $casts = [
        'track_stock' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'raw_data' => 'array',
        'published_at' => 'datetime',
    ];

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


    /** Relations */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function meta()
    {
        return $this->hasMany(ProductMeta::class);
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'product_collection');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }

    public function relatedProducts()
    {
        return $this->hasMany(ProductRelation::class);
    }

    public function stock()
    {
        return $this->hasMany(InventoryStock::class);
    }

    /** Morph - Media Files */
    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /** Scopes */
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


