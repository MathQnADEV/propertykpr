<?php

namespace App\Filament\Resources\Categories\Tables;

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

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        $isMaster = fn(): bool => auth()->check() && auth()->user()->hasRole('master');
        $isAdmin = fn(): bool => auth()->check() && auth()->user()->hasRole('admin');
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
                    ->authorize($isMaster)
                    ->visible($isMaster)
                    ->requiresConfirmation()
                    ->action(fn($record) => $record->delete()),

                Action::make('request_delete')
                    ->label('Ajukan Penghapusan')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->color('warning')
                    ->authorize($isAdmin)
                    ->visible($isAdmin)
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
