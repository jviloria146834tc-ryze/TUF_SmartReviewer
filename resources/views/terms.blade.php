<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terms of Service | SmartReviewer</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;600&family=Inter:wght@400;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Hide scrollbar for Chrome, Safari and Opera */
        ::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        html {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }

        body {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</head>
<body class="bg-[#1A1714] min-h-screen flex flex-col font-['Instrument_Sans'] text-[#9E9690] selection:bg-[#6646E5] selection:text-white">

    <!-- Header -->
    <header class="bg-[#1A1714]/80 backdrop-blur-md border-b border-[#2E2B28] fixed top-0 w-full z-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3 relative z-10 group">
                <div class="w-12 h-12 bg-[#6646E5] rounded-[14px] flex items-center justify-center shadow-lg">
                    <x-logo class="w-7 h-7 text-white" />
                </div>
                <span class="text-[26px] font-normal font-['Inter'] text-white tracking-tight">Smart<span style="font-weight: bold;">Reviewer</span></span>
            </a>
            <div class="flex items-center gap-5">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-[15px] font-semibold text-white hover:text-[#E0D8FC] transition-colors">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-[15px] font-semibold text-white hover:text-[#E0D8FC] transition-colors">Sign in</a>
                    <a href="{{ route('register') }}" class="hidden md:block text-[15px] font-semibold bg-[#6646E5] hover:bg-[#5538D4] text-white px-5 py-2.5 rounded-[12px] transition-all shadow-[0_0_15px_rgba(102,70,229,0.3)]">Get Started</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col pt-40 pb-20 px-6 lg:px-8 max-w-4xl mx-auto w-full relative z-10">
        <!-- Back Button -->
        <div class="mb-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-[#9E9690] hover:text-white transition-colors font-medium group">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Home
            </a>
        </div>

        <h1 class="text-4xl md:text-5xl font-bold font-['Inter'] text-white mb-8">Terms of Service</h1>
        
        <div class="prose prose-invert prose-p:text-[#9E9690] prose-headings:text-white max-w-none">
            <p class="text-lg leading-relaxed mb-8">By accessing or using SmartReviewer, you acknowledge that you have read, understood, and agreed to be bound by these Terms of Service. If you do not agree, you are advised to discontinue use of the system.</p>

            <h2 class="text-2xl font-bold font-['Inter'] mt-10 mb-4 text-[#E0D8FC]">Purpose of the System</h2>
            <p class="leading-relaxed mb-6">SmartReviewer is developed to assist users in reviewing, analyzing, and managing academic or related content. The system must only be used for lawful and legitimate purposes.</p>

            <h2 class="text-2xl font-bold font-['Inter'] mt-10 mb-4 text-[#E0D8FC]">User Obligations</h2>
            <p class="leading-relaxed mb-6">Users are responsible for maintaining the confidentiality of their accounts and credentials. Users agree not to upload, transmit, or distribute unlawful, harmful, or malicious content, attempt to bypass or compromise system security, or engage in fraudulent, abusive, or unethical activities.</p>

            <h2 class="text-2xl font-bold font-['Inter'] mt-10 mb-4 text-[#E0D8FC]">Intellectual Property Rights</h2>
            <p class="leading-relaxed mb-6">All system materials, including but not limited to design, structure, and functionality, are owned by the developers and are protected under applicable intellectual property laws. Unauthorized reproduction or distribution is prohibited.</p>

            <h2 class="text-2xl font-bold font-['Inter'] mt-10 mb-4 text-[#E0D8FC]">User-Generated Content</h2>
            <p class="leading-relaxed mb-6">Users retain ownership of all submitted data. However, by using SmartReviewer, users grant permission for the system to process such data strictly for system functionality, analysis, and improvement.</p>

            <h2 class="text-2xl font-bold font-['Inter'] mt-10 mb-4 text-[#E0D8FC]">Limitation of Liability</h2>
            <p class="leading-relaxed mb-6">SmartReviewer is provided on an “as is” and “as available” basis. The developers shall not be held liable for any direct, indirect, incidental, or consequential damages arising from the use or inability to use the system.</p>

            <h2 class="text-2xl font-bold font-['Inter'] mt-10 mb-4 text-[#E0D8FC]">Modifications to the Terms</h2>
            <p class="leading-relaxed mb-6">The developers reserve the right to modify these Terms at any time. Continued use of the system constitutes acceptance of any revisions.</p>
        </div>
    </main>

    <!-- Footer -->
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