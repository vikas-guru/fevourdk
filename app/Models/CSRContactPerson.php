<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CSRContactPerson extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'designation',
        'email',
        'phone',
        'csr_company_profile_id',
    ];

    public function companyProfile(): BelongsTo
    {
        return $this->belongsTo(CSRCompanyProfile::class);
    }
}
