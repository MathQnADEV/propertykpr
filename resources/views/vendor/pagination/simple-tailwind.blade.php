@if ($paginator->hasPages())
<nav
    role="navigation"
    aria-label="{{ __('Pagination Navigation') }}"
    style="display:flex; align-items:center; justify-content:space-between; padding:12px 0;"
>
    {{-- ← Prev --}}
    @if ($paginator->onFirstPage())
        <span style="
            display:inline-flex; align-items:center; gap:4px;
            padding:8px 14px; border-radius:10px;
            font-size:0.8125rem; font-weight:600;
            background:#F2F2F4; color:#B0B2C0;
            cursor:not-allowed;
        ">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Sebelumnya
        </span>
    @else
        <a
            href="{{ $paginator->previousPageUrl() }}"
            style="
                display:inline-flex; align-items:center; gap:4px;
                padding:8px 14px; border-radius:10px;
                font-size:0.8125rem; font-weight:600;
                background:#fff; color:#060922;
                border:1.5px solid #E8E8ED; text-decoration:none;
            "
        >
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Sebelumnya
        </a>
    @endif

    {{-- Page info --}}
    <span style="font-size:0.8125rem; color:#8F91A2; font-weight:500;">
        Halaman <strong style="color:#060922;">{{ $paginator->currentPage() }}</strong>
    </span>

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
            "
        >
            Selanjutnya
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
            cursor:not-allowed;
        ">
            Selanjutnya
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </span>
    @endif
</nav>
@endif
