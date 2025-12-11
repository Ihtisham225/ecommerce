<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $fillable = [
        'method', 'amount', 'transaction_id', 'notes', 'status'
    ];

    protected $casts = [
        'amount' => 'decimal:3',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_payment_order')
                    ->withTimestamps();
    }
}

