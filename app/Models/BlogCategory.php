<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class BlogCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 
        'slug', 
        'description'
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
    ];

    // Accessors for multilingual fields
    public function getNameAttribute($value)
    {
        $name = json_decode($value, true);
        return $name[App::currentLocale()] ?? $name['en'] ?? '';
    }

    public function getDescriptionAttribute($value)
    {
        if (!$value) return null;
        
        $description = json_decode($value, true);
        return $description[App::currentLocale()] ?? $description['en'] ?? '';
    }

    // Get raw multilingual array
    public function getNames()
    {
        return json_decode($this->attributes['name'], true);
    }

    public function getDescriptions()
    {
        return $this->attributes['description'] ? json_decode($this->attributes['description'], true) : [];
    }

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }

    // Scopes
    public function scopeWithBlogs($query)
    {
        return $query->with('blogs');
    }
}