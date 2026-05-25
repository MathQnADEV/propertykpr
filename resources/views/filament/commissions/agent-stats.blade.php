@php
    $cards = [
        [
            'label'   => 'Total Deal',
            'value'   => $totalDeals,
            'badge'   => 'Total',
            'bg'      => '#060922',
            'iconBg'  => 'rgba(255,255,255,0.1)',
            'iconClr' => '#E0E0E0', /* was: #CEF27F */
            'badgeBg' => 'rgba(200,200,200,0.1)', /* was: rgba(206,242,127,0.1) */
            'badgeClr'=> '#E0E0E0', /* was: #CEF27F */
            'subClr'  => 'rgba(255,255,255,0.6)',
            'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
        ],
        [
            'label'   => 'Total Nilai Properti',
            'value'   => 'Rp ' . number_format($totalValue, 0, ',', '.'),
            'badge'   => 'Nilai',
            'bg'      => '#333333', /* was: #3F52FF */
            'iconBg'  => 'rgba(255,255,255,0.1)',
            'iconClr' => 'white',
            'badgeBg' => 'rgba(255,255,255,0.15)',
            'badgeClr'=> 'white',
            'subClr'  => 'rgba(255,255,255,0.6)',
            'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
            'wide'    => true,
        ],
        [
            'label'   => 'Sudah Berkomisi',
            'value'   => $withComm,
            'badge'   => 'Selesai',
            'bg'      => '#444444', /* was: #059669 */
            'iconBg'  => 'rgba(255,255,255,0.1)',
            'iconClr' => 'white',
            'badgeBg' => 'rgba(255,255,255,0.15)',
            'badgeClr'=> 'white',
            'subClr'  => 'rgba(255,255,255,0.6)',
            'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        ],
        [
            'label'   => 'Belum Berkomisi',
            'value'   => $withoutComm,
            'badge'   => 'Pending',
            'bg'      => '#888888', /* was: #FF9F47 */
            'iconBg'  => 'rgba(255,255,255,0.1)',
            'iconClr' => 'white',
            'badgeBg' => 'rgba(255,255,255,0.15)',
            'badgeClr'=> 'white',
            'subClr'  => 'rgba(255,255,255,0.6)',
            'icon'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        ],
    ];
@endphp

{{-- Agent Stats Card — styled like agent dashboard, using inline styles for brand colors --}}
<div style="border-radius: 0.875rem; overflow: hidden; border: 1px solid #e5e7eb; margin-bottom: 0.25rem;">

    {{-- Header --}}
    <div style="padding: 0.75rem 1.25rem; background-color: #f9fafb; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; gap: 0.5rem;">
        <svg style="width: 1rem; height: 1rem; color: #6b7280; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        <span style="font-size: 0.875rem; font-weight: 600; color: #374151;">Statistik Agent: {{ $agentName }}</span>
    </div>

    {{-- Stat Cards Row --}}
    <div style="display: grid; grid-template-columns: repeat(4, 1fr);">
        @foreach($cards as $card)
            <div style="
                padding: 1.25rem;
                background-color: {{ $card['bg'] }};
                color: white;
                position: relative;
                overflow: hidden;
                {{ !$loop->last ? 'border-right: 1px solid rgba(255,255,255,0.1);' : '' }}
            ">
                {{-- Glow effect (like stat-card::after on dashboard) --}}
                <div style="
                    position: absolute; top: -50%; right: -50%;
                    width: 100%; height: 100%;
                    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
                    pointer-events: none;
                "></div>

                {{-- Icon + Badge row --}}
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
                    <div style="
                        width: 2.25rem; height: 2.25rem;
                        border-radius: 0.625rem;
                        background-color: {{ $card['iconBg'] }};
                        display: flex; align-items: center; justify-content: center;
                        flex-shrink: 0;
                    ">
                        <svg style="width: 1.125rem; height: 1.125rem; color: {{ $card['iconClr'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $card['icon'] !!}
                        </svg>
                    </div>
                    <span style="
                        font-size: 0.6875rem; font-weight: 700;
                        padding: 0.2rem 0.5rem; border-radius: 0.4rem;
                        background-color: {{ $card['badgeBg'] }};
                        color: {{ $card['badgeClr'] }};
                        letter-spacing: 0.03em;
                    ">{{ $card['badge'] }}</span>
                </div>

                {{-- Value --}}
                <p style="font-size: {{ strlen((string) $card['value']) > 10 ? '1rem' : '1.5rem' }}; font-weight: 700; line-height: 1.2; margin-bottom: 0.25rem;">
                    {{ $card['value'] }}
                </p>

                {{-- Label --}}
                <p style="font-size: 0.6875rem; color: {{ $card['subClr'] }}; margin: 0;">{{ $card['label'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Total Komisi Footer (only if exists) --}}
    @if($totalCommAmount > 0)
        <div style="
            padding: 0.75rem 1.25rem;
            background-color: #F0F0F0; /* was: #ede9fe */
            border-top: 1px solid #e5e7eb;
            display: flex; align-items: center; justify-content: space-between;
        ">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 1rem; height: 1rem; color: #555555; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span style="font-size: 0.8125rem; color: #6b7280;">Total komisi yang sudah dibayarkan</span>
            </div>
            <span style="font-size: 0.9375rem; font-weight: 700; color: #555555;">
                Rp {{ number_format($totalCommAmount, 0, ',', '.') }}
            </span>
        </div>
    @endif

</div>
