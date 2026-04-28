@extends('agent.layouts.app')

@section('title', 'Deals - Agent Panel')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#060922]">Deals</h1>
            <p class="text-sm text-[#8F91A2] mt-1">Pantau status semua deals properti</p>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <a href="{{ route('agent.deals', ['filter' => 'sold']) }}" class="agent-card p-4 rounded-2xl border transition-colors {{ $filter === 'sold' ? 'bg-[#3F52FF] text-white border-[#3F52FF]' : 'bg-white border-[#F2F2F4] hover:border-[#3F52FF]/30' }}">
            <div class="flex items-center justify-between mb-2">
                <svg class="w-5 h-5 {{ $filter === 'sold' ? 'text-white/70' : 'text-[#3F52FF]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-2xl font-bold">{{ $soldCount }}</span>
            </div>
            <p class="text-xs {{ $filter === 'sold' ? 'text-white/70' : 'text-[#8F91A2]' }} font-semibold">Terjual</p>
        </a>
        <a href="{{ route('agent.deals', ['filter' => 'in_process']) }}" class="agent-card p-4 rounded-2xl border transition-colors {{ $filter === 'in_process' ? 'bg-[#FF9F47] text-white border-[#FF9F47]' : 'bg-white border-[#F2F2F4] hover:border-[#FF9F47]/30' }}">
            <div class="flex items-center justify-between mb-2">
                <svg class="w-5 h-5 {{ $filter === 'in_process' ? 'text-white/70' : 'text-[#FF9F47]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-2xl font-bold">{{ $inProcessCount }}</span>
            </div>
            <p class="text-xs {{ $filter === 'in_process' ? 'text-white/70' : 'text-[#8F91A2]' }} font-semibold">Dalam Proses</p>
        </a>
        <a href="{{ route('agent.deals', ['filter' => 'failed']) }}" class="agent-card p-4 rounded-2xl border transition-colors {{ $filter === 'failed' ? 'bg-[#FF3E3E] text-white border-[#FF3E3E]' : 'bg-white border-[#F2F2F4] hover:border-[#FF3E3E]/30' }}">
            <div class="flex items-center justify-between mb-2">
                <svg class="w-5 h-5 {{ $filter === 'failed' ? 'text-white/70' : 'text-[#FF3E3E]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-2xl font-bold">{{ $failedCount }}</span>
            </div>
            <p class="text-xs {{ $filter === 'failed' ? 'text-white/70' : 'text-[#8F91A2]' }} font-semibold">Gagal</p>
        </a>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('agent.deals') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ $filter === 'all' ? 'bg-[#060922] text-white' : 'bg-white text-[#060922] border border-[#F2F2F4] hover:bg-[#F2F2F4]' }}">
            Semua
        </a>
        <a href="{{ route('agent.deals', ['filter' => 'sold']) }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ $filter === 'sold' ? 'bg-[#3F52FF] text-white' : 'bg-white text-[#060922] border border-[#F2F2F4] hover:bg-[#F2F2F4]' }}">
            Terjual
        </a>
        <a href="{{ route('agent.deals', ['filter' => 'in_process']) }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ $filter === 'in_process' ? 'bg-[#FF9F47] text-white' : 'bg-white text-[#060922] border border-[#F2F2F4] hover:bg-[#F2F2F4]' }}">
            Dalam Proses
        </a>
        <a href="{{ route('agent.deals', ['filter' => 'failed']) }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ $filter === 'failed' ? 'bg-[#FF3E3E] text-white' : 'bg-white text-[#060922] border border-[#F2F2F4] hover:bg-[#F2F2F4]' }}">
            Gagal
        </a>
    </div>

    {{-- Deals List --}}
    <div class="space-y-3">
        @forelse($deals as $deal)
            <div class="agent-card bg-white rounded-2xl border border-[#F2F2F4] p-4 sm:p-5 cursor-pointer" onclick="window.location='{{ route('agent.deals.show', $deal) }}'">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100 hidden sm:block">
                        @if($deal->house && $deal->house->thumbnail)
                            <img src="{{ Storage::url($deal->house->thumbnail) }}" alt="" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-[#8F91A2]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-bold text-[#060922]">{{ $deal->house->name ?? 'N/A' }}</h3>
                            @if($deal->status === 'Approved')
                                <span class="flex-shrink-0 text-[10px] font-semibold bg-[#3F52FF] text-white px-3 py-1.5 rounded-lg">Terjual</span>
                            @elseif($deal->status === 'Waiting for Bank')
                                <span class="flex-shrink-0 text-[10px] font-semibold bg-[#FF9F47] text-white px-3 py-1.5 rounded-lg">Dalam Proses</span>
                            @else
                                <span class="flex-shrink-0 text-[10px] font-semibold bg-[#FF3E3E] text-white px-3 py-1.5 rounded-lg">Gagal</span>
                            @endif
                        </div>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-[#8F91A2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span class="text-xs text-[#8F91A2]">{{ $deal->customer->nama_lengkap ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-[#8F91A2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <span class="text-xs text-[#8F91A2]">{{ $deal->bank_name }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-[#8F91A2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-xs text-[#8F91A2]">{{ $deal->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-3">
                            <p class="text-lg font-bold text-[#3F52FF]">Rp {{ number_format($deal->house_price, 0, '', '.') }}</p>
                            <svg class="w-5 h-5 text-[#8F91A2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-white rounded-2xl border border-[#F2F2F4]">
                <div class="w-20 h-20 rounded-2xl bg-[#F2F2F4] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-[#8F91A2]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <h3 class="font-bold text-[#060922] text-lg mb-1">
                    @if($filter === 'sold') Belum ada deals terjual
                    @elseif($filter === 'in_process') Tidak ada deals dalam proses
                    @elseif($filter === 'failed') Tidak ada deals gagal
                    @else Belum ada deals
                    @endif
                </h3>
                <p class="text-sm text-[#8F91A2]">Deals akan muncul saat customer mengajukan mortgage</p>
            </div>
        @endforelse
    </div>

    @if($deals->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $deals->withQueryString()->links() }}
        </div>
    @endif
@endsection
