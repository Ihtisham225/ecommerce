<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Creating event - before the customer is created
        static::creating(function ($customer) {
            if (!$customer->is_guest && !$customer->user_id) {
                $customer->user_id = self::createUserForCustomer($customer);
            }
        });

        // Updating event - before the customer is updated
        static::updating(function ($customer) {
            // If converting from guest to registered customer
            if (!$customer->is_guest && !$customer->user_id) {
                $customer->user_id = self::createUserForCustomer($customer);
            }
            
            // If converting from registered to guest, remove user_id
            if ($customer->is_guest && $customer->user_id) {
                $customer->user_id = null;
            }
        });

        // After the customer is saved, sync customer data with user
        static::saved(function ($customer) {
            if (!$customer->is_guest && $customer->user_id) {
                self::syncUserWithCustomer($customer);
            }
        });
    }

    /**
     * Create a user account for customer
     */
    private static function createUserForCustomer($customer): int
    {
        // Check if user already exists with this email
        $user = User::where('email', $customer->email)->first();
        $password = Str::random(12);
        if (!$user) {
            $user = User::create([
                'name' => trim($customer->first_name . ' ' . $customer->last_name),
                'email' => $customer->email,
                'password' => Hash::make($password),
                'user_password' => $password, // Store plain password temporarily if needed
                
            ]);
            
            // Assign customer role to the user
            $user->assignRole('customer');
        } else {
            // Ensure existing user has customer role
            if (!$user->hasRole('customer')) {
                $user->assignRole('customer');
            }
        }
        
        return $user->id;
    }

    /**
     * Sync user data with customer data
     */
    private static function syncUserWithCustomer($customer): void
    {
        if ($customer->user && $customer->user_id) {
            $user = $customer->user;
            
            // Update user name if different
            $fullName = trim($customer->first_name . ' ' . $customer->last_name);
            if ($user->name !== $fullName) {
                $user->name = $fullName;
            }
            
            // Update email if different
            if ($user->email !== $customer->email) {
                $user->email = $customer->email;
            }
            
            // Ensure customer role is assigned
            if (!$user->hasRole('customer')) {
                $user->assignRole('customer');
            }
            
            $user->save();
        }
    }

    /**
     * Convert guest customer to registered user
     */
    public function convertToRegistered(string $password = null): User
    {
        if (!$this->is_guest) {
            throw new \Exception('Customer is already registered');
        }

        $this->is_guest = false;
        $user = $this->createUserAccount($password);
        $this->user_id = $user->id;
        $this->save();

        return $user;
    }

    /**
     * Create user account with optional password
     */
    public function createUserAccount(string $password = null): User
    {
        if ($this->is_guest) {
            throw new \Exception('Cannot create user account for guest customer');
        }

        if ($this->user_id) {
            return $this->user;
        }

        $user = User::where('email', $this->email)->first();
        
        if (!$user) {
            $userData = [
                'name' => $this->full_name,
                'email' => $this->email,
            ];

            if ($password) {
                $userData['password'] = Hash::make($password);
            } else {
                $userData['password'] = Hash::make(Str::random(12));
            }

            $user = User::create($userData);
            $user->assignRole('customer');
            
            $this->update(['user_id' => $user->id]);
        } else {
            // Ensure the user has customer role
            if (!$user->hasRole('customer')) {
                $user->assignRole('customer');
            }
        }
        
        return $user;
    }

    /**
     * Send welcome email to newly created user
     */
    public function sendWelcomeEmail(string $password = null): void
    {
        if (!$this->user_id || $this->is_guest) {
            return;
        }

        // Dispatch a job to send welcome email
        
    }

    /**
     * Check if customer has user account
     */
    public function hasUserAccount(): bool
    {
        return !$this->is_guest && !is_null($this->user_id);
    }

    /**
     * Get the customer's user with role check
     */
    public function getUserWithRoleAttribute()
    {
        if (!$this->user_id) {
            return null;
        }

        return $this->user()->with('roles')->first();
    }

    /**
     * Scope: Get only registered customers (non-guests with user accounts)
     */
    public function scopeRegistered($query)
    {
        return $query->where('is_guest', false)
                     ->whereNotNull('user_id');
    }

    /**
     * Scope: Get only guest customers
     */
    public function scopeGuest($query)
    {
        return $query->where('is_guest', true)
                     ->orWhereNull('user_id');
    }

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
        $firstOrder = $this->orders()->oldest('created_at')->first();
        $lastOrder  = $this->orders()->latest('created_at')->first();

        if (!$firstOrder || !$lastOrder) {
            return null;
        }

        $totalOrders = $this->total_orders_count ?? $this->orders()->count();

        // Ensure minimum of 1 month to avoid division by zero
        $monthsBetween = max(
            1,
            $firstOrder->created_at->diffInMonths($lastOrder->created_at)
        );

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

