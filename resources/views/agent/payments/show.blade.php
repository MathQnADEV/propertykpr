@extends('agent.layouts.app')

@section('title', 'Detail Payment - Agent Panel')

@section('content')
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('agent.payments') }}" class="p-2 rounded-xl border border-[#F2F2F4] hover:bg-[#F2F2F4] transition-colors">
            <svg class="w-5 h-5 text-[#060922]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-bold text-[#060922]">Detail Pengajuan</h1>
                @if(($mortgageRequest->payment_type ?? 'kpr') === 'cash')
                    <span class="text-xs font-bold bg-[#111111] text-white px-2.5 py-1 rounded-lg">CASH</span>
                @else
                    <span class="text-xs font-bold bg-[#111111]/10 text-[#111111] px-2.5 py-1 rounded-lg">KPR</span>
                @endif
            </div>
            <p class="text-sm text-[#8F91A2] mt-0.5">{{ $mortgageRequest->house->name ?? 'N/A' }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Property Info --}}
            <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                <div class="flex items-start gap-4">
                    <div class="w-24 h-24 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                        @if($mortgageRequest->house && $mortgageRequest->house->thumbnail)
                            <img src="{{ Storage::url($mortgageRequest->house->thumbnail) }}" class="w-full h-full object-cover" />
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <h2 class="font-bold text-lg text-[#060922]">{{ $mortgageRequest->house->name ?? 'N/A' }}</h2>
                                <p class="text-sm text-[#8F91A2] mt-0.5">{{ $mortgageRequest->house->category->name ?? '' }}, {{ $mortgageRequest->house->city->name ?? '' }}</p>
                            </div>
                            @if($mortgageRequest->status === 'Approved')
                                <span class="text-xs font-semibold bg-[#111111] text-white px-3 py-1.5 rounded-lg">Disetujui</span>
                            @elseif($mortgageRequest->status === 'Waiting for Bank')
                                <span class="text-xs font-semibold bg-[#888888] text-white px-3 py-1.5 rounded-lg">Proses Bank</span>
                            @else
                                <span class="text-xs font-semibold bg-[#444444] text-white px-3 py-1.5 rounded-lg">Ditolak</span>
                            @endif
                        </div>
                        <p class="text-xl font-bold text-[#111111] mt-2">Rp {{ number_format($mortgageRequest->house_price, 0, '', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Payment Details --}}
            <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                @if(($mortgageRequest->payment_type ?? 'kpr') === 'cash')
                    <h3 class="font-bold text-[#060922] mb-4">Detail Pembayaran Cash</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl bg-[#F8F8FA] sm:col-span-2">
                            <p class="text-xs text-[#8F91A2]">Total Harga (Tunai)</p>
                            <p class="font-bold text-[#111111] text-lg mt-1">Rp {{ number_format($mortgageRequest->house_price, 0, '', '.') }}</p>
                        </div>
                        <div class="p-4 rounded-xl bg-[#F0F0F0]">
                            <p class="text-xs text-[#8F91A2]">Tipe Pembayaran</p>
                            <p class="font-bold text-[#060922] mt-1">Cash / Tunai</p>
                        </div>
                        <div class="p-4 rounded-xl bg-[#F8F8FA]">
                            <p class="text-xs text-[#8F91A2]">Cicilan</p>
                            <p class="font-bold text-[#060922] mt-1">Tidak ada</p>
                        </div>
                    </div>
                @else
                    <h3 class="font-bold text-[#060922] mb-4">Detail KPR</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl bg-[#F8F8FA]">
                            <p class="text-xs text-[#8F91A2]">Uang Muka / DP</p>
                            <p class="font-bold text-[#060922] mt-1">Rp {{ number_format($mortgageRequest->dp_total_amount, 0, '', '.') }} <span class="text-xs font-normal text-[#8F91A2]">({{ $mortgageRequest->dp_percentage }}%)</span></p>
                        </div>
                        <div class="p-4 rounded-xl bg-[#F8F8FA]">
                            <p class="text-xs text-[#8F91A2]">Total Pinjaman</p>
                            <p class="font-bold text-[#060922] mt-1">Rp {{ number_format($mortgageRequest->loan_total_amount, 0, '', '.') }}</p>
                        </div>
                        <div class="p-4 rounded-xl bg-[#F8F8FA]">
                            <p class="text-xs text-[#8F91A2]">Cicilan Bulanan</p>
                            <p class="font-bold text-[#060922] mt-1">Rp {{ number_format($mortgageRequest->monthly_amount, 0, '', '.') }}</p>
                        </div>
                        <div class="p-4 rounded-xl bg-[#F8F8FA]">
                            <p class="text-xs text-[#8F91A2]">Total + Bunga</p>
                            <p class="font-bold text-[#111111] mt-1">Rp {{ number_format($mortgageRequest->loan_interest_total_amount, 0, '', '.') }}</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Installments --}}
            @if($mortgageRequest->installments && $mortgageRequest->installments->count() > 0)
                <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                    <h3 class="font-bold text-[#060922] mb-4">Riwayat Cicilan</h3>
                    <div class="space-y-3">
                        @foreach($mortgageRequest->installments as $inst)
                            <div class="flex items-center justify-between p-3 rounded-xl border border-[#F2F2F4]">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl {{ $inst->is_paid ? 'bg-[#F0F0F0]' : 'bg-[#888888]/20' }} flex items-center justify-center">
                                        @if($inst->is_paid)
                                            <svg class="w-5 h-5 text-[#333333]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @else
                                            <svg class="w-5 h-5 text-[#888888]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm text-[#060922]">Cicilan ke-{{ $inst->no_of_payment }}</p>
                                        <p class="text-xs text-[#8F91A2]">{{ $inst->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <p class="font-bold text-sm text-[#060922]">Rp {{ number_format($inst->grand_total_amount, 0, '', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Customer Info --}}
            <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                <h3 class="font-bold text-[#060922] mb-4">Data Calon Pembeli</h3>
                @if($mortgageRequest->customer)
                    @php $c = $mortgageRequest->customer; @endphp
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-full bg-[#111111] flex items-center justify-center text-white font-bold flex-shrink-0">
                            {{ strtoupper(substr($c->nama_lengkap, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-[#060922]">{{ $c->nama_lengkap }}</p>
                            <p class="text-xs text-[#8F91A2]">{{ $c->email ?? $c->phone }}</p>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between py-2 border-b border-[#F2F2F4]">
                            <span class="text-[#8F91A2]">NIK</span>
                            <span class="font-semibold">{{ $c->nik }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-[#F2F2F4]">
                            <span class="text-[#8F91A2]">No. HP</span>
                            <span class="font-semibold">{{ $c->phone }}</span>
                        </div>
                        @if($c->tanggal_lahir)
                        <div class="flex justify-between py-2 border-b border-[#F2F2F4]">
                            <span class="text-[#8F91A2]">Tgl. Lahir</span>
                            <span class="font-semibold">{{ $c->tempat_lahir ? $c->tempat_lahir . ', ' : '' }}{{ $c->tanggal_lahir->format('d M Y') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between py-2 border-b border-[#F2F2F4]">
                            <span class="text-[#8F91A2]">Pekerjaan</span>
                            <span class="font-semibold">{{ $c->pekerjaan }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-[#F2F2F4]">
                            <span class="text-[#8F91A2]">Penghasilan</span>
                            <span class="font-semibold">Rp {{ number_format($c->penghasilan_bulanan, 0, '', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-[#F2F2F4]">
                            <span class="text-[#8F91A2]">Status</span>
                            <span class="font-semibold">{{ $c->status_pernikahan }}</span>
                        </div>
                        <div class="pt-2">
                            <p class="text-[#8F91A2] mb-1">Alamat</p>
                            <p class="text-[#060922] text-xs leading-relaxed">{{ $c->alamat }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-[#8F91A2]">Data customer tidak tersedia.</p>
                @endif
            </div>

            {{-- Bank Info (KPR only) --}}
            @if(($mortgageRequest->payment_type ?? 'kpr') !== 'cash')
                <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                    <h3 class="font-bold text-[#060922] mb-4">Info Bank</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-[#8F91A2]">Bank</span>
                            <span class="text-sm font-semibold text-[#060922]">{{ $mortgageRequest->bank_name }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-[#8F91A2]">Bunga</span>
                            <span class="text-sm font-semibold text-[#060922]">{{ $mortgageRequest->interest }}%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-[#8F91A2]">Durasi</span>
                            <span class="text-sm font-semibold text-[#060922]">{{ $mortgageRequest->duration }} tahun</span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Submit Payment --}}
            @if($mortgageRequest->status === 'Waiting for Bank')
                <div class="bg-[#060922] rounded-2xl p-5 text-white">
                    <h3 class="font-bold mb-2">Submit untuk Review</h3>
                    <p class="text-xs text-white/60 mb-4">Kirim permintaan pembayaran ini ke admin untuk ditinjau</p>
                    <form method="POST" action="{{ route('agent.payments.submit') }}">
                        @csrf
                        <input type="hidden" name="mortgage_request_id" value="{{ $mortgageRequest->id }}" />
                        <textarea name="notes" rows="3" class="w-full px-4 py-3 rounded-xl bg-white/10 border border-white/10 text-white text-sm focus:outline-none focus:ring-2 focus:ring-white/30 focus:border-white transition-all resize-none placeholder:text-white/30 mb-3" placeholder="Catatan tambahan (opsional)..."></textarea>
                        <button type="submit" class="w-full py-3 bg-[#111111] text-white font-semibold rounded-xl hover:bg-[#333333] transition-colors text-sm">
                            Submit Payment Request
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
