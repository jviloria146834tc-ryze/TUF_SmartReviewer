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

    <!-- Added pb-24 here to guarantee scrollable space at the very bottom -->
    <div class="max-w-[1200px] mx-auto w-full flex flex-col flex-1 pb-24">

        <div class="sticky top-0 z-50 bg-white/85 backdrop-blur-md border-b border-x border-[#E2DDD8] rounded-b-[24px] flex flex-col md:flex-row justify-between items-start md:items-center py-4 px-6 md:px-8 mb-10 shadow-sm">
            <div class="flex flex-col gap-1 mb-4 md:mb-0">
                <!-- Changed to Instrument Sans -->
                <h1 class="text-[20px] md:text-[24px] font-bold text-[#1A1714] font-['Instrument_Sans',sans-serif]">
                    {{ $quiz->title ?? 'Quiz Session' }}
                </h1>
                <span class="text-[#7C7167] text-[14px]">Assessing {{ $questions->count() }} Questions</span>
            </div>

            <div class="flex items-center gap-3">
                <div id="timer" class="bg-[#FEF3C7] text-[#92400E] px-3 py-2 rounded-full font-semibold text-[13px] tracking-wide flex items-center gap-1.5 shadow-sm">
                    ⏱ <span id="time_display">00:00</span>
                </div>
                <a href="{{ route('quizzes.index') }}" class="border border-[#E2DDD8] bg-white hover:bg-[#F9F8F6] text-[#1A1714] px-5 py-2 rounded-[10px] font-semibold text-[13px] transition-colors shadow-sm">
                    End Session
                </a>
                <button type="submit" form="quiz_form" class="bg-[#6646E5] hover:bg-[#5538D4] text-white px-5 py-2 rounded-[10px] font-semibold text-[13px] transition-colors shadow-sm">
                    Submit Quiz
                </button>
            </div>
        </div>

        <form action="{{ route('quizzes.submit', $quiz->id) }}" method="POST" id="quiz_form" class="flex-1 flex flex-col px-4 md:px-0">
            @csrf
            <input type="hidden" name="time_taken" id="time_taken" value="0">
            
            <div class="w-full max-w-[800px] mx-auto flex flex-col gap-8">
                @foreach($questions as $index => $question)
                <div class="flex flex-col gap-6">
                    <div class="bg-white border border-[#E2DDD8] rounded-[16px] p-6 md:p-8 shadow-sm">
                        <div class="flex gap-4 items-start">
                            <div class="bg-[#1A1714] text-white w-9 h-9 rounded-[10px] flex items-center justify-center font-bold text-[15px] flex-shrink-0 shadow-md">
                                {{ $loop->iteration }}
                            </div>
                            
                            <h2 class="text-[24px] md:text-[28px] font-bold text-[#1A1714] font-['Instrument_Sans',sans-serif] tracking-tight leading-tight pt-1">
                                {{ $question->question_text }}
                            </h2>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($question->options as $option)
                        @php $letter = chr(65 + $loop->index); @endphp
                        <label class="relative flex items-center gap-4 bg-white border border-[#E2DDD8] rounded-[14px] p-4 cursor-pointer hover:border-[#6646E5] hover:shadow-md transition-all has-[:checked]:border-[#6646E5] has-[:checked]:bg-[#F4F2FF] has-[:checked]:ring-1 has-[:checked]:ring-[#6646E5] group">
                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $letter }}" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                            <div class="w-8 h-8 bg-[#F9F8F6] text-[#1A1714] border border-[#E2DDD8] rounded-[8px] flex items-center justify-center font-bold text-[14px] group-hover:bg-white group-has-[:checked]:bg-[#6646E5] group-has-[:checked]:text-white group-has-[:checked]:border-[#6646E5] transition-colors flex-shrink-0">
                                {{ $letter }}
                            </div>
                            <div class="text-[#1A1714] font-medium text-[15px] tracking-tight">
                                {{ $option }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <!-- Submit Button Area -->
                <div class="mt-24 flex flex-col items-center justify-center w-full pt-16 border-t border-[#E2DDD8] pb-24">
                    <button type="submit" class="flex w-full max-w-[380px] items-center justify-center bg-[#1A1714] hover:bg-[#2E2B28] text-white py-4 rounded-[14px] font-bold text-[18px] transition-all hover:shadow-xl hover:-translate-y-0.5 cursor-pointer isolate">
                    Submit Quiz Assessment &rarr;
                    </button>
                    <p class="text-[#7C7167] text-[14px] mt-6 font-medium tracking-wide text-center">Please ensure all questions are answered before submitting.</p>
                </div> 
            </div>
        </form>

    </div>

    <script>
        let seconds = 0;
        const timeDisplay = document.getElementById('time_display');
        const timeInput = document.getElementById('time_taken');

        setInterval(() => {
            seconds++;
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            timeDisplay.innerText = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            timeInput.value = seconds;
        }, 1000);
    </script>
</body>
</html>