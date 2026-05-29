@extends('agent.layouts.app')

@section('title', 'Ajukan Pembayaran - Agent Panel')

@section('content')
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('agent.payments') }}" class="p-2 rounded-xl border border-[#F2F2F4] hover:bg-[#F2F2F4] transition-colors">
            <svg class="w-5 h-5 text-[#060922]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-[#060922]">Ajukan Pembayaran</h1>
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
                    <h2 class="font-bold text-[#060922] mb-3">Tipe Pembayaran</h2>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" id="btn-kpr"
                            onclick="setPaymentType('kpr')"
                            class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 transition-all text-sm font-semibold">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            KPR (Kredit)
                        </button>
                        <button type="button" id="btn-cash"
                            onclick="setPaymentType('cash')"
                            class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 transition-all text-sm font-semibold">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Cash (Tunai)
                        </button>
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
                            <select name="house_id" id="house_id" required
                                class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] bg-white transition-all">
                                <option value="">Pilih Properti</option>
                                @foreach($houses as $house)
                                    <option value="{{ $house->id }}" {{ old('house_id') == $house->id ? 'selected' : '' }}>
                                        {{ $house->name }} — Rp {{ number_format($house->price, 0, '', '.') }}
                                    </option>
                                @endforeach
                            </select>
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
function setPaymentType(type) {
    document.getElementById('payment_type').value = type;

    const kprFields    = document.getElementById('kpr-fields');
    const simCard      = document.getElementById('simulation-card');
    const submitBtn    = document.getElementById('submit-btn');
    const btnKpr       = document.getElementById('btn-kpr');
    const btnCash      = document.getElementById('btn-cash');
    const interestSel  = document.getElementById('interest_id');
    const dpSel        = document.getElementById('dp_percentage');

    if (type === 'cash') {
        kprFields.classList.add('hidden');
        simCard.classList.add('hidden');
        submitBtn.textContent = 'Ajukan Cash Sekarang';
        btnCash.classList.add('border-[#060922]', 'bg-[#060922]', 'text-white');
        btnCash.classList.remove('border-[#F2F2F4]', 'text-[#060922]');
        btnKpr.classList.add('border-[#F2F2F4]', 'text-[#060922]');
        btnKpr.classList.remove('border-[#060922]', 'bg-[#060922]', 'text-white');
        interestSel.removeAttribute('required');
        dpSel.removeAttribute('required');
    } else {
        kprFields.classList.remove('hidden');
        submitBtn.textContent = 'Ajukan KPR Sekarang';
        btnKpr.classList.add('border-[#060922]', 'bg-[#060922]', 'text-white');
        btnKpr.classList.remove('border-[#F2F2F4]', 'text-[#060922]');
        btnCash.classList.add('border-[#F2F2F4]', 'text-[#060922]');
        btnCash.classList.remove('border-[#060922]', 'bg-[#060922]', 'text-white');
        interestSel.setAttribute('required', '');
        dpSel.setAttribute('required', '');
        recalculate();
    }
}

// ─── KPR simulation ───
function recalculate() {
    if (document.getElementById('payment_type').value === 'cash') return;

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
document.getElementById('interest_id').addEventListener('change', recalculate);
document.getElementById('dp_percentage').addEventListener('change', recalculate);

// Init on page load (handles old() value on validation error)
document.addEventListener('DOMContentLoaded', function () {
    setPaymentType(document.getElementById('payment_type').value || 'kpr');
});
</script>
@endpush
