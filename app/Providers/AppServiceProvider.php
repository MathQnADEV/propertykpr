<?php

namespace App\Providers;

use App\View\Composers\AgentSidebarComposer;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Custom pagination views (inline styles, tidak butuh Tailwind compile)
        Paginator::defaultView('vendor.pagination.tailwind');
        Paginator::defaultSimpleView('vendor.pagination.simple-tailwind');

        // View Composer — inject sidebar badge counts into every agent layout render
        View::composer('agent.layouts.app', AgentSidebarComposer::class);
    }
}
