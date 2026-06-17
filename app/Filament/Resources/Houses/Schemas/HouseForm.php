<?php

namespace App\Filament\Resources\Houses\Schemas;

use App\Models\Facility;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class HouseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Detail')
                    ->components([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('IDR'),
                        Select::make('certificate')
                            ->options([
                                'SHM' => 'SHM',
                                'SHGB' => 'SHGB',
                                'Patches' => 'Patches',
                            ])
                            ->required(),

                        FileUpload::make('thumbnail')
                            ->image()
                            ->visibility('public')
                            ->required(),

                        // repeater
                        Repeater::make('photos')
                            ->relationship('photos')
                            ->schema([
                                FileUpload::make('photo')
                                    ->visibility('public')
                                    ->required(),
                            ]),

                        Repeater::make('facilities')
                            ->relationship('facilities')
                            ->schema([
                                Select::make('facility_id')
                                    ->options(Facility::all()->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ]),

                    ])->columnSpanFull(),

                Fieldset::make('Informasi Tambahan')
                    ->components([
                        Select::make('agent_id')
                            ->label('Pemilik / Agent')
                            ->helperText('Default: diri sendiri. Bisa diubah ke agent lain bila listing ditugaskan.')
                            ->options(fn() => User::role(['agent', 'admin', 'master'])->orderBy('name')->pluck('name', 'id'))
                            ->default(fn() => auth()->id())
                            ->searchable()
                            ->nullable()
                            ->placeholder('Belum ditugaskan'),

                        Textarea::make('about')
                            ->required(),

                        Select::make('city_id')
                            ->relationship('city', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('electric')
                            ->required()
                            ->numeric()
                            ->prefix('Watts'),

                        TextInput::make('land_area')
                            ->required()
                            ->numeric()
                            ->prefix('m²'),

                        TextInput::make('building_area')
                            ->required()
                            ->numeric()
                            ->prefix('m²'),

                        TextInput::make('bedroom')
                            ->required()
                            ->numeric()
                            ->prefix('Unit'),

                        TextInput::make('bathroom')
                            ->required()
                            ->numeric()
                            ->prefix('Unit'),
                        Select::make('is_available')
                            ->options([
                                1 => 'Tersedia',
                                0 => 'Tidak Tersedia',
                            ])
                            ->required(),

                    ])->columnSpanFull(),
            ]);
    }
}
