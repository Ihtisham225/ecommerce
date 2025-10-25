<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\App;

class Sponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'website',
        'contact_email',
        'contact_phone',
        'country_id',
        'is_active'
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
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

    public function getDescriptionAttribute($value)
    {
        if (!$value) return null;
        $description = json_decode($value, true);
        return $description[App::currentLocale()] ?? $description['en'] ?? '';
    }

    public function getDescriptions()
    {
        return $this->attributes['description'] ? json_decode($this->attributes['description'], true) : [];
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function sponsorLogo(): MorphOne
    {
        return $this->morphOne(Document::class, 'documentable')
            ->where('document_type', 'sponsor_logo');
    }
}