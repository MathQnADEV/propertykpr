<?php

namespace App\Filament\Resources\DeletionRequests;

use App\Filament\Concerns\ViewOnly;
use App\Filament\Resources\DeletionRequests\Pages\CreateDeletionRequest;
use App\Filament\Resources\DeletionRequests\Pages\EditDeletionRequest;
use App\Filament\Resources\DeletionRequests\Pages\ListDeletionRequests;
use App\Filament\Resources\DeletionRequests\Pages\ViewDeletionRequest;
use App\Filament\Resources\DeletionRequests\Schemas\DeletionRequestForm;
use App\Filament\Resources\DeletionRequests\Schemas\DeletionRequestInfolist;
use App\Filament\Resources\DeletionRequests\Tables\DeletionRequestsTable;
use App\Models\DeletionRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class DeletionRequestResource extends Resource
{
    use ViewOnly;
    protected static ?string $model = DeletionRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrash;

    protected static ?string $navigationLabel = 'Permintaan Hapus';
    protected static ?string $modelLabel = 'Permintaan Hapus';
    protected static ?string $pluralModelLabel = 'Permintaan Hapus';
    protected static string | UnitEnum | null $navigationGroup = 'Sistem';

    protected static ?int $navigationSort = 100;

    protected static ?string $recordTitleAttribute = 'Permintaan Hapus';

    public static function form(Schema $schema): Schema
    {
        return DeletionRequestForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DeletionRequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeletionRequestsTable::configure($table);
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
            'index' => ListDeletionRequests::route('/'),
            'create' => CreateDeletionRequest::route('/create'),
            'view' => ViewDeletionRequest::route('/{record}'),
            'edit' => EditDeletionRequest::route('/{record}/edit'),
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
