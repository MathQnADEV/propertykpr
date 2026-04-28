<?php

namespace App\Filament\Resources\MortgageRequests\Schemas;

use App\Models\House;
use App\Models\Interest;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
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
                        ->schema(
                            [
                                Grid::make(3)
                                    ->schema([
                                        Select::make('house_id')
                                            ->label('Properti')
                                            ->options(House::query()->where('is_available', true)->pluck('name', 'id'))
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->live() // live to trigger filtering of interests
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                $house = House::find($state);
                                                if ($house) {
                                                    $set('house_price', $house->price ?? 0);
                                                }
                                            }),

                                        // Then Select Interest Based On Selected House
                                        Select::make('interest_id')
                                            ->label('Suku Bunga Tahunan (%)')
                                            ->options(function (callable $get) {
                                                $houseId = $get('house_id');
                                                if ($houseId) {
                                                    return Interest::where('house_id', $houseId)
                                                        ->get()
                                                        ->pluck('interest', 'id');
                                                }
                                                return [];
                                            })
                                            ->searchable()
                                            ->preload()
                                            ->required()
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
                                            ->label('Durasi (Tahun)')
                                            ->readonly()
                                            ->numeric()
                                            ->suffix('Tahun')
                                            ->required(),

                                        TextInput::make('interest')
                                            ->label('Suku Bunga')
                                            ->readonly()
                                            ->numeric()
                                            ->suffix('%')
                                            ->required(),

                                        TextInput::make('house_price')
                                            ->label('Harga Properti')
                                            ->readonly()
                                            ->numeric()
                                            ->prefix('IDR')
                                            ->required(),

                                        Select::make('dp_percentage')
                                            ->label('Uang Muka (%)')
                                            ->options([
                                                5 => '5%',
                                                10 => '10%',
                                                15 => '15%',
                                                20 => '20%',
                                                40 => '40%',
                                                50 => '50%',
                                                60 => '60%',
                                                80 => '80%',
                                            ])
                                            ->live()
                                            ->required()
                                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                                $housePrice = $get('house_price') ?? 0;
                                                $dpAmount = ($state / 100) * $housePrice;   // Calculate Down Payment Amount
                                                $loanAmount = max($housePrice - $dpAmount, 0);  // Calculate Loan Amount

                                                $set('dp_total_amount', round($dpAmount));
                                                $set('loan_total_amount', round($loanAmount));

                                                // Calculate monthly payment
                                                $durationYears = $get('duration') ?? 0;
                                                // Interest annual rate in %
                                                $interestRate = $get('interest') ?? 0;

                                                if ($durationYears > 0 && $loanAmount > 0 && $interestRate > 0) {
                                                    $totalPayments = $durationYears * 12; // Total number of payments
                                                    $monthlyInterestRate = $interestRate / 100 / 12; // Monthly interest rate

                                                    // Amortization Formula, pow is exponentiation
                                                    $numerator = $loanAmount * $monthlyInterestRate * pow(1 + $monthlyInterestRate, $totalPayments);
                                                    $denominator = pow(1 + $monthlyInterestRate, $totalPayments) - 1;
                                                    $monthlyPayment = $denominator > 0 ? $numerator / $denominator : 0;

                                                    $set('monthly_amount', round($monthlyPayment));

                                                    // Total loan with interest
                                                    $totalLoanWithInterest = $monthlyPayment * $totalPayments;
                                                    $set('loan_interest_total_amount', round($totalLoanWithInterest));
                                                } else {
                                                    $set('monthly_payment', 0);
                                                    $set('loan_interest_total_amount', 0);
                                                }
                                            }),

                                        // Down Payment Amount (Read-only)
                                        TextInput::make('dp_total_amount')
                                            ->label('Jumlah Uang Muka')
                                            ->readonly()
                                            ->numeric()
                                            ->prefix('IDR'),

                                        TextInput::make('loan_total_amount')
                                            ->label('Jumlah Pinjaman')
                                            ->readonly()
                                            ->numeric()
                                            ->prefix('IDR')
                                            ->required(),

                                        TextInput::make('monthly_amount')
                                            ->label('Cicilan Bulanan')
                                            ->readonly()
                                            ->numeric()
                                            ->prefix('IDR')
                                            ->required(),

                                        // Total Payment Amount Field (Read-only)
                                        TextInput::make('loan_interest_total_amount')
                                            ->label('Total Pembayaran (+ Bunga)')
                                            ->readonly()
                                            ->numeric()
                                            ->prefix('IDR')
                                            ->required(),
                                    ]),


                            ]
                        ),

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
                                    'Waiting for Bank' => 'Menunggu Bank',
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
