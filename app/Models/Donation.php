<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'ngo_id',
        'campaign_id',
        'donor_id',
        'donation_type',
        'amount',
        'currency',
        'payment_gateway',
        'gateway_transaction_id',
        'status',
        'payment_response',
        'is_anonymous',
        'message',
        'donated_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_response' => 'array',
        'is_anonymous' => 'boolean',
        'donated_at' => 'datetime',
    ];

    public function ngo(): BelongsTo
    {
        return $this->belongsTo(NGO::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }
}
