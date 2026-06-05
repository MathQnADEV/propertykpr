<?php

namespace App\Filament\Resources\Commissions\Pages;

use App\Filament\Resources\Commissions\CommissionResource;
use App\Models\MortgageRequest;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditCommission extends EditRecord
{
    protected static string $resource = CommissionResource::class;

    protected Width | string | null $maxContentWidth = Width::Full;

    /**
     * Populate virtual/helper fields BEFORE form hydrates.
     * Using mutateFormDataBeforeFill ensures these values are set
     * as initial data — Filament won't fire afterStateUpdated for
     * fields set this way, preventing the commission_amount=0 cascade.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (! empty($data['mortgage_request_id'])) {
            $mr = MortgageRequest::with('house')->find($data['mortgage_request_id']);
            if ($mr) {
                $data['_agent_id']        = $mr->house?->agent_id;
                $data['_house_price']     = $mr->house_price;
                $data['_base_commission'] = (int) round($mr->house_price * 0.025);
            }
        }
        return $data;
    }
}
