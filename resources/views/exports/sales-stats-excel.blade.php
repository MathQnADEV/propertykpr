<table>
    <thead>
        <tr>
            <th colspan="13" style="font-weight:bold;font-size:14px;background-color:#111111;color:#ffffff;">
                Laporan Statistik Penjualan {{ $agentName ? '– ' . $agentName : '(Semua Agent)' }}
            </th>
        </tr>
        <tr>
            <th colspan="13" style="color:#888888;font-size:11px;">
                Digenerate: {{ now()->format('d M Y, H:i') }} WIB
            </th>
        </tr>
        <tr></tr>
    </thead>
    <tbody>

        {{-- ── Ringkasan ── --}}
        <tr>
            <td colspan="2" style="font-weight:bold;font-size:12px;background-color:#F0F0F0;">RINGKASAN STATISTIK</td>
        </tr>
        <tr>
            <td style="font-weight:bold;background-color:#F8F8FA;">Metrik</td>
            <td style="font-weight:bold;background-color:#F8F8FA;">Nilai</td>
        </tr>
        <tr><td>Total Properti</td><td>{{ $stats['total_listings'] }}</td></tr>
        <tr><td>Total Transaksi Masuk</td><td>{{ $stats['total_kpr'] }}</td></tr>
        <tr><td style="padding-left:16px;">— KPR (Rumah)</td><td>{{ $stats['total_kpr_count'] }}</td></tr>
        <tr><td style="padding-left:16px;">— KPA (Apartemen)</td><td>{{ $stats['total_kpa_count'] ?? 0 }}</td></tr>
        <tr><td style="padding-left:16px;">— KPT (Tanah)</td><td>{{ $stats['total_kpt_count'] ?? 0 }}</td></tr>
        <tr><td style="padding-left:16px;">— KPG (Gudang)</td><td>{{ $stats['total_kpg_count'] ?? 0 }}</td></tr>
        <tr><td style="padding-left:16px;">— Cash (Tunai)</td><td>{{ $stats['total_cash_count'] }}</td></tr>
        <tr><td style="padding-left:16px;">— Sewa</td><td>{{ $stats['total_sewa_count'] ?? 0 }}</td></tr>
        <tr><td style="color:#339944;">Disetujui</td><td style="color:#339944;font-weight:bold;">{{ $stats['total_approved'] }}</td></tr>
        <tr><td style="color:#999933;">Pending</td><td style="color:#999933;font-weight:bold;">{{ $stats['total_pending'] }}</td></tr>
        <tr><td style="color:#993333;">Ditolak</td><td style="color:#993333;font-weight:bold;">{{ $stats['total_rejected'] }}</td></tr>
        <tr>
            <td style="font-weight:bold;">Total Pembayaran Disetujui</td>
            <td style="font-weight:bold;">Rp {{ number_format($stats['total_loan_approved'], 0, ',', '.') }}</td>
        </tr>

        @if(count($breakdown) > 0)
            <tr></tr>
            <tr></tr>
            <tr>
                <td colspan="13" style="font-weight:bold;font-size:12px;background-color:#F0F0F0;">REKAP PER AGENT</td>
            </tr>
            {{-- Header baris 1: judul grup --}}
            <tr>
                <td style="font-weight:bold;background-color:#1a1a1a;color:#fff;">Agent</td>
                <td style="font-weight:bold;background-color:#1a1a1a;color:#fff;text-align:center;">Properti</td>
                <td colspan="6" style="font-weight:bold;background-color:#333333;color:#fff;text-align:center;">Tipe Pembayaran</td>
                <td style="font-weight:bold;background-color:#1a1a1a;color:#fff;text-align:center;">Total</td>
                <td colspan="3" style="font-weight:bold;background-color:#333333;color:#fff;text-align:center;">Status</td>
                <td style="font-weight:bold;background-color:#1a1a1a;color:#fff;text-align:right;">Total Disetujui (Rp)</td>
            </tr>
            {{-- Header baris 2: sub-kolom --}}
            <tr>
                <td style="background-color:#F0F0F0;"></td>
                <td style="background-color:#F0F0F0;"></td>
                <td style="font-weight:bold;background-color:#F0F0F0;text-align:center;">KPR</td>
                <td style="font-weight:bold;background-color:#F0F0F0;text-align:center;">KPA</td>
                <td style="font-weight:bold;background-color:#F0F0F0;text-align:center;">KPT</td>
                <td style="font-weight:bold;background-color:#F0F0F0;text-align:center;">KPG</td>
                <td style="font-weight:bold;background-color:#F0F0F0;text-align:center;">Cash</td>
                <td style="font-weight:bold;background-color:#F0F0F0;text-align:center;">Sewa</td>
                <td style="background-color:#F0F0F0;"></td>
                <td style="font-weight:bold;background-color:#F0F0F0;text-align:center;color:#339944;">Setuju</td>
                <td style="font-weight:bold;background-color:#F0F0F0;text-align:center;color:#999933;">Pending</td>
                <td style="font-weight:bold;background-color:#F0F0F0;text-align:center;color:#993333;">Tolak</td>
                <td style="background-color:#F0F0F0;"></td>
            </tr>
            @foreach($breakdown as $row)
            <tr>
                <td style="font-weight:bold;">{{ $row['name'] }}</td>
                <td style="text-align:center;">{{ $row['total_listings'] }}</td>
                <td style="text-align:center;">{{ $row['kpr'] }}</td>
                <td style="text-align:center;">{{ $row['kpa'] }}</td>
                <td style="text-align:center;">{{ $row['kpt'] }}</td>
                <td style="text-align:center;">{{ $row['kpg'] }}</td>
                <td style="text-align:center;">{{ $row['cash'] }}</td>
                <td style="text-align:center;">{{ $row['sewa'] }}</td>
                <td style="text-align:center;font-weight:bold;">{{ $row['total_transactions'] }}</td>
                <td style="text-align:center;color:#339944;font-weight:bold;">{{ $row['total_approved'] }}</td>
                <td style="text-align:center;color:#999933;font-weight:bold;">{{ $row['total_pending'] }}</td>
                <td style="text-align:center;color:#993333;font-weight:bold;">{{ $row['total_rejected'] }}</td>
                <td style="text-align:right;">Rp {{ number_format($row['total_loan_approved'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr style="font-weight:bold;background-color:#111111;color:#ffffff;">
                <td>TOTAL</td>
                <td style="text-align:center;">{{ array_sum(array_column($breakdown, 'total_listings')) }}</td>
                <td style="text-align:center;">{{ array_sum(array_column($breakdown, 'kpr')) }}</td>
                <td style="text-align:center;">{{ array_sum(array_column($breakdown, 'kpa')) }}</td>
                <td style="text-align:center;">{{ array_sum(array_column($breakdown, 'kpt')) }}</td>
                <td style="text-align:center;">{{ array_sum(array_column($breakdown, 'kpg')) }}</td>
                <td style="text-align:center;">{{ array_sum(array_column($breakdown, 'cash')) }}</td>
                <td style="text-align:center;">{{ array_sum(array_column($breakdown, 'sewa')) }}</td>
                <td style="text-align:center;">{{ array_sum(array_column($breakdown, 'total_transactions')) }}</td>
                <td style="text-align:center;">{{ array_sum(array_column($breakdown, 'total_approved')) }}</td>
                <td style="text-align:center;">{{ array_sum(array_column($breakdown, 'total_pending')) }}</td>
                <td style="text-align:center;">{{ array_sum(array_column($breakdown, 'total_rejected')) }}</td>
                <td style="text-align:right;">Rp {{ number_format(array_sum(array_column($breakdown, 'total_loan_approved')), 0, ',', '.') }}</td>
            </tr>
        @endif

    </tbody>
</table>
