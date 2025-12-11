<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderTransaction extends Model
{
    protected $fillable = [
        'type',
        'status',
        'amount',
        'payment_method',
        'gateway',
        'transaction_id',
        'meta',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'meta' => 'array',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_transaction_order')
                    ->withTimestamps();
    }
}
