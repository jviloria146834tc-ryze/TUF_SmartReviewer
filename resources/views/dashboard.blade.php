@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-[1500px] mx-auto w-full h-full flex flex-col pb-4">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4 flex-shrink-0 animate-glide-up">
        <div>
            <div class="inline-flex items-center bg-[#1A1714] px-3.5 py-1.5 rounded-full mb-3 border border-[#2E2B28] shadow-sm">
                <span class="text-white text-[11px] font-black uppercase tracking-[0.05em]">{{ now()->format('l, F j · Y') }}</span>
            </div>
            <h1 class="text-[33px] font-bold text-[#1A1714] tracking-tight"
                x-data="{ 
                    greeting: '',
                    init() {
                        const hour = new Date().getHours();
                        const day = new Date().getDay();
                        if (day === 0 || day === 6) {
                            this.greeting = 'Happy Weekend';
                        } else if (hour >= 5 && hour < 12) {
                            this.greeting = 'Good Morning';
                        } else if (hour >= 12 && hour < 18) {
                            this.greeting = 'Good Afternoon';
                        } else {
                            this.greeting = 'Good Evening';
                        }
                    }
                }">
                <span x-text="greeting">Hello</span>, {{ auth()->check() ? auth()->user()->first_name : 'User' }}
                <p class="text-[16px] text-[#7C7167] font-medium mt-1 tracking-normal">{{ $dashboardMessage }}</p>
            </h1>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto mt-2 md:mt-0">
            <a href="{{ route('materials.upload') }}" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-[12px] font-bold text-[14px] transition-all shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/40 hover:-translate-y-0.5 flex items-center justify-center gap-2 cursor-pointer">
                <x-heroicon-o-cloud-arrow-up class="w-5 h-5" />
                Upload Material
            </a>
            <a href="{{ route('reviewer') }}" class="w-full sm:w-auto bg-white border border-[#E2DDD8] hover:bg-[#F9F8F6] text-[#1A1714] px-6 py-3 rounded-[12px] font-bold text-[14px] transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 flex items-center justify-center gap-2 cursor-pointer">
                <x-heroicon-o-swatch class="w-5 h-5 text-[#6646E5]" />
                Review Material
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-5 flex-shrink-0 animate-glide-up delay-100">
        <x-metric-card title="Materials Uploaded" value="{{ $stats['materials_count'] }}" badgeText="{{ $stats['materials_delta'] }}" badgeType="success" icon="heroicon-o-folder-open" />
        <x-metric-card title="Mastered Flashcards" value="{{ $stats['mastered_flashcards'] }}" badgeText="{{ $stats['mastery_delta'] }}" badgeType="success" icon="heroicon-o-academic-cap" />
        <x-metric-card title="Quizzes Taken" value="{{ $stats['quizzes_count'] }}" badgeText="{{ $stats['quizzes_delta'] }}" badgeType="success" icon="heroicon-o-document-duplicate" />
        @php
            $avgScoreBadge = null;
            if ($stats['avg_score_delta'] > 0) {
                $avgScoreBadge = '↑ ' . $stats['avg_score_delta'] . '%';
            } elseif ($stats['avg_score_delta'] < 0) {
                $avgScoreBadge = '↓ ' . abs($stats['avg_score_delta']) . '%';
            }
        @endphp
        <x-metric-card title="Avg. Quiz Score" value="{{ $stats['avg_score'] }}" 
                       badgeText="{{ $avgScoreBadge }}" 
                       badgeType="{{ $stats['avg_score_delta'] >= 0 ? 'success' : 'warning' }}"
                       icon="heroicon-o-chart-bar" />
        <x-metric-card title="Study Streak" icon="heroicon-o-fire">
            <div class="flex items-center gap-2.5" 
                 x-data="{ value: 0, target: {{ auth()->user()->current_streak }}, blur: 4 }" 
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
                <x-heroicon-s-fire class="w-12 h-12" style="color: {{ auth()->user()->streak_color }}" />
                <div class="flex items-baseline gap-3">
                    <span x-text="value"
                          :style="`color: {{ auth()->user()->streak_color }}; filter: blur(${blur}px)`"
                          class="text-[44px] font-black leading-none tracking-tighter"></span>
                    <span class="text-[14px] font-black text-[#7C7167] whitespace-nowrap mb-1 tracking-wider uppercase"
                          x-html="value <= 1 ? 'day&nbsp;in&nbsp;a&nbsp;row' : 'days&nbsp;in&nbsp;a&nbsp;row'"></span>
                </div>
            </div>
        </x-metric-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5 flex-[3] min-h-0 animate-glide-up delay-200">
        
        <div x-data class="bg-white border border-[#E2DDD8] rounded-[16px] shadow-sm flex flex-col h-full min-h-0 hover:border-[#6646E5]/40 transition-all duration-300 relative group overflow-hidden">
            <div class="p-5 flex justify-between items-center flex-shrink-0 relative z-10">
                <div class="bg-[#1A1714] px-3 py-1 rounded-full shadow-sm">
                    <h3 class="text-[10px] font-bold text-white uppercase tracking-widest">Recent Materials</h3>
                </div>
                <a href="{{ route('materials.index') }}" class="text-[#7C7167] hover:text-[#1A1714] text-[12px] font-bold transition-all flex items-center gap-1 group/btn">
                    View all
                    <x-heroicon-o-arrow-small-right class="w-4 h-4 group-hover/btn:translate-x-0.5 transition-transform" />
                </a>
            </div>

            <div class="px-5 pb-5 flex-1 flex flex-col min-h-0 relative z-10">
                <div class="flex flex-col gap-2.5 overflow-y-auto flex-1 min-h-0 pr-1">
                    @forelse($materials as $material)
                    @php
                        $extension = pathinfo($material->file_path ?? $material->original_path ?? '', PATHINFO_EXTENSION);
                        $iconName = match(strtolower($extension)) {
                            'pdf', 'docx' => 'heroicon-s-document-text',
                            'jpg', 'jpeg', 'png' => 'heroicon-s-photo',
                            default => 'heroicon-s-document',
                        };
                        $iconColor = match(strtolower($extension)) {
                            'pdf' => 'text-[#E11D48]',
                            'docx' => 'text-[#2563EB]',
                            'jpg', 'jpeg', 'png' => 'text-[#2563EB]',
                            default => 'text-[#4B5563]',
                        };
                        $iconBg = match(strtolower($extension)) {
                            'pdf' => 'bg-[#FFE4E6]',
                            'docx' => 'bg-[#DBEAFE]',
                            'jpg', 'jpeg', 'png' => 'bg-[#DBEAFE]',
                            default => 'bg-[#F3F4F6]',
                        };
                        $ext = strtolower($extension);
                        $type = $ext == 'pdf' ? 'pdf' : (in_array($ext, ['jpg', 'jpeg', 'png']) ? 'image' : 'other');
                        $url = $material->public_url ?? '#';
                    @endphp
                    <div @click="$dispatch('open-preview', { url: '{{ $url }}', type: '{{ $type }}' })" class="flex items-center gap-3.5 p-2.5 hover:bg-[#F9F8F6] rounded-xl transition-colors cursor-pointer border border-transparent hover:border-[#E2DDD8] flex-shrink-0 group/item">
                       <div class="w-11 h-11 {{ $iconBg }} rounded-xl flex items-center justify-center transition-transform group-hover/item:scale-105">
                            <x-dynamic-component :component="$iconName" class="w-5.5 h-5.5 {{ $iconColor }}" />
                        </div>
                        <div class="flex-1">
                            <h4 class="text-[14px] font-bold text-[#1A1714] group-hover/item:text-[#6646E5] transition-colors leading-tight">{{ $material->title }}</h4>
                            <p class="text-[12px] text-[#7C7167] mt-0.5">
                                @if($material->source_name)
                                    {{ $material->source_name }} <span class="mx-1.5">•</span> {{ $material->created_at->format('M d, Y • h:i A') }}
                                @else
                                    Uploaded on {{ $material->created_at->format('M d, Y • h:i A') }}
                                @endif
                            </p>
                        </div>
                        <div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wide uppercase
                                {{ $material->status == 'completed' ? 'bg-[#D4F5E3] text-[#166534]' : ($material->status == 'processing' ? 'bg-[#FEF3C7] text-[#92400E]' : 'bg-gray-100 text-gray-600') }}">
                                {{ $material->status }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center h-full text-[#7C7167] py-8">
                        <p class="text-[14px]">No materials uploaded yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white border border-[#E2DDD8] rounded-[16px] shadow-sm flex flex-col h-full min-h-0 hover:border-[#6646E5]/40 transition-all duration-300 relative group overflow-hidden">
            <div class="p-5 flex justify-between items-center flex-shrink-0 relative z-10">
                <div class="bg-[#1A1714] px-3 py-1 rounded-full shadow-sm">
                    <h3 class="text-[10px] font-bold text-white uppercase tracking-widest">Active Quizzes</h3>
                </div>
                <a href="{{ route('quizzes.index') }}" class="text-[#7C7167] hover:text-[#1A1714] text-[12px] font-bold transition-all flex items-center gap-1 group/btn">
                    All
                    <x-heroicon-o-arrow-small-right class="w-4 h-4 group-hover/btn:translate-x-0.5 transition-transform" />
                </a>
            </div>
            
            <div class="px-5 pb-5 flex-1 flex flex-col min-h-0 relative z-10">
                <div class="flex flex-col gap-2.5 overflow-y-auto flex-1 min-h-0 pr-1">
                    @forelse($quizzes as $quiz)
                    @php
                        $attempt = auth()->user()->quizAttempts()->where('quiz_id', $quiz->id)->latest()->first();
                        $progressPercent = 0;
                        if ($attempt && $attempt->total_questions > 0) {
                            $progressVal = count((array)($attempt->answers_json ?? []));
                            $progressPercent = ($progressVal / $attempt->total_questions) * 100;
                        }

                        $questionTypes = $quiz->questions->pluck('type')->unique();
                        $quizTypeLabel = 'Mixed';
                        if ($questionTypes->count() === 1) {
                            $quizTypeLabel = match($questionTypes->first()) {
                                'multiple_choice' => 'MCQ',
                                'true_false' => 'T/F',
                                'fill_in_the_blank' => 'Fill in the blanks',
                                default => 'Mixed',
                            };
                        } elseif ($questionTypes->count() === 0) {
                            $quizTypeLabel = 'Empty';
                        }
                    @endphp
                    <div class="flex items-center gap-3.5 p-3.5 border border-[#E2DDD8] rounded-[12px] hover:border-[#6646E5] transition-colors flex-shrink-0 group/item"
                         x-data="{ showProgress: false }"
                         x-init="setTimeout(() => showProgress = true, {{ 600 + ($loop->index * 100) }})">

                        <div class="w-11 h-11 bg-[#FEF3C7] rounded-xl flex items-center justify-center flex-shrink-0">
                             <x-heroicon-o-clipboard-document-list class="w-5.5 h-5.5 text-[#92400E]" />
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="text-[14px] font-bold text-[#1A1714] leading-tight truncate">{{ $quiz->title }}</h4>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-semibold tracking-wide uppercase bg-[#F4F2FF] text-[#6646E5] border border-[#E0D8FC] flex-shrink-0">
                                    {{ $quizTypeLabel }}
                                </span>
                            </div>
                            
                            <div class="mt-1.5 flex items-center gap-2.5">
                                <div class="flex-1 bg-[#F0EDE8] h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-[#6646E5] h-full rounded-full transition-all duration-1000 ease-out" 
                                         :style="`width: ${showProgress ? '{{ $progressPercent }}%' : '0%'}`"
                                         style="width: 0%"></div>
                                </div>
                                <span class="text-[10px] text-[#7C7167] font-bold min-w-[45px]">
                                    {{ $attempt ? 'IN PROGRESS' : 'START' }}
                                </span>
                            </div>
                        </div>
                        <a href="{{ route('quizzes.session', $quiz->id) }}" class="px-3 py-1.5 border border-[#E2DDD8] rounded-lg text-[#1A1714] text-[11px] font-bold hover:bg-[#F9F8F6] transition-colors whitespace-nowrap">
                            {{ $attempt ? 'Continue' : 'Start' }} &rarr;
                        </a>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center h-full text-[#7C7167] py-8">
                        <p class="text-[14px]">No active quizzes yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-5 mb-4 flex-shrink-0 min-h-[290px] animate-glide-up delay-300 overflow-visible relative z-30">
        <!-- Weekly Performance Chart -->
        <div class="lg:col-span-2 bg-white border border-[#E2DDD8] rounded-[16px] shadow-sm flex flex-col relative overflow-visible hover:border-[#6646E5]/40 transition-all duration-300 group/weekly">
            <!-- Premium Header -->
            <div class="bg-[#1A1714] h-[52px] px-5 flex justify-between items-center flex-shrink-0 relative z-10 rounded-t-[15px]">
                <h3 class="text-[10px] font-bold text-white uppercase tracking-widest leading-none">Weekly Performance</h3>
                <span class="bg-white/10 text-white text-[9px] font-black px-2.5 py-1 rounded-[8px] uppercase tracking-wider border border-white/5 leading-none">Stay consistent!</span>
            </div>
            
            <div class="p-5 flex-1 flex items-end justify-between gap-3.5 px-4 pb-8 min-h-0 relative z-20 overflow-visible">
                @php
                    $counts = array_column($weeklyPerformance, 'count');
                    $maxActivity = count($counts) > 0 ? max(max($counts), 1) : 1;
                @endphp
                @foreach($weeklyPerformance as $day => $data)
                @php
                    $count = $data['count'];
                    $details = $data['details'];
                    $height = ($count / $maxActivity) * 80 + 5; // Min 5% height for visibility
                @endphp
                <div class="flex flex-col items-center gap-4 flex-1 h-full justify-end group/day relative overflow-visible">
                    <!-- Invisible Hover Trigger (Full Column) -->
                    <div class="absolute inset-0 z-30 cursor-pointer"></div>
                    
                    <!-- High-Polish Detailed Tooltip -->
                    <div class="absolute bottom-full mb-4 left-1/2 -translate-x-1/2 bg-[#1A1714] text-white text-[11px] py-4 px-5 rounded-2xl opacity-0 group-hover/day:opacity-100 transition-all duration-300 pointer-events-none z-[100] shadow-2xl min-w-[220px] border border-white/10 scale-95 group-hover/day:scale-100 origin-bottom">
                        <div class="flex flex-col gap-1 mb-4">
                            <span class="text-[10px] text-[#9E9690] uppercase tracking-[0.1em] font-black">{{ $data['full_day'] }}</span>
                            <div class="flex justify-between items-center">
                                <span class="text-[15px] font-bold">Daily Activity</span>
                                <span class="bg-[#6646E5] text-[10px] px-2.5 py-0.5 rounded-full font-bold">{{ $count }} {{ $count == 1 ? 'activity' : 'activities' }}</span>
                            </div>
                        </div>

                        <div class="space-y-4 border-t border-white/10 pt-4">
                            @if(empty($details))
                                <p class="text-white/40 italic text-center py-1">No specific tasks recorded</p>
                            @else
                                @foreach($details as $mTitle => $activity)
                                    <div class="flex flex-col gap-1.5 text-left text-[12px]">
                                        <p class="font-bold text-[#E0D8FC] truncate leading-tight">{{ $mTitle }}</p>
                                        <div class="flex flex-wrap gap-x-4 gap-y-1.5 text-[10px] text-white/60 font-medium">
                                            @if(isset($activity['uploaded']))
                                                <span class="flex items-center gap-1.5">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-[#A78BFA]"></div>
                                                    Uploaded
                                                </span>
                                            @endif
                                            @if(isset($activity['quizzes']))
                                                <span class="flex items-center gap-1.5">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-[#60A5FA]"></div>
                                                    {{ $activity['quizzes'] }} {{ $activity['quizzes'] == 1 ? 'Quiz' : 'Quizzes' }}
                                                </span>
                                            @endif
                                            @if(isset($activity['cards']))
                                                <span class="flex items-center gap-1.5">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-[#FBBF24]"></div>
                                                    {{ $activity['cards'] }} {{ $activity['cards'] == 1 ? 'Card' : 'Cards' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        
                        <!-- Tooltip Arrow -->
                        <div class="absolute top-full left-1/2 -translate-x-1/2 w-0 h-0 border-l-[8px] border-l-transparent border-r-[8px] border-r-transparent border-t-[8px] border-t-[#1A1714]"></div>
                    </div>

                    <div x-data="{ show: false }"
                         x-init="setTimeout(() => show = true, {{ $loop->index * 100 }})"
                         class="w-full max-w-[40px] bg-[#6646E5] rounded-t-lg transition-all duration-1000 ease-out relative z-10" 
                         :style="`height: ${show ? '{{ $height }}%' : '0%'}; opacity: {{ $count > 0 ? '1' : '0.2' }}`">
                    </div>
                    <span class="text-[12px] {{ $day == now()->format('D') ? 'text-[#6646E5] font-bold' : 'text-[#7C7167] font-medium' }} flex-shrink-0 pb-1">{{ $day }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Mastery Insights -->
        <div class="bg-white border border-[#E2DDD8] rounded-[16px] shadow-sm flex flex-col relative overflow-visible hover:border-[#6646E5]/40 transition-all duration-300 group/mastery-card">
            <!-- Premium Header -->
            <div class="bg-[#1A1714] h-[52px] px-5 flex justify-between items-center flex-shrink-0 relative z-10 rounded-t-[15px]">
                <h3 class="text-[10px] font-bold text-white uppercase tracking-widest leading-none">Mastery Insights</h3>
                <x-heroicon-o-academic-cap class="w-4 h-4 text-white/50" />
            </div>

            <div class="p-5 flex-1 flex flex-col items-center justify-center text-center relative z-20 overflow-visible"
                 x-data="{ 
                    percent: 0, 
                    target: {{ $masteryPercentage }}, 
                    offset: {{ 2 * pi() * 44 }}, 
                    circumference: {{ 2 * pi() * 44 }} 
                 }"
                 x-init="
                    setTimeout(() => {
                        let start_time = performance.now();
                        let duration = 1500;
                        let step = (timestamp) => {
                            let progress = Math.min((timestamp - start_time) / duration, 1);
                            let easeOut = 1 - Math.pow(1 - progress, 3);
                            percent = Math.floor(easeOut * target);
                            offset = circumference - (easeOut * (target / 100) * circumference);
                            if(progress < 1) requestAnimationFrame(step);
                        };
                        requestAnimationFrame(step);
                    }, 400);
                 ">
                <div class="w-32 h-32 flex items-center justify-center mb-4 relative group/mastery cursor-pointer overflow-visible" 
                     x-data="{ showTooltip: false }" 
                     @click="showTooltip = !showTooltip" 
                     @click.away="showTooltip = false">
                    <!-- High-Polish Detailed Tooltip -->
                    <div class="absolute bottom-full mb-4 left-1/2 -translate-x-1/2 bg-[#1A1714] text-white text-[11px] py-4 px-5 rounded-2xl transition-all duration-300 z-[100] shadow-2xl min-w-[260px] border border-white/10 origin-bottom cursor-default"
                         :class="showTooltip ? 'opacity-100 scale-100 pointer-events-auto' : 'opacity-0 scale-95 pointer-events-none group-hover/mastery:opacity-100 group-hover/mastery:scale-100 group-hover/mastery:pointer-events-auto'"
                         @click.stop>
                        <div class="flex flex-col gap-1 mb-4 text-left">
                            <span class="text-[10px] text-[#9E9690] uppercase tracking-[0.1em] font-black">
                                @if($masteryMaterialsCount > 5)
                                    Top 5 of {{ $masteryMaterialsCount }} Materials
                                @else
                                    Retention Breakdown
                                @endif
                            </span>
                            <div class="flex justify-between items-center gap-4">
                                <span class="text-[15px] font-bold">Concept Mastery</span>
                                <span class="bg-[#6646E5] text-[10px] px-2.5 py-0.5 rounded-full font-bold whitespace-nowrap flex-shrink-0">{{ $masteryPercentage }}% Overall</span>
                            </div>
                        </div>

                        <div class="space-y-4 border-t border-white/10 pt-4 text-left max-h-[280px] overflow-y-auto custom-scrollbar pr-1">
                            @forelse($masteryDetails as $detail)
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex justify-between items-start gap-3">
                                        <p class="font-bold text-[#E0D8FC] leading-tight flex-1 line-clamp-2">{{ $detail['title'] }}</p>
                                        <span class="text-[#A78BFA] font-black text-[12px] flex-shrink-0">{{ $detail['percent'] }}%</span>
                                    </div>
                                    <div class="w-full bg-white/10 h-1 rounded-full overflow-hidden">
                                        <div class="bg-[#6646E5] h-full rounded-full" style="width: {{ $detail['percent'] }}%"></div>
                                    </div>
                                    <p class="text-[9px] text-white/40">{{ $detail['mastered'] }} of {{ $detail['total'] }} concepts mastered</p>
                                </div>
                            @empty
                                <p class="text-white/40 italic text-center py-1">No mastery data available</p>
                            @endforelse
                        </div>
                        
                        <!-- Tooltip Arrow -->
                        <div class="absolute top-full left-1/2 -translate-x-1/2 w-0 h-0 border-l-[8px] border-l-transparent border-r-[8px] border-r-transparent border-t-[8px] border-t-[#1A1714]"></div>
                    </div>

                    <span class="text-[28px] font-black text-[#1A1714] z-10" x-text="percent + '%'">0%</span>
                    @php
                        $radius = 44;
                        $circumference = 2 * pi() * $radius;
                    @endphp
                    <svg class="absolute inset-0 w-full h-full -rotate-90" viewBox="0 0 100 100">
                        <!-- Background Track -->
                        <circle cx="50" cy="50" r="{{ $radius }}" stroke="#F0EDE8" stroke-width="8" fill="transparent" />
                        <!-- Progress Ring -->
                        <circle cx="50" cy="50" r="{{ $radius }}" stroke="#6646E5" stroke-width="8" fill="transparent" 
                                stroke-dasharray="{{ $circumference }}" 
                                :stroke-dashoffset="circumference - (percent / 100) * circumference" 
                                stroke-linecap="round" 
                                class="transition-none" />
                    </svg>
                </div>
                <h4 class="text-[16px] font-bold text-[#1A1714] mb-1">Concept Mastery</h4>
                <p class="text-[12px] text-[#7C7167] max-w-[180px]">Overall retention across all materials.</p>
            </div>
            
            <div class="p-4 pt-4 border-t border-[#F0EDE8] flex justify-between items-center text-[11px] font-semibold relative z-10">
                <span class="text-[#7C7167]">Recent Progress</span>
                <span class="text-[#166534] bg-[#D4F5E3] px-2 py-0.5 rounded-full">Keep it up!</span>
            </div>
        </div>

        <!-- Score Trends (Line Graph) -->
        <div class="bg-white border border-[#E2DDD8] rounded-[16px] shadow-sm flex flex-col relative overflow-visible hover:border-[#6646E5]/40 transition-all duration-300 group/trends-card">
            <!-- Premium Header -->
            <div class="bg-[#1A1714] h-[52px] px-5 flex justify-between items-center flex-shrink-0 relative z-10 rounded-t-[15px]">
                <h3 class="text-[10px] font-bold text-white uppercase tracking-widest leading-none">Score Trends</h3>
                <a href="{{ route('quizzes.results') }}" class="text-[#9E9690] hover:text-white text-[12px] font-bold transition-all flex items-center gap-1 group/btn">
                    Results
                    <x-heroicon-o-arrow-small-right class="w-4 h-4 group-hover/btn:translate-x-0.5 transition-transform" />
                </a>
            </div>

            <div class="p-5 flex-1 flex flex-col items-center justify-center relative pt-8 pb-3 z-20 overflow-visible"
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
                    // Line animation
                    setTimeout(() => {
                        const path = $refs.trendPath;
                        if (path) {
                            length = path.getTotalLength();
                            offset = length;
                            path.style.strokeDasharray = length;
                            path.style.strokeDashoffset = length;
                            
                            showPoints = true; // Trigger dot sequential appearance
                            
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

                    // Improvement text animation
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
                @if(count($scoreTrends) > 1)
                <svg viewBox="0 0 200 100" class="w-full h-24 overflow-visible">
                    <!-- Grid Lines -->
                    <line x1="0" y1="20" x2="200" y2="20" stroke="#F0EDE8" stroke-width="1" />
                    <line x1="0" y1="50" x2="200" y2="50" stroke="#F0EDE8" stroke-width="1" />
                    <line x1="0" y1="80" x2="200" y2="80" stroke="#F0EDE8" stroke-width="1" />
                    
                    @php
                        $points = [];
                        $count = count($scoreTrends);
                        $step = 200 / ($count - 1);
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
                          stroke-width="3" 
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
                            <!-- Invisible trigger area for easier hovering -->
                            <circle cx="{{ $x }}" cy="{{ $y }}" r="12" fill="transparent" class="cursor-pointer" />
                            <circle cx="{{ $x }}" cy="{{ $y }}" r="4" fill="white" stroke="#6646E5" stroke-width="2" class="group-hover/point:r-6 transition-all cursor-pointer" />
                            
                            <!-- SVG Tooltip - Positioned higher with better boundaries -->
                            <foreignObject x="{{ $x - 90 }}" y="{{ $y - 120 }}" width="180" height="120" class="opacity-0 group-hover/point:opacity-100 transition-opacity pointer-events-none overflow-visible">
                                <div class="bg-[#1A1714] text-white p-3 rounded-xl shadow-2xl border border-white/10 text-center relative z-[100]">
                                    <p class="text-[10px] text-[#9E9690] uppercase font-bold tracking-wider mb-1">{{ $data['date'] }}</p>
                                    <p class="text-[11px] font-bold leading-tight line-clamp-2 min-h-[26px]">{{ $data['title'] }}</p>
                                    <p class="text-[16px] font-bold text-[#6646E5] mt-1">{{ $data['score'] }}%</p>
                                    <!-- Tiny Arrow -->
                                    <div class="absolute top-full left-1/2 -translate-x-1/2 w-0 h-0 border-l-[7px] border-l-transparent border-r-[7px] border-r-transparent border-t-[7px] border-t-[#1A1714]"></div>
                                </div>
                            </foreignObject>
                        </g>
                    @endforeach
                </svg>
                @else
                <div class="h-24 flex items-center justify-center text-[#7C7167] text-[12px] italic">
                    Need more quiz data
                </div>
                @endif
                
                <div class="mt-8 text-center">
                    <div class="text-[24px] font-black text-[#1A1714] flex items-center justify-center gap-0.5">
                        <span x-show="improvementSign !== ''" x-text="improvementSign"></span><span x-text="improvementValue" :style="`filter: blur(${improvementBlur}px)`"></span>%
                    </div>
                    <p class="text-[12px] text-[#7C7167] font-medium mt-1">Score trend (last 10 quizzes)</p>
                </div>
            </div>
            
            <div class="p-4 pt-4 border-t border-[#F0EDE8] flex justify-between items-center text-[11px] font-semibold z-10">
                <span class="text-[#7C7167]">Recent Progress</span>
                <span class="{{ $improvement >= 0 ? 'text-[#166534] bg-[#D4F5E3]' : 'text-[#991B1B] bg-red-50' }} px-2 py-0.5 rounded-full">
                    {{ $improvement >= 0 ? 'Trending Up' : 'Trending Down' }}
                </span>
            </div>
        </div>
    </div>

</div>
@endsection
