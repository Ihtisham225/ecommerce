<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number', 'customer_id', 'status', 'currency',
        'subtotal', 'discount_total', 'tax_total',
        'shipping_total', 'grand_total',
        'payment_status', 'shipping_status',
        'notes'
    ];

    protected $casts = [
        'subtotal' => 'decimal:3',
        'discount_total' => 'decimal:3',
        'tax_total' => 'decimal:3',
        'shipping_total' => 'decimal:3',
        'grand_total' => 'decimal:3',
    ];

    /** Relations */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(OrderPayment::class);
    }

    public function addresses()
    {
        return $this->hasMany(OrderAddress::class);
    }

    public function history()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    /** Scopes */
    public function scopePaid($q)
    {
        return $q->where('payment_status', 'paid');
    }
}
