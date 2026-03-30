<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NgoInventoryItem extends Model
{
    public const KIND_FIXED_ASSET = 'fixed_asset';

    public const KIND_CONSUMABLE = 'consumable';

    protected $fillable = [
        'ngo_id',
        'kind',
        'name',
        'description',
        'category',
        'asset_tag',
        'serial_number',
        'quantity',
        'unit',
        'reorder_level',
        'purchase_date',
        'purchase_amount',
        'current_value_estimate',
        'asset_condition',
        'storage_location',
        'custodian_user_id',
        'supplier_name',
        'invoice_reference',
        'warranty_expires_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'reorder_level' => 'decimal:2',
            'purchase_date' => 'date',
            'purchase_amount' => 'decimal:2',
            'current_value_estimate' => 'decimal:2',
            'warranty_expires_at' => 'date',
        ];
    }

    public function ngo(): BelongsTo
    {
        return $this->belongsTo(NGO::class, 'ngo_id');
    }

    public function custodian(): BelongsTo
    {
        return $this->belongsTo(User::class, 'custodian_user_id');
    }

    public function isLowStock(): bool
    {
        if ($this->kind !== self::KIND_CONSUMABLE || $this->reorder_level === null) {
            return false;
        }

        return (float) $this->quantity <= (float) $this->reorder_level;
    }
}
