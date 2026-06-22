@extends('agent.layouts.app')

@section('title', 'Log Aktivitas - Agent Panel')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-[#060922]">Log Aktivitas</h1>
    <p class="text-sm text-[#8F91A2] mt-1">Riwayat aktivitas Anda</p>
</div>

@if($activities->isEmpty())
    <div class="text-center py-16">
        <p class="text-[#8F91A2]">Belum ada aktivitas</p>
    </div>
@else
    <div class="space-y-3">
        @foreach($activities as $activity)
            <div class="bg-white rounded-2xl p-4 border border-[#F2F2F4]">
                <p class="text-sm text-[#060922] font-medium">{{ $activity->description }}</p>
                <p class="text-xs text-[#8F91A2] mt-1">{{ $activity->created_at->format('d M Y, H:i') }}</p>
            </div>
        @endforeach
    </div>
    <div class="mt-6">
        {{ $activities->links() }}
    </div>
@endif
@endsection
