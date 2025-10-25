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
        'country',
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

    public function defaultShipping()
    {
        return $this->addresses()->where('is_default_shipping', 1)->first();
    }

    public function defaultBilling()
    {
        return $this->addresses()->where('is_default_billing', 1)->first();
    }

    /** Scopes */
    public function scopeActive($q)
    {
        return $q->where('status', 1);
    }
}

