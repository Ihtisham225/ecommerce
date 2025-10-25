<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryStock extends Model
{
    protected $fillable = [
        'inventory_location_id',
        'product_variant_id',
        'product_id',
        'quantity'
    ];

    public function location()
    {
        return $this->belongsTo(InventoryLocation::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
