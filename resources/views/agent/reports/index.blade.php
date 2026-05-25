@extends('agent.layouts.app')

@section('title', 'Laporan Agent - Agent Panel')

@section('content')

    {{-- ── Header ──────────────────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#060922]">Laporan Performa</h1>
            <p class="text-sm text-[#8F91A2] mt-0.5">Ringkasan statistik penjualan & transaksi properti Anda</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-[#8F91A2] bg-white px-4 py-2 rounded-xl border border-[#F2F2F4] self-start sm:self-auto">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>{{ now()->translatedFormat('F Y') }}</span>
        </div>
    </div>

    {{-- ── KPI Cards (2×2 mobile → 4 col desktop) ────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

        {{-- Listings --}}
        <div class="bg-white rounded-2xl border border-[#F2F2F4] p-5">
            <div class="w-10 h-10 rounded-xl bg-[#060922]/8 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-[#060922]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <p class="text-3xl font-bold text-[#060922]">{{ $totalListings }}</p>
            <p class="text-xs text-[#8F91A2] font-medium mt-1">Listing Aktif</p>
        </div>

        {{-- Sold --}}
        <div class="bg-white rounded-2xl border border-[#F2F2F4] p-5">
            <div class="w-10 h-10 rounded-xl bg-[#111111]/10 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-[#111111]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-3xl font-bold text-[#060922]">{{ $totalSold }}</p>
            <p class="text-xs text-[#8F91A2] font-medium mt-1">Transaksi Terjual</p>
        </div>

        {{-- In Process --}}
        <div class="bg-white rounded-2xl border border-[#F2F2F4] p-5">
            <div class="w-10 h-10 rounded-xl bg-[#888888]/10 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-[#888888]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-3xl font-bold text-[#060922]">{{ $totalInProcess }}</p>
            <p class="text-xs text-[#8F91A2] font-medium mt-1">Dalam Proses</p>
        </div>

        {{-- Failed --}}
        <div class="bg-white rounded-2xl border border-[#F2F2F4] p-5">
            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-[#444444]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-3xl font-bold text-[#060922]">{{ $totalFailed }}</p>
            <p class="text-xs text-[#8F91A2] font-medium mt-1">Gagal / Ditolak</p>
        </div>
    </div>

    {{-- ── Revenue Banner ──────────────────────────────────────────────────── --}}
    <div class="rounded-2xl p-5 mb-6 flex items-center justify-between overflow-hidden relative"
        style="background: linear-gradient(135deg, #060922 0%, #1a2563 100%);">
        <div class="relative z-10">
            <p class="text-white/60 text-xs font-semibold uppercase tracking-wider mb-1">Total Nilai Properti Terjual</p>
            <p class="text-white text-2xl sm:text-3xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-white/40 text-xs mt-1.5">Dari {{ $totalSold }} transaksi yang disetujui</p>
        </div>
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center flex-shrink-0"
            style="background: rgba(255,255,255,0.08);">
            <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        {{-- decorative circles --}}
        <div class="absolute -right-8 -top-8 w-40 h-40 rounded-full opacity-5" style="background: #333333;{{-- was: #3F52FF --}}"></div>
        <div class="absolute -right-4 -bottom-10 w-32 h-32 rounded-full opacity-5" style="background: #333333;{{-- was: #3F52FF --}}"></div>
    </div>

    {{-- ── Charts Row ───────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        {{-- Monthly Bar Chart --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-[#F2F2F4] p-5">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="font-bold text-[#060922]">Penjualan per Bulan</h3>
                    <p class="text-xs text-[#8F91A2] mt-0.5">Jumlah deal terjual tiap bulan</p>
                </div>
                @if($monthlySales->count() > 0)
                    <span class="text-xs bg-[#111111]/10 text-[#111111] font-semibold px-3 py-1 rounded-full">
                        {{ $monthlySales->sum('count') }} total deal
                    </span>
                @endif
            </div>

            @php
                $months   = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
                $maxCount = $monthlySales->max('count') ?: 1;
            @endphp

            @if($monthlySales->count() > 0)
                <div class="flex items-end gap-1.5 h-52 pb-6 relative">
                    {{-- Y-axis lines --}}
                    @for($y = 1; $y <= 4; $y++)
                        <div class="absolute left-0 right-0 border-t border-dashed border-[#F2F2F4]"
                            style="bottom: {{ ($y / 4) * 100 }}%; left: 0; right: 0;"></div>
                    @endfor

                    @for($i = 1; $i <= 12; $i++)
                        @php
                            $data   = $monthlySales->firstWhere('month', $i);
                            $count  = $data ? $data->count : 0;
                            $height = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;
                            $isNow  = $i === (int) now()->format('n');
                        @endphp
                        <div class="flex flex-col items-center gap-1 flex-1 h-full justify-end relative z-10">
                            @if($count > 0)
                                <span class="text-[10px] font-bold text-[#060922]">{{ $count }}</span>
                            @endif
                            <div class="w-full rounded-t-lg transition-all duration-500 relative"
                                style="height: {{ max($height, $count > 0 ? 8 : 4) }}%; background: {{ $isNow ? '#060922' : ($count > 0 ? '#333333' : '#F2F2F4') }}; min-height: 4px;">{{-- bar: was #3F52FF --}}
                            </div>
                            <span class="text-[9px] font-semibold absolute bottom-0 {{ $isNow ? 'text-[#060922]' : 'text-[#8F91A2]' }}">
                                {{ $months[$i-1] }}
                            </span>
                        </div>
                    @endfor
                </div>
                <div class="flex items-center gap-4 mt-2 pt-3 border-t border-[#F2F2F4]">
                    <div class="flex items-center gap-1.5">
                        <div class="w-3 h-3 rounded bg-[#111111]"></div>
                        <span class="text-xs text-[#8F91A2]">Bulan lalu</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-3 h-3 rounded bg-[#060922]"></div>
                        <span class="text-xs text-[#8F91A2]">Bulan ini</span>
                    </div>
                </div>
            @else
                <div class="h-52 flex flex-col items-center justify-center gap-2">
                    <div class="w-16 h-16 rounded-2xl bg-[#F2F2F4] flex items-center justify-center">
                        <svg class="w-8 h-8 text-[#8F91A2]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-[#8F91A2]">Belum ada data penjualan</p>
                </div>
            @endif
        </div>

        {{-- Deal Status Breakdown --}}
        <div class="bg-white rounded-2xl border border-[#F2F2F4] p-5">
            <div class="mb-5">
                <h3 class="font-bold text-[#060922]">Konversi Transaksi</h3>
                <p class="text-xs text-[#8F91A2] mt-0.5">Status seluruh transaksi</p>
            </div>

            @php
                $total      = $totalSold + $totalInProcess + $totalFailed;
                $soldPct    = $total > 0 ? round(($totalSold / $total) * 100) : 0;
                $processPct = $total > 0 ? round(($totalInProcess / $total) * 100) : 0;
                $failedPct  = $total > 0 ? round(($totalFailed / $total) * 100) : 0;
            @endphp

            @if($total > 0)
                {{-- Big Number --}}
                <div class="text-center mb-6 py-4 rounded-2xl bg-[#F8F8FA]">
                    <p class="text-5xl font-bold text-[#060922]">{{ $soldPct }}<span class="text-2xl">%</span></p>
                    <p class="text-xs text-[#8F91A2] font-semibold mt-1">Tingkat Keberhasilan</p>
                </div>

                {{-- Stacked Progress Bar --}}
                <div class="mb-5">
                    <div class="flex rounded-full overflow-hidden h-3 gap-0.5">
                        @if($soldPct > 0)
                            <div class="h-full rounded-l-full transition-all" style="width: {{ $soldPct }}%; background: #333333;{{-- was: #3F52FF --}}"></div>
                        @endif
                        @if($processPct > 0)
                            <div class="h-full transition-all {{ $soldPct === 0 ? 'rounded-l-full' : '' }} {{ $failedPct === 0 ? 'rounded-r-full' : '' }}" style="width: {{ $processPct }}%; background: #888888;{{-- was: #FF9F47 --}}"></div>
                        @endif
                        @if($failedPct > 0)
                            <div class="h-full rounded-r-full transition-all" style="width: {{ $failedPct }}%; background: #555555;{{-- was: #FF3E3E --}}"></div>
                        @endif
                    </div>
                </div>

                {{-- Legend & Numbers --}}
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" style="background: #333333;{{-- was: #3F52FF --}}"></div>
                            <span class="text-sm text-[#8F91A2]">Terjual</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-[#8F91A2]">{{ $soldPct }}%</span>
                            <span class="text-sm font-bold text-[#060922] w-6 text-right">{{ $totalSold }}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" style="background: #888888;{{-- was: #FF9F47 --}}"></div>
                            <span class="text-sm text-[#8F91A2]">Dalam Proses</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-[#8F91A2]">{{ $processPct }}%</span>
                            <span class="text-sm font-bold text-[#060922] w-6 text-right">{{ $totalInProcess }}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" style="background: #555555;{{-- was: #FF3E3E --}}"></div>
                            <span class="text-sm text-[#8F91A2]">Gagal</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-[#8F91A2]">{{ $failedPct }}%</span>
                            <span class="text-sm font-bold text-[#060922] w-6 text-right">{{ $totalFailed }}</span>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-[#F2F2F4] flex items-center justify-between">
                        <span class="text-sm font-semibold text-[#060922]">Total</span>
                        <span class="text-sm font-bold text-[#060922]">{{ $total }}</span>
                    </div>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-10 gap-3">
                    <div class="w-14 h-14 rounded-2xl bg-[#F2F2F4] flex items-center justify-center">
                        <svg class="w-7 h-7 text-[#8F91A2]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-[#8F91A2] text-center">Belum ada data transaksi</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ── Commission Summary ─────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-[#F2F2F4] p-5 mb-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-bold text-[#060922]">Laporan Komisi</h3>
                <p class="text-xs text-[#8F91A2] mt-0.5">Rekap pembayaran komisi dari deal yang disetujui</p>
            </div>
            @if($pendingCommissionReqs > 0)
                <a href="{{ route('agent.commissions', ['tab' => 'requests']) }}"
                    class="flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full"
                    style="background: #FFF3CD; color: #92650a;">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#888888] animate-pulse inline-block"></span>
                    {{ $pendingCommissionReqs }} request pending
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
            {{-- Sudah berkomisi --}}
            <div class="rounded-2xl p-4" style="background: #F0FDF4;">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold" style="color: #333333;">Sudah Berkomisi</span>
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: #BBF7D0;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #333333;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-[#060922]">{{ $totalWithCommission }}</p>
                <p class="text-xs mt-1" style="color: #333333;">deal sudah dibayar</p>
            </div>

            {{-- Belum berkomisi --}}
            <div class="rounded-2xl p-4" style="background: #FFF7ED;">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold" style="color: #c2410c;">Belum Berkomisi</span>
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: #FED7AA;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #c2410c;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-[#060922]">{{ $totalWithoutCommission }}</p>
                <p class="text-xs mt-1" style="color: #c2410c;">deal belum dibayar</p>
            </div>

            {{-- Total Komisi Diterima --}}
            <div class="rounded-2xl p-4" style="background: linear-gradient(135deg, #EEF0FF 0%, #E8EBFF 100%);">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-[#111111]">Total Komisi Diterima</span>
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: #C7CDFF;">
                        <svg class="w-3.5 h-3.5 text-[#111111]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xl font-bold text-[#060922] leading-tight">
                    Rp {{ number_format($totalCommissionAmount, 0, ',', '.') }}
                </p>
                <p class="text-xs mt-1 text-[#111111]">total komisi dibayarkan</p>
            </div>
        </div>

        {{-- Progress bar: berkomisi vs belum --}}
        @if($totalSold > 0)
            @php $commPct = round(($totalWithCommission / $totalSold) * 100); @endphp
            <div class="flex items-center gap-3">
                <div class="flex-1 h-2 bg-[#F2F2F4] rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-700"
                        style="width: {{ $commPct }}%; background: linear-gradient(90deg, #333333, #555555);{{-- was: #3F52FF → #6b7bff --}}"></div>
                </div>
                <span class="text-xs font-bold text-[#111111] flex-shrink-0">{{ $commPct }}%</span>
                <span class="text-xs text-[#8F91A2] flex-shrink-0">sudah berkomisi</span>
            </div>
        @endif
    </div>

    {{-- ── Bottom Row ───────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Top Properties --}}
        <div class="bg-white rounded-2xl border border-[#F2F2F4] p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-[#060922]">Properti Terlaris</h3>
                    <p class="text-xs text-[#8F91A2] mt-0.5">Top 5 berdasarkan deal approved</p>
                </div>
                <div class="w-8 h-8 rounded-xl bg-[#EBEBEB] flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#4a7c15]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
            <div class="space-y-3">
                @forelse($topProperties as $idx => $prop)
                    @php
                        $badgeColors = [
                            /* was: ['bg' => '#CEF27F', 'text' => '#4a7c15'] */
                            0 => ['bg' => '#EBEBEB', 'text' => '#333333'],
                            /* was: ['bg' => '#E8EBFF', 'text' => '#3F52FF'] */
                            1 => ['bg' => '#F0F0F0', 'text' => '#333333'],
                            /* was: ['bg' => '#FFF3E0', 'text' => '#FF9F47'] */
                            2 => ['bg' => '#F0F0F0', 'text' => '#888888'],
                        ];
                        $bc = $badgeColors[$idx] ?? ['bg' => '#F2F2F4', 'text' => '#8F91A2'];
                        $maxDeals = $topProperties->max('mortgage_requests_count') ?: 1;
                        $barWidth = $maxDeals > 0 ? ($prop->mortgage_requests_count / $maxDeals) * 100 : 0;
                    @endphp
                    <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-[#F8F8FA] transition-colors">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 font-bold text-xs"
                            style="background: {{ $bc['bg'] }}; color: {{ $bc['text'] }};">
                            {{ $idx + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm text-[#060922] truncate">{{ $prop->name }}</p>
                            <div class="h-1.5 bg-[#F2F2F4] rounded-full mt-2">
                                <div class="h-full rounded-full" style="width: {{ $barWidth }}%; background: {{ $bc['text'] }};"></div>
                            </div>
                        </div>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-lg flex-shrink-0"
                            style="background: {{ $bc['bg'] }}; color: {{ $bc['text'] }};">
                            {{ $prop->mortgage_requests_count }}×
                        </span>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-10 gap-2">
                        <svg class="w-10 h-10 text-[#8F91A2]/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <p class="text-sm text-[#8F91A2]">Belum ada data properti</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Transactions --}}
        <div class="bg-white rounded-2xl border border-[#F2F2F4] p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-[#060922]">Transaksi Terbaru</h3>
                    <p class="text-xs text-[#8F91A2] mt-0.5">10 deal terbaru yang disetujui</p>
                </div>
                <div class="w-8 h-8 rounded-xl bg-[#111111]/10 flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#111111]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
            </div>
            <div class="space-y-2">
                @forelse($recentTransactions as $trx)
                    <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-[#F8F8FA] transition-colors">
                        <div class="w-9 h-9 rounded-xl bg-[#F0F0F0] flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #4a7c15;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm text-[#060922] truncate">{{ $trx->house->name ?? 'N/A' }}</p>
                            <p class="text-xs text-[#8F91A2] truncate">
                                {{ $trx->customer->nama_lengkap ?? '—' }}
                                <span class="mx-1">·</span>
                                {{ $trx->created_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-sm font-bold text-[#060922]">Rp {{ number_format($trx->house_price / 1000000, 0, ',', '.') }}jt</p>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-10 gap-2">
                        <svg class="w-10 h-10 text-[#8F91A2]/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-sm text-[#8F91A2]">Belum ada transaksi</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

@endsection
