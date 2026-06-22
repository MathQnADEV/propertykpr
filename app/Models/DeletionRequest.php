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

    private static array $allowedModels = [
        \App\Models\House::class,
        \App\Models\Category::class,
        \App\Models\City::class,
        \App\Models\Bank::class,
        \App\Models\Developer::class,
        \App\Models\Cluster::class,
        \App\Models\Type::class,
        \App\Models\Interest::class,
        \App\Models\HousePhoto::class,
        \App\Models\MortgageRequest::class,
        \App\Models\Installment::class,
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
        if (!in_array($this->model_type, self::$allowedModels, true)) {
            return null;
        }
        if (!method_exists($this->model_type, 'withTrashed')) {
            return $this->model_type::find($this->model_id);
        }
        return $this->model_type::withTrashed()->find($this->model_id);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
