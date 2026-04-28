<?php

namespace App\Filament\Resources\BankApprovals\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class BankApprovalsTable
{
    public static function configure(Table $table): Table
    {
        $canApprove = fn(): bool => auth()->check() && auth()->user()->hasRole(['admin', 'master']);
        $isMaster = fn(): bool => auth()->check() && auth()->user()->hasRole('master');

        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('mortgageRequest.customer.nama_lengkap')
                    ->label('Nasabah')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('mortgageRequest.house.name')
                    ->label('Rumah')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('mortgageRequest.bank_name')
                    ->label('Bank')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'Waiting for Bank' => 'warning',
                        'Approved'         => 'success',
                        'Rejected'         => 'danger',
                        default            => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('reviewer.name')
                    ->label('Direview oleh')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('reviewed_at')
                    ->label('Tgl Review')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('-')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Waiting for Bank' => 'Menunggu Bank',
                        'Approved'         => 'Disetujui',
                        'Rejected'         => 'Ditolak',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),

                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->authorize($canApprove)
                    ->visible(fn($record) => $record->status === 'Waiting for Bank' && $canApprove())
                    ->form([
                        Textarea::make('review_note')
                            ->label('Catatan (opsional)')
                            ->maxLength(500),
                    ])
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Persetujuan Bank')
                    ->modalDescription('Yakin ingin menyetujui permohonan KPR ini dari sisi bank?')
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status'      => 'Approved',
                            'reviewer_id' => Auth::id(),
                            'review_note' => $data['review_note'] ?? null,
                            'reviewed_at' => now(),
                        ]);

                        $record->mortgageRequest?->update(['status' => 'Approved']);

                        Notification::make()
                            ->title('Persetujuan Bank berhasil disetujui.')
                            ->success()
                            ->send();
                    }),

                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->authorize($canApprove)
                    ->visible(fn($record) => $record->status === 'Waiting for Bank' && $canApprove())
                    ->form([
                        Textarea::make('review_note')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->maxLength(500),
                    ])
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Persetujuan Bank')
                    ->modalDescription('Yakin ingin menolak permohonan KPR ini dari sisi bank?')
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status'      => 'Rejected',
                            'reviewer_id' => Auth::id(),
                            'review_note' => $data['review_note'],
                            'reviewed_at' => now(),
                        ]);

                        $record->mortgageRequest?->update(['status' => 'Rejected']);

                        Notification::make()
                            ->title('Persetujuan Bank ditolak.')
                            ->danger()
                            ->send();
                    }),
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
