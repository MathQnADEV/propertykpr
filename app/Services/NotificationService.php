<?php

namespace App\Services;

use App\Models\Bank;
use App\Models\Category;
use App\Models\City;
use App\Models\Facility;
use App\Models\House;
use App\Models\HousePhoto;
use App\Models\Installment;
use App\Models\Interest;
use App\Models\MortgageRequest;
use App\Models\SystemNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Auth;

class NotificationService
{

    public static bool $skipLogging = false;

    private static array $labels = [
        House::class           => 'Rumah',
        Category::class        => 'Kategori',
        City::class            => 'Kota',
        Bank::class            => 'Bank',
        Facility::class        => 'Fasilitas',
        Interest::class        => 'Bunga',
        HousePhoto::class      => 'Foto Rumah',
        MortgageRequest::class => 'Permohonan KPR',
        Installment::class     => 'Cicilan',
    ];

    private static array $resourceRoutes = [
        House::class           => '/admin/houses',
        Category::class        => '/admin/categories',
        City::class            => '/admin/cities',
        Bank::class            => '/admin/banks',
        Facility::class        => '/admin/facilities',
        Interest::class        => '/admin/interests',
        HousePhoto::class      => '/admin/house-photos',
        MortgageRequest::class => '/admin/mortgage-requests',
        Installment::class     => '/admin/installments',
    ];

    public static function log(string $type, Model $model, string $customTitle = ''): ?SystemNotification
    {
        if (self::$skipLogging)
            return null;
        $user = Auth::user();
        $label = self::$labels[$model::class] ?? class_basename($model::class);
        $name = $model->name ?? $model->email ?? "#{$model->id}";

        $title = $customTitle ?: match ($type) {
            'created'   => "{$user->name} menambahkan {$label}: {$name}",
            'updated'   => "{$user->name} mengubah {$label}: {$name}",
            'deleted'   => "{$user->name} menghapus {$label}: {$name}",
            'restored'  => "{$user->name} memulihkan {$label}: {$name}",
            default     => "{$user->name} {$type} {$label}: {$name}",
        };

        $url = null;
        if (in_array($type, ['created', 'updated'])) {
            $route = self::$resourceRoutes[$model::class] ?? null;
            if ($route) {
                $url = env('APP_URL')."{$route}/{$model->id}/edit";
            }
        }

        return SystemNotification::create([
            'user_id'     => $user->id,
            'type'        => $type,
            'title'       => $title,
            'description' => "{$title}\nWaktu: " . now()->format('d M Y H:i') . "\nOleh: {$user->name} ({$user->email})",
            'url' => $url,
            'is_read'     => false,
        ]);
    }

    public static function notify(string $type, string $title, string $description = '', string $url = ''): SystemNotification
    {
        return SystemNotification::create([
            'user_id'     => Auth::user()->id,
            'type'        => $type,
            'title'       => $title,
            'description' => $description,
            'url'         => $url,
            'is_read'     => false,
        ]);
    }

    public static function markAsRead(SystemNotification $notification): void
    {
        $notification->update(['is_read' => true]);
    }

    public static function markAllAsRead(): int
    {
        return SystemNotification::where('is_read', false)->update(['is_read' => true]);
    }
}
