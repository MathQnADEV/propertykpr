<?php

namespace App\Services;

use App\Models\Bank;
use App\Models\BankApproval;
use App\Models\Category;
use App\Models\City;
use App\Models\House;
use App\Models\HousePhoto;
use App\Models\Installment;
use App\Models\Interest;
use App\Models\MortgageRequest;
use App\Models\SystemNotification;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class NotificationService
{

    public static bool $skipLogging = false;

    private static array $labels = [
        House::class           => 'Rumah',
        Category::class        => 'Kategori',
        City::class            => 'Kota',
        Bank::class            => 'Bank',
        Interest::class        => 'Bunga',
        HousePhoto::class      => 'Foto Rumah',
        MortgageRequest::class => 'Permohonan KPR',
        Installment::class     => 'Cicilan',
        BankApproval::class    => 'Persetujuan Bank',
    ];

    public static function log(string $type, Model $model, string $customTitle = ''): ?Activity
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

        activity()
            ->performedOn($model)
            ->causedBy($user)
            ->withProperties(['type' => $type, 'label' => $label, 'name' => $name])
            ->log($title);

        return null;
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

    public static function sendToRole(string $role, string $title, string $description = '', string $url = ''): void
    {
        $users = User::role($role)->get();
        foreach ($users as $user) {
            SystemNotification::create([
                'user_id'     => $user->id,
                'type'        => 'broadcast',
                'title'       => $title,
                'description' => $description,
                'url'         => $url,
                'is_read'     => false,
                'target_type' => $role,
            ]);
        }
    }

    public static function sendToUsers(array $userIds, string $title, string $description = '', string $url = ''): void
    {
        foreach ($userIds as $userId) {
            SystemNotification::create([
                'user_id'     => $userId,
                'type'        => 'direct',
                'title'       => $title,
                'description' => $description,
                'url'         => $url,
                'is_read'     => false,
            ]);
        }
    }

    public static function markAsRead(SystemNotification $notification): void
    {
        $notification->update(['is_read' => true]);
    }

    public static function markAllAsRead(): int
    {
        return SystemNotification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }
}
