<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #111111; background: #fff; }

        .header { background: #111111; color: #fff; padding: 16px 22px; margin-bottom: 18px; }
        .header h1 { font-size: 15px; font-weight: bold; margin-bottom: 3px; }
        .header p  { font-size: 9px; color: rgba(255,255,255,0.55); }

        .section-title {
            font-size: 10px; font-weight: bold; text-transform: uppercase;
            letter-spacing: 0.05em; color: #555555;
            border-bottom: 2px solid #CCCCCC; padding-bottom: 5px;
            margin-bottom: 10px; margin-top: 18px;
        }

        /* Summary row */
        .summary-wrap { width: 100%; border-collapse: separate; border-spacing: 6px; }
        .s-card { background: #F8F8FA; border: 1px solid #E5E5E5; border-radius: 4px; padding: 8px 12px; }
        .s-label { font-size: 8px; color: #888; text-transform: uppercase; letter-spacing: 0.04em; }
        .s-value { font-size: 15px; font-weight: bold; margin-top: 2px; color: #111; }
        .s-value.money { font-size: 11px; }

        /* Type breakdown row */
        .type-wrap { width: 100%; border-collapse: separate; border-spacing: 6px; margin-top: 10px; }
        .t-card { background: #F0F0F0; border: 1px solid #E0E0E0; border-radius: 4px; padding: 6px 10px; text-align: center; }
        .t-label { font-size: 8px; color: #666; font-weight: bold; text-transform: uppercase; }
        .t-value { font-size: 13px; font-weight: bold; color: #111; margin-top: 1px; }

        /* Breakdown table */
        table.bd { width: 100%; border-collapse: collapse; margin-top: 4px; font-size: 9px; }
        table.bd th {
            background: #1a1a1a; color: #fff;
            font-size: 8px; font-weight: bold;
            text-transform: uppercase; letter-spacing: 0.03em;
            padding: 7px 6px; border: 1px solid #333;
            text-align: center;
        }
        table.bd th.left { text-align: left; }
        table.bd td { padding: 6px 6px; border: 1px solid #DDDDDD; }
        table.bd tr:nth-child(even) td { background: #F8F8FA; }
        table.bd tfoot td {
            background: #111111; color: #fff; font-weight: bold; font-size: 9px;
            padding: 7px 6px; border: 1px solid #333;
        }

        /* Dividers inside th */
        .th-group { border-left: 2px solid #555 !important; }
        .td-group { border-left: 2px solid #AAAAAA !important; }

        .tc  { text-align: center; }
        .tr  { text-align: right; }
        .bold { font-weight: bold; }
        .c-green { color: #339944; }
        .c-amber { color: #999933; }
        .c-red   { color: #993333; }
        .c-money { color: #333333; }

        .footer { margin-top: 22px; text-align: center; font-size: 8px; color: #888888; border-top: 1px solid #CCCCCC; padding-top: 8px; }
    </style>
</head>
<body>

<div class="header">
    <h1>Laporan Statistik Penjualan{{ $agentName ? ' &ndash; ' . $agentName : '' }}</h1>
    <p>Digenerate: {{ now()->format('d M Y, H:i') }} WIB &nbsp;&bull;&nbsp; {{ $agentName ? 'Per-Agent: ' . $agentName : 'Semua Agent' }}</p>
</div>

{{-- ── Ringkasan ── --}}
<div class="section-title">Ringkasan Statistik</div>
<table class="summary-wrap">
    <tr>
        <td width="14.28%">
            <div class="s-card">
                <div class="s-label">Total Properti</div>
                <div class="s-value">{{ number_format($stats['total_listings']) }}</div>
            </div>
        </td>
        <td width="14.28%">
            <div class="s-card">
                <div class="s-label">Total Transaksi</div>
                <div class="s-value">{{ number_format($stats['total_kpr']) }}</div>
            </div>
        </td>
        <td width="14.28%">
            <div class="s-card">
                <div class="s-label">Disetujui</div>
                <div class="s-value c-green">{{ number_format($stats['total_approved']) }}</div>
            </div>
        </td>
        <td width="14.28%">
            <div class="s-card">
                <div class="s-label">Pending</div>
                <div class="s-value c-amber">{{ number_format($stats['total_pending']) }}</div>
            </div>
        </td>
        <td width="14.28%">
            <div class="s-card">
                <div class="s-label">Ditolak</div>
                <div class="s-value c-red">{{ number_format($stats['total_rejected']) }}</div>
            </div>
        </td>
        <td width="28.56%" colspan="2">
            <div class="s-card" style="background:#111111;border-color:#333;">
                <div class="s-label" style="color:rgba(255,255,255,0.5);">Total Pembayaran Disetujui</div>
                <div class="s-value money" style="color:#fff;">Rp {{ number_format($stats['total_loan_approved'], 0, ',', '.') }}</div>
            </div>
        </td>
    </tr>
</table>

{{-- ── Per-Tipe Pembayaran ── --}}
<table class="type-wrap">
    <tr>
        <td width="16.6%">
            <div class="t-card">
                <div class="t-label">KPR (Rumah)</div>
                <div class="t-value">{{ number_format($stats['total_kpr_count']) }}</div>
            </div>
        </td>
        <td width="16.6%">
            <div class="t-card">
                <div class="t-label">KPA (Apartemen)</div>
                <div class="t-value">{{ number_format($stats['total_kpa_count'] ?? 0) }}</div>
            </div>
        </td>
        <td width="16.6%">
            <div class="t-card">
                <div class="t-label">KPT (Tanah)</div>
                <div class="t-value">{{ number_format($stats['total_kpt_count'] ?? 0) }}</div>
            </div>
        </td>
        <td width="16.6%">
            <div class="t-card">
                <div class="t-label">KPG (Gudang)</div>
                <div class="t-value">{{ number_format($stats['total_kpg_count'] ?? 0) }}</div>
            </div>
        </td>
        <td width="16.6%">
            <div class="t-card">
                <div class="t-label">Cash (Tunai)</div>
                <div class="t-value">{{ number_format($stats['total_cash_count']) }}</div>
            </div>
        </td>
        <td width="16.6%">
            <div class="t-card">
                <div class="t-label">Sewa</div>
                <div class="t-value">{{ number_format($stats['total_sewa_count'] ?? 0) }}</div>
            </div>
        </td>
    </tr>
</table>

@if(count($breakdown) > 0)
{{-- ── Per-Agent Breakdown ── --}}
<div class="section-title">Rekap Per Agent</div>
<table class="bd">
    <thead>
        <tr>
            <th class="left" rowspan="2" style="width:14%">Agent</th>
            <th rowspan="2" style="width:5%">Properti</th>
            {{-- Tipe Pembayaran --}}
            <th colspan="6" class="th-group" style="background:#333333;">Tipe Pembayaran</th>
            {{-- Total --}}
            <th rowspan="2" class="th-group" style="width:5%">Total</th>
            {{-- Status --}}
            <th colspan="3" class="th-group" style="background:#333333;">Status</th>
            {{-- Nilai --}}
            <th rowspan="2" class="th-group" style="width:13%;text-align:right;">Total Disetujui (Rp)</th>
        </tr>
        <tr>
            <th class="th-group" style="width:5%">KPR</th>
            <th style="width:5%">KPA</th>
            <th style="width:5%">KPT</th>
            <th style="width:5%">KPG</th>
            <th style="width:5%">Cash</th>
            <th style="width:5%">Sewa</th>
            <th class="th-group" style="width:5%;color:#88cc88;">Setuju</th>
            <th style="width:5%;color:#cccc88;">Pending</th>
            <th style="width:5%;color:#cc8888;">Tolak</th>
        </tr>
    </thead>
    <tbody>
        @foreach($breakdown as $row)
        <tr>
            <td class="bold">{{ $row['name'] }}</td>
            <td class="tc">{{ $row['total_listings'] }}</td>
            <td class="tc td-group">{{ $row['kpr'] }}</td>
            <td class="tc">{{ $row['kpa'] }}</td>
            <td class="tc">{{ $row['kpt'] }}</td>
            <td class="tc">{{ $row['kpg'] }}</td>
            <td class="tc">{{ $row['cash'] }}</td>
            <td class="tc">{{ $row['sewa'] }}</td>
            <td class="tc bold td-group">{{ $row['total_transactions'] }}</td>
            <td class="tc c-green bold td-group">{{ $row['total_approved'] }}</td>
            <td class="tc c-amber bold">{{ $row['total_pending'] }}</td>
            <td class="tc c-red bold">{{ $row['total_rejected'] }}</td>
            <td class="tr c-money td-group">Rp {{ number_format($row['total_loan_approved'], 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td>TOTAL</td>
            <td class="tc">{{ array_sum(array_column($breakdown, 'total_listings')) }}</td>
            <td class="tc">{{ array_sum(array_column($breakdown, 'kpr')) }}</td>
            <td class="tc">{{ array_sum(array_column($breakdown, 'kpa')) }}</td>
            <td class="tc">{{ array_sum(array_column($breakdown, 'kpt')) }}</td>
            <td class="tc">{{ array_sum(array_column($breakdown, 'kpg')) }}</td>
            <td class="tc">{{ array_sum(array_column($breakdown, 'cash')) }}</td>
            <td class="tc">{{ array_sum(array_column($breakdown, 'sewa')) }}</td>
            <td class="tc">{{ array_sum(array_column($breakdown, 'total_transactions')) }}</td>
            <td class="tc">{{ array_sum(array_column($breakdown, 'total_approved')) }}</td>
            <td class="tc">{{ array_sum(array_column($breakdown, 'total_pending')) }}</td>
            <td class="tc">{{ array_sum(array_column($breakdown, 'total_rejected')) }}</td>
            <td class="tr">Rp {{ number_format(array_sum(array_column($breakdown, 'total_loan_approved')), 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>
@endif

<div class="footer">
    &copy; {{ now()->year }} &mdash; Laporan ini digenerate secara otomatis oleh sistem.
</div>

</body>
</html>
