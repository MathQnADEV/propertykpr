@extends('agent.layouts.app')

@section('title', 'Pendapatan - Agent Panel')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#060922]">Pendapatan</h1>
            <p class="text-sm text-[#8F91A2] mt-1">Rekapitulasi pendapatan komisi Anda</p>
        </div>
        <a href="{{ route('agent.commissions') }}"
            class="inline-flex items-center gap-2 border border-[#F2F2F4] bg-white text-[#060922] font-semibold text-sm px-5 py-3 rounded-xl hover:bg-[#F2F2F4] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            Riwayat Komisi
        </a>
    </div>

    {{-- Period Tabs --}}
    <div class="flex gap-2 mb-6">
        @foreach(['yearly' => 'Tahunan', 'monthly' => 'Bulanan', 'weekly' => 'Mingguan'] as $key => $label)
            <a href="{{ route('agent.income', ['period' => $key]) }}"
                class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ $period === $key ? 'bg-[#060922] text-white' : 'bg-white text-[#060922] border border-[#F2F2F4] hover:bg-[#F2F2F4]' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="stat-card bg-[#060922] text-white p-5 rounded-2xl">
            <p class="text-xs text-white/60 mb-1">Total Komisi (All Time)</p>
            <p class="text-2xl font-bold">Rp {{ number_format($totalAllTime, 0, ',', '.') }}</p>
        </div>
        <div class="bg-[#111111] text-white p-5 rounded-2xl">
            <p class="text-xs text-white/60 mb-1">{{ $periodLabel }} Ini</p>
            <p class="text-2xl font-bold">Rp {{ number_format($totalCurrentPeriod, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-[#F2F2F4] p-5 rounded-2xl">
            <p class="text-xs text-[#8F91A2] mb-1">Jumlah Transaksi (All Time)</p>
            <p class="text-2xl font-bold text-[#060922]">{{ $totalDeals }}</p>
        </div>
    </div>

    {{-- Period Breakdown --}}
    <div class="bg-white rounded-2xl border border-[#F2F2F4] p-5">
        <h2 class="font-bold text-[#060922] mb-4">Rincian {{ $periodLabel }}</h2>
        @if($breakdown->count() > 0)
            <div class="space-y-3">
                @foreach($breakdown as $row)
                    <div class="flex items-center gap-4 p-3 rounded-xl border border-[#F2F2F4]">
                        <div class="flex-1">
                            <p class="font-semibold text-sm text-[#060922]">{{ $row->period_label }}</p>
                            <p class="text-xs text-[#8F91A2]">{{ $row->deal_count }} deal</p>
                        </div>
                        {{-- Progress bar relative to max --}}
                        @php $pct = $breakdown->max('total') > 0 ? ($row->total / $breakdown->max('total')) * 100 : 0; @endphp
                        <div class="w-32 h-2 bg-[#F2F2F4] rounded-full overflow-hidden hidden sm:block">
                            <div class="h-full bg-[#111111] rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                        <p class="font-bold text-[#111111] text-sm flex-shrink-0">Rp {{ number_format($row->total, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-10">
                <svg class="w-12 h-12 text-[#8F91A2]/30 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <p class="text-sm text-[#8F91A2]">Belum ada data komisi</p>
            </div>
        @endif
    </div>
@endsection
