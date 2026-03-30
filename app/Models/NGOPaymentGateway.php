<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NGOPaymentGateway extends Model
{
    use HasFactory;

    protected $table = 'ngo_payment_gateways';

    protected $fillable = [
        'ngo_id',
        'gateway_type',
        'merchant_id',
        'api_key',
        'api_secret',
        'webhook_secret',
        'is_active',
    ];

    protected $hidden = [
        'api_key',
        'api_secret',
        'webhook_secret',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function ngo(): BelongsTo
    {
        return $this->belongsTo(NGO::class, 'ngo_id');
    }
}
