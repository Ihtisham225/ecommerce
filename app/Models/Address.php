<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'addressable_id',
        'addressable_type',
        'type',
        'address_line_1',
        'address_line_2',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Polymorphic relation: Address can belong to Customer, Order, etc.
     */
    public function addressable()
    {
        return $this->morphTo();
    }

    /**
     * Scopes
     */
    public function scopeShipping($query)
    {
        return $query->where('type', 'shipping');
    }

    public function scopeBilling($query)
    {
        return $query->where('type', 'billing');
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Helpers
     */
    public function getFullAddressAttribute()
    {
        $address = $this->address_line_1;
        if ($this->address_line_2) {
            $address .= ", {$this->address_line_2}";
        }
        return $address;
    }

    public function isShipping()
    {
        return $this->type === 'shipping';
    }

    public function isBilling()
    {
        return $this->type === 'billing';
    }
}