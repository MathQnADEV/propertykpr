<?php

namespace App\Filament\Resources\Roles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        $isMaster = fn(): bool => auth()->check() && auth()->user()->hasRole('master');

        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
            ])
            ->filters([
                // TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()
                    ->authorize($isMaster)
                    ->visible($isMaster),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorize($isMaster)
                        ->visible($isMaster),
                    ForceDeleteBulkAction::make()
                        ->authorize($isMaster)
                        ->visible($isMaster),
                    RestoreBulkAction::make()
                        ->authorize($isMaster)
                        ->visible($isMaster),
                ]),
            ]);
    }
}
