<?php

namespace App\Filament\Resources\CommissionRequests\Pages;

use App\Filament\Resources\CommissionRequests\CommissionRequestResource;
use Filament\Resources\Pages\ListRecords;

class ListCommissionRequests extends ListRecords
{
    protected static string $resource = CommissionRequestResource::class;
}
