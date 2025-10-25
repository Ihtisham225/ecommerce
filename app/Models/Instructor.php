<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\App;

class Instructor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'bio',
        'specialization',
        'is_active',
    ];

    protected $casts = [
        'name' => 'array',
        'bio' => 'array',
        'specialization' => 'array',
    ];

     // Accessors for multilingual fields
    public function getNameAttribute($value)
    {
        $name = json_decode($value, true);
        return $name[App::currentLocale()] ?? $name['en'] ?? '';
    }

    public function getBioAttribute($value)
    {
        if (!$value) return null;
        $bio = json_decode($value, true);
        return $bio[App::currentLocale()] ?? $bio['en'] ?? '';
    }

    public function getSpecializationAttribute($value)
    {
        if (!$value) return null;
        $specialization = json_decode($value, true);
        return $specialization[App::currentLocale()] ?? $specialization['en'] ?? '';
    }

    // Get raw multilingual data
    public function getNames()
    {
        return json_decode($this->attributes['name'], true);
    }

    public function getBios()
    {
        return $this->attributes['bio'] ? json_decode($this->attributes['bio'], true) : [];
    }

    public function getSpecializations()
    {
        return $this->attributes['specialization'] ? json_decode($this->attributes['specialization'], true) : [];
    }

    // Polymorphic relation with documents (only one as CV)
    public function cv(): MorphOne
    {
        return $this->morphOne(Document::class, 'documentable')
            ->where('document_type', 'cv');
    }

    // Polymorphic relation with documents (only one as profile picture)
    public function profilePicture(): MorphOne
    {
        return $this->morphOne(Document::class, 'documentable')
            ->where('document_type', 'profile_picture');
    }
}
