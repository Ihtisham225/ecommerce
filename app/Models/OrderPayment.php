<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $fillable = [
        'order_id', 'method', 'amount', 'transaction_id', 'status'
    ];

    protected $casts = [
        'amount' => 'decimal:3',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

