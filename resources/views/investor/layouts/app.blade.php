<!doctype html>
<html lang="id">

<head>
    <title>@yield('title', 'Investor Panel - X-Pro')</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/logos/XPRO-Favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/logos/XPRO-Favicon.svg') }}">
    <link href="{{ asset('css/output.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /*
         * ── B&W COLOR THEME ─────────────────────────────────────────────
         * Original → Replaced
         * #3F52FF  (blue accent)   → #111111  (black)
         * #CEF27F  (lime green)    → #111111  (buttons) / #ffffff (avatar bg / text on dark)
         * #FF9F47  (orange)        → #888888  (medium gray)
         * #FF3E3E  (red)           → #444444  (dark gray)
         * #16a34a  (green)         → #444444
         * #7c3aed  (purple)        → #555555
         * nav-item::before         → #ffffff  (was: #CEF27F)
         * ────────────────────────────────────────────────────────────────
         */
        * { font-family: 'Poppins', sans-serif; }
        body { background: #F8F8FA; }

        .investor-sidebar { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        @media (max-width: 1023px) {
            .investor-sidebar { transform: translateX(-100%); }
            .investor-sidebar.open { transform: translateX(0); }
        }

        .sidebar-overlay { transition: opacity 0.3s ease; opacity: 0; pointer-events: none; }
        .sidebar-overlay.active { opacity: 1; pointer-events: all; }

        .page-enter { animation: pageSlideIn 0.35s cubic-bezier(0.4, 0, 0.2, 1) forwards; }
        @keyframes pageSlideIn {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .stat-card { position: relative; overflow: hidden; }
        .stat-card::after {
            content: ''; position: absolute; top: -50%; right: -50%;
            width: 100%; height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
            transition: transform 0.5s ease;
        }
        .stat-card:hover::after { transform: translate(-20%, 20%); }

        .agent-card { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
        .agent-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(6,9,34,0.08); }

        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: rgba(143,145,162,0.3); border-radius: 4px; }

        .nav-item.active::before {
            content: ''; position: absolute; right: 0; top: 50%; transform: translateY(-50%);
            width: 3px; height: 60%; background: #ffffff; /* was: #CEF27F */ border-radius: 3px 0 0 3px;
        }

        .mobile-bottom-nav { box-shadow: 0 -4px 20px rgba(6,9,34,0.08); }

        .toast { animation: toastIn 0.4s cubic-bezier(0.4,0,0.2,1) forwards; }
        @keyframes toastIn {
            from { opacity: 0; transform: translateY(-20px) scale(0.95); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .toast.toast-out { animation: toastOut 0.3s ease forwards; }
        @keyframes toastOut { to { opacity: 0; transform: translateY(-20px) scale(0.95); } }

        .dropdown-menu { transform-origin: top right; transition: all 0.2s cubic-bezier(0.4,0,0.2,1); }
        .dropdown-menu.hidden { transform: scale(0.95); opacity: 0; pointer-events: none; }
    </style>
    @stack('after-styles')
</head>

<body class="min-h-screen">
    <div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 bg-black/50 z-40 lg:hidden" onclick="toggleSidebar()"></div>

    {{-- SIDEBAR --}}
    <aside id="investorSidebar" class="investor-sidebar fixed top-0 left-0 h-full w-[270px] bg-[#060922] text-white z-50 lg:translate-x-0 flex flex-col">
        <div class="flex-shrink-0 px-6 pt-8 pb-6">
            <a href="{{ route('investor.dashboard') }}" class="block">
                <img src="{{ asset('assets/images/logos/XPRO-Favicon.svg') }}" alt="XPRO" class="h-10 w-10 rounded-xl" />
            </a>
            <div class="mt-4 flex items-center gap-3 p-3 rounded-2xl bg-white/5">
                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-[#111111] font-bold text-sm"> {{-- avatar: was bg-[#CEF27F] text-[#060922] --}}
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-[#8F91A2]">Investor</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto custom-scroll px-4 pb-6">
            <p class="text-[11px] font-semibold tracking-wider text-[#8F91A2] uppercase mb-3 px-2">Menu Utama</p>
            <ul class="flex flex-col gap-1 mb-6">
                <li>
                    <a href="{{ route('investor.dashboard') }}"
                       class="nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200
                              {{ request()->routeIs('investor.dashboard') ? 'active bg-white/10 text-white font-semibold' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span class="text-sm">Statistik Penjualan</span>
                    </a>
                </li>
            </ul>

            <p class="text-[11px] font-semibold tracking-wider text-[#8F91A2] uppercase mb-3 px-2">Lainnya</p>
            <ul class="flex flex-col gap-1">
                <li>
                    <a href="{{ route('front.index') }}" class="nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/70 hover:bg-white/5 hover:text-white transition-all duration-200">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                        <span class="text-sm">Lihat Website</span>
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-item w-full relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/70 hover:bg-[#444444]/10 hover:text-[#666666] transition-all duration-200">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span class="text-sm">Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="lg:ml-[270px] min-h-screen pb-20 lg:pb-0">
        {{-- TOP BAR --}}
        <header class="sticky top-0 z-30 bg-[#F8F8FA]/80 backdrop-blur-xl px-4 lg:px-8 py-4">
            <div class="flex items-center justify-between bg-white rounded-2xl px-4 lg:px-6 py-3 shadow-sm">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 -ml-2 rounded-xl hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6 text-[#060922]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <div class="hidden md:flex items-center gap-2 text-sm text-[#8F91A2]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="font-semibold text-[#060922]">Statistik Penjualan</span>
                </div>

                <div class="flex items-center gap-3 ml-auto">
                    <div class="hidden sm:block text-right">
                        <p class="text-xs text-[#8F91A2]">Selamat datang,</p>
                        <p class="text-sm font-semibold text-[#060922]">{{ Auth::user()->name }}</p>
                    </div>
                    <div id="profileDropdown" class="relative">
                        <button onclick="toggleProfileDropdown()" class="w-10 h-10 rounded-full bg-[#060922] flex items-center justify-center text-white font-bold text-sm hover:ring-2 hover:ring-[#111111] transition-all">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </button>
                        <div id="profileMenu" class="dropdown-menu hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-lg border border-[#F2F2F4] py-2 z-50">
                            <a href="{{ route('front.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-[#060922] hover:bg-[#F8F8FA] transition-colors">
                                <svg class="w-4 h-4 text-[#8F91A2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Website
                            </a>
                            <hr class="my-1 border-[#F2F2F4]">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-[#444444] hover:bg-[#F0F0F0] transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- FLASH --}}
        @if(session('success'))
            <div id="toast" class="toast fixed top-6 right-6 z-[100] bg-[#060922] text-white px-5 py-3 rounded-2xl shadow-xl flex items-center gap-3 max-w-sm">
                <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center flex-shrink-0"> {{-- was: bg-[#CEF27F] --}}
                    <svg class="w-4 h-4 text-[#060922]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <p class="text-sm font-medium">{{ session('success') }}</p>
                <button onclick="closeToast()" class="ml-auto text-white/60 hover:text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
        @endif

        {{-- PAGE CONTENT --}}
        <main class="page-enter px-4 lg:px-8 py-2">
            @yield('content')
        </main>
    </div>

    {{-- MOBILE BOTTOM NAV --}}
    <nav class="mobile-bottom-nav fixed bottom-0 left-0 right-0 bg-white z-40 lg:hidden">
        <div class="flex items-center justify-around py-2 px-2">
            <a href="{{ route('investor.dashboard') }}"
               class="flex flex-col items-center gap-1 py-1.5 px-5 rounded-xl {{ request()->routeIs('investor.dashboard') ? 'text-[#111111]' : 'text-[#8F91A2]' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span class="text-[10px] font-semibold">Statistik</span>
            </a>
            <a href="{{ route('front.index') }}" class="flex flex-col items-center gap-1 py-1.5 px-5 rounded-xl text-[#8F91A2]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                <span class="text-[10px] font-semibold">Website</span>
            </a>
        </div>
    </nav>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('investorSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
            document.body.classList.toggle('overflow-hidden', sidebar.classList.contains('open'));
        }
        function toggleProfileDropdown() {
            document.getElementById('profileMenu').classList.toggle('hidden');
        }
        document.addEventListener('click', (e) => {
            const dd = document.getElementById('profileDropdown');
            if (dd && !dd.contains(e.target)) document.getElementById('profileMenu').classList.add('hidden');
        });
        function closeToast() {
            const t = document.getElementById('toast');
            if (t) { t.classList.add('toast-out'); setTimeout(() => t.remove(), 300); }
        }
        setTimeout(() => { const t = document.getElementById('toast'); if (t) closeToast(); }, 4000);
    </script>
    @stack('after-scripts')
</body>
</html>
