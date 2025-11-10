<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'title',
        'sku',
        'barcode',
        'price',
        'compare_at_price',
        'cost',
        'stock_quantity',
        'track_quantity',
        'taxable',
        'options',
        'is_active',
    ];

    protected $casts = [
        'track_quantity' => 'boolean',
        'taxable' => 'boolean',
        'is_active' => 'boolean',
        'options' => 'array',
    ];

    public static function generateUniqueSkuFromParent(Product $product, string $title, $ignoreId = null): array
    {
        $base = Str::upper(Str::slug(substr($product->title['en'] ?? 'Untitled', 0, 20), '-'));
        $variantPart = Str::upper(Str::slug(substr($title, 0, 20), '-'));
        $timestamp = now()->format('YmdHis'); // adds unique date/time stamp

        $sku = "PROD-{$base}-{$variantPart}-{$timestamp}";
        $counter = 1;

        while (self::where('sku', $sku)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $sku = "PROD-{$base}-{$variantPart}-{$timestamp}-" . str_pad($counter++, 2, '0', STR_PAD_LEFT);
        }

        // slug mirrors SKU
        $slug = Str::slug("{$base}-{$variantPart}-{$timestamp}");

        return ['sku' => $sku, 'slug' => $slug];
    }

    /** Scope */
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeInStock($q)
    {
        return $q->where('stock_status', 'in_stock');
    }

    /** Relations */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function stock(): HasMany
    {
        return $this->hasMany(InventoryStock::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
