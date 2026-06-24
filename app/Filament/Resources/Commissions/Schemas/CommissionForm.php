<?php

namespace App\Filament\Resources\Commissions\Schemas;

use App\Models\Commission;
use App\Models\MortgageRequest;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class CommissionForm
{
    public static function configure(Schema $schema): Schema
    {
        $recalculate = function (callable $get, callable $set): void {
            $base  = (float) ($get('_base_commission') ?? 0);
            $input = (float) ($get('commission_input') ?? 0);
            $type  = $get('commission_type');

            $amount = match ($type) {
                'percentage' => round($base * ($input / 100)),
                'nominal'    => round($input),
                default      => 0,
            };

            $set('commission_amount', $amount);
        };

        return $schema->components([

            // ── SECTION 1: Pilih Agent ────────────────────────────────────────
            Section::make('Pilih Agent')
                ->description('Pilih agent terlebih dahulu untuk melihat statistik penjualan sebelum menginput komisi.')
                ->schema([

                    Select::make('_agent_id')
                        ->label('Agent')
                        ->columnSpanFull()
                        ->options(fn () => User::role('agent')->orderBy('name')->pluck('name', 'id')->toArray())
                        ->searchable()
                        ->live()
                        ->dehydrated(false)
                        ->afterStateUpdated(function (callable $set) {
                            $set('mortgage_request_id', null);
                            $set('_house_price',        null);
                            $set('_base_commission',    null);
                            $set('commission_amount',   0);
                            $set('commission_input',    null);
                        }),

                    View::make('filament.commissions.agent-stats')
                        ->viewData(function (callable $get) {
                            $agentId = $get('_agent_id');
                            if (! $agentId) {
                                return [];
                            }

                            $baseDeals = MortgageRequest::where('status', 'Approved')
                                ->whereHas('house', fn ($q) => $q->where('agent_id', $agentId));

                            $totalDeals      = $baseDeals->count();
                            $totalValue      = (float) $baseDeals->sum('house_price');
                            $dealIds         = $baseDeals->pluck('id');
                            $withComm        = Commission::whereIn('mortgage_request_id', $dealIds)->count();
                            $withoutComm     = $totalDeals - $withComm;
                            $totalCommAmount = (float) Commission::whereIn('mortgage_request_id', $dealIds)->sum('commission_amount');

                            return [
                                'agentName'       => User::find($agentId)?->name ?? '-',
                                'totalDeals'      => $totalDeals,
                                'totalValue'      => $totalValue,
                                'withComm'        => $withComm,
                                'withoutComm'     => $withoutComm,
                                'totalCommAmount' => $totalCommAmount,
                            ];
                        })
                        ->columnSpanFull()
                        ->hidden(fn (callable $get) => ! $get('_agent_id')),

                ]),

            // ── SECTION 2: Pilih Deal ─────────────────────────────────────────
            Section::make('Pilih Deal')
                ->description(fn ($record) => $record
                    ? 'Deal yang terhubung ke komisi ini (tidak dapat diubah setelah komisi dibuat).'
                    : 'Hanya deal berstatus Approved yang belum memiliki komisi yang ditampilkan.')
                ->visible(fn (callable $get) => (bool) $get('_agent_id'))
                ->columns(2)
                ->schema([

                    Select::make('mortgage_request_id')
                        ->label('Deal / Pengajuan')
                        ->columnSpanFull()
                        ->options(function ($record, callable $get) {
                            $agentId = $get('_agent_id');

                            $query = MortgageRequest::where('status', 'Approved')
                                ->with(['house', 'customer']);

                            if ($agentId) {
                                $query->whereHas('house', fn ($q) => $q->where('agent_id', $agentId));
                            }

                            if (! $record) {
                                // CREATE: hanya tampilkan deal yang belum berkomisi
                                $query->doesntHave('commission');
                            } else {
                                // EDIT: hanya tampilkan deal ini saja (read-only)
                                $query->where('id', $record->mortgage_request_id);
                            }

                            return $query->get()->mapWithKeys(fn ($mr) => [
                                $mr->id => ($mr->house?->name ?? '-') . ' — ' . ($mr->customer?->nama_lengkap ?? '-'),
                            ]);
                        })
                        ->searchable()
                        ->required()
                        ->disabled(fn ($record) => (bool) $record) // Read-only saat edit
                        ->dehydrated(true)
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $mr = MortgageRequest::find($state);
                            if ($mr) {
                                $set('_house_price',     $mr->house_price);
                                $set('_base_commission', round($mr->house_price * 0.025));
                                $set('commission_amount', 0);
                                $set('commission_input',  null);
                            } else {
                                $set('_house_price',     null);
                                $set('_base_commission', null);
                                $set('commission_amount', 0);
                            }
                        })
                        ->afterStateHydrated(function ($state, callable $set) {
                            // _agent_id, _house_price, _base_commission di-set via
                            // EditCommission::mutateFormDataBeforeFill agar tidak
                            // men-trigger afterStateUpdated cascade yang mereset commission_amount.
                        }),

                    TextInput::make('_house_price')
                        ->label('Harga Properti')
                        ->readonly()
                        ->numeric()
                        ->prefix('IDR')
                        ->dehydrated(false),

                    TextInput::make('_base_commission')
                        ->label('Fee 2.5% (Standar Industri)')
                        ->readonly()
                        ->numeric()
                        ->prefix('IDR')
                        ->dehydrated(false),

                ]),

            // ── SECTION 3: Input Komisi ───────────────────────────────────────
            Section::make('Input Komisi')
                ->description('Masukkan besaran komisi yang akan diterima agent dari deal ini.')
                ->visible(fn (callable $get) => (bool) $get('mortgage_request_id'))
                ->columns(2)
                ->schema([

                    Select::make('commission_type')
                        ->label('Cara Input Komisi')
                        ->options([
                            'percentage' => 'Persentase (%) dari fee 2.5%',
                            'nominal'    => 'Langsung Nominal (Rp)',
                        ])
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn (callable $get, callable $set) => $recalculate($get, $set)),

                    TextInput::make('commission_input')
                        ->label(fn (callable $get) => $get('commission_type') === 'percentage'
                            ? 'Persentase untuk Agent (%)'
                            : 'Nominal Komisi Agent (Rp)')
                        ->suffix(fn (callable $get) => $get('commission_type') === 'percentage' ? '%' : null)
                        ->prefix(fn (callable $get) => $get('commission_type') === 'nominal' ? 'IDR' : null)
                        ->numeric()
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn (callable $get, callable $set) => $recalculate($get, $set)),

                    TextInput::make('commission_amount')
                        ->label('Komisi Diterima Agent (Rp)')
                        ->readonly()
                        ->numeric()
                        ->prefix('IDR')
                        ->columnSpanFull()
                        ->dehydrated(true),

                    Textarea::make('notes')
                        ->label('Catatan')
                        ->columnSpanFull()
                        ->nullable(),

                ]),

        ]);
    }
}
