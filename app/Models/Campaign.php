<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'ngo_id',
        'title',
        'slug',
        'description',
        'featured_image',
        'gallery',
        'target_amount',
        'raised_amount',
        'donor_count',
        'start_date',
        'end_date',
        'status',
        'focus_areas',
        'sdg_goals',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'raised_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'gallery' => 'array',
        'focus_areas' => 'array',
        'sdg_goals' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function ngo(): BelongsTo
    {
        return $this->belongsTo(NGO::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_amount <= 0) {
            return 0;
        }
        
        return min(100, ($this->raised_amount / $this->target_amount) * 100);
    }

    public function getDaysLeftAttribute(): int
    {
        return max(0, now()->diffInDays($this->end_date));
    }
}
