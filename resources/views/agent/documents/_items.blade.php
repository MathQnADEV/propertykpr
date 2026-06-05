@foreach($mortgages as $mortgage)
    <div class="agent-card bg-white rounded-2xl border border-[#F2F2F4] overflow-hidden">
        <div class="p-5">
            {{-- Header transaksi --}}
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100 hidden sm:block">
                    @if($mortgage->house && $mortgage->house->thumbnail)
                        <img src="{{ Storage::url($mortgage->house->thumbnail) }}" loading="lazy"
                            class="w-full h-full object-cover" />
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-[#060922]">{{ $mortgage->house->name ?? 'N/A' }}</h3>
                    <p class="text-xs text-[#8F91A2] mt-0.5">
                        Pembeli: {{ $mortgage->customer->nama_lengkap ?? 'N/A' }} &bull; {{ $mortgage->bank_name }}
                    </p>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    @if($mortgage->status === 'Approved')
                        <span class="text-[10px] font-semibold bg-[#111111] text-white px-3 py-1.5 rounded-lg">Disetujui</span>
                    @elseif($mortgage->status === 'Waiting for Bank')
                        <span class="text-[10px] font-semibold bg-[#888888] text-white px-3 py-1.5 rounded-lg">Proses Bank</span>
                    @else
                        <span class="text-[10px] font-semibold bg-[#444444] text-white px-3 py-1.5 rounded-lg">Ditolak</span>
                    @endif
                </div>
            </div>

            {{-- Dokumen Pendukung --}}
            <div class="mt-4 p-4 rounded-xl bg-[#F8F8FA]">
                @php
                    $totalDocs = $mortgage->mortgageDocuments->count() + ($mortgage->documents ? 1 : 0);
                @endphp
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-semibold text-[#060922]">Dokumen Pendukung</h4>
                    <span class="text-xs text-[#8F91A2]">{{ $totalDocs }} file</span>
                </div>

                {{-- Dokumen awal dari pengajuan --}}
                @if($mortgage->documents)
                    <div class="space-y-2 mb-2">
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-white border border-[#F2F2F4]">
                            <div class="w-8 h-8 rounded-lg bg-[#060922]/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-[#060922]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-[#060922] truncate">Dokumen Pengajuan</p>
                                <p class="text-xs text-[#8F91A2]">Diupload saat pengajuan</p>
                            </div>
                            <a href="{{ $mortgage->document_url }}" target="_blank"
                                class="p-1.5 rounded-lg hover:bg-[#F2F2F4] transition-colors" title="Lihat">
                                <svg class="w-4 h-4 text-[#8F91A2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Daftar dokumen tambahan --}}
                @if($mortgage->mortgageDocuments->isNotEmpty())
                    <div class="space-y-2 mb-3">
                        @foreach($mortgage->mortgageDocuments as $doc)
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-white border border-[#F2F2F4]">
                                <div class="w-8 h-8 rounded-lg bg-[#111111]/10 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-[#111111]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-[#060922] truncate">{{ $doc->name }}</p>
                                    <p class="text-xs text-[#8F91A2]">{{ $doc->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <a href="{{ route('agent.documents.download', $doc) }}" target="_blank"
                                        class="p-1.5 rounded-lg hover:bg-[#F2F2F4] transition-colors" title="Lihat">
                                        <svg class="w-4 h-4 text-[#8F91A2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('agent.documents.delete', $doc) }}" class="inline"
                                        onsubmit="return confirm('Hapus dokumen ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg hover:bg-[#F2F2F4] transition-colors" title="Hapus">
                                            <svg class="w-4 h-4 text-[#888888]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @elseif(!$mortgage->documents)
                    <p class="text-xs text-[#8F91A2] mb-3">Belum ada dokumen. Upload dokumen pendukung di bawah.</p>
                @endif

                {{-- Form upload dokumen baru --}}
                <form method="POST" action="{{ route('agent.documents.upload', $mortgage) }}"
                    enctype="multipart/form-data" class="space-y-2">
                    @csrf
                    <div>
                        <input type="text" name="document_name" required
                            placeholder="Nama dokumen (contoh: KTP, Slip Gaji, NPWP)"
                            class="w-full px-4 py-2.5 rounded-xl border border-[#F2F2F4] text-sm focus:outline-none focus:ring-2 focus:ring-[#111111]/20 focus:border-[#111111] transition-all" />
                    </div>
                    <div class="flex gap-2">
                        <label class="flex-1 flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-dashed border-[#F2F2F4] cursor-pointer hover:border-[#111111]/30 transition-colors">
                            <svg class="w-5 h-5 text-[#8F91A2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <span class="text-sm text-[#8F91A2] upload-label">Pilih file (PDF, JPG, PNG — maks 10MB)</span>
                            <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png" required class="hidden"
                                onchange="this.parentElement.querySelector('.upload-label').textContent = this.files[0].name" />
                        </label>
                        <button type="submit"
                            class="px-5 py-2.5 bg-[#060922] text-white font-semibold text-sm rounded-xl hover:bg-[#060922]/90 transition-colors flex-shrink-0">
                            Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
