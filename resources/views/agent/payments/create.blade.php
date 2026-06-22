@extends('agent.layouts.app')

@section('title', 'Pengajuan Transaksi - Agent Panel')

@section('content')
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('agent.payments') }}" class="p-2 rounded-xl border border-[#F2F2F4] hover:bg-[#F2F2F4] transition-colors">
            <svg class="w-5 h-5 text-[#060922]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-[#060922]">Pengajuan Transksi</h1>
            <p class="text-sm text-[#8F91A2] mt-0.5">Isi data properti dan data calon pembeli</p>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-[#444444]/5 border border-[#444444]/20 rounded-2xl p-4 mb-6">
            <ul class="text-sm text-[#444444] space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('agent.payments.store') }}" enctype="multipart/form-data" id="main-form">
        @csrf
        <input type="hidden" name="payment_type" id="payment_type" value="{{ old('payment_type', 'kpr') }}" />

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Main Form --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Tipe Pembayaran --}}
                <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                    <h2 class="font-bold text-[#060922] mb-3">Tipe Transaksi</h2>
                    <div class="grid grid-cols-3 gap-3">
                        <button type="button" id="btn-kredit"
                            onclick="setMainType('kredit')"
                            class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 transition-all text-sm font-semibold">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            KPR / Kredit
                        </button>
                        <button type="button" id="btn-cash"
                            onclick="setMainType('cash')"
                            class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 transition-all text-sm font-semibold">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Cash (Tunai)
                        </button>
                        <button type="button" id="btn-sewa"
                            onclick="setMainType('sewa')"
                            class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 transition-all text-sm font-semibold">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Sewa
                        </button>
                    </div>

                    {{-- Sub-type kredit (muncul hanya saat KPR/Kredit dipilih) --}}
                    <div id="kredit-subtype" class="hidden mt-3 pt-3 border-t border-[#F2F2F4]">
                        <label class="block text-xs font-semibold text-[#8F91A2] mb-2">Tipe Kredit</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            <button type="button" id="btn-kpr" onclick="setKreditType('kpr')"
                                class="py-2 px-3 rounded-lg border-2 text-xs font-semibold transition-all">
                                KPR <span class="font-normal text-[#8F91A2]">(Rumah)</span>
                            </button>
                            <button type="button" id="btn-kpa" onclick="setKreditType('kpa')"
                                class="py-2 px-3 rounded-lg border-2 text-xs font-semibold transition-all">
                                KPA <span class="font-normal text-[#8F91A2]">(Apartemen)</span>
                            </button>
                            <button type="button" id="btn-kpt" onclick="setKreditType('kpt')"
                                class="py-2 px-3 rounded-lg border-2 text-xs font-semibold transition-all">
                                KPT <span class="font-normal text-[#8F91A2]">(Tanah)</span>
                            </button>
                            <button type="button" id="btn-kpg" onclick="setKreditType('kpg')"
                                class="py-2 px-3 rounded-lg border-2 text-xs font-semibold transition-all">
                                KPG <span class="font-normal text-[#8F91A2]">(Gudang)</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ① Informasi Properti & KPR --}}
                <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-6 h-6 rounded-full bg-[#060922] text-white text-xs font-bold flex items-center justify-center">1</span>
                        <h2 class="font-bold text-[#060922]">Informasi Properti</h2>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Properti <span class="text-[#444444]">*</span></label>
                            {{-- Searchable combobox (ketik untuk filter) --}}
                            <div class="relative" id="house-combobox">
                                <input type="hidden" name="house_id" id="house_id" value="{{ old('house_id') }}">
                                <button type="button" id="house-trigger" onclick="toggleHouseDropdown()"
                                    class="w-full flex items-center justify-between gap-2 px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm bg-white text-left focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all">
                                    <span id="house-selected-label" class="truncate text-[#8F91A2]">Pilih Properti</span>
                                    <svg class="w-4 h-4 text-[#8F91A2] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div id="house-dropdown" class="hidden absolute z-30 mt-1 w-full bg-white border border-[#F2F2F4] rounded-xl shadow-lg overflow-hidden">
                                    <div class="p-2 border-b border-[#F2F2F4]">
                                        <div class="relative">
                                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#8F91A2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                            <input type="text" id="house-search" placeholder="Cari nama properti / agent..." autocomplete="off"
                                                oninput="filterHouses()"
                                                class="w-full pl-9 pr-3 py-2 rounded-lg border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all">
                                        </div>
                                    </div>
                                    <ul id="house-options" class="max-h-60 overflow-y-auto py-1">
                                        @foreach($houses as $house)
                                            <li class="house-option-item"
                                                data-search="{{ strtolower($house->name . ' ' . ($house->agent->name ?? '')) }}">
                                                <button type="button"
                                                    onclick="selectHouse('{{ $house->id }}', this)"
                                                    data-label="{{ $house->name }} — Rp {{ number_format($house->price, 0, '', '.') }}"
                                                    class="w-full text-left px-4 py-2.5 hover:bg-[#F8F8FA] transition-colors">
                                                    <span class="block text-sm font-medium text-[#060922]">{{ $house->name }}</span>
                                                    <span class="block text-xs text-[#8F91A2]">Rp {{ number_format($house->price, 0, '', '.') }} · Agent: {{ $house->agent->name ?? '-' }}</span>
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div id="house-noresult" class="hidden px-4 py-3 text-sm text-[#8F91A2] text-center">Properti tidak ditemukan</div>
                                </div>
                            </div>
                        </div>

                        {{-- KPR-only fields --}}
                        <div id="kpr-fields" class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-[#060922] mb-1.5">Pilihan Bank / KPR <span class="text-[#444444]">*</span></label>
                                <select name="interest_id" id="interest_id"
                                    class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] bg-white transition-all">
                                    <option value="">— Pilih properti terlebih dahulu —</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[#060922] mb-1.5">Uang Muka (DP) <span class="text-[#444444]">*</span></label>
                                <select name="dp_percentage" id="dp_percentage"
                                    class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] bg-white transition-all">
                                    <option value="">Pilih persentase DP</option>
                                    @foreach([5,10,15,20,40,50,60,80] as $dp)
                                        <option value="{{ $dp }}" {{ old('dp_percentage') == $dp ? 'selected' : '' }}>{{ $dp }}%</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Simulasi KPR --}}
                <div id="simulation-card" class="bg-white rounded-2xl p-5 border border-[#F2F2F4] hidden">
                    <h2 class="font-bold text-[#060922] mb-4">Simulasi KPR</h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between py-2 border-b border-[#F2F2F4]"><span class="text-[#8F91A2]">Harga Properti</span><span class="font-semibold" id="sim-price">Rp 0</span></div>
                        <div class="flex justify-between py-2 border-b border-[#F2F2F4]"><span class="text-[#8F91A2]">Uang Muka (DP)</span><span class="font-semibold" id="sim-dp">Rp 0</span></div>
                        <div class="flex justify-between py-2 border-b border-[#F2F2F4]"><span class="text-[#8F91A2]">Total Pinjaman</span><span class="font-semibold" id="sim-loan">Rp 0</span></div>
                        <div class="flex justify-between py-2 border-b border-[#F2F2F4]"><span class="text-[#8F91A2]">Bunga / Tahun</span><span class="font-semibold" id="sim-interest">0%</span></div>
                        <div class="flex justify-between py-2 border-b border-[#F2F2F4]"><span class="text-[#8F91A2]">Durasi</span><span class="font-semibold" id="sim-duration">0 Tahun</span></div>
                        <div class="flex justify-between py-2 border-b border-[#F2F2F4]"><span class="text-[#8F91A2]">Cicilan / Bulan</span><span class="font-semibold text-[#111111]" id="sim-monthly">Rp 0</span></div>
                        <div class="flex justify-between py-2"><span class="text-[#8F91A2]">Total Bayar (+ Bunga)</span><span class="font-bold text-[#060922]" id="sim-total">Rp 0</span></div>
                    </div>
                </div>

                {{-- ② Data Calon Pembeli --}}
                <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-6 h-6 rounded-full bg-[#060922] text-white text-xs font-bold flex items-center justify-center">2</span>
                        <h2 class="font-bold text-[#060922]">Data Calon Pembeli</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Nama Lengkap --}}
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Nama Lengkap <span class="text-[#444444]">*</span></label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                                placeholder="Sesuai KTP"
                                class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all">
                        </div>

                        {{-- NIK --}}
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">NIK / No. KTP <span class="text-[#444444]">*</span></label>
                            <input type="text" name="nik" value="{{ old('nik') }}" required maxlength="16"
                                placeholder="16 digit NIK"
                                class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all">
                        </div>

                        {{-- No. Telepon --}}
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">No. Telepon / HP <span class="text-[#444444]">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required
                                placeholder="08xx-xxxx-xxxx"
                                class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Email <span class="text-[#8F91A2] font-normal">(opsional)</span></label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                placeholder="nama@email.com"
                                class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all">
                        </div>

                        {{-- Status Pernikahan --}}
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Status Pernikahan <span class="text-[#444444]">*</span></label>
                            <select name="status_pernikahan" required
                                class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] bg-white transition-all">
                                @foreach(['Belum Menikah', 'Menikah', 'Cerai'] as $s)
                                    <option value="{{ $s }}" {{ old('status_pernikahan') == $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tempat Lahir --}}
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                                placeholder="Kota tempat lahir"
                                class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all">
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all">
                        </div>

                        {{-- Pekerjaan --}}
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Pekerjaan <span class="text-[#444444]">*</span></label>
                            <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}" required
                                placeholder="e.g. Karyawan Swasta, PNS, Wirausaha"
                                class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all">
                        </div>

                        {{-- Penghasilan --}}
                        <div>
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Penghasilan Bulanan (Rp) <span class="text-[#444444]">*</span></label>
                            <input type="number" name="penghasilan_bulanan" value="{{ old('penghasilan_bulanan') }}" required min="0"
                                placeholder="e.g. 5000000"
                                class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all">
                        </div>

                        {{-- Alamat --}}
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-[#060922] mb-1.5">Alamat Lengkap <span class="text-[#444444]">*</span></label>
                            <textarea name="alamat" rows="3" required
                                placeholder="Alamat sesuai KTP"
                                class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all resize-none">{{ old('alamat') }}</textarea>
                        </div>

                    </div>
                </div>

            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">

                {{-- Upload Dokumen --}}
                <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                    <h2 class="font-bold text-[#060922] mb-2">Dokumen Pendukung <span class="text-[#444444]">*</span></h2>
                    <p class="text-xs text-[#8F91A2] mb-4">Upload dokumen dalam format PDF (maks. 5MB)</p>
                    <div class="space-y-2 text-sm text-[#060922] mb-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#111111] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>FC KTP, NPWP, KK, Akte Nikah</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#111111] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Rekening Koran 6 Bulan</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#111111] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Slip Gaji 3 Bulan Terakhir</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#111111] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Surat Keterangan Kerja</span>
                        </div>
                    </div>
                    <label class="flex flex-col items-center justify-center w-full h-32 rounded-xl border-2 border-dashed border-[#F2F2F4] cursor-pointer hover:border-[#111111]/40 transition-colors">
                        <svg class="w-8 h-8 text-[#8F91A2]/50 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span id="file-label" class="text-xs text-[#8F91A2]">Klik untuk upload PDF</span>
                        <input type="file" name="documents" id="doc-input" accept=".pdf" required class="hidden"
                            onchange="document.getElementById('file-label').textContent = this.files[0]?.name ?? 'Klik untuk upload PDF'" />
                    </label>
                </div>

                <button type="submit" id="submit-btn"
                    class="w-full py-3.5 bg-[#060922] text-white font-semibold rounded-xl hover:bg-[#060922]/90 transition-colors text-sm">
                    Ajukan KPR Sekarang
                </button>
            </div>

        </div>
    </form>
@endsection

@php
$housesJson = $houses->keyBy('id')->map(fn($h) => [
    'price'    => $h->price,
    'interest' => $h->interest->map(fn($i) => [
        'id'       => $i->id,
        'bank'     => $i->bank->name,
        'interest' => $i->interest,
        'duration' => $i->duration,
    ])->values(),
]);
@endphp

@push('after-scripts')
<script>
const housesData = @json($housesJson);

function formatRp(num) {
    return 'Rp ' + Math.round(num).toLocaleString('id-ID');
}

// ─── Payment type toggle ───
const KREDIT_TYPES = ['kpr', 'kpa', 'kpt', 'kpg'];

function setMainType(mainType) {
    const kprFields    = document.getElementById('kpr-fields');
    const simCard      = document.getElementById('simulation-card');
    const submitBtn    = document.getElementById('submit-btn');
    const interestSel  = document.getElementById('interest_id');
    const dpInput      = document.getElementById('dp_input');
    const kreditSub    = document.getElementById('kredit-subtype');

    const allBtns = ['btn-kredit','btn-cash','btn-sewa'];
    allBtns.forEach(id => {
        const b = document.getElementById(id);
        if (b) {
            b.classList.remove('border-[#060922]','bg-[#060922]','text-white');
            b.classList.add('border-[#F2F2F4]','text-[#060922]');
        }
    });

    const active = document.getElementById('btn-' + mainType);
    if (active) {
        active.classList.add('border-[#060922]','bg-[#060922]','text-white');
        active.classList.remove('border-[#F2F2F4]','text-[#060922]');
    }

    if (mainType === 'kredit') {
        kreditSub.classList.remove('hidden');
        kprFields.classList.remove('hidden');
        interestSel.setAttribute('required','');
        if (dpInput) dpInput.removeAttribute('required');
        // default ke KPR jika belum ada sub-type terpilih
        const cur = document.getElementById('payment_type').value;
        if (!KREDIT_TYPES.includes(cur)) setKreditType('kpr');
        else refreshKreditBtn(cur);
        submitBtn.textContent = 'Ajukan Transaksi Sekarang';
        recalculate();
    } else {
        kreditSub.classList.add('hidden');
        kprFields.classList.add('hidden');
        if (simCard) simCard.classList.add('hidden');
        document.getElementById('payment_type').value = mainType;
        interestSel.removeAttribute('required');
        submitBtn.textContent = mainType === 'cash'
            ? 'Ajukan Cash Sekarang'
            : 'Ajukan Sewa Sekarang';
    }
}

function setKreditType(type) {
    document.getElementById('payment_type').value = type;
    refreshKreditBtn(type);
    recalculate();
}

function refreshKreditBtn(active) {
    ['kpr','kpa','kpt','kpg'].forEach(t => {
        const b = document.getElementById('btn-' + t);
        if (!b) return;
        if (t === active) {
            b.classList.add('border-[#060922]','bg-[#060922]','text-white');
            b.classList.remove('border-[#F2F2F4]','text-[#8F91A2]');
        } else {
            b.classList.remove('border-[#060922]','bg-[#060922]','text-white');
            b.classList.add('border-[#F2F2F4]','text-[#8F91A2]');
        }
    });
}

// ─── KPR simulation ───
function recalculate() {
    if (!KREDIT_TYPES.includes(document.getElementById('payment_type').value)) return;

    const houseId    = document.getElementById('house_id').value;
    const interestId = document.getElementById('interest_id').value;
    const dp         = parseInt(document.getElementById('dp_percentage').value);
    const simCard    = document.getElementById('simulation-card');

    if (!houseId || !interestId || !dp) { simCard.classList.add('hidden'); return; }

    const house    = housesData[houseId];
    const interest = house?.interest.find(i => i.id == interestId);
    if (!house || !interest) { simCard.classList.add('hidden'); return; }

    const price       = house.price;
    const dpAmount    = price * (dp / 100);
    const loan        = price - dpAmount;
    const n           = interest.duration * 12;
    const r           = interest.interest / 100 / 12;
    const monthly     = r > 0 ? (loan * r * Math.pow(1+r,n)) / (Math.pow(1+r,n)-1) : loan/n;
    const totalWithInt = monthly * n;

    document.getElementById('sim-price').textContent    = formatRp(price);
    document.getElementById('sim-dp').textContent       = formatRp(dpAmount) + ` (${dp}%)`;
    document.getElementById('sim-loan').textContent     = formatRp(loan);
    document.getElementById('sim-interest').textContent = interest.interest + '%';
    document.getElementById('sim-duration').textContent = interest.duration + ' Tahun';
    document.getElementById('sim-monthly').textContent  = formatRp(monthly);
    document.getElementById('sim-total').textContent    = formatRp(totalWithInt);

    simCard.classList.remove('hidden');
}

// ─── Searchable property combobox ───
function toggleHouseDropdown(forceOpen) {
    const dd = document.getElementById('house-dropdown');
    const willOpen = forceOpen === true ? true : dd.classList.contains('hidden');
    dd.classList.toggle('hidden', !willOpen);
    if (willOpen) {
        const search = document.getElementById('house-search');
        search.value = '';
        filterHouses();
        setTimeout(() => search.focus(), 30);
    }
}

function filterHouses() {
    const q = document.getElementById('house-search').value.trim().toLowerCase();
    let visible = 0;
    document.querySelectorAll('#house-options .house-option-item').forEach(li => {
        const match = li.dataset.search.includes(q);
        li.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    document.getElementById('house-noresult').classList.toggle('hidden', visible > 0);
}

function selectHouse(id, btn) {
    document.getElementById('house_id').value = id;
    document.getElementById('house-selected-label').textContent = btn.dataset.label;
    document.getElementById('house-selected-label').classList.remove('text-[#8F91A2]');
    document.getElementById('house-selected-label').classList.add('text-[#060922]');
    document.getElementById('house-dropdown').classList.add('hidden');
    // Trigger handler lama (rebuild daftar bank + simulasi)
    document.getElementById('house_id').dispatchEvent(new Event('change'));
}

// Tutup dropdown jika klik di luar
document.addEventListener('click', (e) => {
    const cb = document.getElementById('house-combobox');
    if (cb && !cb.contains(e.target)) {
        document.getElementById('house-dropdown').classList.add('hidden');
    }
});

document.getElementById('house_id').addEventListener('change', function () {
    const houseId     = this.value;
    const interestSel = document.getElementById('interest_id');
    interestSel.innerHTML = '<option value="">— Pilih bank / KPR —</option>';
    if (houseId && housesData[houseId]) {
        housesData[houseId].interest.forEach(i => {
            const opt = document.createElement('option');
            opt.value = i.id;
            opt.textContent = `${i.bank} — ${i.interest}% / ${i.duration} Tahun`;
            interestSel.appendChild(opt);
        });
    }
    recalculate();
});

// Guard: pastikan properti dipilih sebelum submit (hidden input tak bisa pakai required)
document.getElementById('main-form').addEventListener('submit', function (e) {
    if (!document.getElementById('house_id').value) {
        e.preventDefault();
        toggleHouseDropdown(true);
        document.getElementById('house-trigger').classList.add('ring-2','ring-[#444444]','border-[#444444]');
    }
});
document.getElementById('interest_id').addEventListener('change', recalculate);
document.getElementById('dp_percentage').addEventListener('change', recalculate);

// Init on page load (handles old() value on validation error)
document.addEventListener('DOMContentLoaded', function () {
    // Restore properti terpilih (old value) ke label combobox + daftar bank
    const oldHouseId = document.getElementById('house_id').value;
    if (oldHouseId) {
        const btn = document.querySelector(`#house-options button[onclick*="selectHouse('${oldHouseId}'"]`);
        if (btn) {
            document.getElementById('house-selected-label').textContent = btn.dataset.label;
            document.getElementById('house-selected-label').classList.remove('text-[#8F91A2]');
            document.getElementById('house-selected-label').classList.add('text-[#060922]');
        }
        document.getElementById('house_id').dispatchEvent(new Event('change'));
        @if(old('interest_id'))
            document.getElementById('interest_id').value = '{{ old('interest_id') }}';
        @endif
    }

    const cur = document.getElementById('payment_type').value || 'kpr';
    if (KREDIT_TYPES.includes(cur)) {
        setMainType('kredit');
        setKreditType(cur);
    } else {
        setMainType(cur);
    }
});
</script>
@endpush
