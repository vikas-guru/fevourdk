<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NGODocument extends Model
{
    use HasFactory;

    protected $table = 'ngo_documents';

    protected $fillable = [
        'ngo_id',
        'document_type',
        'file_path',
        'status',
        'remarks',
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
