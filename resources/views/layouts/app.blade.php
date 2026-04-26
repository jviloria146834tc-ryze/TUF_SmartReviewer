<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartReviewer</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600&family=Inter:wght@400;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex h-screen bg-[#F0EDE8] font-['Instrument_Sans'] text-[#1A1714] overflow-hidden">

    <aside class="w-[240px] bg-[#1A1714] flex flex-col flex-shrink-0 h-full">
        
        <div class="px-6 py-8 border-b border-[#2E2B28] mb-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-[#6646E5] rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg width="97" height="97" viewBox="0 0 97 97" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_264_1514)">
                    <path d="M67 0H30C13.4315 0 0 13.4315 0 30V67C0 83.5685 13.4315 97 30 97H67C83.5685 97 97 83.5685 97 67V30C97 13.4315 83.5685 0 67 0Z" fill="#6F40EA"/>
                    <path d="M41.3653 22.7703C41.5069 22.7574 41.6486 22.7467 41.7905 22.7383C44.2531 22.5947 46.1126 23.3285 47.9286 24.9703C51.9478 28.6039 50.9669 31.9371 51.2412 36.764C52.7287 36.8275 54.3364 36.7944 55.8242 36.769C55.7701 33.8129 55.2288 30.1606 57.2678 27.9523C58.4029 26.7066 61.0674 24.0376 62.7925 23.8944C65.4362 23.6749 68.1995 27.3424 65.8733 29.6172C64.3491 31.1077 62.9748 31.0275 61.0936 31.104C60.6181 32.3189 60.7559 35.4365 60.8951 36.7675C62.0159 36.7703 63.5981 36.8105 64.6511 36.5512C65.6127 36.3215 66.677 35.4253 67.6803 35.4707C70.8447 35.6088 72.8327 39.2471 70.2819 41.5297C67.3896 44.1179 67.1336 42.4925 64.497 41.6166C64.091 41.4818 60.293 41.63 59.5439 41.6399L51.1537 41.6703C51.1266 43.9182 51.1289 46.1664 51.1605 48.4142C52.3269 48.4162 54.0042 48.4545 55.1011 48.2254C56.1675 47.9779 57.326 47.0612 58.4394 47.1003C60.3075 47.1768 62.0724 48.8771 61.9859 50.7919C61.9134 52.3976 60.422 53.8282 58.9881 54.3579C57.4379 54.9305 56.2188 53.7245 54.747 53.3973C53.5736 53.1364 52.3593 53.1044 51.155 53.0418C51.1413 55.3802 51.1443 57.7187 51.1637 60.0571C53.5822 60.0564 56.0007 60.0401 58.4191 60.0082C59.319 60.0002 60.2189 60.0004 61.1188 60.009C63.8891 60.0365 66.2122 60.2239 68.2849 62.3538C68.7311 62.8103 69.1079 63.3299 69.4033 63.8957C69.8949 64.8356 70.1414 65.8339 70.5014 66.8256C70.7611 67.5922 71.591 68.9364 71.464 69.7469C70.9599 72.9637 66.7235 74.1404 64.6221 71.5215C63.4422 70.2934 63.9536 68.7002 64.6892 67.3478C65.3359 66.1588 64.8751 65.379 63.4747 65.1867C62.034 64.9889 60.4427 65.0694 58.966 65.0691L50.9813 65.0724C48.5119 74.7166 36.3126 77.4393 30.0079 69.7779C28.0412 67.3881 27.6772 65.7189 27.9418 62.7268C26.8651 61.53 25.4755 60.8599 24.5482 59.2744C22.64 56.0122 22.3173 51.4 24.0601 47.9937C24.4367 47.2578 25.9514 45.6884 26.0566 45.2432C23.9641 42.4342 25.6554 36.623 27.7962 34.2246C29.1673 32.6885 30.369 31.9114 32.0746 30.8222C32.8414 29.2892 32.9983 28.0986 34.2227 26.5725C36.1809 24.1317 38.3566 23.1723 41.3653 22.7703Z" fill="#FDFDFC"/>
                    <path d="M41.4282 27.5916C42.0303 27.565 42.6111 27.6407 43.1842 27.8313C44.318 28.2083 45.1679 29.119 45.6791 30.1767C46.5633 32.0061 46.2255 34.6125 46.2182 36.6106C46.2105 38.7247 46.4944 41.3733 46.0196 43.4236C45.0794 47.484 40.2664 47.7671 39.5274 50.0023C39.3274 50.6073 39.4092 51.3801 39.7027 51.9433C39.9575 52.4324 40.4085 52.7893 40.9313 52.954C42.7686 53.533 44.656 52.2794 46.2279 51.4723C46.2999 53.7017 46.2305 56.0357 46.2297 58.2757C46.2292 59.8945 46.391 62.2361 46.0788 63.7452C45.5111 66.4896 43.2195 68.8227 40.3586 69.0802C37.8384 69.307 36.1645 68.3098 34.2507 66.7697C33.4747 66.0793 33.22 65.7001 32.7753 64.7764C33.8006 64.7338 34.9517 64.7463 35.8843 64.2678C36.3998 64.0033 36.7981 63.6121 36.9631 63.0463C37.77 60.2806 33.8316 60.0277 32.1092 59.2926C30.8101 58.738 29.5254 57.7722 28.8403 56.5136C28.2885 55.4997 28.0065 54.326 27.9787 53.1759C27.863 48.3972 32.0624 47.8 32.7336 45.4228C33.2367 43.6411 31.086 43.1294 30.3848 41.8765C30.0072 41.2018 30.0736 40.4488 30.29 39.732C30.7115 38.3354 31.4797 37.0478 32.7793 36.3406C34.0092 36.9666 34.2512 37.6822 35.9173 38.3246C36.6939 38.0686 37.035 37.9207 37.6435 37.3566C38.2917 35.5854 37.6623 34.3363 37.4055 32.566C37.0081 29.8264 38.8193 27.9505 41.4282 27.5916Z" fill="#6F40EA"/>
                    </g>
                    <defs>
                        <clipPath id="clip0_264_1514">
                            <rect width="97" height="97" fill="white"/>
                        </clipPath>
                    </defs>
                </svg>
                </div>
                <span class="text-[20px] font-normal font-['Inter'] tracking-tight text-white">Smart<span style="font-weight: bold;">Reviewer</span></span>
            </div>
        </div>

        <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
            
            <a href="/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->is('dashboard') ? 'bg-[#252220] text-white shadow-[inset_2px_0_0_#6646E5]' : 'text-[#9E9690] hover:text-white hover:bg-[#252220]' }}">
                <svg class="w-5 h-5 {{ request()->is('dashboard') ? 'opacity-90' : 'opacity-70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="font-medium text-[14px]">Dashboard</span>
            </a>

            <a href="/upload" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->is('upload') ? 'bg-[#252220] text-white shadow-[inset_2px_0_0_#6646E5]' : 'text-[#9E9690] hover:text-white hover:bg-[#252220]' }}">
                <svg class="w-5 h-5 {{ request()->is('upload') ? 'opacity-90' : 'opacity-70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                <span class="font-medium text-[14px]">Upload Material</span>
            </a>

            <a href="/reviewer" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->is('reviewer') ? 'bg-[#252220] text-white shadow-[inset_2px_0_0_#6646E5]' : 'text-[#9E9690] hover:text-white hover:bg-[#252220]' }}">
                <svg class="w-5 h-5 {{ request()->is('reviewer') ? 'opacity-90' : 'opacity-70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span class="font-medium text-[14px]">Reviewer</span>
            </a>

            <a href="/quizzes" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->is('quizzes') ? 'bg-[#252220] text-white shadow-[inset_2px_0_0_#6646E5]' : 'text-[#9E9690] hover:text-white hover:bg-[#252220]' }}">
                <svg class="w-5 h-5 {{ request()->is('quizzes') ? 'opacity-90' : 'opacity-70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <span class="font-medium text-[14px]">Quizzes</span>
            </a>

            <a href="/results" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->is('results') ? 'bg-[#252220] text-white shadow-[inset_2px_0_0_#6646E5]' : 'text-[#9E9690] hover:text-white hover:bg-[#252220]' }}">
                <svg class="w-5 h-5 {{ request()->is('results') ? 'opacity-90' : 'opacity-70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span class="font-medium text-[14px]">Results</span>
            </a>
            
        </nav>

        <div class="p-6 border-t border-[#2E2B28] mt-auto">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-[34px] h-[34px] rounded-full bg-[#6646E5] text-white flex items-center justify-center font-bold text-[12px]">
                    {{ auth()->check() ? substr(auth()->user()->first_name, 0, 1) . substr(auth()->user()->last_name, 0, 1) : 'WS' }}
                </div>
                <div class="flex flex-col">
                    <span class="text-white font-semibold text-[13px]">
                        {{ auth()->check() ? auth()->user()->first_name . ' ' . auth()->user()->last_name : 'Wyndy Shane' }}
                    </span>
                    <span class="text-[#9E9690] text-[11px]">User</span>
                </div>
            </div>
            
            <form method="POST" action="/logout">
                @csrf
                <a href="/logout-success" class="flex items-center gap-3 px-4 py-3 text-[#A39D98] hover:text-white hover:bg-white/5 rounded-xl transition-all mb-4 group">
                    <svg class="w-5 h-5 text-[#A39D98] group-hover:text-[#FCA5A5] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span class="font-medium text-[15px] group-hover:text-[#FCA5A5] transition-colors">Sign Out</span>
                </a>
            </form>
        </div>

    </aside>

    <main class="flex-1 overflow-y-auto p-8">
        @yield('content')
    </main>

</body>
</html>