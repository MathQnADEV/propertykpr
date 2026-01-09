<?php

namespace App\Filament\Resources\Cities\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class CityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('Details')
                    ->components([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        FileUpload::make('photo')
                            ->required()
                            ->visibility('public')
                            ->image(),
                    ])->columnSpanFull(),
            ]);
    }
}
