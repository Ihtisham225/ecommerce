<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleMerchantSetting extends Model
{
    protected $fillable = [
        'is_enabled',
        'merchant_id',
        'client_id',
        'client_secret',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'auto_sync',
        'last_sync_at',
        'total_products_synced',
        'last_error'
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'auto_sync' => 'boolean',
        'token_expires_at' => 'datetime',
        'last_sync_at' => 'datetime'
    ];

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    public function getIsConnectedAttribute()
    {
        return $this->is_enabled && 
               $this->merchant_id && 
               $this->client_id && 
               $this->client_secret &&
               $this->access_token;
    }
}