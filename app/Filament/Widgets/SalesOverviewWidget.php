<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Services\SalesStatsService;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    public ?string $filter = null;

    protected function getFilters(): ?array
    {
        $filters = ['all' => 'Semua Agent'];

        User::role('agent')->orderBy('name')->pluck('name', 'id')
            ->each(fn ($name, $id) => $filters[(string) $id] = $name);

        return $filters;
    }

    protected function getStats(): array
    {
        $agentId = ($this->filter && $this->filter !== 'all') ? (int) $this->filter : null;
        $s = SalesStatsService::getStats($agentId);

        return [
            Stat::make('Total Properti', number_format($s['total_listings']))
                ->icon('heroicon-o-home')
                ->color('info'),

            Stat::make('Total Pembayaran Masuk', number_format($s['total_kpr']))
                ->icon('heroicon-o-document-text')
                ->color('gray'),

            Stat::make('KPR (Kredit)', number_format($s['total_kpr_count']))
                ->description('Pengajuan via bank')
                ->icon('heroicon-o-building-library')
                ->color('info'),

            Stat::make('Cash (Tunai)', number_format($s['total_cash_count']))
                ->description('Pembayaran langsung')
                ->icon('heroicon-o-banknotes')
                ->color('warning'),

            Stat::make('Pembayaran Disetujui', number_format($s['total_approved']))
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Pembayaran Pending', number_format($s['total_pending']))
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Pembayaran Ditolak', number_format($s['total_rejected']))
                ->icon('heroicon-o-x-circle')
                ->color('danger'),

            Stat::make('Total Pembayaran', 'Rp ' . number_format($s['total_loan_approved'], 0, ',', '.'))
                ->description('Dari pembayaran yang disetujui')
                ->icon('heroicon-o-banknotes')
                ->color('primary'),
        ];
    }
}
