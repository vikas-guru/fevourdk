<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CSRCompanyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_type',
        'industry_sector',
        'registration_number',
        'pan_number',
        'gst_number',
        'company_phone',
        'website',
        'established_year',
        'employee_count',
        'annual_turnover',
        'csr_budget',
        'registered_office_address',
        'corporate_office_address',
        'csr_focus_areas',
        'partnership_type',
        'previous_csr_activities',
        'company_logo',
        'company_brochure',
        'registration_certificate',
        'verification_status',
        'user_id',
    ];

    protected $casts = [
        'csr_focus_areas' => 'array',
        'established_year' => 'integer',
        'employee_count' => 'integer',
        'annual_turnover' => 'decimal:2',
        'csr_budget' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contactPerson(): HasMany
    {
        return $this->hasMany(CSRContactPerson::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'company_id');
    }

    public function domains(): HasMany
    {
        return $this->hasMany(CSRDomain::class);
    }

    public function analytics(): HasMany
    {
        return $this->hasMany(CSRAnalytics::class);
    }

    public function complianceReports(): HasMany
    {
        return $this->hasMany(CSRComplianceReport::class);
    }

    public function getCompanyLogoUrlAttribute(): string
    {
        return $this->company_logo ? asset('storage/' . $this->company_logo) : asset('images/default-company-logo.png');
    }

    public function getCompanyBrochureUrlAttribute(): string
    {
        return $this->company_brochure ? asset('storage/' . $this->company_brochure) : null;
    }

    public function getRegistrationCertificateUrlAttribute(): string
    {
        return $this->registration_certificate ? asset('storage/' . $this->registration_certificate) : null;
    }

    public function getVerificationStatusBadgeAttribute(): string
    {
        return match($this->verification_status) {
            'pending' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
            'verified' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Verified</span>',
            'rejected' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>',
            default => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>',
        };
    }

    public function getFormattedCsrBudgetAttribute(): string
    {
        return '₹' . number_format($this->csr_budget, 2);
    }

    public function getFormattedAnnualTurnoverAttribute(): string
    {
        return '₹' . number_format($this->annual_turnover, 2);
    }

    public function getYearsInOperationAttribute(): int
    {
        return now()->year - $this->established_year;
    }
}
