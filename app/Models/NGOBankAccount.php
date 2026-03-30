<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NGOBankAccount extends Model
{
    use HasFactory;

    protected $table = 'ngo_bank_accounts';

    protected $fillable = [
        'ngo_id',
        'bank_name',
        'account_number',
        'account_holder_name',
        'ifsc_code',
        'branch_address',
        'verification_status',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function ngo(): BelongsTo
    {
        return $this->belongsTo(NGO::class, 'ngo_id');
    }
}
