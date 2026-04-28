@extends('layouts.master')

@section('title', 'Browse Properti - X-Pro')
@section('meta_description', 'Jelajahi ' . $houses->total() . ' properti pilihan dengan simulasi KPR terbaik. Filter berdasarkan kategori, kota, dan budget Anda.')

@section('content')
    <x-nav-front />

    {{-- Header --}}
    <div class="mt-[80px] md:mt-[164px] flex flex-col gap-[6px] text-center items-center px-4">
        <p class="flex items-center gap-[6px] rounded-full py-[6px] px-3 bg-white border border-tedja-border">
            <img src="{{ asset('assets/images/icons/crown.svg') }}" class="flex shrink-0 size-5" alt="icon">
            <span class="font-semibold text-sm">Temukan Hunian Impian Anda</span>
        </p>
        <h1 class="font-bold text-2xl md:text-4xl leading-tight md:leading-[54px]">Browse Semua Properti</h1>
        <p class="text-tedja-secondary font-medium text-sm md:text-base">{{ $houses->total() }} properti tersedia untuk Anda</p>
    </div>

    {{-- Filter Bar --}}
    <div class="w-full max-w-[1280px] px-4 md:px-[75px] mt-[30px] md:mt-[40px] mx-auto">
        <form method="GET" action="{{ route('front.browse') }}">
            <div class="bg-white rounded-[24px] border border-tedja-border overflow-hidden">

                {{-- Search row --}}
                <div class="flex items-center gap-3 px-4 md:px-5 py-3 md:py-4">
                    <svg class="size-5 text-tedja-secondary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama properti, lokasi..."
                        class="flex-1 text-sm bg-transparent outline-none placeholder:text-[#B0B2C0] font-medium min-w-0">
                    @if(request()->hasAny(['search','category','city']))
                        <a href="{{ route('front.browse') }}"
                            class="text-xs font-semibold text-tedja-secondary hover:text-tedja-black transition-colors shrink-0 px-2 md:px-3 py-1.5 rounded-lg hover:bg-[#F2F2F4]">
                            Atur Ulang
                        </a>
                    @endif
                    <button type="submit"
                        class="shrink-0 px-4 md:px-5 py-2.5 bg-tedja-black text-white font-semibold text-sm rounded-xl hover:bg-tedja-black/80 transition-all">
                        Cari
                    </button>
                </div>

                {{-- Filter chips row --}}
                <div class="flex items-center gap-2 md:gap-3 px-4 md:px-5 py-3 border-t border-tedja-border bg-[#FAFAFA] flex-wrap">
                    <span class="text-xs font-semibold text-tedja-secondary uppercase tracking-wide shrink-0">Filter:</span>
                    <div class="flex items-center gap-2 flex-wrap flex-1">

                        {{-- Category dropdown --}}
                        <div class="relative">
                            <select name="category"
                                class="peer appearance-none bg-none pl-3 pr-8 py-1.5 rounded-lg border text-sm bg-white focus:outline-none focus:ring-2 focus:ring-tedja-blue/20 transition-all duration-200 cursor-pointer
                                    {{ request('category') ? 'border-tedja-blue text-tedja-blue font-semibold' : 'border-tedja-border text-tedja-secondary' }}">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 transition-transform duration-200 peer-focus:rotate-180
                                    {{ request('category') ? 'text-tedja-blue' : 'text-tedja-secondary' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        {{-- City dropdown --}}
                        <div class="relative">
                            <select name="city"
                                class="peer appearance-none bg-none pl-3 pr-8 py-1.5 rounded-lg border text-sm bg-white focus:outline-none focus:ring-2 focus:ring-tedja-blue/20 transition-all duration-200 cursor-pointer
                                    {{ request('city') ? 'border-tedja-blue text-tedja-blue font-semibold' : 'border-tedja-border text-tedja-secondary' }}">
                                <option value="">Semua Kota</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                            <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 transition-transform duration-200 peer-focus:rotate-180
                                    {{ request('city') ? 'text-tedja-blue' : 'text-tedja-secondary' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                    </div>
                    @if(request()->hasAny(['search','category','city']))
                        <span class="text-xs text-tedja-secondary w-full sm:w-auto sm:ml-auto">
                            Menampilkan <span class="font-bold text-tedja-black">{{ $houses->total() }}</span> hasil
                        </span>
                    @endif
                </div>

            </div>
        </form>
    </div>

    {{-- Listings grid --}}
    <main class="w-full max-w-[1280px] px-4 md:px-[75px] mx-auto mt-[30px] md:mt-[40px] pb-[60px] md:pb-[100px]">
        @if($houses->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-[30px]">
                @foreach ($houses as $house)
                    <a href="{{ route('front.details', $house->slug) }}" class="flex">
                        <div class="flex flex-col w-full rounded-[30px] ring-1 ring-tedja-border p-[10px] pb-5 gap-3 bg-white hover:ring-2 hover:ring-tedja-blue transition-all duration-300">
                            <div class="relative w-full h-[200px] md:h-[240px] rounded-[22px] overflow-hidden">
                                <img src="{{ Storage::url($house->thumbnail) }}" class="w-full h-full object-cover" loading="lazy" alt="{{ $house->name }}">
                            </div>
                            <div class="flex flex-col gap-[14px] px-[10px] flex-1">
                                <div class="flex flex-col gap-[6px]">
                                    <h3 class="font-bold text-base md:text-lg leading-tight">{{ $house->name }}</h3>
                                    <div class="flex items-center gap-[6px]">
                                        <img src="{{ asset('assets/images/icons/location.svg') }}" class="size-5 flex shrink-0" loading="lazy" alt="icon">
                                        <p class="font-semibold text-sm text-tedja-secondary">{{ $house->city->name }}</p>
                                    </div>
                                </div>
                                <hr class="border-tedja-border">
                                <div class="grid grid-cols-2 gap-y-[10px] gap-x-3">
                                    <div class="flex items-center rounded-[12px] border border-tedja-border p-[10px] gap-[6px]">
                                        <img src="{{ asset('assets/images/icons/slider-vertical.svg') }}" class="size-5 flex shrink-0" loading="lazy" alt="icon">
                                        <p class="font-semibold text-sm">{{ $house->bedroom }} KT</p>
                                    </div>
                                    <div class="flex items-center rounded-[12px] border border-tedja-border p-[10px] gap-[6px]">
                                        <img src="{{ asset('assets/images/icons/slider-horizontal.svg') }}" class="size-5 flex shrink-0" loading="lazy" alt="icon">
                                        <p class="font-semibold text-sm">{{ $house->bathroom }} KM</p>
                                    </div>
                                    <div class="flex items-center rounded-[12px] border border-tedja-border p-[10px] gap-[6px]">
                                        <img src="{{ asset('assets/images/icons/note-favorite.svg') }}" class="size-5 flex shrink-0" loading="lazy" alt="icon">
                                        <p class="font-semibold text-sm">{{ $house->certificate }}</p>
                                    </div>
                                    <div class="flex items-center rounded-[12px] border border-tedja-border p-[10px] gap-[6px]">
                                        <img src="{{ asset('assets/images/icons/maximize-3.svg') }}" class="size-5 flex shrink-0" loading="lazy" alt="icon">
                                        <p class="font-semibold text-sm">{{ $house->land_area }} M²</p>
                                    </div>
                                </div>
                                <p class="font-bold text-tedja-blue text-base md:text-lg mt-auto">
                                    Rp {{ number_format($house->price, 0, '', '.') }}
                                </p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($houses->hasPages())
                <div class="mt-[40px] md:mt-[50px] flex justify-center">
                    {{ $houses->links() }}
                </div>
            @endif

        @else
            <div class="flex flex-col items-center gap-4 py-16 md:py-24">
                <div class="w-16 h-16 rounded-full bg-[#F2F2F4] flex items-center justify-center">
                    <img src="{{ asset('assets/images/icons/building-3.svg') }}" class="size-8 opacity-30" loading="lazy" alt="icon">
                </div>
                <p class="text-tedja-secondary font-semibold text-lg">Tidak ada properti ditemukan</p>
                <p class="text-sm text-tedja-secondary">Coba ubah filter pencarian Anda</p>
                <a href="{{ route('front.browse') }}"
                    class="mt-2 px-6 py-3 bg-tedja-black text-white font-semibold rounded-full hover:bg-tedja-black/80 transition-all text-sm">
                    Lihat Semua Properti
                </a>
            </div>
        @endif
    </main>
@endsection
