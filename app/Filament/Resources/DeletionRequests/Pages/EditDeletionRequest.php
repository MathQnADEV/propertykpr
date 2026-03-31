<?php

namespace App\Filament\Resources\DeletionRequests\Pages;

use App\Filament\Resources\DeletionRequests\DeletionRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDeletionRequest extends EditRecord
{
    protected static string $resource = DeletionRequestResource::class;

    protected function getHeaderActions(): array
    {
        $isMaster = fn(): bool => auth()->check() && auth()->user()->hasRole('master');
        return [
            DeleteAction::make()
                ->authorize($isMaster)
                ->visible($isMaster),
            ForceDeleteAction::make()
                ->authorize($isMaster)
                ->visible($isMaster),
            RestoreAction::make()
                ->authorize($isMaster)
                ->visible($isMaster),
        ];
    }
}
