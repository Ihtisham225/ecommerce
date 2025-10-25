<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyRegistrationParticipant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_registration_id',
        'salutation',
        'full_name',
        'participant_number',
        'email',
        'mobile',
        'city_of_living',
    ];

    /**
     * Get the company registration this participant belongs to.
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(CompanyRegistration::class, 'company_registration_id');
    }
}

