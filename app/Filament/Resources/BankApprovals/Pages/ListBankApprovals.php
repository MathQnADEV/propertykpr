<?php

namespace App\Filament\Resources\BankApprovals\Pages;

use App\Filament\Resources\BankApprovals\BankApprovalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBankApprovals extends ListRecords
{
    protected static string $resource = BankApprovalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
