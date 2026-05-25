<?php

namespace App\Filament\Resources\Commissions\Pages;

use App\Filament\Resources\Commissions\CommissionResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditCommission extends EditRecord
{
    protected static string $resource = CommissionResource::class;

    protected Width | string | null $maxContentWidth = Width::Full;
}
