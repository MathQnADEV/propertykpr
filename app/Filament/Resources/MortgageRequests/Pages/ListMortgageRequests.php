<?php

namespace App\Filament\Resources\MortgageRequests\Pages;

use App\Filament\Resources\MortgageRequests\MortgageRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMortgageRequests extends ListRecords
{
    protected static string $resource = MortgageRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
