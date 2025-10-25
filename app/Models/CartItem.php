<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id', 'product_id', 'product_variant_id',
        'sku', 'title', 'price', 'qty', 'total'
    ];

    protected $casts = [
        'price' => 'decimal:3',
        'total' => 'decimal:3',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}

