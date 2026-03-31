<?php

namespace App\Filament\Resources\Interests;

use App\Filament\Resources\Interests\Pages\CreateInterest;
use App\Filament\Resources\Interests\Pages\EditInterest;
use App\Filament\Resources\Interests\Pages\ListInterests;
use App\Filament\Resources\Interests\Schemas\InterestForm;
use App\Filament\Resources\Interests\Tables\InterestsTable;
use App\Models\Interest;
use App\Services\NotificationService;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class InterestResource extends Resource
{
    protected static ?string $model = Interest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;
    protected static string | UnitEnum | null $navigationGroup = 'Vendors';

    protected static ?string $recordTitleAttribute = 'Interest';

    public static function form(Schema $schema): Schema
    {
        return InterestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InterestsTable::configure($table);
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
            'index' => ListInterests::route('/'),
            'create' => CreateInterest::route('/create'),
            'edit' => EditInterest::route('/{record}/edit'),
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
