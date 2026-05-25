@if ($paginator->hasPages())
<nav
    role="navigation"
    aria-label="{{ __('Pagination Navigation') }}"
    style="display:flex; flex-direction:column; align-items:center; gap:12px; padding:16px 0;"
>
    {{-- Info teks --}}
    <p style="font-size:0.8rem; color:#8F91A2;">
        Menampilkan
        <span style="font-weight:700; color:#060922;">{{ $paginator->firstItem() }}</span>
        –
        <span style="font-weight:700; color:#060922;">{{ $paginator->lastItem() }}</span>
        dari
        <span style="font-weight:700; color:#060922;">{{ $paginator->total() }}</span>
        data
    </p>

    {{-- Tombol navigasi --}}
    <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap; justify-content:center;">

        {{-- ← Prev --}}
        @if ($paginator->onFirstPage())
            <span style="
                display:inline-flex; align-items:center; gap:4px;
                padding:8px 14px; border-radius:10px;
                font-size:0.8125rem; font-weight:600;
                background:#F2F2F4; color:#B0B2C0;
                cursor:not-allowed; user-select:none;
            ">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Prev
            </span>
        @else
            <a
                href="{{ $paginator->previousPageUrl() }}"
                style="
                    display:inline-flex; align-items:center; gap:4px;
                    padding:8px 14px; border-radius:10px;
                    font-size:0.8125rem; font-weight:600;
                    background:#fff; color:#060922;
                    border:1.5px solid #E8E8ED;
                    text-decoration:none;
                    transition:background 0.15s;
                "
                onmouseover="this.style.background='#F2F2F4'"
                onmouseout="this.style.background='#fff'"
            >
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Prev
            </a>
        @endif

        {{-- Nomor halaman --}}
        @foreach ($elements as $element)

            {{-- "..." separator --}}
            @if (is_string($element))
                <span style="
                    display:inline-flex; align-items:center; justify-content:center;
                    width:36px; height:36px; border-radius:10px;
                    font-size:0.8125rem; font-weight:600; color:#8F91A2;
                ">
                    {{ $element }}
                </span>
            @endif

            {{-- Nomor halaman --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        {{-- Halaman aktif --}}
                        <span aria-current="page" style="
                            display:inline-flex; align-items:center; justify-content:center;
                            width:36px; height:36px; border-radius:10px;
                            font-size:0.8125rem; font-weight:700;
                            background:#060922; color:#fff;
                        ">
                            {{ $page }}
                        </span>
                    @else
                        <a
                            href="{{ $url }}"
                            style="
                                display:inline-flex; align-items:center; justify-content:center;
                                width:36px; height:36px; border-radius:10px;
                                font-size:0.8125rem; font-weight:600;
                                background:#fff; color:#060922;
                                border:1.5px solid #E8E8ED;
                                text-decoration:none;
                                transition:background 0.15s;
                            "
                            onmouseover="this.style.background='#F2F2F4'"
                            onmouseout="this.style.background='#fff'"
                        >
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif

        @endforeach

        {{-- → Next --}}
        @if ($paginator->hasMorePages())
            <a
                href="{{ $paginator->nextPageUrl() }}"
                style="
                    display:inline-flex; align-items:center; gap:4px;
                    padding:8px 14px; border-radius:10px;
                    font-size:0.8125rem; font-weight:600;
                    background:#060922; color:#fff;
                    text-decoration:none;
                    transition:opacity 0.15s;
                "
                onmouseover="this.style.opacity='0.85'"
                onmouseout="this.style.opacity='1'"
            >
                Next
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @else
            <span style="
                display:inline-flex; align-items:center; gap:4px;
                padding:8px 14px; border-radius:10px;
                font-size:0.8125rem; font-weight:600;
                background:#F2F2F4; color:#B0B2C0;
                cursor:not-allowed; user-select:none;
            ">
                Next
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        @endif

    </div>
</nav>
@endif
