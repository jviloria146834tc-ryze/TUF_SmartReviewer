@extends('layouts.app')

@section('title', 'Quizzes')

@section('content')
@php
    $currentTab = request('tab', 'active');
@endphp

<div x-data="quizzesPage()" class="max-w-[1500px] mx-auto w-full pb-12 flex flex-col h-full">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8 animate-glide-up">
        <div>
            <a href="{{ route('dashboard') }}" class="text-[#7C7167] text-[13px] font-semibold hover:text-[#1A1714] flex items-center gap-1 mb-2 transition-colors w-max">
                &larr; Back to Dashboard
            </a>
            <h1 class="text-[33px] font-bold text-[#1A1714] tracking-tight font-['Special_Gothic_Expanded_One',sans-serif]">
                Quizzes
            </h1>
        </div>
        
        <button type="button" @click="showGenerateModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-[12px] font-bold text-[15px] transition-all shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/40 hover:-translate-y-0.5 flex items-center gap-2 shrink-0 cursor-pointer pointer-events-auto">
            <x-heroicon-o-plus-circle class="w-5 h-5" />
            Generate New Quiz
        </button>
    </div>

    <!-- TABS -->
    <div class="flex items-center gap-1 bg-[#E2DDD8] p-1 rounded-[12px] w-max mb-6 shadow-inner animate-glide-up delay-100">
        <a href="/quizzes?tab=active" class="{{ $currentTab == 'active' ? 'bg-[#1A1714] text-white shadow-sm' : 'text-[#7C7167] hover:text-[#1A1714]' }} px-5 py-2 rounded-[8px] font-semibold text-[13px] transition-all block">
            Active
        </a>
        <a href="/quizzes?tab=completed" class="{{ $currentTab == 'completed' ? 'bg-[#1A1714] text-white shadow-sm' : 'text-[#7C7167] hover:text-[#1A1714]' }} px-5 py-2 rounded-[8px] font-semibold text-[13px] transition-all block">
            Completed
        </a>
    </div>

    <div class="flex flex-col gap-4 animate-glide-up delay-200">
        @forelse($quizzes as $quiz)
        @php
            $isCompletedTab = $currentTab == 'completed';
            $attempt = null;
            $isCompleted = false;
            
            if (auth()->check()) {
                $attempt = auth()->user()->quizAttempts()->where('quiz_id', $quiz->id)->latest()->first();
                $isCompleted = $attempt && $attempt->completed_at !== null;
            }
            
            $status = 'Ready';
            $statusBg = 'bg-[#F3F4F6]';
            $statusColor = 'text-[#374151]';
            
            if ($loop->first && $currentTab == 'active' && !$attempt) {
                $status = 'New';
                $statusBg = 'bg-[#DBEAFE]';
                $statusColor = 'text-[#1E40AF]';
            } elseif ($attempt && !$isCompleted) {
                $status = 'In Progress';
                $statusBg = 'bg-[#FEF3C7]';
                $statusColor = 'text-[#92400E]';
            } elseif ($isCompleted) {
                $status = 'Completed';
                $statusBg = 'bg-[#D4F5E3]';
                $statusColor = 'text-[#166534]';
            }
        @endphp
        <div class="bg-white border border-[#E2DDD8] rounded-[16px] p-5 flex flex-col md:flex-row items-start md:items-center gap-5 shadow-sm hover:border-[#6646E5] hover:shadow-md transition-all group cursor-pointer">
            
            <div class="w-[50px] h-[50px] rounded-[12px] bg-[#E0D8FC] flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                @if($isCompleted)
                    <x-heroicon-s-clipboard-document-check class="w-7 h-7 text-[#6646E5]" />
                @else
                    <x-heroicon-o-clipboard-document-list class="w-7 h-7 text-[#6646E5]" />
                @endif
            </div>
            
            <div class="flex-1 w-full">
                <div class="flex items-center gap-3 mb-1">
                    <h3 class="text-[16px] font-bold text-[#1A1714] font-['Syne',sans-serif] group-hover:text-[#6646E5] transition-colors">
                        {{ $quiz->title ?? 'Generated Quiz' }}
                    </h3>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold tracking-wide uppercase {{ $statusBg }} {{ $statusColor }}">
                            {{ $status }}
                        </span>
                        @php
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
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold tracking-wide uppercase bg-[#F4F2FF] text-[#6646E5] border border-[#E0D8FC]">
                            {{ $quizTypeLabel }}
                        </span>
                    </div>
                </div>
                <p class="text-[#7C7167] text-[13px]">
                    {{ $quiz->questions()->count() }} items 
                    @if($quiz->material) 
                        <span class="mx-1.5">•</span> {{ $quiz->material->source_name ?: 'Uploaded Material' }}
                    @endif
                    @if($quiz->created_at)
                        <span class="mx-1.5">•</span> {{ $quiz->created_at->format('M d, Y') }} <span class="mx-1.5">•</span> {{ $quiz->created_at->format('h:i A') }}
                    @endif
                </p>
            </div>
            
            <div class="flex items-center gap-4 w-full md:w-auto mt-3 md:mt-0 pt-4 md:pt-0 border-t md:border-t-0 border-[#E2DDD8]">
                
                <div class="w-full sm:w-[130px] flex flex-col gap-1.5 mr-2">
                    <div class="flex justify-between items-center">
                        <span class="text-[11px] text-[#A39D98] font-semibold uppercase tracking-wider">
                            {{ $isCompleted ? 'Final Score' : 'Progress' }}
                        </span>
                        <span class="text-[12px] text-[#1A1714] font-bold">
                            {{ $attempt ? ($isCompleted ? $attempt->score : count((array)($attempt->answers_json ?? []))) . '/' . $attempt->total_questions : '0%' }}
                        </span>
                    </div>
                    <div class="flex-1 bg-[#F0EDE8] h-1.5 rounded-full overflow-hidden">
                        @php
                            $progressVal = 0;
                            if ($attempt && $attempt->total_questions > 0) {
                                $progressVal = $isCompleted ? $attempt->score : count((array)($attempt->answers_json ?? []));
                                $progressPercent = ($progressVal / $attempt->total_questions) * 100;
                            } else {
                                $progressPercent = 0;
                            }
                        @endphp
                        <div class="bg-[#6646E5] h-full rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                    </div>
                </div>
                
                @if(!$attempt)
                    <a href="{{ route('quizzes.session', $quiz->id) }}" class="flex-1 md:flex-none text-center bg-[#6646E5] hover:bg-[#5538D4] text-white px-4 py-2 rounded-[8px] font-semibold text-[13px] transition-colors shadow-sm w-full sm:w-auto">
                        Start Quiz
                    </a>
                @elseif(!$isCompleted)
                    <a href="{{ route('quizzes.session', $quiz->id) }}" class="flex-1 md:flex-none text-center bg-[#6646E5] hover:bg-[#5538D4] text-white px-4 py-2 rounded-[8px] font-semibold text-[13px] transition-colors shadow-sm w-full sm:w-auto">
                        Continue
                    </a>
                @else
                    <div class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto">
                        <a href="{{ route('quizzes.session', $quiz->id) }}" class="w-full sm:w-auto text-center bg-[#1A1714] hover:bg-[#2E2B28] text-white px-4 py-2 rounded-[8px] font-semibold text-[13px] transition-colors shadow-sm">
                            Retry Quiz
                        </a>
                        <button type="button" @click.stop="regenerateFromQuiz({{ $quiz->id }}, {{ $quiz->material_id }}, {{ strlen($quiz->material->raw_content ?? '') }})" class="w-full sm:w-auto text-center bg-white border border-[#E2DDD8] hover:bg-[#F9F8F6] text-[#1A1714] px-4 py-2 rounded-[8px] font-semibold text-[13px] transition-colors shadow-sm flex items-center justify-center gap-1.5">
                            <x-heroicon-o-arrow-path class="w-4 h-4" />
                            Regenerate
                        </button>
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <a href="{{ route('quizzes.breakdown', $attempt->id) }}" class="flex-1 sm:flex-none text-center bg-white border border-[#E2DDD8] hover:bg-[#F9F8F6] text-[#1A1714] px-4 py-2 rounded-[8px] font-semibold text-[13px] transition-colors shadow-sm">
                                Review Results
                            </a>
                            <form action="{{ route('quizzes.destroy', $quiz->id) }}" method="POST" class="inline" @submit.stop onsubmit="return confirm('Are you sure you want to delete this quiz? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" @click.stop class="w-[36px] h-[36px] flex items-center justify-center text-[#A39D98] hover:text-[#EF4444] hover:bg-[#FEE2E2] rounded-[8px] transition-colors flex-shrink-0 shadow-sm border border-transparent hover:border-[#FCA5A5]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

            </div>
        </div>

        @empty
        <div class="flex-1 flex flex-col items-center justify-center py-20 text-center relative z-10 w-full">
            <div class="w-24 h-24 bg-[#E0D8FC] rounded-[32px] flex items-center justify-center text-[#6646E5] mb-8 shadow-inner transform rotate-3 hover:rotate-0 transition-transform">
                <x-heroicon-o-clipboard-document-list class="w-12 h-12" stroke-width="1.5" />
            </div>
            <h2 class="text-[32px] font-bold text-[#1A1714] mb-4 font-['Inter'] tracking-tight">It's a little quiet here...</h2>
            <p class="text-[#7C7167] text-[18px] max-w-md mb-10 leading-relaxed font-medium">
                @if($currentTab == 'completed')
                    You haven't completed any quizzes yet. Finish an active quiz to see your results here!
                @else
                    You don't have any active quizzes right now. Upload some study material to generate one!
                @endif
            </p>
            <a href="/upload" class="bg-[#1A1714] hover:bg-[#2E2B28] text-white px-10 py-4 rounded-[18px] font-bold text-[16px] transition-all shadow-xl hover:-translate-y-1 flex items-center gap-3 cursor-pointer">
                <x-heroicon-o-cloud-arrow-up class="w-6 h-6 text-[#A78BFA]" />
                Upload Material
            </a>
        </div>
        @endforelse
        
    </div>

    <!-- Progress Overlay for Quiz Generation -->
    <div x-show="isGenerating" x-cloak
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
                <div class="flex items-center gap-5 transition-all duration-500" :class="progressPercent < 95 ? 'opacity-100' : 'opacity-50'">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500"
                         :class="progressPercent < 95 ? 'bg-[#6646E5] text-white shadow-lg shadow-[#6646E5]/20' : 'bg-[#D4F5E3] text-[#166534]'">
                        <template x-if="progressPercent < 95">
                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                        <template x-if="progressPercent >= 95">
                            <x-heroicon-s-check class="w-6 h-6" />
                        </template>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-[16px] transition-colors" :class="progressPercent < 95 ? 'text-[#1A1714]' : 'text-[#7C7167]'">Phase: Crafting</span>
                        <span class="text-[13px] font-medium text-[#7C7167]">AI is designing your questions...</span>
                    </div>
                </div>

                <div class="flex items-center gap-5 transition-all duration-500" :class="progressPercent >= 95 ? 'opacity-100' : 'opacity-40'">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500"
                         :class="progressPercent === 100 ? 'bg-[#D4F5E3] text-[#166534]' : (progressPercent >= 95 ? 'bg-[#6646E5] text-white shadow-lg shadow-[#6646E5]/20' : 'bg-gray-100 text-gray-400')">
                        <template x-if="progressPercent >= 95 && progressPercent < 100">
                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                        <template x-if="progressPercent < 95 || progressPercent === 100">
                            <template x-if="progressPercent === 100">
                                <x-heroicon-s-check class="w-6 h-6" />
                            </template>
                            <template x-if="progressPercent < 95">
                                <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                            </template>
                        </template>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-[16px] transition-colors" :class="progressPercent >= 95 ? 'text-[#1A1714]' : 'text-[#7C7167]'">Phase: Ready</span>
                        <span class="text-[13px] font-medium text-[#7C7167]">Optimizing your study session...</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-10 flex flex-col gap-2">
                <div class="flex justify-between items-end">
                    <span class="text-[12px] font-black uppercase tracking-widest text-[#6646E5]" x-text="progressPercent < 95 ? 'Crafting' : 'Finalizing'"></span>
                    <span class="text-[20px] font-black text-[#1A1714]" x-text="progressPercent + '%'"></span>
                </div>
                <div class="w-full bg-[#F0EDE8] h-3 rounded-full overflow-hidden p-0.5">
                    <div class="h-full bg-[#6646E5] rounded-full transition-all duration-500 ease-out shadow-[0_0_10px_rgba(102,70,229,0.4)]"
                         :style="'width: ' + progressPercent + '%'"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Generate Modal -->
    <div x-show="showGenerateModal" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center overflow-y-auto bg-[#1A1714]/60 backdrop-blur-sm" style="display: none;">
        <div @click.away="showGenerateModal = false; setTimeout(() => step = 1, 300)" class="relative w-full max-w-2xl p-6 mx-4 bg-white border border-[#E2DDD8] rounded-[28px] shadow-2xl">
            
            <!-- Step 1: Select Material -->
            <div x-show="step === 1">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-[22px] font-bold text-[#1A1714] font-['Inter']">Select Material</h3>
                        <p class="text-[#7C7167] text-[14px] font-medium">Choose a document to begin your quiz generation</p>
                    </div>
                    <button @click="showGenerateModal = false; setTimeout(() => step = 1, 300)" class="w-10 h-10 rounded-full hover:bg-gray-100 flex items-center justify-center text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="flex flex-col gap-3 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar w-full">
                    @forelse($materials as $m)
                    @php
                        $activeQuiz = $m->quizzes->whereNotIn('id', $completedQuizIds)->first();
                    @endphp
                    
                    @if($activeQuiz)
                    <div class="flex items-center gap-4 p-4 border border-[#E2DDD8] rounded-[20px] bg-[#FAF9F7] opacity-60 grayscale relative overflow-hidden w-full shrink-0">
                        <div class="w-12 h-12 rounded-[14px] bg-[#E2DDD8] text-[#7C7167] flex items-center justify-center flex-shrink-0">
                            <x-heroicon-o-book-open class="w-6 h-6" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-[#1A1714] font-bold text-[15px] truncate">{{ $m->title }}</h4>
                            <p class="text-[#7C7167] text-[12px] font-medium">Active quiz exists · <a href="{{ route('quizzes.session', $activeQuiz->id) }}" class="text-[#6646E5] font-bold hover:underline">Finish it first &rarr;</a></p>
                        </div>
                        <div class="w-5 h-5 flex-shrink-0"></div>
                    </div>
                    @else
                    <button type="button" @click="selectMaterial({{ $m->id }}, {{ strlen($m->raw_content ?? '') }})" class="flex items-center gap-4 p-4 border border-[#E2DDD8] hover:border-[#6646E5] rounded-[20px] transition-all hover:bg-[#F9F8F6] group w-full text-left shrink-0">
                        <div class="w-12 h-12 rounded-[14px] bg-[#E0D8FC] text-[#6646E5] group-hover:bg-[#6646E5] group-hover:text-white transition-colors flex items-center justify-center flex-shrink-0">
                            <x-heroicon-o-book-open class="w-6 h-6" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-[#1A1714] font-bold text-[15px] truncate group-hover:text-[#6646E5] transition-colors">{{ $m->title }}</h4>
                            <p class="text-[#7C7167] text-[12px] font-medium">
                                {{ $m->source_name }} <span class="mx-1.5">•</span> {{ $m->created_at->format('M d, Y') }}
                            </p>
                        </div>
                        <x-heroicon-s-chevron-right class="w-5 h-5 text-[#E2DDD8] group-hover:text-[#6646E5] transition-colors" />
                    </button>
                    @endif
                    @empty
                    <div class="py-10 text-center text-[#7C7167]">
                        <p class="font-medium">No processed materials found.</p>
                        <a href="{{ route('materials.upload') }}" class="text-[#6646E5] font-bold mt-2 inline-block">Upload one now &rarr;</a>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Step 2: Configure Quiz -->
            <div x-show="step === 2" style="display: none;">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <button type="button" @click="step = 1" class="text-[#7C7167] text-[13px] font-semibold hover:text-[#1A1714] flex items-center gap-1 mb-1 transition-colors">
                            &larr; Back to Selection
                        </button>
                        <h3 class="text-[22px] font-bold text-[#1A1714] font-['Inter']">Configure Your Quiz</h3>
                    </div>
                    <button @click="showGenerateModal = false; setTimeout(() => step = 1, 300)" class="w-10 h-10 rounded-full hover:bg-gray-100 flex items-center justify-center text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <div class="space-y-8">
                    <div class="flex flex-col gap-4">
                        <label class="text-[14px] font-bold text-[#1A1714] uppercase tracking-wider">How many items?</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <template x-for="count in [5, 10, 15, 20, 30, 40, 50, 60]">
                                <button type="button" 
                                        @click="numQuestions = count"
                                        :disabled="(count >= 30 && selectedMaterial?.charCount < 1000) || (count >= 50 && selectedMaterial?.charCount < 2000)"
                                        :class="{
                                            'bg-[#6646E5] text-white border-[#6646E5] shadow-md shadow-[#6646E5]/20': numQuestions === count,
                                            'bg-white text-[#1A1714] border-[#E2DDD8] hover:border-[#6646E5] hover:text-[#6646E5]': numQuestions !== count,
                                            'opacity-40 cursor-not-allowed grayscale': (count >= 30 && selectedMaterial?.charCount < 1000) || (count >= 50 && selectedMaterial?.charCount < 2000)
                                        }"
                                        class="py-3 border-2 rounded-[14px] font-bold text-[14px] transition-all">
                                    <span x-text="count"></span>
                                </button>
                            </template>
                        </div>
                        <p x-show="selectedMaterial?.charCount < 1000" class="text-[11px] text-orange-600 font-semibold flex items-center gap-1">
                            <x-heroicon-s-exclamation-circle class="w-3.5 h-3.5" />
                            Higher item counts require longer material
                        </p>
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
                    <button type="button" @click="step = 1" class="px-6 py-3 text-[#7C7167] font-bold text-[14px] hover:text-[#1A1714] transition-colors">Back</button>
                    <button type="button" @click="generateQuiz()" :disabled="isGenerating" class="bg-[#1A1714] hover:bg-[#2E2B28] text-white font-bold px-10 py-3.5 rounded-[16px] shadow-xl hover:-translate-y-0.5 transition-all flex items-center gap-2">
                        <span>Generate Quiz &rarr;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    function quizzesPage() {
        return {
            showGenerateModal: false,
            step: 1,
            selectedMaterial: null,
            numQuestions: 10,
            quizType: 'mixed',
            isGenerating: false,
            progressPercent: 0,
            progressInterval: null,
            
            selectMaterial(materialId, charCount) {
                this.selectedMaterial = { id: materialId, charCount: charCount };
                this.step = 2;
                this.numQuestions = 10;
                this.quizType = 'mixed';
            },

            regenerateFromQuiz(quizId, materialId, charCount) {
                this.selectedMaterial = { id: materialId, charCount: charCount };
                this.step = 2;
                this.showGenerateModal = true;
                this.numQuestions = 10;
                this.quizType = 'mixed';
            },

            async generateQuiz() {
                if (!this.selectedMaterial) return;
                this.isGenerating = true;
                this.progressPercent = 0;
                
                this.progressInterval = setInterval(() => {
                    if (this.progressPercent < 95) {
                        this.progressPercent += Math.floor(Math.random() * 5) + 2;
                    }
                }, 600);
                
                try {
                    const response = await axios.post(`/materials/${this.selectedMaterial.id}/generate-quiz`, {
                        num_questions: this.numQuestions,
                        quiz_type: this.quizType
                    }, {
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    if (response.data.success && response.data.quiz_id) {
                        this.progressPercent = 100;
                        setTimeout(() => {
                            window.location.href = `/quiz/${response.data.quiz_id}`;
                        }, 500);
                    } else {
                        clearInterval(this.progressInterval);
                        alert('Something went wrong. Please try again.');
                        this.isGenerating = false;
                    }
                } catch (error) {
                    clearInterval(this.progressInterval);
                    alert(error.response?.data?.error || 'Failed to generate quiz. Please try again.');
                    this.isGenerating = false;
                }
            }
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2DDD8; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6646E5; }
</style>
@endsection