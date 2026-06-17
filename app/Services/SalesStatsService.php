<?php

namespace App\Services;

use App\Models\Commission;
use App\Models\House;
use App\Models\MortgageRequest;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Facades\DB;

class SalesStatsService
{
    public static function getStats(?int $agentId = null): array
    {
        if ($agentId) {
            $houseIds      = House::where('agent_id', $agentId)->pluck('id');
            $totalListings = House::where('agent_id', $agentId)->count();
            $base          = fn () => MortgageRequest::whereIn('house_id', $houseIds);
        } else {
            $totalListings = House::count();
            $base          = fn () => MortgageRequest::query();
        }

        return [
            'total_listings'      => $totalListings,
            'total_kpr'           => $base()->count(),
            'total_kpr_count'     => $base()->where('payment_type', 'kpr')->count(),
            'total_kpa_count'     => $base()->where('payment_type', 'kpa')->count(),
            'total_kpt_count'     => $base()->where('payment_type', 'kpt')->count(),
            'total_kpg_count'     => $base()->where('payment_type', 'kpg')->count(),
            'total_cash_count'    => $base()->where('payment_type', 'cash')->count(),
            'total_sewa_count'    => $base()->where('payment_type', 'sewa')->count(),
            'total_approved'      => $base()->where('status', 'Approved')->count(),
            'total_pending'       => $base()->where('status', 'Waiting for Bank')->count(),
            'total_rejected'      => $base()->where('status', 'Rejected')->count(),
            'total_loan_approved' => $base()->where('status', 'Approved')->sum('house_price'),
        ];
    }

