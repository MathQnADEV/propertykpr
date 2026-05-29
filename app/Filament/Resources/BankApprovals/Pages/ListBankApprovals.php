<?php

namespace App\Filament\Resources\BankApprovals\Pages;

use App\Filament\Resources\BankApprovals\BankApprovalResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListBankApprovals extends ListRecords
{
    protected static string $resource = BankApprovalResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),
            'kpr' => Tab::make('KPR (Kredit)')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereHas('mortgageRequest', fn ($q) => $q->where('payment_type', 'kpr'))
                ),
            'cash' => Tab::make('Cash (Tunai)')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereHas('mortgageRequest', fn ($q) => $q->where('payment_type', 'cash'))
                ),
        ];
    }
}
