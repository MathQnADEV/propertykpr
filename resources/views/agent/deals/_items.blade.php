@foreach($deals as $deal)
    <div class="agent-card bg-white rounded-2xl border border-[#F2F2F4] p-4 sm:p-5 cursor-pointer"
        onclick="window.location='{{ route('agent.deals.show', $deal) }}'">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100 hidden sm:block">
                @if($deal->house && $deal->house->thumbnail)
                    <img src="{{ Storage::url($deal->house->thumbnail) }}" alt="" loading="lazy"
                        class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-[#8F91A2]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                    <h3 class="font-bold text-[#060922]">{{ $deal->house->name ?? 'N/A' }}</h3>
                    @if($deal->status === 'Approved')
                        <span class="flex-shrink-0 text-[10px] font-semibold bg-[#111111] text-white px-3 py-1.5 rounded-lg">Terjual</span>
                    @elseif($deal->status === 'Waiting for Bank')
                        <span class="flex-shrink-0 text-[10px] font-semibold bg-[#888888] text-white px-3 py-1.5 rounded-lg">Proses Bank</span>
                    @else
                        <span class="flex-shrink-0 text-[10px] font-semibold bg-[#444444] text-white px-3 py-1.5 rounded-lg">Gagal</span>
                    @endif
                </div>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[#8F91A2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="text-xs text-[#8F91A2]">{{ $deal->customer->nama_lengkap ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[#8F91A2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="text-xs text-[#8F91A2]">{{ $deal->bank_name }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[#8F91A2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-xs text-[#8F91A2]">{{ $deal->created_at->format('d M Y') }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-3">
                    <p class="text-lg font-bold text-[#111111]">Rp {{ number_format($deal->house_price, 0, '', '.') }}</p>
                    <svg class="w-5 h-5 text-[#8F91A2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
@endforeach
