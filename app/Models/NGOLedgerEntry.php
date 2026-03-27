<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NGOLedgerEntry extends Model
{
    use HasFactory;

    protected $table = 'ngo_ledger_entries';

    protected $fillable = [
        'ngo_id',
        'entry_date',
        'type',
        'category',
        'reference_type',
        'reference_id',
        'description',
        'amount',
        'balance_after',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function ngo(): BelongsTo
    {
        return $this->belongsTo(NGO::class, 'ngo_id');
    }
}
