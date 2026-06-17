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
                User::role(['agent', 'admin', 'master'])
                    ->withCount('houses as total_listings')
                    // Per tipe pembayaran
                    ->withCount(['mortgageRequests as total_kpr'  => fn (Builder $q) => $q->where('payment_type', 'kpr')])
                    ->withCount(['mortgageRequests as total_kpa'  => fn (Builder $q) => $q->where('payment_type', 'kpa')])
                    ->withCount(['mortgageRequests as total_kpt'  => fn (Builder $q) => $q->where('payment_type', 'kpt')])
                    ->withCount(['mortgageRequests as total_kpg'  => fn (Builder $q) => $q->where('payment_type', 'kpg')])
                    ->withCount(['mortgageRequests as total_cash' => fn (Builder $q) => $q->where('payment_type', 'cash')])
                    ->withCount(['mortgageRequests as total_sewa' => fn (Builder $q) => $q->where('payment_type', 'sewa')])
                    // Status approval
                    ->withCount(['mortgageRequests as total_approved' => fn (Builder $q) => $q->where('status', 'Approved')])
                    ->withCount(['mortgageRequests as total_pending'  => fn (Builder $q) => $q->where('status', 'Waiting for Bank')])
                    ->withCount(['mortgageRequests as total_rejected' => fn (Builder $q) => $q->where('status', 'Rejected')])
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

                // ── Tipe Pembayaran ──────────────────────
                Tables\Columns\TextColumn::make('total_kpr')
                    ->label('KPR')
                    ->alignCenter()
                    ->tooltip('Kredit Pemilikan Rumah'),

                Tables\Columns\TextColumn::make('total_kpa')
                    ->label('KPA')
                    ->alignCenter()
                    ->tooltip('Kredit Pemilikan Apartemen'),

                Tables\Columns\TextColumn::make('total_kpt')
                    ->label('KPT')
                    ->alignCenter()
                    ->tooltip('Kredit Pemilikan Tanah'),

                Tables\Columns\TextColumn::make('total_kpg')
                    ->label('KPG')
                    ->alignCenter()
                    ->tooltip('Kredit Pemilikan Gudang'),

                Tables\Columns\TextColumn::make('total_cash')
                    ->label('Cash')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('total_sewa')
                    ->label('Sewa')
                    ->alignCenter(),

                // ── Status ──────────────────────────────
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
