@extends('layouts.app')

@section('content')
<div class="max-w-[1500px] mx-auto w-full pb-12 flex flex-col h-full">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8">
        <div>
            <a href="/dashboard" class="text-[#7C7167] text-[13px] font-semibold hover:text-[#1A1714] flex items-center gap-1 mb-2 transition-colors w-max">
                &larr; Back to Dashboard
            </a>
            <h1 class="text-[33px] font-bold text-[#1A1714] tracking-tight font-['Special_Gothic_Expanded_One',sans-serif]">
                Study Materials
            </h1>
        </div>
        
        <a href="/upload" class="bg-[#6646E5] hover:bg-[#5538D4] text-white px-5 py-2.5 rounded-[10px] font-semibold text-[14px] transition-colors shadow-sm flex items-center gap-2 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Upload Material
        </a>
    </div>

    <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="relative flex-1 max-w-[400px]">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-[#A39D98]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" placeholder="Search materials..." class="w-full border border-[#E2DDD8] rounded-[12px] pl-10 pr-4 py-2.5 text-[14px] text-[#1A1714] focus:outline-none focus:border-[#6646E5] focus:ring-1 focus:ring-[#6646E5] transition-all shadow-sm">
        </div>
        
        <select class="border border-[#E2DDD8] rounded-[12px] px-4 py-2.5 text-[14px] text-[#1A1714] focus:outline-none focus:border-[#6646E5] focus:ring-1 focus:ring-[#6646E5] transition-all shadow-sm bg-white cursor-pointer appearance-none outline-none min-w-[150px]">
            <option>All Statuses</option>
            <option>Reviewed</option>
            <option>Processing</option>
            <option>New</option>
        </select>
    </div>

    <div class="flex flex-col gap-4">

        @php
            // Mock database for uploaded materials. 
            // Try changing this to $materials = []; to see the empty state!
            $materials = [
                [
                    'title' => 'Python Programming.docx',
                    'date' => 'Uploaded Apr 20',
                    'details' => '8 concepts extracted',
                    'icon' => '📄',
                    'icon_bg' => 'bg-[#E0D8FC]', // Purple hint for docs
                    'status' => 'Reviewed',
                    'status_bg' => 'bg-[#D4F5E3]',
                    'status_color' => 'text-[#166534]'
                ],
                [
                    'title' => 'Database Management.pdf',
                    'date' => 'Uploaded Apr 18',
                    'details' => '5 concepts extracted',
                    'icon' => '📕',
                    'icon_bg' => 'bg-[#FCE7F3]', // Pink hint for PDFs
                    'status' => 'Processing',
                    'status_bg' => 'bg-[#FEF3C7]',
                    'status_color' => 'text-[#92400E]'
                ],
                [
                    'title' => 'Biology 101 Notes.jpg',
                    'date' => 'Uploaded Apr 15',
                    'details' => '12 concepts extracted',
                    'icon' => '🖼️',
                    'icon_bg' => 'bg-[#DBEAFE]', // Blue hint for images
                    'status' => 'Reviewed',
                    'status_bg' => 'bg-[#D4F5E3]',
                    'status_color' => 'text-[#166534]'
                ],
                [
                    'title' => 'History Chapter 4 Summary.pdf',
                    'date' => 'Uploaded Apr 10',
                    'details' => 'Awaiting processing',
                    'icon' => '📕',
                    'icon_bg' => 'bg-[#FCE7F3]',
                    'status' => 'New',
                    'status_bg' => 'bg-[#F3F4F6]',
                    'status_color' => 'text-[#4B5563]'
                ]
            ];
        @endphp

        @forelse($materials as $material)
        <div class="bg-white border border-[#E2DDD8] rounded-[16px] p-5 flex flex-col md:flex-row items-start md:items-center gap-5 shadow-sm hover:border-[#6646E5] hover:shadow-md transition-all group">
            
            <div class="w-[50px] h-[50px] rounded-[12px] {{ $material['icon_bg'] }} flex items-center justify-center text-[22px] flex-shrink-0 group-hover:scale-105 transition-transform">
                {{ $material['icon'] }}
            </div>
            
            <div class="flex-1 w-full">
                <div class="flex items-center gap-3 mb-1">
                    <h3 class="text-[16px] font-bold text-[#1A1714] font-['Syne',sans-serif]">{{ $material['title'] }}</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold tracking-wide {{ $material['status_bg'] }} {{ $material['status_color'] }}">
                        {{ $material['status'] }}
                    </span>
                </div>
                <p class="text-[#7C7167] text-[13px]">{{ $material['date'] }} <span class="mx-1.5">&middot;</span> {{ $material['details'] }}</p>
            </div>
            
            <div class="flex items-center gap-3 w-full md:w-auto mt-3 md:mt-0 pt-4 md:pt-0 border-t md:border-t-0 border-[#E2DDD8]">
                
                @if($material['status'] == 'Reviewed')
                    <a href="/reviewer" class="flex-1 md:flex-none text-center bg-white border border-[#E2DDD8] hover:bg-[#F9F8F6] text-[#1A1714] px-4 py-2 rounded-[8px] font-semibold text-[13px] transition-colors shadow-sm">
                        View Concepts
                    </a>
                    <a href="/quizzes" class="flex-1 md:flex-none text-center bg-[#6646E5] hover:bg-[#5538D4] text-white px-4 py-2 rounded-[8px] font-semibold text-[13px] transition-colors shadow-sm">
                        Take Quiz
                    </a>
                @elseif($material['status'] == 'Processing')
                    <button disabled class="flex-1 md:flex-none bg-[#F3F4F6] text-[#9CA3AF] px-4 py-2 rounded-[8px] font-semibold text-[13px] cursor-not-allowed">
                        Extracting...
                    </button>
                @else
                    <a href="/upload" class="flex-1 md:flex-none text-center bg-[#6646E5] hover:bg-[#5538D4] text-white px-4 py-2 rounded-[8px] font-semibold text-[13px] transition-colors shadow-sm">
                        Start Processing
                    </a>
                @endif
                
                <button class="w-[36px] h-[36px] flex items-center justify-center text-[#A39D98] hover:text-[#EF4444] hover:bg-[#FEE2E2] rounded-[8px] transition-colors flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>

            </div>
        </div>

        @empty
        <div class="flex flex-col items-center justify-center py-16 px-4 text-center border-2 border-dashed border-[#E2DDD8] rounded-[16px] bg-[#FAF9F7] w-full">
            <div class="w-16 h-16 bg-[#E0D8FC] rounded-[16px] flex items-center justify-center text-[28px] mb-5 shadow-sm transform -rotate-3 hover:rotate-0 transition-transform">
                ✨
            </div>
            <h3 class="text-[#1A1714] font-bold text-[18px] md:text-[20px] mb-2 font-['Syne',sans-serif]">It's a little quiet here...</h3>
            <p class="text-[#7C7167] text-[14px] md:text-[15px] max-w-[300px] mb-6 leading-relaxed">
                Upload your first document or paste your notes to let the AI generate your study guide!
            </p>
            <a href="/upload" class="bg-[#1A1714] text-white px-6 py-3 rounded-[10px] font-semibold text-[14px] hover:bg-[#2E2B28] shadow-md transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Upload Material
            </a>
        </div>
        @endforelse
        
    </div>

    <div class="mt-8 flex justify-center">
        <div class="flex gap-1">
            <button class="w-8 h-8 rounded-[8px] border border-[#E2DDD8] flex items-center justify-center text-[#A39D98] hover:text-[#1A1714] hover:bg-[#F9F8F6] transition-colors shadow-sm">&larr;</button>
            <button class="w-8 h-8 rounded-[8px] bg-[#6646E5] text-white flex items-center justify-center font-bold text-[13px] shadow-sm">1</button>
            <button class="w-8 h-8 rounded-[8px] border border-[#E2DDD8] bg-white flex items-center justify-center text-[#1A1714] hover:bg-[#F9F8F6] transition-colors shadow-sm font-semibold text-[13px]">2</button>
            <button class="w-8 h-8 rounded-[8px] border border-[#E2DDD8] bg-white flex items-center justify-center text-[#1A1714] hover:bg-[#F9F8F6] transition-colors shadow-sm font-semibold text-[13px]">3</button>
            <button class="w-8 h-8 rounded-[8px] border border-[#E2DDD8] flex items-center justify-center text-[#A39D98] hover:text-[#1A1714] hover:bg-[#F9F8F6] transition-colors shadow-sm">&rarr;</button>
        </div>
    </div>

</div>
@endsection