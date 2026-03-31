<?php

namespace App\Filament\Resources\DeletionRequests\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DeletionRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('requester_id')
                    ->relationship('requester', 'name')
                    ->required(),
                TextInput::make('model_type')
                    ->required(),
                TextInput::make('model_id')
                    ->required()
                    ->numeric(),
                TextInput::make('model_name'),
                Textarea::make('reason')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->default('pending'),
                Select::make('reviewer_id')
                    ->relationship('reviewer', 'name'),
                Textarea::make('review_note')
                    ->columnSpanFull(),
                DateTimePicker::make('reviewed_at'),
            ]);
    }
}
