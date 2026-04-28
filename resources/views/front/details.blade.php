@extends('layouts.master')

@section('title', $houseDetails->name . ' - X-Pro')
@section('meta_description', 'Properti ' . $houseDetails->name . ' di ' . $houseDetails->city->name . '. ' . $houseDetails->bedroom . ' kamar tidur, LB ' . $houseDetails->building_area . ' m². Harga Rp ' . number_format($houseDetails->price, 0, '', '.') . '.')
@section('og_image', Storage::url($houseDetails->thumbnail))

@section('content')
    <x-nav-front />

    {{-- Hero Header --}}
    <div class="mt-[80px] md:mt-[164px] flex flex-col gap-4 md:gap-5 text-center items-center px-4">
        <p class="flex items-center gap-[6px] rounded-full py-[6px] px-3 bg-white border border-tedja-border">
            <img src="{{ asset('assets/images/icons/crown.svg') }}" class="flex shrink-0 size-5" alt="icon">
            <span class="font-semibold text-sm">Rumah Terbaik Pilihan Terpercaya</span>
        </p>
        <h1 class="font-bold text-2xl md:text-4xl leading-tight md:leading-[54px]">{{ $houseDetails->name }}</h1>
        <div class="flex flex-wrap items-center justify-center gap-3 md:gap-5">
            <div class="flex items-center gap-[6px]">
                <img src="{{ asset('assets/images/icons/location.svg') }}" class="size-5 md:size-6 flex shrink-0" alt="icon">
                <p class="font-semibold text-sm md:text-base">{{ $houseDetails->category->name }}, {{ $houseDetails->city->name }}</p>
            </div>
            <div class="flex items-center gap-[6px]">
                <img src="{{ asset('assets/images/icons/security-user.svg') }}" class="size-5 md:size-6 flex shrink-0" alt="icon">
                <p class="font-semibold text-sm md:text-base">Developer Terpercaya</p>
            </div>
        </div>
    </div>

    {{-- Gallery --}}
    <section id="Gallery" class="flex flex-col md:flex-row gap-4 md:gap-5 w-full max-w-[1280px] px-4 md:px-[75px] mt-[30px] md:mt-[50px] mx-auto">
        {{-- Main thumbnail --}}
        <button class="show-modal-btn relative group w-full md:flex-1 h-[240px] sm:h-[320px] md:h-[450px] rounded-[20px] md:rounded-[30px] overflow-hidden shrink-0">
            <img src="{{ Storage::url($houseDetails->thumbnail) }}" class="w-full h-full object-cover" alt="house thumbnail">
            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                <img src="{{ asset('assets/images/icons/eye-white-fill.svg') }}" class="size-[50px]" alt="icon">
            </div>
        </button>

        {{-- Additional photos --}}
        @if($houseDetails->photos->count())
        <div class="w-full md:w-[450px] md:shrink-0 md:h-[450px] overflow-y-auto pr-1 scrollbar-thin">
            <div class="grid grid-cols-3 md:grid-cols-2 gap-3 md:gap-5">
                @foreach ($houseDetails->photos as $photo)
                    <button class="show-modal-btn relative group w-full h-[100px] sm:h-[130px] md:h-[210px] rounded-[14px] md:rounded-[22px] overflow-hidden">
                        <img src="{{ Storage::url($photo->photo) }}" class="w-full h-full object-cover" loading="lazy" alt="house photo">
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <img src="{{ asset('assets/images/icons/eye-white-fill.svg') }}" class="size-8 md:size-[50px]" loading="lazy" alt="icon">
                        </div>
                    </button>
                @endforeach
            </div>
        </div>
        @endif
    </section>

    {{-- Specs Bar --}}
    <section id="specs" class="w-full max-w-[1280px] px-4 md:px-[75px] mt-[20px] md:mt-[30px] mx-auto">
        <div class="overflow-x-auto">
            <div class="flex items-center justify-between rounded-[20px] border border-tedja-border py-4 md:py-5 px-4 md:px-[30px] bg-white min-w-[600px]">
                <div class="flex flex-col w-fit gap-2 md:gap-3">
                    <p class="text-xs md:text-sm text-tedja-secondary">Kamar Tidur</p>
                    <div class="flex items-center gap-[6px]">
                        <img src="{{ asset('assets/images/icons/slider-vertical.svg') }}" class="size-5 md:size-6 flex shrink-0" alt="icon">
                        <p class="font-semibold text-sm md:text-base">{{ $houseDetails->bedroom }} Kamar Tidur</p>
                    </div>
                </div>
                <div class="h-[50px] md:h-[60px] border border-tedja-border"></div>
                <div class="flex flex-col w-fit gap-2 md:gap-3">
                    <p class="text-xs md:text-sm text-tedja-secondary">Kamar Mandi</p>
                    <div class="flex items-center gap-[6px]">
                        <img src="{{ asset('assets/images/icons/slider-vertical.svg') }}" class="size-5 md:size-6 flex shrink-0" alt="icon">
                        <p class="font-semibold text-sm md:text-base">{{ $houseDetails->bathroom }} Kamar Mandi</p>
                    </div>
                </div>
                <div class="h-[50px] md:h-[60px] border border-tedja-border"></div>
                <div class="flex flex-col w-fit gap-2 md:gap-3">
                    <p class="text-xs md:text-sm text-tedja-secondary">Sertifikat</p>
                    <div class="flex items-center gap-[6px]">
                        <img src="{{ asset('assets/images/icons/note-favorite.svg') }}" class="size-5 md:size-6 flex shrink-0" alt="icon">
                        <p class="font-semibold text-sm md:text-base">{{ $houseDetails->certificate }}</p>
                    </div>
                </div>
                <div class="h-[50px] md:h-[60px] border border-tedja-border"></div>
                <div class="flex flex-col w-fit gap-2 md:gap-3">
                    <p class="text-xs md:text-sm text-tedja-secondary">Luas Tanah</p>
                    <div class="flex items-center gap-[6px]">
                        <img src="{{ asset('assets/images/icons/maximize-3.svg') }}" class="size-5 md:size-6 flex shrink-0" alt="icon">
                        <p class="font-semibold text-sm md:text-base">{{ $houseDetails->land_area }} M²</p>
                    </div>
                </div>
                <div class="h-[50px] md:h-[60px] border border-tedja-border"></div>
                <div class="flex flex-col w-fit gap-2 md:gap-3">
                    <p class="text-xs md:text-sm text-tedja-secondary">Luas Bangunan</p>
                    <div class="flex items-center gap-[6px]">
                        <img src="{{ asset('assets/images/icons/building-3.svg') }}" class="size-5 md:size-6 flex shrink-0" alt="icon">
                        <p class="font-semibold text-sm md:text-base">{{ $houseDetails->building_area }} M²</p>
                    </div>
                </div>
                <div class="h-[50px] md:h-[60px] border border-tedja-border"></div>
                <div class="flex flex-col w-fit gap-2 md:gap-3">
                    <p class="text-xs md:text-sm text-tedja-secondary">Daya Listrik</p>
                    <div class="flex items-center gap-[6px]">
                        <img src="{{ asset('assets/images/icons/flash.svg') }}" class="size-5 md:size-6 flex shrink-0" alt="icon">
                        <p class="font-semibold text-sm md:text-base">{{ $houseDetails->electric }} Watt</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Details + Sidebar --}}
    <section id="Details" class="w-full flex flex-col lg:flex-row gap-8 lg:gap-[70px] max-w-[1280px] px-4 md:px-[75px] my-[40px] md:my-[50px] mx-auto">

        {{-- Left: About / Facilities / Location --}}
        <div class="flex flex-col gap-[30px] flex-1 min-w-0">
            <div id="About" class="flex flex-col gap-[14px]">
                <h2 class="font-semibold text-xl md:text-[22px] leading-[33px]">Tentang Properti</h2>
                <p class="leading-7 md:leading-8 text-sm md:text-base">{{ $houseDetails->about }}</p>
            </div>

            <div id="Nerby-Facilities" class="flex flex-col gap-[14px]">
                <h2 class="font-semibold text-xl md:text-[22px] leading-[33px]">Fasilitas Terdekat</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 md:gap-5">
                    @foreach ($houseDetails->facilities as $facility)
                        <div class="flex flex-col min-h-[120px] md:min-h-[140px] rounded-[20px] border border-tedja-border p-4 md:p-5 gap-3 md:gap-5 bg-white">
                            <img src="{{ Storage::url($facility->facility->photo) }}" class="size-7 md:size-8 flex shrink-0" loading="lazy" alt="icon">
                            <p class="font-semibold text-sm md:text-base">{{ $facility->facility->name }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div id="Location" class="flex flex-col gap-[14px]">
                <h2 class="font-semibold text-xl md:text-[22px] leading-[33px]">Lokasi Strategis</h2>
                <div class="overflow-hidden w-full h-[240px] md:h-[320px] rounded-[20px]">
                    <iframe class="h-full w-full border-0" frameborder="0"
                        src="https://www.google.com/maps/embed/v1/place?q={{ urlencode($houseDetails->name) }}&key={{ $mapsApiKey }}">
                    </iframe>
                </div>
            </div>
        </div>

        {{-- Right Sidebar: Price + Perks + Agent Contact --}}
        <div class="flex flex-col w-full lg:w-[380px] lg:shrink-0 h-fit rounded-[30px] border border-tedja-border p-5 md:p-6 gap-5 bg-white">
            {{-- Price --}}
            <p class="font-bold text-[28px] md:text-[38px] leading-tight md:leading-[57px] text-center text-tedja-blue">
                Rp {{ number_format($houseDetails->price, 0, '', '.') }}
            </p>
            <hr class="border-tedja-border">

            {{-- Perks --}}
            <div class="flex flex-col gap-3 md:gap-4">
                <div class="flex items-center gap-[6px]">
                    <img src="{{ asset('assets/images/icons/tick-circle.svg') }}" class="size-6 flex shrink-0" alt="icon">
                    <p class="font-semibold text-sm md:text-base">Dibangun developer handal</p>
                </div>
                <div class="flex items-center gap-[6px]">
                    <img src="{{ asset('assets/images/icons/tick-circle.svg') }}" class="size-6 flex shrink-0" alt="icon">
                    <p class="font-semibold text-sm md:text-base">Jaminan uang kembali 100%</p>
                </div>
                <div class="flex items-center gap-[6px]">
                    <img src="{{ asset('assets/images/icons/tick-circle.svg') }}" class="size-6 flex shrink-0" alt="icon">
                    <p class="font-semibold text-sm md:text-base">Gratis biaya balik nama</p>
                </div>
            </div>
            <hr class="border-tedja-border">

            {{-- Agent Contact --}}
            <div class="flex flex-col gap-4">
                <div>
                    <p class="font-semibold">Hubungi Agent Kami</p>
                    <p class="text-xs text-tedja-secondary mt-0.5">Tertarik? Agent kami siap membantu pengajuan KPR.</p>
                </div>
                @forelse ($agents as $agent)
                    <div class="flex items-center justify-between rounded-[16px] bg-[#F8F9FF] border border-[#E8EAFF] px-4 py-3 gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-tedja-blue flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                {{ strtoupper(substr($agent->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-sm leading-tight">{{ $agent->name }}</p>
                                <p class="text-xs text-tedja-secondary mt-0.5 truncate">
                                    {{ $agent->phone ?? 'Hubungi via email' }}
                                </p>
                            </div>
                        </div>
                        @if ($agent->phone)
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $agent->phone) }}?text={{ urlencode('Halo, saya tertarik dengan properti ' . $houseDetails->name) }}"
                                target="_blank"
                                class="flex-shrink-0 rounded-xl py-2 px-3 bg-[#25D366] text-white font-semibold text-xs flex items-center gap-1.5 hover:bg-[#1da851] transition-colors">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                WA
                            </a>
                        @endif
                    </div>
                @empty
                    <div class="rounded-[16px] bg-[#F8F8FA] border border-tedja-border px-4 py-5 text-center">
                        <p class="text-sm text-tedja-secondary">Tidak ada agent tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>

            <hr class="border-tedja-border">
            <div class="flex items-center justify-center gap-[6px]">
                <img src="{{ asset('assets/images/icons/security-safe-blue-fill.svg') }}" class="size-6 flex shrink-0" alt="icon">
                <p class="font-semibold text-sm">Semua data privasi Anda aman bersama kami</p>
            </div>
        </div>
    </section>

    {{-- KPR Calculator --}}
    @if($houseDetails->interest->count())
    <section id="KPR" class="w-full max-w-[1280px] px-4 md:px-[75px] mb-[60px] md:mb-[80px] mx-auto">
        <div class="bg-white rounded-[30px] border border-tedja-border overflow-hidden">

            {{-- Section header --}}
            <div class="flex items-center gap-4 px-5 md:px-8 py-5 md:py-6 border-b border-tedja-border bg-[#FAFAFA]">
                <div class="w-11 h-11 rounded-2xl bg-tedja-blue/10 flex items-center justify-center shrink-0">
                    <img src="{{ asset('assets/images/icons/note-favorite.svg') }}" class="size-5" alt="icon">
                </div>
                <div>
                    <h2 class="font-bold text-lg md:text-xl leading-tight">Simulasi KPR</h2>
                    <p class="text-xs md:text-sm text-tedja-secondary mt-0.5">Pilih bank dan uang muka untuk melihat estimasi cicilan bulanan</p>
                </div>
            </div>

            <div class="flex flex-col lg:grid lg:grid-cols-2 lg:divide-x divide-tedja-border">

                {{-- Left: Controls --}}
                <div class="flex flex-col gap-6 p-5 md:p-8">

                    {{-- Bank Selection --}}
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-tedja-blue text-white text-xs font-bold flex items-center justify-center shrink-0">1</span>
                            <p class="font-semibold text-sm">Pilih Bank / KPR</p>
                        </div>
                        <div class="flex flex-col gap-2" id="bank-options">
                            @foreach ($houseDetails->interest as $interest)
                                <button type="button"
                                    data-interest-id="{{ $interest->id }}"
                                    data-interest="{{ $interest->interest }}"
                                    data-duration="{{ $interest->duration }}"
                                    onclick="selectBank(this)"
                                    class="bank-btn flex items-center gap-3 rounded-[14px] border border-tedja-border px-4 py-3 text-left hover:border-tedja-blue hover:bg-[#F5F7FF] transition-all duration-200">
                                    <div class="shrink-0" style="width:40px;height:28px;overflow:hidden;display:flex;align-items:center;">
                                        <img src="{{ Storage::url($interest->bank->photo) }}"
                                            style="max-width:40px;max-height:28px;width:auto;height:auto;object-fit:contain;"
                                            loading="lazy" alt="{{ $interest->bank->name }}">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-sm leading-tight">{{ $interest->bank->name }}</p>
                                        <p class="text-xs text-tedja-secondary mt-0.5">
                                            {{ $interest->interest }}% / thn &bull; Tenor {{ $interest->duration }} thn
                                        </p>
                                    </div>
                                    <div class="w-4 h-4 rounded-full border-2 border-tedja-border shrink-0 flex items-center justify-center bank-radio transition-all duration-200"></div>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- DP Selection --}}
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-tedja-blue text-white text-xs font-bold flex items-center justify-center shrink-0">2</span>
                            <p class="font-semibold text-sm">Uang Muka (DP)</p>
                        </div>
                        <div class="grid grid-cols-4 gap-2" id="dp-options">
                            @foreach([10, 20, 30, 40, 50, 60, 70, 80] as $dp)
                                <button type="button"
                                    data-dp="{{ $dp }}"
                                    onclick="selectDp(this)"
                                    class="dp-btn rounded-[12px] border border-tedja-border py-2.5 text-sm font-bold text-center hover:bg-tedja-black hover:text-white hover:border-tedja-black transition-all duration-200">
                                    {{ $dp }}%
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Right: Result --}}
                <div class="flex flex-col p-5 md:p-8 border-t lg:border-t-0 border-tedja-border">

                    {{-- Placeholder state --}}
                    <div id="kpr-placeholder" class="flex flex-col items-center justify-center h-full gap-3 rounded-[20px] border-2 border-dashed border-tedja-border bg-[#FAFBFF] p-8">
                        <div class="w-14 h-14 rounded-full bg-[#EEF0FF] flex items-center justify-center">
                            <img src="{{ asset('assets/images/icons/note-favorite.svg') }}" class="size-6 opacity-50" alt="icon">
                        </div>
                        <div class="text-center">
                            <p class="font-semibold text-sm text-tedja-secondary">Simulasi belum aktif</p>
                            <p class="text-xs text-tedja-secondary mt-1 leading-relaxed">Pilih bank dan persentase DP<br>di sebelah kiri untuk mulai</p>
                        </div>
                    </div>

                    {{-- Result card --}}
                    <div id="kpr-result" class="hidden flex-col rounded-[20px] border border-tedja-border overflow-hidden">

                        {{-- Monthly highlight --}}
                        <div class="bg-tedja-black text-white px-6 py-5 text-center">
                            <p class="text-xs text-white/50 uppercase tracking-wider mb-2">Estimasi Cicilan / Bulan</p>
                            <p class="font-bold text-[24px] md:text-[28px] leading-tight text-tedja-green" id="res-monthly">Rp 0</p>
                        </div>

                        {{-- Detail rows --}}
                        <div class="bg-white flex flex-col divide-y divide-tedja-border">
                            <div class="flex justify-between items-center px-5 py-2.5">
                                <span class="text-xs text-tedja-secondary">Harga Properti</span>
                                <span class="font-semibold text-sm" id="res-price">-</span>
                            </div>
                            <div class="flex justify-between items-center px-5 py-2.5">
                                <span class="text-xs text-tedja-secondary">Uang Muka (DP)</span>
                                <span class="font-semibold text-sm" id="res-dp">-</span>
                            </div>
                            <div class="flex justify-between items-center px-5 py-2.5">
                                <span class="text-xs text-tedja-secondary">Total Pinjaman</span>
                                <span class="font-semibold text-sm" id="res-loan">-</span>
                            </div>
                            <div class="flex justify-between items-center px-5 py-2.5">
                                <span class="text-xs text-tedja-secondary">Bunga / Tahun</span>
                                <span class="font-semibold text-sm" id="res-interest">-</span>
                            </div>
                            <div class="flex justify-between items-center px-5 py-2.5">
                                <span class="text-xs text-tedja-secondary">Tenor</span>
                                <span class="font-semibold text-sm" id="res-duration">-</span>
                            </div>
                            <div class="flex justify-between items-center px-5 py-3 bg-[#F8F9FF]">
                                <span class="text-sm font-bold">Total Bayar (+ Bunga)</span>
                                <span class="font-bold text-sm text-tedja-blue" id="res-total">-</span>
                            </div>
                        </div>

                        <p class="text-[10px] text-tedja-secondary/60 text-center px-5 py-2.5 bg-white border-t border-tedja-border">
                            * Simulasi bersifat estimasi. Angka final ditentukan oleh pihak bank.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Modal Gallery --}}
    <div id="Gallery-Modal" class="fixed inset-0 items-center justify-center bg-tedja-black/50 flex z-30 hidden px-4">
        <div id="Modal-Content" class="rounded-[30px] md:rounded-[50px] flex flex-col gap-5 py-[30px] md:py-[40px] w-full max-w-[900px]">
            <div class="flex max-h-[70vh] overflow-hidden rounded-[20px]">
                <img src="{{ asset('assets/images/thumbnails/thumbnails-6.png') }}" class="w-full object-contain" loading="lazy" alt="thumbnail">
            </div>
            <button id="closeModal" class="px-5 mx-auto py-[14px] !w-fit bg-tedja-red rounded-full font-semibold text-white">
                Tutup
            </button>
        </div>
    </div>

