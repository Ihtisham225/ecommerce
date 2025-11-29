<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
}

