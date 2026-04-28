@extends('investor.layouts.app')

@section('title', 'Statistik Penjualan - Investor Panel')

@section('content')

    {{-- ── Header ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 mt-2">
        <div>
            <h1 class="text-2xl font-bold text-[#060922]">Statistik Penjualan</h1>
            <p class="text-sm text-[#8F91A2] mt-1">
                {{ $agentName ? 'Menampilkan data untuk: ' . $agentName : 'Data keseluruhan semua agent' }}
            </p>
        </div>
        <div class="flex items-center gap-2 text-sm text-[#8F91A2] bg-white px-4 py-2 rounded-xl border border-[#F2F2F4]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    {{-- ── Agent Filter (Searchable Dropdown) ── --}}
    <div class="bg-white rounded-2xl border border-[#F2F2F4] p-4 mb-6">
        <p class="text-xs font-semibold text-[#8F91A2] uppercase tracking-wider mb-3">Filter Agent</p>

        <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
            {{-- Dropdown --}}
            <div class="relative w-full sm:w-72" id="agentDropdownWrap">
                <button type="button" id="agentDropdownBtn" onclick="toggleAgentDropdown()"
                    class="w-full flex items-center justify-between gap-2 px-4 py-2.5 bg-[#F8F8FA] border border-[#F2F2F4] rounded-xl text-sm font-semibold text-[#060922] hover:border-[#3F52FF]/40 transition-colors">
                    <div class="flex items-center gap-2 min-w-0">
                        <div class="w-6 h-6 rounded-full bg-[#060922] flex items-center justify-center text-[#CEF27F] font-bold text-[10px] flex-shrink-0">
                            {{ $agentName ? strtoupper(substr($agentName, 0, 1)) : '★' }}
                        </div>
                        <span class="truncate">{{ $agentName ?? 'Semua Agent' }}</span>
                    </div>
                    <svg id="agentDropdownChevron" class="w-4 h-4 text-[#8F91A2] flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Dropdown Panel --}}
                <div id="agentDropdownPanel"
                    class="hidden absolute left-0 top-full mt-2 w-full bg-white border border-[#F2F2F4] rounded-2xl shadow-xl z-50 overflow-hidden">

                    {{-- Search --}}
                    <div class="p-3 border-b border-[#F2F2F4]">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#8F91A2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" id="agentSearch" oninput="filterAgents(this.value)"
                                placeholder="Cari agent..."
                                class="w-full pl-9 pr-3 py-2 text-sm border border-[#F2F2F4] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#3F52FF]/20 focus:border-[#3F52FF] transition-all" />
                        </div>
                    </div>

                    {{-- List --}}
                    <div class="max-h-56 overflow-y-auto" id="agentList">
                        <a href="{{ route('investor.dashboard') }}"
                           class="flex items-center gap-2.5 px-4 py-2.5 text-sm hover:bg-[#F8F8FA] transition-colors {{ is_null($agentId) ? 'bg-[#3F52FF]/5 font-semibold text-[#3F52FF]' : 'text-[#060922]' }}"
                           data-name="semua agent">
                            <div class="w-7 h-7 rounded-full bg-[#060922] flex items-center justify-center text-[#CEF27F] font-bold text-[10px] flex-shrink-0">★</div>
                            <span>Semua Agent</span>
                            @if(is_null($agentId))
                                <svg class="w-4 h-4 ml-auto text-[#3F52FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </a>
                        @foreach($agents as $agent)
                            <a href="{{ route('investor.dashboard', ['agent_id' => $agent->id]) }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm hover:bg-[#F8F8FA] transition-colors {{ $agentId === $agent->id ? 'bg-[#3F52FF]/5 font-semibold text-[#3F52FF]' : 'text-[#060922]' }}"
                               data-name="{{ strtolower($agent->name) }}">
                                <div class="w-7 h-7 rounded-full bg-[#060922] flex items-center justify-center text-[#CEF27F] font-bold text-[10px] flex-shrink-0">
                                    {{ strtoupper(substr($agent->name, 0, 1)) }}
                                </div>
                                <span>{{ $agent->name }}</span>
                                @if($agentId === $agent->id)
                                    <svg class="w-4 h-4 ml-auto text-[#3F52FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </a>
                        @endforeach
                        <p id="agentEmpty" class="hidden px-4 py-4 text-sm text-[#8F91A2] text-center">Tidak ada hasil</p>
                    </div>
                </div>
            </div>

            {{-- Active badge --}}
            @if($agentName)
                <div class="flex items-center gap-2 text-sm text-[#3F52FF] bg-[#3F52FF]/5 px-3 py-2 rounded-xl">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="font-semibold">{{ $agentName }}</span>
                    <a href="{{ route('investor.dashboard') }}" class="ml-1 text-[#8F91A2] hover:text-[#060922] transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- ── Stats Cards ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">

        <div class="stat-card text-white p-5 rounded-2xl" style="background-color:#060922;">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,0.1)">
                    <svg class="w-5 h-5" style="color:#CEF27F" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2 py-1 rounded-lg" style="background:rgba(255,255,255,0.1);color:#CEF27F;">Total</span>
            </div>
            <p class="text-2xl lg:text-3xl font-bold">{{ number_format($stats['total_listings']) }}</p>
            <p class="text-xs mt-1" style="color:rgba(255,255,255,0.6)">Total Properti</p>
        </div>

        <div class="stat-card text-white p-5 rounded-2xl" style="background-color:#3F52FF;">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,0.1)">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2 py-1 rounded-lg" style="background:rgba(255,255,255,0.15)">KPR</span>
            </div>
            <p class="text-2xl lg:text-3xl font-bold">{{ number_format($stats['total_kpr']) }}</p>
            <p class="text-xs mt-1" style="color:rgba(255,255,255,0.6)">Total KPR Masuk</p>
        </div>

        <div class="stat-card text-white p-5 rounded-2xl" style="background-color:#16a34a;">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,0.1)">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2 py-1 rounded-lg" style="background:rgba(255,255,255,0.15)">Approved</span>
            </div>
            <p class="text-2xl lg:text-3xl font-bold">{{ number_format($stats['total_approved']) }}</p>
            <p class="text-xs mt-1" style="color:rgba(255,255,255,0.6)">KPR Disetujui</p>
        </div>

        <div class="stat-card text-white p-5 rounded-2xl" style="background-color:#FF9F47;">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,0.1)">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2 py-1 rounded-lg" style="background:rgba(255,255,255,0.15)">Pending</span>
            </div>
            <p class="text-2xl lg:text-3xl font-bold">{{ number_format($stats['total_pending']) }}</p>
            <p class="text-xs mt-1" style="color:rgba(255,255,255,0.6)">KPR Pending</p>
        </div>

        <div class="stat-card text-white p-5 rounded-2xl" style="background-color:#FF3E3E;">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,0.1)">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2 py-1 rounded-lg" style="background:rgba(255,255,255,0.15)">Ditolak</span>
            </div>
            <p class="text-2xl lg:text-3xl font-bold">{{ number_format($stats['total_rejected']) }}</p>
            <p class="text-xs mt-1" style="color:rgba(255,255,255,0.6)">KPR Ditolak</p>
        </div>

        <div class="stat-card text-white p-5 rounded-2xl col-span-2 lg:col-span-1" style="background-color:#7c3aed;">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(255,255,255,0.1)">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2 py-1 rounded-lg" style="background:rgba(255,255,255,0.15)">Loan</span>
            </div>
            <p class="text-base lg:text-lg font-bold leading-tight break-all">
                Rp {{ number_format($stats['total_loan_approved'], 0, ',', '.') }}
            </p>
            <p class="text-xs mt-1" style="color:rgba(255,255,255,0.6)">Total Pinjaman Approved</p>
        </div>

    </div>

    {{-- ── Per-Agent Breakdown Table ── --}}
    <div class="bg-white rounded-2xl border border-[#F2F2F4] overflow-hidden">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b border-[#F2F2F4]">
            <div>
                <h2 class="font-bold text-lg text-[#060922]">Rekap Per Agent</h2>
                @if($agentName)
                    <p class="text-xs mt-0.5" style="color:#3F52FF">Difilter: {{ $agentName }}</p>
                @else
                    <p class="text-xs text-[#8F91A2] mt-0.5">Menampilkan semua agent</p>
                @endif
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('investor.export.excel', ['agent_id' => $agentId]) }}"
                   target="_blank"
                   class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold rounded-xl text-white transition-colors"
                   style="background-color:#16a34a;" onmouseover="this.style.backgroundColor='#15803d'" onmouseout="this.style.backgroundColor='#16a34a'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Excel
                </a>
                <a href="{{ route('investor.export.pdf', ['agent_id' => $agentId]) }}"
                   target="_blank"
                   class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold rounded-xl text-white transition-colors bg-[#FF3E3E] hover:bg-red-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    PDF
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-[#F8F8FA] border-b border-[#F2F2F4]">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-[#8F91A2] uppercase tracking-wider">Agent</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-[#8F91A2] uppercase tracking-wider">Properti</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-[#8F91A2] uppercase tracking-wider">Total KPR</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#16a34a">Disetujui</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-[#FF9F47] uppercase tracking-wider">Pending</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-[#FF3E3E] uppercase tracking-wider">Ditolak</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#7c3aed">Total Pinjaman</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F2F2F4]">
                    @forelse($breakdown as $row)
                        <tr class="hover:bg-[#F8F8FA] transition-colors {{ $agentId === $row['id'] ? 'bg-[#3F52FF]/5' : '' }}">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-[#060922] flex items-center justify-center text-[#CEF27F] font-bold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($row['name'], 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-[#060922]">{{ $row['name'] }}</p>
                                        @if($agentId === $row['id'])
                                            <p class="text-[10px] font-semibold" style="color:#3F52FF">&#10003; Difilter</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center font-semibold text-[#060922]">{{ $row['total_listings'] }}</td>
                            <td class="px-4 py-4 text-center font-semibold text-[#060922]">{{ $row['total_kpr'] }}</td>
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex items-center justify-center min-w-[2rem] px-2.5 py-0.5 rounded-full text-xs font-bold"
                                    style="{{ $row['total_approved'] > 0 ? 'background:#dcfce7;color:#15803d' : 'background:#F2F2F4;color:#8F91A2' }}">
                                    {{ $row['total_approved'] }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex items-center justify-center min-w-[2rem] px-2.5 py-0.5 rounded-full text-xs font-bold
                                    {{ $row['total_pending'] > 0 ? 'bg-orange-100 text-orange-600' : 'bg-[#F2F2F4] text-[#8F91A2]' }}">
                                    {{ $row['total_pending'] }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex items-center justify-center min-w-[2rem] px-2.5 py-0.5 rounded-full text-xs font-bold
                                    {{ $row['total_rejected'] > 0 ? 'bg-red-100 text-red-600' : 'bg-[#F2F2F4] text-[#8F91A2]' }}">
                                    {{ $row['total_rejected'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right font-semibold text-xs whitespace-nowrap" style="color:#7c3aed">
                                Rp {{ number_format($row['total_loan_approved'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-14 text-center">
                                <svg class="w-12 h-12 text-[#8F91A2]/30 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <p class="text-sm text-[#8F91A2]">Belum ada agent terdaftar.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                @if(count($breakdown) > 1)
                @php
                    $totals = [
                        'total_listings'      => array_sum(array_column($breakdown, 'total_listings')),
                        'total_kpr'           => array_sum(array_column($breakdown, 'total_kpr')),
                        'total_approved'      => array_sum(array_column($breakdown, 'total_approved')),
                        'total_pending'       => array_sum(array_column($breakdown, 'total_pending')),
                        'total_rejected'      => array_sum(array_column($breakdown, 'total_rejected')),
                        'total_loan_approved' => array_sum(array_column($breakdown, 'total_loan_approved')),
                    ];
                @endphp
                <tfoot>
                    <tr class="bg-[#060922] text-white font-bold">
                        <td class="px-5 py-3.5 text-sm">Total Keseluruhan</td>
                        <td class="px-4 py-3.5 text-center">{{ $totals['total_listings'] }}</td>
                        <td class="px-4 py-3.5 text-center">{{ $totals['total_kpr'] }}</td>
                        <td class="px-4 py-3.5 text-center" style="color:#CEF27F">{{ $totals['total_approved'] }}</td>
                        <td class="px-4 py-3.5 text-center" style="color:#FF9F47">{{ $totals['total_pending'] }}</td>
                        <td class="px-4 py-3.5 text-center" style="color:#ff8080">{{ $totals['total_rejected'] }}</td>
                        <td class="px-5 py-3.5 text-right text-xs whitespace-nowrap" style="color:#CEF27F">
                            Rp {{ number_format($totals['total_loan_approved'], 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

@endsection

@push('after-scripts')
<script>
    // ── Searchable Agent Dropdown ──
    function toggleAgentDropdown() {
        const panel  = document.getElementById('agentDropdownPanel');
        const chev   = document.getElementById('agentDropdownChevron');
        const isOpen = !panel.classList.contains('hidden');

        if (isOpen) {
            closeAgentDropdown();
        } else {
            panel.classList.remove('hidden');
            chev.style.transform = 'rotate(180deg)';
            document.getElementById('agentSearch').focus();
        }
    }

    function closeAgentDropdown() {
        document.getElementById('agentDropdownPanel').classList.add('hidden');
        document.getElementById('agentDropdownChevron').style.transform = '';
        document.getElementById('agentSearch').value = '';
        filterAgents('');
    }

    function filterAgents(query) {
        const q     = query.toLowerCase().trim();
        const items = document.querySelectorAll('#agentList a[data-name]');
        let   shown = 0;

        items.forEach(item => {
            const match = item.dataset.name.includes(q);
            item.style.display = match ? '' : 'none';
            if (match) shown++;
        });

        const empty = document.getElementById('agentEmpty');
        empty.classList.toggle('hidden', shown > 0);
    }

    // Close on outside click
    document.addEventListener('click', (e) => {
        const wrap = document.getElementById('agentDropdownWrap');
        if (wrap && !wrap.contains(e.target)) closeAgentDropdown();
    });

    // Close on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeAgentDropdown();
    });
</script>
@endpush
