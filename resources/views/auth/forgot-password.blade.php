<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | SmartReviewer</title>
    
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
                Forgot your<br>password?
            </h1>
            <!-- Subtext -->
            <p class="text-[#9E9690] text-[18px] lg:text-[20px] leading-relaxed max-w-[450px] animate-glide-down delay-100">
                No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
            </p>
        </div>

        <div></div>
    </div>

    <!-- Right Side -->
    <div class="flex-1 flex flex-col justify-center items-center p-8 lg:p-12 relative bg-white overflow-y-auto">
        
        <div class="w-full max-w-[440px]">
            <h2 class="text-[32px] md:text-[35px] font-bold font-['Inter'] leading-tight mb-2 text-[#1A1714] animate-glide-up">
                Reset Password
            </h2>
            <p class="text-[#7C7167] text-[16px] mb-10 animate-glide-up delay-100">
                Remember your password? <a href="{{ route('login') }}" class="text-[#6646E5] font-semibold hover:text-[#5538D4] transition-colors">Sign In</a>
            </p>

            @if (session('status'))
                <div class="mb-8 bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-start gap-3 animate-glide-up delay-150">
                    <div class="bg-emerald-500 text-white p-1 rounded-full flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-emerald-800 font-bold text-[14px]">Link Sent</h4>
                        <p class="text-emerald-700 text-[13px] mt-0.5">{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-8 bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3 animate-glide-up delay-150">
                    <div class="bg-red-500 text-white p-1 rounded-full flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-red-800 font-bold text-[14px]">Request Failed</h4>
                        <ul class="text-red-700 text-[13px] mt-1 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="#" class="flex flex-col gap-5 relative z-10 animate-glide-up delay-200">
                @csrf
                <div class="flex flex-col gap-2 group">
                    <label class="text-[15px] font-semibold text-[#1A1714] group-focus-within:text-[#6646E5] transition-colors">Email address</label>
                    <input type="email" name="email" required autofocus class="w-full border border-[#E2DDD8] rounded-[12px] px-4 py-3.5 text-[16px] text-[#1A1714] focus:outline-none focus:border-[#6646E5] focus:ring-4 focus:ring-[#6646E5]/10 hover:border-[#C4BCB4] transition-all bg-white shadow-sm placeholder-[#9E9690]">
                </div>
                
                <button type="submit" class="w-full bg-[#6646E5] hover:bg-[#5538D4] text-white font-semibold text-[17px] rounded-[12px] py-4 mt-2 transition-all duration-300 shadow-[0_4px_15_rgba(102,70,229,0.3)] hover:shadow-[0_6px_25_rgba(102,70,229,0.5)] hover:-translate-y-0.5 flex items-center justify-center gap-2 group">
                    Email Password Reset Link <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </button>
            </form>

            <div class="mt-8 text-center relative z-10 animate-glide-up delay-300">
                <a href="{{ url('/') }}" class="text-[15px] text-[#7C7167] hover:text-[#1A1714] transition-all flex items-center justify-center gap-2 group w-max mx-auto">
                    <span class="group-hover:-translate-x-1 transition-transform">&larr;</span> Back to home
                </a>
            </div>
        </div>
    </div>
</body>
</html>