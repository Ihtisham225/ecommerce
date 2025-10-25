<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Certificate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_registration_participant_id',
        'course_id',
        'title',
        'issued_at',
        'is_active'
    ];

    protected $casts = [
        'title' => 'array',
        'issued_at' => 'date',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(CompanyRegistrationParticipant::class, 'company_registration_participant_id');
    }

    // Dynamic accessor
    public function getRecipientAttribute()
    {
        return $this->user ?? $this->participant;
    }

    public function getRecipientNameAttribute()
    {
        if ($this->user) {
            // Self-registered user
            return "{$this->user->name} (Individual)";
        }

        if ($this->participant) {
            // Company participant
            $name = $this->participant->full_name;
            $company = optional($this->participant->registration)->company_name;

            return $company ? "{$name} ({$company})" : $name;
        }

        return '-';
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // Polymorphic relation with documents (certificate file)
    public function certificateFile(): MorphOne
    {
        return $this->morphOne(Document::class, 'documentable')
            ->where('document_type', 'certificate_file');
    }

    // Accessor to get localized title
    public function getTitleAttribute($value)
    {
        $title = json_decode($value, true);
        return $title[App::currentLocale()] ?? $title['en'] ?? '';
    }

    // To fetch raw multilingual titles
    public function getTitles()
    {
        return json_decode($this->attributes['title'], true);
    }

    public function recipient()
    {
        return $this->user_id
            ? $this->belongsTo(User::class)
            : $this->belongsTo(CompanyRegistrationParticipant::class, 'company_registration_participant_id');
    }
}
