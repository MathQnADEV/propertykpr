<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo'),
                TextColumn::make('name')
                    ->searchable(),
            ])
            ->filters([
                // TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn() => auth()->user()->hasRole('master')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn() => auth()->user()->hasRole('master')),
                    ForceDeleteBulkAction::make()
                        ->visible(fn() => auth()->user()->hasRole('master')),
                    RestoreBulkAction::make()
                        ->visible(fn() => auth()->user()->hasRole('master')),
                ]),
            ]);
    }
}
