<?php

namespace App\Filament\Resources\DeletionRequests\Tables;

use App\Services\DeletionRequestService;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DeletionRequestsTable
{
    public static function configure(Table $table): Table
    {
        $isMaster = fn(): bool => auth()->check() && auth()->user()->hasRole('master');
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')->label('ID')->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('model_name')->label('Data')->searchable()->wrap(),
                TextColumn::make('requester.name')->label('Pemohon')->searchable()->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn($record) => match ($record->status) {
                        'pending'  => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn($record) => match ($record->status) {
                        'pending'  => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default    => ucfirst($record->status),
                    })
                    ->sortable(),
                TextColumn::make('reason')->label('Alasan')->limit(50)->toggleable(),
                TextColumn::make('created_at')->label('Diajukan')->dateTime('d M Y, H:i')->sortable()->since(),
            ])
            ->filters([
                SelectFilter::make('status')->label('Status')->options([
                    'pending'  => 'Menunggu',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                ]),
            ])
            ->recordActions([
                ViewAction::make(),

                // ── Setujui: hanya master ──
                Action::make('approve')
                    ->label('Setujui & Hapus')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->authorize($isMaster)
                    ->visible(
                        fn($record) =>
                        $record->status === 'pending' && $isMaster
                    )
                    ->form([
                        Textarea::make('note')
                            ->label('Catatan (opsional)')
                            ->maxLength(500),
                    ])
                    ->requiresConfirmation()
                    ->action(function ($record, array $data) {
                        $success = DeletionRequestService::approve($record, $data['note'] ?? '');
                        if ($success) {
                            Notification::make()
                                ->title('Permintaan disetujui. Data telah dihapus (soft delete).')
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Gagal: Data target tidak ditemukan.')
                                ->danger()
                                ->send();
                        }
                    }),

                // ── Tolak: hanya master ──
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->authorize($isMaster)
                    ->visible(
                        fn($record) =>
                        $record->status === 'pending' && $isMaster
                    )
                    ->form([
                        Textarea::make('note')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->maxLength(500),
                    ])
                    ->requiresConfirmation()
                    ->action(function ($record, array $data) {
                        DeletionRequestService::reject($record, $data['note']);
                        Notification::make()
                            ->title('Permintaan penghapusan ditolak.')
                            ->danger()
                            ->send();
                    }),
            ])
            ->emptyStateHeading('Tidak ada permintaan penghapusan')
            ->emptyStateDescription('Permintaan akan muncul ketika admin mengajukan penghapusan data.');
    }
}
