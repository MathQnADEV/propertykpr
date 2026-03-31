<?php

namespace App\Filament\Resources\Facilities\Tables;

use App\Services\DeletionRequestService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class FacilitiesTable
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
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('direct_delete')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn() => auth()->user()->hasRole('master'))
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->delete()),

                Action::make('request_delete')
                    ->label('Ajukan Penghapusan')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('warning')
                    ->visible(fn() => auth()->user()->hasRole('admin'))
                    ->form([
                        Textarea::make('reason')
                            ->label('Alasan Penghapusan')
                            ->required()
                            ->maxLength(500),
                    ])
                    ->action(function ($record, array $data) {
                        DeletionRequestService::requestDeletion($record, $data['reason']);
                        Notification::make()
                            ->title('Permintaan penghapusan telah dikirim ke Master')
                            ->success()
                            ->send();
                    }),
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
