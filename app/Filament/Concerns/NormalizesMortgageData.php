<?php

namespace App\Filament\Concerns;

use App\Models\House;
use App\Models\Interest;

/**
 * Menghitung semua nilai numerik pengajuan KPR di sisi server
 * (duration, interest, dp, cicilan, dll) agar tidak bergantung pada
 * field tersembunyi di form yang bisa gagal ter-dehydrate.
 */
trait NormalizesMortgageData
{
    protected function computeMortgageData(array $data): array
    {
        $type = $data['payment_type'] ?? 'kpr';

        // Harga properti — dari form, fallback ke data house.
        $housePrice = (float) ($data['house_price'] ?? 0);
        if ($housePrice <= 0 && ! empty($data['house_id'])) {
            $housePrice = (float) (House::find($data['house_id'])?->price ?? 0);
        }
        $data['house_price'] = $housePrice;

        // ── Cash / Sewa ────────────────────────────────────────────────
        if (in_array($type, ['cash', 'sewa'], true)) {
            $data['interest_id']                = null;
            $data['bank_name']                  = $type === 'cash' ? 'Cash' : 'Sewa';
            $data['duration']                   = 0;
            $data['interest']                   = 0;
            $data['dp_percentage']              = 100;
            $data['dp_total_amount']            = (int) round($housePrice);
            $data['loan_total_amount']          = 0;
            $data['monthly_amount']             = 0;
            $data['loan_interest_total_amount'] = (int) round($housePrice);
            unset($data['dp_input']);

            return $data;
        }

        // ── Kredit (kpr/kpa/kpt/kpg) ───────────────────────────────────
        $interest = ! empty($data['interest_id'])
            ? Interest::with('bank')->find($data['interest_id'])
            : null;

        $rate     = (float) ($interest->interest ?? 0);
        $duration = (int) ($interest->duration ?? 0);

        $data['interest']  = $rate;
        $data['duration']  = $duration;
        $data['bank_name'] = $interest?->bank?->name ?? ($data['bank_name'] ?? '');

        // Parse DP — "20%" (persen) atau "20000000" (nominal Rp), boleh kosong.
        $dpRaw    = trim((string) ($data['dp_input'] ?? ''));
        $dpAmount = 0.0;
        $dpPct    = 0.0;

        if ($dpRaw !== '' && $housePrice > 0) {
            if (str_contains($dpRaw, '%')) {
                $dpPct    = (float) str_replace(['%', ' '], '', $dpRaw);
                $dpAmount = ($dpPct / 100) * $housePrice;
            } else {
                $dpAmount = (float) str_replace(['.', ',', ' '], '', $dpRaw);
                $dpPct    = $housePrice > 0 ? ($dpAmount / $housePrice) * 100 : 0;
            }
        }

        $loan = max($housePrice - $dpAmount, 0);
        $data['dp_percentage']     = (int) round($dpPct);
        $data['dp_total_amount']   = (int) round($dpAmount);
        $data['loan_total_amount'] = (int) round($loan);

        if ($duration > 0 && $loan > 0 && $rate > 0) {
            $n = $duration * 12;
            $r = $rate / 100 / 12;
            $monthly = ($loan * $r * pow(1 + $r, $n)) / (pow(1 + $r, $n) - 1);
            $data['monthly_amount']             = (int) round($monthly);
            $data['loan_interest_total_amount'] = (int) round($monthly * $n);
        } else {
            $data['monthly_amount']             = 0;
            $data['loan_interest_total_amount'] = 0;
        }

        unset($data['dp_input']);

        return $data;
    }
}
