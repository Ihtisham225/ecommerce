<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'email',
        'first_name',
        'last_name',
        'phone',
        'is_guest',
    ];

    protected $casts = [
        'is_guest' => 'boolean',
    ];

    /**
     * Relationship: A customer may belong to a user account OR be a guest.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: A customer can have multiple addresses.
     */
    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }
    
    /**
     * Relationship: A customer can have multiple orders.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function activeOrders(): HasMany
    {
        return $this->orders()
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->whereNotIn('payment_status', ['refunded']);
    }

    public function completedOrders(): HasMany
    {
        return $this->orders()
            ->where('status', 'completed')
            ->where('payment_status', 'paid');
    }

    /**
     * Get orders by status
     */
    public function ordersByStatus(string $status): HasMany
    {
        return $this->orders()->where('status', $status);
    }

    /**
     * Helper: Full name accessor
     */
    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function wishlist()
    {
        return $this->hasOne(Wishlist::class);
    }

    public function shippingAddresses()
    {
        return $this->morphMany(Address::class, 'addressable')->where('type', 'shipping');
    }

    public function billingAddresses()
    {
        return $this->morphMany(Address::class, 'addressable')->where('type', 'billing');
    }

    public function defaultShippingAddress()
    {
        return $this->shippingAddresses()->where('is_default', true)->first();
    }

    public function defaultBillingAddress()
    {
        return $this->billingAddresses()->where('is_default', true)->first();
    }

    // Helper methods
    public function getDefaultShippingAttribute()
    {
        return $this->defaultShippingAddress();
    }

    public function getDefaultBillingAttribute()
    {
        return $this->defaultBillingAddress();
    }

    public function getShippingAddressesAttribute()
    {
        return $this->shippingAddresses()->get();
    }

    public function getBillingAddressesAttribute()
    {
        return $this->billingAddresses()->get();
    }

    /** Scopes */
    public function scopeActive($q)
    {
        return $q->where('status', 1);
    }

    /**
     * Get total amount spent by customer
     */
    public function getTotalSpentAttribute(): float
    {
        // Using withSum to optimize query
        return (float) $this->orders()->sum('grand_total');
        
        // OR if you want to exclude cancelled orders:
        // return (float) $this->orders()
        //     ->whereNotIn('status', ['cancelled', 'refunded'])
        //     ->sum('grand_total');
    }

    /**
     * Get total number of orders for customer
     */
    public function getTotalOrdersCountAttribute(): int
    {
        return $this->orders()->count();
        
        // OR if you want to exclude cancelled orders:
        // return $this->orders()
        //     ->whereNotIn('status', ['cancelled', 'refunded'])
        //     ->count();
    }

    /**
     * Get successful orders (completed/paid)
     */
    public function getSuccessfulOrdersAttribute(): int
    {
        return $this->orders()
            ->where('status', 'completed')
            ->where('payment_status', 'paid')
            ->count();
    }

    /**
     * Get average order value
     */
    public function getAverageOrderValueAttribute(): ?float
    {
        $totalSpent = $this->total_spent;
        $totalOrders = $this->total_orders_count;
        
        if ($totalOrders === 0) {
            return null;
        }
        
        return round($totalSpent / $totalOrders, 2);
    }

    /**
     * Get first order date
     */
    public function getFirstOrderDateAttribute(): ?string
    {
        $firstOrder = $this->orders()->orderBy('created_at')->first();
        return $firstOrder?->created_at?->format('M j, Y');
    }

    /**
     * Get last order date
     */
    public function getLastOrderDateAttribute(): ?string
    {
        $lastOrder = $this->orders()->orderBy('created_at', 'desc')->first();
        return $lastOrder?->created_at?->format('M j, Y');
    }

    /**
     * Check if customer is VIP (spent over certain amount)
     */
    public function getIsVipAttribute(): bool
    {
        return $this->total_spent > 1000; // Adjust threshold as needed
    }

    /**
     * Calculate customer lifetime value
     */
    public function getLifetimeValueAttribute(): float
    {
        // Total spent minus refunds
        $totalSpent = $this->total_spent;
        $totalRefunds = $this->orders()->sum('refund_amount') ?? 0;
        
        return max(0, $totalSpent - $totalRefunds);
    }

    /**
     * Calculate order frequency (orders per month)
     */
    public function getOrderFrequencyAttribute(): ?float
    {
        $firstOrder = $this->orders()->orderBy('created_at')->first();
        $lastOrder = $this->orders()->orderBy('created_at', 'desc')->first();
        
        if (!$firstOrder || !$lastOrder) {
            return null;
        }
        
        $totalOrders = $this->total_orders_count;
        $monthsBetween = $firstOrder->created_at->diffInMonths($lastOrder->created_at);
        
        if ($monthsBetween === 0) {
            return $totalOrders;
        }
        
        return round($totalOrders / $monthsBetween, 2);
    }

    /**
     * Scope: Customers who have placed orders
     */
    public function scopeHasOrders($query)
    {
        return $query->whereHas('orders');
    }

    /**
     * Scope: VIP customers (spent over threshold)
     */
    public function scopeVip($query, float $threshold = 1000)
    {
        return $query->whereHas('orders', function ($q) use ($threshold) {
            $q->selectRaw('customer_id, SUM(grand_total) as total_spent')
              ->groupBy('customer_id')
              ->having('total_spent', '>', $threshold);
        });
    }

    /**
     * Scope: Inactive customers (no orders in last X days)
     */
    public function scopeInactive($query, int $days = 365)
    {
        return $query->whereDoesntHave('orders', function ($q) use ($days) {
            $q->where('created_at', '>=', now()->subDays($days));
        });
    }
}

