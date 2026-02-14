<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;

    protected $table = 'availability_slots';

    protected $fillable = [
        'name',
        'description',
        'time_slot',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function volunteers()
    {
        return $this->belongsToMany(Volunteer::class, 'volunteer_availability');
    }
}
