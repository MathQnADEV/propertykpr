<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MortgageDocument extends Model
{
    protected $fillable = [
        'mortgage_request_id',
        'name',
        'file_path',
    ];

    public function mortgageRequest(): BelongsTo
    {
        return $this->belongsTo(MortgageRequest::class);
    }
}
