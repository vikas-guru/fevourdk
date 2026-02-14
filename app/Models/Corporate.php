<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Corporate extends Model
{
    use HasFactory;

    protected $table = 'corporates';

    protected $fillable = [
        'name',
        'pan_number',
        'cin_number',
        'gst_number',
        'state_id',
        'district_id',
        'city_id',
        'address',
        'postal_code',
        'website',
        'description',
        'industry_sector',
        'annual_turnover',
        'csr_budget',
        'contact_person_name',
        'contact_person_designation',
        'contact_person_phone',
        'contact_person_email',
        'status',
    ];

    protected $casts = [
        'annual_turnover' => 'integer',
        'csr_budget' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(CorporateDocument::class);
    }

    public function focusAreas(): BelongsToMany
    {
        return $this->belongsToMany(FocusArea::class, 'corporate_focus_areas');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function getProfileCompletionAttribute(): int
    {
        $fields = [
            'name', 'pan_number', 'address', 'description', 
            'website', 'contact_person_email', 'csr_budget'
        ];
        
        $completed = 0;
        foreach ($fields as $field) {
            if (!empty($this->$field)) $completed++;
        }
        
        return (int) (($completed / count($fields)) * 100);
    }
}
