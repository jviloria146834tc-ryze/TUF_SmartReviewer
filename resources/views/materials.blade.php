@extends('layouts.app')

@section('content')
<div class="max-w-[1500px] mx-auto w-full pb-12 flex flex-col h-full">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8">
        <div>
            <a href="{{ route('dashboard') }}" class="text-[#7C7167] text-[13px] font-semibold hover:text-[#1A1714] flex items-center gap-1 mb-2 transition-colors w-max">
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

    <form method="GET" action="{{ route('materials.index') }}" class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="relative flex-1 max-w-[400px]">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-[#A39D98]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search materials..." class="w-full border border-[#E2DDD8] rounded-[12px] pl-10 pr-4 py-2.5 text-[14px] text-[#1A1714] focus:outline-none focus:border-[#6646E5] focus:ring-1 focus:ring-[#6646E5] transition-all shadow-sm">
        </div>
        
        <div class="relative w-full sm:w-auto">
            <select name="status" onchange="this.form.submit()" class="w-full sm:w-[160px] border border-[#E2DDD8] rounded-[12px] pl-4 pr-10 py-2.5 text-[14px] font-medium text-[#1A1714] focus:outline-none focus:border-[#6646E5] focus:ring-1 focus:ring-[#6646E5] transition-all shadow-sm bg-white cursor-pointer appearance-none">
                <option value="">All Statuses</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <x-heroicon-o-chevron-down class="w-4 h-4 text-[#7C7167]" />
            </div>
        </div>
        
        <button type="submit" class="hidden">Search</button>
    </form>

    <div class="flex flex-col gap-4">

        @forelse($materials as $material)
        @php
            $extension = pathinfo($material->original_path, PATHINFO_EXTENSION);
            $iconName = match(strtolower($extension)) {
                'pdf' => 'heroicon-s-document-text',
                'jpg', 'jpeg', 'png' => 'heroicon-s-photo',
                default => 'heroicon-s-document',
            };
            $iconColor = match(strtolower($extension)) {
                'pdf' => 'text-[#E11D48]', // Red for PDF
                'jpg', 'jpeg', 'png' => 'text-[#2563EB]', // Blue for Images
                default => 'text-[#4B5563]', // Gray for others
            };
            $iconBg = match(strtolower($extension)) {
                'pdf' => 'bg-[#FFE4E6]',
                'jpg', 'jpeg', 'png' => 'bg-[#DBEAFE]',
                default => 'bg-[#F3F4F6]',
            };
        @endphp
        <div class="bg-white border border-[#E2DDD8] rounded-[16px] p-5 flex flex-col md:flex-row items-start md:items-center gap-5 shadow-sm hover:border-[#6646E5] hover:shadow-md transition-all group">
            
            <div class="w-[50px] h-[50px] rounded-[12px] {{ $iconBg }} flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                <x-dynamic-component :component="$iconName" class="w-7 h-7 {{ $iconColor }}" />
            </div>
            
            <div class="flex-1 w-full">
                <div class="flex items-center gap-3 mb-1">
                    <h3 class="text-[16px] font-bold text-[#1A1714] font-['Syne',sans-serif]">{{ $material->title }}</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold tracking-wide 
                        {{ $material->status == 'completed' ? 'bg-[#D4F5E3] text-[#166534]' : ($material->status == 'processing' ? 'bg-[#FEF3C7] text-[#92400E]' : 'bg-gray-100 text-gray-600') }}">
                        {{ ucfirst($material->status) }}
                    </span>
                </div>
                <p class="text-[#7C7167] text-[13px]">Uploaded {{ $material->created_at->format('M d, Y') }} <span class="mx-1.5">&middot;</span> {{ $material->quizzes()->count() }} quizzes generated</p>
            </div>
            
            <div class="flex items-center gap-3 w-full md:w-auto mt-3 md:mt-0 pt-4 md:pt-0 border-t md:border-t-0 border-[#E2DDD8]">
                
                @if($material->status == 'completed')
                    <a href="{{ route('reviewer', $material->id) }}" class="flex-1 md:flex-none text-center bg-white border border-[#E2DDD8] hover:bg-[#F9F8F6] text-[#1A1714] px-4 py-2 rounded-[8px] font-semibold text-[13px] transition-colors shadow-sm">
                        View Concepts
                    </a>
                    <a href="{{ $material->quizzes->first() ? route('quizzes.session', $material->quizzes->first()->id) : route('quizzes.index') }}" class="flex-1 md:flex-none text-center bg-[#6646E5] hover:bg-[#5538D4] text-white px-4 py-2 rounded-[8px] font-semibold text-[13px] transition-colors shadow-sm">
                        Take Quiz
                    </a>
                @elseif($material->status == 'processing')
                    <button disabled class="flex-1 md:flex-none bg-[#F3F4F6] text-[#9CA3AF] px-4 py-2 rounded-[8px] font-semibold text-[13px] cursor-not-allowed">
                        Extracting...
                    </button>
                @else
                    <button disabled class="flex-1 md:flex-none bg-[#F3F4F6] text-[#9CA3AF] px-4 py-2 rounded-[8px] font-semibold text-[13px] cursor-not-allowed">
                        Pending...
                    </button>
                @endif
                
                <form action="{{ route('materials.destroy', $material->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this material? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-[36px] h-[36px] flex items-center justify-center text-[#A39D98] hover:text-[#EF4444] hover:bg-[#FEE2E2] rounded-[8px] transition-colors flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </form>

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

    @if($materials instanceof \Illuminate\Pagination\LengthAwarePaginator && $materials->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $materials->links() }}
    </div>
    @endif

</div>
@endsection