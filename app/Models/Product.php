<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'description', 'sku', 'type', 'price', 'compare_at_price', 'cost',
        'stock_quantity', 'track_stock', 'stock_status',
        'is_active', 'is_featured', 'is_published',
        'published_at', 'slug', 'meta_title', 'meta_description',
        'brand_id', 'external_id', 'platform', 'handle', 'raw_data',
        'created_by', 'has_options', 'charge_tax', 'requires_shipping',
    ];

    protected $casts = [
        'track_stock' => 'boolean',
        'is_active' => 'boolean',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'has_options' => 'boolean',
        'charge_tax' => 'boolean',
        'requires_shipping' => 'boolean',
        'raw_data' => 'array',
        'published_at' => 'datetime',
        'title' => 'array',
        'description' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($product) {
            // For new products, slug starts null
            if (!isset($product->slug)) {
                $product->slug = null;
            }

            // SKU starts null
            if (!isset($product->sku)) {
                $product->sku = null;
            }
        });
    }

    /**
     * Generate unique SKU (and slug) from title
     */
    public static function generateUniqueSkuAndSlug(string $title, $ignoreId = null): array
    {
        $titlePart = Str::upper(Str::slug(substr($title, 0, 20), '-'));
        $datetime = now()->format('YmdHis'); // e.g. 20251108123045
        $sku = 'PROD-' . $titlePart . '-' . $datetime; 
        $slug = Str::slug($title);

        $counter = 1;

        // Ensure SKU uniqueness
        while (self::where('sku', $sku)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $sku = 'PROD-' . $titlePart . '-' . $datetime . '-' . str_pad($counter++, 2, '0', STR_PAD_LEFT);
        }

        // Ensure slug uniqueness
        $originalSlug = $slug;
        while (self::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $originalSlug . '-' . Str::random(4);
        }

        return ['sku' => $sku, 'slug' => $slug];
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

    // ---------- Relations ----------

    // ---------- Translations (morph) ----------
    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    // ---------- Documents (images) ----------
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function mainImage()
    {
        return $this->documents()->where('document_type', 'main')->orderByDesc('id');
    }

    public function galleryImages()
    {
        return $this->documents()->where('document_type', 'gallery')->orderBy('id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class, 'product_collection');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(ProductOption::class);
    }

    public function shipping(): HasOne
    {
        return $this->hasOne(ProductShipping::class);
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

    public function scopeByBrand($query, $brandId)
    {
        return $query->where('brand_id', $brandId);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->whereHas('categories', fn($q) => $q->where('categories.id', $categoryId));
    }

    public function scopeByCollection($query, $collectionId)
    {
        return $query->whereHas('collections', fn($q) => $q->where('collections.id', $collectionId));
    }

    public function scopeWithTags($query, array $tags)
    {
        return $query->withAnyTags($tags);
    }

    public function deleteCompletely()
    {
        DB::beginTransaction();

        try {
            // Delete product documents
            foreach ($this->documents as $document) {
                if (!empty($document->file_path) && Storage::disk('public')->exists($document->file_path)) {
                    Storage::disk('public')->delete($document->file_path);
                }
                $document->delete();
            }

            // Delete variants + images + stock
            foreach ($this->variants as $variant) {
                if ($variant->image && !empty($variant->image->file_path) &&
                    Storage::disk('public')->exists($variant->image->file_path)) {
                    Storage::disk('public')->delete($variant->image->file_path);
                    $variant->image->delete();
                }

                $variant->stock()?->delete();
                $variant->delete();
            }

            // Delete other relations
            $this->options()->delete();
            $this->shipping()?->delete();
            $this->translations()->delete();

            $this->tags()->detach();
            $this->categories()->detach();
            $this->collections()->detach();

            // Delete product
            $this->forceDelete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
