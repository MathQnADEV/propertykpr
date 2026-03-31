<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeletionRequest extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table = 'deletion_requests';

    protected $fillable = [
        'requester_id',
        'model_type',
        'model_id',
        'model_name',
        'reason',
        'status',
        'reviewer_id',
        'review_note',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function getModelRecord()
    {
        return $this->model_type::withTrashed()->find($this->model_id);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
