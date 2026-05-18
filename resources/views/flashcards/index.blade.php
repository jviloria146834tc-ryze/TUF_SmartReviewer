@extends('layouts.app')

@section('title', 'Flashcards: ' . $material->title)

@section('content')
<div x-data="flashcardSession()" class="max-w-4xl mx-auto w-full py-8 px-4 h-full flex flex-col relative">
    
    <!-- Progress Overlay for Generation -->
    <div x-show="isGenerating" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-[#F0EDE8]/90 backdrop-blur-sm z-[150] flex flex-col items-center justify-center">
        
        <div class="w-full max-w-md bg-white p-10 rounded-[32px] shadow-2xl border border-[#E2DDD8] relative overflow-hidden">
            <h2 class="text-[26px] font-bold text-[#1A1714] mb-8 font-['Inter'] text-center tracking-tight" x-text="showRegenerateModal ? 'Regenerating Deck' : 'Generating Flashcards'"></h2>
            
            <div class="space-y-8">
                <!-- Step 1: Crafting -->
                <div class="flex items-center gap-5 transition-all duration-500" :class="progressPercent < 95 ? 'opacity-100' : 'opacity-50'">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500"
                         :class="progressPercent < 95 ? 'bg-[#6646E5] text-white shadow-lg shadow-[#6646E5]/20' : 'bg-[#D4F5E3] text-[#166534]'">
                        <template x-if="progressPercent < 95">
                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                        <template x-if="progressPercent >= 95">
                            <x-heroicon-s-check class="w-6 h-6" />
                        </template>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-[16px] transition-colors" :class="progressPercent < 95 ? 'text-[#1A1714]' : 'text-[#7C7167]'">Phase: Crafting</span>
                        <span class="text-[13px] font-medium text-[#7C7167]">AI is designing your flashcards...</span>
                    </div>
                </div>

                <!-- Step 2: Finalizing -->
                <div class="flex items-center gap-5 transition-all duration-500" :class="progressPercent >= 95 ? 'opacity-100' : 'opacity-40'">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500"
                         :class="progressPercent === 100 ? 'bg-[#D4F5E3] text-[#166534]' : (progressPercent >= 95 ? 'bg-[#6646E5] text-white shadow-lg shadow-[#6646E5]/20' : 'bg-gray-100 text-gray-400')">
                        <template x-if="progressPercent >= 95 && progressPercent < 100">
                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                        <template x-if="progressPercent < 95 || progressPercent === 100">
                            <template x-if="progressPercent === 100">
                                <x-heroicon-s-check class="w-6 h-6" />
                            </template>
                            <template x-if="progressPercent < 95">
                                <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                            </template>
                        </template>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-[16px] transition-colors" :class="progressPercent >= 95 ? 'text-[#1A1714]' : 'text-[#7C7167]'">Phase: Ready</span>
                        <span class="text-[13px] font-medium text-[#7C7167]">Optimizing your recall deck...</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-10 flex flex-col gap-2">
                <div class="flex justify-between items-end">
                    <span class="text-[12px] font-black uppercase tracking-widest text-[#6646E5]" x-text="progressPercent < 95 ? 'Crafting' : 'Finalizing'"></span>
                    <span class="text-[20px] font-black text-[#1A1714]" x-text="progressPercent + '%'"></span>
                </div>
                <div class="w-full bg-[#F0EDE8] h-3 rounded-full overflow-hidden p-0.5">
                    <div class="h-full bg-[#6646E5] rounded-full transition-all duration-500 ease-out shadow-[0_0_10px_rgba(102,70,229,0.4)]"
                         :style="'width: ' + progressPercent + '%'"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- All Mastered Overlay -->
    <div x-show="allMastered" x-cloak 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-[#F0EDE8]/90 backdrop-blur-sm z-[140] flex flex-col items-center justify-center">
        
        <div class="bg-white p-10 rounded-[32px] text-center max-w-md mx-auto shadow-2xl border border-[#E2DDD8] animate-glide-up">
            <div class="w-20 h-20 bg-[#F4F2FF] rounded-[24px] flex items-center justify-center text-[#6646E5] mb-6 mx-auto">
                <x-heroicon-s-trophy class="w-10 h-10" />
            </div>
            <h2 class="text-[24px] font-bold text-[#1A1714] mb-3">All Cards Mastered!</h2>
            <p class="text-[#7C7167] text-[15px] mb-8 leading-relaxed">You've successfully mastered all flashcards in this deck. Great job!</p>
            <div class="flex flex-col gap-3">
                <button @click="resetMastery()" :disabled="isResetting" class="bg-[#1A1714] hover:bg-[#2E2B28] text-white px-6 py-3.5 rounded-[16px] font-bold text-[15px] transition-all flex items-center justify-center gap-2 disabled:opacity-50">
                    <span x-show="!isResetting">Study Again (Reset)</span>
                    <span x-show="isResetting" class="flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Resetting...
                    </span>
                </button>
                <button @click="showRegenerateModal = true" class="bg-white border border-[#E2DDD8] hover:bg-[#F9F8F6] text-[#1A1714] px-6 py-3.5 rounded-[16px] font-bold text-[15px] transition-all flex items-center justify-center gap-2">
                    <x-heroicon-o-sparkles class="w-5 h-5 text-[#6646E5]" />
                    Regenerate Deck
                </button>
                <a href="{{ route('reviewer', $material->id) }}" class="text-[#7C7167] hover:text-[#1A1714] font-semibold text-[14px] transition-colors py-2">
                    Return to Reviewer
                </a>
            </div>
        </div>
    </div>

    <!-- Header & Timer -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-6 animate-glide-up">
        <div>
            <a href="{{ route('reviewer', $material->id) }}" class="text-[#7C7167] text-[13px] font-semibold hover:text-[#1A1714] flex items-center gap-1 mb-2 transition-colors">
                &larr; Back to Reviewer
            </a>
            <h1 class="text-[28px] md:text-[32px] font-bold text-[#1A1714] tracking-tight font-['Inter']">
                {{ $material->title }}
            </h1>
        </div>

        <div class="flex items-center gap-3">
            <div class="bg-white border border-[#E2DDD8] rounded-full px-6 py-3 flex items-center gap-4 shadow-sm">
                <div class="flex items-center gap-2 text-[#6646E5] font-bold text-[14px]">
                    <x-heroicon-o-check-circle class="w-5 h-5" />
                    <span x-text="masteredCount + ' Mastered'"></span>
                </div>
            </div>

            @if($flashcards->isNotEmpty())
            <button @click="showRegenerateModal = true" class="bg-white border border-[#E2DDD8] hover:bg-[#F9F8F6] text-[#1A1714] p-3 rounded-full shadow-sm transition-all group flex items-center justify-center" title="Regenerate Deck">
                <x-heroicon-o-arrow-path class="w-5 h-5 text-[#6646E5] group-hover:rotate-180 transition-transform duration-500" />
            </button>
            @endif
        </div>
    </div>

    @if($flashcards->isEmpty())
        <!-- Empty State / Generator -->
        <div class="flex-1 flex flex-col items-center justify-center py-20 text-center bg-white rounded-[32px] border border-[#E2DDD8] shadow-sm animate-glide-up delay-100">
            <div class="w-20 h-20 bg-[#F4F2FF] rounded-[24px] flex items-center justify-center text-[#6646E5] mb-6">
                <x-heroicon-o-rectangle-stack class="w-10 h-10" />
            </div>
            <h2 class="text-[32px] font-bold text-[#1A1714] mb-3">No Flashcards Yet</h2>
            <p class="text-[#7C7167] text-[17px] max-w-sm mb-10 leading-relaxed font-medium">
                Unlock active recall by generating study cards for this material.
            </p>
            
            <div class="flex flex-col gap-5 mb-10 w-full max-w-sm">
                <label class="text-[14px] font-bold text-[#1A1714] uppercase tracking-wider">How many cards?</label>
                <div class="grid grid-cols-5 gap-2">
                    <template x-for="count in [10, 20, 30, 40, 50]">
                        <button type="button" 
                                @click="numCards = count"
                                :disabled="(count >= 30 && contentCharacterCount < 1000) || (count >= 50 && contentCharacterCount < 2000)"
                                :class="{
                                    'bg-[#6646E5] text-white border-[#6646E5] shadow-md shadow-[#6646E5]/20': numCards == count,
                                    'bg-white text-[#1A1714] border-[#E2DDD8] hover:border-[#6646E5] hover:text-[#6646E5]': numCards != count,
                                    'opacity-40 cursor-not-allowed grayscale': (count >= 30 && contentCharacterCount < 1000) || (count >= 50 && contentCharacterCount < 2000)
                                }"
                                class="py-3 border-2 rounded-[14px] font-bold text-[14px] transition-all">
                            <span x-text="count"></span>
                        </button>
                    </template>
                </div>
                <p x-show="contentCharacterCount < 1000" class="text-[11px] text-orange-600 font-semibold flex items-center justify-center gap-1">
                    <x-heroicon-s-exclamation-circle class="w-3.5 h-3.5" />
                    Higher counts require longer material
                </p>
            </div>

            <button @click="generateCards()" :disabled="isGenerating" class="bg-[#1A1714] hover:bg-[#2E2B28] text-white px-10 py-4 rounded-[20px] font-bold text-[16px] transition-all shadow-xl flex items-center gap-3 disabled:opacity-50 hover:-translate-y-1">
                <x-heroicon-o-sparkles class="w-6 h-6 text-[#A78BFA]" />
                Generate Flashcards &rarr;
            </button>
        </div>
    @else
        <!-- Flashcard Interface -->
        <div class="flex-1 flex flex-col gap-8 max-w-2xl mx-auto w-full relative animate-glide-up delay-100">
            
            <!-- Progress Bar -->
            <div class="space-y-3">
                <div class="flex justify-between items-end text-[13px] font-bold text-[#1A1714]">
                    <span class="text-[#6646E5]" x-text="'CARD ' + (currentIndex + 1) + ' OF ' + cards.length"></span>
                    <span class="text-[#7C7167]" x-text="Math.round(((currentIndex + 1) / cards.length) * 100) + '%'"></span>
                </div>
                <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden border border-[#E2DDD8]/50">
                    <div class="h-full bg-gradient-to-r from-[#6646E5] to-[#8B5CF6] transition-all duration-500 ease-out"
                         :style="'width: ' + (((currentIndex + 1) / cards.length) * 100) + '%'"></div>
                </div>
            </div>

            <!-- The Card -->
            <div class="relative h-[450px] w-full perspective-1000 group">
                <div class="relative w-full h-full transition-transform duration-700 transform-style-3d"
                     :class="isFlipped ? 'rotate-y-180' : ''"
                     @click="isFlipped = !isFlipped">
                    
                    <!-- Front -->
                    <div class="absolute inset-0 w-full h-full backface-hidden bg-white border-2 border-[#E2DDD8] rounded-[32px] p-8 md:p-16 flex flex-col items-center justify-center text-center shadow-xl group-hover:border-[#6646E5] transition-all cursor-pointer">
                        <div class="absolute top-8 left-1/2 -translate-x-1/2 bg-[#F4F2FF] text-[#6646E5] px-4 py-1.5 rounded-full text-[10px] font-black tracking-[0.2em] uppercase border border-[#6646E5]/10 z-20">
                            Question
                        </div>
                        <div class="flex-1 flex items-center justify-center w-full mt-4">
                            <h2 class="text-[24px] md:text-[32px] font-bold text-[#1A1714] leading-[1.4] font-['Inter'] break-words max-w-full" x-text="cards[currentIndex].front"></h2>
                        </div>
                        <div class="mt-8 text-[12px] font-bold text-[#9E9690] uppercase tracking-widest flex items-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                            <x-heroicon-o-arrow-path class="w-4 h-4 animate-pulse" />
                            Click to reveal answer
                        </div>
                    </div>

                    <!-- Back -->
                    <div class="absolute inset-0 w-full h-full backface-hidden rotate-y-180 bg-[#1A1714] border-2 border-[#2E2B28] rounded-[32px] p-8 md:p-16 flex flex-col items-center justify-center text-center shadow-2xl relative overflow-hidden cursor-pointer">
                        <div class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff0a_1px,transparent_1px),linear-gradient(to_bottom,#ffffff0a_1px,transparent_1px)] bg-[size:24px_24px]"></div>
                        <div class="absolute top-8 left-1/2 -translate-x-1/2 bg-[#F4F2FF] text-[#6646E5] px-4 py-1.5 rounded-full text-[10px] font-black tracking-[0.2em] uppercase border border-[#6646E5]/10 z-20">
                            Answer
                        </div>
                        <div class="flex-1 flex items-center justify-center w-full mt-4 relative z-10">
                            <p class="text-[20px] md:text-[26px] font-medium text-white leading-[1.6] break-words max-w-full" x-text="cards[currentIndex].back"></p>
                        </div>
                        <div class="mt-8 text-[12px] font-bold text-[#6646E5] uppercase tracking-widest flex items-center gap-2 relative z-10 opacity-80 group-hover:opacity-100 transition-opacity">
                            <x-heroicon-o-arrow-path class="w-4 h-4" />
                            Click to flip back
                        </div>
                    </div>
                </div>
            </div>

            <!-- Controls -->
            <div class="flex flex-col gap-6 relative z-30">
                <div class="flex items-center justify-between gap-4">
                    <button @click="prevCard()" :disabled="currentIndex === 0" class="flex-1 bg-white border border-[#E2DDD8] hover:bg-[#F9F8F6] text-[#1A1714] px-6 py-4 rounded-[18px] font-bold text-[15px] transition-all flex items-center justify-center gap-2 disabled:opacity-30 disabled:cursor-not-allowed shadow-sm">
                        <x-heroicon-o-chevron-left class="w-5 h-5" />
                        Previous
                    </button>
                    
                    <button type="button" @click.stop="toggleMastery()" 
                            class="w-16 h-16 rounded-full border-2 transition-all flex items-center justify-center shadow-lg hover:scale-110 active:scale-95 shrink-0 bg-white"
                            :class="cards[currentIndex].is_mastered ? 'border-[#6646E5] text-[#6646E5]' : 'border-[#E2DDD8] text-[#9E9690] hover:border-[#6646E5] hover:text-[#6646E5]'">
                        <div x-show="cards[currentIndex].is_mastered">
                            <x-heroicon-s-check-circle class="w-10 h-10" />
                        </div>
                        <div x-show="!cards[currentIndex].is_mastered">
                            <x-heroicon-o-check-circle class="w-10 h-10" />
                        </div>
                    </button>

                    <button @click="nextCard()" :disabled="currentIndex === cards.length - 1" class="flex-1 bg-[#1A1714] hover:bg-[#2E2B28] text-white px-6 py-4 rounded-[18px] font-bold text-[15px] transition-all flex items-center justify-center gap-2 disabled:opacity-30 disabled:cursor-not-allowed shadow-sm">
                        Next
                        <x-heroicon-o-chevron-right class="w-5 h-5" />
                    </button>
                </div>

                <!-- Mastery Status Info -->
                <div class="text-center">
                    <span class="text-[13px] font-bold transition-colors" :class="cards[currentIndex].is_mastered ? 'text-[#6646E5]' : 'text-[#7C7167]'">
                        <span x-show="cards[currentIndex].is_mastered">✨ You've mastered this card!</span>
                        <span x-show="!cards[currentIndex].is_mastered">Click the checkmark if you're confident in this concept</span>
                    </span>
                </div>
            </div>

        </div>
    @endif

    <!-- Regenerate Modal -->
    <div x-show="showRegenerateModal" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center overflow-y-auto bg-[#1A1714]/60 backdrop-blur-sm" style="display: none;">
        <div @click.away="showRegenerateModal = false" class="relative w-full max-w-lg p-8 mx-4 bg-white border border-[#E2DDD8] rounded-[28px] shadow-2xl">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-[22px] font-bold text-[#1A1714] font-['Inter']">Regenerate Deck</h3>
                <button @click="showRegenerateModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="space-y-8">
                <p class="text-[#7C7167] text-[15px] font-medium leading-relaxed">
                    Generating a new deck will replace your current cards. This is great for refreshing your knowledge or focusing on different aspects of the material.
                </p>

                <div class="flex flex-col gap-4">
                    <label class="text-[14px] font-bold text-[#1A1714] uppercase tracking-wider">Number of cards?</label>
                    <div class="grid grid-cols-5 gap-2">
                        <template x-for="count in [10, 20, 30, 40, 50]">
                            <button type="button" 
                                    @click="numCards = count"
                                    :disabled="(count >= 30 && contentCharacterCount < 1000) || (count >= 50 && contentCharacterCount < 2000)"
                                    :class="{
                                        'bg-[#6646E5] text-white border-[#6646E5] shadow-md shadow-[#6646E5]/20': numCards == count,
                                        'bg-white text-[#1A1714] border-[#E2DDD8] hover:border-[#6646E5] hover:text-[#6646E5]': numCards != count,
                                        'opacity-40 cursor-not-allowed grayscale': (count >= 30 && contentCharacterCount < 1000) || (count >= 50 && contentCharacterCount < 2000)
                                    }"
                                    class="py-3 border-2 rounded-[14px] font-bold text-[14px] transition-all">
                            <span x-text="count"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-[#E2DDD8] flex justify-end gap-3">
                <button type="button" @click="showRegenerateModal = false" class="px-6 py-3 text-[#7C7167] font-bold text-[14px] hover:text-[#1A1714] transition-colors">Cancel</button>
                <button type="button" @click="generateCards()" :disabled="isGenerating" class="bg-[#1A1714] hover:bg-[#2E2B28] text-white font-bold px-10 py-3.5 rounded-[16px] shadow-xl hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    Regenerate &rarr;
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        function flashcardSession() {
            return {
                currentIndex: 0,
                isFlipped: false,
                isGenerating: false,
                progressPercent: 0,
                progressInterval: null,
                isResetting: false,
                showRegenerateModal: false,
                numCards: 10,
                contentCharacterCount: @json($material ? strlen($material->raw_content ?? '') : 0),
                cards: {!! json_encode($flashcards) !!},
                
                init() {
                },

                get masteredCount() {
                    return this.cards.filter(c => c.is_mastered).length;
                },

                get allMastered() {
                    // Check if all cards in the deck are mastered
                    return this.cards.length > 0 && this.masteredCount === this.cards.length;
                },

                nextCard() {
                    if (this.currentIndex < this.cards.length - 1) {
                        this.isFlipped = false;
                        setTimeout(() => this.currentIndex++, 150);
                    }
                },

                prevCard() {
                    if (this.currentIndex > 0) {
                        this.isFlipped = false;
                        setTimeout(() => this.currentIndex--, 150);
                    }
                },

                async generateCards() {
                    this.isGenerating = true;
                    this.progressPercent = 0;
                    
                    this.progressInterval = setInterval(() => {
                        if (this.progressPercent < 95) {
                            this.progressPercent += Math.floor(Math.random() * 5) + 2;
                        }
                    }, 600);

                    try {
                        const response = await axios.post(`/materials/{{ $material->id }}/generate-flashcards`, {
                            num_cards: this.numCards
                        }, {
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        });
                        
                        if (response.data.success) {
                            this.progressPercent = 100;
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        }
                    } catch (error) {
                        clearInterval(this.progressInterval);
                        alert('Failed to generate flashcards.');
                        console.error(error);
                        this.isGenerating = false;
                    }
                },

                async toggleMastery() {
                    const index = this.currentIndex;
                    const card = this.cards[index];
                    const originalState = !!card.is_mastered;
                    
                    // Toggle the state
                    const newState = !originalState;
                    
                    // Robust reactivity update
                    this.cards[index] = { ...this.cards[index], is_mastered: newState };
                    
                    // If we just mastered the card and it's not the last card, 
                    // auto-advance to the next card after a brief delay
                    if (newState && index < this.cards.length - 1) {
                        setTimeout(() => {
                            if (this.currentIndex === index) { // Only advance if user hasn't moved manually
                                this.nextCard();
                            }
                        }, 600);
                    }

                    try {
                        const response = await axios.post(`/flashcards/${card.id}/toggle-mastery`, {}, {
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        });
                        if (!response.data.success) {
                            // Revert on failure
                            this.cards[index] = { ...this.cards[index], is_mastered: originalState };
                        }
                    } catch (error) {
                        this.cards[index] = { ...this.cards[index], is_mastered: originalState };
                        console.error('Mastery Toggle Error:', error);
                    }
                },

                async resetMastery() {
                    this.isResetting = true;
                    try {
                        const response = await axios.post(`/materials/{{ $material->id }}/reset-flashcard-mastery`, {}, {
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        });
                        if (response.data.success) {
                            // Update local state to reflect reset
                            this.cards = this.cards.map(c => ({ ...c, is_mastered: false }));
                            this.currentIndex = 0;
                            this.isFlipped = false;
                        }
                    } catch (error) {
                        alert('Failed to reset flashcards.');
                        console.error(error);
                    } finally {
                        this.isResetting = false;
                    }
                }
            }
        }
    </script>
</div>

<style>
    .perspective-1000 { perspective: 1000px; }
    .transform-style-3d { transform-style: preserve-3d; }
    .backface-hidden { backface-visibility: hidden; }
    .rotate-y-180 { transform: rotateY(180deg); }
</style>
@endsection
