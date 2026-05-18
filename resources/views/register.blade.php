<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | SmartReviewer</title>
    
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
                Supercharge<br>your studies.
            </h1>
            <!-- Subtext -->
            <p class="text-[#9E9690] text-[18px] lg:text-[20px] leading-relaxed max-w-[450px] animate-glide-down delay-100">
                The smartest way to study—instantly generate quizzes, flashcards, and study guides from your lecture notes.  
            </p>

            <!-- Cards -->
            <div class="w-full mt-12 hidden lg:grid grid-cols-2 gap-x-6 gap-y-8 max-w-[650px] relative z-10 pb-12 animate-glide-down delay-200">
                
                <!-- Smart OCR -->
                <div class="bg-[#262321] border border-white/5 p-6 rounded-[20px] transform -rotate-2 hover:rotate-0 transition-all duration-500 shadow-xl cursor-default group/card relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-12 h-12 bg-[#6646E5] rounded-full blur-2xl opacity-0 group-hover/card:opacity-40 transition-opacity"></div>
                    <div class="w-12 h-12 bg-[#E0D8FC] rounded-[12px] flex items-center justify-center mb-4 shadow-sm group-hover/card:scale-110 transition-transform duration-500">
                        <svg class="w-6 h-6 text-[#6646E5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div class="text-white text-[18px] font-bold tracking-tight mb-1.5 group-hover/card:text-[#E0D8FC] transition-colors">Smart OCR</div>
                    <div class="text-white/70 text-[14px] leading-snug hover:text-white transition-colors duration-150">Extract text from PDFs, Word docs, and images.</div>
                </div>

                <!-- Customizable Quizzes -->
                <div class="bg-[#262321] border border-white/5 p-6 rounded-[20px] transform rotate-3 hover:rotate-0 transition-all duration-300 shadow-2xl cursor-default translate-y-8 group/card relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-12 h-12 bg-[#FBBF24] rounded-full blur-2xl opacity-0 group-hover/card:opacity-30 transition-opacity"></div>
                    <div class="w-12 h-12 bg-[#FEF3C7] rounded-[12px] flex items-center justify-center mb-4 shadow-sm group-hover/card:scale-110 transition-transform duration-500">
                        <x-heroicon-o-clipboard-document-list class="w-6 h-6 text-[#D97706]" />
                    </div>
                    <div class="text-white text-[18px] font-bold tracking-tight mb-1.5 group-hover/card:text-[#FEF3C7] transition-colors">Customizable Quizzes</div>
                    <div class="text-white/70 text-[14px] leading-snug hover:text-white transition-colors duration-150">Multiple-choice, T/F, and Fill-in-the-blanks.</div>
                </div>

                <!-- Active Recall -->
                <div class="bg-[#262321] border border-white/5 p-6 rounded-[20px] transform rotate-1 hover:rotate-0 transition-all duration-500 shadow-xl cursor-default group/card relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-12 h-12 bg-[#10B981] rounded-full blur-2xl opacity-0 group-hover/card:opacity-30 transition-opacity"></div>
                    <div class="w-12 h-12 bg-[#D1FAE5] rounded-[12px] flex items-center justify-center mb-4 shadow-sm group-hover/card:scale-110 transition-transform duration-500">
                        <x-heroicon-o-rectangle-stack class="w-6 h-6 text-[#059669]" />
                    </div>
                    <div class="text-white text-[18px] font-bold tracking-tight mb-1.5 group-hover/card:text-[#D1FAE5] transition-colors">Active Recall</div>
                    <div class="text-white/70 text-[14px] leading-snug hover:text-white transition-colors duration-150">Interactive flashcards with mastery tracking.</div>
                </div>

                <!-- Study Analytics -->
                <div class="bg-[#262321] border border-white/5 p-6 rounded-[20px] transform -rotate-2 hover:rotate-0 transition-all duration-500 shadow-2xl cursor-default translate-y-8 group/card relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-12 h-12 bg-[#EC4899] rounded-full blur-2xl opacity-0 group-hover/card:opacity-30 transition-opacity"></div>
                    <div class="w-12 h-12 bg-[#FCE7F3] rounded-[12px] flex items-center justify-center mb-4 shadow-sm group-hover/card:scale-110 transition-transform duration-500">
                        <x-heroicon-o-chart-bar-square class="w-6 h-6 text-[#DB2777]" />
                    </div>
                    <div class="text-white text-[18px] font-bold tracking-tight mb-1.5 group-hover/card:text-[#FCE7F3] transition-colors">Study Analytics</div>
                    <div class="text-white/70 text-[14px] leading-snug hover:text-white transition-colors duration-150">Track streaks, score trends, and progress.</div>
                </div>

            </div>
        </div>

        <div></div>
    </div>

    <!-- Right Side -->
    <div class="flex-1 flex flex-col justify-center items-center p-8 lg:p-12 relative bg-white overflow-y-auto">
        
        <div class="w-full max-w-[440px]">
            <h2 class="text-[32px] md:text-[35px] font-bold font-['Inter'] leading-tight mb-2 text-[#1A1714] animate-glide-up">
                Create your account
            </h2>
            <p class="text-[#7C7167] text-[16px] mb-10 animate-glide-up delay-100">
                Already have one? <a href="{{ route('login') }}" class="text-[#6646E5] font-semibold hover:text-[#5538D4] transition-colors">Sign In</a>
            </p>

            @if ($errors->any())
                <div class="mb-8 bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3 animate-glide-up delay-150">
                    <div class="bg-red-500 text-white p-1 rounded-full flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-red-800 font-bold text-[14px]">Registration Failed</h4>
                        <ul class="text-red-700 text-[13px] mt-1 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" class="flex flex-col gap-5 relative z-10 animate-glide-up delay-200">
                @csrf
                <div class="flex flex-col md:flex-row gap-5">
                    <div class="flex flex-col gap-2 group w-full md:w-1/2">
                        <label class="text-[15px] font-semibold text-[#1A1714] group-focus-within:text-[#6646E5] transition-colors">First name</label>
                        <input type="text" name="first_name" placeholder="Juan" required class="w-full border border-[#E2DDD8] rounded-[12px] px-4 py-3.5 text-[16px] text-[#1A1714] focus:outline-none focus:border-[#6646E5] focus:ring-4 focus:ring-[#6646E5]/10 hover:border-[#C4BCB4] transition-all bg-white shadow-sm placeholder-[#9E9690]">
                    </div>
                    <div class="flex flex-col gap-2 group w-full md:w-1/2">
                        <label class="text-[15px] font-semibold text-[#1A1714] group-focus-within:text-[#6646E5] transition-colors">Last name</label>
                        <input type="text" name="last_name" placeholder="Dela Cruz" required class="w-full border border-[#E2DDD8] rounded-[12px] px-4 py-3.5 text-[16px] text-[#1A1714] focus:outline-none focus:border-[#6646E5] focus:ring-4 focus:ring-[#6646E5]/10 hover:border-[#C4BCB4] transition-all bg-white shadow-sm placeholder-[#9E9690]">
                    </div>
                </div>
                <div class="flex flex-col gap-2 group">
                    <label class="text-[15px] font-semibold text-[#1A1714] group-focus-within:text-[#6646E5] transition-colors">Email address</label>
                    <input type="email" name="email" placeholder="you@email.com" required class="w-full border border-[#E2DDD8] rounded-[12px] px-4 py-3.5 text-[16px] text-[#1A1714] focus:outline-none focus:border-[#6646E5] focus:ring-4 focus:ring-[#6646E5]/10 hover:border-[#C4BCB4] transition-all bg-white shadow-sm placeholder-[#9E9690]">
                </div>
                <div class="flex flex-col gap-2 group" x-data="{ show: false }">
                    <label class="text-[15px] font-semibold text-[#1A1714] group-focus-within:text-[#6646E5] transition-colors">Password</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="password" placeholder="Min. 8 characters" required minlength="8" class="w-full border border-[#E2DDD8] rounded-[12px] px-4 py-3.5 pr-12 text-[16px] text-[#1A1714] focus:outline-none focus:border-[#6646E5] focus:ring-4 focus:ring-[#6646E5]/10 hover:border-[#C4BCB4] transition-all bg-white shadow-sm placeholder-[#9E9690]">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#6646E5] transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg x-show="show" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </button>
                    </div>
                </div>
                <div class="flex flex-col gap-2 group mb-2" x-data="{ show: false }">
                    <label class="text-[15px] font-semibold text-[#1A1714] group-focus-within:text-[#6646E5] transition-colors">Confirm password</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="password_confirmation" placeholder="••••••••" required class="w-full border border-[#E2DDD8] rounded-[12px] px-4 py-3.5 pr-12 text-[16px] text-[#1A1714] focus:outline-none focus:border-[#6646E5] focus:ring-4 focus:ring-[#6646E5]/10 hover:border-[#C4BCB4] transition-all bg-white shadow-sm placeholder-[#9E9690]">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#6646E5] transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg x-show="show" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </button>
                    </div>
                </div>
                @if(session('success'))
                    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl font-bold text-[14px]">
                        {{ session('success') }}
                    </div>
                @endif
                <button type="submit" class="w-full bg-[#6646E5] hover:bg-[#5538D4] text-white font-semibold text-[17px] rounded-[12px] py-4 transition-all duration-300 shadow-[0_4px_15_rgba(102,70,229,0.3)] hover:shadow-[0_6px_25_rgba(102,70,229,0.5)] hover:-translate-y-0.5 flex items-center justify-center gap-2 group mt-2">
                    Create Account <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </button>
            </form>
            
            <p class="text-center text-[13px] text-[#7C7167] mt-8 animate-glide-up delay-300">
                By signing up, you agree to our <a href="{{ route('terms') }}" class="font-semibold text-[#6646E5] hover:underline">Terms</a> and <a href="{{ route('privacy') }}" class="font-semibold text-[#6646E5] hover:underline">Privacy Policy</a>.
            </p>

            <div class="mt-8 text-center relative z-10 animate-glide-up delay-300">
                <a href="{{ url('/') }}" class="text-[15px] text-[#7C7167] hover:text-[#1A1714] transition-all flex items-center justify-center gap-2 group w-max mx-auto">
                    <span class="group-hover:-translate-x-1 transition-transform">&larr;</span> Back to home
                </a>
            </div>
        </div>
    </div>

</body>
</html>