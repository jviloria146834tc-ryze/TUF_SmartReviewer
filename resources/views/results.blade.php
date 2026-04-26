@extends('layouts.app')

@section('content')
<div class="max-w-[1500px] mx-auto w-full pb-12 flex flex-col h-full">

    <div class="mb-8">
        <h1 class="text-[33px] font-bold text-[#1A1714] tracking-tight font-['Special_Gothic_Expanded_One',sans-serif]">
            Performance History
        </h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white border border-[#E2DDD8] rounded-[16px] p-6 flex flex-col justify-center shadow-sm">
            <span class="text-[#7C7167] text-[13px] font-medium mb-1">All-time Avg.</span>
            <span class="text-[#1A1714] text-[48px] font-bold font-['TASA_Orbiter',sans-serif] leading-tight">87%</span>
        </div>

        <div class="bg-white border border-[#E2DDD8] rounded-[16px] p-6 flex flex-col justify-center shadow-sm">
            <span class="text-[#7C7167] text-[13px] font-medium mb-1">Best Score</span>
            <span class="text-[#1A1714] text-[48px] font-bold font-['TASA_Orbiter',sans-serif] leading-tight">96%</span>
        </div>

        <div class="bg-white border border-[#E2DDD8] rounded-[16px] p-6 flex flex-col justify-center shadow-sm">
            <span class="text-[#7C7167] text-[13px] font-medium mb-1">Total Quizzes</span>
            <span class="text-[#1A1714] text-[48px] font-bold font-['TASA_Orbiter',sans-serif] leading-tight">28</span>
        </div>

    </div>

    <div class="bg-white border border-[#E2DDD8] rounded-[16px] shadow-sm flex flex-col w-full overflow-hidden">
        
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                
                <thead>
                    <tr class="border-b border-[#E2DDD8] bg-[#FAF9F7]">
                        <th class="py-4 px-6 text-[#7C7167] text-[12px] font-semibold tracking-[0.72px] uppercase">Assessment</th>
                        <th class="py-4 px-6 text-[#7C7167] text-[12px] font-semibold tracking-[0.72px] uppercase">Date</th>
                        <th class="py-4 px-6 text-[#7C7167] text-[12px] font-semibold tracking-[0.72px] uppercase">Questions</th>
                        <th class="py-4 px-6 text-[#7C7167] text-[12px] font-semibold tracking-[0.72px] uppercase">Score</th>
                        <th class="py-4 px-6 text-[#7C7167] text-[12px] font-semibold tracking-[0.72px] uppercase">Status</th>
                        <th class="py-4 px-6"></th> </tr>
                </thead>

                <tbody>
                    @php
                        // Mock database for performance history
                        $history = [
                            [
                                'title' => 'Python Programming Assessment',
                                'date' => 'Apr 20, 2025',
                                'questions' => '10 / 10',
                                'score' => 92,
                                'status' => 'Complete'
                            ],
                            [
                                'title' => 'Data Structures — Arrays',
                                'date' => 'Apr 17, 2025',
                                'questions' => '8 / 8',
                                'score' => 78,
                                'status' => 'Complete'
                            ],
                            [
                                'title' => 'Database — SQL Basics',
                                'date' => 'Apr 14, 2025',
                                'questions' => '12 / 12',
                                'score' => 58,
                                'status' => 'Needs Review'
                            ],
                            [
                                'title' => 'Algorithms — Sorting',
                                'date' => 'Apr 10, 2025',
                                'questions' => '10 / 10',
                                'score' => 58,
                                'status' => 'Needs Review'
                            ]
                        ];
                    @endphp

                    @foreach($history as $record)
                    <tr class="border-b border-[#E2DDD8] hover:bg-[#F9F8F6] transition-colors last:border-b-0">
                        
                        <td class="py-5 px-6">
                            <span class="text-[#1A1714] text-[14px] font-medium">{{ $record['title'] }}</span>
                        </td>
                        
                        <td class="py-5 px-6">
                            <span class="text-[#7C7167] text-[14px]">{{ $record['date'] }}</span>
                        </td>
                        
                        <td class="py-5 px-6">
                            <span class="text-[#7C7167] text-[14px]">{{ $record['questions'] }}</span>
                        </td>
                        
                        <td class="py-5 px-6">
                            @php
                                $scoreColor = 'text-[#EF4444]'; // Default red
                                if ($record['score'] >= 90) {
                                    $scoreColor = 'text-[#16A34A]'; // Green
                                } elseif ($record['score'] >= 70) {
                                    $scoreColor = 'text-[#F59E0B]'; // Orange/Yellow
                                }
                            @endphp
                            <span class="{{ $scoreColor }} text-[14px] font-bold font-['TASA_Orbiter',sans-serif]">
                                {{ $record['score'] }}%
                            </span>
                        </td>
                        
                        <td class="py-5 px-6">
                            @if($record['status'] == 'Complete')
                                <div class="bg-[#D1FAE5] text-[#065F46] px-3 py-1 rounded-full text-[11px] font-semibold tracking-wide w-max">
                                    Complete
                                </div>
                            @else
                                <div class="bg-[#FEE2E2] text-[#991B1B] px-3 py-1 rounded-full text-[11px] font-semibold tracking-wide w-max">
                                    Needs Review
                                </div>
                            @endif
                        </td>
                        
                        <td class="py-5 px-6 text-right">
                            <a href="#" class="text-[#7C7167] hover:text-[#6646E5] font-semibold text-[12px] transition-colors flex items-center gap-1 justify-end w-max ml-auto">
                                View &rarr;
                            </a>
                        </td>

                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection