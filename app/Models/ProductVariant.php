<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
