@extends('layouts.app')

@section('content')
<div class="max-w-[1500px] mx-auto w-full h-full flex flex-col pb-4 overflow-hidden">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4 flex-shrink-0">
        <div>
            <h2 class="text-[#7C7167] text-[14px] font-medium mb-1">{{ now()->format('l, F j · Y') }}</h2>
            <h1 class="text-[33px] font-bold text-[#1A1714] tracking-tight">
                Hello, {{ auth()->check() ? auth()->user()->first_name : 'User' }}
            </h1>
        </div>
        <a href="{{ route('materials.upload') }}" class="bg-[#6646E5] hover:bg-[#5538D4] text-white px-5 py-2.5 rounded-lg font-semibold text-[13px] transition-colors flex items-center gap-2">
            <span>+</span> Upload Material
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6 flex-shrink-0">
        <x-metric-card title="Materials Uploaded" value="{{ $stats['materials_count'] }}" badgeText="" badgeType="success" />
        <x-metric-card title="Quizzes Taken" value="{{ $stats['quizzes_count'] }}" badgeText="" badgeType="info" />
        <x-metric-card title="Avg. Quiz Score" value="{{ $stats['avg_score'] }}" badgeText="" badgeType="success" />
        <x-metric-card title="Study Streak" subText="days in a row">
            <div class="flex items-center gap-2">
                <x-heroicon-s-fire class="w-15 h-15 text-orange-500" />
                <span class="text-4xl font-bold">0</span>
            </div>
        </x-metric-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 flex-[2] min-h-0">
        
        <div class="bg-white border border-[#E2DDD8] rounded-[16px] p-6 shadow-sm flex flex-col h-full min-h-0">
            <div class="flex justify-between items-center mb-4 flex-shrink-0">
                <h3 class="text-[18px] font-bold text-[#1A1714]">Recent Materials</h3>
                <a href="{{ route('materials.index') }}" class="text-[12px] font-semibold text-[#7C7167] hover:text-[#1A1714] transition-colors">View all &rarr;</a>
            </div>
            
            <div class="flex flex-col gap-3 overflow-y-auto flex-1 min-h-0 pr-2">
                @forelse($materials->take(5) as $material)
                @php
                    $extension = pathinfo($material->original_path, PATHINFO_EXTENSION);
                    $iconName = match(strtolower($extension)) {
                        'pdf' => 'heroicon-s-document-text',
                        'jpg', 'jpeg', 'png' => 'heroicon-s-photo',
                        default => 'heroicon-s-document',
                    };
                    $iconColor = match(strtolower($extension)) {
                        'pdf' => 'text-[#E11D48]',
                        'jpg', 'jpeg', 'png' => 'text-[#2563EB]',
                        default => 'text-[#4B5563]',
                    };
                    $iconBg = match(strtolower($extension)) {
                        'pdf' => 'bg-[#FFE4E6]',
                        'jpg', 'jpeg', 'png' => 'bg-[#DBEAFE]',
                        default => 'bg-[#F3F4F6]',
                    };
                @endphp
                <div class="flex items-center gap-4 p-3 hover:bg-[#F9F8F6] rounded-xl transition-colors cursor-pointer border border-transparent hover:border-[#E2DDD8] flex-shrink-0">
                   <div class="w-12 h-12 {{ $iconBg }} rounded-xl flex items-center justify-center">
                        <x-dynamic-component :component="$iconName" class="w-6 h-6 {{ $iconColor }}" />
                    </div>
                    <div class="flex-1">
                        <h4 class="text-[15px] font-semibold text-[#1A1714]">{{ $material->title }}</h4>
                        <p class="text-[13px] text-[#7C7167]">Uploaded on {{ $material->created_at->format('M d') }}</p>
                    </div>
                    <div>
                        <span class="px-3 py-1 rounded-full text-[11px] font-semibold tracking-wide 
                            {{ $material->status == 'completed' ? 'bg-[#D4F5E3] text-[#166534]' : ($material->status == 'processing' ? 'bg-[#FEF3C7] text-[#92400E]' : 'bg-gray-100 text-gray-600') }}">
                            {{ ucfirst($material->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center h-full text-[#7C7167] py-8">
                    <p class="text-[14px]">No materials uploaded yet.</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white border border-[#E2DDD8] rounded-[16px] p-6 shadow-sm flex flex-col h-full min-h-0">
            <div class="flex justify-between items-center mb-4 flex-shrink-0">
                <h3 class="text-[18px] font-bold text-[#1A1714]">Active Quizzes</h3>
                <a href="{{ route('quizzes.index') }}" class="text-[#7C7167] text-[13px] font-semibold hover:text-[#1A1714]">All &rarr;</a>
            </div>
            
            <div class="flex flex-col gap-3 overflow-y-auto flex-1 min-h-0 pr-2">
                @forelse($quizzes as $quiz)
                @php
                    $attempt = auth()->user()->quizAttempts()->where('quiz_id', $quiz->id)->latest()->first();
                @endphp
                <div class="flex items-center gap-4 p-4 border border-[#E2DDD8] rounded-[12px] hover:border-[#6646E5] transition-colors flex-shrink-0">

                <div class="w-12 h-12 {{ $attempt ? 'bg-[#D4F5E3]' : 'bg-[#FEF3C7]' }} rounded-xl flex items-center justify-center">
                     <x-heroicon-o-clipboard-document-list class="w-6 h-6 {{ $attempt ? 'text-[#166534]' : 'text-[#92400E]' }}" />
                </div>

                    <div class="flex-1">
                        <h4 class="text-[15px] font-semibold text-[#1A1714]">{{ $quiz->title }}</h4>
                        <p class="text-[13px] text-[#7C7167]">{{ $quiz->questions()->count() }} questions</p>
                        
                        <div class="mt-2 flex items-center gap-3">
                            @php
                                $percent = ($attempt && $attempt->total_questions > 0) ? ($attempt->score / $attempt->total_questions) * 100 : 0;
                            @endphp
                            <div class="flex-1 bg-[#F0EDE8] h-1.5 rounded-full overflow-hidden">
                                <div class="bg-[#6646E5] h-full rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                            </div>
                            <span class="text-[11px] text-[#7C7167] font-medium min-w-[50px]">
                                {{ $attempt ? 'Score: ' . $attempt->score . '/' . $attempt->total_questions : 'Not started' }}
                            </span>
                        </div>
                    </div>
                    @if(!$attempt)
                        <a href="{{ route('quizzes.session', $quiz->id) }}" class="px-4 py-2 border border-[#E2DDD8] rounded-lg text-[#1A1714] text-[12px] font-semibold hover:bg-[#F9F8F6] transition-colors">
                            Start &rarr;
                        </a>
                    @else
                        <a href="{{ route('quizzes.breakdown', $attempt->id) }}" class="px-4 py-2 border border-[#6646E5] bg-[#6646E5]/5 rounded-lg text-[#6646E5] text-[12px] font-semibold hover:bg-[#6646E5] hover:text-white transition-all">
                            Result &rarr;
                        </a>
                    @endif
                </div>
                @empty
                <div class="flex flex-col items-center justify-center h-full text-[#7C7167] py-8">
                    <p class="text-[14px]">No active quizzes yet.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>

    <div class="bg-white border border-[#E2DDD8] rounded-[16px] p-6 shadow-sm flex flex-col h-[32%] min-h-[280px]">
        <div class="flex justify-between items-center mb-4 flex-shrink-0">
            <h3 class="text-[20px] font-bold text-[#1A1714]">Weekly Performance</h3>
            <span class="bg-[#F0EDE8] text-[#7C7167] px-4 py-2 rounded-full text-[12px] font-semibold tracking-wide">Stay consistent!</span>
        </div>
        
        <div class="flex-1 flex items-end justify-between gap-4 px-6 pb-2 min-h-0">
            @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
            <div class="flex flex-col items-center gap-2 flex-1 h-full justify-end">
                <div class="w-full max-w-[50px] bg-[#6646E5] rounded-lg opacity-20 hover:opacity-100 transition-opacity cursor-pointer h-[4px]"></div>
                <span class="text-[12px] text-[#7C7167] font-medium flex-shrink-0">{{ $day }}</span>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
