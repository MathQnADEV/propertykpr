<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class SalesBreakdownWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Rekap Per Agent')
            ->query(
                User::role('agent')
                    ->withCount('houses as total_listings')
                    ->withCount(['mortgageRequests as total_kpr'])
                    ->withCount(['mortgageRequests as total_approved' => fn (Builder $q) => $q->where('status', 'Approved')])
                    ->withCount(['mortgageRequests as total_pending'  => fn (Builder $q) => $q->where('status', 'Waiting for Bank')])
                    ->withCount(['mortgageRequests as total_rejected' => fn (Builder $q) => $q->where('status', 'Rejected')])
                    ->withSum(
                        ['mortgageRequests as total_loan_approved' => fn (Builder $q) => $q->where('status', 'Approved')],
                        'loan_total_amount'
                    )
                    ->orderBy('name')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Agent')
                    ->weight(FontWeight::SemiBold)
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_listings')
                    ->label('Properti')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('total_kpr')
                    ->label('Total KPR')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('total_approved')
                    ->label('Disetujui')
                    ->alignCenter()
                    ->color('success'),

                Tables\Columns\TextColumn::make('total_pending')
                    ->label('Menunggu')
                    ->alignCenter()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('total_rejected')
                    ->label('Ditolak')
                    ->alignCenter()
                    ->color('danger'),

                Tables\Columns\TextColumn::make('total_loan_approved')
                    ->label('Total Pinjaman (Disetujui)')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state ?? 0, 0, ',', '.'))
                    ->alignRight()
                    ->color('primary'),
            ])
            ->headerActions([
                 Action::make('exportExcel')
                    ->label('Excel')
                    ->icon('heroicon-o-table-cells')
                    ->color('success')
                    ->url(fn () => route('admin.export.stats.excel'))
                    ->openUrlInNewTab(),

                Action::make('exportPdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->url(fn () => route('admin.export.stats.pdf'))
                    ->openUrlInNewTab(),
            ])
            ->paginated(false);
    }
}
