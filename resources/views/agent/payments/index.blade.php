@extends('agent.layouts.app')

@section('title', 'Pengajuan Transaksi - Agent Panel')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#060922]">Pengajuan Transaksi</h1>
            <p class="text-sm text-[#8F91A2] mt-1">Ajukan dan kelola transaksi properti</p>
        </div>
        <a href="{{ route('agent.payments.create') }}"
            class="inline-flex items-center gap-2 bg-[#111111] text-white font-semibold text-sm px-5 py-3 rounded-xl hover:bg-[#333333] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Ajukan Transaksi Baru
        </a>
    </div>

    {{-- Status Filter --}}
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('agent.payments') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ !request('status') ? 'bg-[#060922] text-white' : 'bg-white text-[#060922] border border-[#F2F2F4] hover:bg-[#F2F2F4]' }}">
            Semua
        </a>
        <a href="{{ route('agent.payments', ['status' => 'Waiting for Bank']) }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ request('status') === 'Waiting for Bank' ? 'bg-[#888888] text-white' : 'bg-white text-[#060922] border border-[#F2F2F4] hover:bg-[#F2F2F4]' }}">
            Proses Bank
        </a>
        <a href="{{ route('agent.payments', ['status' => 'Approved']) }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ request('status') === 'Approved' ? 'bg-[#111111] text-white' : 'bg-white text-[#060922] border border-[#F2F2F4] hover:bg-[#F2F2F4]' }}">
            Disetujui
        </a>
        <a href="{{ route('agent.payments', ['status' => 'Rejected']) }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ request('status') === 'Rejected' ? 'bg-[#444444] text-white' : 'bg-white text-[#060922] border border-[#F2F2F4] hover:bg-[#F2F2F4]' }}">
            Ditolak
        </a>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-2xl p-4 border border-[#F2F2F4] mb-6">
        <form method="GET" action="{{ route('agent.payments') }}" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama properti atau customer..." class="flex-1 px-4 py-2.5 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" />
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}" />
            @endif
            <button type="submit" class="px-5 py-2.5 bg-[#060922] text-white font-semibold text-sm rounded-xl hover:bg-[#060922]/90 transition-colors">Cari</button>
        </form>
    </div>

    {{-- Payment Requests List --}}
    <div class="space-y-3" id="infinite-list">
        @if($paymentRequests->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl border border-[#F2F2F4]">
                <div class="w-20 h-20 rounded-2xl bg-[#F2F2F4] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-[#8F91A2]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-[#060922] text-lg mb-1">Belum ada permintaan pembayaran</h3>
                <p class="text-sm text-[#8F91A2]">Permintaan pembayaran akan muncul di sini</p>
            </div>
        @else
            @include('agent.payments._items')
        @endif
    </div>

    @if($paymentRequests->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $paymentRequests->withQueryString()->links() }}
        </div>
    @endif
@endsection
