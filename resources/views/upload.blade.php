@extends('layouts.app')

@section('content')
<div x-data="uploadForm()" class="max-w-[1500px] mx-auto w-full lg:h-full flex flex-col pb-4 relative">

    <!-- Loading Overlay -->
    <div x-show="isProcessing" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-[#F0EDE8]/80 backdrop-blur-sm z-[100] flex flex-col items-center justify-center">
        
        <div class="w-24 h-24 mb-8 relative">
            <div class="absolute inset-0 border-4 border-[#6646E5]/20 rounded-full"></div>
            <div class="absolute inset-0 border-4 border-[#6646E5] border-t-transparent rounded-full animate-spin"></div>
        </div>
        
        <h2 class="text-[24px] font-bold text-[#1A1714] mb-2 font-['Inter']">Processing Material</h2>
        <p class="text-[#7C7167] text-[15px] animate-pulse">AI is extracting and analyzing your content...</p>
    </div>

    <div class="mb-6 flex-shrink-0">
        <a href="{{ route('dashboard') }}" class="text-[#7C7167] text-[13px] font-semibold hover:text-[#1A1714] flex items-center gap-1 mb-2 transition-colors w-max">
            &larr; Back to Dashboard
        </a>
        <h1 class="text-[28px] md:text-[33px] font-bold text-[#1A1714] tracking-tight font-['Inter']">Upload Material</h1>
    </div>

    <!-- Error Message -->
    <template x-if="errorMessage">
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700" x-text="errorMessage"></p>
                </div>
            </div>
        </div>
    </template>

    <form @submit.prevent="submitForm" enctype="multipart/form-data" class="flex flex-col gap-6 lg:flex-1 lg:min-h-0">
        @csrf

        <div class="flex flex-col lg:flex-row gap-6 lg:flex-[4] lg:min-h-0">
            
            <div id="drop_zone" 
                 @dragover.prevent="isDragging = true" 
                 @dragleave.prevent="isDragging = false"
                 @drop.prevent="handleDrop"
                 :class="{'border-[#6646E5] bg-[#F9F8F6]': isDragging || fileName, 'border-[#E2DDD8]': !isDragging && !fileName}"
                 class="w-full lg:flex-1 bg-transparent border-2 border-dashed hover:border-[#6646E5] hover:bg-[#F9F8F6] rounded-[18px] p-6 flex flex-col items-center justify-center min-h-[300px] lg:min-h-0 lg:h-full transition-all cursor-pointer relative group">
                
                <input type="file" id="file" name="file" @change="handleFileSelect" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".pdf,.docx,.jpg,.jpeg,.png">
                
                <div class="w-[75px] h-[75px] bg-[#E0D8FC] text-[#6646E5] rounded-[22px] flex items-center justify-center mb-6 group-hover:scale-105 transition-transform flex-shrink-0">
                    <div x-show="!fileName">
                        <x-heroicon-o-folder class="w-10 h-10" stroke-width="2" />
                    </div>
                    <div x-show="fileName" x-cloak>
                        <x-heroicon-o-check-circle class="w-10 h-10 text-green-600" stroke-width="2" />
                    </div>
                </div>

                <h2 class="text-[18px] md:text-[22px] font-bold text-[#1A1714] mb-2 font-['Inter'] text-center" x-text="fileName ? 'File selected!' : 'Drag & Drop your file here'"></h2>
                <p class="text-[#7C7167] text-[13px] md:text-[15px] mb-6 text-center" x-text="fileName ? fileName : 'Supports PDF, DOCX, JPG, PNG — up to 30MB'"></p>
                
                <div class="bg-[#1A1714] text-white font-semibold text-[14px] md:text-[15px] px-8 py-3 rounded-[12px] shadow-md group-hover:bg-[#2E2B28] transition-colors pointer-events-none flex-shrink-0 text-center" x-text="fileName ? 'Change File' : 'Browse Files'"></div>
            </div>

            <div class="w-full lg:w-[300px] bg-[#FAF9F7] border border-[#E2DDD8] rounded-[18px] p-6 flex flex-col lg:h-full lg:overflow-y-auto">
                <h3 class="text-[#1A1714] font-semibold text-[16px] mb-5 flex items-center gap-2 flex-shrink-0">
                    <x-heroicon-o-light-bulb class="w-5 h-5 text-orange-500" />
                    Tips for best results
                </h3>
                
                <ul class="flex flex-col gap-5 text-[#7C7167] text-[13px] leading-relaxed">
                    <li class="flex items-start">
                        <span class="mr-3 text-[#1A1714] font-bold">•</span>
                        <span>Use high-resolution images (300 DPI or above) for OCR accuracy.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-3 text-[#1A1714] font-bold">•</span>
                        <span>Ensure documents have clear, readable text without heavy formatting.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-3 text-[#1A1714] font-bold">•</span>
                        <span>Longer materials will generate more detailed concept summaries.</span>
                    </li>
                    <li class="flex items-start">
                        <span class="mr-3 text-[#1A1714] font-bold">•</span>
                        <span>If uploading photos of notes, ensure good lighting and avoid cursive handwriting.</span>
                    </li>
                </ul>
            </div>

        </div>

        <div class="flex items-center w-full flex-shrink-0 overflow-hidden">
            <div class="flex-1 border-t border-[#E2DDD8]"></div>
            <span class="px-2 md:px-6 text-[#7C7167] text-[11px] md:text-[14px] font-medium tracking-wide uppercase text-center">OR PASTE TEXT DIRECTLY</span>
            <div class="flex-1 border-t border-[#E2DDD8]"></div>
        </div>

        <div class="bg-white border border-[#E2DDD8] rounded-[18px] p-6 shadow-sm flex flex-col relative min-h-[250px] lg:flex-[3] lg:min-h-0">
            <h3 class="text-[#1A1714] font-semibold text-[16px] mb-3 flex-shrink-0">Paste your study material here</h3>
            
            <textarea x-model="textContent" name="content" placeholder="Paste lecture notes, textbook excerpts, or any study content here..." 
                class="w-full h-full flex-1 resize-none text-[#1A1714] text-[15px] placeholder-[#A39D98] bg-transparent border-none outline-none focus:ring-0 pb-16"></textarea>
            
            <div class="absolute bottom-6 left-6 right-6 md:left-auto">
                <button type="submit" :disabled="isProcessing" class="w-full md:w-auto bg-[#6646E5] hover:bg-[#5538D4] text-white font-semibold text-[15px] px-6 py-3 rounded-[12px] shadow-md transition-colors flex justify-center items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!isProcessing">Generate Review &rarr;</span>
                    <span x-show="isProcessing" x-cloak>Processing...</span>
                </button>
            </div>
        </div>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    function uploadForm() {
        return {
            isProcessing: false,
            isDragging: false,
            fileName: '',
            textContent: '',
            errorMessage: '',
            
            handleFileSelect(e) {
                const file = e.target.files[0];
                if (file) {
                    this.fileName = file.name;
                }
            },
            
            handleDrop(e) {
                this.isDragging = false;
                const file = e.dataTransfer.files[0];
                if (file) {
                    this.fileName = file.name;
                    document.getElementById('file').files = e.dataTransfer.files;
                }
            },
            
            async submitForm() {
                const fileInput = document.getElementById('file');
                if (!fileInput.files.length && !this.textContent.trim()) {
                    this.errorMessage = 'Please select a file or paste some content.';
                    return;
                }

                this.isProcessing = true;
                this.errorMessage = '';
                
                const formData = new FormData();
                if (fileInput.files.length) {
                    formData.append('file', fileInput.files[0]);
                }
                if (this.textContent.trim()) {
                    formData.append('content', this.textContent);
                }

                try {
                    const response = await axios.post('{{ route('materials.store') }}', formData, {
                        timeout: 90000, // 90 seconds
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        }
                    });
                    
                    if (response.data.success) {
                        window.location.href = response.data.redirect;
                    }
                } catch (error) {
                    this.isProcessing = false;
                    
                    if (error.code === 'ECONNABORTED') {
                        this.errorMessage = 'The request timed out. The file might be too large or the AI is taking too long.';
                    } else if (error.response?.status === 500) {
                        this.errorMessage = 'A server-side error occurred. The AI might have had trouble reading this specific PDF. Try a different file or paste the text directly.';
                    } else {
                        this.errorMessage = error.response?.data?.error || 'An unexpected error occurred. Please check your connection and try again.';
                    }
                    
                    console.error('Upload error:', error);
                }
            }
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
