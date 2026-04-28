@extends('layouts.master')

@section('title', 'Search House ProperyKpr')

@section('content')
    <x-nav-front />
    <div class="mt-[80px] md:mt-[164px] flex flex-col gap-[6px] text-center items-center px-4">
        <h1 class="font-bold text-2xl md:text-4xl leading-tight md:leading-[54px]">{{ $category->name }} in {{ $city->name }} City</h1>
        <div class="flex items-center gap-[6px]">
            <img src="{{ asset('assets/images/icons/building-3.svg') }}" class="size-6 flex shrink-0" alt="icon">
            <p class="font-semibold text-sm md:text-base">Available {{ $houses->count() }} House Properties</p>
        </div>
    </div>
    <main class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 w-full max-w-[1280px] px-4 md:px-[75px] gap-4 md:gap-[30px] mx-auto my-[40px] md:my-[50px]">
        @forelse ($houses as $house)
            <a href="{{ route('front.details', $house->slug) }}" class="card">
                <div
                    class="flex flex-col rounded-[30px] ring-1 ring-tedja-border p-[10px] pb-5 gap-3 bg-white hover:ring-2 hover:ring-tedja-blue transition-all duration-300">
                    <div class="thumbnail-container relative w-full h-[200px] md:h-[240px] rounded-[30px] overflow-hidden">
                        <img src="{{ Storage::url($house->thumbnail) }}" class="w-full h-full object-cover"
                            alt="thumbnail">
                        <button class="absolute top-5 right-5">
                            <img src="{{ asset('assets/images/icons/heart-white-fill.svg') }}" class="size-[50px] flex shrink-0"
                                alt="icon whishlist">
                        </button>
                    </div>
                    <div class="flex flex-col gap-[14px] px-[10px]">
                        <div class="flex flex-col gap-[6px]">
                            <h3 class="font-bold text-base md:text-lg">{{ $house->name }}</h3>
                            <div class="flex items-center gap-[6px]">
                                <img src="{{ asset('assets/images/icons/location.svg') }}" class="size-5 flex shrink-0" alt="icon">
                                <p class="font-semibold text-sm">{{ $house->city->name }}</p>
                            </div>
                        </div>
                    </div>
                    <hr class="border-tedja-border">
                    <div class="grid grid-cols-2 gap-y-[10px] gap-x-3">
                        <div class="flex items-center rounded-[14px] border border-tedja-border p-[10px] gap-[6px]">
                            <img src="{{ asset('assets/images/icons/slider-vertical.svg') }}" class="size-5 flex shrink-0"
                                alt="icon">
                            <p class="font-semibold text-sm">{{ $house->bedroom }} Bedrooms</p>
                        </div>
                        <div class="flex items-center rounded-[14px] border border-tedja-border p-[10px] gap-[6px]">
                            <img src="{{ asset('assets/images/icons/slider-horizontal.svg') }}" class="size-5 flex shrink-0"
                                alt="icon">
                            <p class="font-semibold text-sm">{{ $house->bathroom }} Bathrooms</p>
                        </div>
                        <div class="flex items-center rounded-[14px] border border-tedja-border p-[10px] gap-[6px]">
                            <img src="{{ asset('assets/images/icons/note-favorite.svg') }}" class="size-5 flex shrink-0"
                                alt="icon">
                            <p class="font-semibold text-sm">{{ $house->certificate }}</p>
                        </div>
                        <div class="flex items-center rounded-[14px] border border-tedja-border p-[10px] gap-[6px]">
                            <img src="{{ asset('assets/images/icons/maximize-3.svg') }}" class="size-5 flex shrink-0"
                                alt="icon">
                            <p class="font-semibold text-sm">{{ $house->land_area }} M²</p>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full flex flex-col items-center gap-4 py-16">
                <p class="text-tedja-secondary font-semibold text-lg">Belum ada data rumah sesuai kriteria</p>
            </div>
        @endforelse
    </main>
@endsection
