<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'currency',
        'currency_code',
        'is_active'
    ];

    protected $casts = [
        'name' => 'array',
        'is_active' => 'boolean'
    ];

    // Accessors for multilingual fields
    public function getNameAttribute($value)
    {
        $name = json_decode($value, true);
        return $name[App::currentLocale()] ?? $name['en'] ?? '';
    }

    public function getNames()
    {
        return json_decode($this->attributes['name'], true);
    }

    // Accessor for getting the full flag URL
    public function getFlagUrlAttribute()
    {
        if ($this->flag) {
            return Storage::disk('public')->url($this->flag);
        }
        
        return null;
    }

    public function sponsors(): HasMany
    {
        return $this->hasMany(Sponsor::class);
    }
    
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function countryFlag(): MorphOne
    {
        return $this->morphOne(Document::class, 'documentable')
            ->where('document_type', 'country_flag');
    }
}