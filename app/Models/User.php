<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'city_id',
        'is_active',
        'last_login_at',
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
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function donor(): HasOne
    {
        return $this->hasOne(Donor::class);
    }

    public function ngoUser(): HasOne
    {
        return $this->hasOne(NGOUser::class);
    }

    public function corporateUser(): HasOne
    {
        return $this->hasOne(CorporateUser::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class, 'donor_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function auditLogs(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'model');
    }

    public function companyProfile(): HasOne
    {
        return $this->hasOne(CSRCompanyProfile::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function isStateAdmin(): bool
    {
        return $this->hasRole('state_admin');
    }

    public function isNGOAdmin(): bool
    {
        return $this->hasRole('ngo_admin');
    }

    public function isCorporateAdmin(): bool
    {
        return $this->hasRole('corporate_admin');
    }

    public function isDonor(): bool
    {
        return $this->hasRole('donor');
    }

    public function isVendor(): bool
    {
        return $this->hasRole('vendor');
    }

    public function isVolunteer(): bool
    {
        return $this->hasRole('volunteer');
    }
}
