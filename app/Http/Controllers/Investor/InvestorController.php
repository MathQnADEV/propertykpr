<?php

namespace App\Http\Controllers\Investor;

use App\Exports\SalesStatsExport;
use App\Http\Controllers\Controller;
use App\Services\SalesStatsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InvestorController extends Controller
{
    public function dashboard(Request $request)
    {
        $agentId   = $request->integer('agent_id', 0) ?: null;
        $stats     = SalesStatsService::getStats($agentId);
        $breakdown = SalesStatsService::getAgentBreakdown();
        $agents    = SalesStatsService::getAgents();
        $agentName = SalesStatsService::getAgentName($agentId);

        return view('investor.dashboard.index', compact(
            'stats', 'breakdown', 'agents', 'agentId', 'agentName'
        ));
    }

    public function exportExcel(Request $request)
    {
        $agentId   = $request->integer('agent_id', 0) ?: null;
        $agentName = SalesStatsService::getAgentName($agentId);
        $filename  = $agentName
            ? 'statistik-' . \Str::slug($agentName) . '-' . now()->format('Ymd') . '.xlsx'
            : 'statistik-penjualan-' . now()->format('Ymd') . '.xlsx';

        return Excel::download(new SalesStatsExport($agentId), $filename);
    }

    public function exportPdf(Request $request)
    {
        $agentId   = $request->integer('agent_id', 0) ?: null;
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
