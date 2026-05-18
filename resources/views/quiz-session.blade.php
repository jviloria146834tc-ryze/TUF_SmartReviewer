@extends('layouts.app')

@section('title', 'Quiz Session | ' . ($quiz->title ?? 'SmartReviewer'))

@section('content')
<div x-data="quizSession()" class="max-w-3xl mx-auto w-full py-8 px-4 flex flex-col relative">
    
    <!-- Header & Timer -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-6 animate-glide-up">
        <div class="flex-1 min-w-0">
            <a href="{{ route('quizzes.index') }}" @click="saveProgress(false)" class="text-[#7C7167] text-[13px] font-semibold hover:text-[#1A1714] flex items-center gap-1 mb-2 transition-colors">
                &larr; Save & Exit
            </a>
            <h1 class="text-[24px] md:text-[28px] font-bold text-[#1A1714] tracking-tight font-['Inter'] break-words">
                {{ $quiz->title ?? 'Quiz Session' }}
            </h1>
        </div>

        <div class="flex items-center gap-3 shrink-0 mb-1.5">
            <div id="timer" class="bg-[#FEF3C7] text-[#92400E] px-4 py-2 rounded-full font-semibold text-[14px] tracking-wide flex items-center justify-center gap-2 shadow-sm min-w-[100px]">
                ⏱ <span x-text="formattedTime">00:00</span>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="space-y-3 mb-8 animate-glide-up delay-100">
        <div class="flex justify-between items-end text-[13px] font-bold text-[#1A1714]">
            <span class="text-[#6646E5]" x-text="'ITEM ' + (currentIndex + 1) + ' OF ' + questions.length + ' ITEMS'"></span>
            <span class="text-[#7C7167]" x-text="Math.round(((currentIndex + 1) / questions.length) * 100) + '%'"></span>
        </div>
        <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden border border-[#E2DDD8]/50">
            <div class="h-full bg-gradient-to-r from-[#6646E5] to-[#8B5CF6] transition-all duration-500 ease-out"
                 :style="'width: ' + (((currentIndex + 1) / questions.length) * 100) + '%'"></div>
        </div>
    </div>

    <!-- Active Question Card -->
    <div class="w-full relative animate-glide-up delay-100">
        
        <form action="{{ route('quizzes.submit', $quiz->id) }}" method="POST" id="quiz_form" @submit="saveProgress(true)">
            @csrf
            <input type="hidden" name="time_taken" :value="seconds">
            
            <template x-if="questions.length > 0">
                <div class="bg-white border-2 border-[#E2DDD8] rounded-[32px] p-8 md:p-12 shadow-xl flex flex-col mb-8">
                    <div class="flex gap-4 items-start mb-8">
                        <div class="bg-[#1A1714] text-white w-10 h-10 rounded-[12px] flex items-center justify-center font-bold text-[16px] flex-shrink-0 shadow-md">
                            <span x-text="currentIndex + 1"></span>
                        </div>
                        <h2 class="text-[22px] md:text-[26px] font-bold text-[#1A1714] font-['Inter',sans-serif] tracking-tight leading-snug pt-1" x-text="questions[currentIndex].question_text"></h2>
                    </div>

                    <!-- Options (Multiple Choice or True/False) -->
                    <template x-if="questions[currentIndex].type === 'multiple_choice' || questions[currentIndex].type === 'true_false' || (questions[currentIndex].type === 'mixed' && (questions[currentIndex].options && (Array.isArray(questions[currentIndex].options) ? questions[currentIndex].options.length > 0 : Object.keys(questions[currentIndex].options).length > 0)))">
                        <div class="grid grid-cols-1 gap-4">
                            <template x-for="(option, optIndex) in (Array.isArray(questions[currentIndex].options) ? questions[currentIndex].options : Object.values(questions[currentIndex].options || {}))" :key="optIndex">
                                <label @click="setAnswer(questions[currentIndex].id, option)" class="relative flex items-center gap-4 bg-white border border-[#E2DDD8] rounded-[16px] p-5 cursor-pointer hover:border-[#6646E5] hover:shadow-md transition-all group"
                                    :class="answers[questions[currentIndex].id] === option ? 'border-[#6646E5] bg-[#F4F2FF] ring-1 ring-[#6646E5]' : ''">
                                    <input type="radio" :name="'answers[' + questions[currentIndex].id + ']'" :value="option" :checked="answers[questions[currentIndex].id] === option" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    
                                    <div class="w-10 h-10 rounded-[10px] flex items-center justify-center font-bold text-[15px] transition-colors flex-shrink-0"
                                        :class="answers[questions[currentIndex].id] === option ? 'bg-[#1A1714] text-white border border-[#1A1714]' : 'bg-[#F9F8F6] text-[#1A1714] border border-[#E2DDD8] group-hover:bg-white'"
                                        x-text="questions[currentIndex].type === 'true_false' ? String(option).substring(0, 1) : String.fromCharCode(65 + Number(optIndex))">
                                    </div>
                                    <div class="text-[#1A1714] font-semibold text-[16px] tracking-tight" x-text="option"></div>
                                </label>
                            </template>
                        </div>
                    </template>

                    <!-- Fill in the blank -->
                    <template x-if="questions[currentIndex].type === 'fill_in_the_blank' || (questions[currentIndex].type === 'mixed' && (!questions[currentIndex].options || (Array.isArray(questions[currentIndex].options) ? questions[currentIndex].options.length === 0 : Object.keys(questions[currentIndex].options).length === 0)))">
                        <div class="w-full mt-4">
                            <input type="text" :name="'answers[' + questions[currentIndex].id + ']'" :value="answers[questions[currentIndex].id]" @input="setAnswer(questions[currentIndex].id, $event.target.value)" @keydown.enter.prevent @blur="saveProgress(false)" placeholder="Type your exact answer here..." class="w-full bg-[#F9F8F6] border-2 border-[#E2DDD8] rounded-[16px] p-5 text-[18px] font-semibold text-[#1A1714] focus:outline-none focus:border-[#6646E5] focus:bg-white focus:ring-4 focus:ring-[#6646E5]/10 transition-all shadow-inner">
                        </div>
                    </template>
                </div>
            </template>

            <!-- Hidden Inputs to ensure all answers are always in the DOM for submission -->
            <template x-for="(value, key) in answers" :key="'hidden-'+key">
                <input type="hidden" :name="'answers[' + key + ']'" :value="value">
            </template>
        </form>

        <!-- Controls -->
        <div class="flex items-center justify-between gap-4 mt-auto mb-12">
            <button type="button" @click="prevQuestion()" :disabled="currentIndex === 0" class="flex-1 bg-white border border-[#E2DDD8] hover:bg-[#F9F8F6] text-[#1A1714] px-6 py-4 rounded-[18px] font-bold text-[15px] transition-all flex items-center justify-center gap-2 disabled:opacity-30 disabled:cursor-not-allowed shadow-sm">
                <x-heroicon-o-chevron-left class="w-5 h-5" />
                Previous
            </button>
            
            <template x-if="currentIndex < questions.length - 1">
                <button type="button" @click="nextQuestion()" class="flex-1 bg-[#1A1714] hover:bg-[#2E2B28] text-white px-6 py-4 rounded-[18px] font-bold text-[15px] transition-all flex items-center justify-center gap-2 shadow-sm">
                    Next
                    <x-heroicon-o-chevron-right class="w-5 h-5" />
                </button>
            </template>

            <template x-if="currentIndex === questions.length - 1">
                <button type="submit" form="quiz_form" :disabled="!allAnswered" class="flex-1 bg-[#6646E5] hover:bg-[#5538D4] text-white px-6 py-4 rounded-[18px] font-bold text-[15px] transition-all shadow-lg hover:shadow-[#6646E5]/40 hover:-translate-y-0.5 flex items-center justify-center gap-2 cursor-pointer isolate disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:hover:shadow-none">
                    <span x-text="allAnswered ? 'Submit Quiz' : 'Answer all questions to submit'"></span>
                    <x-heroicon-o-check-circle class="w-5 h-5" x-show="allAnswered" />
                </button>
            </template>
        </div>

    </div>

    <!-- Hidden Inputs for saving progress -->
    <form id="save_form" class="hidden">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        function quizSession() {
            return {
                currentIndex: 0,
                seconds: {{ $attempt->time_taken ?? 0 }},
                formattedTime: '00:00',
                questions: @json($questions),
                answers: @json($attempt->answers_json ?? new stdClass()),
                attemptId: {{ $attempt->id }},
                lastSaveTime: Date.now(),
                
                get allAnswered() {
                    return this.questions.every(q => this.answers[q.id] !== undefined && this.answers[q.id] !== null && this.answers[q.id].toString().trim() !== '');
                },

                init() {
                    this.updateTimeDisplay();
                    setInterval(() => {
                        this.seconds++;
                        this.updateTimeDisplay();
                    }, 1000);
                    
                    // Ensure answers is an object
                    if (Array.isArray(this.answers) && this.answers.length === 0) {
                        this.answers = {};
                    } else if (typeof this.answers === 'object' && this.answers !== null) {
                         for (const [key, value] of Object.entries(this.answers)) {
                             if (typeof value === 'object' && value !== null && 'user_answer' in value) {
                                 this.answers[key] = value.user_answer;
                             }
                         }
                    }

                    // Start at first unanswered question if possible
                    for(let i=0; i<this.questions.length; i++) {
                        if(!this.answers[this.questions[i].id]) {
                            this.currentIndex = i;
                            break;
                        }
                    }
                },

                setAnswer(questionId, value) {
                    this.answers[questionId] = value;
                    this.saveProgress(false);
                },

                updateTimeDisplay() {
                    const mins = Math.floor(this.seconds / 60);
                    const secs = this.seconds % 60;
                    this.formattedTime = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                },

                nextQuestion() {
                    this.saveProgress(false);
                    if (this.currentIndex < this.questions.length - 1) {
                        this.currentIndex++;
                    }
                },

                prevQuestion() {
                    if (this.currentIndex > 0) {
                        this.currentIndex--;
                    }
                },

                async saveProgress(isSubmitting) {
                    this.lastSaveTime = Date.now();
                    const csrfToken = document.querySelector('input[name="_token"]').value;
                    
                    if (!isSubmitting) {
                        try {
                            await axios.post(`/quiz/attempt/${this.attemptId}/save`, {
                                answers: this.answers,
                                time_taken: this.seconds
                            }, {
                                headers: { 'X-CSRF-TOKEN': csrfToken }
                            });
                        } catch (error) {
                            console.error('Failed to auto-save progress', error);
                        }
                    }
                }
            }
        }
    </script>
</div>
@endsection
