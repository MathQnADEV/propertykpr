<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommissionRequest extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'mortgage_request_id',
        'agent_id',
        'notes',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function mortgageRequest(): BelongsTo
    {
        return $this->belongsTo(MortgageRequest::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isProcessed(): bool { return $this->status === 'processed'; }
    public function isRejected(): bool  { return $this->status === 'rejected'; }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'Menunggu',
            'processed' => 'Diproses',
            'rejected'  => 'Ditolak',
            default     => ucfirst($this->status),
        };
    }
}