@endsection

@push('after-styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
@endpush

@push('after-scripts')
    <script src="{{ asset('js/gallery.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
        const housePrice = {{ $houseDetails->price }};
        let selectedInterest = null;
        let selectedDuration = null;
        let selectedDp       = null;

        function formatRp(num) {
            return 'Rp ' + Math.round(num).toLocaleString('id-ID');
        }

        function selectBank(el) {
            document.querySelectorAll('.bank-btn').forEach(b => {
                b.style.borderColor = '';
                b.style.backgroundColor = '';
                b.querySelector('.bank-radio').innerHTML = '';
            });

            el.style.borderColor = '#3F52FF';
            el.style.backgroundColor = '#F5F7FF';
            el.querySelector('.bank-radio').innerHTML =
                '<div style="width:8px;height:8px;border-radius:50%;background:#3F52FF;flex-shrink:0;"></div>';

            selectedInterest = parseFloat(el.dataset.interest);
            selectedDuration = parseInt(el.dataset.duration);
            calculate();
        }

        function selectDp(el) {
            document.querySelectorAll('.dp-btn').forEach(b => {
                b.classList.remove('bg-tedja-black', 'text-white');
                b.style.borderColor = '';
            });

            el.classList.add('bg-tedja-black', 'text-white');
            el.style.borderColor = '#060922';

            selectedDp = parseInt(el.dataset.dp);
            calculate();
        }

        function calculate() {
            if (selectedInterest === null || selectedDp === null) return;

            const dp       = housePrice * (selectedDp / 100);
            const loan     = housePrice - dp;
            const n        = selectedDuration * 12;
            const r        = selectedInterest / 100 / 12;
            const monthly  = r > 0
                ? (loan * r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1)
                : loan / n;
            const totalPay = monthly * n;

            document.getElementById('res-price').textContent    = formatRp(housePrice);
            document.getElementById('res-dp').textContent       = formatRp(dp) + ' (' + selectedDp + '%)';
            document.getElementById('res-loan').textContent     = formatRp(loan);
            document.getElementById('res-interest').textContent = selectedInterest + '% / tahun';
            document.getElementById('res-duration').textContent = selectedDuration + ' Tahun (' + n + ' bulan)';
            document.getElementById('res-monthly').textContent  = formatRp(monthly);
            document.getElementById('res-total').textContent    = formatRp(totalPay);

            document.getElementById('kpr-placeholder').classList.add('hidden');
            const result = document.getElementById('kpr-result');
            result.classList.remove('hidden');
            result.classList.add('flex');
        }
    </script>
@endpush
