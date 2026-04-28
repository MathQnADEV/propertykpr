<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankApproval extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'mortgage_request_id',
        'status',
        'reviewer_id',
        'review_note',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function mortgageRequest(): BelongsTo
    {
        return $this->belongsTo(MortgageRequest::class, 'mortgage_request_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function scopeWaitingForBank($query)
    {
        return $query->where('status', 'Waiting for Bank');
    }
}
