<nav class="fixed top-0 left-0 right-0 z-30">
    {{-- Navbar bar --}}
    <div class="flex items-center justify-between px-4 py-3 bg-white border-b border-tedja-border shadow-sm
                md:mx-auto md:mt-[30px] md:max-w-[1130px] md:rounded-3xl md:p-4 md:border md:border-tedja-border md:shadow-none">

        {{-- Logo --}}
        <a href="{{ route('front.index') }}" class="flex shrink-0 items-center gap-2">
            <div class="inline-flex items-center justify-center bg-[#060922] rounded-xl p-1.5 shadow-sm">
                <img src="{{ asset('assets/images/logos/XPRO-Favicon.svg') }}" alt="XPRO" class="h-auto w-12 rounded-lg">
            </div>
        </a>

        {{-- Desktop: nav links (hidden on mobile) --}}
        <ul class="hidden md:flex items-center gap-[30px]">
            <li class="group {{ request()->routeIs('front.index') ? 'active' : '' }}">
                <a href="{{ route('front.index') }}"
                    class="hover:font-bold group-[.active]:font-bold transition-all duration-300">Beranda</a>
            </li>
            <li class="group {{ request()->routeIs('front.browse') ? 'active' : '' }}">
                <a href="{{ route('front.browse') }}"
                    class="hover:font-bold group-[.active]:font-bold transition-all duration-300">Jelajahi</a>
            </li>
        </ul>

        {{-- Desktop: guest Sign In only (hidden on mobile) --}}
        @guest
        <div class="hidden md:flex items-center gap-3">
            <a href="{{ route('login') }}"
                class="group rounded-full border border-tedja-black py-[14px] px-5 hover:bg-tedja-black flex items-center transition-all duration-300">
                <span class="font-semibold group-hover:text-white transition-all duration-300">Masuk</span>
            </a>
        </div>
        @endguest

        {{-- Desktop: auth profile dropdown (hidden on mobile) --}}
        @auth
        <div class="hidden md:block relative">
            <button id="Profile" class="flex items-center gap-[14px]">
                <div class="flex text-right flex-col gap-0.5">
                    <p class="text-sm text-tedja-secondary">Halo,</p>
                    <p class="font-semibold">{{ Auth::user()->name }}</p>
                </div>
                <div class="flex rounded-full size-[50px] overflow-hidden">
                    <img src="{{ Storage::url(Auth::user()->photo) }}" class="w-full h-full object-cover" loading="lazy" alt="photo">
                </div>
            </button>
            {{-- Dropdown: no "flex" in static classes — JS adds it to avoid hidden/flex conflict --}}
            <ul id="profile-dropdown"
                class="hidden absolute top-full mt-[10px] right-0 w-[180px] shrink-0 text-left rounded-xl border border-tedja-border py-5 px-5 bg-white shadow-[0px_10px_30px_0px_#B8B8B840] gap-[14px]">
                <li>
                    <a href="{{ route('front.browse') }}" class="hover:text-tedja-blue transition-all duration-300">Browse Properti</a>
                </li>
                <li>
                    @if(Auth::user()->hasRole('agent'))
                        <a href="{{ route('agent.dashboard') }}" class="hover:text-tedja-blue transition-all duration-300">Dashboard Agent</a>
                    @elseif(Auth::user()->hasRole(['master', 'admin']))
                        <a href="/admin" class="hover:text-tedja-blue transition-all duration-300">Dashboard Admin</a>
                    @elseif(Auth::user()->hasRole('investor'))
                        <a href="{{ route('investor.dashboard') }}" class="hover:text-tedja-blue transition-all duration-300">Dashboard Investor</a>
                    @endif
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left hover:text-tedja-blue transition-all duration-300">
                            Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        @endauth

        {{-- Mobile: hamburger button (hidden on desktop) --}}
        <button id="mobile-menu-btn" class="flex md:hidden flex-col justify-center gap-[5px] p-2 -mr-1" aria-label="Menu">
            <span class="block w-6 h-0.5 bg-tedja-black transition-all duration-300"></span>
            <span class="block w-6 h-0.5 bg-tedja-black transition-all duration-300"></span>
            <span class="block w-6 h-0.5 bg-tedja-black transition-all duration-300"></span>
        </button>
    </div>

    {{-- Mobile menu dropdown (hidden on desktop via md:hidden) --}}
    <div id="mobile-menu"
        class="hidden flex-col bg-white border-b border-tedja-border px-4 py-3 gap-1 md:hidden">
        <a href="{{ route('front.index') }}"
            class="flex items-center rounded-xl px-3 py-2.5 font-semibold text-sm {{ request()->routeIs('front.index') ? 'text-tedja-blue bg-[#F0F0F0]' : 'hover:bg-[#F5F5F7]' }} transition-colors">
            Beranda
        </a>
        <a href="{{ route('front.browse') }}"
            class="flex items-center rounded-xl px-3 py-2.5 font-semibold text-sm {{ request()->routeIs('front.browse') ? 'text-tedja-blue bg-[#F0F0F0]' : 'hover:bg-[#F5F5F7]' }} transition-colors">
            Jelajahi
        </a>

        @guest
        <div class="pt-3 mt-1 border-t border-tedja-border">
            <a href="{{ route('login') }}"
                class="flex items-center justify-center rounded-full border border-tedja-black py-2.5 font-semibold text-sm">
                Masuk
            </a>
        </div>
        @endguest

        @auth
        <div class="pt-3 mt-1 border-t border-tedja-border flex flex-col gap-1">
            <div class="flex items-center gap-3 px-3 py-2 mb-1">
                <div class="flex rounded-full size-9 overflow-hidden shrink-0">
                    <img src="{{ Storage::url(Auth::user()->photo) }}" class="w-full h-full object-cover" loading="lazy" alt="photo">
                </div>
                <div>
                    <p class="text-xs text-tedja-secondary">Halo,</p>
                    <p class="font-semibold text-sm">{{ Auth::user()->name }}</p>
                </div>
            </div>
            <a href="{{ route('front.browse') }}"
                class="rounded-xl px-3 py-2.5 font-semibold text-sm text-tedja-secondary hover:bg-[#F5F5F7] transition-colors">
                Browse Properti
            </a>
            @if(Auth::user()->hasRole('agent'))
                <a href="{{ route('agent.dashboard') }}"
                    class="rounded-xl px-3 py-2.5 font-semibold text-sm text-[#111111] hover:bg-[#F5F5F7] transition-colors">
                    Dashboard Agent
                </a>
            @elseif(Auth::user()->hasRole(['master', 'admin']))
                <a href="/admin"
                    class="rounded-xl px-3 py-2.5 font-semibold text-sm text-[#111111] hover:bg-[#F5F5F7] transition-colors">
                    Dashboard Admin
                </a>
            @elseif(Auth::user()->hasRole('investor'))
                <a href="{{ route('investor.dashboard') }}"
                    class="rounded-xl px-3 py-2.5 font-semibold text-sm text-[#111111] hover:bg-[#F5F5F7] transition-colors">
                    Dashboard Investor
                </a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-left rounded-xl px-3 py-2.5 font-semibold text-sm text-[#555555] hover:bg-[#F0F0F0] transition-colors">
                    Log Out
                </button>
            </form>
        </div>
        @endauth
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ── Mobile hamburger ──
        const btn  = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        if (btn && menu) {
            btn.addEventListener('click', function () {
                const isOpen = menu.classList.contains('flex');
                menu.classList.toggle('hidden', isOpen);
                menu.classList.toggle('flex', !isOpen);
            });
        }

        // ── Desktop profile dropdown ──
        const profileBtn  = document.getElementById('Profile');
        const profileDrop = document.getElementById('profile-dropdown');
        if (profileBtn && profileDrop) {
            profileBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                const isHidden = profileDrop.classList.contains('hidden');
                profileDrop.classList.toggle('hidden', !isHidden);
                profileDrop.classList.toggle('flex', isHidden);
                profileDrop.classList.toggle('flex-col', isHidden);
            });

            document.addEventListener('click', function () {
                if (!profileDrop.classList.contains('hidden')) {
                    profileDrop.classList.add('hidden');
                    profileDrop.classList.remove('flex', 'flex-col');
                }
            });

            profileDrop.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        }
    });
</script>
