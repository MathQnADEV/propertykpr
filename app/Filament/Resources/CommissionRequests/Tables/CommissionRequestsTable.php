<?php

namespace App\Filament\Resources\CommissionRequests\Tables;

use App\Models\Commission;
use App\Models\CommissionRequest;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CommissionRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('mortgageRequest.house.name')
                    ->label('Properti')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('agent.name')
                    ->label('Agent')
                    ->searchable(),

                TextColumn::make('mortgageRequest.house_price')
                    ->label('Harga')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),

                TextColumn::make('notes')
                    ->label('Catatan Agent')
                    ->limit(40)
                    ->placeholder('—'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'pending'   => 'warning',
                        'processed' => 'success',
                        'rejected'  => 'danger',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'pending'   => 'Menunggu',
                        'processed' => 'Diproses',
                        'rejected'  => 'Ditolak',
                        default     => $state,
                    }),

                TextColumn::make('created_at')
                    ->label('Tgl Request')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending'   => 'Menunggu',
                        'processed' => 'Diproses',
                        'rejected'  => 'Ditolak',
                    ])
                    ->default('pending'),
            ])
            ->actions([
                // ── Buat Komisi (Process) ──────────────────────────────────
                Action::make('process')
                    ->label('Buat Komisi')
                    ->icon(Heroicon::OutlinedCheckCircle)
                    ->color('success')
                    ->form([
                        TextInput::make('_house_price')
                            ->label('Harga Properti')
                            ->prefix('IDR')
                            ->readonly()
                            ->dehydrated(false),

                        TextInput::make('_base_commission')
                            ->label('Fee 2.5% (Base)')
                            ->prefix('IDR')
                            ->readonly()
                            ->dehydrated(false),

                        Select::make('commission_type')
                            ->label('Cara Input Komisi')
                            ->options([
                                'percentage' => 'Persentase (%) dari fee 2.5%',
                                'nominal'    => 'Langsung Nominal (Rp)',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (callable $get, callable $set) {
                                self::recalculate($get, $set);
                            }),

                        TextInput::make('commission_input')
                            ->label(fn (callable $get) => $get('commission_type') === 'percentage'
                                ? 'Persentase untuk Agent (%)'
                                : 'Nominal Komisi (Rp)')
                            ->suffix(fn (callable $get) => $get('commission_type') === 'percentage' ? '%' : null)
                            ->prefix(fn (callable $get) => $get('commission_type') === 'nominal' ? 'IDR' : null)
                            ->numeric()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (callable $get, callable $set) {
                                self::recalculate($get, $set);
                            }),

                        TextInput::make('commission_amount')
                            ->label('Komisi Diterima Agent (Rp)')
                            ->prefix('IDR')
                            ->readonly()
                            ->dehydrated(true),

                        Textarea::make('notes')
                            ->label('Catatan Master')
                            ->nullable(),
                    ])
                    ->fillForm(fn (CommissionRequest $record): array => [
                        '_house_price'     => $record->mortgageRequest?->house_price ?? 0,
                        '_base_commission' => round(($record->mortgageRequest?->house_price ?? 0) * 0.025),
                        'commission_amount' => 0,
                    ])
                    ->action(function (CommissionRequest $record, array $data): void {
                        // Hapus permanen komisi lama yang sudah di-soft-delete
                        // agar unique constraint tidak error saat insert baru.
                        Commission::withTrashed()
                            ->where('mortgage_request_id', $record->mortgage_request_id)
                            ->whereNotNull('deleted_at')
                            ->forceDelete();

                        Commission::create([
                            'mortgage_request_id' => $record->mortgage_request_id,
                            'agent_id'            => $record->agent_id,
                            'commission_type'     => $data['commission_type'],
                            'commission_input'    => $data['commission_input'],
                            'commission_amount'   => $data['commission_amount'],
                            'notes'               => $data['notes'] ?? null,
                        ]);

                        $record->update(['status' => 'processed']);
                    })
                    ->visible(fn (CommissionRequest $record) => $record->status === 'pending'),

                // ── Tolak ──────────────────────────────────────────────────
                Action::make('reject')
                    ->label('Tolak')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->color('danger')
                    ->form([
                        Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->placeholder('Berikan alasan penolakan yang jelas untuk agent...'),
                    ])
                    ->requiresConfirmation(false)
                    ->action(fn (CommissionRequest $record, array $data) => $record->update([
                        'status'           => 'rejected',
                        'rejection_reason' => $data['rejection_reason'],
                    ]))
                    ->visible(fn (CommissionRequest $record) => $record->status === 'pending'),
            ]);
    }

    private static function recalculate(callable $get, callable $set): void
    {
        $base  = (float) ($get('_base_commission') ?? 0);
        $input = (float) ($get('commission_input') ?? 0);
        $type  = $get('commission_type');

        $amount = match ($type) {
            'percentage' => round($base * ($input / 100)),
            'nominal'    => round($input),
            default      => 0,
        };

        $set('commission_amount', $amount);
    }
}
