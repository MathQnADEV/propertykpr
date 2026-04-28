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
        return User::role('agent')
            ->select('id', 'name')
            ->orderBy('name')
            ->get()
            ->map(fn ($agent) => array_merge(
                ['id' => $agent->id, 'name' => $agent->name],
                self::getStats($agent->id)
            ))
            ->all();
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
