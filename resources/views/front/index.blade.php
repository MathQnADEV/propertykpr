@extends('layouts.master')

@section('title', 'Beranda - X-Pro')

@section('content')
    <x-nav-front />
    <header class="relative flex flex-col w-full min-h-[500px] md:h-[712px] pb-8 md:pb-0">
        <div class="absolute w-full h-full md:h-[650px] overflow-hidden">
            <img src="{{ asset('assets/images/backgrounds/hero-image.webp') }}" class="w-full h-full object-cover"
                alt="hero image">
            <div class="absolute w-full h-full bg-tedja-black/10"></div>
        </div>
        <div class="relative flex flex-col mt-[100px] md:mt-[244px] gap-4 md:gap-5 items-center px-4 md:px-0">
            <h1 class="font-extrabold text-[28px] md:text-[46px] leading-tight md:leading-[60px] text-center text-white">Miliki Rumah Impian</h1>
            <p class="text-sm md:text-lg leading-7 md:leading-8 text-center text-white px-2 md:px-0">
                Temukan Properti Impian Anda.
            </p>
        </div>
        <form action="{{ route('front.search') }}"
            class="relative flex flex-col md:flex-row w-[calc(100%-2rem)] md:w-full max-w-[940px] rounded-2xl md:rounded-3xl p-4 md:p-5 gap-3 md:gap-5 bg-white border border-tedja-border shadow-[0px_8px_30px_0px_#06092208] mx-4 md:mx-auto mt-5 md:mt-auto">
            <div class="flex flex-col w-full md:max-w-[241px] gap-1.5 md:gap-2">
                <p class="font-semibold text-sm md:text-base">Lokasi</p>
                <label class="relative">
                    <select name="city"
                        class="peer appearance-none bg-none outline-none w-full rounded-full ring-1 ring-tedja-black py-2.5 md:py-[14px] pl-4 md:pl-5 pr-10 md:pr-11 font-semibold text-sm invalid:font-normal focus:ring-2 focus:ring-tedja-blue transition-all duration-300 cursor-pointer"
                        required>
                        <option value="" hidden disabled selected>Pilih lokasi Anda</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                    <svg class="pointer-events-none absolute right-3 md:right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-tedja-secondary transition-transform duration-300 peer-focus:rotate-180"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </label>
            </div>
            <div class="flex flex-col w-full md:max-w-[227px] gap-1.5 md:gap-2">
                <p class="font-semibold text-sm md:text-base">Kategori</p>
                <label class="relative">
                    <select name="category"
                        class="peer appearance-none bg-none outline-none w-full rounded-full ring-1 ring-tedja-black py-2.5 md:py-[14px] pl-4 md:pl-5 pr-10 md:pr-11 font-semibold text-sm invalid:font-normal focus:ring-2 focus:ring-tedja-blue transition-all duration-300 cursor-pointer"
                        required>
                        <option value="" hidden disabled selected>Pilih Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <svg class="pointer-events-none absolute right-3 md:right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-tedja-secondary transition-transform duration-300 peer-focus:rotate-180"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </label>
            </div>
            <div class="flex flex-col w-full md:max-w-[232px] gap-1.5 md:gap-2">
                <p class="font-semibold text-sm md:text-base">Tipe Properti</p>
                <label class="relative">
                    <select name="property_type"
                        class="peer appearance-none bg-none outline-none w-full rounded-full ring-1 ring-tedja-black py-2.5 md:py-[14px] pl-4 md:pl-5 pr-10 md:pr-11 font-semibold text-sm invalid:font-normal focus:ring-2 focus:ring-tedja-blue transition-all duration-300 cursor-pointer"
                        required>
                        <option value="" hidden disabled selected>Pilih tipe properti</option>
                        <option value="1">Rumah</option>
                    </select>
                    <svg class="pointer-events-none absolute right-3 md:right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-tedja-secondary transition-transform duration-300 peer-focus:rotate-180"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </label>
            </div>
            <button type="submit"
                class="group rounded-full border py-2.5 md:py-[14px] px-5 flex items-center justify-center bg-tedja-green md:mt-auto w-full md:w-auto">
                <span class="font-semibold text-nowrap text-white">Cari Rumah</span>
            </button>
        </form>
    </header>

    <section id="Popular-Categories" class="flex flex-col w-full mt-[50px] md:mt-[70px] gap-[30px]">
        <div class="flex flex-col w-full max-w-[1280px] px-4 md:px-[75px] mx-auto">
            <div class="flex items-center justify-between">
                <div class="flex flex-col gap-1">
                    <h2 class="font-bold text-xl md:text-[26px] leading-8 md:leading-10">Kategori Populer</h2>
                    <p class="text-sm md:text-base">Temukan rumah sesuai kebutuhanmu</p>
                </div>
                <a href="{{ route('front.browse') }}"
                    class="group rounded-full border border-tedja-black py-2.5 md:py-[14px] px-4 md:px-5 hover:bg-tedja-black flex items-center transition-all duration-300">
                    <span class="font-semibold text-sm group-hover:text-white transition-all duration-300">Lihat Semua</span>
                </a>
            </div>
        </div>
        <div class="swiper w-full overflow-x-hidden">
            <div class="swiper-wrapper">
                @forelse ($categories as $category)
                    <div
                        class="swiper-slide !w-fit py-0.5 first-of-type:pl-4 last-of-type:pr-4 md:first-of-type:pl-[calc(((100%-1280px)/2)+75px)] md:last-of-type:pr-[calc(((100%-1280px)/2)+75px)]">
                        <a href="{{ route('front.category', $category->slug) }}" class="card">
                            <div
                                class="flex flex-col w-[200px] md:w-[240px] shrink-0 rounded-[30px] ring-1 ring-tedja-border bg-white p-[10px] gap-4 hover:ring-2 hover:ring-tedja-blue transition-all duration-300">
                                <div class="flex w-full h-[200px] md:h-[240px] overflow-hidden rounded-[30px]">
                                    <img src="{{ Storage::url($category->photo) }}" class="w-full h-full object-cover"
                                        loading="lazy" alt="thumbnails">
                                </div>
                                <div class="flex flex-col gap-2 pb-[10px] pl-[10px]">
                                    <h3 class="font-bold text-base md:text-lg">{{ $category->name }}</h3>
                                    <div class="flex items-center gap-[6px]">
                                        <img src="{{ asset('assets/images/icons/house-2.svg') }}"
                                            class="size-5 flex shrink-0" loading="lazy" alt="icon">
                                        <p class="font-semibold text-sm">{{ $category->available_houses_count }} Properti</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    belum ada data
                @endforelse
            </div>
        </div>
    </section>

    <section id="Testimonials" class="flex flex-col w-full max-w-[1280px] px-4 md:px-[135px] gap-[31px] mx-auto my-[50px] md:my-[70px]">
        <div class="flex flex-col gap-1 text-center">
            <h2 class="font-bold text-xl md:text-[26px] leading-8 md:leading-10">Cari Rumah</h2>
            <p>Kami hadir membantu Anda menemukan rumah idaman</p>
        </div>
    </section>
@endsection

@push('after-scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@endpush

@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{ asset('js/home.js') }}"></script>
@endpush
