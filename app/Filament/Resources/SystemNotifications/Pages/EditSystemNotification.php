<?php

namespace App\Filament\Resources\SystemNotifications\Pages;

use App\Filament\Resources\SystemNotifications\SystemNotificationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSystemNotification extends EditRecord
{
    protected static string $resource = SystemNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn() => auth()->user()->hasRole('master')),
            ForceDeleteAction::make()
                ->visible(fn() => auth()->user()->hasRole('master')),
            RestoreAction::make()
                ->visible(fn() => auth()->user()->hasRole('master')),
        ];
    }
}
