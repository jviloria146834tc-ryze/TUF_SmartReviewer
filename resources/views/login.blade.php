<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | SmartReviewer</title>
    
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
                Welcome<br>back!
            </h1>
            <!-- Subtext -->
            <p class="text-[#9E9690] text-[18px] lg:text-[20px] leading-relaxed max-w-[420px] animate-glide-down delay-100">
                Your personalized AI study companion is ready for your next session.
            </p>
            
            <!-- Cards -->
            <div class="w-full mt-12 hidden lg:grid grid-cols-2 gap-x-6 gap-y-8 max-w-[650px] relative z-10 pb-12 animate-glide-down delay-200">
                
                <div class="bg-[#262321] border border-white/5 p-6 rounded-[20px] transform -rotate-2 hover:rotate-0 transition-all duration-300 shadow-xl cursor-default group/card relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-12 h-12 bg-[#6646E5] rounded-full blur-2xl opacity-0 group-hover/card:opacity-40 transition-opacity"></div>
                    <div class="text-[#E0D8FC] text-[15px] font-semibold mb-2">Study Streak</div>
                    <div class="text-[32px] xl:text-[36px] font-bold tracking-tight flex items-center gap-2 transition-transform group-hover/card:scale-105 duration-300" style="color: {{ $lastStreakColor == '#7C7167' ? 'white' : $lastStreakColor }}">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd" />
                        </svg>
                        {{ $lastStreak }} <span class="text-[18px] font-medium text-white/60 mt-1.5 group-hover/card:text-white transition-colors duration-150">Days</span>
                    </div>
                </div>

                <div class="bg-[#262321] border border-white/5 p-6 rounded-[20px] transform rotate-3 hover:rotate-0 transition-all duration-300 shadow-2xl cursor-default translate-y-8 group/card relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-12 h-12 bg-[#6646E5] rounded-full blur-2xl opacity-0 group-hover/card:opacity-40 transition-opacity"></div>
                    <div class="text-[#E0D8FC] text-[15px] font-semibold mb-3">Avg. Quiz Score</div>
                    <div class="text-white text-[32px] xl:text-[36px] font-bold tracking-tight flex items-center gap-3 transition-transform group-hover/card:scale-105 duration-300">
                        {{ $lastAvgScore }}% <span class="bg-[#1A1714] text-[#9E9690] text-[14px] px-3 py-1.5 rounded-full font-medium border border-white/10">{{ $lastAvgScore >= 80 ? 'High' : ($lastAvgScore >= 50 ? 'Stable' : 'Growing') }}</span>
                    </div>
                </div>

                <div class="bg-[#262321] border border-white/5 p-6 rounded-[20px] transform rotate-1 hover:rotate-0 transition-all duration-300 shadow-xl cursor-default group/card relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-12 h-12 bg-[#6646E5] rounded-full blur-2xl opacity-0 group-hover/card:opacity-40 transition-opacity"></div>
                    <div class="text-[#E0D8FC] text-[15px] font-semibold mb-2">Materials Reviewed</div>
                    <div class="text-white text-[32px] xl:text-[36px] font-bold tracking-tight flex items-baseline gap-1.5 transition-transform group-hover/card:scale-105 duration-300">
                        <svg class="w-8 h-8 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        {{ $lastMaterialsCount }} <span class="text-[18px] font-medium text-white/60 mb-1.5 group-hover/card:text-white transition-colors duration-150">Docs</span>
                    </div>
                </div>

                <div class="bg-[#262321] border border-white/5 p-6 rounded-[20px] transform -rotate-2 hover:rotate-0 transition-all duration-300 shadow-2xl cursor-default translate-y-8 group/card relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-12 h-12 bg-[#6646E5] rounded-full blur-2xl opacity-0 group-hover/card:opacity-40 transition-opacity"></div>
                    <div class="text-[#E0D8FC] text-[15px] font-semibold mb-3">Flashcards Mastered</div>
                    <div class="text-white text-[32px] xl:text-[36px] font-bold tracking-tight flex items-center gap-3 transition-transform group-hover/card:scale-105 duration-300">
                        {{ $lastMasteryCount }} <span class="bg-[#1A1714] text-[#9E9690] text-[14px] px-3 py-1.5 rounded-full font-medium border border-white/10">Cards</span>
                    </div>
                    <div class="text-white/60 text-[13px] mt-1.5 group-hover/card:text-white transition-colors duration-150">Keep pushing your mastery!</div>
                </div>

            </div>
        </div>

        <div></div>
    </div>

    <!-- Right Side -->
    <div class="flex-1 flex flex-col justify-center items-center p-8 lg:p-12 relative bg-white overflow-y-auto">
        
        <div class="w-full max-w-[440px]">
            <h2 class="text-[32px] md:text-[35px] font-bold font-['Inter'] leading-tight mb-2 text-[#1A1714] animate-glide-up">
                Sign In
            </h2>
            <p class="text-[#7C7167] text-[16px] mb-10 animate-glide-up delay-100">
                Don't have an account? <a href="{{ route('register') }}" class="text-[#6646E5] font-semibold hover:text-[#5538D4] transition-colors">Create one</a>
            </p>

            @if ($errors->any())
                <div class="mb-8 bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3 animate-glide-up delay-150">
                    <div class="bg-red-500 text-white p-1 rounded-full flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-red-800 font-bold text-[14px]">Authentication Failed</h4>
                        <p class="text-red-700 text-[13px] mt-0.5">The credentials you entered do not match our records. Please try again or create an account.</p>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="flex flex-col gap-5 relative z-10 animate-glide-up delay-200">
                @csrf
                <div class="flex flex-col gap-2 group">
                    <label class="text-[15px] font-semibold text-[#1A1714] group-focus-within:text-[#6646E5] transition-colors">Email address</label>
                    <input type="email" name="email" required autofocus class="w-full border border-[#E2DDD8] rounded-[12px] px-4 py-3.5 text-[16px] text-[#1A1714] focus:outline-none focus:border-[#6646E5] focus:ring-4 focus:ring-[#6646E5]/10 hover:border-[#C4BCB4] transition-all bg-white shadow-sm placeholder-[#9E9690]">
                </div>
                <div class="flex flex-col gap-2 group" x-data="{ show: false }">
                    <label class="text-[15px] font-semibold text-[#1A1714] group-focus-within:text-[#6646E5] transition-colors">Password</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="password" placeholder="••••••••" required class="w-full border border-[#E2DDD8] rounded-[12px] px-4 py-3.5 pr-12 text-[16px] text-[#1A1714] tracking-widest focus:outline-none focus:border-[#6646E5] focus:ring-4 focus:ring-[#6646E5]/10 hover:border-[#C4BCB4] transition-all bg-white shadow-sm placeholder-[#9E9690]">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#6646E5] transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg x-show="show" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </button>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-1 mb-6">
                    <label class="flex items-center gap-2 cursor-pointer text-[15px] text-[#7C7167] hover:text-[#1A1714] transition-colors group">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-[#E2DDD8] text-[#6646E5] focus:ring-[#6646E5] group-hover:border-[#6646E5] transition-colors">
                        Remember me
                    </label>
                    <a href="{{ route('password.request') }}" class="text-[15px] text-[#6646E5] font-semibold hover:text-[#5538D4] hover:underline transition-all">Forgot password?</a>
                </div>
                <button type="submit" class="w-full bg-[#6646E5] hover:bg-[#5538D4] text-white font-semibold text-[17px] rounded-[12px] py-4 transition-all duration-300 shadow-[0_4px_15_rgba(102,70,229,0.3)] hover:shadow-[0_6px_25_rgba(102,70,229,0.5)] hover:-translate-y-0.5 flex items-center justify-center gap-2 group">
                    Sign In <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
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