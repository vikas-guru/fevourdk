<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Donor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'donor_type',
        'total_donated',
        'donation_count',
        'first_donation_date',
        'last_donation_date',
        'is_anonymous',
    ];

    protected $casts = [
        'total_donated' => 'decimal:2',
        'first_donation_date' => 'date',
        'last_donation_date' => 'date',
        'is_anonymous' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function recurringDonations(): HasMany
    {
        return $this->hasMany(RecurringDonation::class);
    }
}
