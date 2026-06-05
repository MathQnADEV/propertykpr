<?php

namespace App\Filament\Resources\Commissions\Pages;

use App\Filament\Resources\Commissions\CommissionResource;
use App\Models\Commission;
use App\Models\MortgageRequest;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateCommission extends CreateRecord
{
    protected static string $resource = CommissionResource::class;

    protected Width | string | null $maxContentWidth = Width::Full;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Unique constraint tidak mengenal soft-delete — hapus permanen record lama
        // yang sudah di-soft-delete agar tidak trigger duplicate entry error.
        Commission::withTrashed()
            ->where('mortgage_request_id', $data['mortgage_request_id'])
            ->whereNotNull('deleted_at')
            ->forceDelete();

        $mr = MortgageRequest::with('house')->find($data['mortgage_request_id']);
        $data['agent_id'] = $mr?->house?->agent_id;
        return $data;
    }
}
