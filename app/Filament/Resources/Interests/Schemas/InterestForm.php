<?php

namespace App\Filament\Resources\Interests\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InterestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('house_id')
                    ->relationship('house', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('bank_id')
                    ->relationship('bank', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('interest')
                    ->required()
                    ->numeric()
                    ->prefix('%'),

                TextInput::make('duration')
                    ->required()
                    ->numeric()
                    ->prefix('Years'),
            ]);
    }
}
