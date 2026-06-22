<?php

namespace App\Filament\Resources\Types\Schemas;

use App\Models\Cluster;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class TypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Tipe')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('cluster_id')
                            ->label('Cluster')
                            ->options(Cluster::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                    ])->columnSpanFull(),
            ]);
    }
}
