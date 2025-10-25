<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryLocation extends Model
{
    protected $fillable = [
        'name', 'code', 'country', 'city',
        'address', 'is_default', 'is_active'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function stocks()
    {
        return $this->hasMany(InventoryStock::class);
    }
}
