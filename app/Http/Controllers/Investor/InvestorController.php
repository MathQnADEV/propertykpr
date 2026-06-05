<?php

namespace App\Http\Controllers\Investor;

use App\Exports\SalesStatsExport;
use App\Http\Controllers\Controller;
use App\Services\SalesStatsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class InvestorController extends Controller
{
    public function dashboard(Request $request)
    {
        $investor       = Auth::user();
        $investedCities = $investor->investedCities()->get();
        $cityIds        = $investedCities->pluck('id');
        $agentId        = $request->integer('agent_id', 0) ?: null;
        $agentName      = SalesStatsService::getAgentName($agentId);

        // Stats untuk investor (unit terjual, komisi agent, pendapatan investor)
        $investorStats = SalesStatsService::getInvestorStats(
            $investor,
            $agentId,
            $cityIds->isNotEmpty() ? $cityIds : null
        );

        // Breakdown per agent (untuk tabel rekap)
        if ($cityIds->isNotEmpty()) {
            $breakdown = SalesStatsService::getAgentBreakdownByCity($cityIds);
            $agents    = SalesStatsService::getAgentsByCity($cityIds);
        } else {
            $breakdown = SalesStatsService::getAgentBreakdown();
            $agents    = SalesStatsService::getAgents();
        }

        // Tetap kirim $stats untuk kompatibilitas tabel breakdown yang sudah ada
        $stats = SalesStatsService::getStats($agentId);

        // Paginate breakdown (10 agent per halaman), full array tetap untuk total footer
        $perPage            = 10;
        $currentPage        = $request->integer('page', 1);
        $breakdownPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            array_slice($breakdown, ($currentPage - 1) * $perPage, $perPage),
            count($breakdown),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('investor.dashboard.index', compact(
            'stats', 'investorStats', 'breakdown', 'breakdownPaginated', 'agents',
            'agentId', 'agentName', 'cityIds', 'investedCities'
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
        $investor  = Auth::user();
        $cityIds   = $investor->investedCities()->pluck('cities.id');
        $agentId   = $request->integer('agent_id', 0) ?: null;
        $agentName = SalesStatsService::getAgentName($agentId);

        if ($cityIds->isNotEmpty()) {
            $stats     = SalesStatsService::getStatsByCity($cityIds, $agentId);
            $breakdown = $agentId ? [] : SalesStatsService::getAgentBreakdownByCity($cityIds);
        } else {
            $stats     = SalesStatsService::getStats($agentId);
            $breakdown = $agentId ? [] : SalesStatsService::getAgentBreakdown();
        }

        $filename = $agentName
            ? 'statistik-' . \Str::slug($agentName) . '-' . now()->format('Ymd') . '.pdf'
            : 'statistik-penjualan-' . now()->format('Ymd') . '.pdf';

        $pdf = Pdf::loadView('exports.sales-stats-pdf', compact('stats', 'breakdown', 'agentName'))
            ->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }
}
