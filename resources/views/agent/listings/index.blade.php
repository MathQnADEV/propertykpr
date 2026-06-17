@extends('agent.layouts.app')

@section('title', 'Unggah Listing - Agent Panel')

@section('content')
    {{-- Style ditaruh inline di body (bukan @push) agar pasti ter-render --}}
    <style>
        /* Flexbox lebar eksplisit: deterministik, mustahil "2 lalu 3". */
        #infinite-list.agent-listings-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: stretch;
        }
        #infinite-list.agent-listings-grid > * { width: 100%; }
        @media (min-width: 640px) {
            #infinite-list.agent-listings-grid > * { width: calc((100% - 1rem) / 2); }
        }
        @media (min-width: 1024px) {
            #infinite-list.agent-listings-grid > * { width: calc((100% - 2rem) / 3); }
        }
    </style>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#060922]">{{ $viewAll ? 'Semua Listing' : 'Unggah Listing' }}</h1>
            <p class="text-sm text-[#8F91A2] mt-1">{{ $viewAll ? 'Seluruh listing dari semua agent' : 'Kelola listing properti Anda' }}</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            {{-- Toggle: Listing Saya / Semua Listing --}}
            <a href="{{ route('agent.listings') }}"
               class="inline-flex items-center gap-1.5 text-sm font-semibold px-4 py-2.5 rounded-xl border transition-colors {{ !$viewAll ? 'bg-[#060922] text-white border-[#060922]' : 'bg-white text-[#060922] border-[#F2F2F4] hover:bg-[#F2F2F4]' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Listing Saya
            </a>
            <a href="{{ route('agent.listings', ['view' => 'all']) }}"
               class="inline-flex items-center gap-1.5 text-sm font-semibold px-4 py-2.5 rounded-xl border transition-colors {{ $viewAll ? 'bg-[#060922] text-white border-[#060922]' : 'bg-white text-[#060922] border-[#F2F2F4] hover:bg-[#F2F2F4]' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                Semua Listing
            </a>
            @if(!$viewAll)
            <a href="{{ route('agent.listings.create') }}" class="inline-flex items-center gap-2 bg-[#111111] text-white font-semibold text-sm px-5 py-2.5 rounded-xl hover:bg-[#333333] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Listing Baru
            </a>
            @endif
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl p-4 border border-[#F2F2F4] mb-6">
        <form method="GET" action="{{ route('agent.listings') }}" class="flex flex-col sm:flex-row gap-3">
            @if($viewAll)
                <input type="hidden" name="view" value="all" />
                {{-- Dropdown pilih user/agent — auto-submit saat dipilih --}}
                <select name="agent" onchange="this.form.submit()" class="pl-4 pr-10 py-2.5 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] bg-white transition-all">
                    <option value="">Semua User</option>
                    @foreach($owners as $owner)
                        <option value="{{ $owner->id }}" {{ request('agent') == $owner->id ? 'selected' : '' }}>{{ $owner->name }}</option>
                    @endforeach
                </select>
            @endif
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari listing..." class="w-full px-4 py-2.5 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" />
            </div>
            <select name="category" class="pl-4 pr-10 py-2.5 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] bg-white transition-all">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="city" class="pl-4 pr-10 py-2.5 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] bg-white transition-all">
                <option value="">Semua Kota</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-5 py-2.5 bg-[#060922] text-white font-semibold text-sm rounded-xl hover:bg-[#060922]/90 transition-colors">
                Filter
            </button>
        </form>
    </div>

    {{-- Listings Grid --}}
    @if($listings->isEmpty())
        <div class="text-center py-16">
            <div class="w-20 h-20 rounded-2xl bg-[#F2F2F4] flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-[#8F91A2]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <h3 class="font-bold text-[#060922] text-lg mb-1">Belum ada listing</h3>
            <p class="text-sm text-[#8F91A2] mb-4">Mulai tambahkan listing properti pertama Anda</p>
            <a href="{{ route('agent.listings.create') }}"
                class="inline-flex items-center gap-2 bg-[#111111] text-white font-semibold text-sm px-5 py-3 rounded-xl">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Listing
            </a>
        </div>
    @else
        <div class="agent-listings-grid flex flex-wrap gap-4" id="infinite-list">
            @include('agent.listings._items')
        </div>
    @endif

    @if($listings->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $listings->withQueryString()->links() }}
        </div>
    @endif
@endsection
