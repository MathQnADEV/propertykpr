<?php

namespace App\Filament\Resources\Houses\Schemas;

use App\Models\Cluster;
use App\Models\Developer;
use App\Models\Type;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;

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
                        TextInput::make('certificate')
                            ->nullable()
                            ->maxLength(100)
                            ->placeholder('Contoh: SHM, SHGB, dll'),

                        FileUpload::make('thumbnail')
                            ->image()
                            ->visibility('public')
                            ->required(),

                        Repeater::make('photos')
                            ->relationship('photos')
                            ->schema([
                                FileUpload::make('photo')
                                    ->visibility('public')
                                    ->required(),
                            ]),

                        Select::make('developer_id')
                            ->label('Developer')
                            ->options(Developer::all()->pluck('name', 'id'))
                            ->searchable()
                            ->nullable()
                            ->placeholder('Pilih Developer')
                            ->live(),

                        Select::make('cluster_id')
                            ->label('Cluster')
                            ->options(fn(Get $get) => Cluster::where('developer_id', $get('developer_id'))->pluck('name', 'id'))
                            ->searchable()
                            ->nullable()
                            ->placeholder('Pilih Cluster')
                            ->live(),

                        Select::make('type_id')
                            ->label('Tipe')
                            ->options(fn(Get $get) => Type::where('cluster_id', $get('cluster_id'))->pluck('name', 'id'))
                            ->searchable()
                            ->nullable()
                            ->placeholder('Pilih Tipe'),

                        Textarea::make('facilities')
                            ->label('Fasilitas')
                            ->placeholder('Masukkan fasilitas, pisahkan dengan koma. Contoh: Kolam Renang, Gym, Parkir Luas')
                            ->rows(4)
                            ->autosize(),

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
                            ->numeric()
                            ->nullable()
                            ->prefix('Watts'),

                        TextInput::make('land_area')
                            ->numeric()
                            ->nullable()
                            ->prefix('m²'),

                        TextInput::make('building_area')
                            ->numeric()
                            ->nullable()
                            ->prefix('m²'),

                        TextInput::make('bedroom')
                            ->numeric()
                            ->nullable()
                            ->prefix('Unit'),

                        TextInput::make('bathroom')
                            ->numeric()
                            ->nullable()
                            ->prefix('Unit'),

                        Select::make('is_available')
                            ->options([
                                1 => 'Tersedia',
                                0 => 'Tidak Tersedia',
                            ])
                            ->nullable()
                            ->placeholder('Pilih Status'),

                    ])->columnSpanFull(),
            ]);
    }
}
