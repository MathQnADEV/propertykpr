@extends('agent.layouts.app')

@section('title', 'Notifikasi - Agent Panel')

@section('content')
<div class="flex items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-[#060922]">Notifikasi</h1>
        <p class="text-sm text-[#8F91A2] mt-0.5">Pesan dari master dan admin</p>
    </div>
    <div class="flex items-center gap-2">
        <form method="POST" action="{{ route('agent.notifications.markAllRead') }}">
            @csrf
            <button type="submit" class="text-sm font-semibold text-[#111111] hover:underline">Tandai Semua Dibaca</button>
        </form>
        <form method="POST" action="{{ route('agent.notifications.deleteAll') }}" onsubmit="return confirm('Hapus semua notifikasi?')">
            @csrf
            
            <button type="submit" class="text-sm font-semibold text-red-500 hover:underline">Hapus Semua</button>
        </form>
    </div>
</div>

@if($notifications->isEmpty())
    <div class="text-center py-16">
        <div class="w-20 h-20 rounded-2xl bg-[#F2F2F4] flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-[#8F91A2]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        </div>
        <h3 class="font-bold text-[#060922] text-lg mb-1">Belum ada notifikasi</h3>
        <p class="text-sm text-[#8F91A2]">Notifikasi dari master dan admin akan muncul di sini</p>
    </div>
@else
    <div class="space-y-3">
        @foreach($notifications as $notif)
            <div class="bg-white rounded-2xl p-5 border border-[#F2F2F4] {{ $notif->is_read ? 'opacity-60' : 'border-l-4 border-l-[#111111]' }}">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-[#060922] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <h3 class="font-semibold text-sm text-[#060922]">{{ $notif->title }}</h3>
                            <span class="text-[10px] text-[#8F91A2] flex-shrink-0">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-[#8F91A2] mt-1 whitespace-pre-line">{{ $notif->description }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            @if($notif->url)
                                <a href="{{ $notif->url }}" class="text-xs font-semibold text-[#111111] hover:underline">Lihat Detail</a>
                            @endif
                            @if(!$notif->is_read)
                                <form method="POST" action="{{ route('agent.notifications.markRead', $notif) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs text-[#8F91A2] hover:text-[#111111]">Tandai Dibaca</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('agent.notifications.delete', $notif) }}" class="inline" onsubmit="return confirm('Hapus notifikasi?')">
                                @csrf
                                
                                <button type="submit" class="text-xs text-[#8F91A2] hover:text-red-500">Hapus</button>
                            </form>
                        </div>
                    </div>
                    @if(!$notif->is_read)
                        <div class="w-2.5 h-2.5 rounded-full bg-red-500 flex-shrink-0 mt-1.5"></div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
@endif
@endsection
