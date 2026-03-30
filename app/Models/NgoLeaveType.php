<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NgoLeaveType extends Model
{
    protected $table = 'ngo_leave_types';

    protected $fillable = [
        'ngo_id',
        'name',
        'default_days_per_year',
        'is_paid',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_paid' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function ngo(): BelongsTo
    {
        return $this->belongsTo(NGO::class, 'ngo_id');
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(NgoLeaveRequest::class, 'leave_type_id');
    }
}
