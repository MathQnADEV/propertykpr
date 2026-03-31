<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

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
