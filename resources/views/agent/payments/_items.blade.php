@foreach($paymentRequests as $pr)
    <div class="agent-card bg-white rounded-2xl border border-[#F2F2F4] p-4 sm:p-5">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100 hidden sm:block">
                @if($pr->house && $pr->house->thumbnail)
                    <img src="{{ Storage::url($pr->house->thumbnail) }}" alt="" loading="lazy"
                        class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#8F91A2]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <h3 class="font-bold text-[#060922]">{{ $pr->house->name ?? 'N/A' }}</h3>
                    @if(($pr->payment_type ?? 'kpr') === 'cash')
                        <span class="text-[9px] font-bold bg-[#111111] text-white px-2 py-0.5 rounded-md">CASH</span>
                    @elseif(($pr->payment_type ?? '') === 'sewa')
                        <span class="text-[9px] font-bold bg-[#555555] text-white px-2 py-0.5 rounded-md">SEWA</span>
                    @else
                        <span class="text-[9px] font-bold bg-[#111111]/10 text-[#111111] px-2 py-0.5 rounded-md">{{ strtoupper($pr->payment_type ?? 'KPR') }}</span>
                    @endif
                </div>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1">
                    <p class="text-xs text-[#8F91A2]">Pembeli: <span class="font-semibold text-[#060922]">{{ $pr->customer->nama_lengkap ?? 'N/A' }}</span></p>
                    @if(($pr->payment_type ?? 'kpr') === 'cash')
                        <p class="text-xs text-[#8F91A2]">Tipe: <span class="font-semibold text-[#060922]">Tunai</span></p>
                    @else
                        <p class="text-xs text-[#8F91A2]">Bank: <span class="font-semibold text-[#060922]">{{ $pr->bank_name }}</span></p>
                        <p class="text-xs text-[#8F91A2]">Durasi: <span class="font-semibold text-[#060922]">{{ $pr->duration }} tahun</span></p>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <div class="text-right hidden sm:block">
                    @if(($pr->payment_type ?? 'kpr') === 'cash')
                        <p class="text-xs text-[#8F91A2]">Harga Properti</p>
                        <p class="font-bold text-[#111111]">Rp {{ number_format($pr->house_price, 0, '', '.') }}</p>
                    @else
                        <p class="text-xs text-[#8F91A2]">Total Pinjaman</p>
                        <p class="font-bold text-[#111111]">Rp {{ number_format($pr->loan_total_amount, 0, '', '.') }}</p>
                    @endif
                </div>
                @if($pr->status === 'Approved')
                    <span class="text-[10px] font-semibold bg-[#111111] text-white px-3 py-1.5 rounded-lg">Disetujui</span>
                @elseif($pr->status === 'Waiting for Bank')
                    <span class="text-[10px] font-semibold bg-[#888888] text-white px-3 py-1.5 rounded-lg">Proses Bank</span>
                @else
                    <span class="text-[10px] font-semibold bg-[#444444] text-white px-3 py-1.5 rounded-lg">Ditolak</span>
                @endif
                <a href="{{ route('agent.payments.show', $pr) }}"
                    class="p-2 rounded-xl border border-[#F2F2F4] hover:bg-[#F2F2F4] transition-colors">
                    <svg class="w-5 h-5 text-[#060922]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
@endforeach
