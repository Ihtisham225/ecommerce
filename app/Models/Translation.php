<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'locale', 'translatable_type', 'translatable_id', 'field', 'value'
    ];

    public function translatable()
    {
        return $this->morphTo();
    }
}
