<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartReviewer | Quiz Session</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;600;700&family=Inter:wght@400;700&family=Syne:wght@600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F0EDE8] min-h-screen flex flex-col font-['Instrument_Sans']">

    <div class="max-w-[1200px] mx-auto w-full pb-12 flex flex-col flex-1">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center pt-8 pb-6 px-6 md:px-10">
            <div class="flex flex-col gap-1 mb-4 md:mb-0">
                <h1 class="text-[18px] md:text-[20px] font-bold text-[#1A1714]">
                    Python Fundamentals — Syntax & Logic
                </h1>
                <span class="text-[#7C7167] text-[14px]">Question 3 of 10</span>
            </div>

            <div class="flex items-center gap-4">
                <div class="bg-[#FEF3C7] text-[#92400E] px-3 py-1.5 rounded-full font-semibold text-[13px] tracking-wide flex items-center gap-1.5 shadow-sm">
                    ⏱ 07:42
                </div>
                <a href="/quizzes" class="border border-[#E2DDD8] bg-white hover:bg-[#F9F8F6] text-[#1A1714] px-5 py-2 rounded-[10px] font-semibold text-[13px] transition-colors shadow-sm">
                    End Session
                </a>
            </div>
        </div>

        <div class="w-full bg-[#E2DDD8] h-[4px]">
            <div class="bg-[#6646E5] h-full transition-all duration-500 rounded-r-full" style="width: 30%"></div>
        </div>

        <div class="flex-1 flex flex-col justify-center items-center px-4 md:px-0 mt-8 md:mt-12">
            <div class="w-full max-w-[680px]">
                
                <div class="bg-white border border-[#E2DDD8] rounded-[16px] p-6 md:p-8 mb-8 shadow-sm">
                    <div class="flex gap-4 items-start">
                        <div class="bg-[#1A1714] text-white w-9 h-9 rounded-[10px] flex items-center justify-center font-bold text-[15px] flex-shrink-0 shadow-md">
                            Q3
                        </div>
                        <h2 class="text-[20px] md:text-[22px] font-bold text-[#1A1714] font-['Syne',sans-serif] leading-snug pt-1">
                            What is the correct way to define a function in Python?
                        </h2>
                    </div>
                </div>

                <form action="/quiz-submit" method="POST" class="flex flex-col gap-4">
                    @csrf
                    
                    @php
                        $options = [
                            ['letter' => 'A', 'code' => 'function myFunc():'],
                            ['letter' => 'B', 'code' => 'def myFunc():'],
                            ['letter' => 'C', 'code' => 'func myFunc():'],
                            ['letter' => 'D', 'code' => 'define myFunc():'],
                        ];
                    @endphp

                    @foreach($options as $option)
                    <label class="relative flex items-center gap-4 bg-white border border-[#E2DDD8] rounded-[14px] p-4 cursor-pointer hover:border-[#6646E5] hover:shadow-md transition-all has-[:checked]:border-[#6646E5] has-[:checked]:bg-[#F4F2FF] has-[:checked]:ring-1 has-[:checked]:ring-[#6646E5] group">
                        <input type="radio" name="answer" value="{{ $option['letter'] }}" class="absolute opacity-0 w-0 h-0" required>
                        <div class="w-8 h-8 bg-[#F9F8F6] text-[#1A1714] border border-[#E2DDD8] rounded-[8px] flex items-center justify-center font-bold text-[14px] group-hover:bg-white group-has-[:checked]:bg-[#6646E5] group-has-[:checked]:text-white group-has-[:checked]:border-[#6646E5] transition-colors flex-shrink-0">
                            {{ $option['letter'] }}
                        </div>
                        <div class="text-[#1A1714] font-mono text-[15px] tracking-tight">
                            {{ $option['code'] }}
                        </div>
                    </label>
                    @endforeach

                    <div class="flex items-center justify-between mt-8 pt-6">
                        <button type="button" class="bg-white border border-[#E2DDD8] hover:bg-[#F9F8F6] text-[#1A1714] px-6 py-2.5 rounded-[10px] font-semibold text-[14px] transition-colors shadow-sm">
                            &larr; Previous
                        </button>
                        
                        <span class="text-[#7C7167] font-medium text-[14px]">
                            3 / 10 answered
                        </span>
                        
                        <button type="submit" class="bg-[#1A1714] hover:bg-[#2E2B28] text-white px-8 py-2.5 rounded-[10px] font-semibold text-[14px] transition-colors shadow-md flex items-center gap-2">
                            Next &rarr;
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</body>
</html>