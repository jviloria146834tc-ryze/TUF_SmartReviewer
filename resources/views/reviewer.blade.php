@extends('layouts.app')

@section('title', isset($material) ? $material->title : 'Reviewer')

@section('content')
<div x-data="reviewerPage()" x-init="initComponent()" class="max-w-[1500px] mx-auto w-full pb-16 h-full flex flex-col relative">

    <!-- Progress Overlay for Quiz Generation -->
    <div x-show="isGenerating" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-[#F0EDE8]/90 backdrop-blur-sm z-[100] flex flex-col items-center justify-center">
        
        <div class="w-full max-w-md bg-white p-10 rounded-[32px] shadow-2xl border border-[#E2DDD8] relative overflow-hidden">
            <h2 class="text-[26px] font-bold text-[#1A1714] mb-8 font-['Inter'] text-center tracking-tight">Generating Quiz</h2>
            
            <div class="space-y-8">
                <!-- Step 1: Crafting -->
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

                <!-- Step 2: Finalizing -->
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

    <!-- Header (High Z-Index to ensure clickability) -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4 relative z-[50] animate-glide-up">
        <div>
            @if($material)
                <a href="{{ route('reviewer', ['reset' => 1]) }}" class="text-[#7C7167] text-[13px] font-semibold hover:text-[#1A1714] flex items-center gap-1 mb-2 transition-colors w-max">
                    &larr; Back
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="text-[#7C7167] text-[13px] font-semibold hover:text-[#1A1714] flex items-center gap-1 mb-2 transition-colors w-max">
                    &larr; Back to Dashboard
                </a>
            @endif
            <h1 class="text-[33px] font-bold text-[#1A1714] tracking-tight font-['Special_Gothic_Expanded_One',sans-serif]">
                AI Reviewer
            </h1>
        </div>
        
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 relative z-[51] w-full md:w-auto mt-2 md:mt-0">
            @if(!$material)
                <a href="{{ route('materials.upload') }}" class="w-full sm:w-auto justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-[12px] font-bold text-[15px] transition-all shadow-lg shadow-indigo-600/20 flex items-center gap-2">
                    <x-heroicon-o-cloud-arrow-up class="w-5 h-5" />
                    Upload Material
                </a>
            @else
                <button type="button" @click.stop="openSwitcher()" class="w-full sm:w-auto justify-center bg-white border border-[#E2DDD8] hover:bg-[#F9F8F6] text-[#1A1714] px-6 py-3 rounded-[12px] font-bold text-[15px] transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 flex items-center gap-2 cursor-pointer pointer-events-auto">
                    <x-heroicon-o-swatch class="w-5 h-5 text-[#6646E5]" />
                    Change Material
                </button>

                <button type="button" @click.stop="openConfig()" class="w-full sm:w-auto justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-[12px] font-bold text-[15px] transition-all shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/40 hover:-translate-y-0.5 flex items-center gap-2 cursor-pointer pointer-events-auto">
                    <x-heroicon-o-plus-circle class="w-5 h-5" />
                    Generate Quiz
                </button>
            @endif
        </div>
    </div>

    @if($material)
    <!-- MAIN CONTENT AREA -->
    <div class="w-full flex flex-col gap-10 relative z-10">
        
        <!-- TOP SECTION: Material Banner & Flashcards -->
        <div class="w-full flex flex-col xl:flex-row gap-6 items-stretch animate-glide-up delay-100">
            <!-- Material Title Banner -->
            @php
                $ext = strtolower(pathinfo($material->file_path ?? $material->original_path ?? '', PATHINFO_EXTENSION));
                $type = $ext == 'pdf' ? 'pdf' : (in_array($ext, ['jpg', 'jpeg', 'png']) ? 'image' : 'other');
                $url = $material->public_url ?? '#';
            @endphp
            <div @click="$dispatch('open-preview', { url: '{{ $url }}', type: '{{ $type }}' })" class="flex-1 bg-[#1A1714] border border-[#2E2B28] hover:border-[#6646E5] rounded-[24px] p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 shadow-xl relative overflow-hidden cursor-pointer transition-all group">
                <div class="absolute -top-20 -right-20 w-[300px] h-[300px] bg-[#6646E5] rounded-full mix-blend-multiply filter blur-[100px] opacity-40 transition-opacity group-hover:opacity-60"></div>
                <div class="flex flex-col gap-3 relative z-10">
                    <span class="bg-[#2E2B28] border border-[#3A3734] text-[#E0D8FC] px-4 py-1.5 rounded-full text-[12px] font-bold w-max tracking-wider uppercase">
                        AI Generated Study Guide
                    </span>
                    <h2 class="text-white text-[28px] md:text-[32px] font-bold font-['Inter',sans-serif] leading-tight tracking-tight transition-colors group-hover:text-[#A78BFA]">
                        {{ $material->title }}
                    </h2>
                    <p class="text-[#9E9690] text-[14px] font-medium">
                        {{ $material->source_name }} <span class="mx-1.5">•</span> Processed on {{ $material->created_at->format('M d, Y') }} <span class="mx-1.5">•</span> {{ $material->created_at->format('h:i A') }}
                    </p>
                </div>
                <div class="bg-white/10 border border-white/10 w-20 h-20 rounded-[20px] flex items-center justify-center flex-shrink-0 relative z-10 backdrop-blur-md transition-transform group-hover:scale-110">
                    <x-heroicon-o-book-open class="w-10 h-10 text-white" />
                </div>
            </div>

            <!-- Flashcards Button -->
            <a href="{{ route('flashcards.index', $material->id) }}" class="xl:w-[440px] bg-[#1A1714] border border-[#2E2B28] hover:border-[#6646E5] rounded-[24px] px-8 py-6 flex items-center gap-6 shadow-xl transition-all group relative overflow-hidden">
                <div class="absolute -bottom-12 -right-12 w-40 h-40 bg-[#6646E5] rounded-full mix-blend-multiply filter blur-[80px] opacity-20 group-hover:opacity-40 transition-opacity"></div>
                <div class="w-20 h-20 bg-[#6646E5] rounded-[22px] flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform relative z-10 shrink-0">
                    <x-heroicon-o-rectangle-stack class="w-10 h-10" />
                </div>
                <div class="relative z-10 text-left flex-1">
                    <div class="flex items-center justify-between gap-2 mb-1">
                        <h3 class="text-white font-bold text-[24px] md:text-[28px] leading-tight group-hover:text-[#A78BFA] transition-colors">
                            Study Flashcards
                        </h3>
                        @if($allFlashcardsMastered)
                            <span class="bg-[#D4F5E3] text-[#166534] text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full border border-[#166534]/10 shadow-sm">Mastered</span>
                        @endif
                    </div>
                    <p class="text-[#9E9690] text-[15px] font-medium">
                        Master concepts through active recall
                    </p>
                </div>
            </a>
        </div>

        <!-- AI Summary Section -->
        <div class="bg-white border border-[#E2DDD8] rounded-[24px] p-6 md:p-10 shadow-sm relative animate-glide-up delay-200 hover:border-[#6646E5]/40 transition-all duration-300">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-[12px] bg-[#F4F2FF] flex items-center justify-center">
                    <x-heroicon-s-sparkles class="w-6 h-6 text-[#6646E5]" />
                </div>
                <h3 class="text-[24px] font-bold text-[#1A1714] font-['Inter']">Comprehensive Summary</h3>
            </div>
            <div class="text-[#2E2B28] text-[16px] md:text-[18px] leading-[1.8] font-medium [&_h3]:text-[22px] [&_h3]:font-bold [&_h3]:text-[#1A1714] [&_h3]:mt-8 [&_h3]:mb-4 [&_h3:first-child]:mt-0 [&_p]:mb-5 [&_ul]:list-disc [&_ul]:ml-6 [&_ul]:mb-5 [&_li]:mb-2 [&_li]:pl-2 [&_strong]:text-[#1A1714] [&_strong]:font-bold">
                {!! \Illuminate\Support\Str::markdown($material->summary ?? 'No summary available.', ['html_input' => 'escape']) !!}
            </div>
        </div>

        <!-- Key Concepts Section -->
        <div class="{{ !$latestUnattemptedQuiz ? 'mb-20' : '' }} animate-glide-up delay-300">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-[12px] bg-[#2E2B28] border border-[#3A3734] flex items-center justify-center">
                    <x-heroicon-s-academic-cap class="w-6 h-6 text-[#E0D8FC]" />
                </div>
                <h3 class="text-[24px] font-bold text-[#1A1714] font-['Inter']">Key Concepts to Master</h3>
            </div>
            @if($material->concepts)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($material->concepts as $concept)
                    <div class="bg-[#1A1714] border border-[#2E2B28] rounded-[24px] p-6 md:p-8 flex flex-col gap-4 shadow-sm hover:border-[#6646E5]/60 hover:shadow-[0_0_20px_rgba(102,70,229,0.15)] transition-all duration-300 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-[#E0D8FC] mix-blend-multiply opacity-[0.03] rounded-bl-full group-hover:opacity-10 transition-opacity"></div>
                        <div class="flex items-start gap-4 relative z-10">
                            <div class="w-12 h-12 rounded-[14px] bg-[#2E2B28] border border-[#3A3734] flex items-center justify-center text-[#E0D8FC] font-bold text-[18px] shadow-inner flex-shrink-0 group-hover:bg-[#6646E5] group-hover:border-[#6646E5] group-hover:text-white transition-colors">
                                {{ $loop->iteration }}
                            </div>
                            <div class="flex flex-col gap-2 pt-1">
                                <h4 class="text-white font-bold text-[18px] md:text-[20px] font-['Inter'] tracking-tight">
                                    {{ is_array($concept) ? $concept['title'] : $concept }}
                                </h4>
                                <p class="text-[#9E9690] text-[15px] md:text-[16px] leading-[1.7] font-medium">
                                    {{ is_array($concept) ? $concept['short_explanation'] : 'No explanation available.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        @if($latestUnattemptedQuiz)
        <!-- Take Quiz CTA -->
        <div id="take-quiz-section" class="bg-gradient-to-r from-[#E0D8FC] to-[#F4F2FF] rounded-[24px] p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 shadow-sm border border-[#D1C4F9] relative overflow-hidden mb-12 animate-glide-up delay-400">
            <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-gradient-to-l from-white/40 to-transparent pointer-events-none"></div>
            <div class="max-w-2xl relative z-10">
                <h3 class="text-[#1A1714] text-[22px] md:text-[24px] font-bold mb-2 font-['Inter']">Ready to test your knowledge?</h3>
                <p class="text-[#5538D4] text-[15px] md:text-[16px] font-medium leading-relaxed">Your adaptive quiz is ready. Start your assessment now to track your mastery.</p>
            </div>
            <a href="{{ route('quizzes.session', $latestUnattemptedQuiz->id) }}" class="w-full md:w-auto bg-[#6646E5] hover:bg-[#5538D4] text-white px-10 py-4 rounded-[16px] font-bold text-[16px] transition-all shadow-lg shadow-[#6646E5]/30 hover:shadow-[#6646E5]/50 hover:-translate-y-1 text-center flex items-center justify-center gap-2 relative z-10">
                Take Quiz
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
        @endif
    </div>
    @else
    <!-- EMPTY STATE -->
    <div class="flex-1 flex flex-col items-center justify-center py-20 text-center relative z-10 animate-glide-up delay-100">
        <div class="w-24 h-24 bg-[#E0D8FC] rounded-[32px] flex items-center justify-center text-[#6646E5] mb-8 shadow-inner transform rotate-3">
            <x-heroicon-o-book-open class="w-12 h-12" stroke-width="1.5" />
        </div>
        <h2 class="text-[32px] font-bold text-[#1A1714] mb-4 font-['Inter'] tracking-tight">Your reviewer is empty</h2>
        <p class="text-[#7C7167] text-[18px] max-w-md mb-10 leading-relaxed font-medium">
            Ready to master some material? Select one of your processed documents to start reviewing and testing your knowledge.
        </p>
        <button type="button" @click.stop="openSwitcher()" class="bg-[#1A1714] hover:bg-[#2E2B28] text-white px-10 py-4 rounded-[18px] font-bold text-[16px] transition-all shadow-xl hover:-translate-y-1 flex items-center gap-3 cursor-pointer">
            <x-heroicon-o-swatch class="w-6 h-6 text-[#A78BFA]" />
            Select Processed Material
        </button>
    </div>
    @endif

    <!-- Modals Section -->
    <div class="modals-container">
        <!-- Quiz Config Modal -->
        <div x-show="showConfig" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center overflow-y-auto bg-[#1A1714]/60 backdrop-blur-sm" style="display: none;">
            <div @click.away="showConfig = false" class="relative w-full max-w-lg p-6 mx-4 bg-white border border-[#E2DDD8] rounded-[24px] shadow-2xl">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-[22px] font-bold text-[#1A1714] font-['Inter']">Configure Your Quiz</h3>
                    <button @click="showConfig = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <div class="space-y-8">
                    <div class="flex flex-col gap-4">
                        <label class="text-[14px] font-bold text-[#1A1714] uppercase tracking-wider">How many items?</label>
                        <div class="grid grid-cols-4 md:grid-cols-4 gap-2 sm:gap-3">
                            <template x-for="count in [5, 10, 15, 20, 30, 40, 50, 60]">
                                <button type="button" 
                                        @click="numQuestions = count"
                                        :disabled="(count >= 30 && contentCharacterCount < 1000) || (count >= 50 && contentCharacterCount < 2000)"
                                        :class="{
                                            'bg-[#6646E5] text-white border-[#6646E5] shadow-md shadow-[#6646E5]/20': numQuestions === count,
                                            'bg-white text-[#1A1714] border-[#E2DDD8] hover:border-[#6646E5] hover:text-[#6646E5]': numQuestions !== count,
                                            'opacity-40 cursor-not-allowed grayscale': (count >= 30 && contentCharacterCount < 1000) || (count >= 50 && contentCharacterCount < 2000)
                                        }"
                                        class="py-2 sm:py-3 border-2 rounded-[14px] font-bold text-[14px] transition-all">
                                    <span x-text="count"></span>
                                </button>
                            </template>
                        </div>
                        <p x-show="contentCharacterCount < 1000" class="text-[11px] text-orange-600 font-semibold flex items-center gap-1">
                            <x-heroicon-s-exclamation-circle class="w-3.5 h-3.5" />
                            Higher item counts require longer material
                        </p>
                    </div>

                    <div class="flex flex-col gap-4">
                        <label class="text-[14px] font-bold text-[#1A1714] uppercase tracking-wider">Question Types</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
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
                    <button type="button" @click="showConfig = false" class="px-6 py-3 text-[#7C7167] font-bold text-[14px] hover:text-[#1A1714] transition-colors">Cancel</button>
                    <button type="button" @click="generateQuiz()" class="bg-[#1A1714] hover:bg-[#2E2B28] text-white font-bold px-10 py-3.5 rounded-[16px] shadow-xl hover:-translate-y-0.5 transition-all">Generate &rarr;</button>
                </div>
            </div>
        </div>

        <!-- Material Switcher Modal -->
        <div x-show="showSwitcher" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center overflow-y-auto bg-[#1A1714]/60 backdrop-blur-sm" style="display: none;">
            <div @click.away="showSwitcher = false" class="relative w-full max-w-2xl p-6 mx-4 bg-white border border-[#E2DDD8] rounded-[28px] shadow-2xl">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-[22px] font-bold text-[#1A1714] font-['Inter']">Select Material</h3>
                        <p class="text-[#7C7167] text-[14px] font-medium">
                            @if($material)
                                Switch to a different study guide
                            @else
                                Choose a document to begin your review session
                            @endif
                        </p>
                    </div>
                    <button @click="showSwitcher = false" class="w-10 h-10 rounded-full hover:bg-gray-100 flex items-center justify-center text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="flex flex-col gap-3 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar w-full">
                    @forelse($allMaterials as $m)
                    <a href="{{ route('reviewer', $m->id) }}" class="flex items-center gap-4 p-4 border-2 rounded-[20px] transition-all hover:bg-[#F9F8F6] w-full shrink-0 {{ ($material && $material->id == $m->id) ? 'border-[#6646E5] bg-[#F4F2FF]' : 'border-[#E2DDD8] hover:border-[#6646E5]' }}">
                        <div class="w-12 h-12 rounded-[14px] {{ ($material && $material->id == $m->id) ? 'bg-[#6646E5] text-white' : 'bg-[#E0D8FC] text-[#6646E5]' }} flex items-center justify-center flex-shrink-0">
                            <x-heroicon-o-book-open class="w-6 h-6" />
                        </div>
                        <div class="flex-1 min-w-0 pr-2">
                            <div class="flex items-center gap-2">
                                <h4 class="text-[#1A1714] font-bold text-[15px] truncate">{{ $m->title }}</h4>
                                @if($m->flashcards_count > 0 && $m->flashcards_count === $m->mastered_flashcards_count)
                                    <span class="bg-[#D4F5E3] text-[#166534] text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full border border-[#166534]/10 shadow-sm flex-shrink-0">Mastered</span>
                                @endif
                            </div>
                            <p class="text-[#7C7167] text-[12px] font-medium truncate">
                                {{ $m->source_name }} <span class="mx-1.5">•</span> {{ $m->created_at->format('M d, Y') }} <span class="mx-1.5">•</span> {{ $m->created_at->format('h:i A') }}
                            </p>
                        </div>
                        @if($material && $material->id == $m->id)
                        <div class="w-12 h-12 bg-[#6646E5] rounded-[14px] flex items-center justify-center text-white flex-shrink-0 shadow-md">
                            <x-heroicon-s-check class="w-6 h-6" />
                        </div>
                        @endif
                    </a>
                    @empty
                    <div class="py-10 text-center text-[#7C7167]">
                        <p class="font-medium">No processed materials found.</p>
                        <a href="{{ route('materials.upload') }}" class="text-[#6646E5] font-bold mt-2 inline-block">Upload one now &rarr;</a>
                    </div>
                    @endforelse
                </div>
                <div class="mt-8 pt-6 border-t border-[#E2DDD8] flex justify-end">
                    <button type="button" @click="showSwitcher = false" class="bg-[#1A1714] hover:bg-[#2E2B28] text-white font-semibold px-8 py-3 rounded-[12px]">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        function reviewerPage() {
            return {
                showConfig: false,
                showSwitcher: false,
                isGenerating: false,
                progressPercent: 0,
                progressInterval: null,
                numQuestions: 5,
                quizType: 'mixed',
                materialId: @json($material->id ?? null),
                hasUnattemptedQuiz: @json($latestUnattemptedQuiz !== null),
                contentCharacterCount: @json($material ? strlen($material->raw_content ?? '') : 0),
                
                initComponent() {
                    console.log('Reviewer Component Ready');
                },

                openSwitcher() {
                    console.log('Opening Switcher');
                    this.showSwitcher = true;
                },

                openConfig() {
                    if (this.hasUnattemptedQuiz) {
                        const el = document.getElementById('take-quiz-section');
                        if (el) {
                            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            return;
                        }
                    }
                    console.log('Opening Config');
                    this.showConfig = true;
                },

                async generateQuiz() {
                    if (!this.materialId) return;
                    
                    this.showConfig = false;
                    this.isGenerating = true;
                    this.progressPercent = 0;

                    this.progressInterval = setInterval(() => {
                        if (this.progressPercent < 95) {
                            this.progressPercent += Math.floor(Math.random() * 5) + 2;
                        }
                    }, 600);

                    try {
                        const response = await axios.post(`/materials/${this.materialId}/generate-quiz`, {
                            num_questions: this.numQuestions,
                            quiz_type: this.quizType
                        }, {
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        });

                        if (response.data.success) {
                            this.progressPercent = 100;
                            setTimeout(() => {
                                window.location.href = response.data.redirect;
                            }, 500);
                        }
                    } catch (error) {
                        clearInterval(this.progressInterval);
                        this.isGenerating = false;
                        alert('Failed to generate quiz.');
                    }
                }
            }
        }
    </script>
</div>

<style>
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2DDD8; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6646E5; }
</style>
@endsection
