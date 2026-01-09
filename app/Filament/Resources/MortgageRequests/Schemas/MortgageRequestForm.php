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
use App\Models\User;
use Filament\Forms\Components\FileUpload;

class MortgageRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([

                    Step::make('Product and Price')
                        ->schema(
                            [
                                Grid::make(3)
                                    ->schema([
                                        Select::make('house_id')
                                            ->label('House')
                                            ->options(House::query()->pluck('name', 'id'))
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
                                            ->label('Annual Interest in %')
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
                                            ->label('Bank Name')
                                            ->readonly()
                                            ->required(),

                                        TextInput::make('duration')
                                            ->label('Duration in Years')
                                            ->readonly()
                                            ->numeric()
                                            ->suffix('Years')
                                            ->required(),

                                        TextInput::make('interest')
                                            ->label('Interest Rate')
                                            ->readonly()
                                            ->numeric()
                                            ->suffix('%')
                                            ->required(),

                                        TextInput::make('house_price')
                                            ->label('House Price')
                                            ->readonly()
                                            ->numeric()
                                            ->prefix('IDR')
                                            ->required(),

                                        Select::make('dp_percentage')
                                            ->label('Down Payment (%)')
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
                                            ->label('Down Payment Total Amount')
                                            ->readonly()
                                            ->numeric()
                                            ->prefix('IDR'),

                                        TextInput::make('loan_total_amount')
                                            ->label('Loan Total Amount')
                                            ->readonly()
                                            ->numeric()
                                            ->prefix('IDR')
                                            ->required(),

                                        TextInput::make('monthly_amount')
                                            ->label('Monthly Payment Amount')
                                            ->readonly()
                                            ->numeric()
                                            ->prefix('IDR')
                                            ->required(),

                                        // Total Payment Amount Field (Read-only)
                                        TextInput::make('loan_interest_total_amount')
                                            ->label('Total Payment Amount (with Interest)')
                                            ->readonly()
                                            ->numeric()
                                            ->prefix('IDR')
                                            ->required(),
                                    ]),


                            ]
                        ),

                    Step::make('Customer Information')
                        ->schema([
                            Select::make('user_id')
                                ->relationship('customer', 'email')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->live()
                                // Data baru
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $user = User::find($state);

                                    $name = $user->name;
                                    $email = $user->email;

                                    $set('name', $name);
                                    $set('email', $email);
                                })
                                // Data baru setelah dihydrate
                                ->afterStateHydrated(function (callable $set, $state) {
                                    $userId = $state;
                                    if ($userId) {
                                        $user = User::find($userId);
                                        $name = $user->name;
                                        $email = $user->email;
                                        $set('name', $name);
                                        $set('email', $email);
                                    }
                                }),

                            TextInput::make('name')
                                ->readonly()
                                ->required()
                                ->maxLength(255),

                            TextInput::make('email')
                                ->readonly()
                                ->required()
                                ->maxLength(255),
                        ]),

                    Step::make('Bank Approval')
                        ->schema([
                            FileUpload::make('documents')
                                ->acceptedFileTypes(['application/pdf'])
                                ->visibility('public')
                                ->required(),

                            Select::make('status')
                                ->label('Approval Status')
                                ->options([
                                    'Waiting for Bank' => 'Waiting for Bank',
                                    'Approved' => 'Approved',
                                    'Rejected' => 'Rejected',
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
