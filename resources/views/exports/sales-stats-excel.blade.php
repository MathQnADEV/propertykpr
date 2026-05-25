<table>
    <thead>
        <tr>
            <th colspan="7" style="font-weight:bold;font-size:14px;background-color:#1e293b;color:#ffffff;">
                Laporan Statistik Penjualan {{ $agentName ? '– ' . $agentName : '(Semua Agent)' }}
            </th>
        </tr>
        <tr>
            <th colspan="7" style="color:#64748b;font-size:11px;">
                Digenerate: {{ now()->format('d M Y, H:i') }} WIB
            </th>
        </tr>
        <tr></tr>
    </thead>
    <tbody>

        {{-- ── Ringkasan ── --}}
        <tr>
            <td colspan="2" style="font-weight:bold;font-size:12px;background-color:#f1f5f9;">RINGKASAN STATISTIK</td>
        </tr>
        <tr>
            <td style="font-weight:bold;background-color:#f8fafc;">Metrik</td>
            <td style="font-weight:bold;background-color:#f8fafc;">Nilai</td>
        </tr>
        <tr><td>Total Properti</td><td>{{ $stats['total_listings'] }}</td></tr>
        <tr><td>Total KPR Masuk</td><td>{{ $stats['total_kpr'] }}</td></tr>
        <tr><td style="color:#444444;">KPR Disetujui</td><td style="color:#444444;font-weight:bold;">{{ $stats['total_approved'] }}</td></tr>
        <tr><td style="color:#d97706;">KPR Pending</td><td style="color:#d97706;font-weight:bold;">{{ $stats['total_pending'] }}</td></tr>
        <tr><td style="color:#dc2626;">KPR Ditolak</td><td style="color:#dc2626;font-weight:bold;">{{ $stats['total_rejected'] }}</td></tr>
        <tr>
            <td style="color:#555555;">Total Nilai Pinjaman (Approved)</td>
            <td style="color:#555555;font-weight:bold;">Rp {{ number_format($stats['total_loan_approved'], 0, ',', '.') }}</td>
        </tr>

        @if(count($breakdown) > 0)
            <tr></tr>
            <tr></tr>
            <tr>
                <td colspan="7" style="font-weight:bold;font-size:12px;background-color:#f1f5f9;">REKAP PER AGENT</td>
            </tr>
            <tr>
                <td style="font-weight:bold;background-color:#f8fafc;">Agent</td>
                <td style="font-weight:bold;background-color:#f8fafc;text-align:center;">Properti</td>
                <td style="font-weight:bold;background-color:#f8fafc;text-align:center;">Total KPR</td>
                <td style="font-weight:bold;background-color:#f8fafc;text-align:center;color:#444444;">Disetujui</td>
                <td style="font-weight:bold;background-color:#f8fafc;text-align:center;color:#d97706;">Pending</td>
                <td style="font-weight:bold;background-color:#f8fafc;text-align:center;color:#dc2626;">Ditolak</td>
                <td style="font-weight:bold;background-color:#f8fafc;text-align:right;color:#555555;">Total Pinjaman (Approved)</td>
            </tr>
            @foreach($breakdown as $row)
            <tr>
                <td style="font-weight:bold;">{{ $row['name'] }}</td>
                <td style="text-align:center;">{{ $row['total_listings'] }}</td>
                <td style="text-align:center;">{{ $row['total_kpr'] }}</td>
                <td style="text-align:center;color:#444444;">{{ $row['total_approved'] }}</td>
                <td style="text-align:center;color:#d97706;">{{ $row['total_pending'] }}</td>
                <td style="text-align:center;color:#dc2626;">{{ $row['total_rejected'] }}</td>
                <td style="text-align:right;">Rp {{ number_format($row['total_loan_approved'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr style="font-weight:bold;background-color:#f1f5f9;">
                <td>TOTAL</td>
                <td style="text-align:center;">{{ array_sum(array_column($breakdown, 'total_listings')) }}</td>
                <td style="text-align:center;">{{ array_sum(array_column($breakdown, 'total_kpr')) }}</td>
                <td style="text-align:center;color:#444444;">{{ array_sum(array_column($breakdown, 'total_approved')) }}</td>
                <td style="text-align:center;color:#d97706;">{{ array_sum(array_column($breakdown, 'total_pending')) }}</td>
                <td style="text-align:center;color:#dc2626;">{{ array_sum(array_column($breakdown, 'total_rejected')) }}</td>
                <td style="text-align:right;color:#555555;">Rp {{ number_format(array_sum(array_column($breakdown, 'total_loan_approved')), 0, ',', '.') }}</td>
            </tr>
        @endif

    </tbody>
</table>
