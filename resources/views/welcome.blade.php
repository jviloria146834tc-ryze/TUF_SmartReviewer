<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="overflow-y: overlay; scrollbar-gutter: stable;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmartReviewer | Study Smarter, Score Higher</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;600&family=Inter:wght@400;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#1A1714] w-full min-h-screen flex flex-col font-['Instrument_Sans'] text-white overflow-x-hidden selection:bg-[#6646E5] selection:text-white">

    <!-- Global Grid Background -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff0f_1px,transparent_1px),linear-gradient(to_bottom,#ffffff0f_1px,transparent_1px)] bg-[size:48px_48px]"></div>
    </div>

    <!-- Header -->
    <header x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)" :class="scrolled ? 'bg-[#1A1714]/80 backdrop-blur-md border-b border-[#2E2B28]' : 'bg-transparent border-transparent'" class="fixed top-0 w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Exact Logo Match from Auth -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 relative z-10 group">
                <div class="w-12 h-12 bg-[#6646E5] rounded-[14px] flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300">
                    <x-logo class="w-7 h-7 text-white" />
                </div>
                <span class="text-[26px] font-normal font-['Inter'] text-white tracking-tight">Smart<span style="font-weight: bold;">Reviewer</span></span>
            </a>
            
            <nav class="hidden md:flex gap-8">
                <a href="#features" class="text-[15px] font-medium text-[#9E9690] hover:text-[#E0D8FC] hover:-translate-y-0.5 transition-all">Features</a>
                <a href="#how-it-works" class="text-[15px] font-medium text-[#9E9690] hover:text-[#E0D8FC] hover:-translate-y-0.5 transition-all">How it Works</a>
            </nav>

            <div class="flex items-center gap-4 sm:gap-5">
                <a href="{{ route('login') }}" class="text-[14px] sm:text-[15px] font-semibold text-white hover:text-[#E0D8FC] transition-colors">Sign in</a>
                <a href="{{ route('register') }}" class="hidden sm:inline-flex text-[15px] font-semibold bg-[#6646E5] hover:bg-[#5538D4] text-white px-5 py-2.5 rounded-[12px] transition-all shadow-[0_0_15px_rgba(102,70,229,0.3)] hover:shadow-[0_0_25px_rgba(102,70,229,0.5)] hover:-translate-y-0.5">Get Started</a>
            </div>
        </div>
    </header>

    <main class="flex-1 flex flex-col pt-20 relative z-10">
        
        <!-- Hero Section -->
        <section class="flex-1 flex flex-col items-center justify-center text-center px-4 sm:px-6 lg:px-8 pt-24 pb-20 relative">
            <!-- Hero Blobs -->
            <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-[#6646E5] rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-pulse" style="animation-duration: 4s; z-index: -1;"></div>
            <div class="absolute top-[40%] -right-20 w-[400px] h-[400px] bg-[#E0D8FC] rounded-full mix-blend-multiply filter blur-[128px] opacity-20 animate-pulse" style="animation-duration: 5s; z-index: -1;"></div>

            <div class="animate-glide-down inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#2E2B28]/80 text-[#E0D8FC] text-sm font-medium mb-8 border border-[#E0D8FC]/30 backdrop-blur-md shadow-[0_0_15px_rgba(224,216,252,0.1)] cursor-default hover:bg-[#3A3734] hover:scale-105 hover:border-[#E0D8FC]/60 hover:shadow-[0_0_25px_rgba(224,216,252,0.2)] transition-all duration-300">
                <span class="flex h-2 w-2 rounded-full bg-[#6646E5] animate-pulse"></span>
                AI-Powered Study Companion
            </div>
            
            <h1 class="animate-glide-down delay-100 text-5xl md:text-[80px] lg:text-[90px] font-bold font-['Inter'] leading-[1.05] tracking-tight mb-8">
                Study Smarter,<br>
                <span class="text-[#6646E5] relative inline-block">
                    Score Higher.
                </span>
            </h1>
            
            <p class="animate-glide-down delay-200 text-xl md:text-[22px] text-[#9E9690] font-['Instrument_Sans'] mb-12 max-w-[650px] mx-auto leading-relaxed">
                Transform your lecture notes into interactive quizzes, AI flashcards, and personalized progress paths — all in one platform.
            </p>
            
            <div class="animate-glide-up delay-300 flex flex-col sm:flex-row items-center justify-center gap-4 mb-20 w-full sm:w-auto">
                <a href="{{ route('register') }}" class="w-full sm:w-auto bg-[#6646E5] hover:bg-[#5538D4] text-white px-8 py-4 rounded-[14px] font-semibold text-lg transition-all shadow-[0_0_30px_rgba(102,70,229,0.3)] hover:shadow-[0_0_40px_rgba(102,70,229,0.5)] hover:-translate-y-1 flex items-center justify-center gap-2 group">
                    Start Studying for Free
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                <a href="#features" class="w-full sm:w-auto bg-[#2E2B28]/60 backdrop-blur-md border border-[#3A3734] hover:bg-[#3A3734] text-[#F0EDE8] px-8 py-4 rounded-[14px] font-semibold text-lg transition-all hover:-translate-y-1">
                    Explore Features
                </a>
            </div>

            <!-- Floating Tech Pills -->
            <div class="animate-glide-up delay-[400ms] flex flex-wrap justify-center gap-4 font-['Instrument_Sans'] z-20">
                <div class="bg-[#1A1714]/80 border border-emerald-500/30 text-emerald-400 px-5 py-2.5 rounded-full font-medium text-sm tracking-wide backdrop-blur-xl transform hover:scale-105 hover:-translate-y-1 hover:border-emerald-500/60 shadow-[0_0_15px_rgba(16,185,129,0.1)] hover:shadow-[0_0_20px_rgba(16,185,129,0.3)] transition-all cursor-default">
                    ✦ OCR & NLP Processing
                </div>
                <div class="bg-[#1A1714]/80 border border-[#E0D8FC]/30 text-[#E0D8FC] px-5 py-2.5 rounded-full font-medium text-sm tracking-wide backdrop-blur-xl transform hover:scale-105 hover:-translate-y-1 hover:border-[#E0D8FC]/60 shadow-[0_0_15px_rgba(224,216,252,0.15)] hover:shadow-[0_0_25px_rgba(224,216,252,0.3)] transition-all cursor-default">
                    ✦ AI Summaries & Concepts
                </div>
                <div class="bg-[#1A1714]/80 border border-amber-500/30 text-amber-400 px-5 py-2.5 rounded-full font-medium text-sm tracking-wide backdrop-blur-xl transform hover:scale-105 hover:-translate-y-1 hover:border-amber-500/60 shadow-[0_0_15px_rgba(245,158,11,0.1)] hover:shadow-[0_0_20px_rgba(245,158,11,0.3)] transition-all cursor-default">
                    ✦ Customizable Quizzes
                </div>
                <div class="bg-[#1A1714]/80 border border-blue-500/30 text-blue-400 px-5 py-2.5 rounded-full font-medium text-sm tracking-wide backdrop-blur-xl transform hover:scale-105 hover:-translate-y-1 hover:border-blue-500/60 shadow-[0_0_15px_rgba(59,130,246,0.1)] hover:shadow-[0_0_20px_rgba(59,130,246,0.3)] transition-all cursor-default">
                    ✦ Flashcards & Mastery
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-32 relative bg-[#1A1714] border-t border-[#2E2B28] overflow-hidden">
            <!-- Features Blobs (Smaller versions) -->
            <div class="absolute top-20 right-0 w-[250px] h-[250px] bg-[#6646E5] rounded-full mix-blend-multiply filter blur-[100px] opacity-20 animate-pulse" style="animation-duration: 6s; z-index: -1;"></div>
            <div class="absolute bottom-10 left-10 w-[200px] h-[200px] bg-[#E0D8FC] rounded-full mix-blend-multiply filter blur-[90px] opacity-15 animate-pulse" style="animation-duration: 7s; z-index: -1;"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-20">
                    <h2 class="text-[40px] md:text-[50px] font-bold font-['Inter'] mb-4 text-white">Supercharge your studies</h2>
                    <p class="text-[#9E9690] text-xl max-w-2xl mx-auto">The smartest tools combined into one powerful platform to help you learn faster and retain more.</p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Feature 1: OCR -->
                    <div class="bg-[#2E2B28]/40 backdrop-blur-lg border border-[#3A3734] p-8 rounded-[24px] hover:bg-[#3A3734]/80 hover:border-[#6646E5]/40 hover:-translate-y-3 transition-all duration-500 group shadow-lg">
                        <div class="w-14 h-14 bg-[#1A1714] border border-[#3A3734] group-hover:border-[#6646E5] text-[#E0D8FC] rounded-[16px] flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 shadow-md">
                            <svg class="w-7 h-7 text-emerald-400 group-hover:text-[#6646E5] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h3 class="text-[20px] font-bold font-['Inter'] mb-3 text-white group-hover:text-[#E0D8FC] transition-colors">Smart OCR</h3>
                        <p class="text-[#9E9690] text-[15px] leading-relaxed group-hover:text-white/80 transition-colors">Support for PDF, DOCX, PNG, JPG, and copy-pasted texts for instant digitization with high accuracy.</p>
                    </div>

                    <!-- Feature 2: AI Reviewer -->
                    <div class="bg-[#2E2B28]/40 backdrop-blur-lg border border-[#3A3734] p-8 rounded-[24px] hover:bg-[#3A3734]/80 hover:border-[#6646E5]/40 hover:-translate-y-3 transition-all duration-500 group shadow-lg">
                        <div class="w-14 h-14 bg-[#1A1714] border border-[#3A3734] group-hover:border-[#6646E5] text-[#E0D8FC] rounded-[16px] flex items-center justify-center mb-6 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-500 shadow-md">
                            <x-heroicon-o-book-open class="w-7 h-7 text-[#E0D8FC] group-hover:text-[#6646E5] transition-colors" />
                        </div>
                        <h3 class="text-[20px] font-bold font-['Inter'] mb-3 text-white group-hover:text-[#E0D8FC] transition-colors">AI Reviewer</h3>
                        <p class="text-[#9E9690] text-[15px] leading-relaxed group-hover:text-white/80 transition-colors">Generate comprehensive summaries and automatically extract key concepts from your materials for rapid review.</p>
                    </div>

                    <!-- Feature 3: Customizable Quizzes -->
                    <div class="bg-[#2E2B28]/40 backdrop-blur-lg border border-[#3A3734] p-8 rounded-[24px] hover:bg-[#3A3734]/80 hover:border-[#6646E5]/40 hover:-translate-y-3 transition-all duration-500 group shadow-lg">
                        <div class="w-14 h-14 bg-[#1A1714] border border-[#3A3734] group-hover:border-[#6646E5] text-[#E0D8FC] rounded-[16px] flex items-center justify-center mb-6 group-hover:scale-110 group-hover:-rotate-3 transition-all duration-500 shadow-md">
                            <x-heroicon-o-clipboard-document-list class="w-7 h-7 text-[#FBBF24] group-hover:text-[#6646E5] transition-colors" />
                        </div>
                        <h3 class="text-[20px] font-bold font-['Inter'] mb-3 text-white group-hover:text-[#E0D8FC] transition-colors">Customizable Quizzes</h3>
                        <p class="text-[#9E9690] text-[15px] leading-relaxed group-hover:text-white/80 transition-colors">Tailored assessments with up to 60 items, covering multiple-choice, true/false, and fill-in-the-blank formats.</p>
                    </div>

                    <!-- Feature 4: Active Recall -->
                    <div class="bg-[#2E2B28]/40 backdrop-blur-lg border border-[#3A3734] p-8 rounded-[24px] hover:bg-[#3A3734]/80 hover:border-[#6646E5]/40 hover:-translate-y-3 transition-all duration-500 group shadow-lg">
                        <div class="w-14 h-14 bg-[#1A1714] border border-[#3A3734] group-hover:border-[#6646E5] text-[#E0D8FC] rounded-[16px] flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 shadow-md">
                            <x-heroicon-o-rectangle-stack class="w-7 h-7 text-blue-400 group-hover:text-[#6646E5] transition-colors" />
                        </div>
                        <h3 class="text-[20px] font-bold font-['Inter'] mb-3 text-white group-hover:text-[#E0D8FC] transition-colors">Active Recall Cards</h3>
                        <p class="text-[#9E9690] text-[15px] leading-relaxed group-hover:text-white/80 transition-colors">AI-generated flashcards designed to help you master complex terms and concepts through active recall.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="py-32 relative bg-[#1A1714]/40 backdrop-blur-lg border-t border-[#2E2B28] overflow-hidden">
            <!-- Distinct Blobs for How it Works (Using Theme Colors) -->
            <div class="absolute top-0 right-[10%] w-[400px] h-[400px] bg-[#6646E5] rounded-full mix-blend-multiply filter blur-[150px] opacity-15 animate-pulse" style="animation-duration: 7s; z-index: -1;"></div>
            <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-[#E0D8FC] rounded-full mix-blend-multiply filter blur-[150px] opacity-10 animate-pulse" style="animation-duration: 5s; z-index: -1;"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-16">
                    <h2 class="text-[40px] md:text-[50px] font-bold font-['Inter'] mb-4 text-white">How it works</h2>
                    <p class="text-[#9E9690] text-xl max-w-2xl mx-auto">From messy notes to mastery in three simple steps.</p>
                </div>
                
                <div class="max-w-4xl mx-auto">
                    <!-- Window Tile Card -->
                    <div class="bg-[#2E2B28]/40 backdrop-blur-xl border border-[#3A3734] rounded-[24px] overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.5)] relative">
                        <!-- Mac-style Window Header -->
                        <div class="bg-[#1A1714]/80 border-b border-[#3A3734] px-6 py-4 flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                            <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                            <div class="ml-4 text-[#9E9690] text-sm font-medium font-['Inter']">SmartReviewer_Workflow.exe</div>
                        </div>
                        
                        <!-- Window Content -->
                        <div class="p-8 md:p-12">
                            <div class="space-y-12 relative before:absolute before:inset-0 before:ml-6 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-[#3A3734] before:to-transparent">
                                
                                <!-- Step 1 -->
                                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                                    <div class="flex items-center justify-center w-12 h-12 rounded-full border-4 border-[#1A1714] bg-[#6646E5] text-white font-bold shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shadow-[0_0_15px_rgba(102,70,229,0.5)] z-10 transition-transform duration-300 group-hover:scale-110">
                                        1
                                    </div>
                                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-3rem)] p-6 bg-[#1A1714]/60 border border-[#3A3734] rounded-[20px] group-hover:border-[#6646E5]/50 group-hover:-translate-y-1 transition-all duration-300">
                                        <h4 class="text-xl font-bold text-white mb-2 font-['Inter']">Upload your materials</h4>
                                        <p class="text-[#9E9690] text-[15px] leading-relaxed">Drag and drop your PDFs, PowerPoint slides, or even snap a photo of your handwritten notes. Our smart OCR will digitize it instantly.</p>
                                    </div>
                                </div>
                                
                                <!-- Step 2 -->
                                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                                    <div class="flex items-center justify-center w-12 h-12 rounded-full border-4 border-[#1A1714] bg-[#FBBF24] text-[#1A1714] font-bold shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shadow-[0_0_15px_rgba(251,191,36,0.4)] z-10 transition-transform duration-300 group-hover:scale-110">
                                        2
                                    </div>
                                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-3rem)] p-6 bg-[#1A1714]/60 border border-[#3A3734] rounded-[20px] group-hover:border-[#FBBF24]/50 group-hover:-translate-y-1 transition-all duration-300">
                                        <h4 class="text-xl font-bold text-white mb-2 font-['Inter']">AI processes content</h4>
                                        <p class="text-[#9E9690] text-[15px] leading-relaxed">AI analyzes your documents, extracts key concepts, and automatically generates comprehensive study guides, flashcards, and targeted quizzes.</p>
                                    </div>
                                </div>

                                <!-- Step 3 -->
                                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                                    <div class="flex items-center justify-center w-12 h-12 rounded-full border-4 border-[#1A1714] bg-[#60A5FA] text-[#1A1714] font-bold shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shadow-[0_0_15px_rgba(96,165,250,0.4)] z-10 transition-transform duration-300 group-hover:scale-110">
                                        3
                                    </div>
                                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-3rem)] p-6 bg-[#1A1714]/60 border border-[#3A3734] rounded-[20px] group-hover:border-[#60A5FA]/50 group-hover:-translate-y-1 transition-all duration-300">
                                        <h4 class="text-xl font-bold text-white mb-2 font-['Inter']">Review & ace exams</h4>
                                        <p class="text-[#9E9690] text-[15px] leading-relaxed">Take customized quizzes, review comprehensive flashcards, and master key concepts to ace your exams.</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- CTA Section -->
        <section class="py-32 relative bg-[#1A1714]/80 backdrop-blur-xl overflow-hidden border-t border-[#2E2B28]">
            <!-- CTA Blobs -->
            <div class="absolute inset-0 flex items-center justify-center z-[-1] pointer-events-none">
                <div class="w-[600px] h-[600px] bg-[#6646E5] rounded-full mix-blend-multiply filter blur-[150px] opacity-20 animate-pulse"></div>
            </div>

            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                <h2 class="text-[40px] md:text-[56px] font-bold font-['Inter'] mb-6 text-white leading-tight">Ready to ace your next exam?</h2>
                <p class="text-[#9E9690] text-[20px] mb-12 max-w-2xl mx-auto">Be one of the students who study smarter, not harder.</p>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-3 px-10 py-5 bg-[#E0D8FC] text-[#1A1714] rounded-[16px] font-bold text-[18px] hover:scale-105 hover:bg-white transition-all duration-300 shadow-[0_0_30px_rgba(224,216,252,0.2)] group">
                    Create Your Free Account
                    <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </section>
    </main>

    <footer class="border-t border-[#2E2B28] py-12 bg-[#1A1714] relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3 group cursor-pointer">
                <div class="w-10 h-10 bg-[#6646E5] rounded-[10px] flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <x-logo class="w-6 h-6 text-white" />
                </div>
                <div class="flex flex-col">
                    <span class="text-[20px] leading-tight font-normal font-['Inter'] text-white tracking-tight">Smart<span style="font-weight: bold;">Reviewer</span></span>
                    <p class="text-[#9E9690] text-[10px] font-medium tracking-wide">Study Smarter, Score Higher</p>
                </div>
            </div>
            
            <p class="text-[#7C7167] text-[14px]">
                &copy; {{ date('Y') }} SmartReviewer. All rights reserved.
            </p>
            
            <div class="flex gap-6">
                <a href="{{ route('privacy') }}" class="text-[#7C7167] hover:text-white transition-colors text-[14px]">Privacy</a>
                <a href="{{ route('terms') }}" class="text-[#7C7167] hover:text-white transition-colors text-[14px]">Terms</a>
                <a href="{{ route('about') }}" class="text-[#7C7167] hover:text-white transition-colors text-[14px]">About Us</a>
            </div>
        </div>
    </footer>

</body>
</html>
