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
        'country',
        'city',
        'state',
        'zip',
        'address_line_1',
        'address_line_2',
        'phone',
        'company',
        'latitude',
        'longitude',
        'tax_number',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * Polymorphic relation: Address can belong to Customer or Order.
     */
    public function addressable()
    {
        return $this->morphTo();
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
