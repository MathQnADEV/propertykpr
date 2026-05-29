<table>
    <thead>
        <tr>
            <th colspan="7" style="font-weight:bold;font-size:14px;background-color:#111111;color:#ffffff;">
                Laporan Statistik Penjualan {{ $agentName ? '– ' . $agentName : '(Semua Agent)' }}
            </th>
        </tr>
        <tr>
            <th colspan="7" style="color:#888888;font-size:11px;">
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
        <tr><td>Total Pembayaran Masuk</td><td>{{ $stats['total_kpr'] }}</td></tr>
        <tr><td style="color:#444444;">Pembayaran Disetujui</td><td style="color:#444444;font-weight:bold;">{{ $stats['total_approved'] }}</td></tr>
        <tr><td style="color:#888888;">Pembayaran Pending</td><td style="color:#888888;font-weight:bold;">{{ $stats['total_pending'] }}</td></tr>
        <tr><td style="color:#555555;">Pembayaran Ditolak</td><td style="color:#555555;font-weight:bold;">{{ $stats['total_rejected'] }}</td></tr>
        <tr>
            <td style="color:#333333;">Total Pembayaran (Disetujui)</td>
            <td style="color:#333333;font-weight:bold;">Rp {{ number_format($stats['total_loan_approved'], 0, ',', '.') }}</td>
        </tr>

        @if(count($breakdown) > 0)
            <tr></tr>
            <tr></tr>
            <tr>
                <td colspan="7" style="font-weight:bold;font-size:12px;background-color:#F0F0F0;">REKAP PER AGENT</td>
            </tr>
            <tr>
                <td style="font-weight:bold;background-color:#F8F8FA;">Agent</td>
                <td style="font-weight:bold;background-color:#F8F8FA;text-align:center;">Properti</td>
                <td style="font-weight:bold;background-color:#F8F8FA;text-align:center;">Total Pembayaran</td>
                <td style="font-weight:bold;background-color:#F8F8FA;text-align:center;color:#444444;">Disetujui</td>
                <td style="font-weight:bold;background-color:#F8F8FA;text-align:center;color:#888888;">Pending</td>
                <td style="font-weight:bold;background-color:#F8F8FA;text-align:center;color:#555555;">Ditolak</td>
                <td style="font-weight:bold;background-color:#F8F8FA;text-align:right;color:#333333;">Total Pembayaran (Disetujui)</td>
            </tr>
            @foreach($breakdown as $row)
            <tr>
                <td style="font-weight:bold;">{{ $row['name'] }}</td>
                <td style="text-align:center;">{{ $row['total_listings'] }}</td>
                <td style="text-align:center;">{{ $row['total_kpr'] }}</td>
                <td style="text-align:center;color:#444444;">{{ $row['total_approved'] }}</td>
                <td style="text-align:center;color:#888888;">{{ $row['total_pending'] }}</td>
                <td style="text-align:center;color:#555555;">{{ $row['total_rejected'] }}</td>
                <td style="text-align:right;">Rp {{ number_format($row['total_loan_approved'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr style="font-weight:bold;background-color:#F0F0F0;">
                <td>TOTAL</td>
                <td style="text-align:center;">{{ array_sum(array_column($breakdown, 'total_listings')) }}</td>
                <td style="text-align:center;">{{ array_sum(array_column($breakdown, 'total_kpr')) }}</td>
                <td style="text-align:center;color:#444444;">{{ array_sum(array_column($breakdown, 'total_approved')) }}</td>
                <td style="text-align:center;color:#888888;">{{ array_sum(array_column($breakdown, 'total_pending')) }}</td>
                <td style="text-align:center;color:#555555;">{{ array_sum(array_column($breakdown, 'total_rejected')) }}</td>
                <td style="text-align:right;color:#333333;">Rp {{ number_format(array_sum(array_column($breakdown, 'total_loan_approved')), 0, ',', '.') }}</td>
            </tr>
        @endif

    </tbody>
</table>
