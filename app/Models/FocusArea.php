<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FocusArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function ngos()
    {
        return $this->belongsToMany(NGO::class, 'ngo_focus_areas');
    }

    public function corporates()
    {
        return $this->belongsToMany(Corporate::class, 'corporate_focus_areas');
    }

    public function volunteers()
    {
        return $this->belongsToMany(Volunteer::class, 'volunteer_interests');
    }

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaign_focus_areas');
    }
}
