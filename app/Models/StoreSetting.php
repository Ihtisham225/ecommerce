<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    protected $fillable = [
        'user_id', 'store_name', 'store_email', 'store_phone',
        'currency_code', 'timezone', 'logo', 'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    // Default settings structure
    protected $attributes = [
        'settings' => '{
            "shipping_methods": [],
            "payment_methods": [],
            "bank_details": {},
            "store_address": {},
            "tax_settings": {},
            "notification_settings": {},
            "store_hours": {}
        }',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function getCurrencySymbolAttribute()
    {
        return $this->currency->symbol ?? 'KWD';
    }

    // Accessor for specific settings
    public function getShippingMethodsAttribute()
    {
        return $this->settings['shipping_methods'] ?? [];
    }

    public function getPaymentMethodsAttribute()
    {
        return $this->settings['payment_methods'] ?? [];
    }

    public function getBankDetailsAttribute()
    {
        return $this->settings['bank_details'] ?? [];
    }

    public function getStoreAddressAttribute()
    {
        return $this->settings['store_address'] ?? [];
    }
}