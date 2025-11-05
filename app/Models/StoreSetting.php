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
}
