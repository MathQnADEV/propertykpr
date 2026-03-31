<?php

namespace App\Filament\Resources\SystemNotifications\Pages;

use App\Filament\Resources\SystemNotifications\SystemNotificationResource;
use App\Services\NotificationService;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSystemNotification extends ViewRecord
{
    protected static string $resource = SystemNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
        ];
    }

    protected function afterView(): void
    {
        if(! $this->record->is_read) {
            NotificationService::markAsRead($this->record);
        }
    }
}
