@extends('agent.layouts.app')

@section('title', 'Laporan - Agent Panel')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-[#060922]">Laporan</h1>
    <p class="text-sm text-[#8F91A2] mt-1">Ringkasan kinerja Anda</p>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="stat-card bg-[#060922] text-white p-5 rounded-2xl">
        <p class="text-xs text-white/60">Total Listing</p>
        <p class="text-2xl font-bold mt-1">{{ $totalListings }}</p>
    </div>
    <div class="stat-card bg-[#333333] text-white p-5 rounded-2xl">
        <p class="text-xs text-white/60">Terjual</p>
        <p class="text-2xl font-bold mt-1">{{ $totalSold }}</p>
    </div>
    <div class="stat-card bg-[#888888] text-white p-5 rounded-2xl">
        <p class="text-xs text-white/60">Proses</p>
        <p class="text-2xl font-bold mt-1">{{ $totalInProcess }}</p>
    </div>
    <div class="stat-card bg-[#444444] text-white p-5 rounded-2xl">
        <p class="text-xs text-white/60">Gagal</p>
        <p class="text-2xl font-bold mt-1">{{ $totalFailed }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
        <h2 class="font-bold text-lg text-[#060922] mb-4">Total Pendapatan</h2>
        <p class="text-3xl font-bold text-[#111111]">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4]">
        <h2 class="font-bold text-lg text-[#060922] mb-4">Total Komisi</h2>
        <p class="text-3xl font-bold text-[#111111]">Rp {{ number_format($totalCommissionAmount, 0, ',', '.') }}</p>
    </div>
</div>

@if($recentTransactions->isNotEmpty())
<div class="bg-white rounded-2xl p-5 border border-[#F2F2F4] mt-6">
    <h2 class="font-bold text-lg text-[#060922] mb-4">Transaksi Terbaru</h2>
    @foreach($recentTransactions as $t)
        <div class="flex items-center justify-between py-3 border-b border-[#F2F2F4] last:border-0">
            <div>
                <p class="font-semibold text-sm">{{ $t->house->name ?? 'N/A' }}</p>
                <p class="text-xs text-[#8F91A2]">{{ $t->customer->nama_lengkap ?? 'N/A' }} • {{ $t->created_at->format('d M Y') }}</p>
            </div>
            <p class="font-bold text-sm">Rp {{ number_format($t->house_price, 0, ',', '.') }}</p>
        </div>
    @endforeach
</div>
@endif
@endsection
