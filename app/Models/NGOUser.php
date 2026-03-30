<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NGOUser extends Model
{
    use HasFactory;

    protected $table = 'ngo_users';

    protected $fillable = [
        'user_id',
        'ngo_id',
        'employee_code',
        'employment_type',
        'work_location',
        'role',
        'designation',
        'job_title',
        'department',
        'joined_at',
        'manager_user_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'joined_at' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ngo(): BelongsTo
    {
        return $this->belongsTo(NGO::class, 'ngo_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_user_id');
    }
}
