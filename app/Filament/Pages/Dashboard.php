<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\SalesBreakdownWidget;
use App\Filament\Widgets\SalesOverviewWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            SalesOverviewWidget::class,
            SalesBreakdownWidget::class,
        ];
    }
}
