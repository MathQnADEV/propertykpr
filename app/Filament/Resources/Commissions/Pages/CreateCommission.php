<?php

namespace App\Filament\Resources\Commissions\Pages;

use App\Filament\Resources\Commissions\CommissionResource;
use App\Models\MortgageRequest;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateCommission extends CreateRecord
{
    protected static string $resource = CommissionResource::class;

    protected Width | string | null $maxContentWidth = Width::Full;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $mr = MortgageRequest::with('house')->find($data['mortgage_request_id']);
        $data['agent_id'] = $mr?->house?->agent_id;
        return $data;
    }
}
