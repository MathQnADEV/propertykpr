<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        $isMaster = fn(): bool => auth()->check() && auth()->user()->hasRole('master');

        return [
            CreateAction::make()
                ->authorize($isMaster)
                ->visible($isMaster),
        ];
    }
}
