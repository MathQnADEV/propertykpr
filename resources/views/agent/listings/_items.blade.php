@foreach($listings as $listing)
    <div class="agent-card bg-white rounded-2xl border border-[#F2F2F4] overflow-hidden group flex flex-col">
        <a href="{{ route('agent.listings.show', $listing) }}" class="block relative h-40 overflow-hidden flex-shrink-0">
            @if($listing->thumbnail)
                <img src="{{ Storage::url($listing->thumbnail) }}" alt="{{ $listing->name }}" loading="lazy"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                    <svg class="w-10 h-10 text-[#8F91A2]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif
            <div class="absolute top-3 left-3">
                <span class="bg-white/90 backdrop-blur-sm text-[#060922] text-[10px] font-semibold px-2.5 py-1 rounded-lg">
                    {{ $listing->certificate ?? '-' }}
                </span>
            </div>
            @if(!$listing->is_available)
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                    <span class="bg-[#444444] text-white text-xs font-bold px-3 py-1 rounded-lg">Tidak Tersedia</span>
                </div>
            @endif
        </a>
        <div class="p-4 flex flex-col flex-1">
            <a href="{{ route('agent.listings.show', $listing) }}" class="block">
                <h3 class="font-bold text-[#060922] truncate hover:text-[#111111]">{{ $listing->name }}</h3>
            </a>
            <div class="flex items-center gap-1 mt-1">
                <svg class="w-3.5 h-3.5 text-[#8F91A2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p class="text-xs text-[#8F91A2]">{{ $listing->category->name ?? '-' }}, {{ $listing->city->name ?? '-' }}</p>
            </div>
            @if(($viewAll ?? false) && $listing->agent_id !== auth()->id())
                <p class="text-[10px] text-[#8F91A2] mt-1">Agent: <span class="font-semibold text-[#060922]">{{ $listing->agent->name ?? '-' }}</span></p>
            @endif
            <a href="{{ route('agent.listings.show', $listing) }}" class="block">
                <p class="text-lg font-bold text-[#111111] mt-3">Rp {{ number_format($listing->price, 0, '', '.') }}</p>
            </a>
            <div class="flex items-center gap-3 mt-3 text-xs text-[#8F91A2]">
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    {{ $listing->bedroom }} KT
                </span>
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    {{ $listing->bathroom }} KM
                </span>
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                    </svg>
                    {{ $listing->land_area }} m²
                </span>
            </div>
            <div class="flex gap-2 mt-auto pt-4">
                @if($listing->agent_id === auth()->id())
                    <a href="{{ route('agent.listings.edit', $listing) }}"
                        class="flex-1 text-center py-2 rounded-xl bg-[#060922] text-white text-sm font-semibold hover:bg-[#060922]/90 transition-colors">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('agent.listings.delete', $listing) }}"
                        class="flex-shrink-0" onsubmit="return confirm('Yakin ingin menghapus listing ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="p-2 rounded-xl border border-[#444444]/20 text-[#444444] hover:bg-[#444444]/5 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                @else
                    <a href="{{ route('agent.payments.create', ['house_id' => $listing->id]) }}"
                        class="flex-1 text-center py-2 rounded-xl bg-[#111111] text-white text-sm font-semibold hover:bg-[#333333] transition-colors">
                        Ajukan Transaksi
                    </a>
                @endif
            </div>
        </div>
    </div>
@endforeach
