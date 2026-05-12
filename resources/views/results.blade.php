@extends('layouts.app')

@section('content')
<div class="max-w-[1500px] mx-auto w-full pb-12 flex flex-col h-full">

    <div class="mb-8">
        <a href="{{ route('dashboard') }}" class="text-[#7C7167] text-[13px] font-semibold hover:text-[#1A1714] flex items-center gap-1 mb-2 transition-colors w-max">
            &larr; Dashboard
        </a>
        <h1 class="text-[33px] font-bold text-[#1A1714] tracking-tight font-['Special_Gothic_Expanded_One',sans-serif]">
            Performance History
        </h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <!-- Avg Score Card -->
        <div class="bg-white border border-[#E2DDD8] rounded-[20px] p-8 flex flex-col justify-center shadow-sm hover:border-[#6646E5] transition-colors group cursor-default">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-[#F0EDE8] group-hover:bg-[#E0D8FC] flex items-center justify-center transition-colors">
                    <x-heroicon-o-chart-pie class="w-5 h-5 text-[#7C7167] group-hover:text-[#6646E5] transition-colors" />
                </div>
                <span class="text-[#7C7167] text-[13px] font-bold tracking-wide uppercase">All-time Avg</span>
            </div>
            <span class="text-[#1A1714] text-[44px] font-bold font-['TASA_Orbiter',sans-serif] leading-tight">{{ $avgScore ?? '0' }}%</span>
        </div>

        <!-- Best Score Card -->
        <div class="bg-white border border-[#E2DDD8] rounded-[20px] p-8 flex flex-col justify-center shadow-sm hover:border-[#6646E5] transition-colors group cursor-default">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-[#F0EDE8] group-hover:bg-[#E0D8FC] flex items-center justify-center transition-colors">
                    <x-heroicon-o-trophy class="w-5 h-5 text-[#7C7167] group-hover:text-[#6646E5] transition-colors" />
                </div>
                <span class="text-[#7C7167] text-[13px] font-bold tracking-wide uppercase">Best Score</span>
            </div>
            <span class="text-[#1A1714] text-[44px] font-bold font-['TASA_Orbiter',sans-serif] leading-tight">{{ $bestScore ?? '0' }}%</span>
        </div>

        <!-- Total Quizzes Card -->
        <div class="bg-white border border-[#E2DDD8] rounded-[20px] p-8 flex flex-col justify-center shadow-sm hover:border-[#6646E5] transition-colors group cursor-default">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-[#F0EDE8] group-hover:bg-[#E0D8FC] flex items-center justify-center transition-colors">
                    <x-heroicon-o-document-duplicate class="w-5 h-5 text-[#7C7167] group-hover:text-[#6646E5] transition-colors" />
                </div>
                <span class="text-[#7C7167] text-[13px] font-bold tracking-wide uppercase">Total Quizzes</span>
            </div>
            <span class="text-[#1A1714] text-[44px] font-bold font-['TASA_Orbiter',sans-serif] leading-tight">{{ $totalQuizzes ?? '0' }}</span>
        </div>
    </div>

    <div class="bg-white border border-[#E2DDD8] rounded-[20px] shadow-sm flex flex-col w-full overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-[#FAF9F7] border-b border-[#E2DDD8]">
                        <th class="py-5 px-6 text-[#7C7167] text-[12px] font-bold tracking-[1px] uppercase">Assessment</th>
                        <th class="py-5 px-6 text-[#7C7167] text-[12px] font-bold tracking-[1px] uppercase">Date</th>
                        <th class="py-5 px-6 text-[#7C7167] text-[12px] font-bold tracking-[1px] uppercase">Questions</th>
                        <th class="py-5 px-6 text-[#7C7167] text-[12px] font-bold tracking-[1px] uppercase">Score</th>
                        <th class="py-5 px-6 text-[#7C7167] text-[12px] font-bold tracking-[1px] uppercase">Status</th>
                        <th class="py-5 px-6"></th> 
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($attempts as $attempt)
                    <tr class="border-b border-[#E2DDD8] hover:bg-[#F9F8F6] transition-colors last:border-b-0 group">
                        
                        <td class="py-5 px-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-[10px] bg-[#F0EDE8] flex items-center justify-center group-hover:bg-[#E0D8FC] group-hover:text-[#6646E5] text-[#7C7167] transition-colors">
                                    <x-heroicon-o-clipboard-document-list class="w-5 h-5" />
                                </div>
                                <span class="text-[#1A1714] text-[15px] font-bold">{{ $attempt->quiz->title ?? 'Untitled Quiz' }}</span>
                            </div>
                        </td>
                        
                        <td class="py-5 px-6">
                            <span class="text-[#7C7167] text-[14px] font-medium">{{ $attempt->completed_at ? $attempt->completed_at->format('M d, Y') : $attempt->created_at->format('M d, Y') }}</span>
                        </td>
                        
                        <td class="py-5 px-6">
                            <span class="text-[#1A1714] text-[14px] font-bold">{{ $attempt->score }} <span class="text-[#A39D98] font-medium">/ {{ $attempt->total_questions }}</span></span>
                        </td>
                        
                        <td class="py-5 px-6">
                            @php
                                $percentage = ($attempt->total_questions > 0) ? ($attempt->score / $attempt->total_questions) * 100 : 0;
                                $scoreColor = 'text-[#E11D48]'; // Rose for low
                                if ($percentage >= 90) {
                                    $scoreColor = 'text-[#16A34A]'; // Green
                                } elseif ($percentage >= 70) {
                                    $scoreColor = 'text-[#D97706]'; // Amber
                                }
                            @endphp
                            <span class="{{ $scoreColor }} text-[15px] font-bold font-['TASA_Orbiter',sans-serif]">
                                {{ round($percentage) }}%
                            </span>
                        </td>
                        
                        <td class="py-5 px-6">
                            @if($percentage >= 75)
                                <div class="bg-[#D4F5E3] text-[#166534] px-3 py-1 rounded-full text-[12px] font-semibold tracking-wide w-max border border-[#BBF7D0]">
                                    Complete
                                </div>
                            @else
                                <div class="bg-[#FEF3C7] text-[#92400E] px-3 py-1 rounded-full text-[12px] font-semibold tracking-wide w-max border border-[#FDE68A]">
                                    Needs Review
                                </div>
                            @endif
                        </td>
                        
                        <td class="py-5 px-6 text-right">
                            <a href="{{ route('quizzes.breakdown', $attempt->id) }}" class="text-[#1A1714] bg-white border border-[#E2DDD8] hover:border-[#6646E5] hover:text-[#6646E5] font-semibold text-[12px] px-4 py-2.5 rounded-[8px] transition-all flex items-center gap-1 justify-center w-max ml-auto shadow-sm">
                                View Details &rarr;
                            </a>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-20 text-center">
                            <div class="flex flex-col items-center justify-center gap-4">
                                <div class="w-16 h-16 bg-[#F0EDE8] rounded-[16px] flex items-center justify-center text-[#7C7167]">
                                    <x-heroicon-o-document-magnifying-glass class="w-8 h-8" />
                                </div>
                                <h3 class="text-[#1A1714] font-bold text-[18px]">No History Yet</h3>
                                <p class="text-[#7C7167] font-medium text-[14px]">You haven't completed any quizzes. Your performance records will appear here.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    @if($attempts instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-6 flex justify-center">
            {{ $attempts->links() }}
        </div>
    @endif

</div>
@endsection