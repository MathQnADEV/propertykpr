<?php

namespace App\Filament\Resources\Clusters\Schemas;

use App\Models\Developer;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class ClusterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Cluster')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('developer_id')
                            ->label('Developer')
                            ->options(Developer::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                    ])->columnSpanFull(),
            ]);
    }
}
