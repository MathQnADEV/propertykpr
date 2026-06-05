@extends('agent.layouts.app')

@section('title', 'Ajukan Komisi - Agent Panel')

@section('content')
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('agent.commissions', ['tab' => 'requests']) }}"
            class="w-10 h-10 bg-white border border-[#F2F2F4] rounded-xl flex items-center justify-center hover:bg-[#F2F2F4] transition-colors">
            <svg class="w-5 h-5 text-[#060922]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-[#060922]">Ajukan Komisi</h1>
            <p class="text-sm text-[#8F91A2] mt-0.5">Pilih transaksi yang sudah ACC bank untuk diajukan komisinya ke atasan</p>
        </div>
    </div>

    @if($eligibleDeals->isEmpty())
        <div class="text-center py-20 bg-white rounded-2xl border border-[#F2F2F4]">
            <div class="w-20 h-20 rounded-2xl bg-[#F2F2F4] flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-[#8F91A2]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="font-bold text-[#060922] text-lg mb-1">Tidak ada transaksi yang bisa diajukan</h3>
            <p class="text-sm text-[#8F91A2]">Transaksi harus sudah ACC bank, belum berkomisi, dan belum ada pengajuan yang menunggu</p>
        </div>
    @else
        <form action="{{ route('agent.commissions.request.store') }}" method="POST">
            @csrf

            {{-- Deal Selection --}}
            <div class="bg-white rounded-2xl border border-[#F2F2F4] p-5 mb-4">
                <h2 class="font-bold text-[#060922] mb-4">Pilih Transaksi</h2>

                @error('mortgage_request_id')
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-[#444444]">{{ $message }}</div>
                @enderror

                @if($eligibleDeals->total() > 1)
                    <p class="text-xs text-[#8F91A2] mb-3">Klik salah satu kartu di bawah untuk memilih transaksi.</p>
                @endif

                <div class="space-y-3">
                    @foreach($eligibleDeals as $deal)
                        @php
                            // Auto-select jika hanya ada 1 deal (total, bukan per halaman)
                            $isAutoSelected = $eligibleDeals->total() === 1;
                            $isChecked      = old('mortgage_request_id') == $deal->id || ($isAutoSelected && !old('mortgage_request_id'));
                        @endphp
                        <label class="block cursor-pointer">
                            <input type="radio" name="mortgage_request_id" value="{{ $deal->id }}"
                                class="sr-only peer" {{ $isChecked ? 'checked' : '' }}>
                            <div class="flex items-center gap-4 p-4 rounded-xl border-2 border-[#F2F2F4] peer-checked:border-[#111111] peer-checked:bg-[#111111]/5 transition-all">
                                <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                                    @if($deal->house?->thumbnail)
                                        <img src="{{ Storage::url($deal->house->thumbnail) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-[#8F91A2]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-[#060922] truncate">{{ $deal->house?->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-[#8F91A2]">
                                        {{ $deal->customer?->nama_lengkap ?? 'N/A' }} •
                                        Rp {{ number_format($deal->house_price, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="text-xs text-[#8F91A2]">Est. Fee 2.5%</p>
                                    <p class="font-bold text-[#111111] text-sm">Rp {{ number_format($deal->house_price * 0.025, 0, ',', '.') }}</p>
                                </div>
                                {{-- Radio checkmark indicator --}}
                                <div class="w-5 h-5 rounded-full border-2 border-[#F2F2F4] peer-checked:border-[#111111] flex items-center justify-center flex-shrink-0 transition-all">
                                    <div class="w-2.5 h-2.5 rounded-full bg-[#111111] hidden peer-checked:block"></div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                @if($eligibleDeals->hasPages())
                    <div class="mt-4 pt-4 border-t border-[#F2F2F4]">
                        {{ $eligibleDeals->withQueryString()->links() }}
                    </div>
                @endif
            </div>

            {{-- Notes --}}
            <div class="bg-white rounded-2xl border border-[#F2F2F4] p-5 mb-6">
                <label class="block font-bold text-[#060922] mb-2">
                    Catatan untuk Master
                    <span class="font-normal text-[#8F91A2] text-sm">(opsional)</span>
                </label>
                <textarea name="notes" rows="3"
                    placeholder="Contoh: Mohon segera diproses, deal ini sudah closing bulan lalu..."
                    class="w-full px-4 py-3 rounded-xl border border-[#F2F2F4] text-sm text-[#060922] placeholder-[#8F91A2] focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] resize-none transition-all">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-xs text-[#444444] mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="flex gap-3">
                <a href="{{ route('agent.commissions', ['tab' => 'requests']) }}"
                    class="flex-1 sm:flex-none px-6 py-3 rounded-xl border border-[#F2F2F4] bg-white text-[#060922] font-semibold text-sm text-center hover:bg-[#F2F2F4] transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 sm:flex-none px-6 py-3 rounded-xl bg-[#060922] text-white font-semibold text-sm hover:bg-[#060922]/90 transition-colors">
                    Kirim Request
                </button>
            </div>
        </form>
    @endif

@endsection

@push('after-scripts')
<script>
    // Visual radio selection feedback
    document.querySelectorAll('input[name="mortgage_request_id"]').forEach(radio => {
        radio.addEventListener('change', () => {
            document.querySelectorAll('input[name="mortgage_request_id"]').forEach(r => {
                const indicator = r.closest('label').querySelector('.w-5.h-5 .w-2\\.5');
                if (indicator) indicator.classList.toggle('hidden', !r.checked);
            });
        });
    });
</script>
@endpush
