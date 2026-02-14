<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class NGO extends Model
{
    use HasFactory;

    protected $table = 'ngos';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'registration_number',
        'pan',
        'email',
        'phone',
        'website',
        'logo',
        'address',
        'city_id',
        'latitude',
        'longitude',
        'verification_status',
        'focus_areas',
        'is_active',
        'verified_at',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'focus_areas' => 'array',
        'is_active' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(NGODocument::class);
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(NGOBankAccount::class);
    }

    public function paymentGateways(): HasMany
    {
        return $this->hasMany(NGOPaymentGateway::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(NGOUser::class);
    }

    public function auditLogs(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'model');
    }
}
