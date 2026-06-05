<?php

namespace App\Filament\Resources\MortgageRequests\Schemas;

use App\Models\House;
use App\Models\Interest;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Placeholder;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use App\Models\Customer;
use Filament\Forms\Components\FileUpload;

class MortgageRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([

                    Step::make('Produk dan Harga')
                        ->schema([
                            Select::make('payment_type')
                                ->label('Tipe Pembayaran')
                                ->options([
                                    'kpr'  => 'KPR (Rumah)',
                                    'kpa'  => 'KPA (Apartemen)',
                                    'kpt'  => 'KPT (Tanah)',
                                    'kpg'  => 'KPG (Gudang)',
                                    'cash' => 'Cash (Tunai)',
                                    'sewa' => 'Sewa',
                                ])
                                ->default('kpr')
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if (in_array($state, ['cash', 'sewa'])) {
                                        $set('interest_id', null);
                                        $set('bank_name', $state === 'cash' ? 'Cash' : 'Sewa');
                                        $set('duration', 0);
                                        $set('interest', 0);
                                        $set('dp_percentage', $state === 'cash' ? 100 : 0);
                                    } else {
                                        $set('bank_name', '');
                                        $set('duration', null);
                                        $set('interest', null);
                                        $set('dp_percentage', null);
                                    }
                                }),

                            Grid::make(3)
                                ->schema([
                                    Select::make('house_id')
                                        ->label('Properti')
                                        ->options(House::query()->where('is_available', true)->pluck('name', 'id'))
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->live()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            $house = House::find($state);
                                            if ($house) {
                                                $set('house_price', $house->price ?? 0);
                                            }
                                        }),

                                    // Select Bank/KPR — options show "BankName — X% / Y Thn"
                                    Select::make('interest_id')
                                        ->label('Bank / KPR')
                                        ->options(function (callable $get) {
                                            $houseId = $get('house_id');
                                            if ($houseId) {
                                                return Interest::where('house_id', $houseId)
                                                    ->with('bank')
                                                    ->get()
                                                    ->mapWithKeys(fn ($i) => [
                                                        $i->id => ($i->bank->name ?? '-') . ' — ' . $i->interest . '% / ' . $i->duration . ' Thn',
                                                    ]);
                                            }
                                            return [];
                                        })
                                        ->searchable()
                                        ->preload()
                                        ->required(fn (callable $get) => !in_array($get('payment_type'), ['cash', 'sewa']))
                                        ->hidden(fn (callable $get) => in_array($get('payment_type'), ['cash', 'sewa']))
                                        ->live()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            $interest = Interest::find($state);
                                            if ($interest) {
                                                $set('bank_name', $interest->bank->name ?? '');
                                                $set('interest', $interest->interest);
                                                $set('duration', $interest->duration);
                                            }
                                        }),

                                    TextInput::make('bank_name')
                                        ->label('Nama Bank')
                                        ->readonly()
                                        ->required(),

                                    TextInput::make('duration')
                                        ->label('Tenor KPR')
                                        ->readonly()
                                        ->numeric()
                                        ->suffix('Tahun')
                                        ->hidden(fn (callable $get) => $get('payment_type') === 'cash')
                                        ->required(fn (callable $get) => $get('payment_type') !== 'cash')
                                        ->dehydrated(true),

                                    TextInput::make('house_price')
                                        ->label('Harga Properti')
                                        ->readonly()
                                        ->numeric()
                                        ->prefix('IDR')
                                        ->required(),

                                    // Satu field — ketik "20%" untuk persen, atau "20000000" untuk nominal Rp
                                    TextInput::make('dp_input')
                                        ->label('DP (% atau Nominal Rp)')
                                        ->placeholder('contoh: 20%  atau  20000000 — kosongkan jika bebas biaya')
                                        ->hint('Tambahkan tanda % untuk persen, angka saja untuk nominal Rp')
                                        ->hidden(fn (callable $get) => $get('payment_type') === 'cash')
                                        ->nullable()
                                        ->dehydrated(false)
                                        ->afterStateHydrated(function ($state, $record, callable $set) {
                                            // Pre-fill saat edit record yang sudah ada
                                            if ($record && $record->dp_percentage > 0) {
                                                $set('dp_input', $record->dp_percentage . '%');
                                            } elseif ($record && $record->dp_total_amount > 0) {
                                                $set('dp_input', (string) $record->dp_total_amount);
                                            }
                                        })
                                        ->live()
                                        ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                            $housePrice = (float) ($get('house_price') ?? 0);
                                            $value      = trim((string) ($state ?? ''));

                                            if ($value === '' || $housePrice <= 0) {
                                                $set('dp_percentage', 0);
                                                $set('dp_total_amount', 0);
                                                $set('loan_total_amount', round($housePrice));
                                                $set('monthly_amount', 0);
                                                $set('loan_interest_total_amount', 0);
                                                return;
                                            }

                                            if (str_contains($value, '%')) {
                                                // Mode persen — contoh: "20%"
                                                $pct      = (float) str_replace(['%', ' '], '', $value);
                                                $dpAmount = ($pct / 100) * $housePrice;
                                                $set('dp_percentage', (int) round($pct));
                                            } else {
                                                // Mode nominal — contoh: "20000000"
                                                $dpAmount = (float) str_replace(['.', ',', ' '], '', $value);
                                                $pct      = $housePrice > 0 ? ($dpAmount / $housePrice) * 100 : 0;
                                                $set('dp_percentage', (int) round($pct));
                                            }

                                            $loanAmount = max($housePrice - $dpAmount, 0);
                                            $set('dp_total_amount', round($dpAmount));
                                            $set('loan_total_amount', round($loanAmount));

                                            $durationYears = $get('duration') ?? 0;
                                            $interestRate  = $get('interest') ?? 0;

                                            if ($durationYears > 0 && $loanAmount > 0 && $interestRate > 0) {
                                                $totalPayments       = $durationYears * 12;
                                                $monthlyInterestRate = $interestRate / 100 / 12;

                                                // Amortization Formula
                                                $numerator   = $loanAmount * $monthlyInterestRate * pow(1 + $monthlyInterestRate, $totalPayments);
                                                $denominator = pow(1 + $monthlyInterestRate, $totalPayments) - 1;
                                                $monthlyPayment = $denominator > 0 ? $numerator / $denominator : 0;

                                                $set('monthly_amount', round($monthlyPayment));
                                                $set('loan_interest_total_amount', round($monthlyPayment * $totalPayments));
                                            } else {
                                                $set('monthly_amount', 0);
                                                $set('loan_interest_total_amount', 0);
                                            }
                                        }),

                                    Textarea::make('notes')
                                        ->label('Catatan')
                                        ->placeholder('contoh: Free Biaya-biaya, DP gratis, dll.')
                                        ->rows(2)
                                        ->nullable()
                                        ->columnSpanFull(),

                                    // Hidden calculated fields — values set via afterStateUpdated, saved to DB
                                    TextInput::make('dp_percentage')
                                        ->hidden()
                                        ->numeric()
                                        ->dehydrated(true),

                                    TextInput::make('interest')
                                        ->hidden()
                                        ->numeric()
                                        ->dehydrated(true),

                                    TextInput::make('dp_total_amount')
                                        ->hidden()
                                        ->numeric()
                                        ->dehydrated(true),

                                    TextInput::make('loan_total_amount')
                                        ->hidden()
                                        ->numeric()
                                        ->dehydrated(true),

                                    TextInput::make('monthly_amount')
                                        ->hidden()
                                        ->numeric()
                                        ->dehydrated(true),

                                    TextInput::make('loan_interest_total_amount')
                                        ->hidden()
                                        ->numeric()
                                        ->dehydrated(true),
                                ]),
                        ]),

                    Step::make('Informasi Nasabah')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    Select::make('customer_id')
                                        ->label('Customer')
                                        ->relationship('customer', 'nama_lengkap')
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->live()
                                        ->columnSpanFull()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            $customer = Customer::find($state);
                                            if ($customer) {
                                                $set('_customer_nik', $customer->nik);
                                                $set('_customer_phone', $customer->phone);
                                                $set('_customer_email', $customer->email ?? '-');
                                                $set('_customer_pekerjaan', $customer->pekerjaan);
                                                $set('_customer_penghasilan', $customer->penghasilan_bulanan);
                                                $set('_customer_alamat', $customer->alamat);
                                            }
                                        })
                                        ->afterStateHydrated(function (callable $set, $state) {
                                            if ($state) {
                                                $customer = Customer::find($state);
                                                if ($customer) {
                                                    $set('_customer_nik', $customer->nik);
                                                    $set('_customer_phone', $customer->phone);
                                                    $set('_customer_email', $customer->email ?? '-');
                                                    $set('_customer_pekerjaan', $customer->pekerjaan);
                                                    $set('_customer_penghasilan', $customer->penghasilan_bulanan);
                                                    $set('_customer_alamat', $customer->alamat);
                                                }
                                            }
                                        }),

                                    TextInput::make('_customer_nik')
                                        ->label('NIK')
                                        ->readonly()
                                        ->dehydrated(false),

                                    TextInput::make('_customer_phone')
                                        ->label('No. HP')
                                        ->readonly()
                                        ->dehydrated(false),

                                    TextInput::make('_customer_email')
                                        ->label('Email')
                                        ->readonly()
                                        ->dehydrated(false),

                                    TextInput::make('_customer_pekerjaan')
                                        ->label('Pekerjaan')
                                        ->readonly()
                                        ->dehydrated(false),

                                    TextInput::make('_customer_penghasilan')
                                        ->label('Penghasilan Bulanan')
                                        ->readonly()
                                        ->numeric()
                                        ->prefix('IDR')
                                        ->dehydrated(false),

                                    TextInput::make('_customer_alamat')
                                        ->label('Alamat')
                                        ->readonly()
                                        ->columnSpanFull()
                                        ->dehydrated(false),
                                ]),
                        ]),

                    Step::make('Persetujuan Bank')
                        ->schema([
                            FileUpload::make('documents')
                                ->acceptedFileTypes(['application/pdf'])
                                ->visibility('public')
                                ->required(),

                            Select::make('status')
                                ->label('Status Persetujuan')
                                ->options([
                                    'Waiting for Bank' => 'Proses Bank',
                                    'Approved' => 'Disetujui',
                                    'Rejected' => 'Ditolak',
                                ])
                                ->required(),
                        ]),

                ])
                    ->columnSpanFull()
                    ->columns(1)
                    ->skippable()
            ]);
    }
}
