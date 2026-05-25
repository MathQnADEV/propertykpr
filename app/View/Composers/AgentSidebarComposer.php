<?php

namespace App\View\Composers;

use App\Models\CommissionRequest;
use App\Models\House;
use App\Models\MortgageRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AgentSidebarComposer
{
    /**
     * Bind sidebar badge data to the agent layout view.
     * Uses optimised subquery for deals to avoid a separate pluck() query.
     */
    public function compose(View $view): void
    {
        if (! Auth::check()) {
            $view->with('sidebarPendingDeals', 0);
            $view->with('sidebarPendingCommReqs', 0);
            return;
        }

        $agentId = Auth::id();

        // Pending KPR deals — single query with a subquery instead of pluck → whereIn
        $pendingDeals = MortgageRequest::whereIn(
            'house_id',
            House::withTrashed()->where('agent_id', $agentId)->select('id')
        )->where('status', 'Waiting for Bank')->count();

        // Pending commission requests
        $pendingCommReqs = CommissionRequest::where('agent_id', $agentId)
            ->where('status', 'pending')
            ->count();

        $view->with('sidebarPendingDeals', $pendingDeals);
        $view->with('sidebarPendingCommReqs', $pendingCommReqs);
    }
}
