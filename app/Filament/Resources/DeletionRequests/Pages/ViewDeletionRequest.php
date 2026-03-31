<?php

namespace App\Filament\Resources\DeletionRequests\Pages;

use App\Filament\Resources\DeletionRequests\DeletionRequestResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDeletionRequest extends ViewRecord
{
    protected static string $resource = DeletionRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
