<?php

namespace App\Exports;

use App\Services\SalesStatsService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesStatsExport implements FromView, ShouldAutoSize, WithTitle
{
    public function __construct(private readonly ?int $agentId = null) {}

    public function title(): string
    {
        return $this->agentId ? 'Statistik Agent' : 'Statistik Penjualan';
    }

    public function view(): View
    {
        $stats     = SalesStatsService::getStats($this->agentId);
        $breakdown = $this->agentId ? [] : SalesStatsService::getAgentBreakdown();
        $agentName = SalesStatsService::getAgentName($this->agentId);

        return view('exports.sales-stats-excel', compact('stats', 'breakdown', 'agentName'));
    }
}
