@extends('agent.layouts.app')

@section('title', 'Laporan Agent - Agent Panel')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#060922]">Laporan Agent</h1>
            <p class="text-sm text-[#8F91A2] mt-1">Ringkasan performa dan statistik penjualan</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-[#8F91A2] bg-white px-4 py-2 rounded-xl">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span>{{ now()->translatedFormat('F Y') }}</span>
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-[#F2F2F4]">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-[#060922]/10 flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#060922]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <span class="text-xs text-[#8F91A2] font-semibold">Listings</span>
            </div>
            <p class="text-2xl font-bold text-[#060922]">{{ $totalListings }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-[#F2F2F4]">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-[#3F52FF]/10 flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#3F52FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs text-[#8F91A2] font-semibold">Terjual</span>
            </div>
            <p class="text-2xl font-bold text-[#060922]">{{ $totalSold }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-[#F2F2F4]">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-[#FF9F47]/10 flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#FF9F47]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs text-[#8F91A2] font-semibold">Proses</span>
            </div>
            <p class="text-2xl font-bold text-[#060922]">{{ $totalInProcess }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-[#F2F2F4]">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-[#FF3E3E]/10 flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#FF3E3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs text-[#8F91A2] font-semibold">Gagal</span>
            </div>
            <p class="text-2xl font-bold text-[#060922]">{{ $totalFailed }}</p>
        </div>
        <div class="col-span-2 lg:col-span-1 bg-gradient-to-br from-[#3F52FF] to-[#2a3bcc] text-white p-5 rounded-2xl">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs text-white/70 font-semibold">Pendapatan</span>
            </div>
            <p class="text-lg font-bold">Rp {{ number_format($totalRevenue, 0, '', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Sales Chart --}}
        <div class="lg:col-span-2 bg-white rounded-2xl p-5 border border-[#F2F2F4]">
            <h3 class="font-bold text-[#060922] mb-4">Penjualan per Bulan</h3>
            @if($monthlySales->count() > 0)
                <div class="relative h-64" id="chartContainer">
                    @php
                        $maxCount = $monthlySales->max('count') ?: 1;
                        $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
                    @endphp
                    <div class="flex items-end justify-around h-full gap-2 px-4 pb-8">
                        @for($i = 1; $i <= 12; $i++)
                            @php
                                $data = $monthlySales->firstWhere('month', $i);
                                $count = $data ? $data->count : 0;
                                $height = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;
                            @endphp
                            <div class="flex flex-col items-center gap-2 flex-1">
                                <span class="text-xs font-bold text-[#060922]">{{ $count }}</span>
                                <div class="w-full rounded-t-lg transition-all duration-700 {{ $count > 0 ? 'bg-[#3F52FF]' : 'bg-[#F2F2F4]' }}" style="height: {{ max($height, 4) }}%; min-height: 8px;"></div>
                                <span class="text-[10px] text-[#8F91A2] font-semibold">{{ $months[$i-1] }}</span>
                            </div>
                        @endfor
                    </div>
                </div>
            @else
                <div class="h-64 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-12 h-12 text-[#8F91A2]/20 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <p class="text-sm text-[#8F91A2]">Belum ada data penjualan</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Conversion Rate --}}
        <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
            <h3 class="font-bold text-[#060922] mb-4">Tingkat Konversi</h3>
            @php
                $total = $totalSold + $totalInProcess + $totalFailed;
                $soldPct = $total > 0 ? round(($totalSold / $total) * 100) : 0;
                $processPct = $total > 0 ? round(($totalInProcess / $total) * 100) : 0;
                $failedPct = $total > 0 ? round(($totalFailed / $total) * 100) : 0;
            @endphp
            <div class="flex justify-center mb-6">
                <div class="relative w-40 h-40">
                    <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                        <circle cx="18" cy="18" r="15.5" fill="none" stroke="#F2F2F4" stroke-width="3"/>
                        @if($soldPct > 0)
                            <circle cx="18" cy="18" r="15.5" fill="none" stroke="#3F52FF" stroke-width="3"
                                stroke-dasharray="{{ $soldPct }} {{ 100 - $soldPct }}" stroke-dashoffset="0"
                                stroke-linecap="round"/>
                        @endif
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center flex-col">
                        <span class="text-3xl font-bold text-[#060922]">{{ $soldPct }}%</span>
                        <span class="text-[10px] text-[#8F91A2]">Tingkat Terjual</span>
                    </div>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-[#3F52FF]"></div>
                        <span class="text-sm text-[#8F91A2]">Terjual</span>
                    </div>
                    <span class="text-sm font-bold text-[#060922]">{{ $soldPct }}%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-[#FF9F47]"></div>
                        <span class="text-sm text-[#8F91A2]">Dalam Proses</span>
                    </div>
                    <span class="text-sm font-bold text-[#060922]">{{ $processPct }}%</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-[#FF3E3E]"></div>
                        <span class="text-sm text-[#8F91A2]">Gagal</span>
                    </div>
                    <span class="text-sm font-bold text-[#060922]">{{ $failedPct }}%</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        {{-- Top Properties --}}
        <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
            <h3 class="font-bold text-[#060922] mb-4">Properti Terlaris</h3>
            <div class="space-y-3">
                @forelse($topProperties as $idx => $prop)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg {{ $idx === 0 ? 'bg-[#CEF27F]' : ($idx === 1 ? 'bg-[#3F52FF]/10' : 'bg-[#F8F8FA]') }} flex items-center justify-center flex-shrink-0">
                            <span class="text-xs font-bold {{ $idx === 0 ? 'text-[#060922]' : ($idx === 1 ? 'text-[#3F52FF]' : 'text-[#8F91A2]') }}">#{{ $idx + 1 }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm text-[#060922] truncate">{{ $prop->name }}</p>
                            <p class="text-xs text-[#8F91A2]">Rp {{ number_format($prop->price, 0, '', '.') }}</p>
                        </div>
                        <span class="text-xs font-bold text-[#3F52FF] bg-[#3F52FF]/10 px-2 py-1 rounded-lg flex-shrink-0">{{ $prop->mortgage_requests_count }} terjual</span>
                    </div>
                @empty
                    <p class="text-sm text-[#8F91A2] text-center py-8">Belum ada data</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Transactions --}}
        <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
            <h3 class="font-bold text-[#060922] mb-4">Transaksi Terbaru</h3>
            <div class="space-y-3">
                @forelse($recentTransactions as $trx)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#CEF27F]/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm text-[#060922] truncate">{{ $trx->house->name ?? 'N/A' }}</p>
                            <p class="text-xs text-[#8F91A2]">{{ $trx->customer->nama_lengkap ?? '' }} &bull; {{ $trx->created_at->format('d M Y') }}</p>
                        </div>
                        <p class="text-sm font-bold text-[#060922] flex-shrink-0">Rp {{ number_format($trx->house_price, 0, '', '.') }}</p>
                    </div>
                @empty
                    <p class="text-sm text-[#8F91A2] text-center py-8">Belum ada transaksi</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
