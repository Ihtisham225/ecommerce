<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    protected $fillable = [
        'shipping_class_id', 'country',
        'min_weight', 'max_weight',
        'rate'
    ];

    protected $casts = [
        'min_weight' => 'decimal:2',
        'max_weight' => 'decimal:2',
        'rate' => 'decimal:3'
    ];

    public function shippingClass()
    {
        return $this->belongsTo(ShippingClass::class);
    }
}
