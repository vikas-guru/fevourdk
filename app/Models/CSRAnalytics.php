<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CSRAnalytics extends Model
{
    use HasFactory;

    protected $table = 'csr_analytics';

    protected $fillable = [
        'company_id',
        'year',
        'quarter',
        'total_funds_distributed',
        'funds_utilized',
        'impact_score',
        'ngos_partnered',
        'campaigns_supported',
        'beneficiaries_reached',
        'focus_areas',
        'metrics',
        'notes',
    ];

    protected $casts = [
        'total_funds_distributed' => 'decimal:2',
        'funds_utilized' => 'decimal:2',
        'impact_score' => 'decimal:2',
        'ngos_partnered' => 'integer',
        'campaigns_supported' => 'integer',
        'beneficiaries_reached' => 'integer',
        'focus_areas' => 'array',
        'metrics' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(CSRCompanyProfile::class, 'company_id');
    }

    public function getFormattedTotalFundsAttribute(): string
    {
        return '₹' . number_format($this->total_funds_distributed, 2);
    }

    public function getFormattedFundsUtilizedAttribute(): string
    {
        return '₹' . number_format($this->funds_utilized, 2);
    }

    public function getUtilizationPercentageAttribute(): float
    {
        if ($this->total_funds_distributed > 0) {
            return round(($this->funds_utilized / $this->total_funds_distributed) * 100, 2);
        }
        return 0;
    }

    public function scopeForYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeForQuarter($query, $quarter)
    {
        return $query->where('quarter', $quarter);
    }
}
