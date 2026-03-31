<?php

namespace App\Filament\Resources\MortgageRequests\Pages;

use App\Filament\Resources\MortgageRequests\MortgageRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditMortgageRequest extends EditRecord
{
    protected static string $resource = MortgageRequestResource::class;

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
