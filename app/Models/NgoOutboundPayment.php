<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NgoOutboundPayment extends Model
{
    protected $table = 'ngo_outbound_payments';

    protected $fillable = [
        'ngo_id',
        'payee_user_id',
        'payee_name',
        'category',
        'amount',
        'currency',
        'payment_method',
        'status',
        'paid_at',
        'utr_reference',
        'notes',
        'recorded_by_user_id',
        'ledger_entry_id',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function ngo(): BelongsTo
    {
        return $this->belongsTo(NGO::class, 'ngo_id');
    }

    public function payeeUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payee_user_id');
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by_user_id');
    }

    public function ledgerEntry(): BelongsTo
    {
        return $this->belongsTo(NGOLedgerEntry::class, 'ledger_entry_id');
    }
}
