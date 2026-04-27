@extends('layouts.app')

@section('content')
<div class="max-w-[1500px] mx-auto w-full lg:h-full flex flex-col pb-4">

    <div class="mb-6 flex-shrink-0">
        <a href="/dashboard" class="text-[#7C7167] text-[13px] font-semibold hover:text-[#1A1714] flex items-center gap-1 mb-2 transition-colors w-max">
            &larr; Back to Dashboard
        </a>
        <h1 class="text-[28px] md:text-[33px] font-bold text-[#1A1714] tracking-tight font-['Inter']">Upload Material</h1>
    </div>

    <form method="POST" action="/upload" enctype="multipart/form-data" class="flex flex-col gap-6 lg:flex-1 lg:min-h-0">
        @csrf

        <div class="flex flex-col lg:flex-row gap-6 lg:flex-[4] lg:min-h-0">
            
            <div class="w-full lg:flex-1 bg-transparent border-2 border-dashed border-[#E2DDD8] hover:border-[#6646E5] hover:bg-[#F9F8F6] rounded-[18px] p-6 flex flex-col items-center justify-center min-h-[300px] lg:min-h-0 lg:h-full transition-all cursor-pointer relative group">
                
                <input type="file" id="document_upload" name="document_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".pdf,.docx,.jpg,.jpeg,.png">
                <div class="w-[75px] h-[75px] bg-[#E0D8FC] text-[#6646E5] rounded-[22px] flex items-center justify-center mb-6 group-hover:scale-105 transition-transform flex-shrink-0">
                    <x-heroicon-o-folder class="w-10 h-10" stroke-width="2" />
                </div>

                <h2 class="text-[18px] md:text-[22px] font-bold text-[#1A1714] mb-2 font-['Inter'] text-center">Drag & Drop your file here</h2>
                <p class="text-[#7C7167] text-[13px] md:text-[15px] mb-6 text-center">Supports PDF, DOCX, JPG, PNG — up to 25MB</p>
                
                <div class="bg-[#1A1714] text-white font-semibold text-[14px] md:text-[15px] px-8 py-3 rounded-[12px] shadow-md group-hover:bg-[#2E2B28] transition-colors pointer-events-none flex-shrink-0 text-center">
                    Browse Files
                </div>
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
            
            <textarea name="document_text" placeholder="Paste lecture notes, textbook excerpts, or any study content here..." 
                class="w-full h-full flex-1 resize-none text-[#1A1714] text-[15px] placeholder-[#A39D98] bg-transparent border-none outline-none focus:ring-0 pb-16"></textarea>
            
            <div class="absolute bottom-6 left-6 right-6 md:left-auto">
                <button type="submit" class="w-full md:w-auto bg-[#6646E5] hover:bg-[#5538D4] text-white font-semibold text-[15px] px-6 py-3 rounded-[12px] shadow-md transition-colors flex justify-center items-center gap-2">
                    Generate Review &rarr;
                </button>
            </div>
        </div>

    </form>
</div>
@endsection
