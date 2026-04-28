<?php

namespace App\Http\Controllers;

use App\Exports\SalesStatsExport;
use App\Services\SalesStatsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class AdminExportController extends Controller
{
    public function excel(int $agentId = null)
    {
        $agentId   = request()->integer('agent_id', 0) ?: null;
        $agentName = SalesStatsService::getAgentName($agentId);
        $filename  = $agentName
            ? 'statistik-' . \Str::slug($agentName) . '-' . now()->format('Ymd') . '.xlsx'
            : 'statistik-penjualan-' . now()->format('Ymd') . '.xlsx';

        return Excel::download(new SalesStatsExport($agentId), $filename);
    }

    public function pdf()
    {
        $agentId   = request()->integer('agent_id', 0) ?: null;
        $stats     = SalesStatsService::getStats($agentId);
        $breakdown = $agentId ? [] : SalesStatsService::getAgentBreakdown();
        $agentName = SalesStatsService::getAgentName($agentId);

        $filename = $agentName
            ? 'statistik-' . \Str::slug($agentName) . '-' . now()->format('Ymd') . '.pdf'
            : 'statistik-penjualan-' . now()->format('Ymd') . '.pdf';

        $pdf = Pdf::loadView('exports.sales-stats-pdf', compact('stats', 'breakdown', 'agentName'))
            ->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }
}
