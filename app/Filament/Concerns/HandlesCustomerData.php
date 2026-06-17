<?php

namespace App\Filament\Concerns;

use App\Models\Customer;

/**
 * Dipakai oleh halaman Create/Edit Pengajuan KPR (Filament).
 * Field customer diisi langsung di form dengan prefix "cust_" lalu
 * dibuat/diupdate sebagai record Customer saat menyimpan — tanpa dropdown.
 */
trait HandlesCustomerData
{
    /** Kolom Customer yang dikelola lewat form (prefix "cust_"). */
    protected function customerKeys(): array
    {
        return [
            'nama_lengkap',
            'email',
            'phone',
            'nik',
            'tempat_lahir',
            'tanggal_lahir',
            'alamat',
            'pekerjaan',
            'penghasilan_bulanan',
            'status_pernikahan',
        ];
    }

    /** Ambil & buang field cust_* dari $data, kembalikan array data Customer. */
    protected function pullCustomerData(array &$data): array
    {
        $cust = [];
        foreach ($this->customerKeys() as $key) {
            $cust[$key] = $data["cust_{$key}"] ?? null;
            unset($data["cust_{$key}"]);
        }
        return $cust;
    }

    /** Isi field cust_* dari record Customer (untuk prefill saat edit). */
    protected function fillCustomerData(array $data, ?Customer $customer): array
    {
        if (! $customer) {
            return $data;
        }
        foreach ($this->customerKeys() as $key) {
            $data["cust_{$key}"] = $customer->{$key};
        }
        return $data;
    }
}
