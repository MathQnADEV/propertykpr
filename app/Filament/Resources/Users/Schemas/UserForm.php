<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\City;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('phone')
                    ->maxLength(255)
                    ->required(),

                TextInput::make('email')
                    ->maxLength(255)
                    ->email()
                    ->required(),

                TextInput::make('password')
                    ->helperText('Minimal 9 karakter')
                    ->password()
                    ->required()
                    ->minLength(9)
                    ->maxLength(255),

                Select::make('role')
                    ->label('Peran')
                    ->relationship('roles', 'name')
                    ->required(),

                FileUpload::make('photo')
                    ->required()
                    ->visibility('public')
                    ->image(),

                TextInput::make('instagram')
                    ->label('Instagram (username)')
                    ->nullable()
                    ->maxLength(100),

                TextInput::make('facebook')
                    ->label('Facebook (username/link)')
                    ->nullable()
                    ->maxLength(100),

                TextInput::make('whatsapp')
                    ->label('WhatsApp (nomor, tanpa +)')
                    ->nullable()
                    ->maxLength(20),

                Select::make('investedCities')
                    ->label('Area Investasi (khusus investor)')
                    ->relationship('investedCities', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->helperText('Isi hanya untuk akun dengan role Investor'),

                // ── Bagian Komisi Investor ──────────────────────────────────
                Select::make('investor_share_type')
                    ->label('Tipe Bagi Hasil Investor')
                    ->options([
                        'percentage' => 'Persentase (%)',
                        'nominal'    => 'Nominal (Rp)',
                    ])
                    ->nullable()
                    ->live()
                    ->helperText('Berapa bagian komisi agent yang masuk ke investor ini'),

                TextInput::make('investor_share_value')
                    ->label(fn (callable $get) => $get('investor_share_type') === 'nominal'
                        ? 'Nominal Bagi Hasil (Rp)'
                        : 'Persentase Bagi Hasil (%)')
                    ->numeric()
                    ->nullable()
                    ->minValue(0)
                    ->suffix(fn (callable $get) => $get('investor_share_type') === 'nominal' ? null : '%')
                    ->prefix(fn (callable $get) => $get('investor_share_type') === 'nominal' ? 'Rp' : null)
                    ->placeholder(fn (callable $get) => $get('investor_share_type') === 'nominal'
                        ? 'contoh: 500000'
                        : 'contoh: 5')
                    ->helperText('Diisi oleh master — ditampilkan di dashboard investor'),
            ]);
    }
}
