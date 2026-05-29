@extends('layouts.master')

@section('title', 'Masuk - X-Pro')

@section('content')
    <x-nav-front />
    <main class="flex min-h-screen">
        {{-- Form --}}
        <form action="{{ route('login') }}" method="POST"
            class="flex items-center justify-center flex-1 px-4 pt-[100px] pb-8 md:pt-[114px] md:pb-0 md:justify-start md:pl-[calc(((100%-1280px)/2)+75px)]">
            @csrf
            <div class="flex flex-col h-fit w-full max-w-[440px] md:w-[500px] md:max-w-none shrink-0 rounded-[20px] border border-tedja-border p-6 md:p-[30px] gap-4 md:gap-5 bg-white">
                <h1 class="font-bold text-2xl md:text-[28px] leading-tight md:leading-[42px]">Masuk ke Akun Saya</h1>
                <div class="flex flex-col gap-2">
                    <p class="font-semibold text-sm md:text-base">Alamat Email</p>
                    <label class="relative">
                        <img src="{{ asset('assets/images/icons/sms.svg') }}" class="absolute size-5 md:size-6 transform -translate-y-1/2 top-1/2 left-4 md:left-5" alt="icon">
                        <input type="email" name="email"
                            class="appearance-none outline-none w-full rounded-full ring-1 ring-tedja-border py-3 md:py-[14px] pl-11 md:pl-[54px] pr-5 font-semibold text-sm placeholder:font-normal focus:ring-1 focus:ring-tedja-blue transition-all duration-300"
                            placeholder="Masukkan alamat email Anda">
                    </label>
                    <x-input-error :messages="$errors->get('email')" class="text-sm text-tedja-red" />
                </div>
                <div class="flex flex-col gap-2">
                    <p class="font-semibold text-sm md:text-base">Kata Sandi</p>
                    <label class="relative">
                        <img src="{{ asset('assets/images/icons/lock.svg') }}" class="absolute size-5 md:size-6 transform -translate-y-1/2 top-1/2 left-4 md:left-5" alt="icon">
                        <input type="password" name="password"
                            class="appearance-none outline-none w-full rounded-full ring-1 ring-tedja-border py-3 md:py-[14px] pl-11 md:pl-[54px] pr-5 font-semibold text-sm placeholder:font-normal focus:ring-1 focus:ring-tedja-blue transition-all duration-300"
                            placeholder="Masukkan kata sandi Anda">
                    </label>
                    <x-input-error :messages="$errors->get('password')" class="text-sm text-tedja-red" />
                    {{-- <a href="#" class="hover:underline text-sm text-tedja-secondary">Lupa kata sandi</a> --}}
                </div>
                <button type="submit"
                    class="rounded-full py-3 md:py-[14px] px-5 bg-tedja-green w-full text-center font-semibold text-sm md:text-base">
                    Login
                </button>
            </div>
        </form>

        {{-- Right image panel: hidden on mobile --}}
        <div class="hidden md:flex relative w-full max-w-[640px]">
            <div class="fixed top-0 h-screen w-full max-w-[640px] overflow-hidden">
                <img src="{{ asset('assets/images/backgrounds/login-banner.png') }}" class="w-full h-full object-cover" alt="banner">
                <div class="absolute bottom-0 w-full px-[30px] pb-[30px]">
                    <div class="flex flex-col rounded-[30px] border border-tedja-border p-4 gap-[14px] bg-white">
                        <div class="flex">
                            <img src="{{ asset('assets/images/icons/Star 1.svg') }}" class="flex shrink-0" alt="star">
                            <img src="{{ asset('assets/images/icons/Star 1.svg') }}" class="flex shrink-0" alt="star">
                            <img src="{{ asset('assets/images/icons/Star 1.svg') }}" class="flex shrink-0" alt="star">
                            <img src="{{ asset('assets/images/icons/Star 1.svg') }}" class="flex shrink-0" alt="star">
                            <img src="{{ asset('assets/images/icons/Star 1.svg') }}" class="flex shrink-0" alt="star">
                        </div>
                        <p class="font-semibold leading-[28px]">X-PRO membantu kami mendapatkan rumah idaman dengan interest yang rendah, gaji UMR juga bisa hidup bahagia!</p>
                        <div class="flex items-center gap-[14px]">
                            <div class="flex size-[60px] rounded-full overflow-hidden">
                                <img src="{{ asset('assets/images/photos/profile.png') }}" class="w-full h-full object-cover" alt="photo profile">
                            </div>
                            <div>
                                <p class="font-semibold">Sarina Dwi</p>
                                <p class="text-sm text-tedja-secondary">Desainer Rumah</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
