<?php

namespace App\Filament\Resources\SystemNotifications\Pages;

use App\Filament\Resources\SystemNotifications\SystemNotificationResource;
use App\Models\SystemNotification;
use App\Models\User;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateSystemNotification extends CreateRecord
{
    protected static string $resource = SystemNotificationResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $targetType = $data['target_type'] ?? '';
        $specificUsers = $data['specific_users'] ?? [];
        $title = $data['title'] ?? '';
        $description = $data['description'] ?? '';
        $url = $data['url'] ?? '';

        $userIds = [];

        if (str_contains(strtolower($targetType), 'semua agent') || $targetType === 'all_agent') {
            $userIds = User::role('agent')->pluck('id')->toArray();
        } elseif (str_contains(strtolower($targetType), 'semua admin') || $targetType === 'all_admin') {
            $userIds = User::role('admin')->pluck('id')->toArray();
        } elseif (str_contains(strtolower($targetType), 'semua investor') || $targetType === 'all_investor') {
            $userIds = User::role('investor')->pluck('id')->toArray();
        } else {
            $userIds = is_array($specificUsers) ? $specificUsers : [];
        }

        if (empty($userIds)) {
            FilamentNotification::make()
                ->title('Tidak ada penerima yang dipilih')
                ->danger()
                ->send();
            $this->halt();
            return new SystemNotification();
        }

        $now = now();
        $inserts = [];
        foreach ($userIds as $userId) {
            $inserts[] = [
                'user_id'     => (int) $userId,
                'type'        => 'broadcast',
                'title'       => $title,
                'description' => $description,
                'url'         => $url,
                'is_read'     => false,
                'target_type' => $targetType,
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        SystemNotification::insert($inserts);

        $first = SystemNotification::where('type', 'broadcast')->latest()->first();

        FilamentNotification::make()
            ->title('Berhasil dikirim ke ' . count($userIds) . ' penerima')
            ->success()
            ->send();

        return $first ?? new SystemNotification();
    }
}
