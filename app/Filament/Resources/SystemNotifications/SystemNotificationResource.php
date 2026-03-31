<?php

namespace App\Filament\Resources\SystemNotifications;

use App\Filament\Concerns\ViewOnly;
use App\Filament\Resources\SystemNotifications\Pages\CreateSystemNotification;
use App\Filament\Resources\SystemNotifications\Pages\EditSystemNotification;
use App\Filament\Resources\SystemNotifications\Pages\ListSystemNotifications;
use App\Filament\Resources\SystemNotifications\Pages\ViewSystemNotification;
use App\Filament\Resources\SystemNotifications\Schemas\SystemNotificationForm;
use App\Filament\Resources\SystemNotifications\Schemas\SystemNotificationInfolist;
use App\Filament\Resources\SystemNotifications\Tables\SystemNotificationsTable;
use App\Models\SystemNotification;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class SystemNotificationResource extends Resource
{
    use ViewOnly;
    protected static ?string $model = SystemNotification::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBell;

    protected static string | UnitEnum | null $navigationGroup = 'System';
    protected static ?string $recordTitleAttribute = 'System Notification';

    protected static ?int $navigationSort = 90;

    protected static ?string $label = 'Notifikasi';
    protected static ?string $pluralLabel = 'Notifikasi';

    public static function form(Schema $schema): Schema
    {
        return SystemNotificationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SystemNotificationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SystemNotificationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSystemNotifications::route('/'),
            'create' => CreateSystemNotification::route('/create'),
            'view' => ViewSystemNotification::route('/{record}'),
            'edit' => EditSystemNotification::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