    public static function getAgentBreakdown(): array
    {
        $agents   = User::role(['agent', 'admin', 'master'])->select('id', 'name')->orderBy('name')->get();
        $agentIds = $agents->pluck('id')->all();

        if (empty($agentIds)) return [];

        // Listings count per agent — single query
        $listings = House::selectRaw('agent_id, COUNT(*) as cnt')
            ->whereIn('agent_id', $agentIds)
            ->groupBy('agent_id')
            ->pluck('cnt', 'agent_id');

        // Mortgage aggregates per agent via JOIN — single query (replaces N×5 queries)
        $mortgages = DB::table('mortgage_requests as mr')
            ->join('houses as h', 'h.id', '=', 'mr.house_id')
            ->whereIn('h.agent_id', $agentIds)
            ->whereNull('mr.deleted_at')
            ->selectRaw('
                h.agent_id,
                COUNT(*) as total_transactions,
                SUM(mr.payment_type = "kpr")  as kpr,
                SUM(mr.payment_type = "kpa")  as kpa,
                SUM(mr.payment_type = "kpt")  as kpt,
                SUM(mr.payment_type = "kpg")  as kpg,
                SUM(mr.payment_type = "cash") as cash,
                SUM(mr.payment_type = "sewa") as sewa,
                SUM(mr.status = "Approved") as total_approved,
                SUM(mr.status = "Waiting for Bank") as total_pending,
                SUM(mr.status = "Rejected") as total_rejected,
                SUM(IF(mr.status = "Approved", mr.house_price, 0)) as total_loan_approved
            ')
            ->groupBy('h.agent_id')
            ->get()
            ->keyBy('agent_id');

        return $agents->map(fn ($agent) => [
            'id'                  => $agent->id,
            'name'                => $agent->name,
            'total_listings'      => (int) ($listings[$agent->id] ?? 0),
            'total_transactions'  => (int) ($mortgages[$agent->id]->total_transactions ?? 0),
            'kpr'                 => (int) ($mortgages[$agent->id]->kpr ?? 0),
            'kpa'                 => (int) ($mortgages[$agent->id]->kpa ?? 0),
            'kpt'                 => (int) ($mortgages[$agent->id]->kpt ?? 0),
            'kpg'                 => (int) ($mortgages[$agent->id]->kpg ?? 0),
            'cash'                => (int) ($mortgages[$agent->id]->cash ?? 0),
            'sewa'                => (int) ($mortgages[$agent->id]->sewa ?? 0),
            'total_approved'      => (int) ($mortgages[$agent->id]->total_approved ?? 0),
            'total_pending'       => (int) ($mortgages[$agent->id]->total_pending ?? 0),
            'total_rejected'      => (int) ($mortgages[$agent->id]->total_rejected ?? 0),
            'total_loan_approved' => (int) ($mortgages[$agent->id]->total_loan_approved ?? 0),
        ])->all();
    }

    /**
     * Stats filtered by city IDs (untuk investor per-wilayah).
     * Jika $cityIds kosong, kembalikan semua.
     */
    public static function getStatsByCity(Enumerable $cityIds, ?int $agentId = null): array
    {
        if ($cityIds->isEmpty()) {
            return self::getStats($agentId);
        }

        $houseQuery = House::whereIn('city_id', $cityIds);

        if ($agentId) {
            $houseQuery->where('agent_id', $agentId);
        }

        $houseIds      = $houseQuery->pluck('id');
        $totalListings = $houseQuery->count();
        $base          = fn () => MortgageRequest::whereIn('house_id', $houseIds);

        return [
            'total_listings'      => $totalListings,
            'total_kpr'           => $base()->count(),
            'total_kpr_count'     => $base()->where('payment_type', 'kpr')->count(),
            'total_kpa_count'     => $base()->where('payment_type', 'kpa')->count(),
            'total_kpt_count'     => $base()->where('payment_type', 'kpt')->count(),
            'total_kpg_count'     => $base()->where('payment_type', 'kpg')->count(),
            'total_cash_count'    => $base()->where('payment_type', 'cash')->count(),
            'total_sewa_count'    => $base()->where('payment_type', 'sewa')->count(),
            'total_approved'      => $base()->where('status', 'Approved')->count(),
            'total_pending'       => $base()->where('status', 'Waiting for Bank')->count(),
            'total_rejected'      => $base()->where('status', 'Rejected')->count(),
            'total_loan_approved' => $base()->where('status', 'Approved')->sum('house_price'),
        ];
    }

    /**
     * Breakdown per agent filtered by city IDs.
     */
    public static function getAgentBreakdownByCity(Enumerable $cityIds): array
    {
        if ($cityIds->isEmpty()) {
            return self::getAgentBreakdown();
        }

        $agents   = User::role(['agent', 'admin', 'master'])
            ->whereHas('houses', fn ($q) => $q->whereIn('city_id', $cityIds))
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $agentIds = $agents->pluck('id')->all();

        if (empty($agentIds)) return [];

        $listings = House::selectRaw('agent_id, COUNT(*) as cnt')
            ->whereIn('agent_id', $agentIds)
            ->whereIn('city_id', $cityIds)
            ->groupBy('agent_id')
            ->pluck('cnt', 'agent_id');

        $mortgages = DB::table('mortgage_requests as mr')
            ->join('houses as h', 'h.id', '=', 'mr.house_id')
            ->whereIn('h.agent_id', $agentIds)
            ->whereIn('h.city_id', $cityIds)
            ->whereNull('mr.deleted_at')
            ->selectRaw('
                h.agent_id,
                COUNT(*) as total_transactions,
                SUM(mr.payment_type = "kpr")  as kpr,
                SUM(mr.payment_type = "kpa")  as kpa,
                SUM(mr.payment_type = "kpt")  as kpt,
                SUM(mr.payment_type = "kpg")  as kpg,
                SUM(mr.payment_type = "cash") as cash,
                SUM(mr.payment_type = "sewa") as sewa,
                SUM(mr.status = "Approved") as total_approved,
                SUM(mr.status = "Waiting for Bank") as total_pending,
                SUM(mr.status = "Rejected") as total_rejected,
                SUM(IF(mr.status = "Approved", mr.house_price, 0)) as total_loan_approved
            ')
            ->groupBy('h.agent_id')
            ->get()
            ->keyBy('agent_id');

        return $agents->map(fn ($agent) => [
            'id'                  => $agent->id,
            'name'                => $agent->name,
            'total_listings'      => (int) ($listings[$agent->id] ?? 0),
            'total_transactions'  => (int) ($mortgages[$agent->id]->total_transactions ?? 0),
            'kpr'                 => (int) ($mortgages[$agent->id]->kpr ?? 0),
            'kpa'                 => (int) ($mortgages[$agent->id]->kpa ?? 0),
            'kpt'                 => (int) ($mortgages[$agent->id]->kpt ?? 0),
            'kpg'                 => (int) ($mortgages[$agent->id]->kpg ?? 0),
            'cash'                => (int) ($mortgages[$agent->id]->cash ?? 0),
            'sewa'                => (int) ($mortgages[$agent->id]->sewa ?? 0),
            'total_approved'      => (int) ($mortgages[$agent->id]->total_approved ?? 0),
            'total_pending'       => (int) ($mortgages[$agent->id]->total_pending ?? 0),
            'total_rejected'      => (int) ($mortgages[$agent->id]->total_rejected ?? 0),
            'total_loan_approved' => (int) ($mortgages[$agent->id]->total_loan_approved ?? 0),
        ])->all();
    }

    /**
     * Statistik khusus investor:
     * - total unit terjual (jumlah komisi agent yang sudah dibayar)
     * - total komisi agent
     * - pendapatan investor (bagian dari komisi agent)
     *
     * Filter opsional: agentId, cityIds
     */
    public static function getInvestorStats(
        User $investor,
        ?int $agentId = null,
        ?Enumerable $cityIds = null
    ): array {
        $query = Commission::query();

        // Filter by agent
        if ($agentId) {
            $query->where('agent_id', $agentId);
        }

        // Filter by city (lewat house agent)
        if ($cityIds && $cityIds->isNotEmpty()) {
            $query->whereHas('mortgageRequest.house', fn ($q) =>
                $q->whereIn('city_id', $cityIds)
            );
        }

        $totalUnits          = $query->count();
        $totalAgentCommission = (float) $query->sum('commission_amount');

        // Hitung pendapatan investor
        $investorIncome = 0;
        if ($investor->investor_share_type === 'percentage' && $investor->investor_share_value > 0) {
            $investorIncome = $totalAgentCommission * ($investor->investor_share_value / 100);
        } elseif ($investor->investor_share_type === 'nominal' && $investor->investor_share_value > 0) {
            // Nominal: flat per unit terjual
            $investorIncome = $totalUnits * (float) $investor->investor_share_value;
        }

        return [
            'total_units'           => $totalUnits,
            'total_agent_commission' => $totalAgentCommission,
            'investor_income'       => $investorIncome,
            'share_type'            => $investor->investor_share_type,
            'share_value'           => (float) ($investor->investor_share_value ?? 0),
        ];
    }

    public static function getAgentName(?int $agentId): ?string
    {
        if (! $agentId) return null;
        return User::find($agentId)?->name;
    }

    public static function getAgents(): Collection
    {
        return User::role(['agent', 'admin', 'master'])->select('id', 'name')->orderBy('name')->get();
    }

    public static function getAgentsByCity(Enumerable $cityIds): Collection
    {
        return User::role(['agent', 'admin', 'master'])
            ->whereHas('houses', fn ($q) => $q->whereIn('city_id', $cityIds))
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
    }
}
