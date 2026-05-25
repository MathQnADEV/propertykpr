@extends('agent.layouts.app')

@section('title', 'Upload Proof & Docs - Agent Panel')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#060922]">Upload Proof & Docs</h1>
            <p class="text-sm text-[#8F91A2] mt-1">Upload bukti dan dokumen pendukung untuk setiap mortgage</p>
        </div>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-2xl p-4 border border-[#F2F2F4] mb-6">
        <form method="GET" action="{{ route('agent.documents') }}" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama properti..." class="flex-1 px-4 py-2.5 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" />
            <button type="submit" class="px-5 py-2.5 bg-[#060922] text-white font-semibold text-sm rounded-xl hover:bg-[#060922]/90 transition-colors">Cari</button>
        </form>
    </div>

    {{-- Mortgage List with Upload --}}
    <div class="space-y-4" id="infinite-list">
        @if($mortgages->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl border border-[#F2F2F4]">
                <div class="w-20 h-20 rounded-2xl bg-[#F2F2F4] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-[#8F91A2]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-[#060922] text-lg mb-1">Belum ada data mortgage</h3>
                <p class="text-sm text-[#8F91A2]">Dokumen akan muncul setelah ada mortgage request</p>
            </div>
        @else
            @include('agent.documents._items')
        @endif
    </div>

    @if($mortgages->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $mortgages->withQueryString()->links() }}
        </div>
    @endif
@endsection
