<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing | SmartReviewer</title>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;600;700&family=Inter:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <meta http-equiv="refresh" content="4;url=/reviewer" />
</head>
<body class="bg-[#1A1714] min-h-screen flex flex-col items-center justify-center font-['Instrument_Sans'] text-white relative overflow-hidden">

    <div class="absolute inset-0 z-0 flex items-center justify-center pointer-events-none">
        <div class="w-[500px] h-[500px] bg-[#6646E5] rounded-full mix-blend-screen filter blur-[150px] opacity-30 animate-pulse"></div>
    </div>

    <div class="relative z-10 flex flex-col items-center">
        <div class="relative w-24 h-24 flex items-center justify-center mb-8">
            <div class="absolute inset-0 border-4 border-[#E0D8FC]/20 rounded-[24px] rotate-45"></div>
            <div class="absolute inset-0 border-4 border-t-[#6646E5] border-r-[#6646E5] border-b-transparent border-l-transparent rounded-[24px] rotate-45 animate-spin"></div>
            <div class="w-12 h-12 bg-[#6646E5] rounded-[12px] flex items-center justify-center shadow-[0_0_30px_rgba(102,70,229,0.8)] animate-pulse">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
        </div>

        <h2 class="text-[28px] font-bold text-white font-['Inter'] mb-3 tracking-tight">AI is analyzing your material...</h2>
        <p class="text-[#A39D98] text-[16px] max-w-[350px] text-center leading-relaxed">
            Extracting key concepts, generating adaptive questions, and preparing your custom study guide.
        </p>
    </div>

</body>
</html>