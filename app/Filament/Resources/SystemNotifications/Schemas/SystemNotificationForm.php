<?php

namespace App\Filament\Resources\SystemNotifications\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;

class SystemNotificationForm
{
    public static function configure(Schema $schema): Schema
    {
        $user = auth()->user();
        $isMaster = $user->hasRole('master');
        $isAdmin = $user->hasRole('admin');

        $targetOptions = [];
        if ($isMaster) {
            $targetOptions['Semua Agent'] = 'all_agent';
            $targetOptions['Semua Admin'] = 'all_admin';
            $targetOptions['Semua Investor'] = 'all_investor';
            $targetOptions['Agent Tertentu'] = 'specific_agent';
            $targetOptions['Admin Tertentu'] = 'specific_admin';
            $targetOptions['Investor Tertentu'] = 'specific_investor';
        } elseif ($isAdmin) {
            $targetOptions['Semua Agent'] = 'all_agent';
            $targetOptions['Agent Tertentu'] = 'specific_agent';
        }

        return $schema
            ->components([
                Fieldset::make('Kirim Notifikasi')
                    ->schema([
                        Select::make('target_type')
                            ->label('Tujuan')
                            ->options($targetOptions)
                            ->required()
                            ->live(),

                        Select::make('specific_users')
                            ->label('Pilih Penerima')
                            ->options(User::role('agent')->orderBy('name')->pluck('name', 'id')->toArray())
                            ->multiple()
                            ->searchable(),

                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->label('Pesan')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('url')
                            ->label('Link (opsional)')
                            ->url(),
                    ])->columnSpanFull(),
            ]);
    }
}
