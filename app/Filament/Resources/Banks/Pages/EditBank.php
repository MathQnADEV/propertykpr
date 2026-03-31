<?php

namespace App\Filament\Resources\Banks\Pages;

use App\Filament\Resources\Banks\BankResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBank extends EditRecord
{
    protected static string $resource = BankResource::class;

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
