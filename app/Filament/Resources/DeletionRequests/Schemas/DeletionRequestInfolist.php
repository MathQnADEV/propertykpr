<?php

namespace App\Filament\Resources\DeletionRequests\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DeletionRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('requester.name'),
                TextEntry::make('model_type'),
                TextEntry::make('model_id')
                    ->numeric(),
                TextEntry::make('model_name'),
                TextEntry::make('status'),
                TextEntry::make('reviewer.name'),
                TextEntry::make('reviewed_at')
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
