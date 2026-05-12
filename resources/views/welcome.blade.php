<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartReviewer | Study Smarter, Score Higher</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;600&family=Inter:wght@400;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#1A1714] text-white font-['Inter'] relative overflow-hidden min-h-screen flex items-center justify-center">

    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff0a_1px,transparent_1px),linear-gradient(to_bottom,#ffffff0a_1px,transparent_1px)] bg-[size:32px_32px]"></div>
        
        <div class="absolute top-[-10%] left-[-5%] w-[450px] h-[450px] bg-[#6646E5] opacity-20 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-[5%] right-[-5%] w-[350px] h-[350px] bg-[#FBBF24] opacity-15 blur-[120px] rounded-full"></div>
        <div class="absolute top-[40%] right-[20%] w-[250px] h-[250px] bg-[#60A5FA] opacity-15 blur-[100px] rounded-full"></div>
    </div>

    <div class="relative z-10 flex flex-col items-center px-6 text-center max-w-4xl mx-auto">
        
        <div class="flex items-center gap-3 mb-12">
            <div class="w-12 h-12 bg-[#6646E5] rounded-xl flex items-center justify-center shadow-lg"> 
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
            <h1 class="text-4xl font-normal tracking-tight">Smart<span style="font-weight: bold;">Reviewer</span></h1>
        </div>

        <h2 class="text-6xl md:text-[90px] font-bold leading-none tracking-tight mb-8">
            Study Smarter,<br>
            <span class="text-[#6646E5]">Score Higher.</span>
        </h2>
        
        <p class="text-xl md:text-2xl text-[#9E9690] font-['Instrument_Sans'] mb-12 max-w-2xl">
            AI-generated reviews, adaptive quizzes, and progress tracking — all in one platform.
        </p>

        <div class="flex flex-wrap justify-center gap-4 mb-16 font-['Instrument_Sans']">
            <a href="{{ route('register') }}" class="bg-[#6646E5] hover:bg-[#5538D4] text-white px-8 py-3.5 rounded-xl font-semibold text-lg transition-colors shadow-md">
                Get Started
            </a>
            <a href="{{ route('login') }}" class="bg-[#2E2B28] hover:bg-[#3A3734] text-[#F0EDE8] px-8 py-3.5 rounded-xl font-semibold text-lg transition-colors shadow-md border border-[#3A3734]">
                Sign In
            </a>
        </div>

        <div class="flex flex-wrap justify-center gap-4 font-['Instrument_Sans']">
            <div class="bg-[#D4F5E3] text-[#166534] px-4 py-2 rounded-full font-semibold text-sm tracking-wide shadow-sm">
                ✦ OCR & NLP Processing
            </div>
            
            <div class="bg-[#FEF3C7] text-[#92400E] px-4 py-2 rounded-full font-semibold text-sm tracking-wide shadow-sm">
                ✦ Adaptive Quizzes
            </div>
            
            <div class="bg-[#F0EDE8] text-[#1E40AF] px-4 py-2 rounded-full font-semibold text-sm tracking-wide shadow-sm">
                ✦ Progress Analytics
            </div>
            
            <div class="bg-[#F0EDE8] text-[#57534E] px-4 py-2 rounded-full font-semibold text-sm tracking-wide shadow-sm">
                ✦ Multi-device Access
            </div>
        </div>

    </div>

</body>
</html>