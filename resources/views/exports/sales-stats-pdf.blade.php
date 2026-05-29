<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #111111; background: #fff; }

        .header { background: #111111; color: #fff; padding: 18px 24px; margin-bottom: 20px; }
        .header h1 { font-size: 16px; font-weight: bold; margin-bottom: 4px; }
        .header p  { font-size: 10px; color: rgba(255,255,255,0.6); }

        .section-title {
            font-size: 11px; font-weight: bold; text-transform: uppercase;
            letter-spacing: 0.05em; color: #555555;
            border-bottom: 2px solid #CCCCCC; padding-bottom: 6px;
            margin-bottom: 12px; margin-top: 20px;
        }

        /* Summary cards */
        .cards { width: 100%; border-collapse: separate; border-spacing: 8px; }
        .card { background: #F8F8FA; border: 1px solid #E5E5E5; border-radius: 6px; padding: 10px 14px; }
        .card-label { font-size: 9px; color: #888888; text-transform: uppercase; letter-spacing: 0.04em; }
        .card-value { font-size: 18px; font-weight: bold; margin-top: 2px; }

        .c-blue   { color: #333333; } /* was: #3b82f6 */
        .c-slate  { color: #555555; } /* was: #475569 */
        .c-green  { color: #444444; }
        .c-amber  { color: #888888; } /* was: #d97706 */
        .c-red    { color: #555555; } /* was: #dc2626 */
        .c-purple { color: #333333; } /* was: #555555 */

        /* Breakdown table */
        table.breakdown { width: 100%; border-collapse: collapse; margin-top: 4px; }
        table.breakdown th {
            background: #F0F0F0; font-size: 9px; font-weight: bold;
            text-transform: uppercase; letter-spacing: 0.04em;
            padding: 8px 10px; border: 1px solid #DDDDDD; color: #555555;
        }
        table.breakdown td { padding: 7px 10px; border: 1px solid #DDDDDD; font-size: 10px; }
        table.breakdown tr:nth-child(even) td { background: #F8F8FA; }
        table.breakdown tfoot td {
            background: #111111; color: #fff; font-weight: bold; font-size: 10px;
            padding: 8px 10px; border: 1px solid #333333;
        }

        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .font-bold   { font-weight: bold; }

        .footer { margin-top: 28px; text-align: center; font-size: 9px; color: #888888; border-top: 1px solid #CCCCCC; padding-top: 10px; }
    </style>
</head>
<body>

<div class="header">
    <h1>Laporan Statistik Penjualan{{ $agentName ? ' &ndash; ' . $agentName : '' }}</h1>
    <p>Digenerate: {{ now()->format('d M Y, H:i') }} WIB &nbsp;&bull;&nbsp; {{ $agentName ? 'Per-Agent' : 'Semua Agent' }}</p>
</div>

{{-- ── Ringkasan ── --}}
<div class="section-title">Ringkasan Statistik</div>
<table class="cards">
    <tr>
        <td width="16.6%">
            <div class="card">
                <div class="card-label">Total Properti</div>
                <div class="card-value c-blue">{{ number_format($stats['total_listings']) }}</div>
            </div>
        </td>
        <td width="16.6%">
            <div class="card">
                <div class="card-label">Total Pembayaran Masuk</div>
                <div class="card-value c-slate">{{ number_format($stats['total_kpr']) }}</div>
            </div>
        </td>
        <td width="16.6%">
            <div class="card">
                <div class="card-label">Pembayaran Disetujui</div>
                <div class="card-value c-green">{{ number_format($stats['total_approved']) }}</div>
            </div>
        </td>
        <td width="16.6%">
            <div class="card">
                <div class="card-label">Pembayaran Pending</div>
                <div class="card-value c-amber">{{ number_format($stats['total_pending']) }}</div>
            </div>
        </td>
        <td width="16.6%">
            <div class="card">
                <div class="card-label">Pembayaran Ditolak</div>
                <div class="card-value c-red">{{ number_format($stats['total_rejected']) }}</div>
            </div>
        </td>
        <td width="16.6%">
            <div class="card">
                <div class="card-label">Total Pembayaran (Disetujui)</div>
                <div class="card-value c-purple" style="font-size:12px;">Rp {{ number_format($stats['total_loan_approved'], 0, ',', '.') }}</div>
            </div>
        </td>
    </tr>
</table>

@if(count($breakdown) > 0)
{{-- ── Per-Agent Breakdown ── --}}
<div class="section-title">Rekap Per Agent</div>
<table class="breakdown">
    <thead>
        <tr>
            <th>Agent</th>
            <th class="text-center">Properti</th>
            <th class="text-center">Total Pembayaran</th>
            <th class="text-center" style="color:#444444;">Disetujui</th>
            <th class="text-center" style="color:#888888;">Pending</th>
            <th class="text-center" style="color:#555555;">Ditolak</th>
            <th class="text-right" style="color:#333333;">Total Pembayaran (Disetujui)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($breakdown as $row)
        <tr>
            <td class="font-bold">{{ $row['name'] }}</td>
            <td class="text-center">{{ $row['total_listings'] }}</td>
            <td class="text-center">{{ $row['total_kpr'] }}</td>
            <td class="text-center c-green font-bold">{{ $row['total_approved'] }}</td>
            <td class="text-center c-amber font-bold">{{ $row['total_pending'] }}</td>
            <td class="text-center c-red font-bold">{{ $row['total_rejected'] }}</td>
            <td class="text-right c-purple">Rp {{ number_format($row['total_loan_approved'], 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td>TOTAL</td>
            <td class="text-center">{{ array_sum(array_column($breakdown, 'total_listings')) }}</td>
            <td class="text-center">{{ array_sum(array_column($breakdown, 'total_kpr')) }}</td>
            <td class="text-center">{{ array_sum(array_column($breakdown, 'total_approved')) }}</td>
            <td class="text-center">{{ array_sum(array_column($breakdown, 'total_pending')) }}</td>
            <td class="text-center">{{ array_sum(array_column($breakdown, 'total_rejected')) }}</td>
            <td class="text-right">Rp {{ number_format(array_sum(array_column($breakdown, 'total_loan_approved')), 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>
@endif

<div class="footer">
    &copy; {{ now()->year }} &mdash; Laporan ini digenerate secara otomatis oleh sistem.
</div>

</body>
</html>
