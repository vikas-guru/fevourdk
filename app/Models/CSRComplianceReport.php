<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CSRComplianceReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'report_type',
        'report_period',
        'compliance_score',
        'compliance_metrics',
        'findings',
        'recommendations',
        'report_data',
        'status',
        'submitted_at',
        'reviewed_at',
    ];

    protected $casts = [
        'compliance_metrics' => 'array',
        'findings' => 'array',
        'recommendations' => 'array',
        'report_data' => 'array',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(CSRCompanyProfile::class);
    }

    public function getComplianceScoreColorAttribute(): string
    {
        $score = (float) $this->compliance_score;
        if ($score >= 90) return 'text-green-600';
        if ($score >= 75) return 'text-yellow-600';
        if ($score >= 50) return 'text-orange-600';
        return 'text-red-600';
    }

    public function getComplianceGradeAttribute(): string
    {
        $score = (float) $this->compliance_score;
        if ($score >= 90) return 'A+';
        if ($score >= 85) return 'A';
        if ($score >= 75) return 'B+';
        if ($score >= 65) return 'B';
        if ($score >= 50) return 'C';
        return 'D';
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }
}
