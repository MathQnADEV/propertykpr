@extends('agent.layouts.app')

@section('title', 'Profile - Agent Panel')

@section('content')
    <div class="flex items-center gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#060922]">Profile Saya</h1>
            <p class="text-sm text-[#8F91A2] mt-0.5">Kelola informasi akun dan keamanan</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Avatar Card --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl p-6 border border-[#F2F2F4] flex flex-col items-center text-center gap-4">
                <div class="w-24 h-24 rounded-full bg-[#060922] flex items-center justify-center text-[#CEF27F] font-bold text-4xl">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-[#060922] text-lg">{{ $user->name }}</p>
                    <p class="text-sm text-[#8F91A2]">{{ $user->email }}</p>
                    <span class="mt-2 inline-block bg-[#CEF27F]/20 text-[#060922] text-xs font-semibold px-3 py-1 rounded-full">Agent</span>
                </div>
                <div class="w-full pt-4 border-t border-[#F2F2F4] text-left space-y-2">
                    <div class="flex items-center gap-2 text-sm text-[#8F91A2]">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Bergabung {{ $user->created_at->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm {{ $user->email_verified_at ? 'text-green-600' : 'text-[#FF3E3E]' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $user->email_verified_at ? 'Email terverifikasi' : 'Email belum terverifikasi' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Forms --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Update Profile Info --}}
            <div class="bg-white rounded-2xl p-6 border border-[#F2F2F4]">
                <h2 class="font-bold text-[#060922] mb-1">Informasi Profil</h2>
                <p class="text-sm text-[#8F91A2] mb-5">Perbarui nama dan alamat email akun Anda.</p>

                @if ($errors->any() && !$errors->has('current_password') && !$errors->has('password'))
                    <div class="bg-[#FF3E3E]/5 border border-[#FF3E3E]/20 rounded-xl p-3 mb-4">
                        <ul class="text-sm text-[#FF3E3E] space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('agent.profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-sm font-semibold text-[#060922] mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#3F52FF]/20 focus:border-[#3F52FF] transition-all" />
                        @error('name')
                            <p class="mt-1 text-xs text-[#FF3E3E]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#060922] mb-1.5">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#3F52FF]/20 focus:border-[#3F52FF] transition-all" />
                        @error('email')
                            <p class="mt-1 text-xs text-[#FF3E3E]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-1">
                        <button type="submit"
                            class="px-6 py-2.5 bg-[#060922] text-white text-sm font-semibold rounded-xl hover:bg-[#060922]/90 transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Update Password --}}
            <div class="bg-white rounded-2xl p-6 border border-[#F2F2F4]">
                <h2 class="font-bold text-[#060922] mb-1">Ubah Password</h2>
                <p class="text-sm text-[#8F91A2] mb-5">Pastikan akun Anda menggunakan password yang kuat dan aman.</p>

                @if ($errors->has('current_password') || $errors->has('password'))
                    <div class="bg-[#FF3E3E]/5 border border-[#FF3E3E]/20 rounded-xl p-3 mb-4">
                        <ul class="text-sm text-[#FF3E3E] space-y-0.5">
                            @error('current_password') <li>{{ $message }}</li> @enderror
                            @error('password') <li>{{ $message }}</li> @enderror
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('agent.profile.password') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-[#060922] mb-1.5">Password Saat Ini</label>
                        <input type="password" name="current_password" required
                            class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#3F52FF]/20 focus:border-[#3F52FF] transition-all"
                            placeholder="Masukkan password lama" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#060922] mb-1.5">Password Baru</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#3F52FF]/20 focus:border-[#3F52FF] transition-all"
                            placeholder="Minimal 8 karakter" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#060922] mb-1.5">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#3F52FF]/20 focus:border-[#3F52FF] transition-all"
                            placeholder="Ulangi password baru" />
                    </div>

                    <div class="pt-1">
                        <button type="submit"
                            class="px-6 py-2.5 bg-[#3F52FF] text-white text-sm font-semibold rounded-xl hover:bg-[#3F52FF]/90 transition-colors">
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
