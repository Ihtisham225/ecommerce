<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function dashboardRoute(): string
    {
        return match (true) {
            $this->hasRole('admin')    => route('admin.dashboard'),
            $this->hasRole('vendor')   => route('vendor.dashboard'),
            $this->hasRole('customer') => route('customer.dashboard'),
            default                    => '/',
        };
    }

    // Relationships
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    // Scopes
    public function scopeCustomers($query)
    {
        return $query->role('customer');
    }

    // Polymorphic relation with documents (only one as user avatar)
    public function userAvatar(): MorphOne
    {
        return $this->morphOne(Document::class, 'documentable')
            ->where('document_type', 'user_avatar');
    }
}
