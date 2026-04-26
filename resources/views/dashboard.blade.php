@extends('layouts.app')

@section('content')
<div class="max-w-[1500px] mx-auto w-full h-full flex flex-col pb-4">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4 flex-shrink-0">
        <div>
            <h2 class="text-[#7C7167] text-[14px] font-medium mb-1">{{ now()->format('l, F j · Y') }}</h2>
            <h1 class="text-[33px] font-bold text-[#1A1714] tracking-tight">
                Hello, {{ auth()->check() ? auth()->user()->first_name : 'Wyndy Shane' }}
            </h1>
        </div>
        <a href="/upload" class="bg-[#6646E5] hover:bg-[#5538D4] text-white px-5 py-2.5 rounded-lg font-semibold text-[13px] transition-colors flex items-center gap-2">
            <span>+</span> Upload Material
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6 flex-shrink-0">
        <x-metric-card title="Materials Uploaded" value="12" badgeText="+3 this week" badgeType="success" />
        <x-metric-card title="Quizzes Taken" value="28" badgeText="5 pending" badgeType="info" />
        <x-metric-card title="Avg. Quiz Score" value="87%" badgeText="↑ 4% vs last week" badgeType="success" />
        <x-metric-card title="Study Streak" value="🔥 7" subText="days in a row" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 flex-[3] min-h-0">
        
        <div class="bg-white border border-[#E2DDD8] rounded-[16px] p-6 shadow-sm flex flex-col h-full">
            <div class="flex justify-between items-center mb-4 flex-shrink-0">
                <h3 class="text-[18px] font-bold text-[#1A1714]">Recent Materials</h3>
                <a href="/materials" class="text-[12px] font-semibold text-[#7C7167] hover:text-[#1A1714] transition-colors">View all &rarr;</a>
            </div>
            
            <div class="flex flex-col gap-3 flex-1 overflow-y-auto min-h-0 pr-2">
                @php
                    $materials = [
                        ['name' => 'Python Programming.docx', 'date' => 'Apr 20', 'concepts' => 8, 'status' => 'Reviewed', 'color' => 'success'],
                        ['name' => 'Database Management.pdf', 'date' => 'Apr 18', 'concepts' => 5, 'status' => 'Processing', 'color' => 'warning'],
                    ];
                @endphp

                @foreach($materials as $material)
                <div class="flex items-center gap-4 p-3 hover:bg-[#F9F8F6] rounded-xl transition-colors cursor-pointer border border-transparent hover:border-[#E2DDD8] flex-shrink-0">
                    <div class="w-12 h-12 bg-[#F0EDE8] rounded-xl flex items-center justify-center text-[20px]">
                        📄
                    </div>
                    <div class="flex-1">
                        <h4 class="text-[15px] font-semibold text-[#1A1714]">{{ $material['name'] }}</h4>
                        <p class="text-[13px] text-[#7C7167]">Uploaded {{ $material['date'] }} · {{ $material['concepts'] }} concepts extracted</p>
                    </div>
                    <div>
                        <span class="px-3 py-1 rounded-full text-[11px] font-semibold tracking-wide 
                            {{ $material['color'] == 'success' ? 'bg-[#D4F5E3] text-[#166534]' : 'bg-[#FEF3C7] text-[#92400E]' }}">
                            {{ $material['status'] }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white border border-[#E2DDD8] rounded-[16px] p-6 shadow-sm flex flex-col h-full">
            <div class="flex justify-between items-center mb-4 flex-shrink-0">
                <h3 class="text-[18px] font-bold text-[#1A1714]">Active Quizzes</h3>
                <a href="/quizzes" class="text-[#7C7167] text-[13px] font-semibold hover:text-[#1A1714]">All &rarr;</a>
            </div>
            
            <div class="flex flex-col gap-3 flex-1 overflow-y-auto min-h-0 pr-2">
                @php
                    $quizzes = [
                        ['name' => 'Python Fundamentals', 'topic' => 'Syntax & Logic', 'qs' => 10, 'progress' => 60, 'status' => 'Continue'],
                        ['name' => 'Data Structures', 'topic' => 'Arrays & Linked Lists', 'qs' => 8, 'progress' => 0, 'status' => 'Start'],
                    ];
                @endphp

                @foreach($quizzes as $quiz)
                <div class="flex items-center gap-4 p-4 border border-[#E2DDD8] rounded-[12px] hover:border-[#6646E5] transition-colors flex-shrink-0">
                    <div class="w-12 h-12 bg-[#FEF3C7] rounded-xl flex items-center justify-center text-[20px]">
                        📑
                    </div>
                    <div class="flex-1">
                        <h4 class="text-[15px] font-semibold text-[#1A1714]">{{ $quiz['name'] }}</h4>
                        <p class="text-[13px] text-[#7C7167]">{{ $quiz['topic'] }} · {{ $quiz['qs'] }} questions</p>
                        
                        <div class="mt-2 flex items-center gap-3">
                            <div class="flex-1 bg-[#F0EDE8] h-1.5 rounded-full overflow-hidden">
                                <div class="bg-[#6646E5] h-full rounded-full" style="width: {{ $quiz['progress'] }}%"></div>
                            </div>
                            <span class="text-[11px] text-[#7C7167] font-medium min-w-[50px]">{{ $quiz['progress'] == 0 ? 'Not started' : $quiz['progress'] . '% done' }}</span>
                        </div>
                    </div>
                    <button class="px-4 py-2 border border-[#E2DDD8] rounded-lg text-[#1A1714] text-[12px] font-semibold hover:bg-[#F9F8F6] transition-colors">
                        {{ $quiz['status'] }} &rarr;
                    </button>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    <div class="bg-white border border-[#E2DDD8] rounded-[16px] p-6 shadow-sm flex flex-col flex-[2] min-h-[220px]">
        <div class="flex justify-between items-center mb-4 flex-shrink-0">
            <h3 class="text-[20px] font-bold text-[#1A1714]">Weekly Performance</h3>
            <span class="bg-[#D4F5E3] text-[#166534] px-4 py-2 rounded-full text-[12px] font-semibold tracking-wide">↑ Improving</span>
        </div>
        
        <div class="flex-1 flex items-end justify-between gap-4 px-6 pb-2 min-h-0">
            @foreach(['Mon' => 60, 'Tue' => 40, 'Wed' => 80, 'Thu' => 30, 'Fri' => 90, 'Sat' => 20, 'Sun' => 50] as $day => $height)
            <div class="flex flex-col items-center gap-2 flex-1 h-full justify-end">
                <div class="w-full max-w-[50px] bg-[#6646E5] rounded-lg opacity-{{ $height == 90 ? '100' : '60' }} hover:opacity-100 transition-opacity cursor-pointer min-h-[4px]" style="height: {{ $height }}%;"></div>
                <span class="text-[12px] text-[#7C7167] font-medium flex-shrink-0">{{ $day }}</span>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection