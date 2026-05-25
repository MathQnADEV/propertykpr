<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commission extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'mortgage_request_id',
        'agent_id',
        'commission_type',
        'commission_input',
        'commission_amount',
        'notes',
    ];

    protected $casts = [
        'commission_input'  => 'decimal:2',
        'commission_amount' => 'decimal:2',
    ];

    public function mortgageRequest(): BelongsTo
    {
        return $this->belongsTo(MortgageRequest::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}
