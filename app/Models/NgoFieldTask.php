<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NgoFieldTask extends Model
{
    protected $table = 'ngo_field_tasks';

    protected $fillable = [
        'ngo_id',
        'created_by_user_id',
        'assigned_by_user_id',
        'assignee_id',
        'title',
        'description',
        'task_type',
        'status',
        'priority',
        'due_at',
        'target_latitude',
        'target_longitude',
        'assigned_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
            'assigned_at' => 'datetime',
            'completed_at' => 'datetime',
            'target_latitude' => 'decimal:7',
            'target_longitude' => 'decimal:7',
        ];
    }

    public function ngo(): BelongsTo
    {
        return $this->belongsTo(NGO::class, 'ngo_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by_user_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(NgoFieldSession::class, 'field_task_id');
    }
}
