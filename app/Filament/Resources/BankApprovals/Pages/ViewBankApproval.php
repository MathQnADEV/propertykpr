<?php

namespace App\Filament\Resources\BankApprovals\Pages;

use App\Filament\Resources\BankApprovals\BankApprovalResource;
use Filament\Resources\Pages\ViewRecord;

class ViewBankApproval extends ViewRecord
{
    protected static string $resource = BankApprovalResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
