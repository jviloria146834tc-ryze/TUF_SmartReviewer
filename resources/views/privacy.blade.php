<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Privacy Policy | SmartReviewer</title>
    
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

        <h1 class="text-4xl md:text-5xl font-bold font-['Inter'] text-white mb-8">Privacy Policy</h1>
        
        <div class="prose prose-invert prose-p:text-[#9E9690] prose-headings:text-white max-w-none">
            <p class="text-lg leading-relaxed mb-8">This Privacy Policy is established in compliance with Republic Act No. 10173, also known as the Data Privacy Act of 2012, and its Implementing Rules and Regulations.</p>

            <h2 class="text-2xl font-bold font-['Inter'] mt-10 mb-4 text-[#E0D8FC]">Collection of Personal Data</h2>
            <p class="leading-relaxed mb-6">SmartReviewer may collect personal information such as name and email address, system usage data including logs and interactions, and user-submitted content for review and analysis. All data collected shall be relevant and necessary to the system’s purpose.</p>

            <h2 class="text-2xl font-bold font-['Inter'] mt-10 mb-4 text-[#E0D8FC]">Purpose of Data Processing</h2>
            <p class="leading-relaxed mb-6">Collected data shall be used solely for providing and improving system services, facilitating user access and functionality, and ensuring system security.</p>

            <h2 class="text-2xl font-bold font-['Inter'] mt-10 mb-4 text-[#E0D8FC]">Data Protection and Security Measures</h2>
            <p class="leading-relaxed mb-6">SmartReviewer implements appropriate organizational, physical, and technical measures to protect personal data against unauthorized access, disclosure, or destruction.</p>

            <h2 class="text-2xl font-bold font-['Inter'] mt-10 mb-4 text-[#E0D8FC]">Data Sharing and Disclosure</h2>
            <p class="leading-relaxed mb-6">Personal data will not be sold or shared with third parties except when required by law or when necessary for system operations under strict confidentiality obligations.</p>

            <h2 class="text-2xl font-bold font-['Inter'] mt-10 mb-4 text-[#E0D8FC]">Data Retention</h2>
            <p class="leading-relaxed mb-6">Personal data shall be retained only as long as necessary to fulfill its intended purpose or as required by law. Afterward, data will be securely deleted or anonymized.</p>

            <h2 class="text-2xl font-bold font-['Inter'] mt-10 mb-4 text-[#E0D8FC]">Rights of Data Subjects</h2>
            <p class="leading-relaxed mb-6">Users have the right to be informed, access their personal data, request correction, request deletion, and object to processing where applicable, in accordance with the Data Privacy Act.</p>

            <h2 class="text-2xl font-bold font-['Inter'] mt-10 mb-4 text-[#E0D8FC]">Consent</h2>
            <p class="leading-relaxed mb-6">By using SmartReviewer, users consent to the collection and processing of their personal data as outlined in this Privacy Policy.</p>

            <h2 class="text-2xl font-bold font-['Inter'] mt-10 mb-4 text-[#E0D8FC]">Changes to the Privacy Policy</h2>
            <p class="leading-relaxed mb-6">This Privacy Policy may be updated from time to time. Continued use of the system signifies acceptance of any changes.</p>
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