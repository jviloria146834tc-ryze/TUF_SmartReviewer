@extends('layouts.app')

@section('title', 'Materials Library')

@section('content')
<div x-data="materialsPage()" class="max-w-[1500px] mx-auto w-full pb-12 flex flex-col h-full">

    <!-- Progress Overlay (Sync with upload.blade.php) -->
    <div x-show="isProcessing" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-[#F0EDE8]/90 backdrop-blur-sm z-[100] flex flex-col items-center justify-center">
        
        <div class="w-full max-w-md bg-white p-10 rounded-[32px] shadow-2xl border border-[#E2DDD8] relative overflow-hidden">
            <h2 class="text-[26px] font-bold text-[#1A1714] mb-8 font-['Inter'] text-center tracking-tight">Processing Material</h2>
            
            <div class="space-y-8">
                <!-- Step 1: Analysis -->
                <div class="flex items-center gap-5 transition-all duration-500" :class="progressStage === 'uploading' ? 'opacity-100' : 'opacity-50'">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500"
                         :class="progressStage === 'uploading' ? 'bg-[#6646E5] text-white shadow-lg shadow-[#6646E5]/20' : 'bg-[#D4F5E3] text-[#166534]'">
                        <template x-if="progressStage === 'uploading'">
                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                        <template x-if="progressStage !== 'uploading'">
                            <x-heroicon-s-check class="w-6 h-6" />
                        </template>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-[16px] transition-colors" :class="progressStage === 'uploading' ? 'text-[#1A1714]' : 'text-[#7C7167]'">Phase 1: Analysis</span>
                        <span class="text-[13px] font-medium text-[#7C7167]">Re-extracting content...</span>
                    </div>
                </div>

                <!-- Step 2: Finalizing -->
                <div class="flex items-center gap-5 transition-all duration-500" :class="progressStage === 'extracting' ? 'opacity-100' : 'opacity-40'">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500"
                         :class="progressStage === 'extracting' ? 'bg-[#6646E5] text-white shadow-lg shadow-[#6646E5]/20' : (progressPercent === 100 ? 'bg-[#D4F5E3] text-[#166534]' : 'bg-gray-100 text-gray-400')">
                        <template x-if="progressStage === 'extracting'">
                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                        <template x-if="progressStage !== 'extracting'">
                            <template x-if="progressPercent === 100">
                                <x-heroicon-s-check class="w-6 h-6" />
                            </template>
                            <template x-if="progressPercent !== 100">
                                <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                            </template>
                        </template>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-[16px] transition-colors" :class="progressStage === 'extracting' ? 'text-[#1A1714]' : 'text-[#7C7167]'">Phase 2: Finalizing</span>
                        <span class="text-[13px] font-medium text-[#7C7167]">AI is rebuilding your study guide...</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-10 flex flex-col gap-2">
                <div class="flex justify-between items-end">
                    <span class="text-[12px] font-black uppercase tracking-widest text-[#6646E5]" x-text="progressStage === 'uploading' ? 'Analyzing' : 'Almost Ready'"></span>
                    <span class="text-[20px] font-black text-[#1A1714]" x-text="progressPercent + '%'"></span>
                </div>
                <div class="w-full bg-[#F0EDE8] h-3 rounded-full overflow-hidden p-0.5">
                    <div class="h-full bg-[#6646E5] rounded-full transition-all duration-500 ease-out shadow-[0_0_10px_rgba(102,70,229,0.4)]"
                         :style="'width: ' + progressPercent + '%'"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8 animate-glide-up">
        <div>
            <a href="{{ route('dashboard') }}" class="text-[#7C7167] text-[13px] font-semibold hover:text-[#1A1714] flex items-center gap-1 mb-2 transition-colors w-max">
                &larr; Back to Dashboard
            </a>
            <h1 class="text-[33px] font-bold text-[#1A1714] tracking-tight font-['Special_Gothic_Expanded_One',sans-serif]">
                Study Materials
            </h1>
        </div>
        
        <a href="/upload" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-[12px] font-bold text-[15px] transition-all shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/40 hover:-translate-y-0.5 flex items-center gap-2 shrink-0">
            <x-heroicon-o-cloud-arrow-up class="w-5 h-5" />
            Upload Material
        </a>
    </div>

    <form method="GET" action="{{ route('materials.index') }}" class="flex flex-col sm:flex-row gap-4 mb-6 animate-glide-up delay-100">
        <div class="relative flex-1 max-w-[400px]">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-[#A39D98]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search materials..." class="w-full bg-white border border-[#E2DDD8] rounded-[12px] pl-10 pr-4 py-2.5 text-[14px] text-[#1A1714] placeholder-[#A39D98] focus:outline-none focus:border-[#6646E5] focus:ring-1 focus:ring-[#6646E5] transition-all shadow-sm">
        </div>
        
        <div class="relative w-full sm:w-auto">
            <select name="status" onchange="this.form.submit()" class="w-full sm:w-[160px] bg-[#1A1714] border border-[#2E2B28] rounded-[12px] pl-4 pr-10 py-2.5 text-[14px] font-medium text-white focus:outline-none focus:border-[#6646E5] focus:ring-1 focus:ring-[#6646E5] transition-all shadow-sm cursor-pointer appearance-none">
                <option value="">All Statuses</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <x-heroicon-o-chevron-down class="w-4 h-4 text-[#9E9690]" />
            </div>
        </div>
        
        <button type="submit" class="hidden">Search</button>
    </form>

    <div class="flex flex-col gap-4 animate-glide-up delay-200" x-data>

        @forelse($materials as $material)
        @php
            $extension = pathinfo($material->file_path ?? $material->original_path ?? '', PATHINFO_EXTENSION);
            $iconName = match(strtolower($extension)) {
                'pdf', 'docx' => 'heroicon-s-document-text',
                'jpg', 'jpeg', 'png' => 'heroicon-s-photo',
                default => 'heroicon-s-document',
            };
            $iconColor = match(strtolower($extension)) {
                'pdf' => 'text-[#E11D48]', // Red for PDF
                'docx' => 'text-[#2563EB]', // Blue for DOCX
                'jpg', 'jpeg', 'png' => 'text-[#2563EB]', // Blue for Images
                default => 'text-[#4B5563]', // Gray for others
            };
            $iconBg = match(strtolower($extension)) {
                'pdf' => 'bg-[#FFE4E6]',
                'docx' => 'bg-[#DBEAFE]',
                'jpg', 'jpeg', 'png' => 'bg-[#DBEAFE]',
                default => 'bg-[#F3F4F6]',
            };
            $ext = strtolower($extension);
            $type = $ext == 'pdf' ? 'pdf' : (in_array($ext, ['jpg', 'jpeg', 'png']) ? 'image' : 'other');
            $url = $material->public_url ?? '#';
        @endphp
        <div @click="$dispatch('open-preview', { url: '{{ $url }}', type: '{{ $type }}' })" class="bg-white border border-[#E2DDD8] rounded-[16px] p-5 flex flex-col md:flex-row items-start md:items-center gap-5 shadow-sm hover:border-[#6646E5] hover:shadow-md transition-all group cursor-pointer">
            
            <div class="w-[50px] h-[50px] rounded-[12px] {{ $iconBg }} flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                <x-dynamic-component :component="$iconName" class="w-7 h-7 {{ $iconColor }}" />
            </div>
            
            <div class="flex-1 w-full">
                <div class="flex items-center gap-3 mb-1">
                    <h3 class="text-[16px] font-bold text-[#1A1714] font-['Syne',sans-serif] group-hover:text-[#6646E5] transition-colors">{{ $material->title }}</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold tracking-wide uppercase
                        {{ $material->status == 'completed' ? 'bg-[#D4F5E3] text-[#166534]' : 'bg-[#FEE2E2] text-[#EF4444]' }}">
                        {{ $material->status }}
                    </span>
                </div>
                <p class="text-[#7C7167] text-[13px] font-medium leading-relaxed">
                    @if($material->source_name)
                        {{ $material->source_name }} <span class="mx-1.5">•</span> 
                    @endif
                    Uploaded {{ $material->created_at->format('M d, Y') }} <span class="mx-1.5">•</span> {{ $material->created_at->format('h:i A') }}
                </p>
            </div>
            
            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full md:w-auto mt-4 md:mt-0 pt-4 md:pt-0 border-t md:border-t-0 border-[#E2DDD8]">
                
                @if($material->status == 'completed')
                    <a href="{{ route('reviewer', $material->id) }}" @click.stop class="flex-1 sm:flex-none text-center bg-white border border-[#E2DDD8] hover:bg-[#F9F8F6] text-[#1A1714] px-4 py-2 rounded-[8px] font-semibold text-[13px] transition-colors shadow-sm">
                        Review Material
                    </a>
                @elseif($material->status == 'failed' || $material->status == 'pending')
                    <button type="button" @click.stop="reprocessMaterial({{ $material->id }})" class="flex-1 sm:flex-none text-center bg-[#D97706] hover:bg-[#B45309] text-white px-4 py-2 rounded-[8px] font-semibold text-[13px] transition-colors shadow-sm flex items-center justify-center gap-1.5">
                        <x-heroicon-o-arrow-path class="w-4 h-4" />
                        Retry
                    </button>
                @endif
                
                <div class="flex items-center gap-2 w-full sm:w-auto mt-2 sm:mt-0">
                    <button type="button" @click.stop="$dispatch('open-preview', { url: '{{ $url }}', type: '{{ $type }}' })" class="w-[36px] h-[36px] flex items-center justify-center text-[#7C7167] hover:text-[#6646E5] hover:bg-[#E0D8FC] rounded-[8px] transition-colors flex-shrink-0 shadow-sm border border-[#E2DDD8] hover:border-[#6646E5] bg-white">
                        <x-heroicon-o-eye class="w-4 h-4" />
                    </button>
                    <form action="{{ route('materials.destroy', $material->id) }}" method="POST" class="inline" @submit.stop onsubmit="return confirm('Are you sure you want to delete this material? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" @click.stop class="w-[36px] h-[36px] flex items-center justify-center text-[#A39D98] hover:text-[#EF4444] hover:bg-[#FEE2E2] rounded-[8px] transition-colors flex-shrink-0 shadow-sm border border-transparent hover:border-[#FCA5A5]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @empty
        <div class="flex-1 flex flex-col items-center justify-center py-20 text-center relative z-10 w-full">
            <div class="w-24 h-24 bg-[#E0D8FC] rounded-[32px] flex items-center justify-center text-[#6646E5] mb-8 shadow-inner transform rotate-3 hover:rotate-0 transition-transform">
                <x-heroicon-o-folder-open class="w-12 h-12" stroke-width="1.5" />
            </div>
            <h2 class="text-[32px] font-bold text-[#1A1714] mb-4 font-['Inter'] tracking-tight">It's a little quiet here...</h2>
            <p class="text-[#7C7167] text-[18px] max-w-md mb-10 leading-relaxed font-medium">
                Upload your first document or paste your notes to let the AI generate your study guide!
            </p>
            <a href="/upload" class="bg-[#1A1714] hover:bg-[#2E2B28] text-white px-10 py-4 rounded-[18px] font-bold text-[16px] transition-all shadow-xl hover:-translate-y-1 flex items-center gap-3 cursor-pointer">
                <x-heroicon-o-cloud-arrow-up class="w-6 h-6 text-[#A78BFA]" />
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

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    function materialsPage() {
        return {
            isProcessing: false,
            progressPercent: 0,
            progressStage: 'idle',
            progressInterval: null,

            async reprocessMaterial(materialId) {
                this.isProcessing = true;
                this.progressPercent = 0;
                this.progressStage = 'uploading';
                
                this.progressInterval = setInterval(() => {
                    if (this.progressPercent < 45) {
                        this.progressPercent += Math.floor(Math.random() * 3) + 1;
                    } else if (this.progressPercent < 85) {
                        this.progressStage = 'extracting';
                        this.progressPercent += 1;
                    } else if (this.progressPercent < 98) {
                        this.progressPercent += 0.5;
                    }
                }, 400);

                try {
                    const response = await axios.post(`/materials/${materialId}/reprocess`, {}, {
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    
                    if (response.data.success) {
                        this.progressPercent = 100;
                        setTimeout(() => {
                            window.location.href = `/reviewer/${response.data.material_id}`;
                        }, 500);
                    }
                } catch (error) {
                    clearInterval(this.progressInterval);
                    this.isProcessing = false;
                    alert(error.response?.data?.error || 'An error occurred while reprocessing.');
                }
            }
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection