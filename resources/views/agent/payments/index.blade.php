@extends('agent.layouts.app')

@section('title', 'Permintaan Pembayaran - Agent Panel')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#060922]">Permintaan Pembayaran</h1>
            <p class="text-sm text-[#8F91A2] mt-1">Submit dan kelola permintaan pembayaran KPR</p>
        </div>
        <a href="{{ route('agent.payments.create') }}"
            class="inline-flex items-center gap-2 bg-[#CEF27F] text-[#060922] font-semibold text-sm px-5 py-3 rounded-xl hover:bg-[#b8dc5f] transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Ajukan KPR Baru
        </a>
    </div>

    {{-- Status Filter --}}
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('agent.payments') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ !request('status') ? 'bg-[#060922] text-white' : 'bg-white text-[#060922] border border-[#F2F2F4] hover:bg-[#F2F2F4]' }}">
            Semua
        </a>
        <a href="{{ route('agent.payments', ['status' => 'Waiting for Bank']) }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ request('status') === 'Waiting for Bank' ? 'bg-[#FF9F47] text-white' : 'bg-white text-[#060922] border border-[#F2F2F4] hover:bg-[#F2F2F4]' }}">
            Menunggu
        </a>
        <a href="{{ route('agent.payments', ['status' => 'Approved']) }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ request('status') === 'Approved' ? 'bg-[#3F52FF] text-white' : 'bg-white text-[#060922] border border-[#F2F2F4] hover:bg-[#F2F2F4]' }}">
            Disetujui
        </a>
        <a href="{{ route('agent.payments', ['status' => 'Rejected']) }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors {{ request('status') === 'Rejected' ? 'bg-[#FF3E3E] text-white' : 'bg-white text-[#060922] border border-[#F2F2F4] hover:bg-[#F2F2F4]' }}">
            Ditolak
        </a>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-2xl p-4 border border-[#F2F2F4] mb-6">
        <form method="GET" action="{{ route('agent.payments') }}" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama properti atau customer..." class="flex-1 px-4 py-2.5 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#3F52FF]/20 focus:border-[#3F52FF] transition-all" />
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}" />
            @endif
            <button type="submit" class="px-5 py-2.5 bg-[#060922] text-white font-semibold text-sm rounded-xl hover:bg-[#060922]/90 transition-colors">Cari</button>
        </form>
    </div>

    {{-- Payment Requests List --}}
    <div class="space-y-3">
        @forelse($paymentRequests as $pr)
            <div class="agent-card bg-white rounded-2xl border border-[#F2F2F4] p-4 sm:p-5">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100 hidden sm:block">
                        @if($pr->house && $pr->house->thumbnail)
                            <img src="{{ Storage::url($pr->house->thumbnail) }}" alt="" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-[#8F91A2]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-[#060922]">{{ $pr->house->name ?? 'N/A' }}</h3>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1">
                            <p class="text-xs text-[#8F91A2]">Customer: <span class="font-semibold text-[#060922]">{{ $pr->customer->nama_lengkap ?? 'N/A' }}</span></p>
                            <p class="text-xs text-[#8F91A2]">Bank: <span class="font-semibold text-[#060922]">{{ $pr->bank_name }}</span></p>
                            <p class="text-xs text-[#8F91A2]">Durasi: <span class="font-semibold text-[#060922]">{{ $pr->duration }} tahun</span></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs text-[#8F91A2]">Total Pinjaman</p>
                            <p class="font-bold text-[#3F52FF]">Rp {{ number_format($pr->loan_total_amount, 0, '', '.') }}</p>
                        </div>
                        @if($pr->status === 'Approved')
                            <span class="text-[10px] font-semibold bg-[#3F52FF] text-white px-3 py-1.5 rounded-lg">Disetujui</span>
                        @elseif($pr->status === 'Waiting for Bank')
                            <span class="text-[10px] font-semibold bg-[#FF9F47] text-white px-3 py-1.5 rounded-lg">Menunggu</span>
                        @else
                            <span class="text-[10px] font-semibold bg-[#FF3E3E] text-white px-3 py-1.5 rounded-lg">Ditolak</span>
                        @endif
                        <a href="{{ route('agent.payments.show', $pr) }}" class="p-2 rounded-xl border border-[#F2F2F4] hover:bg-[#F2F2F4] transition-colors">
                            <svg class="w-5 h-5 text-[#060922]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-white rounded-2xl border border-[#F2F2F4]">
                <div class="w-20 h-20 rounded-2xl bg-[#F2F2F4] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-[#8F91A2]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3 class="font-bold text-[#060922] text-lg mb-1">Belum ada permintaan pembayaran</h3>
                <p class="text-sm text-[#8F91A2]">Permintaan pembayaran akan muncul di sini</p>
            </div>
        @endforelse
    </div>

    @if($paymentRequests->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $paymentRequests->withQueryString()->links() }}
        </div>
    @endif
@endsection
