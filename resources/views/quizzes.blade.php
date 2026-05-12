@extends('layouts.app')

@section('content')
@php
    $currentTab = request('tab', 'active');
@endphp

<div class="max-w-[1500px] mx-auto w-full pb-12">

    <div class="mb-8">
        <a href="{{ route('dashboard') }}" class="text-[#7C7167] text-[13px] font-semibold hover:text-[#1A1714] flex items-center gap-1 mb-2 transition-colors w-max">
            &larr; Dashboard
        </a>
        <h1 class="text-[33px] font-bold text-[#1A1714] tracking-tight font-['Special_Gothic_Expanded_One',sans-serif]">
            Quizzes
        </h1>
    </div>

    <div class="flex items-center gap-1 bg-[#E2DDD8] p-1 rounded-[12px] w-max mb-8 shadow-inner">
        <a href="/quizzes?tab=active" class="{{ $currentTab == 'active' ? 'bg-white text-[#1A1714] shadow-sm' : 'text-[#7C7167] hover:text-[#1A1714]' }} px-5 py-2 rounded-[8px] font-semibold text-[13px] transition-all block">
            Active
        </a>
        <a href="/quizzes?tab=completed" class="{{ $currentTab == 'completed' ? 'bg-white text-[#1A1714] shadow-sm' : 'text-[#7C7167] hover:text-[#1A1714]' }} px-5 py-2 rounded-[8px] font-semibold text-[13px] transition-all block">
            Completed
        </a>
    </div>

    <div class="flex flex-col gap-5">
        @forelse($quizzes as $quiz)
        @php
            $isCompleted = $currentTab == 'completed';
            $attempt = null;
            if (auth()->check()) {
                $attempt = auth()->user()->quizAttempts()->where('quiz_id', $quiz->id)->latest()->first();
            }
            
            $status = 'New';
            $statusBg = 'bg-[#DBEAFE]';
            $statusColor = 'text-[#1E40AF]';
            
            if ($attempt) {
                $status = 'Completed';
                $statusBg = 'bg-[#D4F5E3]';
                $statusColor = 'text-[#166534]';
            }
            
            $icon = '📝';
            $iconBg = 'bg-[#E0D8FC]';
        @endphp
        <div class="bg-white border border-[#E2DDD8] rounded-[16px] p-5 md:p-6 flex flex-col md:flex-row items-start md:items-center gap-6 shadow-sm hover:border-[#6646E5] hover:shadow-md transition-all cursor-pointer group">
            
            <div class="w-[56px] h-[56px] rounded-[16px] {{ $iconBg }} flex items-center justify-center text-[24px] flex-shrink-0 group-hover:scale-105 transition-transform">
                {{ $icon }}
            </div>
            
            <div class="flex-1 w-full">
                <div class="flex items-center flex-wrap gap-3 mb-1.5">
                    <h3 class="text-[16px] font-bold text-[#1A1714] font-['Syne',sans-serif]">{{ $quiz->title }}</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold tracking-wide uppercase {{ $statusBg }} {{ $statusColor }}">
                        {{ $status }}
                    </span>
                </div>
                <p class="text-[#7C7167] text-[14px]">
                    {{ $quiz->questions()->count() }} questions 
                    @if($quiz->material) 
                        &middot; From: {{ $quiz->material->title }}
                    @endif
                </p>
            </div>
            
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 w-full md:w-auto mt-4 md:mt-0 pt-4 md:pt-0 border-t md:border-t-0 border-[#E2DDD8]">
                
                <div class="w-full sm:w-[150px] flex flex-col gap-1.5">
                    <div class="flex justify-between items-center">
                        <span class="text-[11px] text-[#A39D98] font-semibold uppercase tracking-wider">
                            {{ $attempt ? 'Final Score' : 'Progress' }}
                        </span>
                        <span class="text-[12px] text-[#1A1714] font-bold">
                            {{ $attempt ? $attempt->score . '/' . $attempt->total_questions : '0%' }}
                        </span>
                    </div>
                    <div class="flex-1 bg-[#F0EDE8] h-1.5 rounded-full overflow-hidden">
                        <div class="bg-[#6646E5] h-full rounded-full transition-all duration-500" style="width: {{ ($attempt && $attempt->total_questions > 0) ? ($attempt->score / $attempt->total_questions) * 100 : 0 }}%"></div>
                    </div>
                </div>
                
                @if(!$attempt)
                    <a href="{{ route('quizzes.session', $quiz->id) }}" class="w-full sm:w-auto bg-[#6646E5] hover:bg-[#5538D4] text-white px-6 py-2.5 rounded-[8px] font-semibold text-[13px] transition-colors whitespace-nowrap shadow-sm text-center block">
                        Start Now
                    </a>
                @else
                    <a href="{{ route('quizzes.breakdown', $attempt->id) }}" class="w-full sm:w-auto bg-white border border-[#E2DDD8] hover:bg-[#F9F8F6] text-[#1A1714] px-6 py-2.5 rounded-[8px] font-semibold text-[13px] transition-colors whitespace-nowrap text-center block">
                        Review Results
                    </a>
                @endif

            </div>
        </div>

        @empty
        <div class="flex flex-col items-center justify-center py-16 px-4 text-center border-2 border-dashed border-[#E2DDD8] rounded-[16px] bg-[#FAF9F7] w-full">
            <div class="w-16 h-16 bg-[#E0D8FC] rounded-[16px] flex items-center justify-center text-[28px] mb-5 shadow-sm transform -rotate-3 hover:rotate-0 transition-transform">
                ✨
            </div>
            <h3 class="text-[#1A1714] font-bold text-[18px] md:text-[20px] mb-2 font-['Syne',sans-serif]">It's a little quiet here...</h3>
            <p class="text-[#7C7167] text-[14px] md:text-[15px] max-w-[300px] mb-6 leading-relaxed">
                @if($currentTab == 'completed')
                    You haven't completed any quizzes yet. Finish an active quiz to see your results here!
                @else
                    You don't have any active quizzes right now. Upload some study material to generate one!
                @endif
            </p>
            <a href="/upload" class="bg-[#1A1714] text-white px-6 py-3 rounded-[10px] font-semibold text-[14px] hover:bg-[#2E2B28] shadow-md transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Upload Material
            </a>
        </div>
        @endforelse
        
    </div>
</div>
@endsection