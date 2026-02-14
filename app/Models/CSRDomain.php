<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CSRDomain extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'domain_name',
        'domain_url',
        'verification_status',
        'verification_documents',
        'verified_at',
    ];

    protected $casts = [
        'verification_documents' => 'array',
        'verified_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(CSRCompanyProfile::class);
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

    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }
}
