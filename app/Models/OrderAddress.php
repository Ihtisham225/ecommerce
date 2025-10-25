<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
{
    protected $fillable = [
        'order_id', 'type', 'first_name', 'last_name', 'phone',
        'address1', 'address2', 'city', 'state', 'postal_code', 'country'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

