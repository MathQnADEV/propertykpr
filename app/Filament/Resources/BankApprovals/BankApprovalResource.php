<?php

namespace App\Filament\Resources\BankApprovals;

use App\Filament\Resources\BankApprovals\Pages\ListBankApprovals;
use App\Filament\Resources\BankApprovals\Pages\ViewBankApproval;
use App\Filament\Resources\BankApprovals\Schemas\BankApprovalInfolist;
use App\Filament\Resources\BankApprovals\Tables\BankApprovalsTable;
use App\Models\BankApproval;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class BankApprovalResource extends Resource
{
    protected static ?string $model = BankApproval::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static ?string $navigationLabel = 'Persetujuan Bank';
    protected static ?string $modelLabel = 'Persetujuan Bank';
    protected static ?string $pluralModelLabel = 'Persetujuan Bank';
    protected static ?string $recordTitleAttribute = 'Persetujuan Bank';

    protected static string | UnitEnum | null $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 2;

    public static function infolist(Schema $schema): Schema
    {
        return BankApprovalInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BankApprovalsTable::configure($table);
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
            'index' => ListBankApprovals::route('/'),
            'view'  => ViewBankApproval::route('/{record}'),
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
