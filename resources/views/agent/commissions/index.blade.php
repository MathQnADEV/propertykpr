@extends('agent.layouts.app')

@section('title', 'Komisi & Pendapatan - Agent Panel')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-[#060922]">Komisi & Pendapatan</h1>
    <p class="text-sm text-[#8F91A2] mt-1">Ringkasan komisi Anda</p>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="stat-card bg-[#060922] text-white p-5 rounded-2xl">
        <p class="text-xs text-white/60">Total Komisi</p>
        <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalAllTime, 0, ',', '.') }}</p>
    </div>
    <div class="stat-card bg-[#333333] text-white p-5 rounded-2xl">
        <p class="text-xs text-white/60">{{ $periodLabel }} Ini</p>
        <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalCurrentPeriod, 0, ',', '.') }}</p>
    </div>
    <div class="stat-card bg-[#888888] text-white p-5 rounded-2xl">
        <p class="text-xs text-white/60">Total Transaksi</p>
        <p class="text-2xl font-bold mt-1">{{ $totalDeals }}</p>
    </div>
    <div class="stat-card bg-[#444444] text-white p-5 rounded-2xl">
        <p class="text-xs text-white/60">Request Pending</p>
        <p class="text-2xl font-bold mt-1">{{ $pendingRequestCount }}</p>
    </div>
</div>

<div class="bg-white rounded-2xl p-5 border border-[#F2F2F4] mb-6">
    <h2 class="font-bold text-lg text-[#060922] mb-4">Riwayat Komisi</h2>
    @forelse($commissions as $c)
        <div class="flex items-center justify-between py-3 border-b border-[#F2F2F4] last:border-0">
            <div>
                <p class="font-semibold text-sm">{{ $c->mortgageRequest->house->name ?? 'N/A' }}</p>
                <p class="text-xs text-[#8F91A2]">{{ $c->created_at->format('d M Y') }}</p>
            </div>
            <p class="font-bold text-sm">Rp {{ number_format($c->commission_amount, 0, ',', '.') }}</p>
        </div>
    @empty
        <p class="text-sm text-[#8F91A2] text-center py-4">Belum ada komisi</p>
    @endforelse
</div>

<div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
    <h2 class="font-bold text-lg text-[#060922] mb-4">Request Komisi</h2>
    @forelse($commissionRequests as $req)
        <div class="flex items-center justify-between py-3 border-b border-[#F2F2F4] last:border-0">
            <div>
                <p class="font-semibold text-sm">{{ $req->mortgageRequest->house->name ?? 'N/A' }}</p>
                <p class="text-xs text-[#8F91A2]">{{ $req->created_at->format('d M Y') }} • 
                    <span class="{{ $req->status === 'pending' ? 'text-yellow-600' : ($req->status === 'approved' ? 'text-green-600' : 'text-red-600') }}">{{ ucfirst($req->status) }}</span>
                </p>
            </div>
        </div>
    @empty
        <p class="text-sm text-[#8F91A2] text-center py-4">Belum ada request</p>
    @endforelse
</div>
@endsection
