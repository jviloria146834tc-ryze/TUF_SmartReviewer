@extends('layouts.app')

@section('title', 'Performance Results')

@section('content')
<div class="max-w-[1500px] mx-auto w-full flex flex-col h-full overflow-hidden">

    <div class="mb-4 animate-glide-up flex-shrink-0">
        <a href="{{ route('dashboard') }}" class="text-[#7C7167] text-[12px] font-semibold hover:text-[#1A1714] flex items-center gap-1 mb-1 transition-colors w-max">
            &larr; Back to Dashboard
        </a>
        <h1 class="text-[33px] font-bold text-[#1A1714] tracking-tight font-['Special_Gothic_Expanded_One',sans-serif]">
            Performance Results
        </h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 mb-5 items-stretch animate-glide-up delay-100 flex-shrink-0">
        <!-- Avg Score Card -->
        <div class="lg:col-span-1 bg-white border border-[#E2DDD8] rounded-[20px] flex flex-col shadow-sm hover:border-[#6646E5] transition-all duration-300 group cursor-default relative overflow-hidden">
            <div class="bg-[#1A1714] p-3 px-5 relative overflow-hidden">
                <div class="flex justify-between items-center relative z-10">
                    <h3 class="text-[10px] font-bold text-white uppercase tracking-widest">Avg. Quiz Score</h3>
                    <x-heroicon-o-chart-pie class="w-4 h-4 text-white" />
                </div>
            </div>
            <div class="p-5 flex-1 flex flex-col relative z-10">
                <div class="flex-1 flex flex-col justify-center"
                     x-data="{ value: 0, target: {{ $stats['avg_score'] ?? 0 }}, blur: 4 }" 
                     x-init="
                        let start_time = performance.now();
                        let step = (timestamp) => {
                            let progress = Math.min((timestamp - start_time) / 1500, 1);
                            let easeOut = 1 - Math.pow(1 - progress, 3);
                            value = Math.floor(easeOut * target);
                            blur = 4 * (1 - progress);
                            if(progress < 1) requestAnimationFrame(step);
                            else { value = target; blur = 0; }
                        };
                        requestAnimationFrame(step);
                     ">
                    <div class="flex items-center gap-2">
                        <span class="text-[#1A1714] text-[38px] font-black leading-none tracking-tighter">
                            <span x-text="value" :style="`filter: blur(${blur}px)`"></span>%
                        </span>
                        
                        @if($stats['avg_score_delta'] !== 0)
                            <span class="{{ $stats['avg_score_delta'] > 0 ? 'text-[#166534] bg-[#D4F5E3] border-[#BBF7D0]' : 'text-[#991B1B] bg-red-50 border-red-100' }} px-2 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wide border shadow-sm mb-1">
                                {{ $stats['avg_score_delta'] > 0 ? '↑' : '↓' }} {{ abs($stats['avg_score_delta']) }}%
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="absolute right-0 bottom-0 w-16 h-16 bg-[#F4F2FF] rounded-tl-full opacity-50 group-hover:bg-[#E0D8FC] transition-colors duration-500 pointer-events-none"></div>
        </div>

        <!-- Accuracy Rate Card -->
        <div class="lg:col-span-1 bg-white border border-[#E2DDD8] rounded-[20px] flex flex-col shadow-sm hover:border-[#6646E5] transition-all duration-300 group cursor-default relative overflow-hidden">
            <div class="bg-[#1A1714] p-3 px-5 relative overflow-hidden">
                <div class="flex justify-between items-center relative z-10">
                    <h3 class="text-[10px] font-bold text-white uppercase tracking-widest">Accuracy Rate</h3>
                    <x-heroicon-o-check-badge class="w-4 h-4 text-white" />
                </div>
            </div>
            <div class="p-5 flex-1 flex flex-col relative z-10">
                <div class="flex-1 flex flex-col justify-center"
                     x-data="{ value: 0, target: {{ $stats['accuracy_rate'] ?? 0 }}, blur: 4 }" 
                     x-init="
                        let start_time = performance.now();
                        let step = (timestamp) => {
                            let progress = Math.min((timestamp - start_time) / 1500, 1);
                            let easeOut = 1 - Math.pow(1 - progress, 3);
                            value = Math.floor(easeOut * target);
                            blur = 4 * (1 - progress);
                            if(progress < 1) requestAnimationFrame(step);
                            else { value = target; blur = 0; }
                        };
                        requestAnimationFrame(step);
                     ">
                    <div class="flex items-center gap-2">
                        <span class="text-[#1A1714] text-[38px] font-black leading-none tracking-tighter">
                            <span x-text="value" :style="`filter: blur(${blur}px)`"></span>%
                        </span>
                        
                        @if($stats['accuracy_delta'] !== 0)
                            <span class="{{ $stats['accuracy_delta'] > 0 ? 'text-[#166534] bg-[#D4F5E3] border-[#BBF7D0]' : 'text-[#991B1B] bg-red-50 border-red-100' }} px-2 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wide border shadow-sm mb-1">
                                {{ $stats['accuracy_delta'] > 0 ? '↑' : '↓' }} {{ abs($stats['accuracy_delta']) }}%
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="absolute right-0 bottom-0 w-16 h-16 bg-[#F4F2FF] rounded-tl-full opacity-50 group-hover:bg-[#E0D8FC] transition-colors duration-500 pointer-events-none"></div>
        </div>

        <!-- Total Quizzes Card -->
        <div class="lg:col-span-1 bg-white border border-[#E2DDD8] rounded-[20px] flex flex-col shadow-sm hover:border-[#6646E5] transition-all duration-300 group cursor-default relative overflow-hidden">
            <div class="bg-[#1A1714] p-3 px-5 relative overflow-hidden">
                <div class="flex justify-between items-center relative z-10">
                    <h3 class="text-[10px] font-bold text-white uppercase tracking-widest">Total Quizzes</h3>
                    <x-heroicon-o-document-duplicate class="w-4 h-4 text-white" />
                </div>
            </div>
            <div class="p-5 flex-1 flex flex-col relative z-10">
                <div class="flex-1 flex flex-col justify-center"
                     x-data="{ value: 0, target: {{ $stats['quizzes_count'] ?? 0 }}, blur: 4 }" 
                     x-init="
                        let start_time = performance.now();
                        let step = (timestamp) => {
                            let progress = Math.min((timestamp - start_time) / 1500, 1);
                            let easeOut = 1 - Math.pow(1 - progress, 3);
                            value = Math.floor(easeOut * target);
                            blur = 4 * (1 - progress);
                            if(progress < 1) requestAnimationFrame(step);
                            else { value = target; blur = 0; }
                        };
                        requestAnimationFrame(step);
                     ">
                    <div class="flex items-center gap-2">
                        <span class="text-[#1A1714] text-[38px] font-black leading-none tracking-tighter">
                            <span x-text="value" :style="`filter: blur(${blur}px)`"></span>
                        </span>
                        
                        @if($stats['quizzes_delta'])
                            <span class="text-[#2563EB] bg-[#DBEAFE] border-[#BFDBFE] px-2 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wide border shadow-sm mb-1">
                                {{ $stats['quizzes_delta'] }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="absolute right-0 bottom-0 w-16 h-16 bg-[#F4F2FF] rounded-tl-full opacity-50 group-hover:bg-[#E0D8FC] transition-colors duration-500 pointer-events-none"></div>
        </div>

        <!-- Right: Score Trend Graph -->
        <div class="lg:col-span-2 bg-white border border-[#E2DDD8] rounded-[20px] flex flex-col shadow-sm relative overflow-hidden hover:border-[#6646E5]/40 transition-all duration-300 group">
            <div class="bg-[#1A1714] p-3 px-5 relative overflow-hidden">
                <div class="flex justify-between items-center relative z-10">
                    <h3 class="text-[10px] font-bold text-white uppercase tracking-widest">Score Trends</h3>
                    <x-heroicon-o-chart-bar-square class="w-4 h-4 text-white" />
                </div>
            </div>
            <div class="p-4 pb-2 flex-1 flex flex-col relative z-20">
                <div class="flex-1 flex flex-col md:flex-row items-center gap-6 relative"
                     x-data="{ 
                        length: 0, 
                        offset: 0, 
                        showPoints: false,
                        improvementValue: 0,
                        improvementTarget: {{ abs($improvement) }},
                        improvementSign: '{{ $improvement > 0 ? '+' : ($improvement < 0 ? '-' : '') }}',
                        improvementBlur: 4
                     }"
                     x-init="
                        setTimeout(() => {
                            const path = $refs.trendPath;
                            if (path) {
                                length = path.getTotalLength();
                                offset = length;
                                path.style.strokeDasharray = length;
                                path.style.strokeDashoffset = length;
                                showPoints = true;
                                let start_time = performance.now();
                                let duration = 1500;
                                let step = (timestamp) => {
                                    let progress = Math.min((timestamp - start_time) / duration, 1);
                                    let easeOut = 1 - Math.pow(1 - progress, 3);
                                    offset = length * (1 - easeOut);
                                    if(progress < 1) requestAnimationFrame(step);
                                };
                                requestAnimationFrame(step);
                            }
                        }, 600);

                        setTimeout(() => {
                            let start_time = performance.now();
                            let duration = 1500;
                            let step = (timestamp) => {
                                let progress = Math.min((timestamp - start_time) / duration, 1);
                                let easeOut = 1 - Math.pow(1 - progress, 3);
                                improvementValue = Math.floor(easeOut * improvementTarget);
                                improvementBlur = 4 * (1 - progress);
                                if(progress < 1) requestAnimationFrame(step);
                                else { improvementValue = improvementTarget; improvementBlur = 0; }
                            };
                            requestAnimationFrame(step);
                        }, 800);
                     ">
                    
                    <div class="flex-1 w-full flex items-center justify-center min-h-[100px]">
                        @if(count($scoreTrends) > 1)
                        <svg viewBox="0 0 300 100" class="w-full h-20 overflow-visible">
                            <!-- Grid Lines -->
                            <line x1="0" y1="20" x2="300" y2="20" stroke="#F0EDE8" stroke-width="1" />
                            <line x1="0" y1="50" x2="300" y2="50" stroke="#F0EDE8" stroke-width="1" />
                            <line x1="0" y1="80" x2="300" y2="80" stroke="#F0EDE8" stroke-width="1" />
                            
                            @php
                                $points = [];
                                $count = count($scoreTrends);
                                $step = 300 / ($count - 1);
                                foreach($scoreTrends as $i => $data) {
                                    $x = $i * $step;
                                    $y = 80 - ($data['score'] / 100) * 60;
                                    $points[] = "$x,$y";
                                }
                                $pathData = "M " . implode(" L ", $points);
                            @endphp
                            <path x-ref="trendPath"
                                  d="{{ $pathData }}" 
                                  fill="none" 
                                  stroke="#6646E5" 
                                  stroke-width="4" 
                                  stroke-linecap="round" 
                                  :style="`stroke-dashoffset: ${offset}`"
                                  class="transition-none" />
                            
                            @foreach($scoreTrends as $i => $data)
                                @php
                                    $x = $i * $step;
                                    $y = 80 - ($data['score'] / 100) * 60;
                                    $delay = ($i / (max($count - 1, 1))) * 1500;
                                @endphp
                                <g class="group/point transition-all duration-300 ease-out" 
                                   :style="`opacity: ${showPoints ? 1 : 0}; transform: scale(${showPoints ? 1 : 0.5}); transition-delay: {{ $delay }}ms`"
                                   style="transform-origin: {{ $x }}px {{ $y }}px">
                                    <circle cx="{{ $x }}" cy="{{ $y }}" r="12" fill="transparent" class="cursor-pointer" />
                                    <circle cx="{{ $x }}" cy="{{ $y }}" r="4" fill="white" stroke="#6646E5" stroke-width="3" class="group-hover/point:r-6 transition-all cursor-pointer shadow-sm" />
                                    
                                    <foreignObject x="{{ $x - 90 }}" y="{{ $y - 100 }}" width="180" height="100" class="opacity-0 group-hover/point:opacity-100 transition-opacity pointer-events-none overflow-visible">
                                        <div class="bg-[#1A1714] text-white p-3 rounded-xl shadow-2xl border border-white/10 text-center relative">
                                            <p class="text-[10px] text-[#9E9690] uppercase font-bold tracking-wider mb-1">{{ $data['date'] }}</p>
                                            <p class="text-[11px] font-bold leading-tight line-clamp-2 min-h-[26px]">{{ $data['title'] }}</p>
                                            <p class="text-[16px] font-bold text-[#6646E5] mt-1">{{ $data['score'] }}%</p>
                                            <div class="absolute top-full left-1/2 -translate-x-1/2 w-0 h-0 border-l-[7px] border-l-transparent border-r-[7px] border-r-transparent border-t-[7px] border-t-[#1A1714]"></div>
                                        </div>
                                    </foreignObject>
                                </g>
                            @endforeach
                        </svg>
                        @else
                        <div class="h-20 flex items-center justify-center text-[#7C7167] text-[12px] italic">
                            Take more quizzes to see your trend
                        </div>
                        @endif
                    </div>
                    
                    <div class="flex flex-col items-center md:items-end text-center md:text-right min-w-[100px] shrink-0">
                        <div class="mb-1">
                            <div class="text-[28px] font-semibold text-[#1A1714] leading-none flex items-center justify-center md:justify-end gap-0.5 font-['TASA_Orbiter',sans-serif]">
                                <span x-show="improvementSign !== ''" x-text="improvementSign"></span><span x-text="improvementValue" :style="`filter: blur(${improvementBlur}px)`"></span>%
                            </div>
                            <p class="text-[9px] text-[#7C7167] font-bold uppercase tracking-widest mt-1">Growth Index</p>
                        </div>
                        <div class="mt-2">
                             <span class="{{ $improvement >= 0 ? 'text-[#166534] bg-[#D4F5E3]' : 'text-[#991B1B] bg-red-50' }} px-2.5 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider border {{ $improvement >= 0 ? 'border-[#BBF7D0]' : 'border-red-100' }} shadow-sm">
                                {{ $improvement >= 0 ? 'Trending Up' : 'Trending Down' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-[#E2DDD8] rounded-[20px] shadow-sm flex flex-col w-full overflow-hidden animate-glide-up delay-200 hover:border-[#6646E5]/20 transition-all duration-500 flex-1 min-h-0">
        <div class="w-full overflow-auto h-full">
            <table class="w-full text-left border-collapse min-w-[950px]">
                <thead class="sticky top-0 z-10">
                    <tr class="bg-[#1A1714] border-b border-[#2E2B28]">
                        <th class="py-4 px-6 text-white text-[10px] font-bold tracking-[0.1em] uppercase">Assessment Overview</th>
                        <th class="py-4 px-6 text-white text-[10px] font-bold tracking-[0.1em] uppercase text-center">Type</th>
                        <th class="py-4 px-6 text-white text-[10px] font-bold tracking-[0.1em] uppercase">Completed At</th>
                        <th class="py-4 px-6 text-white text-[10px] font-bold tracking-[0.1em] uppercase text-center">Items</th>
                        <th class="py-4 px-6 text-white text-[10px] font-bold tracking-[0.1em] uppercase text-center">Score Result</th>
                        <th class="py-4 px-6 text-white text-[10px] font-bold tracking-[0.1em] uppercase">Status</th>
                        <th class="py-4 px-6"></th> 
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($attempts as $attempt)
                    @php
                        $percentage = ($attempt->total_questions > 0) ? ($attempt->score / $attempt->total_questions) * 100 : 0;
                    @endphp
                    <tr class="border-b border-[#E2DDD8] hover:bg-[#F4F2FF] transition-all duration-300 last:border-b-0 group">
                        
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-[12px] bg-[#F0EDE8] flex items-center justify-center group-hover:bg-[#6646E5] text-[#7C7167] group-hover:text-white transition-all duration-300 shadow-sm group-hover:shadow-indigo-600/20">
                                    @if($attempt->completed_at)
                                        <x-heroicon-s-clipboard-document-check class="w-5 h-5" />
                                    @else
                                        <x-heroicon-o-clipboard-document-list class="w-5 h-5" />
                                    @endif
                                </div>
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-[#1A1714] text-[14px] font-bold group-hover:text-[#6646E5] transition-colors leading-tight">{{ $attempt->quiz->title ?? 'Untitled Quiz' }}</span>
                                    <span class="text-[11px] text-[#7C7167] font-medium">{{ $attempt->quiz->material->source_name ?? 'Uploaded Material' }}</span>
                                </div>
                            </div>
                        </td>

                        <td class="py-4 px-6 text-center">
                            @php
                                $questionTypes = $attempt->quiz->questions->pluck('type')->unique();
                                $quizTypeLabel = 'Mixed';
                                if ($questionTypes->count() === 1) {
                                    $quizTypeLabel = match($questionTypes->first()) {
                                        'multiple_choice' => 'MCQ',
                                        'true_false' => 'T/F',
                                        'fill_in_the_blank' => 'Fill Blanks',
                                        default => 'Mixed',
                                    };
                                } elseif ($questionTypes->count() === 0) {
                                    $quizTypeLabel = 'Empty';
                                }
                            @endphp
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-black tracking-widest uppercase bg-[#F4F2FF] text-[#6646E5] border border-[#E0D8FC] shadow-sm whitespace-nowrap">
                                {{ $quizTypeLabel }}
                            </span>
                        </td>
                        
                        <td class="py-4 px-6">
                            <div class="flex flex-col gap-0.5">
                                <span class="text-[#1A1714] text-[13px] font-bold tracking-tight">
                                    {{ ($attempt->completed_at ?? $attempt->created_at)->format('M d, Y') }}
                                </span>
                                <span class="text-[#7C7167] text-[11px] font-medium uppercase tracking-wider">
                                    {{ ($attempt->completed_at ?? $attempt->created_at)->format('h:i A') }}
                                </span>
                            </div>
                        </td>
                        
                        <td class="py-4 px-6 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-[#1A1714] text-[14px] font-semibold leading-none">{{ $attempt->total_questions }}</span>
                                <span class="text-[#A39D98] text-[8px] font-bold uppercase tracking-widest mt-1">Questions</span>
                            </div>
                        </td>
                        
                        <td class="py-4 px-6 text-center">
                            <div class="inline-flex flex-col items-center">
                                <span class="text-[#1A1714] text-[18px] font-semibold font-['TASA_Orbiter',sans-serif] leading-none">
                                    {{ round($percentage) }}%
                                </span>
                                <span class="text-[#7C7167] text-[9px] font-bold uppercase tracking-widest mt-1 bg-[#FAF9F7] px-2 py-0.5 rounded-md border border-[#E2DDD8]">
                                    {{ $attempt->score }} Correct
                                </span>
                            </div>
                        </td>
                        
                        <td class="py-4 px-6">
                            @if($percentage >= 75)
                                <div class="bg-[#D4F5E3] text-[#166534] px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider w-max border border-[#BBF7D0] shadow-sm">
                                    Complete
                                </div>
                            @else
                                <div class="bg-[#FEF3C7] text-[#92400E] px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider w-max border border-[#FDE68A] shadow-sm">
                                    Needs Review
                                </div>
                            @endif
                        </td>
                        
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('quizzes.breakdown', $attempt->id) }}" class="inline-flex items-center gap-2 bg-white border border-[#E2DDD8] hover:border-[#6646E5] hover:text-[#6646E5] text-[#1A1714] font-bold text-[9px] uppercase tracking-widest px-3.5 py-2 rounded-[10px] transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 group/btn">
                                <span>Details</span>
                                <x-heroicon-s-arrow-right class="w-3 h-3 transition-transform group-hover/btn:translate-x-1" />
                            </a>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-20 text-center">
                            <div class="flex flex-col items-center justify-center gap-4">
                                <div class="w-16 h-16 bg-[#F0EDE8] rounded-[20px] flex items-center justify-center text-[#7C7167] shadow-inner">
                                    <x-heroicon-o-document-magnifying-glass class="w-8 h-8" />
                                </div>
                                <div class="flex flex-col gap-1">
                                    <h3 class="text-[#1A1714] font-bold text-[18px]">No History Yet</h3>
                                    <p class="text-[#7C7167] font-medium text-[14px] max-w-xs mx-auto">You haven't completed any quizzes. Your performance records will appear here.</p>
                                </div>
                                <a href="{{ route('quizzes.index') }}" class="bg-[#6646E5] text-white px-6 py-2.5 rounded-[12px] font-bold text-[13px] hover:bg-[#5538D4] transition-all shadow-lg shadow-indigo-600/20">
                                    Take Your First Quiz
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    @if($attempts instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-4 flex justify-center flex-shrink-0">
            {{ $attempts->links() }}
        </div>
    @endif

</div>
@endsection
