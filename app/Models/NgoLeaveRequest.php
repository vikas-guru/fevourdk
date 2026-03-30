<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NgoLeaveRequest extends Model
{
    protected $table = 'ngo_leave_requests';

    protected $fillable = [
        'ngo_id',
        'user_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'days',
        'status',
        'reason',
        'decided_by_user_id',
        'decided_at',
        'decision_notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'days' => 'decimal:1',
            'decided_at' => 'datetime',
        ];
    }

    public function ngo(): BelongsTo
    {
        return $this->belongsTo(NGO::class, 'ngo_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(NgoLeaveType::class, 'leave_type_id');
    }

    public function decidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by_user_id');
    }
}
