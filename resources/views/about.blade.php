<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="overflow-y: overlay; scrollbar-gutter: stable;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About Us | SmartReviewer</title>
    
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
            <a href="{{ url('/') }}" class="flex items-center gap-3 relative z-10 group">
                <div class="w-12 h-12 bg-[#6646E5] rounded-[14px] flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300">
                    <x-logo class="w-7 h-7 text-white" />
                </div>
                <span class="text-[26px] font-normal font-['Inter'] text-white tracking-tight">Smart<span style="font-weight: bold;">Reviewer</span></span>
            </a>
            
            <div class="flex items-center gap-4 sm:gap-5">
                <a href="{{ route('login') }}" class="text-[14px] sm:text-[15px] font-semibold text-white hover:text-[#E0D8FC] transition-colors">Sign in</a>
                <a href="{{ route('register') }}" class="hidden sm:inline-flex text-[15px] font-semibold bg-[#6646E5] hover:bg-[#5538D4] text-white px-5 py-2.5 rounded-[12px] transition-all shadow-[0_0_15px_rgba(102,70,229,0.3)] hover:shadow-[0_0_25px_rgba(102,70,229,0.5)] hover:-translate-y-0.5">Get Started</a>
            </div>
        </div>
    </header>

    <main class="flex-1 flex flex-col pt-32 pb-20 relative z-10">
        <!-- Background Blur Effects -->
        <div class="absolute top-20 left-0 w-[500px] h-[500px] bg-[#6646E5] rounded-full mix-blend-multiply filter blur-[150px] opacity-20 animate-pulse" style="animation-duration: 6s; z-index: -1;"></div>
        <div class="absolute top-[40%] right-0 w-[400px] h-[400px] bg-[#E0D8FC] rounded-full mix-blend-multiply filter blur-[128px] opacity-10 animate-pulse" style="animation-duration: 5s; z-index: -1;"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 w-full animate-glide-up">
            
            <!-- Back Button -->
            <div class="mb-12">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-[#9E9690] hover:text-white transition-colors font-medium group">
                    <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Home
                </a>
            </div>

            <!-- Custom Animations -->
            <style>
                @keyframes glideRightBlur {
                    0% { opacity: 0; transform: translateX(-40px); filter: blur(8px); }
                    100% { opacity: 1; transform: translateX(0); filter: blur(0); }
                }
                .animate-glide-right-blur {
                    animation: glideRightBlur 1.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
                }
            </style>

            <!-- System Overview -->
            <div class="mb-24 text-center">
                <h1 class="animate-glide-right-blur text-4xl md:text-[64px] font-bold font-['Inter'] leading-[1.1] tracking-tight mb-8">
                    Empowering the Next Generation of <span class="text-[#6646E5]">Learners</span>
                </h1>
                <p class="animate-glide-right-blur text-xl md:text-[22px] text-[#9E9690] font-['Instrument_Sans'] max-w-3xl mx-auto leading-relaxed mb-12" style="animation-delay: 200ms; opacity: 0;">
                    SmartReviewer is an advanced AI-powered study companion designed to transform how students engage with educational materials. By bridging the gap between raw data and actionable knowledge, we help students organize, review, and master their subjects faster and more efficiently.
                </p>
                <div class="animate-glide-right-blur inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#2E2B28]/80 text-[#E0D8FC] text-sm font-medium mb-8 border border-[#E0D8FC]/30 backdrop-blur-md shadow-[0_0_15px_rgba(224,216,252,0.1)] cursor-default hover:bg-[#3A3734] hover:scale-105 hover:border-[#E0D8FC]/60 hover:shadow-[0_0_25px_rgba(224,216,252,0.2)] transition-all duration-300" style="animation-delay: 400ms; opacity: 0;">
                    <span class="flex h-2 w-2 rounded-full bg-[#6646E5] animate-pulse"></span>
                    Built for Students, Driven by AI
                </div>
            </div>

            <!-- Tech Stack & APIs -->
            <div class="bg-[#1A1714]/80 backdrop-blur-xl border border-[#2E2B28] rounded-[32px] p-8 md:p-12 mb-24 shadow-2xl relative overflow-hidden group">
                <div class="absolute -right-20 -bottom-20 w-[300px] h-[300px] bg-[#6646E5] rounded-full mix-blend-multiply filter blur-[80px] opacity-10 group-hover:opacity-30 transition-opacity duration-700"></div>
                
                <h2 class="text-[32px] font-bold font-['Inter'] mb-10 flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#2E2B28] rounded-[16px] flex items-center justify-center border border-[#3A3734] group-hover:scale-110 transition-transform">
                        <x-heroicon-o-cpu-chip class="w-6 h-6 text-[#E0D8FC]" />
                    </div>
                    Under the Hood
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                    <div>
                        <h3 class="text-[20px] font-bold text-white mb-6 font-['Inter']">Core Technology</h3>
                        <ul class="space-y-4">
                            <li class="flex items-start gap-4 hover:translate-x-2 transition-transform cursor-default">
                                <div class="mt-1 w-2 h-2 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.6)]"></div>
                                <div>
                                    <span class="font-bold text-white block">Laravel Ecosystem</span>
                                    <span class="text-[#9E9690] text-[14px]">Powering the robust backend, queue workers, and background jobs.</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-4 hover:translate-x-2 transition-transform cursor-default">
                                <div class="mt-1 w-2 h-2 rounded-full bg-[#38BDF8] shadow-[0_0_8px_rgba(56,189,248,0.6)]"></div>
                                <div>
                                    <span class="font-bold text-white block">Tailwind CSS & Alpine.js</span>
                                    <span class="text-[#9E9690] text-[14px]">Creating the seamless, highly interactive user interface.</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-4 hover:translate-x-2 transition-transform cursor-default">
                                <div class="mt-1 w-2 h-2 rounded-full bg-[#10B981] shadow-[0_0_8px_rgba(16,185,129,0.6)]"></div>
                                <div>
                                    <span class="font-bold text-white block">MySQL Database</span>
                                    <span class="text-[#9E9690] text-[14px]">Providing secure, scalable, and optimized storage.</span>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-[20px] font-bold text-white mb-6 font-['Inter']">APIs & Integrations</h3>
                        <ul class="space-y-4">
                            <li class="flex items-start gap-4 hover:translate-x-2 transition-transform cursor-default">
                                <div class="mt-1 w-2 h-2 rounded-full bg-[#8B5CF6] shadow-[0_0_8px_rgba(139,92,246,0.6)]"></div>
                                <div>
                                    <span class="font-bold text-white block">Google Gemini / Advanced AI</span>
                                    <span class="text-[#9E9690] text-[14px]">Driving intelligent summaries, quizzes, and flashcards.</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-4 hover:translate-x-2 transition-transform cursor-default">
                                <div class="mt-1 w-2 h-2 rounded-full bg-[#F59E0B] shadow-[0_0_8px_rgba(245,158,11,0.6)]"></div>
                                <div>
                                    <span class="font-bold text-white block">OCR Technology</span>
                                    <span class="text-[#9E9690] text-[14px]">Extracting raw text from PDFs, images, and documents.</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- The Team -->
            <div>
                <div class="text-center mb-16">
                    <h2 class="text-[40px] font-bold font-['Inter'] mb-4">Meet the Team</h2>
                    <p class="text-[#9E9690] text-lg max-w-2xl mx-auto">The dedicated individuals who brought SmartReviewer to life.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Member 1 -->
                    <div class="bg-[#2E2B28]/40 backdrop-blur-lg border border-[#3A3734] rounded-[24px] p-8 text-center hover:-translate-y-2 transition-transform duration-500 group shadow-lg">
                        <div class="w-24 h-24 mx-auto bg-gradient-to-br from-[#6646E5] to-[#E0D8FC] rounded-full mb-6 p-1 group-hover:shadow-[0_0_20px_rgba(102,70,229,0.4)] group-hover:scale-110 transition-all duration-300">
                            <div class="w-full h-full bg-[#1A1714] rounded-full flex items-center justify-center border-[3px] border-[#1A1714]">
                                <x-heroicon-s-star class="w-10 h-10 text-white" />
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-white font-['Inter'] mb-1">Wyndy Shane Yecyec</h3>
                        <p class="text-[#6646E5] font-bold text-sm tracking-wider uppercase mb-4">Project Leader</p>
                        <p class="text-[#9E9690] text-sm leading-relaxed">
                            Guided the project vision, managed timelines, and ensured the final product met the core educational objectives.
                        </p>
                    </div>

                    <!-- Member 2 -->
                    <div class="bg-[#2E2B28]/40 backdrop-blur-lg border border-[#3A3734] rounded-[24px] p-8 text-center hover:-translate-y-2 transition-transform duration-500 group shadow-lg">
                        <div class="w-24 h-24 mx-auto bg-gradient-to-br from-emerald-400 to-emerald-700 rounded-full mb-6 p-1 group-hover:shadow-[0_0_20px_rgba(16,185,129,0.4)] group-hover:scale-110 transition-all duration-300">
                            <div class="w-full h-full bg-[#1A1714] rounded-full flex items-center justify-center border-[3px] border-[#1A1714]">
                                <x-heroicon-s-code-bracket class="w-10 h-10 text-white" />
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-white font-['Inter'] mb-1">Jay Lloyd Viloria</h3>
                        <p class="text-[#10B981] font-bold text-sm tracking-wider uppercase mb-4">Fullstack Developer</p>
                        <p class="text-[#9E9690] text-sm leading-relaxed">
                            Architected the system, integrated the AI engines, and built the interactive frontend and optimized backend queues.
                        </p>
                    </div>

                    <!-- Member 3 -->
                    <div class="bg-[#2E2B28]/40 backdrop-blur-lg border border-[#3A3734] rounded-[24px] p-8 text-center hover:-translate-y-2 transition-transform duration-500 group shadow-lg">
                        <div class="w-24 h-24 mx-auto bg-gradient-to-br from-amber-400 to-amber-700 rounded-full mb-6 p-1 group-hover:shadow-[0_0_20px_rgba(245,158,11,0.4)] group-hover:scale-110 transition-all duration-300">
                            <div class="w-full h-full bg-[#1A1714] rounded-full flex items-center justify-center border-[3px] border-[#1A1714]">
                                <x-heroicon-s-check-badge class="w-10 h-10 text-white" />
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-white font-['Inter'] mb-1">Danica Marie Villaver</h3>
                        <p class="text-[#F59E0B] font-bold text-sm tracking-wider uppercase mb-4">Quality Assurance Tester</p>
                        <p class="text-[#9E9690] text-sm leading-relaxed">
                            Conducted rigorous testing of the quiz algorithms, flashcard retention models, and overall platform stability.
                        </p>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- The Ultimate Force Large Footer Text -->
        <div class="w-full overflow-hidden mt-20 -mb-20 flex justify-center items-end relative select-none pointer-events-none">
            <h2 class="text-[10vw] font-black tracking-tighter text-transparent bg-clip-text bg-gradient-to-b from-[#6646E5]/30 to-[#1A1714] leading-[0.75] whitespace-nowrap" style="font-family: 'Inter', sans-serif;">
                THE ULTIMATE FORCE
            </h2>
        </div>
    </main>

    <footer class="border-t border-[#2E2B28] py-12 bg-[#1A1714] relative z-20 mt-auto">
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