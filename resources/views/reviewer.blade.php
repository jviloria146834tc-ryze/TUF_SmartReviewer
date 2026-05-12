@extends('layouts.app')

@section('content')
<div class="max-w-[1500px] mx-auto w-full pb-12">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <a href="{{ route('dashboard') }}" class="text-[#7C7167] text-[13px] font-semibold hover:text-[#1A1714] flex items-center gap-1 mb-2 transition-colors w-max">
                &larr; Dashboard
            </a>
            <h1 class="text-[33px] font-bold text-[#1A1714] tracking-tight font-['Special_Gothic_Expanded_One',sans-serif]">
                AI Reviewer
            </h1>
        </div>
        <a href="{{ ($material && $material->quizzes->first()) ? route('quizzes.session', $material->quizzes->first()->id) : route('quizzes.index') }}" class="bg-[#6646E5] hover:bg-[#5538D4] text-white px-6 py-2.5 rounded-[8px] font-semibold text-[13px] transition-colors flex items-center gap-2">
            Take Quiz &rarr;
        </a>
    </div>

    <div class="bg-[#1A1714] rounded-[16px] p-6 md:p-8 mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 shadow-md">
        <div class="flex flex-col gap-3">
            <span class="bg-[#E0D8FC] text-[#6646E5] px-3 py-1 rounded-full text-[11px] font-semibold w-max tracking-wide">
                {{ $material ? 'AI Generated' : 'No Material' }}
            </span>
            <h2 class="text-white text-[24px] md:text-[26px] font-bold font-['Special_Gothic_Expanded_One',sans-serif]">
                {{ $material->title ?? 'Select a material' }}
            </h2>
            <p class="text-[#9E9690] text-[14px]">
                @if($material)
                    Uploaded {{ $material->created_at->format('M d, Y') }} &middot; Status: {{ ucfirst($material->status) }}
                @else
                    Please upload or select a material to view concepts.
                @endif
            </p>
        </div>
        <div class="bg-white/10 w-16 h-16 rounded-2xl flex items-center justify-center flex-shrink-0">
        <x-heroicon-o-clipboard-document-list class="w-8 h-8 text-white" />
        </div>
    </div>

    @if($material)
    <div class="bg-gradient-to-br from-[#F9F8F6] to-white border-l-4 border-[#6646E5] border-y border-r border-y-[#E2DDD8] border-r-[#E2DDD8] rounded-r-[16px] rounded-l-[4px] p-6 md:p-8 mb-10 shadow-md relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-[#6646E5] opacity-5 rounded-full blur-2xl"></div>
        <div class="flex items-center gap-3 mb-5 relative z-10">
            <div class="w-8 h-8 rounded-full bg-[#E0D8FC] flex items-center justify-center">
                <x-heroicon-s-sparkles class="w-5 h-5 text-[#6646E5]" />
            </div>
            <h3 class="text-[20px] md:text-[22px] font-bold text-[#1A1714] tracking-tight">AI Summary</h3>
        </div>
        <p class="text-[#2E2B28] text-[16px] md:text-[18px] leading-[1.8] font-medium relative z-10">
            {{ $material->quizzes->first()->summary ?? 'No summary available.' }}
        </p>
    </div>
    @endif

    <div class="flex justify-between items-center mb-4 px-2">
        <h3 class="text-[18px] font-bold text-[#1A1714]">Key Concepts</h3>
    </div>

    <div class="flex flex-col gap-4 mb-12">
        @if($material && $material->quizzes->first() && $material->quizzes->first()->concepts)
            <div class="grid grid-cols-1 gap-4" x-data="{ activeConcept: null }">
                @foreach($material->quizzes->first()->concepts as $index => $concept)
                <div class="bg-white border border-[#E2DDD8] rounded-xl overflow-hidden hover:border-[#6646E5] transition-colors shadow-sm">
                    <button 
                        @click="activeConcept = (activeConcept === {{ $index }} ? null : {{ $index }})"
                        class="w-full p-4 flex items-center justify-between text-left focus:outline-none"
                    >
                        <div class="flex items-center gap-4">
                            <div class="w-2 h-2 rounded-full bg-[#6646E5]"></div>
                            <span class="text-[#1A1714] font-bold text-[15px]">
                                {{ is_array($concept) ? $concept['title'] : $concept }}
                            </span>
                        </div>
                        <x-heroicon-o-chevron-down 
                            class="w-5 h-5 text-[#7C7167] transition-transform duration-200"
                            ::class="activeConcept === {{ $index }} ? 'rotate-180' : ''"
                        />
                    </button>
                    
                    <div 
                        x-show="activeConcept === {{ $index }}" 
                        x-collapse 
                        x-cloak
                        class="px-4 pb-5 pt-0 border-t border-[#F0EDE8]"
                    >
                        <p class="text-[#2E2B28] text-[16px] leading-relaxed mt-4 bg-[#F9F8F6] p-4 rounded-lg border border-[#E2DDD8]">
                            {{ is_array($concept) ? $concept['short_explanation'] : 'No explanation available for this concept.' }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-12 text-[#7C7167] bg-white border border-dashed border-[#E2DDD8] rounded-xl">
                <p>No concepts found. Ensure your material is processed.</p>
            </div>
        @endif
    </div>

    <div class="bg-[#E0D8FC] rounded-[16px] p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 shadow-sm border border-[#D1C4F9]">
        <div>
            <h3 class="text-[#1A1714] text-[18px] font-bold mb-1">Ready to test your knowledge?</h3>
            <p class="text-[#5538D4] text-[14px] font-medium">An adaptive quiz has been generated based on this material.</p>
        </div>
        <a href="{{ ($material && $material->quizzes->first()) ? route('quizzes.session', $material->quizzes->first()->id) : route('quizzes.index') }}" class="bg-[#6646E5] hover:bg-[#5538D4] text-white px-6 py-3 rounded-[10px] font-semibold text-[14px] transition-colors shadow-sm whitespace-nowrap">
            Take Quiz &rarr;
        </a>
    </div>

</div>
@endsection