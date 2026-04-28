@extends('agent.layouts.app')

@section('title', 'Dashboard - Agent Panel')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#060922]">Dashboard</h1>
            <p class="text-sm text-[#8F91A2] mt-1">Selamat datang kembali, {{ $agent->name }}!</p>
        </div>
        <div class="flex items-center gap-2 text-sm text-[#8F91A2] bg-white px-4 py-2 rounded-xl">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="stat-card bg-[#060922] text-white p-5 rounded-2xl">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#CEF27F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <span class="text-xs text-[#CEF27F] font-semibold bg-[#CEF27F]/10 px-2 py-1 rounded-lg">Total</span>
            </div>
            <p class="text-2xl lg:text-3xl font-bold">{{ $totalListings }}</p>
            <p class="text-xs text-white/60 mt-1">Listings Aktif</p>
        </div>
        <div class="stat-card bg-[#3F52FF] text-white p-5 rounded-2xl">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs font-semibold bg-white/10 px-2 py-1 rounded-lg">Terjual</span>
            </div>
            <p class="text-2xl lg:text-3xl font-bold">{{ $totalSold }}</p>
            <p class="text-xs text-white/60 mt-1">Deals Selesai</p>
        </div>
        <div class="stat-card bg-[#FF9F47] text-white p-5 rounded-2xl">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs font-semibold bg-white/10 px-2 py-1 rounded-lg">Proses</span>
            </div>
            <p class="text-2xl lg:text-3xl font-bold">{{ $totalInProcess }}</p>
            <p class="text-xs text-white/60 mt-1">Dalam Proses</p>
        </div>
        <div class="stat-card bg-[#FF3E3E] text-white p-5 rounded-2xl">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs font-semibold bg-white/10 px-2 py-1 rounded-lg">Gagal</span>
            </div>
            <p class="text-2xl lg:text-3xl font-bold">{{ $totalFailed }}</p>
            <p class="text-xs text-white/60 mt-1">Deals Gagal</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Recent Listings --}}
        <div class="lg:col-span-2 bg-white rounded-2xl p-5 border border-[#F2F2F4]">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-bold text-lg text-[#060922]">Listing Terbaru</h2>
                <a href="{{ route('agent.listings') }}" class="text-sm text-[#3F52FF] font-semibold hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-3">
                @forelse($recentListings as $listing)
                    <div class="agent-card flex items-center gap-4 p-3 rounded-xl border border-[#F2F2F4] hover:border-[#3F52FF]/20 cursor-pointer" onclick="window.location='{{ route('agent.listings.edit', $listing) }}'">
                        <div class="w-14 h-14 lg:w-16 lg:h-16 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                            @if($listing->thumbnail)
                                <img src="{{ Storage::url($listing->thumbnail) }}" alt="{{ $listing->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[#8F91A2]">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-sm text-[#060922] truncate">{{ $listing->name }}</h3>
                            <p class="text-xs text-[#8F91A2] mt-0.5">{{ $listing->category->name ?? '-' }} &bull; {{ $listing->city->name ?? '-' }}</p>
                        </div>
                        <p class="text-sm font-bold text-[#3F52FF] hidden sm:block">Rp {{ number_format($listing->price, 0, '', '.') }}</p>
                        <svg class="w-5 h-5 text-[#8F91A2] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-[#8F91A2]/30 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <p class="text-sm text-[#8F91A2]">Belum ada listing</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Deals --}}
        <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-bold text-lg text-[#060922]">Deals Terbaru</h2>
                <a href="{{ route('agent.deals') }}" class="text-sm text-[#3F52FF] font-semibold hover:underline">Semua</a>
            </div>
            <div class="space-y-3">
                @forelse($recentDeals as $deal)
                    <div class="agent-card flex items-center gap-3 p-3 rounded-xl border border-[#F2F2F4] hover:border-[#3F52FF]/20 cursor-pointer" onclick="window.location='{{ route('agent.deals.show', $deal) }}'">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-sm text-[#060922] truncate">{{ $deal->house->name ?? 'N/A' }}</h3>
                            <p class="text-xs text-[#8F91A2] mt-0.5">{{ $deal->customer->nama_lengkap ?? 'N/A' }}</p>
                        </div>
                        @if($deal->status === 'Approved')
                            <span class="text-[10px] font-semibold bg-[#3F52FF] text-white px-2 py-1 rounded-lg flex-shrink-0">Terjual</span>
                        @elseif($deal->status === 'Waiting for Bank')
                            <span class="text-[10px] font-semibold bg-[#FF9F47] text-white px-2 py-1 rounded-lg flex-shrink-0">Proses</span>
                        @else
                            <span class="text-[10px] font-semibold bg-[#FF3E3E] text-white px-2 py-1 rounded-lg flex-shrink-0">Gagal</span>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-[#8F91A2]/30 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <p class="text-sm text-[#8F91A2]">Belum ada deals</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="mt-6 bg-white rounded-2xl p-5 border border-[#F2F2F4]">
        <h2 class="font-bold text-lg text-[#060922] mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <a href="{{ route('agent.listings.create') }}" class="agent-card flex flex-col items-center gap-3 p-5 rounded-xl border border-[#F2F2F4] hover:border-[#CEF27F] hover:bg-[#CEF27F]/5 text-center group">
                <div class="w-12 h-12 rounded-xl bg-[#CEF27F]/20 flex items-center justify-center group-hover:bg-[#CEF27F]/30 transition-colors">
                    <svg class="w-6 h-6 text-[#060922]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <span class="text-sm font-semibold text-[#060922]">Unggah Listing</span>
            </a>
            <a href="{{ route('agent.payments') }}" class="agent-card flex flex-col items-center gap-3 p-5 rounded-xl border border-[#F2F2F4] hover:border-[#3F52FF] hover:bg-[#3F52FF]/5 text-center group">
                <div class="w-12 h-12 rounded-xl bg-[#3F52FF]/10 flex items-center justify-center group-hover:bg-[#3F52FF]/15 transition-colors">
                    <svg class="w-6 h-6 text-[#3F52FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <span class="text-sm font-semibold text-[#060922]">Permintaan Pembayaran</span>
            </a>
            <a href="{{ route('agent.documents') }}" class="agent-card flex flex-col items-center gap-3 p-5 rounded-xl border border-[#F2F2F4] hover:border-[#FF9F47] hover:bg-[#FF9F47]/5 text-center group">
                <div class="w-12 h-12 rounded-xl bg-[#FF9F47]/10 flex items-center justify-center group-hover:bg-[#FF9F47]/15 transition-colors">
                    <svg class="w-6 h-6 text-[#FF9F47]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <span class="text-sm font-semibold text-[#060922]">Upload Bukti</span>
            </a>
            <a href="{{ route('agent.reports') }}" class="agent-card flex flex-col items-center gap-3 p-5 rounded-xl border border-[#F2F2F4] hover:border-[#060922] hover:bg-[#060922]/5 text-center group">
                <div class="w-12 h-12 rounded-xl bg-[#060922]/10 flex items-center justify-center group-hover:bg-[#060922]/15 transition-colors">
                    <svg class="w-6 h-6 text-[#060922]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <span class="text-sm font-semibold text-[#060922]">Lihat Report</span>
            </a>
        </div>
    </div>
@endsection
