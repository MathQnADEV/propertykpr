<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan — {{ config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/logos/XPRO-Favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#F8F9FF] min-h-screen flex items-center justify-center px-4">

    <div class="flex flex-col items-center text-center max-w-md w-full">

        {{-- Angka 404 --}}
        <p class="text-[120px] sm:text-[160px] font-black text-[#060922]/5 leading-none select-none">404</p>

        {{-- Logo XPRO --}}
        <img src="{{ asset('assets/images/logos/XPRO-Favicon.svg') }}"
             alt="X-Pro Logo"
             class="w-12 h-12 -mt-4 mb-6">

        {{-- Pesan --}}
        <h1 class="font-bold text-2xl sm:text-3xl text-[#060922] mb-3">
            Halaman Tidak Ditemukan
        </h1>
        <p class="text-sm sm:text-base text-[#8F91A2] leading-relaxed mb-8">
            Halaman yang kamu cari tidak ada, sudah dipindahkan, atau mungkin alamatnya salah ketik.
        </p>

        {{-- Tombol aksi --}}
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">

            @php $prev = url()->previous(); @endphp
            @if($prev && $prev !== url()->current())
                <a href="{{ $prev }}"
                   class="inline-flex items-center justify-center gap-2 bg-[#060922] text-white font-semibold text-sm px-6 py-3 rounded-xl hover:bg-[#060922]/90 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            @endif

            <a href="{{ url('/') }}"
               class="inline-flex items-center justify-center gap-2 bg-white text-[#060922] font-semibold text-sm px-6 py-3 rounded-xl border border-[#E8EAFF] hover:bg-[#F2F2F4] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Beranda
            </a>

            <a href="{{ url('/browse') }}"
               class="inline-flex items-center justify-center gap-2 bg-white text-[#060922] font-semibold text-sm px-6 py-3 rounded-xl border border-[#E8EAFF] hover:bg-[#F2F2F4] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Cari Properti
            </a>

        </div>

        {{-- Footer --}}
        <p class="mt-10 text-xs text-[#8F91A2]">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>

    </div>

</body>
</html>
