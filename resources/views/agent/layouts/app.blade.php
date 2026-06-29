<!doctype html>
<html lang="id">
<head>
    <title>@yield('title', 'Agent Panel - X-Pro')</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/logos/XPRO-Favicon.svg') }}">
    <link href="{{ asset('css/output.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { background: #F8F8FA; }
        .agent-sidebar { transition: transform 0.3s; }
        @media (max-width: 1023px) { .agent-sidebar { transform: translateX(-100%); } .agent-sidebar.open { transform: translateX(0); } }
        .sidebar-overlay { transition: opacity 0.3s; opacity: 0; pointer-events: none; }
        .sidebar-overlay.active { opacity: 1; pointer-events: all; }
        .page-enter { animation: pageSlideIn 0.35s forwards; }
        @keyframes pageSlideIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        .agent-card { transition: all 0.25s; }
        .agent-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(6,9,34,0.08); }
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: rgba(143,145,162,0.3); border-radius: 4px; }
        .nav-item.active::before { content: ''; position: absolute; right: 0; top: 50%; transform: translateY(-50%); width: 3px; height: 60%; background: #ffffff; border-radius: 3px 0 0 3px; }
        .toast { animation: toastIn 0.4s forwards; }
        @keyframes toastIn { from { opacity: 0; transform: translateY(-20px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
    </style>
    @stack('after-styles')
</head>
<body class="min-h-screen">
    <div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 bg-black/50 z-40 lg:hidden" onclick="toggleSidebar()"></div>
    <aside id="agentSidebar" class="agent-sidebar fixed top-0 left-0 h-full w-[270px] bg-[#060922] text-white z-50 lg:translate-x-0 flex flex-col">
        <div class="flex-shrink-0 px-6 pt-8 pb-6">
            <a href="{{ route('agent.dashboard') }}" class="flex items-center gap-2">
                <img src="{{ asset('assets/images/logos/XPRO-Favicon.svg') }}" alt="XPRO" class="h-10 w-10 rounded-xl" />
                <span class="text-white font-bold text-lg">Xpro Property</span>
            </a>
            <div class="mt-4 flex items-center gap-3 p-3 rounded-2xl bg-white/5">
                @if(Auth::user()->photo)
                    <img src="{{ Storage::url(Auth::user()->photo) }}" class="w-10 h-10 rounded-full object-cover" />
                @else
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-[#111111] font-bold text-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                @endif
                <div>
                    <p class="font-semibold text-sm">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-[#8F91A2]">Agent</p>
                </div>
            </div>
        </div>
        <nav class="flex-1 overflow-y-auto custom-scroll px-4 pb-6">
            <p class="text-[11px] font-semibold tracking-wider text-[#8F91A2] uppercase mb-3 px-2">Menu Utama</p>
            <ul class="flex flex-col gap-1 mb-6">
                <li><a href="{{ route('agent.dashboard') }}" class="nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('agent.dashboard') ? 'active bg-white/10 text-white font-semibold' : 'text-white/70 hover:bg-white/5 hover:text-white' }}"><svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 13a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6z"/></svg><span class="text-sm">Dashboard</span></a></li>
                <li><a href="{{ route('agent.listings') }}" class="nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('agent.listings*') ? 'active bg-white/10 text-white font-semibold' : 'text-white/70 hover:bg-white/5 hover:text-white' }}"><svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg><span class="text-sm">Listing</span></a></li>
                <li><a href="{{ route('agent.notifications') }}" class="nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('agent.notifications*') ? 'active bg-white/10 text-white font-semibold' : 'text-white/70 hover:bg-white/5 hover:text-white' }}"><svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg><span class="text-sm">Notifikasi</span>@php $unreadCount = App\Models\SystemNotification::where("user_id", Auth::id())->where("is_read", false)->whereIn("type", ["broadcast", "direct"])->count(); @endphp @if($unreadCount > 0)<span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>@endif</a></li>
                <li><a href="{{ route('agent.property-browse') }}" class="nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('agent.property-browse*') ? 'active bg-white/10 text-white font-semibold' : 'text-white/70 hover:bg-white/5 hover:text-white' }}"><svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg><span class="text-sm">Lihat Property</span></a></li>
                <li><a href="{{ route('agent.payments') }}" class="nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('agent.payments*') ? 'active bg-white/10 text-white font-semibold' : 'text-white/70 hover:bg-white/5 hover:text-white' }}"><svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg><span class="text-sm">Pengajuan</span></a></li>
            </ul>
            <p class="text-[11px] font-semibold tracking-wider text-[#8F91A2] uppercase mb-3 px-2">Kelola</p>
            <ul class="flex flex-col gap-1 mb-6">
                <li><a href="{{ route('agent.documents') }}" class="nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('agent.documents*') ? 'active bg-white/10 text-white font-semibold' : 'text-white/70 hover:bg-white/5 hover:text-white' }}"><svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg><span class="text-sm">Dokumen</span></a></li>
                <li><a href="{{ route('agent.deals') }}" class="nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('agent.deals*') ? 'active bg-white/10 text-white font-semibold' : 'text-white/70 hover:bg-white/5 hover:text-white' }}"><svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/></svg><span class="text-sm">Transaksi</span></a></li>
                <li><a href="{{ route('agent.commissions') }}" class="nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('agent.commissions*') ? 'active bg-white/10 text-white font-semibold' : 'text-white/70 hover:bg-white/5 hover:text-white' }}"><svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span class="text-sm">Komisi & Pendapatan</span></a></li>
            </ul>
            <p class="text-[11px] font-semibold tracking-wider text-[#8F91A2] uppercase mb-3 px-2">Lainnya</p>
            <ul class="flex flex-col gap-1">
                <li><a href="{{ route('agent.reports') }}" class="nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('agent.reports*') ? 'active bg-white/10 text-white font-semibold' : 'text-white/70 hover:bg-white/5 hover:text-white' }}"><svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg><span class="text-sm">Laporan</span></a></li>
                <li><a href="{{ route('agent.activity-log') }}" class="nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('agent.activity-log*') ? 'active bg-white/10 text-white font-semibold' : 'text-white/70 hover:bg-white/5 hover:text-white' }}"><svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span class="text-sm">Log Aktivitas</span></a></li>
                <li><a href="{{ route('agent.profile') }}" class="nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('agent.profile*') ? 'active bg-white/10 text-white font-semibold' : 'text-white/70 hover:bg-white/5 hover:text-white' }}"><svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg><span class="text-sm">Akun Saya</span></a></li>
                <li><a href="{{ route('front.index') }}" class="nav-item relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/70 hover:bg-white/5 hover:text-white"><svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9"/></svg><span class="text-sm">Lihat Website</span></a></li>
                <li><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="nav-item w-full relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-white/70 hover:bg-white/10 hover:text-white"><svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg><span class="text-sm">Logout</span></button></form></li>
            </ul>
        </nav>
    </aside>
    <div class="lg:ml-[270px] min-h-screen pb-20 lg:pb-0">
        <header class="sticky top-0 z-30 bg-[#F8F8FA]/80 backdrop-blur-xl px-4 lg:px-8 py-4">
            <div class="flex items-center justify-between bg-white rounded-2xl px-4 lg:px-6 py-3 shadow-sm">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 -ml-2 rounded-xl hover:bg-gray-100"><svg class="w-6 h-6 text-[#060922]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg></button>
                <div class="hidden md:block relative flex-1 max-w-md"><svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-[#8F91A2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg><input type="text" placeholder="Cari listing, transaksi..." class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" /></div>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block text-right"><p class="text-xs text-[#8F91A2]">Halo,</p><p class="text-sm font-semibold text-[#060922]">{{ Auth::user()->name }}</p></div>
                    @if(Auth::user()->photo)<img src="{{ Storage::url(Auth::user()->photo) }}" class="w-10 h-10 rounded-full object-cover" />@else<div class="w-10 h-10 rounded-full bg-[#060922] flex items-center justify-center text-white font-bold text-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>@endif
                </div>
            </div>
        </header>
        @if(session('success'))<div id="toast" class="toast fixed top-6 right-6 z-[100] bg-[#060922] text-white px-5 py-3 rounded-2xl shadow-xl flex items-center gap-3 max-w-sm"><div class="w-8 h-8 rounded-full bg-white flex items-center justify-center"><svg class="w-4 h-4 text-[#060922]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></div><p class="text-sm font-medium">{{ session('success') }}</p><button onclick="closeToast()" class="ml-auto text-white/60 hover:text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button></div>@endif
        <main class="page-enter px-4 lg:px-8 py-2">@yield('content')</main>
    </div>
    <script>function toggleSidebar(){document.getElementById('agentSidebar').classList.toggle('open');document.getElementById('sidebarOverlay').classList.toggle('active');}function closeToast(){const t=document.getElementById('toast');if(t)t.remove();}setTimeout(()=>{const t=document.getElementById('toast');if(t)closeToast();},4000);</script>
    @stack('after-scripts')
</body>
</html>
