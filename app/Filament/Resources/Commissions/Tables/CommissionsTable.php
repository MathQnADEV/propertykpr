<?php

namespace App\Filament\Resources\Commissions\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CommissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('mortgageRequest.house.name')
                    ->label('Properti')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('agent.name')
                    ->label('Agent')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('mortgageRequest.house_price')
                    ->label('Harga Properti')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),

                TextColumn::make('commission_type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn ($state) => $state === 'percentage' ? 'info' : 'warning')
                    ->formatStateUsing(fn ($state) => $state === 'percentage' ? 'Persentase' : 'Nominal'),

                TextColumn::make('commission_input')
                    ->label('Input Master')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->commission_type === 'percentage'
                            ? $state . '%'
                            : 'Rp ' . number_format($state, 0, ',', '.');
                    }),

                TextColumn::make('commission_amount')
                    ->label('Komisi Agent')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
