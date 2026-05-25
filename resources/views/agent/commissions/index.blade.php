@extends('agent.layouts.app')

@section('title', 'Komisi & Pendapatan - Agent Panel')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#060922]">Komisi & Pendapatan</h1>
            <p class="text-sm text-[#8F91A2] mt-1">Ringkasan komisi, pendapatan, dan permintaan komisi Anda</p>
        </div>
        <a href="{{ route('agent.commissions.request') }}"
            class="inline-flex items-center gap-2 bg-[#111111] text-white font-semibold text-sm px-5 py-3 rounded-xl hover:bg-[#333333] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Ajukan Komisi
        </a>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="stat-card bg-[#060922] text-white p-5 rounded-2xl">
            <p class="text-xs text-white/60 mb-1">Total Komisi (All Time)</p>
            <p class="text-2xl font-bold">Rp {{ number_format($totalAllTime, 0, ',', '.') }}</p>
        </div>
        <div class="stat-card bg-[#111111] text-white p-5 rounded-2xl">
            <p class="text-xs text-white/60 mb-1">{{ $periodLabel }} Ini</p>
            <p class="text-2xl font-bold">Rp {{ number_format($totalCurrentPeriod, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-[#F2F2F4] p-5 rounded-2xl">
            <p class="text-xs text-[#8F91A2] mb-1">Jumlah Transaksi Berkomisi</p>
            <p class="text-2xl font-bold text-[#060922]">{{ $totalDeals }}</p>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="flex gap-2 mb-6 overflow-x-auto pb-1">
        <a href="{{ route('agent.commissions', ['tab' => 'income', 'period' => $period]) }}"
            class="flex-shrink-0 px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ $tab === 'income' ? 'bg-[#060922] text-white' : 'bg-white text-[#060922] border border-[#F2F2F4] hover:bg-[#F2F2F4]' }}">
            Pendapatan
        </a>
        <a href="{{ route('agent.commissions', ['tab' => 'history']) }}"
            class="flex-shrink-0 px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ $tab === 'history' ? 'bg-[#060922] text-white' : 'bg-white text-[#060922] border border-[#F2F2F4] hover:bg-[#F2F2F4]' }}">
            Riwayat Komisi
        </a>
        <a href="{{ route('agent.commissions', ['tab' => 'requests']) }}"
            class="flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ $tab === 'requests' ? 'bg-[#060922] text-white' : 'bg-white text-[#060922] border border-[#F2F2F4] hover:bg-[#F2F2F4]' }}">
            Pengajuan Komisi
            @if($pendingRequestCount > 0)
                <span class="badge-pulse bg-[#888888] text-[#060922] text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $pendingRequestCount }}</span>
            @endif
        </a>
    </div>

    {{-- ═══ TAB: INCOME ════════════════════════════════════════════════════════ --}}
    @if($tab === 'income')
        <div class="flex gap-2 mb-6">
            @foreach(['yearly' => 'Tahunan', 'monthly' => 'Bulanan', 'weekly' => 'Mingguan'] as $key => $label)
                <a href="{{ route('agent.commissions', ['tab' => 'income', 'period' => $key]) }}"
                    class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ $period === $key ? 'bg-[#060922] text-white' : 'bg-white text-[#060922] border border-[#F2F2F4] hover:bg-[#F2F2F4]' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="bg-white rounded-2xl border border-[#F2F2F4] p-5">
            <h2 class="font-bold text-[#060922] mb-4">Rincian {{ $periodLabel }}</h2>
            @if($breakdown->count() > 0)
                @php $maxTotal = $breakdown->max('total'); @endphp
                <div class="space-y-3">
                    @foreach($breakdown as $row)
                        <div class="flex items-center gap-4 p-3 rounded-xl border border-[#F2F2F4]">
                            <div class="flex-1">
                                <p class="font-semibold text-sm text-[#060922]">{{ $row->period_label }}</p>
                                <p class="text-xs text-[#8F91A2]">{{ $row->deal_count }} deal</p>
                            </div>
                            @php $pct = $maxTotal > 0 ? ($row->total / $maxTotal) * 100 : 0; @endphp
                            <div class="w-32 h-2 bg-[#F2F2F4] rounded-full overflow-hidden hidden sm:block">
                                <div class="h-full bg-[#111111] rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                            <p class="font-bold text-[#111111] text-sm flex-shrink-0">Rp {{ number_format($row->total, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10">
                    <svg class="w-12 h-12 text-[#8F91A2]/30 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <p class="text-sm text-[#8F91A2]">Belum ada data komisi untuk periode ini</p>
                </div>
            @endif
        </div>
    @endif

    {{-- ═══ TAB: RIWAYAT KOMISI ════════════════════════════════════════════════ --}}
    @if($tab === 'history')
        <div class="space-y-3">
            @forelse($commissions as $commission)
                @php $mr = $commission->mortgageRequest; @endphp
                <div class="agent-card bg-white rounded-2xl border border-[#F2F2F4] p-4 sm:p-5">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100 hidden sm:block">
                            @if($mr?->house?->thumbnail)
                                <img src="{{ Storage::url($mr->house->thumbnail) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-[#8F91A2]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="font-bold text-[#060922] truncate">{{ $mr?->house?->name ?? 'N/A' }}</h3>
                                <span class="text-[9px] font-bold px-2 py-0.5 rounded-md {{ ($mr?->payment_type ?? 'kpr') === 'cash' ? 'bg-[#111111] text-white' : 'bg-[#111111]/10 text-[#111111]' }}">
                                    {{ strtoupper($mr?->payment_type ?? 'KPR') }}
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                                <p class="text-xs text-[#8F91A2]">Pembeli: <span class="font-semibold text-[#060922]">{{ $mr?->customer?->nama_lengkap ?? 'N/A' }}</span></p>
                                <p class="text-xs text-[#8F91A2]">Harga: <span class="font-semibold text-[#060922]">Rp {{ number_format($mr?->house_price ?? 0, 0, ',', '.') }}</span></p>
                                <p class="text-xs text-[#8F91A2]">Fee 2.5%: <span class="font-semibold text-[#060922]">Rp {{ number_format(($mr?->house_price ?? 0) * 0.025, 0, ',', '.') }}</span></p>
                            </div>
                            <p class="text-[10px] text-[#8F91A2]/70 mt-1">{{ $commission->created_at->format('d M Y') }}</p>
                        </div>
                        <div class="flex-shrink-0 text-right">
                            <p class="text-xs text-[#8F91A2]">
                                @if($commission->commission_type === 'percentage')
                                    {{ $commission->commission_input }}% dari fee
                                @else
                                    Nominal langsung
                                @endif
                            </p>
                            <p class="font-bold text-lg text-[#111111]">Rp {{ number_format($commission->commission_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @if($commission->notes)
                        <div class="mt-3 pt-3 border-t border-[#F2F2F4]">
                            <p class="text-xs text-[#8F91A2]">Catatan: <span class="text-[#060922]">{{ $commission->notes }}</span></p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-16 bg-white rounded-2xl border border-[#F2F2F4]">
                    <div class="w-20 h-20 rounded-2xl bg-[#F2F2F4] flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-[#8F91A2]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-[#060922] text-lg mb-1">Belum ada komisi</h3>
                    <p class="text-sm text-[#8F91A2]">Komisi masuk setelah diproses oleh atasan</p>
                </div>
            @endforelse
        </div>
        @if($commissions->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $commissions->appends(['tab' => 'history'])->links() }}
            </div>
        @endif
    @endif

    {{-- ═══ TAB: REQUEST KOMISI ════════════════════════════════════════════════ --}}
    @if($tab === 'requests')
        <div class="space-y-3">
            @forelse($commissionRequests as $req)
                @php $mr = $req->mortgageRequest; @endphp
                <div class="agent-card bg-white rounded-2xl border border-[#F2F2F4] p-4 sm:p-5">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                <h3 class="font-bold text-[#060922] truncate">{{ $mr?->house?->name ?? 'N/A' }}</h3>
                                @if($req->status === 'pending')
                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded-md bg-[#888888]/15 text-[#888888]">MENUNGGU</span>
                                @elseif($req->status === 'processed')
                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded-md bg-[#111111] text-white">DIPROSES</span>
                                @else
                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded-md bg-red-100 text-[#444444]">DITOLAK</span>
                                @endif
                            </div>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                                <p class="text-xs text-[#8F91A2]">Pembeli: <span class="font-semibold text-[#060922]">{{ $mr?->customer?->nama_lengkap ?? 'N/A' }}</span></p>
                                <p class="text-xs text-[#8F91A2]">Harga: <span class="font-semibold text-[#060922]">Rp {{ number_format($mr?->house_price ?? 0, 0, ',', '.') }}</span></p>
                                <p class="text-xs text-[#8F91A2]">Dikirim: <span class="text-[#060922]">{{ $req->created_at->format('d M Y') }}</span></p>
                            </div>
                            @if($req->notes)
                                <p class="text-xs text-[#8F91A2] mt-1">Catatan: <span class="text-[#060922]">{{ $req->notes }}</span></p>
                            @endif
                            @if($req->isRejected() && $req->rejection_reason)
                                <div class="mt-2 p-2 bg-red-50 rounded-lg border border-red-100">
                                    <p class="text-xs text-[#444444]"><span class="font-semibold">Alasan penolakan:</span> {{ $req->rejection_reason }}</p>
                                </div>
                            @endif
                        </div>
                        @if($req->isProcessed() && $mr?->commission)
                            <div class="text-right flex-shrink-0">
                                <p class="text-xs text-[#8F91A2]">Komisi diterima</p>
                                <p class="font-bold text-lg text-[#111111]">Rp {{ number_format($mr->commission->commission_amount, 0, ',', '.') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-white rounded-2xl border border-[#F2F2F4]">
                    <div class="w-20 h-20 rounded-2xl bg-[#F2F2F4] flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-[#8F91A2]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <h3 class="font-bold text-[#060922] text-lg mb-1">Belum ada pengajuan komisi</h3>
                    <p class="text-sm text-[#8F91A2] mb-4">Ajukan komisi untuk transaksi yang sudah ACC bank</p>
                    <a href="{{ route('agent.commissions.request') }}"
                        class="inline-flex items-center gap-2 bg-[#060922] text-white font-semibold text-sm px-5 py-3 rounded-xl hover:bg-[#060922]/90 transition-colors">
                        + Ajukan Komisi
                    </a>
                </div>
            @endforelse
        </div>
        @if($commissionRequests->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $commissionRequests->appends(['tab' => 'requests'])->links() }}
            </div>
        @endif
    @endif

@endsection
