<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = [
        'order_id', 'order_payment_id', 'amount', 'reason', 'status', 'details'
    ];

    protected $casts = [
        'amount' => 'decimal:3',
        'details' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function payment()
    {
        return $this->belongsTo(OrderPayment::class, 'order_payment_id');
    }
}

