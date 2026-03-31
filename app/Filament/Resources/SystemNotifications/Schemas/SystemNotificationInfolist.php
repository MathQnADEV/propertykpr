<?php

namespace App\Filament\Resources\SystemNotifications\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SystemNotificationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name'),
                TextEntry::make('type'),
                TextEntry::make('title'),
                TextEntry::make('url')
                ->label('Link')
                ->formatStateUsing(fn($state) => $state ? 'Lihat Detail →' : '-')
                ->url(fn($record) => $record->url)
                ->openUrlInNewTab()
                ->color(fn($record) => $record->url ? 'primary' : 'gray')
                ->default('-'),
                IconEntry::make('is_read')
                    ->boolean(),
                TextEntry::make('deleted_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
