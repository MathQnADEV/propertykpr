<?php

namespace App\Filament\Resources\CommissionRequests;

use App\Filament\Resources\CommissionRequests\Pages\ListCommissionRequests;
use App\Filament\Resources\CommissionRequests\Tables\CommissionRequestsTable;
use App\Models\CommissionRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CommissionRequestResource extends Resource
{
    protected static ?string $model = CommissionRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;

    protected static ?string $navigationLabel  = 'Request Komisi';
    protected static ?string $modelLabel       = 'Request Komisi';
    protected static ?string $pluralModelLabel = 'Request Komisi';

    protected static string|UnitEnum|null $navigationGroup = 'Transaksi';
    protected static ?int $navigationSort = 4;

    public static function table(Table $table): Table
    {
        return CommissionRequestsTable::configure($table);
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'pending')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCommissionRequests::route('/'),
        ];
    }
}
