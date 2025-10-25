<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'sku', 'barcode', 'title',
        'price', 'compare_at_price', 'cost_price',
        'stock_quantity', 'track_stock', 'stock_status',
        'options', 'is_active', 'external_id', 'raw_data'
    ];

    protected $casts = [
        'track_stock' => 'boolean',
        'is_active' => 'boolean',
        'options' => 'array',
        'raw_data' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
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

    public function stock()
    {
        return $this->hasMany(InventoryStock::class);
    }
}
