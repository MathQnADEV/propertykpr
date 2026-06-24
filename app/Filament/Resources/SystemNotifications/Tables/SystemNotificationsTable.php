<?php

namespace App\Filament\Resources\SystemNotifications\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SystemNotificationsTable
{
    public static function configure(Table $table): Table
    {
        $isMaster = fn(): bool => auth()->check() && auth()->user()->hasRole('master');

        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn($query) => $query->whereIn('type', ['broadcast', 'direct']))
            ->columns([
                TextColumn::make('target_type')
                    ->label('Tujuan')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'all_agent' => 'Semua Agent',
                        'all_admin' => 'Semua Admin',
                        'all_investor' => 'Semua Investor',
                        'specific_agent' => 'Agent Tertentu',
                        'specific_admin' => 'Admin Tertentu',
                        'specific_investor' => 'Investor Tertentu',
                        default => $state,
                    }),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('description')
                    ->label('Pesan')
                    ->limit(50)
                    ->wrap(),

                TextColumn::make('user.name')
                    ->label('Pengirim')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->since(),

                TextColumn::make('is_read')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state ? 'Dibaca' : 'Belum')
                    ->color(fn($state) => $state ? 'success' : 'warning'),

            ])
            ->filters([
                TernaryFilter::make('is_read')
                    ->label('Status Baca')
                    ->trueLabel('Sudah Dibaca')
                    ->falseLabel('Belum Dibaca'),
            ])
            ->recordActions([
                ViewAction::make(),
                DeleteAction::make()
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
                ]),
            ]);
    }
}
