@extends('agent.layouts.app')

@section('title', 'Transaksi - Agent Panel')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#060922]">Transaksi</h1>
            <p class="text-sm text-[#8F91A2] mt-1">Pantau status semua transaksi properti</p>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <a href="{{ route('agent.deals', ['filter' => 'sold']) }}" class="agent-card p-4 rounded-2xl border transition-colors {{ $filter === 'sold' ? 'bg-[#111111] text-white border-[#111111]' : 'bg-white border-[#F2F2F4] hover:border-[#111111]/30' }}">
            <div class="flex items-center justify-between mb-2">
                <svg class="w-5 h-5 {{ $filter === 'sold' ? 'text-white/70' : 'text-[#111111]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-2xl font-bold">{{ $soldCount }}</span>
            </div>
            <p class="text-xs {{ $filter === 'sold' ? 'text-white/70' : 'text-[#8F91A2]' }} font-semibold">Terjual</p>
        </a>
        <a href="{{ route('agent.deals', ['filter' => 'in_process']) }}" class="agent-card p-4 rounded-2xl border transition-colors {{ $filter === 'in_process' ? 'bg-[#888888] text-white border-[#888888]' : 'bg-white border-[#F2F2F4] hover:border-[#888888]/30' }}">
            <div class="flex items-center justify-between mb-2">
                <svg class="w-5 h-5 {{ $filter === 'in_process' ? 'text-white/70' : 'text-[#888888]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-2xl font-bold">{{ $inProcessCount }}</span>
            </div>
            <p class="text-xs {{ $filter === 'in_process' ? 'text-white/70' : 'text-[#8F91A2]' }} font-semibold">Dalam Proses</p>
        </a>
        <a href="{{ route('agent.deals', ['filter' => 'failed']) }}" class="agent-card p-4 rounded-2xl border transition-colors {{ $filter === 'failed' ? 'bg-[#444444] text-white border-[#444444]' : 'bg-white border-[#F2F2F4] hover:border-[#444444]/30' }}">
            <div class="flex items-center justify-between mb-2">
                <svg class="w-5 h-5 {{ $filter === 'failed' ? 'text-white/70' : 'text-[#444444]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
        <a href="{{ route('agent.deals', ['filter' => 'sold']) }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ $filter === 'sold' ? 'bg-[#111111] text-white' : 'bg-white text-[#060922] border border-[#F2F2F4] hover:bg-[#F2F2F4]' }}">
            Terjual
        </a>
        <a href="{{ route('agent.deals', ['filter' => 'in_process']) }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ $filter === 'in_process' ? 'bg-[#888888] text-white' : 'bg-white text-[#060922] border border-[#F2F2F4] hover:bg-[#F2F2F4]' }}">
            Dalam Proses
        </a>
        <a href="{{ route('agent.deals', ['filter' => 'failed']) }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ $filter === 'failed' ? 'bg-[#444444] text-white' : 'bg-white text-[#060922] border border-[#F2F2F4] hover:bg-[#F2F2F4]' }}">
            Gagal
        </a>
    </div>

    {{-- Deals List --}}
    <div class="space-y-3" id="infinite-list">
        @if($deals->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl border border-[#F2F2F4]">
                <div class="w-20 h-20 rounded-2xl bg-[#F2F2F4] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-[#8F91A2]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="font-bold text-[#060922] text-lg mb-1">
                    @if($filter === 'sold') Belum ada transaksi terjual
                    @elseif($filter === 'in_process') Tidak ada transaksi dalam proses
                    @elseif($filter === 'failed') Tidak ada transaksi gagal
                    @else Belum ada transaksi
                    @endif
                </h3>
                <p class="text-sm text-[#8F91A2]">Transaksi akan muncul saat pembeli mengajukan KPR</p>
            </div>
        @else
            @include('agent.deals._items')
        @endif
    </div>

    @if($deals->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $deals->withQueryString()->links() }}
        </div>
    @endif
@endsection
