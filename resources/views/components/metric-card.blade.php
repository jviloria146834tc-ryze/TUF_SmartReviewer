@props(['title', 'value' => null, 'badgeText' => null, 'badgeType' => 'success', 'subText' => null, 'icon' => null])

@php
    // Dynamically assign Figma's specific pill colors
    $badgeColors = [
        'success' => 'bg-[#D4F5E3] text-[#166534]', // Green (Improving)
        'info'    => 'bg-[#DBEAFE] text-[#1E40AF]', // Blue (Pending)
        'warning' => 'bg-[#FEF3C7] text-[#92400E]', // Yellow/Orange
    ];
    $badgeClass = $badgeColors[$badgeType] ?? $badgeColors['success'];
@endphp

<div class="bg-white border border-[#E2DDD8] rounded-[20px] flex flex-col shadow-sm hover:border-[#6646E5] transition-all duration-300 relative overflow-hidden group cursor-default">
    <!-- Accent Circle Bottom Right -->
    <div class="absolute right-0 bottom-0 w-16 h-16 bg-[#F4F2FF] rounded-tl-full opacity-50 group-hover:bg-[#E0D8FC] transition-colors duration-500 pointer-events-none"></div>

    <!-- Black Header -->
    <div class="bg-[#1A1714] p-3 px-5 relative overflow-hidden">
        <div class="flex justify-between items-center relative z-10">
            <h3 class="text-[10px] font-bold text-white uppercase tracking-widest">{{ $title }}</h3>
            @if($icon)
                <x-dynamic-component :component="$icon" class="w-4 h-4 text-white" />
            @endif
        </div>
    </div>
    
    <div class="p-5 flex-1 flex flex-col relative z-10">
        <div class="flex-1 flex flex-col justify-center">
            <div class="flex items-center gap-2">
                <span class="text-[38px] font-black leading-none text-[#1A1714] tracking-tighter flex items-center gap-2">
                    @if($value !== null)
                    <span x-data="{ value: 0, target: '{{ $value }}', blur: 3 }" 
                        x-init="
                            if (isNaN(parseFloat(target))) { value = target; blur = 0; return; }
                            let numTarget = parseFloat(target);
                            let isFloat = target.toString().includes('.');
                            let decimals = isFloat ? target.toString().split('.')[1].length : 0;
                            let start_time = performance.now();
                            let step = (timestamp) => {
                                let progress = Math.min((timestamp - start_time) / 1500, 1);
                                let easeOut = 1 - Math.pow(1 - progress, 3);
                                let current = easeOut * numTarget;
                                value = isFloat ? current.toFixed(decimals) : Math.floor(current);
                                blur = 3 * (1 - progress);
                                if(progress < 1) requestAnimationFrame(step);
                                else { value = target; blur = 0; }
                            };
                            requestAnimationFrame(step);
                        "
                        x-text="value"
                        :style="`filter: blur(${blur}px)`"></span>
                    @endif
                    {{ $slot }}
                </span>

                @if($badgeText)
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold tracking-wide {{ $badgeClass }} mb-1 shadow-sm">
                        {{ $badgeText }}
                    </span>
                @endif
            </div>
            
            @if($subText && !$badgeText)
                <span class="text-[#7C7167] text-[13px] font-semibold tracking-wide mt-1">{{ $subText }}</span>
            @endif
        </div>
    </div>
</div>
