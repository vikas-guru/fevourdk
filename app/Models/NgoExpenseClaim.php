<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NgoExpenseClaim extends Model
{
    protected $table = 'ngo_expense_claims';

    protected $fillable = [
        'ngo_id',
        'user_id',
        'amount',
        'currency',
        'category',
        'description',
        'receipt_path',
        'status',
        'reviewed_by_user_id',
        'reviewed_at',
        'review_notes',
        'ledger_note',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'reviewed_at' => 'datetime',
        ];
    }

    public function ngo(): BelongsTo
    {
        return $this->belongsTo(NGO::class, 'ngo_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id');
    }
}
