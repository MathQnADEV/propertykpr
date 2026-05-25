<?php

namespace App\Services;

use App\Models\House;
use App\Models\MortgageRequest;
use App\Models\User;
use Illuminate\Support\Collection;

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
            'total_approved'      => $base()->where('status', 'Approved')->count(),
            'total_pending'       => $base()->where('status', 'Waiting for Bank')->count(),
            'total_rejected'      => $base()->where('status', 'Rejected')->count(),
            'total_loan_approved' => $base()->where('status', 'Approved')->sum('loan_total_amount'),
        ];
    }

    public static function getAgentBreakdown(): array
    {
        $agents   = User::role('agent')->select('id', 'name')->orderBy('name')->get();
        $agentIds = $agents->pluck('id')->all();

        if (empty($agentIds)) return [];

        // Listings count per agent — single query
        $listings = House::selectRaw('agent_id, COUNT(*) as cnt')
            ->whereIn('agent_id', $agentIds)
            ->groupBy('agent_id')
            ->pluck('cnt', 'agent_id');

        // Mortgage aggregates per agent via JOIN — single query (replaces N×5 queries)
        $mortgages = \DB::table('mortgage_requests as mr')
            ->join('houses as h', 'h.id', '=', 'mr.house_id')
            ->whereIn('h.agent_id', $agentIds)
            ->whereNull('mr.deleted_at')
            ->selectRaw('
                h.agent_id,
                COUNT(*) as total_kpr,
                SUM(mr.status = "Approved") as total_approved,
                SUM(mr.status = "Waiting for Bank") as total_pending,
                SUM(mr.status = "Rejected") as total_rejected,
                SUM(IF(mr.status = "Approved", mr.loan_total_amount, 0)) as total_loan_approved
            ')
            ->groupBy('h.agent_id')
            ->get()
            ->keyBy('agent_id');

        return $agents->map(fn ($agent) => [
            'id'                  => $agent->id,
            'name'                => $agent->name,
            'total_listings'      => (int) ($listings[$agent->id] ?? 0),
            'total_kpr'           => (int) ($mortgages[$agent->id]->total_kpr ?? 0),
            'total_approved'      => (int) ($mortgages[$agent->id]->total_approved ?? 0),
            'total_pending'       => (int) ($mortgages[$agent->id]->total_pending ?? 0),
            'total_rejected'      => (int) ($mortgages[$agent->id]->total_rejected ?? 0),
            'total_loan_approved' => (int) ($mortgages[$agent->id]->total_loan_approved ?? 0),
        ])->all();
    }

    public static function getAgentName(?int $agentId): ?string
    {
        if (! $agentId) return null;
        return User::find($agentId)?->name;
    }

    public static function getAgents(): Collection
    {
        return User::role('agent')->select('id', 'name')->orderBy('name')->get();
    }
}
