<?php

namespace App\Filament\Resources\SystemNotifications\Tables;

use App\Services\NotificationService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SystemNotificationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')->label('ID')->sortable()->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('type')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'created'         => 'success',
                        'updated'         => 'warning',
                        'deleted'         => 'danger',
                        'delete_request'  => 'danger',
                        'delete_approved' => 'success',
                        'delete_rejected' => 'danger',
                        'restored'        => 'info',
                        default           => 'gray',
                    })
                    ->formatStateUsing(fn($state) => match ($state) {
                        'created'         => 'Dibuat',
                        'updated'         => 'Diubah',
                        'deleted'         => 'Dihapus',
                        'delete_request'  => 'Minta Hapus',
                        'delete_approved' => 'Hapus Disetujui',
                        'delete_rejected' => 'Hapus Ditolak',
                        'restored'        => 'Dipulihkan',
                        default           => $state,
                    }),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->weight(fn($record) => $record->is_read ? 'normal' : 'bold')
                    ->wrap(),

                TextColumn::make('user.name')
                    ->label('Oleh')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->since(),

            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'created'         => 'Dibuat',
                        'updated'         => 'Diubah',
                        'deleted'         => 'Dihapus',
                        'delete_request'  => 'Minta Hapus',
                        'delete_approved' => 'Hapus Disetujui',
                        'delete_rejected' => 'Hapus Ditolak',
                        'restored'        => 'Dipulihkan',
                    ]),
                TernaryFilter::make('is_read')
                    ->label('Status Baca')
                    ->trueLabel('Sudah Dibaca')
                    ->falseLabel('Belum Dibaca'),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('mark_read')
                    ->label('Tandai Dibaca')
                    ->icon('heroicon-o-check')
                    ->visible(fn($record) => ! $record->is_read)
                    ->action(function ($record) {
                        NotificationService::markAsRead($record);
                        Notification::make()
                            ->title('Ditandai sudah dibaca')
                            ->success()
                            ->send();
                    })->visible(fn() => auth()->user()->hasRole('master')),

                // Hapus notifikasi: hanya master
                DeleteAction::make()
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
