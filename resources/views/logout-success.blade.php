<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Logged Out | SmartReviewer</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;600&family=Inter:wght@400;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#1A1714] min-h-screen flex items-center justify-center font-['Instrument_Sans'] text-white overflow-hidden selection:bg-[#6646E5] selection:text-white">

    <!-- Global Grid Background & Blobs -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff0f_1px,transparent_1px),linear-gradient(to_bottom,#ffffff0f_1px,transparent_1px)] bg-[size:48px_48px]"></div>
        <div class="absolute -top-40 -left-40 w-[600px] h-[600px] bg-[#6646E5] rounded-full mix-blend-multiply filter blur-[128px] opacity-30 animate-pulse" style="animation-duration: 4s;"></div>
        <div class="absolute -bottom-40 -right-40 w-[600px] h-[600px] bg-[#E0D8FC] rounded-full mix-blend-multiply filter blur-[128px] opacity-15 animate-pulse" style="animation-duration: 6s;"></div>
    </div>

    <!-- Main Content Card -->
    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)" 
         :class="show ? 'opacity-100 scale-100 translate-y-0' : 'opacity-0 scale-95 translate-y-8'"
         class="relative z-10 w-full max-w-[480px] bg-white/5 backdrop-blur-2xl border border-white/10 rounded-[32px] p-10 md:p-12 flex flex-col items-center shadow-[0_20px_50px_rgba(0,0,0,0.5)] mx-4 transition-all duration-700 ease-out">
        
        <!-- Logout Icon -->
        <div class="w-20 h-20 bg-[#6646E5] rounded-[22px] flex items-center justify-center shadow-[0_0_30px_rgba(102,70,229,0.3)] mb-10 transform hover:scale-105 transition-transform duration-300">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
        </div>

        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500/10 text-emerald-400 text-sm font-semibold mb-6 border border-emerald-500/20">
            <span class="flex h-2 w-2 rounded-full bg-emerald-500 shadow-[0_0_10px_#10B981]"></span>
            Signed Out Safely
        </div>

        <h2 class="text-[32px] md:text-[38px] font-bold text-white font-['Inter'] mb-4 text-center tracking-tight leading-tight">
            See you next time!
        </h2>
        
        <p class="text-[#9E9690] text-[16px] md:text-[18px] text-center leading-relaxed mb-12 max-w-[340px]">
            Your progress is securely saved. Take a break and return whenever you're ready to master more.
        </p>

        <a href="/" class="group w-full bg-[#E0D8FC] hover:bg-white text-[#1A1714] font-bold text-[18px] rounded-[18px] py-4.5 transition-all duration-300 shadow-[0_0_30px_rgba(224,216,252,0.2)] hover:shadow-[0_0_40px_rgba(255,255,255,0.3)] hover:-translate-y-1 text-center flex items-center justify-center gap-2 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></div>
            <span class="relative z-10">Back to Home</span>
            <svg class="w-5 h-5 relative z-10 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </a>
    </div>

</body>
</html>
