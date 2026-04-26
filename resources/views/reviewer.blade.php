@extends('layouts.app')

@section('content')
<div class="max-w-[1500px] mx-auto w-full pb-12">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <a href="/dashboard" class="text-[#7C7167] text-[13px] font-semibold hover:text-[#1A1714] flex items-center gap-1 mb-2 transition-colors w-max">
                &larr; Dashboard
            </a>
            <h1 class="text-[33px] font-bold text-[#1A1714] tracking-tight font-['Special_Gothic_Expanded_One',sans-serif]">
                AI Reviewer
            </h1>
        </div>
        <a href="/quizzes" class="bg-[#6646E5] hover:bg-[#5538D4] text-white px-6 py-2.5 rounded-[8px] font-semibold text-[13px] transition-colors flex items-center gap-2">
            Take Quiz &rarr;
        </a>
    </div>

    <div class="bg-[#1A1714] rounded-[16px] p-6 md:p-8 mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 shadow-md">
        <div class="flex flex-col gap-3">
            <span class="bg-[#E0D8FC] text-[#6646E5] px-3 py-1 rounded-full text-[11px] font-semibold w-max tracking-wide">
                AI Generated
            </span>
            <h2 class="text-white text-[24px] md:text-[26px] font-bold font-['Special_Gothic_Expanded_One',sans-serif]">
                Python_Programming.docx
            </h2>
            <p class="text-[#9E9690] text-[14px]">
                Processed Apr 20, 2025 &middot; 8 key concepts identified &middot; ~12 min read
            </p>
        </div>
        <div class="text-[40px] bg-white/10 w-16 h-16 rounded-2xl flex items-center justify-center flex-shrink-0">
            📑
        </div>
    </div>

    <div class="flex justify-between items-center mb-4 px-2">
        <h3 class="text-[18px] font-bold text-[#1A1714]">Key Concepts</h3>
        <span class="text-[#7C7167] text-[14px]">Click to expand</span>
    </div>

    <div class="flex flex-col gap-4 mb-12">
        @php
            $concepts = [
                [
                    'num' => '1',
                    'title' => 'Variables & Data Types',
                    'content' => 'Python is a dynamically typed language. Common data types include int, float, str, bool, list, tuple, dict, and set. Variables are assigned using the = operator and can hold any type.',
                    'bg_color' => 'bg-[#D4F5E3]', 'text_color' => 'text-[#166534]', // Green
                    'open' => true // First item open by default
                ],
                [
                    'num' => '2',
                    'title' => 'Control Flow (if/elif/else)',
                    'content' => 'Control flow allows your program to make decisions. If a condition evaluates to True, the corresponding block of code runs. Elif and else provide alternative paths.',
                    'bg_color' => 'bg-[#DBEAFE]', 'text_color' => 'text-[#1E40AF]', // Blue
                    'open' => false
                ],
                [
                    'num' => '3',
                    'title' => 'Loops (for & while)',
                    'content' => 'For loops iterate over a sequence (like a list or string). While loops execute a block of code as long as a specified condition remains True.',
                    'bg_color' => 'bg-[#FEF3C7]', 'text_color' => 'text-[#92400E]', // Yellow
                    'open' => false
                ],
                [
                    'num' => '4',
                    'title' => 'Functions & Scope',
                    'content' => 'Functions are reusable blocks of code defined using the def keyword. Scope determines the visibility of variables (local vs. global).',
                    'bg_color' => 'bg-[#FCE7F3]', 'text_color' => 'text-[#9D174D]', // Pink
                    'open' => false
                ],
                [
                    'num' => '5',
                    'title' => 'Object-Oriented Programming',
                    'content' => 'OOP in Python involves creating classes and objects. Concepts include inheritance, encapsulation, and polymorphism to structure complex code.',
                    'bg_color' => 'bg-[#D4F5E3]', 'text_color' => 'text-[#166534]', // Green
                    'open' => false
                ],
            ];
        @endphp

        @foreach($concepts as $concept)
        <details class="group bg-white border border-[#E2DDD8] rounded-[12px] shadow-sm transition-all" {{ $concept['open'] ? 'open' : '' }}>
            <summary class="flex justify-between items-center p-4 md:p-6 cursor-pointer list-none focus:outline-none rounded-[12px] hover:bg-[#F9F8F6] transition-colors">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 {{ $concept['bg_color'] }} {{ $concept['text_color'] }} rounded-[8px] flex items-center justify-center font-bold text-[13px]">
                        {{ $concept['num'] }}
                    </div>
                    <h4 class="text-[15px] md:text-[16px] font-semibold text-[#1A1714]">
                        {{ $concept['title'] }}
                    </h4>
                </div>
                <svg class="w-5 h-5 text-[#7C7167] transition-transform duration-200 group-open:-rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </summary>
            
            <div class="px-4 md:px-6 pb-6 pt-2 text-[#7C7167] text-[14px] md:text-[15px] leading-relaxed pl-[4.5rem]">
                {{ $concept['content'] }}
            </div>
        </details>
        @endforeach
    </div>

    <div class="bg-[#E0D8FC] rounded-[16px] p-6 md:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 shadow-sm border border-[#D1C4F9]">
        <div>
            <h3 class="text-[#1A1714] text-[18px] font-bold mb-1">Ready to test your knowledge?</h3>
            <p class="text-[#5538D4] text-[14px] font-medium">An adaptive quiz has been generated based on this material.</p>
        </div>
        <a href="/quizzes" class="bg-[#6646E5] hover:bg-[#5538D4] text-white px-6 py-3 rounded-[10px] font-semibold text-[14px] transition-colors shadow-sm whitespace-nowrap">
            Take Quiz &rarr;
        </a>
    </div>

</div>
@endsection