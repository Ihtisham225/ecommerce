<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingClass extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    public function rates()
    {
        return $this->hasMany(ShippingRate::class);
    }
}
