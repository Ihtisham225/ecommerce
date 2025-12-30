<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'variant_id',
        'quantity',
        'price',
        'options'
    ];

    protected $casts = [
        'price' => 'decimal:3',
        'quantity' => 'integer',
        'options' => 'array'
    ];

    /**
     * Get the cart
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the variant
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    /**
     * Calculate item total
     */
    public function getTotalAttribute()
    {
        return $this->price * $this->quantity;
    }
}