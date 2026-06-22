@extends('agent.layouts.app')

@section('title', 'Lihat Property - Agent Panel')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-[#060922]">Lihat Property</h1>
    <p class="text-sm text-[#8F91A2] mt-1">Jelajahi semua properti yang tersedia</p>
</div>

<div class="bg-white rounded-2xl p-4 border border-[#F2F2F4] mb-6">
    <form method="GET" action="{{ route('agent.property-browse') }}" class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari properti..." class="w-full px-4 py-2.5 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" />
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
        <button type="submit" class="px-5 py-2.5 bg-[#060922] text-white font-semibold text-sm rounded-xl hover:bg-[#060922]/90 transition-colors">Cari</button>
    </form>
</div>

@if($properties->isEmpty())
    <div class="text-center py-16 bg-white rounded-2xl border border-[#F2F2F4]">
        <div class="w-20 h-20 rounded-2xl bg-[#F2F2F4] flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-[#8F91A2]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        </div>
        <h3 class="font-bold text-[#060922] text-lg mb-1">Belum ada properti</h3>
        <p class="text-sm text-[#8F91A2]">Properti akan muncul di sini setelah ditambahkan</p>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($properties as $property)
            <div class="agent-card bg-white rounded-2xl border border-[#F2F2F4] overflow-hidden group flex flex-col">
                <a href="{{ route('agent.listings.show', $property) }}" class="block relative h-48 overflow-hidden">
                    @if($property->thumbnail)
                        <img src="{{ Storage::url($property->thumbnail) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                    @else
                        <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                            <svg class="w-12 h-12 text-[#8F91A2]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                    <div class="absolute top-3 left-3">
                        <span class="bg-white/90 backdrop-blur-sm text-[#060922] text-[10px] font-semibold px-2.5 py-1 rounded-lg">{{ $property->certificate }}</span>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-3">
                        <p class="text-lg font-bold text-white">Rp {{ number_format($property->price, 0, ',', '.') }}</p>
                    </div>
                </a>
                <div class="p-4 flex flex-col flex-1">
                    <a href="{{ route('agent.listings.show', $property) }}" class="block">
                        <h3 class="font-bold text-[#060922] truncate hover:text-[#111111]">{{ $property->name }}</h3>
                    </a>
                    <p class="text-xs text-[#8F91A2] mt-1">{{ $property->category->name ?? '' }} • {{ $property->city->name ?? '' }}</p>
                    <div class="flex items-center gap-3 mt-3 text-xs text-[#8F91A2]">
                        <span>🛏 {{ $property->bedroom ?: 0 }} KT</span>
                        <span>🚿 {{ $property->bathroom ?: 0 }} KM</span>
                        <span>📐 {{ $property->land_area ?: 0 }} m²</span>
                    </div>
                    <div class="flex items-center gap-2 mt-3 pt-3 border-t border-[#F2F2F4]">
                        <div class="w-6 h-6 rounded-full bg-[#060922] flex items-center justify-center text-white text-[10px] font-bold">{{ strtoupper(substr($property->agent->name ?? 'A', 0, 1)) }}</div>
                        <span class="text-xs text-[#8F91A2]">{{ $property->agent->name ?? 'N/A' }}</span>
                        @if($property->agent_id !== auth()->id())
                            <a href="{{ route('agent.payments.create', ['house_id' => $property->id]) }}" class="ml-auto inline-flex items-center gap-1 text-[10px] font-semibold text-white bg-[#111111] px-3 py-1.5 rounded-lg hover:bg-[#333333] transition-colors">
                                Ajukan Transaksi
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-6">
        {{ $properties->links() }}
    </div>
@endif
@endsection
