<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'product_variant_id',
        'sku', 'title', 'price', 'quantity', 'subtotal', 'total', 'tax'
    ];

    protected $casts = [
        'price' => 'decimal:3',
        'total' => 'decimal:3',
        'subtotal' => 'decimal:3',
        'tax' => 'decimal:3',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
