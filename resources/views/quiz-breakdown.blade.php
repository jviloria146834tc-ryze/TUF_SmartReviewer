@extends('layouts.app')

@section('content')
<div class="max-w-[1500px] mx-auto w-full pb-12 flex flex-col h-full">

    <div class="mb-8">
        <a href="{{ route('quizzes.results') }}" class="text-[#7C7167] text-[13px] font-semibold hover:text-[#1A1714] flex items-center gap-1 mb-2 transition-colors w-max">
            &larr; Back to History
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
            <div class="bg-white border border-[#E2DDD8] rounded-[16px] p-5 md:p-6 shadow-sm flex gap-4 md:gap-5 items-start">
                
                @if($isCorrect)
                    <div class="w-8 h-8 rounded-[8px] bg-[#D4F5E3] text-[#166534] flex items-center justify-center flex-shrink-0 shadow-sm mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                @else
                    <div class="w-8 h-8 rounded-[8px] bg-[#FCE7F3] text-[#9D174D] flex items-center justify-center flex-shrink-0 shadow-sm mt-0.5">
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
                        <div class="mt-2 p-3 bg-[#F9F8F6] rounded-lg border border-[#E2DDD8] text-[13px] text-[#7C7167]">
                            <span class="font-bold text-[#1A1714]">Explanation:</span> {{ $q->explanation }}
                        </div>
                        @endif
                    </div>
                </div>

            </div>
            @endforeach

        </div>

        <div class="w-full lg:w-[380px] flex flex-col gap-4 lg:sticky lg:top-8 flex-shrink-0">
            
            <div class="bg-white border border-[#E2DDD8] rounded-[16px] p-6 md:p-8 shadow-sm flex flex-col items-center text-center">
                <h2 class="text-[15px] font-semibold text-[#1A1714] uppercase tracking-wide mb-6">Final Score</h2>
                
                @php
                    $percentage = ($attempt->total_questions > 0) ? ($attempt->score / $attempt->total_questions) * 100 : 0;
                @endphp

                <h3 class="text-[24px] font-bold text-[#1A1714] font-['Syne',sans-serif] mb-1">
                    {{ $percentage >= 75 ? 'Great Work!' : 'Keep Practicing!' }}
                </h3>
                <p class="text-[#7C7167] text-[14px] mb-8">{{ $attempt->score }} correct out of {{ $attempt->total_questions }} questions</p>

                <div class="relative w-[130px] h-[130px] flex items-center justify-center mb-8">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                        <path class="text-[#F0EDE8]" stroke-dasharray="100, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3"></path>
                        <path class="text-[#6646E5]" stroke-dasharray="{{ $percentage }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"></path>
                    </svg>
                    <div class="absolute flex flex-col items-center justify-center">
                        <span class="text-[38px] font-bold text-[#1A1714] font-['TASA_Orbiter',sans-serif] leading-none">{{ round($percentage) }}%</span>
                    </div>
                </div>

                <div class="w-full border-t border-b border-[#E2DDD8] py-4 flex justify-between items-center mb-6">
                    <div class="flex flex-col items-start gap-1">
                        <span class="text-[#7C7167] text-[13px] font-medium">Time taken</span>
                        <span class="text-[#1A1714] font-bold text-[15px]">
                            @if($attempt->time_taken)
                                {{ floor($attempt->time_taken / 60) }}m {{ $attempt->time_taken % 60 }}s
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <span class="text-[#7C7167] text-[13px] font-medium">Date</span>
                        <span class="text-[#1A1714] font-bold text-[15px]">{{ $attempt->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

            </div>

            <a href="{{ route('quizzes.session', $attempt->quiz_id) }}" class="w-full bg-[#6646E5] hover:bg-[#5538D4] text-white px-6 py-4 rounded-[12px] font-semibold text-[15px] transition-colors shadow-sm text-center">
                Retry Quiz
            </a>
            <a href="{{ route('reviewer') }}" class="w-full bg-white border border-[#E2DDD8] hover:bg-[#F9F8F6] text-[#1A1714] font-semibold text-[15px] rounded-[12px] py-4 transition-colors shadow-sm text-center block">
                Review Material
            </a>

        </div>

    </div>
    @else
    <div class="bg-white border border-[#E2DDD8] rounded-[16px] p-12 text-center shadow-sm">
        <p class="text-[#7C7167]">No quiz attempt data found.</p>
        <a href="{{ route('quizzes.index') }}" class="mt-4 inline-block text-[#6646E5] font-semibold">Take a quiz</a>
    </div>
    @endif

</div>
@endsection
