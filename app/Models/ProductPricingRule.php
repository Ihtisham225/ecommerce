<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPricingRule extends Model
{
    protected $fillable = [
        'product_id', 'title', 'type',
        'value', 'start_at', 'end_at',
        'is_active'
    ];

    protected $casts = [
        'value' => 'decimal:3',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

