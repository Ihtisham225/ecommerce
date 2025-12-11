<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number', 'customer_id', 'status', 'source', 'platform', 'external_id',
        'subtotal', 'discount_total', 'tax_total', 'shipping_total', 'grand_total',
        'payment_status', 'shipping_status', 'notes', 'admin_notes',
        'shipping_method', 'created_by', 'cancelled_at', 'completed_at', 'raw_data'
    ];

    protected $casts = [
        'subtotal' => 'decimal:3',
        'discount_total' => 'decimal:3',
        'tax_total' => 'decimal:3',
        'shipping_total' => 'decimal:3',
        'grand_total' => 'decimal:3',
        'cancelled_at' => 'datetime',
        'completed_at' => 'datetime',
        'raw_data' => 'array',
    ];

    protected static function booted()
    {
        static::saving(function ($order) {
            $order->subtotal = $order->items()->sum(DB::raw('price * quantity'));
            $order->discount_total = $order->adjustments()
                ->where('type', 'discount')
                ->sum('amount');
            $order->tax_total = $order->items()->sum('tax');
            $order->grand_total = $order->subtotal + $order->shipping_total + $order->tax_total - $order->discount_total;
        });
    }

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
        return $this->belongsToMany(OrderPayment::class, 'order_payment_order')
                    ->withTimestamps();
    }

    public function transactions()
    {
        return $this->belongsToMany(OrderTransaction::class, 'order_transaction_order')
                    ->withTimestamps();
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function history()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function adjustments()
    {
        return $this->hasMany(OrderAdjustment::class);
    }

    public function fulfillments()
    {
        return $this->hasMany(Fulfillment::class);
    }

    /** Scopes */
    public function scopeOnline($query)
    {
        return $query->where('source', 'online');
    }

    public function scopeInStore($query)
    {
        return $query->where('source', 'in_store');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /** Methods */
    public function getBillingAddressAttribute()
    {
        return $this->addresses()->where('type', 'billing')->first();
    }

    public function getShippingAddressAttribute()
    {
        return $this->addresses()->where('type', 'shipping')->first();
    }

    public function getTotalPaidAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function getIsFullyPaidAttribute()
    {
        return $this->total_paid >= $this->grand_total;
    }

    public function getBalanceDueAttribute()
    {
        return (float)$this->grand_total - (float)$this->total_paid;
    }

    /**
     * Calculate remaining balance based on payments
     */
    public function calculateBalance(Order $order)
    {
        // Calculate total paid from all payments
        $totalPaid = $order->payments()->sum('amount');
        $grandTotal = (float) $order->grand_total;
        $balance = $grandTotal - $totalPaid;
        
        // Determine payment status
        if ($totalPaid >= $grandTotal) {
            $paymentStatus = 'paid';
        } elseif ($totalPaid > 0) {
            $paymentStatus = 'partially_paid';
        } else {
            $paymentStatus = 'pending';
        }
        
        return response()->json([
            'success' => true,
            'balance' => $balance,
            'total_paid' => $totalPaid,
            'grand_total' => $grandTotal,
            'payment_status' => $paymentStatus,
            'is_fully_paid' => $balance <= 0,
            'is_partially_paid' => $totalPaid > 0 && $balance > 0,
            'is_unpaid' => $totalPaid == 0,
        ]);
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isFulfilled()
    {
        return $this->shipping_status === 'fulfilled';
    }
}