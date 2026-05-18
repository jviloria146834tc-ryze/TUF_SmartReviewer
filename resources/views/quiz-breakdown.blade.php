@extends('layouts.app')

@section('title', 'Quiz Breakdown: ' . $attempt->quiz->title)

@section('content')
<div x-data="quizConfig()" class="max-w-[1500px] mx-auto w-full pb-12 flex flex-col h-full relative">

    <!-- Progress Overlay for Quiz Generation -->
    <div x-show="isProcessing" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-[#F0EDE8]/90 backdrop-blur-sm z-[150] flex flex-col items-center justify-center">
        
        <div class="w-full max-w-md bg-white p-10 rounded-[32px] shadow-2xl border border-[#E2DDD8] relative overflow-hidden">
            <h2 class="text-[26px] font-bold text-[#1A1714] mb-8 font-['Inter'] text-center tracking-tight">Generating Quiz</h2>
            
            <div class="space-y-8">
                <div class="flex items-center gap-5 transition-all duration-500" :class="progressStage !== 'generating' ? 'opacity-100' : 'opacity-50'">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500"
                         :class="progressStage !== 'generating' ? 'bg-[#6646E5] text-white shadow-lg shadow-[#6646E5]/20' : 'bg-[#D4F5E3] text-[#166534]'">
                        <template x-if="progressStage !== 'generating'">
                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                        <template x-if="progressStage === 'generating'">
                            <x-heroicon-s-check class="w-6 h-6" />
                        </template>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-[16px] transition-colors" :class="progressStage !== 'generating' ? 'text-[#1A1714]' : 'text-[#7C7167]'">Phase: Crafting</span>
                        <span class="text-[13px] font-medium text-[#7C7167]">AI is designing your questions...</span>
                    </div>
                </div>

                <div class="flex items-center gap-5 transition-all duration-500" :class="progressStage === 'generating' ? 'opacity-100' : 'opacity-40'">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500"
                         :class="progressStage === 'completed' ? 'bg-[#D4F5E3] text-[#166534]' : (progressStage === 'generating' ? 'bg-[#6646E5] text-white shadow-lg shadow-[#6646E5]/20' : 'bg-gray-100 text-gray-400')">
                        <template x-if="progressStage === 'generating'">
                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                        <template x-if="progressStage !== 'generating'">
                            <template x-if="progressStage === 'completed'">
                                <x-heroicon-s-check class="w-6 h-6" />
                            </template>
                            <template x-if="progressStage !== 'completed'">
                                <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                            </template>
                        </template>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-[16px] transition-colors" :class="progressStage === 'generating' ? 'text-[#1A1714]' : 'text-[#7C7167]'">Phase: Ready</span>
                        <span class="text-[13px] font-medium text-[#7C7167]">Optimizing your study session...</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-10 flex flex-col gap-2">
                <div class="flex justify-between items-end">
                    <span class="text-[12px] font-black uppercase tracking-widest text-[#6646E5]" x-text="progressStage !== 'generating' ? 'Crafting' : 'Finalizing'"></span>
                </div>
                <div class="w-full bg-[#F0EDE8] h-3 rounded-full overflow-hidden p-0.5">
                    <div class="h-full bg-[#6646E5] rounded-full transition-all duration-[2000ms] ease-out shadow-[0_0_10px_rgba(102,70,229,0.4)]"
                         :style="progressStage === 'generating' ? 'width: 100%' : (progressStage === 'extracting' ? 'width: 60%' : 'width: 10%')"></div>
                </div>
            </div>

            <template x-if="errorMessage">
                <p class="text-sm text-red-600 mt-4 text-center font-semibold" x-text="errorMessage"></p>
            </template>
        </div>
    </div>

    <div class="mb-8 animate-glide-up">
        <a href="{{ route('quizzes.results') }}" class="text-[#7C7167] text-[13px] font-semibold hover:text-[#1A1714] flex items-center gap-1 mb-2 transition-colors w-max">
            &larr; Back to Results
        </a>
        <h1 class="text-[33px] font-bold text-[#1A1714] tracking-tight font-['Special_Gothic_Expanded_One',sans-serif]">
            Quiz Breakdown
        </h1>
    </div>

    @if($attempt)
    <div class="flex flex-col-reverse lg:flex-row gap-8 items-start pb-12">
        
        <div class="flex-1 w-full flex flex-col gap-4 mb-8">
            
            @php
                $questions = $attempt->quiz->questions;
                $userAnswers = $attempt->answers_json;
            @endphp

            @foreach($questions as $index => $q)
            @php
                $result = $userAnswers[$q->id] ?? null;
                $isCorrect = $result['is_correct'] ?? false;
            @endphp
            <div class="bg-white border border-[#E2DDD8] rounded-[16px] p-5 md:p-6 shadow-sm flex gap-4 md:gap-5 items-start hover:border-[#6646E5]/40 transition-all duration-300 animate-glide-up group" style="animation-delay: {{ 100 + ($index * 50) }}ms">
                
                @if($isCorrect)
                    <div class="w-8 h-8 rounded-[8px] bg-[#D4F5E3] text-[#166534] flex items-center justify-center flex-shrink-0 shadow-sm mt-0.5 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                @else
                    <div class="w-8 h-8 rounded-[8px] bg-[#FCE7F3] text-[#9D174D] flex items-center justify-center flex-shrink-0 shadow-sm mt-0.5 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                @endif

                <div class="flex flex-col gap-2 w-full">
                    <h3 class="text-[15px] md:text-[16px] font-semibold text-[#1A1714] leading-snug">
                        {{ $loop->iteration }}. {{ $q->question_text }}
                    </h3>
                    
                    <div class="flex flex-col gap-1.5 mt-1">
                        <div class="text-[13px] md:text-[14px]">
                            <span class="text-[#7C7167]">Your answer:</span> 
                            <span class="font-medium ml-1 {{ !$isCorrect ? 'text-[#9D174D] line-through opacity-80' : 'text-[#1A1714]' }}">
                                {{ $result['user_answer'] ?? 'No answer' }}
                            </span>
                        </div>

                        @if(!$isCorrect)
                        <div class="text-[13px] md:text-[14px]">
                            <span class="text-[#166534] font-medium">Correct answer:</span> 
                            <span class="font-medium ml-1 text-[#166534]">
                                {{ $result['correct_answer'] ?? $q->correct_answer }}
                            </span>
                        </div>
                        @endif

                        @if($q->explanation)
                        <div class="mt-2 p-3 bg-[#F9F8F6] rounded-lg border border-[#E2DDD8] text-[13px] text-[#7C7167] group-hover:bg-[#F4F2FF] group-hover:border-[#6646E5]/20 transition-colors">
                            <span class="font-bold text-[#1A1714]">Explanation:</span> {{ $q->explanation }}
                        </div>
                        @endif
                    </div>
                </div>

            </div>
            @endforeach

        </div>

        <div class="w-full lg:w-[380px] flex flex-col gap-4 lg:sticky lg:top-8 flex-shrink-0 animate-glide-up" style="animation-delay: 200ms">
            
            <div class="bg-white border border-[#E2DDD8] rounded-[24px] p-6 md:p-8 shadow-sm flex flex-col items-center text-center hover:border-[#6646E5]/40 transition-all duration-300">
                <h2 class="text-[13px] font-bold text-[#7C7167] uppercase tracking-[0.1em] mb-8">Final Assessment</h2>
                
                @php
                    $percentage = ($attempt->total_questions > 0) ? ($attempt->score / $attempt->total_questions) * 100 : 0;
                    $radius = 44;
                    $circumference = 2 * pi() * $radius;
                @endphp

                <h3 class="text-[24px] font-bold text-[#1A1714] font-['Inter',sans-serif] tracking-tight mb-1">
                    {{ $percentage >= 75 ? 'Great Work!' : 'Keep Practicing!' }}
                </h3>
                <p class="text-[#7C7167] text-[14px] mb-10 font-medium">{{ $attempt->score }} correct out of {{ $attempt->total_questions }} items</p>

                <div class="relative w-[160px] h-[160px] flex items-center justify-center mb-10"
                     x-data="{ 
                        percent: 0, 
                        target: {{ round($percentage) }},
                        circumference: {{ $circumference }}
                     }"
                     x-init="
                        setTimeout(() => {
                            let start_time = performance.now();
                            let duration = 1500;
                            let step = (timestamp) => {
                                let progress = Math.min((timestamp - start_time) / duration, 1);
                                let easeOut = 1 - Math.pow(1 - progress, 3);
                                percent = Math.floor(easeOut * target);
                                if(progress < 1) requestAnimationFrame(step);
                            };
                            requestAnimationFrame(step);
                        }, 500);
                     ">
                    <svg class="absolute inset-0 w-full h-full -rotate-90" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="{{ $radius }}" stroke="#F0EDE8" stroke-width="8" fill="transparent" />
                        <circle cx="50" cy="50" r="{{ $radius }}" stroke="#6646E5" stroke-width="8" fill="transparent" 
                                stroke-dasharray="{{ $circumference }}" 
                                :stroke-dashoffset="circumference - (percent / 100) * circumference" 
                                stroke-linecap="round" 
                                class="transition-none shadow-[0_0_10px_rgba(102,70,229,0.2)]" />
                    </svg>
                    <div class="flex flex-col items-center justify-center">
                        <span class="text-[42px] font-bold text-[#1A1714] font-['TASA_Orbiter',sans-serif] leading-none" x-text="percent + '%'">0%</span>
                    </div>
                </div>

                <div class="w-full border-t border-[#F0EDE8] pt-6 flex justify-between items-center text-[10px] font-black uppercase tracking-wider">
                    <div class="flex flex-col items-start gap-1">
                        <span class="text-[#9E9690]">Time taken</span>
                        <span class="text-[#1A1714] text-[13px]">
                            @if($attempt->time_taken)
                                {{ floor($attempt->time_taken / 60) }}m {{ $attempt->time_taken % 60 }}s
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                    <div class="flex flex-col items-end gap-1 text-right">
                        <span class="text-[#9E9690]">Finished at</span>
                        <span class="text-[#1A1714] text-[13px] whitespace-nowrap">
                            {{ ($attempt->completed_at ?? $attempt->created_at)->format('M d, Y • h:i A') }}
                        </span>
                    </div>
                </div>

            </div>

            <div class="flex flex-col gap-3 w-full">
                <a href="{{ route('quizzes.session', $attempt->quiz_id) }}" class="w-full bg-[#1A1714] hover:bg-[#2E2B28] text-white px-6 py-4 rounded-[16px] font-bold text-[15px] transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <x-heroicon-o-arrow-path class="w-5 h-5 text-[#A78BFA]" />
                    Retry Same Quiz
                </a>
                <button @click.prevent="openConfig" class="w-full bg-[#6646E5] hover:bg-[#5538D4] text-white px-6 py-4 rounded-[16px] font-bold text-[15px] transition-all shadow-lg shadow-[#6646E5]/20 hover:shadow-[#6646E5]/40 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <x-heroicon-o-sparkles class="w-5 h-5" />
                    Regenerate & Custom Retry
                </button>
                <a href="{{ route('reviewer') }}" class="w-full bg-white border border-[#E2DDD8] hover:bg-[#F9F8F6] text-[#1A1714] px-6 py-4 rounded-[16px] font-bold text-[15px] transition-all shadow-sm hover:shadow-md hover:border-[#6646E5]/40 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <x-heroicon-o-book-open class="w-5 h-5 text-[#6646E5]" />
                    Review Material
                </a>
            </div>

        </div>

    </div>
    @else
    <div class="bg-white border border-[#E2DDD8] rounded-[16px] p-12 text-center shadow-sm">
        <p class="text-[#7C7167]">No quiz attempt data found.</p>
        <a href="{{ route('quizzes.index') }}" class="mt-4 inline-block text-[#6646E5] font-semibold">Take a quiz</a>
    </div>
    @endif

    <!-- Quiz Config Modal -->
    <div x-show="showConfig" x-cloak class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-[#1A1714]/60 backdrop-blur-sm">
        <div @click.away="showConfig = false"
             x-show="showConfig"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative w-full max-w-lg p-6 mx-4 bg-white border border-[#E2DDD8] rounded-[24px] shadow-2xl">
            
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-[22px] font-bold text-[#1A1714] font-['Inter']">Configure Your Quiz</h3>
                <button @click="showConfig = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="space-y-8">
                <div class="flex flex-col gap-4">
                    <label class="text-[14px] font-bold text-[#1A1714] uppercase tracking-wider">How many items?</label>
                    <div class="grid grid-cols-4 gap-3">
                        <template x-for="count in [5, 10, 15, 20, 30, 40, 50, 60]">
                            <button type="button" 
                                    @click="numQuestions = count"
                                    :disabled="(count >= 30 && contentCharacterCount < 1000) || (count >= 50 && contentCharacterCount < 2000)"
                                    :class="{
                                        'bg-[#6646E5] text-white border-[#6646E5] shadow-md shadow-[#6646E5]/20': numQuestions === count,
                                        'bg-white text-[#1A1714] border-[#E2DDD8] hover:border-[#6646E5] hover:text-[#6646E5]': numQuestions !== count,
                                        'opacity-40 cursor-not-allowed grayscale': (count >= 30 && contentCharacterCount < 1000) || (count >= 50 && contentCharacterCount < 2000)
                                    }"
                                    class="py-3 border-2 rounded-[14px] font-bold text-[14px] transition-all">
                                <span x-text="count"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <label class="text-[14px] font-bold text-[#1A1714] uppercase tracking-wider">Question Types</label>
                    <div class="grid grid-cols-2 gap-3">
                        <template x-for="type in [
                            { val: 'mixed', label: 'Mixed Mode', icon: 'heroicon-o-sparkles', desc: 'Variety of formats' },
                            { val: 'multiple_choice', label: 'MCQ Only', icon: 'heroicon-o-list-bullet', desc: 'Standard multiple choice' },
                            { val: 'true_false', label: 'True / False', icon: 'heroicon-o-check-circle', desc: 'Binary choices' },
                            { val: 'fill_in_the_blank', label: 'Fill Blanks', icon: 'heroicon-o-pencil-square', desc: 'Recall based questions' }
                        ]">
                            <button type="button" 
                                    @click="quizType = type.val"
                                    :class="quizType === type.val ? 'border-[#6646E5] bg-[#F4F2FF] ring-2 ring-[#6646E5]/10' : 'border-[#E2DDD8] hover:border-[#6646E5] bg-white'"
                                    class="p-4 border-2 rounded-[18px] text-left transition-all group">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-bold text-[14px] transition-colors" :class="quizType === type.val ? 'text-[#6646E5]' : 'text-[#1A1714]'" x-text="type.label"></span>
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center transition-colors" :class="quizType === type.val ? 'bg-[#6646E5] text-white' : 'bg-[#F9F8F6] text-[#A39D98] group-hover:text-[#6646E5]'">
                                            <x-heroicon-s-check class="w-3.5 h-3.5" x-show="quizType === type.val" />
                                        </div>
                                    </div>
                                    <span class="text-[11px] text-[#7C7167] font-medium" x-text="type.desc"></span>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-[#E2DDD8] flex justify-end gap-3">
                <button type="button" @click="showConfig = false" class="px-6 py-3 text-[#7C7167] font-bold text-[14px] hover:text-[#1A1714] transition-colors">
                    Cancel
                </button>
                <button type="button" @click="submitConfig" class="bg-[#1A1714] hover:bg-[#2E2B28] text-white font-bold text-[15px] px-8 py-3.5 rounded-[16px] shadow-xl hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    Regenerate Quiz &rarr;
                </button>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    function quizConfig() {
        return {
            isProcessing: false,
            showConfig: false,
            progressStage: 'idle',
            numQuestions: 5,
            quizType: 'mixed',
            errorMessage: '',
            contentCharacterCount: @json($attempt && $attempt->quiz && $attempt->quiz->material ? strlen($attempt->quiz->material->raw_content ?? '') : 0),
            
            openConfig() {
                this.errorMessage = '';
                this.showConfig = true;
            },

            async submitConfig() {
                this.showConfig = false;
                this.isProcessing = true;
                this.errorMessage = '';
                this.progressStage = 'uploading';
                
                try {
                    // Simulate progress stages
                    setTimeout(() => {
                        if (this.isProcessing) this.progressStage = 'extracting';
                    }, 1000);

                    setTimeout(() => {
                        if (this.isProcessing) this.progressStage = 'generating';
                    }, 2500);

                    const response = await axios.post('{{ $attempt ? route("materials.generate_quiz", $attempt->quiz->material_id) : "" }}', {
                        num_questions: this.numQuestions,
                        quiz_type: this.quizType
                    }, {
                        timeout: 90000,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    if (response.data.success) {
                        this.progressStage = 'completed';
                        setTimeout(() => {
                            window.location.href = response.data.redirect;
                        }, 500);
                    }
                } catch (error) {
                    this.isProcessing = false;
                    
                    if (error.code === 'ECONNABORTED') {
                        this.errorMessage = 'The request timed out. The AI is taking too long.';
                    } else if (error.response?.status === 500) {
                        this.errorMessage = 'A server-side error occurred. The AI might have had trouble processing the material.';
                    } else {
                        this.errorMessage = error.response?.data?.error || 'An unexpected error occurred. Please try again.';
                    }
                    
                    console.error('Generation error:', error);
                }
            }
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
