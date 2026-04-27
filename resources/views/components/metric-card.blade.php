@props(['title', 'value' => null, 'badgeText' => null, 'badgeType' => 'success', 'subText' => null])

@php
    // Dynamically assign Figma's specific pill colors
    $badgeColors = [
        'success' => 'bg-[#D4F5E3] text-[#166534]', // Green (Improving)
        'info'    => 'bg-[#DBEAFE] text-[#1E40AF]', // Blue (Pending)
        'warning' => 'bg-[#FEF3C7] text-[#92400E]', // Yellow/Orange
    ];
    $badgeClass = $badgeColors[$badgeType] ?? $badgeColors['success'];
@endphp

<div class="bg-white border border-[#E2DDD8] rounded-[16px] p-8 flex flex-col justify-between h-full min-h-[160px] shadow-sm">
    <h3 class="text-[#7C7167] font-medium text-[13px]">{{ $title }}</h3>
    
    <div class="mt-4 flex items-end justify-between">
        <span class="text-[40px] font-extrabold leading-none text-[#1A1714] tracking-tight flex items-center gap-2">
            {{ $value }}
            {{ $slot }}
        </span>
        
        @if($badgeText)
            <span class="px-3 py-1.5 rounded-full text-[11px] font-semibold tracking-wide {{ $badgeClass }}">
                {{ $badgeText }}
            </span>
        @elseif($subText)
            <span class="text-[#92400E] text-[11px] font-semibold tracking-wide mb-1">{{ $subText }}</span>
        @endif
    </div>
</div>
