<?php

namespace App\Filament\Resources\MortgageRequests\RelationManagers;

use App\Filament\Resources\MortgageRequests\MortgageRequestResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class InstallmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'installments';

    // protected static ?string $relatedResource = MortgageRequestResource::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Wizard::make([
                    Step::make('Angsuran')
                        ->schema([
                            TextInput::make('no_of_payment')
                                ->label('No. Angsuran')
                                ->helperText('Pembayaran cicilan ke berapa')
                                ->numeric()
                                ->required(),

                            Select::make('sub_total_amount')
                                ->label('Cicilan Bulanan')
                                ->options(
                                    function () {
                                        $mortgageRequest = $this->getOwnerRecord();
                                        return $mortgageRequest ? [$mortgageRequest->monthly_amount => $mortgageRequest->monthly_amount] : [];
                                    }
                                )
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                    $tax = $state * 0.11;
                                    $sub_total_amount = $state;
                                    $insurance = 900000; // Fixed insurance amount from bank or property manager
                                    $grandTotal = $state + $tax + $insurance;

                                    $set('total_tax_amount', round($tax));
                                    $set('insurance_amount', $insurance);
                                    $set('grand_total_amount', round($grandTotal));

                                    $mortgageRequest = $this->getOwnerRecord();
                                    if ($mortgageRequest) {
                                        $lastInstalment = $mortgageRequest->installments()
                                            ->where('is_paid', true)
                                            ->orderBy('no_of_payment', 'desc')
                                            ->first();

                                        $previousRemainingLoan = $lastInstalment
                                            ? $lastInstalment->remaining_loan_amount
                                            : $mortgageRequest->loan_interest_total_amount;

                                        $remainingLoanAfterPayment = max($previousRemainingLoan - round($sub_total_amount), 0);

                                        // set the calculated remaining loan amount
                                        $set('remaining_loan_amount', $remainingLoanAfterPayment);
                                        $set('remaining_loan_amount_before_payment', $previousRemainingLoan);
                                    }
                                }),

                            TextInput::make('total_tax_amount')
                                ->label('Pajak 11%')
                                ->readOnly()
                                ->required()
                                ->numeric()
                                ->prefix('IDR'),

                            TextInput::make('insurance_amount')
                                ->label('Asuransi Tambahan')
                                ->readOnly()
                                ->default(900000)
                                ->numeric()
                                ->prefix('IDR'),

                            TextInput::make('grand_total_amount')
                                ->label('Total Pembayaran')
                                ->readOnly()
                                ->required()
                                ->numeric()
                                ->prefix('IDR'),

                            TextInput::make('remaining_loan_amount_before_payment')
                                ->label('Sisa Pinjaman Sebelum Pembayaran')
                                ->readOnly()
                                ->numeric()
                                ->prefix('IDR'),

                            TextInput::make('remaining_loan_amount')
                                ->label('Sisa Pinjaman Setelah Pembayaran')
                                ->readOnly()
                                ->numeric()
                                ->prefix('IDR'),
                        ]),

                    Step::make('Metode Pembayaran')
                        ->schema([
                            ToggleButtons::make('is_paid')
                                ->label('Status Pembayaran')
                                ->boolean()
                                ->grouped()
                                ->icons([
                                    true => 'heroicon-o-check-circle',
                                    false => 'heroicon-o-x-circle',
                                ])
                                ->required(),

                            Select::make('payment_type')
                                ->label('Tipe Pembayaran')
                                ->options([
                                    'Midtrans' => 'Midtrans',
                                    'Manual' => 'Manual',
                                ])
                                ->required(),

                            FileUpload::make('proof')
                                ->label('Bukti Pembayaran')
                                ->visibility('public')
                                ->image(),
                        ]),
                ])
                    ->columnSpanFull()
                    ->columns(1)
                    ->skippable(),


            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('no_of_payment')
            ->columns([
                TextColumn::make('no_of_payment'),
                TextColumn::make('sub_total_amount'),
                TextColumn::make('insurance_amount'),
                TextColumn::make('total_tax_amount'),
                IconColumn::make('is_paid')
                        ->boolean()
                        ->trueColor('success')
                        ->falseColor('danger')
                        ->trueIcon('heroicon-o-check-circle')
                        ->falseIcon('heroicon-o-x-circle')
                        ->label('Terverifikasi'),
            ])
            ->filters([
                // ...
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
