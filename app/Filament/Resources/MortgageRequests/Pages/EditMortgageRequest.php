<?php

namespace App\Filament\Resources\MortgageRequests\Pages;

use App\Filament\Concerns\HandlesCustomerData;
use App\Filament\Concerns\NormalizesMortgageData;
use App\Filament\Resources\MortgageRequests\MortgageRequestResource;
use App\Models\Customer;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditMortgageRequest extends EditRecord
{
    use HandlesCustomerData;
    use NormalizesMortgageData;

    protected static string $resource = MortgageRequestResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Prefill field cust_* dari customer yang sudah terhubung.
        return $this->fillCustomerData($data, $this->record->customer);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Hitung ulang nilai numerik KPR di server.
        $data = $this->computeMortgageData($data);

        // Update customer terhubung, atau buat baru jika belum ada.
        $customerData = $this->pullCustomerData($data);

        if ($this->record->customer_id && $this->record->customer) {
            $this->record->customer->update($customerData);
            $data['customer_id'] = $this->record->customer_id;
        } else {
            $customer = Customer::create($customerData);
            $data['customer_id'] = $customer->id;
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        $isMaster = fn(): bool => auth()->check() && auth()->user()->hasRole('master');

        return [
            DeleteAction::make()
                ->authorize($isMaster)
                ->visible($isMaster),
            ForceDeleteAction::make()
                ->authorize($isMaster)
                ->visible($isMaster),
            RestoreAction::make()
                ->authorize($isMaster)
                ->visible($isMaster),
        ];
    }
}
