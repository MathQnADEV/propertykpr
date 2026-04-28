@extends('agent.layouts.app')

@section('title', 'Upload Proof & Docs - Agent Panel')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#060922]">Upload Proof & Docs</h1>
            <p class="text-sm text-[#8F91A2] mt-1">Upload bukti dan dokumen pendukung untuk setiap mortgage</p>
        </div>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-2xl p-4 border border-[#F2F2F4] mb-6">
        <form method="GET" action="{{ route('agent.documents') }}" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama properti..." class="flex-1 px-4 py-2.5 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#3F52FF]/20 focus:border-[#3F52FF] transition-all" />
            <button type="submit" class="px-5 py-2.5 bg-[#060922] text-white font-semibold text-sm rounded-xl hover:bg-[#060922]/90 transition-colors">Cari</button>
        </form>
    </div>

    {{-- Mortgage List with Upload --}}
    <div class="space-y-4">
        @forelse($mortgages as $mortgage)
            <div class="agent-card bg-white rounded-2xl border border-[#F2F2F4] overflow-hidden">
                <div class="p-5">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100 hidden sm:block">
                            @if($mortgage->house && $mortgage->house->thumbnail)
                                <img src="{{ Storage::url($mortgage->house->thumbnail) }}" class="w-full h-full object-cover" />
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-[#060922]">{{ $mortgage->house->name ?? 'N/A' }}</h3>
                            <p class="text-xs text-[#8F91A2] mt-0.5">Customer: {{ $mortgage->customer->nama_lengkap ?? 'N/A' }} &bull; {{ $mortgage->bank_name }}</p>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            @if($mortgage->status === 'Approved')
                                <span class="text-[10px] font-semibold bg-[#3F52FF] text-white px-3 py-1.5 rounded-lg">Disetujui</span>
                            @elseif($mortgage->status === 'Waiting for Bank')
                                <span class="text-[10px] font-semibold bg-[#FF9F47] text-white px-3 py-1.5 rounded-lg">Menunggu</span>
                            @else
                                <span class="text-[10px] font-semibold bg-[#FF3E3E] text-white px-3 py-1.5 rounded-lg">Ditolak</span>
                            @endif
                        </div>
                    </div>

                    {{-- Document Status --}}
                    <div class="mt-4 p-4 rounded-xl bg-[#F8F8FA]">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-[#060922]">Dokumen</h4>
                            @if($mortgage->documents)
                                <span class="flex items-center gap-1 text-xs font-semibold text-green-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Sudah diupload
                                </span>
                            @else
                                <span class="flex items-center gap-1 text-xs font-semibold text-[#FF9F47]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Belum ada dokumen
                                </span>
                            @endif
                        </div>

                        @if($mortgage->documents)
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-white border border-[#F2F2F4]">
                                <div class="w-10 h-10 rounded-lg bg-[#3F52FF]/10 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-[#3F52FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-[#060922] truncate">{{ basename($mortgage->documents) }}</p>
                                    <p class="text-xs text-[#8F91A2]">Uploaded {{ $mortgage->updated_at->diffForHumans() }}</p>
                                </div>
                                <a href="{{ Storage::url($mortgage->documents) }}" target="_blank" class="p-2 rounded-lg hover:bg-[#F2F2F4] transition-colors">
                                    <svg class="w-5 h-5 text-[#8F91A2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </div>
                        @endif

                        {{-- Upload Form --}}
                        <form method="POST" action="{{ route('agent.documents.upload', $mortgage) }}" enctype="multipart/form-data" class="mt-3">
                            @csrf
                            <div class="flex gap-2">
                                <label class="flex-1 flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-dashed border-[#F2F2F4] cursor-pointer hover:border-[#3F52FF]/30 transition-colors">
                                    <svg class="w-5 h-5 text-[#8F91A2]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    <span class="text-sm text-[#8F91A2] upload-label">Pilih file (PDF, JPG, PNG)</span>
                                    <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png" required class="hidden" onchange="this.parentElement.querySelector('.upload-label').textContent = this.files[0].name" />
                                </label>
                                <button type="submit" class="px-5 py-2.5 bg-[#060922] text-white font-semibold text-sm rounded-xl hover:bg-[#060922]/90 transition-colors flex-shrink-0">
                                    Upload
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-white rounded-2xl border border-[#F2F2F4]">
                <div class="w-20 h-20 rounded-2xl bg-[#F2F2F4] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-[#8F91A2]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="font-bold text-[#060922] text-lg mb-1">Belum ada data mortgage</h3>
                <p class="text-sm text-[#8F91A2]">Dokumen akan muncul setelah ada mortgage request</p>
            </div>
        @endforelse
    </div>

    @if($mortgages->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $mortgages->withQueryString()->links() }}
        </div>
    @endif
@endsection
