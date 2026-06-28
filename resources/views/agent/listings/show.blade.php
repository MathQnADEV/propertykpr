@extends('agent.layouts.app')

@section('title', $house->name . ' - Detail Properti')

@section('content')
<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('agent.listings', ['view' => 'all']) }}" class="p-2 rounded-xl border border-[#F2F2F4] hover:bg-[#F2F2F4] transition-colors">
        <svg class="w-5 h-5 text-[#060922]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div class="flex-1">
        <h1 class="text-2xl font-bold text-[#060922]">{{ $house->name }}</h1>
        <p class="text-sm text-[#8F91A2] mt-0.5">{{ $house->category->name ?? '' }} • {{ $house->city->name ?? '' }}</p>
    </div>
    @if($house->agent_id !== auth()->id())
    <a href="{{ route('agent.payments.create', ['house_id' => $house->id]) }}" class="inline-flex items-center gap-2 bg-[#111111] text-white font-semibold text-sm px-5 py-3 rounded-xl hover:bg-[#333333] transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Ajukan Transaksi
    </a>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl border border-[#F2F2F4] overflow-hidden">
            @if($house->thumbnail)
                <img src="{{ Storage::url($house->thumbnail) }}" class="w-full h-64 sm:h-96 object-cover" />
            @else
                <div class="w-full h-64 bg-gray-100 flex items-center justify-center">
                    <svg class="w-16 h-16 text-[#8F91A2]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl p-6 border border-[#F2F2F4]">
        @if($house->latitude && $house->longitude)
        <div class="bg-white rounded-2xl p-6 border border-[#F2F2F4]">
            <h2 class="font-bold text-lg text-[#060922] mb-4">Lokasi</h2>
            <div class="w-full h-64 rounded-xl overflow-hidden">
                <iframe width="100%" height="100%" frameborder="0" src="https://maps.google.com/maps?q={{ $house->latitude }},{{ $house->longitude }}&z=15&output=embed"></iframe>
            </div>
        </div>
        @endif
        @if($house->maps_url)
        <div class="bg-white rounded-2xl p-6 border border-[#F2F2F4]">
            <h2 class="font-bold text-lg text-[#060922] mb-4">Lokasi</h2>
            <div class="w-full h-64 rounded-xl overflow-hidden">
                <iframe width="100%" height="100%" frameborder="0" src="{{ $house->maps_url }}" allowfullscreen></iframe>
            </div>
        </div>
        @endif
            <h2 class="font-bold text-lg text-[#060922] mb-4">Deskripsi</h2>
            <p class="text-sm text-[#8F91A2] leading-relaxed">{{ $house->about ?: 'Tidak ada deskripsi.' }}</p>
        </div>

        @if($house->facilities)
        <div class="bg-white rounded-2xl p-6 border border-[#F2F2F4]">
            <h2 class="font-bold text-lg text-[#060922] mb-4">Fasilitas</h2>
            <div class="flex flex-wrap gap-2">
                @foreach(explode(',', $house->facilities) as $f)
                    <span class="px-3 py-1.5 bg-[#F2F2F4] text-[#060922] text-xs font-medium rounded-lg">{{ trim($f) }}</span>
                @endforeach
            </div>
        </div>
        @endif

        @if($house->photos->count() > 0)
        <div class="bg-white rounded-2xl p-6 border border-[#F2F2F4]">
            <h2 class="font-bold text-lg text-[#060922] mb-4">Foto Lainnya</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                @foreach($house->photos as $photo)
                    <img src="{{ Storage::url($photo->photo) }}" class="w-full h-40 object-cover rounded-xl" />
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="space-y-4">
        <div class="bg-[#060922] text-white rounded-2xl p-6">
            <p class="text-3xl font-bold">Rp {{ number_format($house->price, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-[#F2F2F4]">
            <h2 class="font-bold text-lg text-[#060922] mb-4">Detail Properti</h2>
            <div class="space-y-3">
                <div class="flex justify-between"><span class="text-sm text-[#8F91A2]">Sertifikat</span><span class="text-sm font-semibold">{{ $house->certificate ?? '-' }}</span></div>
                <div class="flex justify-between"><span class="text-sm text-[#8F91A2]">Kamar Tidur</span><span class="text-sm font-semibold">{{ $house->bedroom ?: '-' }} Unit</span></div>
                <div class="flex justify-between"><span class="text-sm text-[#8F91A2]">Kamar Mandi</span><span class="text-sm font-semibold">{{ $house->bathroom ?: '-' }} Unit</span></div>
                <div class="flex justify-between"><span class="text-sm text-[#8F91A2]">Listrik</span><span class="text-sm font-semibold">{{ $house->electric ?: '-' }} Watts</span></div>
                <div class="flex justify-between"><span class="text-sm text-[#8F91A2]">Luas Tanah</span><span class="text-sm font-semibold">{{ $house->land_area ?: '-' }} m²</span></div>
                <div class="flex justify-between"><span class="text-sm text-[#8F91A2]">Luas Bangunan</span><span class="text-sm font-semibold">{{ $house->building_area ?: '-' }} m²</span></div>
                <div class="flex justify-between"><span class="text-sm text-[#8F91A2]">Kategori</span><span class="text-sm font-semibold">{{ $house->category->name ?? '-' }}</span></div>
                <div class="flex justify-between"><span class="text-sm text-[#8F91A2]">Kota</span><span class="text-sm font-semibold">{{ $house->city->name ?? '-' }}</span></div>
                @if($house->developer)<div class="flex justify-between"><span class="text-sm text-[#8F91A2]">Developer</span><span class="text-sm font-semibold">{{ $house->developer->name }}</span></div>@endif
                @if($house->cluster)<div class="flex justify-between"><span class="text-sm text-[#8F91A2]">Cluster</span><span class="text-sm font-semibold">{{ $house->cluster->name }}</span></div>@endif
                @if($house->type)<div class="flex justify-between"><span class="text-sm text-[#8F91A2]">Tipe</span><span class="text-sm font-semibold">{{ $house->type->name }}</span></div>@endif
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-[#F2F2F4]">
            <h2 class="font-bold text-lg text-[#060922] mb-4">Agent</h2>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-[#060922] flex items-center justify-center text-white font-bold text-sm">{{ strtoupper(substr($house->agent->name ?? 'A', 0, 1)) }}</div>
                <div>
                    <p class="font-semibold text-sm">{{ $house->agent->name ?? 'N/A' }}</p>
                    <p class="text-xs text-[#8F91A2]">{{ $house->agent->email ?? '' }}</p>
                </div>
            </div>
        </div>

        @if($house->agent_id !== auth()->id())
        <a href="{{ route('agent.payments.create', ['house_id' => $house->id]) }}" class="w-full inline-flex items-center justify-center gap-2 bg-[#111111] text-white font-semibold text-sm px-5 py-3 rounded-xl hover:bg-[#333333] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Ajukan Transaksi
        </a>
        @endif
    </div>
</div>
@endsection
