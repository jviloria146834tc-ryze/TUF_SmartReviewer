<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email | SmartReviewer</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;600&family=Inter:wght@400;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-white h-screen flex font-['Instrument_Sans'] text-[#1A1714] overflow-hidden">

    <!-- Top Progress Bar -->
    <div id="top-progress-bar"></div>

    <div class="hidden md:flex md:w-[45%] lg:w-[45%] bg-[#1A1714] p-10 lg:p-16 flex-col justify-between relative overflow-hidden">
        
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff0a_1px,transparent_1px),linear-gradient(to_bottom,#ffffff0a_1px,transparent_1px)] bg-[size:32px_32px]"></div>
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-[#6646E5] rounded-full mix-blend-multiply filter blur-[128px] opacity-40 z-0"></div>
        <div class="absolute top-[40%] -right-20 w-96 h-96 bg-[#E0D8FC] rounded-full mix-blend-multiply filter blur-[128px] opacity-20 z-0"></div>

        <!-- Logo -->
        <a href="{{ url('/') }}" class="flex items-center gap-4 relative z-10 mb-12 group">
            <div class="w-14 h-14 bg-[#6646E5] rounded-[16px] flex items-center justify-center shadow-[0_0_20px_rgba(102,70,229,0.3)] group-hover:shadow-[0_0_35px_rgba(102,70,229,0.6)] group-hover:scale-105 transition-all duration-500">
                <x-logo class="w-8 h-8 text-white" />
            </div>
            <div class="flex flex-col">
                <span class="text-[32px] leading-tight font-normal font-['Inter'] text-white tracking-tight">Smart<span style="font-weight: bold;">Reviewer</span></span>
                <p class="text-[#9E9690] text-[14px] font-medium tracking-wide">Study Smarter, Score Higher</p>
            </div>
        </a>

        <div class="mt-8 mb-16 relative z-10">
            <!-- Heading -->
            <h1 class="text-white text-[50px] lg:text-[64px] font-bold font-['Inter'] leading-[1.05] mb-6 animate-glide-down">
                Almost<br>there.
            </h1>
            <!-- Subtext -->
            <p class="text-[#9E9690] text-[18px] lg:text-[20px] leading-relaxed max-w-[450px] animate-glide-down delay-100">
                Check your inbox to confirm your email and unlock the power of AI-assisted studying.
            </p>

            <!-- Feature Card -->
            <div class="w-full mt-12 hidden lg:flex flex-col gap-6 max-w-[500px] relative z-10 pb-12 animate-glide-down delay-200">
                <div class="bg-[#262321] border border-white/5 p-6 rounded-[20px] transform hover:scale-[1.02] transition-all duration-500 shadow-xl cursor-default relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-12 h-12 bg-[#6646E5] rounded-full blur-2xl opacity-20"></div>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-[#E0D8FC] rounded-[12px] flex items-center justify-center shadow-sm">
                            <svg class="w-6 h-6 text-[#6646E5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <div class="text-white text-[18px] font-bold tracking-tight mb-0.5">Check Spam Folder</div>
                            <div class="text-white/70 text-[14px] leading-snug">If you don't see it, it might be in your spam folder.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div></div>
    </div>

    <!-- Right Side -->
    <div class="flex-1 flex flex-col justify-center items-center p-8 lg:p-12 relative bg-white overflow-y-auto">
        
        <div class="w-full max-w-[440px]">
            <h2 class="text-[32px] md:text-[35px] font-bold font-['Inter'] leading-tight mb-2 text-[#1A1714] animate-glide-up">
                Verify your email
            </h2>
            <p class="text-[#7C7167] text-[16px] mb-8 animate-glide-up delay-100">
                Thanks for signing up! We've sent a 6-digit verification code to your email address. Please enter it below to verify your account.
            </p>

            @if (session('message'))
                <div class="mb-8 bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-start gap-3 animate-glide-up delay-150">
                    <div class="bg-emerald-500 text-white p-1 rounded-full flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-emerald-800 font-bold text-[14px]">Code Sent</h4>
                        <p class="text-emerald-700 text-[13px] mt-0.5">{{ session('message') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->has('code'))
                <div class="mb-8 bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3 animate-glide-up delay-150">
                    <div class="bg-red-500 text-white p-1 rounded-full flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-red-800 font-bold text-[14px]">Verification Failed</h4>
                        <p class="text-red-700 text-[13px] mt-0.5">{{ $errors->first('code') }}</p>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('verification.verify_code') }}" class="flex flex-col gap-5 relative z-10 animate-glide-up delay-200 mb-8" x-data="{ code: '' }">
                @csrf
                <div class="flex flex-col gap-2 group">
                    <label class="text-[15px] font-semibold text-[#1A1714] group-focus-within:text-[#6646E5] transition-colors">Verification Code</label>
                    <input type="text" name="code" x-model="code" placeholder="123456" maxlength="6" required class="w-full border border-[#E2DDD8] rounded-[12px] px-4 py-3.5 text-[24px] tracking-[0.5em] text-center text-[#1A1714] focus:outline-none focus:border-[#6646E5] focus:ring-4 focus:ring-[#6646E5]/10 hover:border-[#C4BCB4] transition-all bg-white shadow-sm placeholder-[#9E9690]/50 font-mono">
                </div>
                
                <button type="submit" :disabled="code.length !== 6" class="w-full bg-[#6646E5] disabled:bg-[#C4BCB4] disabled:cursor-not-allowed hover:bg-[#5538D4] text-white font-semibold text-[17px] rounded-[12px] py-4 transition-all duration-300 shadow-[0_4px_15_rgba(102,70,229,0.3)] disabled:shadow-none hover:shadow-[0_6px_25_rgba(102,70,229,0.5)] disabled:hover:shadow-none hover:-translate-y-0.5 disabled:hover:translate-y-0 flex items-center justify-center gap-2 group">
                    Verify Account <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </button>
            </form>

            <form method="POST" action="{{ route('verification.send') }}" class="relative z-10 animate-glide-up delay-300">
                @csrf
                <button type="submit" class="w-full bg-white border border-[#E2DDD8] hover:bg-[#F9F8F6] hover:border-[#C4BCB4] text-[#1A1714] font-semibold text-[15px] rounded-[12px] py-3.5 transition-all flex items-center justify-center gap-2">
                    Resend Code
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="mt-8 text-center relative z-10 animate-glide-up delay-300">
                @csrf
                <button type="submit" class="text-[15px] text-[#7C7167] hover:text-[#1A1714] transition-all flex items-center justify-center gap-2 group w-max mx-auto font-semibold">
                    <span class="group-hover:-translate-x-1 transition-transform">&larr;</span> Log out and back to home
                </button>
            </form>
        </div>
    </div>
</body>
</html>