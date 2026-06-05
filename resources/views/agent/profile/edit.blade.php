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
                <div class="w-24 h-24 rounded-full bg-[#060922] flex items-center justify-center text-white font-bold text-4xl">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-[#060922] text-lg">{{ $user->name }}</p>
                    <p class="text-sm text-[#8F91A2]">{{ $user->email }}</p>
                    <span class="mt-2 inline-block bg-[#F0F0F0] text-[#060922] text-xs font-semibold px-3 py-1 rounded-full">Agent</span>
                </div>
                <div class="w-full pt-4 border-t border-[#F2F2F4] text-left space-y-2">
                    <div class="flex items-center gap-2 text-sm text-[#8F91A2]">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Bergabung {{ $user->created_at->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm {{ $user->email_verified_at ? 'text-[#333333]' : 'text-[#444444]' }}">
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
                    <div class="bg-[#444444]/5 border border-[#444444]/20 rounded-xl p-3 mb-4">
                        <ul class="text-sm text-[#444444] space-y-0.5">
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
                            class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" />
                        @error('name')
                            <p class="mt-1 text-xs text-[#444444]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#060922] mb-1.5">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" />
                        @error('email')
                            <p class="mt-1 text-xs text-[#444444]">{{ $message }}</p>
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
                    <div class="bg-[#444444]/5 border border-[#444444]/20 rounded-xl p-3 mb-4">
                        <ul class="text-sm text-[#444444] space-y-0.5">
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
                            class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all"
                            placeholder="Masukkan password lama" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#060922] mb-1.5">Password Baru</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all"
                            placeholder="Minimal 8 karakter" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#060922] mb-1.5">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all"
                            placeholder="Ulangi password baru" />
                    </div>

                    <div class="pt-1">
                        <button type="submit"
                            class="px-6 py-2.5 bg-[#111111] text-white text-sm font-semibold rounded-xl hover:bg-[#111111]/90 transition-colors">
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>

            {{-- Media Sosial --}}
            <div class="bg-white rounded-2xl p-6 border border-[#F2F2F4]">
                <h2 class="font-bold text-[#060922] mb-1">Media Sosial</h2>
                <p class="text-sm text-[#8F91A2] mb-5">Link ini akan tampil di kartu profil publik Anda untuk dihubungi pembeli.</p>

                @if(session('social_success'))
                    <div class="bg-[#F0F0F0] border border-[#CCCCCC] rounded-xl p-3 mb-4 text-sm text-[#333333]">
                        {{ session('social_success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('agent.profile.social') }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-sm font-semibold text-[#060922] mb-1.5">Instagram</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-[#F2F2F4] bg-[#F8F8FA] text-[#8F91A2] text-sm">instagram.com/</span>
                            <input type="text" name="instagram" value="{{ old('instagram', $user->instagram) }}"
                                placeholder="username"
                                class="flex-1 px-4 py-3 rounded-r-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#060922] mb-1.5">Facebook</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-[#F2F2F4] bg-[#F8F8FA] text-[#8F91A2] text-sm">facebook.com/</span>
                            <input type="text" name="facebook" value="{{ old('facebook', $user->facebook) }}"
                                placeholder="username atau link"
                                class="flex-1 px-4 py-3 rounded-r-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#060922] mb-1.5">WhatsApp</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-[#F2F2F4] bg-[#F8F8FA] text-[#8F91A2] text-sm">+</span>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $user->whatsapp) }}"
                                placeholder="6281234567890 (tanpa tanda +)"
                                class="flex-1 px-4 py-3 rounded-r-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" />
                        </div>
                    </div>

                    <div class="pt-1">
                        <button type="submit"
                            class="px-6 py-2.5 bg-[#060922] text-white text-sm font-semibold rounded-xl hover:bg-[#060922]/90 transition-colors">
                            Simpan Media Sosial
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
