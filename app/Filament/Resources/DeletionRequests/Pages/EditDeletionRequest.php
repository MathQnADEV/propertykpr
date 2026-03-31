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
