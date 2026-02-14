<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Volunteer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'education',
        'occupation',
        'experience_years',
        'previous_volunteer',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'motivation',
        'status',
    ];

    protected $casts = [
        'experience_years' => 'integer',
        'previous_volunteer' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'volunteer_skills');
    }

    public function availability(): BelongsToMany
    {
        return $this->belongsToMany(Availability::class, 'volunteer_availability');
    }

    public function interests(): BelongsToMany
    {
        return $this->belongsToMany(FocusArea::class, 'volunteer_interests');
    }

    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class, 'campaign_volunteers');
    }

    public function hours(): HasMany
    {
        return $this->hasMany(VolunteerHour::class);
    }

    public function getProfileCompletionAttribute(): int
    {
        $fields = [
            'education', 'occupation', 'motivation', 
            'emergency_contact_name', 'emergency_contact_phone'
        ];
        
        $completed = 0;
        foreach ($fields as $field) {
            if (!empty($this->$field)) $completed++;
        }
        
        return (int) (($completed / count($fields)) * 100);
    }

    public function getTotalHoursAttribute(): int
    {
        return $this->hours()->sum('hours');
    }

    public function getActiveCampaignsAttribute(): int
    {
        return $this->campaigns()
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->count();
    }
}
