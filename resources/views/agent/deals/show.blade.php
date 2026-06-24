@extends('agent.layouts.app')

@section('title', 'Detail Transaksi - Agent Panel')

@section('content')
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('agent.deals') }}" class="p-2 rounded-xl border border-[#F2F2F4] hover:bg-[#F2F2F4] transition-colors">
            <svg class="w-5 h-5 text-[#060922]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-[#060922]">Detail Transaksi</h1>
            <p class="text-sm text-[#8F91A2] mt-0.5">{{ $mortgageRequest->house->name ?? 'N/A' }}</p>
        </div>
        @if($mortgageRequest->status === 'Approved')
            <span class="text-xs font-semibold bg-[#111111] text-white px-4 py-2 rounded-xl">Terjual</span>
        @elseif($mortgageRequest->status === 'Waiting for Bank')
            <span class="text-xs font-semibold bg-[#888888] text-white px-4 py-2 rounded-xl">Proses Bank</span>
        @else
            <span class="text-xs font-semibold bg-[#444444] text-white px-4 py-2 rounded-xl">Gagal</span>
        @endif
    </div>

    {{-- Property Hero --}}
    @if($mortgageRequest->house)
        <div class="bg-white rounded-2xl border border-[#F2F2F4] overflow-hidden mb-6">
            <div class="relative h-48 sm:h-64 bg-gray-100">
                @if($mortgageRequest->house->thumbnail)
                    <img src="{{ Storage::url($mortgageRequest->house->thumbnail) }}" class="w-full h-full object-cover" />
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-5 text-white">
                    <h2 class="font-bold text-xl sm:text-2xl">{{ $mortgageRequest->house->name }}</h2>
                    <div class="flex items-center gap-2 mt-1">
                        <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        <span class="text-sm text-white/80">{{ $mortgageRequest->house->category->name ?? '' }}, {{ $mortgageRequest->house->city->name ?? '' }}</span>
                    </div>
                </div>
            </div>

            {{-- Property Specs --}}
            <div class="p-5">
                <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
                    <div class="flex flex-col items-center p-3 rounded-xl bg-[#F8F8FA]">
                        <span class="text-lg font-bold text-[#060922]">{{ $mortgageRequest->house->bedroom }}</span>
                        <span class="text-[10px] text-[#8F91A2] font-semibold mt-0.5">KT</span>
                    </div>
                    <div class="flex flex-col items-center p-3 rounded-xl bg-[#F8F8FA]">
                        <span class="text-lg font-bold text-[#060922]">{{ $mortgageRequest->house->bathroom }}</span>
                        <span class="text-[10px] text-[#8F91A2] font-semibold mt-0.5">KM</span>
                    </div>
                    <div class="flex flex-col items-center p-3 rounded-xl bg-[#F8F8FA]">
                        <span class="text-lg font-bold text-[#060922]">{{ $mortgageRequest->house->land_area }}</span>
                        <span class="text-[10px] text-[#8F91A2] font-semibold mt-0.5">m² Tanah</span>
                    </div>
                    <div class="flex flex-col items-center p-3 rounded-xl bg-[#F8F8FA]">
                        <span class="text-lg font-bold text-[#060922]">{{ $mortgageRequest->house->building_area }}</span>
                        <span class="text-[10px] text-[#8F91A2] font-semibold mt-0.5">m² Bangunan</span>
                    </div>
                    <div class="flex flex-col items-center p-3 rounded-xl bg-[#F8F8FA]">
                        <span class="text-lg font-bold text-[#060922]">{{ $mortgageRequest->house->electric }}</span>
                        <span class="text-[10px] text-[#8F91A2] font-semibold mt-0.5">Watt</span>
                    </div>
                    <div class="flex flex-col items-center p-3 rounded-xl bg-[#F8F8FA]">
                        <span class="text-lg font-bold text-[#060922]">{{ $mortgageRequest->house->certificate }}</span>
                        <span class="text-[10px] text-[#8F91A2] font-semibold mt-0.5">Sertifikat</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Financial Details --}}
            <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                <h3 class="font-bold text-[#060922] mb-4">Detail Keuangan</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-3 border-b border-[#F2F2F4]">
                        <span class="text-sm text-[#8F91A2]">Harga Properti</span>
                        <span class="font-bold text-[#060922]">Rp {{ number_format($mortgageRequest->house_price, 0, '', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-[#F2F2F4]">
                        <span class="text-sm text-[#8F91A2]">Uang Muka / DP ({{ $mortgageRequest->dp_percentage }}%)</span>
                        <span class="font-semibold text-[#060922]">Rp {{ number_format($mortgageRequest->dp_total_amount, 0, '', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-[#F2F2F4]">
                        <span class="text-sm text-[#8F91A2]">Total Pinjaman</span>
                        <span class="font-semibold text-[#060922]">Rp {{ number_format($mortgageRequest->loan_total_amount, 0, '', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-[#F2F2F4]">
                        <span class="text-sm text-[#8F91A2]">Cicilan per Bulan</span>
                        <span class="font-semibold text-[#060922]">Rp {{ number_format($mortgageRequest->monthly_amount, 0, '', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <span class="text-sm font-semibold text-[#060922]">Total + Bunga</span>
                        <span class="font-bold text-xl text-[#111111]">Rp {{ number_format($mortgageRequest->loan_interest_total_amount, 0, '', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Installments --}}
            @if($mortgageRequest->installments && $mortgageRequest->installments->count() > 0)
                <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                    <h3 class="font-bold text-[#060922] mb-4">Riwayat Cicilan ({{ $mortgageRequest->installments->count() }})</h3>
                    <div class="space-y-2">
                        @foreach($mortgageRequest->installments as $inst)
                            <div class="flex items-center gap-4 p-3 rounded-xl {{ $inst->is_paid ? 'bg-white/10' : 'bg-[#F8F8FA]' }}">
                                <div class="w-10 h-10 rounded-lg {{ $inst->is_paid ? 'bg-[#EBEBEB]' : 'bg-[#888888]/20' }} flex items-center justify-center flex-shrink-0">
                                    @if($inst->is_paid)
                                        <svg class="w-5 h-5 text-[#333333]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                        <svg class="w-5 h-5 text-[#888888]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-sm text-[#060922]">Cicilan ke-{{ $inst->no_of_payment }}</p>
                                    <p class="text-xs text-[#8F91A2]">{{ $inst->created_at->format('d M Y') }}</p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="font-bold text-sm text-[#060922]">Rp {{ number_format($inst->grand_total_amount, 0, '', '.') }}</p>
                                    <p class="text-[10px] {{ $inst->is_paid ? 'text-[#333333]' : 'text-[#888888]' }} font-semibold">{{ $inst->is_paid ? 'Lunas' : 'Belum bayar' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Gallery --}}
            @if($mortgageRequest->house && $mortgageRequest->house->photos->count() > 0)
                <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                    <h3 class="font-bold text-[#060922] mb-4">Galeri Foto</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($mortgageRequest->house->photos as $photo)
                            <div class="h-32 rounded-xl overflow-hidden">
                                <img src="{{ Storage::url($photo->photo) }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            {{-- Customer Info --}}
            <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                <h3 class="font-bold text-[#060922] mb-4">Data Pembeli</h3>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-[#111111] flex items-center justify-center text-white font-bold text-lg">
                        {{ strtoupper(substr($mortgageRequest->customer->nama_lengkap ?? 'N', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-[#060922]">{{ $mortgageRequest->customer->nama_lengkap ?? 'N/A' }}</p>
                        <p class="text-xs text-[#8F91A2]">{{ $mortgageRequest->customer->email ?? '' }}</p>
                    </div>
                </div>
                @if($mortgageRequest->customer && $mortgageRequest->customer->phone)
                    <div class="p-3 rounded-xl bg-[#F8F8FA] flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#8F91A2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span class="text-sm">{{ $mortgageRequest->customer->phone }}</span>
                    </div>
                @endif
            </div>

            {{-- Bank Info --}}
            <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                <h3 class="font-bold text-[#060922] mb-4">Info Bank</h3>
                @if($mortgageRequest->interestModel && $mortgageRequest->interestModel->bank && $mortgageRequest->interestModel->bank->photo)
                    <div class="w-full h-16 flex items-center justify-center mb-4 p-2 rounded-xl bg-[#F8F8FA]">
                        <img src="{{ Storage::url($mortgageRequest->interestModel->bank->photo) }}" class="h-full object-contain" />
                    </div>
                @endif
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-[#8F91A2]">Nama Bank</span>
                        <span class="font-semibold text-[#060922]">{{ $mortgageRequest->bank_name }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-[#8F91A2]">Bunga</span>
                        <span class="font-semibold text-[#060922]">{{ $mortgageRequest->interest }}%</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-[#8F91A2]">Durasi</span>
                        <span class="font-semibold text-[#060922]">{{ $mortgageRequest->duration }} tahun</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-[#8F91A2]">Sisa Pinjaman</span>
                        <span class="font-bold text-[#888888]">Rp {{ number_format($mortgageRequest->remaining_loan_amount, 0, '', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Documents --}}
            <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                <h3 class="font-bold text-[#060922] mb-4">Dokumen</h3>
                @if($mortgageRequest->documents)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white/10 border border-[#E8E8E8]">
                        <svg class="w-8 h-8 text-[#333333] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-[#060922]">Dokumen tersedia</p>
                            <a href="{{ Storage::url($mortgageRequest->documents) }}" target="_blank" class="text-xs text-[#111111] hover:underline">Lihat dokumen</a>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-[#888888]/10 border border-[#888888]/20">
                        <svg class="w-8 h-8 text-[#888888] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <p class="text-sm font-semibold text-[#060922]">Belum ada dokumen</p>
                            <a href="{{ route('agent.documents') }}" class="text-xs text-[#111111] hover:underline">Upload sekarang</a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Facilities --}}
            @if($mortgageRequest->house && $mortgageRequest->house->facilities)
                <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
                    <h3 class="font-bold text-[#060922] mb-4">Fasilitas</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach(array_filter(array_map('trim', explode(',', $mortgageRequest->house->facilities))) as $f)
                            <span class="px-3 py-1.5 rounded-lg bg-[#F8F8FA] text-xs font-semibold text-[#060922]">{{ $f }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
