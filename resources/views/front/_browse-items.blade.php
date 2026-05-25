@foreach($houses as $house)
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
