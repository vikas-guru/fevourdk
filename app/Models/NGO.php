<?php

namespace App\Models;

use App\Traits\HasLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class NGO extends Model
{
    use HasFactory, HasLocation;

    protected $table = 'ngos';

    protected $fillable = [
        'name',
        'slug',
        'founder_name',
        'founder_phone',
        'founder_email',
        'cofounder_name',
        'cofounder_phone',
        'cofounder_email',
        'description',
        'registration_number',
        'pan',
        'email',
        'phone',
        'website',
        'logo',
        'theme_color',
        'address',
        'state_id',
        'district_id',
        'city_id',
        'latitude',
        'longitude',
        'verification_status',
        'focus_areas',
        'is_active',
        'verified_at',
        'website_url',
        'custom_domain',
        'custom_domain_status',
        'tawk_property_id',
        'tawk_widget_id',
        'facebook_url',
        'instagram_url',
        'google_business_location_id',
        'google_business_auto_post',
        'digitalization_settings',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'focus_areas' => 'array',
        'is_active' => 'boolean',
        'verified_at' => 'datetime',
        'google_business_auto_post' => 'boolean',
        'digitalization_settings' => 'array',
    ];

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'ngo_id');
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class, 'ngo_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(NGODocument::class, 'ngo_id');
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(NGOBankAccount::class, 'ngo_id');
    }

    public function paymentGateways(): HasMany
    {
        return $this->hasMany(NGOPaymentGateway::class, 'ngo_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(NGOUser::class, 'ngo_id');
    }

    public function socialChannels(): HasMany
    {
        return $this->hasMany(NGOSocialChannel::class, 'ngo_id');
    }

    public function socialPostJobs(): HasMany
    {
        return $this->hasMany(NGOSocialPostJob::class, 'ngo_id');
    }

    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(NGOLedgerEntry::class, 'ngo_id');
    }

    public function auditLogs(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'model');
    }

    /**
     * Get campaigns count for the NGO
     */
    public function getCampaignsCountAttribute(): int
    {
        return $this->campaigns()->count();
    }

    /**
     * Get donations count for the NGO
     */
    public function getDonorsCountAttribute(): int
    {
        return $this->donations()->count();
    }

    /**
     * Get years active since verification
     */
    public function getYearsActiveAttribute(): int
    {
        if (!$this->verified_at) {
            return 0;
        }
        
        return $this->verified_at->diffInYears(now());
    }

    /**
     * Get short location display
     */
    public function getShortLocationAttribute(): string
    {
        if ($this->city && $this->city->name) {
            return "{$this->city->name}, {$this->state->code}";
        }
        
        if ($this->district && $this->district->name) {
            return "{$this->district->name}, {$this->state->code}";
        }
        
        return $this->state ? $this->state->name : 'Unknown';
    }

    /**
     * Get full location display
     */
    public function getFullLocationAttribute(): string
    {
        $parts = [];
        
        if ($this->city && $this->city->name) {
            $parts[] = $this->city->name;
        }
        
        if ($this->district && $this->district->name) {
            $parts[] = $this->district->name;
        }
        
        if ($this->state && $this->state->name) {
            $parts[] = $this->state->name;
        }
        
        return empty($parts) ? 'Unknown Location' : implode(', ', $parts);
    }

    /**
     * Scope for verified NGOs only
     */
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    /**
     * Scope for active NGOs only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for NGOs in a specific state
     */
    public function scopeInState($query, $stateId)
    {
        return $query->where('state_id', $stateId);
    }

    /**
     * Scope for NGOs in a specific district
     */
    public function scopeInDistrict($query, $districtId)
    {
        return $query->where('district_id', $districtId);
    }

    /**
     * Scope for NGOs in a specific city
     */
    public function scopeInCity($query, $cityId)
    {
        return $query->where('city_id', $cityId);
    }

    /**
     * Scope for NGOs with specific focus areas
     */
    public function scopeWithFocusArea($query, $area)
    {
        return $query->whereJsonContains('focus_areas', $area);
    }

    /**
     * Scope for NGOs with multiple focus areas
     */
    public function scopeWithFocusAreas($query, array $areas)
    {
        return $query->whereJsonContains('focus_areas', $areas);
    }

    /**
     * Get unique focus areas across all NGOs
     */
    public static function getUniqueFocusAreas(): array
    {
        $focusAreas = static::verified()->active()->pluck('focus_areas')->flatten()->unique()->filter()->values()->toArray();
        
        sort($focusAreas);
        
        return $focusAreas;
    }

    /**
     * Get location statistics for NGO
     */
    public function getLocationStats(): array
    {
        return [
            'state_name' => $this->state ? $this->state->name : null,
            'district_name' => $this->district ? $this->district->name : null,
            'city_name' => $this->city ? $this->city->name : null,
            'full_location' => $this->getFullLocationAttribute(),
            'short_location' => $this->getShortLocationAttribute(),
            'campaigns_count' => $this->getCampaignsCountAttribute(),
            'donors_count' => $this->getDonorsCountAttribute(),
            'years_active' => $this->getYearsActiveAttribute()
        ];
    }
}
