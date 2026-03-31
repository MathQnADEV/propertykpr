<?php

namespace App\Models\Concerns;

use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            if (!Auth::check()) return;
            NotificationService::log('created', $model);
        });

        static::updated(function ($model) {
            if (!Auth::check()) return;
            if ($model->wasRecentlyCreated) return;
            if (!$model->isDirty()) return;
            if ($model->isDirty('deleted_at')) return; // jangan log soft delete sebagai "updated"
            NotificationService::log('updated', $model);
        });

        static::deleted(function ($model) {
            if (!Auth::check()) return;
            NotificationService::log('deleted', $model);
        });

        static::restored(function ($model) {
            if (!Auth::check()) return;
            NotificationService::log('restored', $model);
        });
    }
}
