<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'product_variant_id',
        'sku', 'title', 'price', 'qty', 'total'
    ];

    protected $casts = [
        'price' => 'decimal:3',
        'total' => 'decimal:3',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
