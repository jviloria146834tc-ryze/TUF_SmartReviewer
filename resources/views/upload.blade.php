@extends('layouts.app')

@section('title', 'Upload Material')

@section('content')
<div x-data="uploadForm()" class="max-w-[1500px] mx-auto w-full lg:h-full flex flex-col pb-4 relative">

    <!-- Progress Overlay -->
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
                        <span class="text-[13px] font-medium text-[#7C7167]">Uploading & extracting content...</span>
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
                        <span class="text-[13px] font-medium text-[#7C7167]">AI is generating your study guide...</span>
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
                 :class="{'border-[#6646E5] bg-[#F9F8F6]': isDragging || fileNames, 'border-[#E2DDD8] bg-white': !isDragging && !fileNames}"
                 class="w-full lg:flex-1 bg-white border-2 border-dashed hover:border-[#6646E5] hover:bg-[#F9F8F6] rounded-[18px] p-6 flex flex-col items-center justify-center min-h-[300px] lg:min-h-0 lg:h-full transition-all cursor-pointer relative group">
                
                <input type="file" id="file" name="files[]" multiple @change="handleFileSelect" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".pdf,.docx,.jpg,.jpeg,.png">
                
                <div class="w-[75px] h-[75px] bg-[#E0D8FC] text-[#6646E5] rounded-[22px] flex items-center justify-center mb-6 group-hover:scale-105 transition-transform flex-shrink-0">
                    <div x-show="!fileNames">
                        <x-heroicon-o-folder class="w-10 h-10" stroke-width="2" />
                    </div>
                    <div x-show="fileNames" x-cloak>
                        <x-heroicon-o-document-duplicate class="w-10 h-10 text-green-600" stroke-width="2" />
                    </div>
                </div>

                <h2 class="text-[18px] md:text-[22px] font-bold text-[#1A1714] mb-2 font-['Inter'] text-center" x-text="fileNames ? 'Files selected!' : 'Drag & Drop your files here'"></h2>
                <p class="text-[#7C7167] text-[13px] md:text-[15px] mb-6 text-center" x-text="fileNames ? fileNames : 'Supports PDF, DOCX, JPG, PNG (Multiple allowed) — up to 30MB total'"></p>
                
                <div class="bg-[#1A1714] text-white font-semibold text-[14px] md:text-[15px] px-8 py-3 rounded-[12px] shadow-md group-hover:bg-[#2E2B28] transition-colors pointer-events-none flex-shrink-0 text-center" x-text="fileNames ? 'Change Files' : 'Browse Files'"></div>
            </div>

            <div class="w-full lg:w-[300px] bg-[#FAF9F7] border border-[#E2DDD8] rounded-[18px] p-6 flex flex-col lg:h-full lg:overflow-y-auto hover:border-[#6646E5]/40 transition-all duration-300">
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

        <div class="bg-white border border-[#E2DDD8] rounded-[18px] p-6 shadow-sm flex flex-col relative min-h-[250px] lg:flex-[3] lg:min-h-0 hover:border-[#6646E5]/40 transition-all duration-300">
            <h3 class="text-[#1A1714] font-semibold text-[16px] mb-3 flex-shrink-0">Paste your study material here</h3>
            
            <textarea x-model="textContent" name="content" placeholder="Paste lecture notes, textbook excerpts, or any study content here..." 
                class="w-full h-full flex-1 resize-none text-[#1A1714] text-[15px] placeholder-[#A39D98] bg-transparent border-none outline-none focus:ring-0 pb-16"></textarea>
            
            <div class="absolute bottom-6 left-6 right-6 md:left-auto">
                <button type="submit" :disabled="!hasContent || isProcessing" class="w-full md:w-auto bg-[#6646E5] hover:bg-[#5538D4] text-white font-semibold text-[15px] px-8 py-3 rounded-[12px] shadow-md transition-colors flex justify-center items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!isProcessing">Process Material &rarr;</span>
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
            files: [],
            fileNames: '',
            textContent: '',
            errorMessage: '',
            progressPercent: 0,
            progressStage: 'idle',
            progressInterval: null,
            
            get hasContent() {
                return this.files.length > 0 || this.textContent.trim().length > 0;
            },

            handleFileSelect(e) {
                this.files = Array.from(e.target.files);
                this.updateFileNames();
            },
            
            handleDrop(e) {
                this.isDragging = false;
                this.files = Array.from(e.dataTransfer.files);
                document.getElementById('file').files = e.dataTransfer.files;
                this.updateFileNames();
            },

            updateFileNames() {
                if (this.files.length === 1) {
                    this.fileNames = this.files[0].name;
                } else if (this.files.length > 1) {
                    this.fileNames = this.files.length + ' files selected';
                } else {
                    this.fileNames = '';
                }
            },

            async submitForm() {
                this.isProcessing = true;
                this.errorMessage = '';
                this.progressPercent = 0;
                this.progressStage = 'uploading';
                
                // Smart Progress Logic
                this.progressInterval = setInterval(() => {
                    if (this.progressPercent < 45) {
                        this.progressPercent += Math.floor(Math.random() * 3) + 1;
                    } else if (this.progressPercent < 85) {
                        this.progressStage = 'extracting';
                        this.progressPercent += 1;
                    } else if (this.progressPercent < 98) {
                        this.progressPercent += 0.5; // Slow down near the end
                    }
                }, 400);

                const formData = new FormData();
                this.files.forEach((file, index) => {
                    formData.append(`files[${index}]`, file);
                });
                if (this.textContent.trim()) {
                    formData.append('content', this.textContent);
                }

                try {
                    const response = await axios.post('{{ route('materials.store') }}', formData, {
                        timeout: 120000, 
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
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
                    this.errorMessage = error.response?.data?.error || 'An unexpected error occurred during processing.';
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
