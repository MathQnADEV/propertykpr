<?php

namespace App\Filament\Resources\MortgageRequests\Pages;

use App\Filament\Concerns\HandlesCustomerData;
use App\Filament\Concerns\NormalizesMortgageData;
use App\Filament\Resources\MortgageRequests\MortgageRequestResource;
use App\Models\Customer;
use Filament\Resources\Pages\CreateRecord;

class CreateMortgageRequest extends CreateRecord
{
    use HandlesCustomerData;
    use NormalizesMortgageData;

    protected static string $resource = MortgageRequestResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Catat pembuat/penjual transaksi — dasar untuk hak ajukan komisi.
        $data['user_id'] = auth()->id();

        // Hitung nilai numerik KPR di server (anti-NULL untuk cash/sewa & DP kosong).
        $data = $this->computeMortgageData($data);

        // Buat customer baru dari field cust_* lalu hubungkan ke pengajuan.
        $customerData = $this->pullCustomerData($data);
        $customer = Customer::create($customerData);
        $data['customer_id'] = $customer->id;

        return $data;
    }
}
