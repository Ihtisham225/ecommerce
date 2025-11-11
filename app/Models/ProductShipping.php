<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductShipping extends Model
{
    protected $table = 'product_shipping';

    protected $fillable = [
        'product_id',
        'variant_id',
        'requires_shipping',
        'weight',
        'width',
        'height',
        'length',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
