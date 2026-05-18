<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AI Study Hub') | SmartReviewer</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600&family=Inter:wght@400;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        [x-cloak] { display: none !important; }

        /* Persistent Sidebar State (Prevents Flicker) */
        :root { 
            --sb-w: 260px; 
            --sb-logo-px: 24px;
            --sb-link-px: 16px;
            --sb-gap: 12px;
        }

        /* Smaller monitors (Laptops) */
        @media (min-width: 1024px) and (max-width: 1440px) {
            :root {
                --sb-w: 240px;
                --sb-logo-px: 20px;
                --sb-gap: 10px;
            }
        }

        html[data-sb="collapsed"] { 
            --sb-w: 80px; 
            --sb-logo-px: 0px;
            --sb-link-px: 0px;
            --sb-gap: 0px;
        }
        
        #main-sidebar { 
            width: var(--sb-w); 
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        }

        /* Content Visibility Logic */
        .sb-content {
            transition: opacity 0.2s ease, transform 0.2s ease, width 0.3s ease;
            opacity: 1;
            transform: translateX(0);
            display: inline-flex;
            flex-direction: column;
        }

        html[data-sb="collapsed"] .sb-content {
            opacity: 0;
            transform: translateX(-10px);
            pointer-events: none;
            width: 0;
            height: 0;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        /* Centering Logic */
        .sb-logo-container {
            padding-left: var(--sb-logo-px);
            padding-right: var(--sb-logo-px);
            transition: all 0.3s ease;
        }
        html[data-sb="collapsed"] .sb-logo-container {
            justify-content: center;
        }

        .sb-link {
            display: flex;
            align-items: center;
            gap: var(--sb-gap);
            transition: all 0.3s;
        }
        html[data-sb="collapsed"] .sb-link {
            justify-content: center;
        }

        .sb-icon-wrapper {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Disable transitions on load to prevent jumping */
        .no-transition, .no-transition * {
            transition: none !important;
        }

        /* Toggle Button Rotation */
        #sidebar-toggle {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        html[data-sb="collapsed"] #sidebar-toggle {
            transform: rotate(180deg);
        }
    </style>
    <script>
        /* Immediate execution to prevent layout shift */
        (function() {
            const stored = localStorage.getItem('sidebarOpen');
            let state;
            
            if (stored === null) {
                // Default based on screen size: collapse on small monitors (< 1280px)
                state = window.innerWidth < 1280 ? 'collapsed' : 'expanded';
            } else {
                state = stored === 'false' ? 'collapsed' : 'expanded';
            }
            
            document.documentElement.setAttribute('data-sb', state);
            
            // Temporary class to prevent transition on initial load
            document.documentElement.classList.add('no-transition');
            window.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => {
                    document.documentElement.classList.remove('no-transition');
                }, 100);
            });
        })();
    </script>
</head>
<body class="flex h-screen bg-[#F0EDE8] font-['Instrument_Sans'] text-[#1A1714] overflow-hidden" 
      x-data="{ 
          sidebarOpen: document.documentElement.getAttribute('data-sb') !== 'collapsed', 
          mobileMenuOpen: false 
      }" 
      @sidebar-toggle.window="sidebarOpen = !sidebarOpen; localStorage.setItem('sidebarOpen', sidebarOpen); document.documentElement.setAttribute('data-sb', sidebarOpen ? 'expanded' : 'collapsed')">

    <!-- Top Progress Bar -->
    <div id="top-progress-bar"></div>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="mobileMenuOpen" x-cloak @click="mobileMenuOpen = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[140] md:hidden"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"></div>

    <aside id="main-sidebar" 
           :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
           class="bg-[#1A1714] flex flex-col flex-shrink-0 h-full border-r border-[#2E2B28] fixed inset-y-0 left-0 z-[150] transition-transform duration-300 md:relative">
        
        <!-- Toggle Button (Desktop Only) -->
        <button id="sidebar-toggle"
                @click="sidebarOpen = !sidebarOpen; localStorage.setItem('sidebarOpen', sidebarOpen); document.documentElement.setAttribute('data-sb', sidebarOpen ? 'expanded' : 'collapsed')" 
                class="absolute -right-3 top-12 w-6 h-6 bg-[#6646E5] rounded-full hidden md:flex items-center justify-center text-white cursor-pointer z-[100] border-2 border-[#1A1714] shadow-lg hover:scale-110 transition-all duration-300">
            <x-heroicon-m-chevron-left class="w-4 h-4" />
        </button>

        <!-- Sidebar Background Glow -->
        <div class="absolute -top-20 -left-20 w-[300px] h-[300px] bg-[#6646E5] rounded-full mix-blend-multiply filter blur-[100px] opacity-10 pointer-events-none"></div>

        <!-- Logo Section -->
        <div class="py-10 border-b border-[#2E2B28] mb-6 relative z-10 overflow-hidden h-[121px] flex-shrink-0 transition-all duration-300 flex items-center sb-logo-container">
            <a href="{{ route('dashboard') }}" class="flex items-center group transition-all duration-300 sb-link">
                <div class="w-10 h-10 bg-[#6646E5] rounded-[12px] flex items-center justify-center flex-shrink-0 shadow-[0_0_15px_rgba(102,70,229,0.3)] group-hover:shadow-[0_0_25px_rgba(102,70,229,0.5)] group-hover:scale-105 transition-all duration-500">
                    <x-logo class="w-6 h-6 text-white" />
                </div>
                <div class="flex flex-col sb-content">
                    <span class="text-[20px] leading-tight font-normal font-['Inter'] tracking-tight text-white whitespace-nowrap">Smart<span style="font-weight: bold;">Reviewer</span></span>
                    <p class="text-[#9E9690] text-[9px] font-medium tracking-wide uppercase whitespace-nowrap">STUDY SMARTER, SCORE HGHER</p>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 space-y-1.5 overflow-y-auto overflow-x-hidden relative z-10 custom-scrollbar">

            <a href="{{ route('dashboard') }}" class="block px-4 py-3.5 rounded-[14px] transition-all duration-300 group {{ request()->routeIs('dashboard') ? 'bg-[#6646E5] text-white shadow-[0_4px_20px_rgba(102,70,229,0.2)]' : 'text-[#9E9690] hover:text-white hover:bg-white/5' }}">
                <div class="sb-link">
                    <div class="sb-icon-wrapper">
                        <x-heroicon-o-home class="w-5 h-5 transition-transform group-hover:scale-110 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-[#6646E5]/70 group-hover:text-[#6646E5]' }}" />
                    </div>
                    <span class="font-bold text-[14px] whitespace-nowrap sb-content">Dashboard</span>
                </div>
            </a>

            <a href="{{ route('materials.upload') }}" class="block px-4 py-3.5 rounded-[14px] transition-all duration-300 group {{ request()->routeIs('materials.upload') ? 'bg-[#6646E5] text-white shadow-[0_4px_20px_rgba(102,70,229,0.2)]' : 'text-[#9E9690] hover:text-white hover:bg-white/5' }}">
                <div class="sb-link">
                    <div class="sb-icon-wrapper">
                        <x-heroicon-o-cloud-arrow-up class="w-5 h-5 transition-transform group-hover:scale-110 {{ request()->routeIs('materials.upload') ? 'text-white' : 'text-[#6646E5]/70 group-hover:text-[#6646E5]' }}" />
                    </div>
                    <span class="font-bold text-[14px] whitespace-nowrap sb-content">Upload Material</span>
                </div>
            </a>

            <a href="{{ route('reviewer') }}" class="block px-4 py-3.5 rounded-[14px] transition-all duration-300 group {{ request()->routeIs('reviewer') ? 'bg-[#6646E5] text-white shadow-[0_4px_20px_rgba(102,70,229,0.2)]' : 'text-[#9E9690] hover:text-white hover:bg-white/5' }}">
                <div class="sb-link">
                    <div class="sb-icon-wrapper">
                        <x-heroicon-o-book-open class="w-5 h-5 transition-transform group-hover:scale-110 {{ request()->routeIs('reviewer') ? 'text-white' : 'text-[#6646E5]/70 group-hover:text-[#6646E5]' }}" />
                    </div>
                    <span class="font-bold text-[14px] whitespace-nowrap sb-content">Reviewer</span>
                </div>
            </a>

            <a href="{{ route('quizzes.index') }}" class="block px-4 py-3.5 rounded-[14px] transition-all duration-300 group {{ (request()->routeIs('quizzes.index') || request()->routeIs('quizzes.session')) ? 'bg-[#6646E5] text-white shadow-[0_4px_20px_rgba(102,70,229,0.2)]' : 'text-[#9E9690] hover:text-white hover:bg-white/5' }}">
                <div class="sb-link">
                    <div class="sb-icon-wrapper">
                        <x-heroicon-o-clipboard-document-list class="w-5 h-5 transition-transform group-hover:scale-110 {{ (request()->routeIs('quizzes.index') || request()->routeIs('quizzes.session')) ? 'text-white' : 'text-[#6646E5]/70 group-hover:text-[#6646E5]' }}" />
                    </div>
                    <span class="font-bold text-[14px] whitespace-nowrap sb-content">Quizzes</span>
                </div>
            </a>

            <a href="{{ route('materials.index') }}" class="block px-4 py-3.5 rounded-[14px] transition-all duration-300 group {{ request()->routeIs('materials.index') ? 'bg-[#6646E5] text-white shadow-[0_4px_20px_rgba(102,70,229,0.2)]' : 'text-[#9E9690] hover:text-white hover:bg-white/5' }}">
                <div class="sb-link">
                    <div class="sb-icon-wrapper">
                        <x-heroicon-o-folder-open class="w-5 h-5 transition-transform group-hover:scale-110 {{ request()->routeIs('materials.index') ? 'text-white' : 'text-[#6646E5]/70 group-hover:text-[#6646E5]' }}" />
                    </div>
                    <span class="font-bold text-[14px] whitespace-nowrap sb-content">Materials</span>
                </div>
            </a>

            <a href="{{ route('quizzes.results') }}" class="block px-4 py-3.5 rounded-[14px] transition-all duration-300 group {{ (request()->routeIs('quizzes.results') || request()->routeIs('quizzes.breakdown')) ? 'bg-[#6646E5] text-white shadow-[0_4px_20px_rgba(102,70,229,0.2)]' : 'text-[#9E9690] hover:text-white hover:bg-white/5' }}">
                <div class="sb-link">
                    <div class="sb-icon-wrapper">
                        <x-heroicon-o-chart-bar class="w-5 h-5 transition-transform group-hover:scale-110 {{ (request()->routeIs('quizzes.results') || request()->routeIs('quizzes.breakdown')) ? 'text-white' : 'text-[#6646E5]/70 group-hover:text-[#6646E5]' }}" />
                    </div>
                    <span class="font-bold text-[14px] whitespace-nowrap sb-content">Results</span>
                </div>
            </a>

            <!-- Divider -->
            <div class="py-6 px-4 flex-shrink-0">
                <div class="h-px bg-gradient-to-r from-transparent via-[#2E2B28] to-transparent"></div>
            </div>

            <!-- Pro Tip Card -->
            <div class="px-4 pb-4 flex-shrink-0 sb-content">
                <div class="bg-gradient-to-br from-[#2E2B28] to-[#1A1714] border border-white/5 rounded-[20px] p-5 relative overflow-hidden group/tip cursor-default hover:border-[#6646E5]/40 transition-all duration-300">
                    <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-[#6646E5] rounded-full blur-2xl opacity-20 group-hover/tip:opacity-40 transition-opacity"></div>                    <div class="w-8 h-8 bg-[#6646E5]/20 rounded-lg flex items-center justify-center text-[#A78BFA] mb-3 group-hover/tip:scale-110 transition-transform">
                        <x-heroicon-o-light-bulb class="w-5 h-5" />
                    </div>
                    <h5 class="text-white text-[12px] font-bold mb-1">Study Tip</h5>
                    <p class="text-[#9E9690] text-[11px] leading-relaxed">Try taking a quiz 10 minutes after reading to boost retention!</p>
                </div>
            </div>

        </nav>

        <!-- Profile & Logout -->
        <div class="p-4 border-t border-[#2E2B28] mt-auto relative z-10 bg-[#1A1714] flex-shrink-0">
            <a href="{{ route('profile.edit') }}" class="flex items-center p-3 rounded-2xl hover:bg-white/5 transition-colors group cursor-pointer mb-2 sb-link {{ request()->routeIs('profile.edit') ? 'bg-white/5' : '' }}">
                <div class="sb-icon-wrapper w-12 h-12 rounded-full bg-[#6646E5] text-white font-bold text-[12px] shadow-lg group-hover:scale-105 transition-transform duration-300">
                    {{ auth()->check() ? substr(auth()->user()->first_name, 0, 1) . substr(auth()->user()->last_name, 0, 1) : 'U' }}
                </div>
                <div class="flex flex-col min-w-0 sb-content">
                    <span class="text-white font-bold text-[13px] truncate whitespace-nowrap group-hover:text-[#E0D8FC] transition-colors">
                        {{ auth()->check() ? auth()->user()->first_name . ' ' . auth()->user()->last_name : 'User' }}
                    </span>
                    <span class="text-[#9E9690] text-[10px] font-bold uppercase tracking-wider whitespace-nowrap">Manage Profile</span>
                </div>
            </a>

            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <button type="submit" class="w-full flex items-center px-4 py-3 text-[#9E9690] hover:text-white hover:bg-white/5 rounded-xl transition-all group text-left sb-link">
                    <div class="sb-icon-wrapper">
                        <x-heroicon-o-arrow-left-on-rectangle class="w-5 h-5 group-hover:text-red-400 group-hover:-translate-x-1 transition-all" />
                    </div>
                    <span class="font-bold text-[13px] group-hover:text-red-400 whitespace-nowrap sb-content">Sign Out</span>
                </button>
            </form>
        </div>

    </aside>

    <div class="flex-1 flex flex-col h-screen min-w-0 overflow-hidden relative z-10">
        <!-- Global Grid Background (Subtle for light theme) -->
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#1a171408_1px,transparent_1px),linear-gradient(to_bottom,#1a171408_1px,transparent_1px)] bg-[size:48px_48px]"></div>
        </div>

        <!-- Mobile Header -->
        <header class="md:hidden flex items-center justify-between bg-white border-b border-[#E2DDD8] px-4 py-3 flex-shrink-0 relative z-[130]">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-[#6646E5] rounded-[8px] flex items-center justify-center shadow-sm">
                    <x-logo class="w-5 h-5 text-white" />
                </div>
                <span class="font-bold text-[#1A1714] text-[16px] tracking-tight">SmartReviewer</span>
            </div>
            <button @click="mobileMenuOpen = true" class="p-2 -mr-2 text-[#7C7167] hover:text-[#1A1714] transition-colors">
                <x-heroicon-o-bars-3 class="w-6 h-6" />
            </button>
        </header>

        <main class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8 xl:p-10">
            @yield('content')
        </main>
    </div>

    <!-- Global Preview Modal -->
    <div x-data="{ previewModalOpen: false, previewFileUrl: '', previewFileType: '' }" 
         @open-preview.window="previewFileUrl = $event.detail.url; previewFileType = $event.detail.type; previewModalOpen = true"
         x-show="previewModalOpen" 
         x-cloak 
         @keydown.escape.window="previewModalOpen = false"
         class="fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-sm"
         style="z-index: 99999;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
         
         <div @click.away="previewModalOpen = false"
              x-show="previewModalOpen"
              x-transition:enter="transition ease-out duration-300 delay-100"
              x-transition:enter-start="opacity-0 scale-95"
              x-transition:enter-end="opacity-100 scale-100"
              x-transition:leave="transition ease-in duration-200"
              x-transition:leave-start="opacity-100 scale-100"
              x-transition:leave-end="opacity-0 scale-95"
              class="relative w-[98vw] max-w-[1800px] h-[96vh] bg-white rounded-[24px] shadow-2xl overflow-hidden flex flex-col">
              
              <!-- Header -->
              <div class="flex items-center justify-between px-6 py-4 border-b border-[#E2DDD8] bg-gray-50 flex-shrink-0">
                  <h3 class="text-lg font-bold text-[#1A1714]">File Preview</h3>
                  <button @click="previewModalOpen = false" class="text-[#7C7167] hover:text-[#1A1714] transition-colors bg-white border border-[#E2DDD8] rounded-full p-1 shadow-sm">
                      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                  </button>
              </div>
              
              <!-- Content -->
              <div class="flex-1 overflow-auto bg-[#F0EDE8] flex items-center justify-center p-6">
                  <template x-if="previewFileType === 'pdf'">
                      <iframe :src="previewFileUrl" class="w-full h-full rounded-lg shadow-sm border border-[#E2DDD8]"></iframe>
                  </template>
                  <template x-if="previewFileType === 'image'">
                      <img :src="previewFileUrl" class="max-w-full max-h-full object-contain rounded-lg shadow-sm border border-[#E2DDD8]">
                  </template>
                  <template x-if="!previewFileType || (previewFileType !== 'pdf' && previewFileType !== 'image')">
                      <div class="text-[#7C7167] text-center bg-white p-10 rounded-2xl border border-[#E2DDD8] shadow-sm">
                          <x-heroicon-o-document-text class="w-16 h-16 mx-auto mb-4 text-[#A39D98]" />
                          <p class="font-medium text-[16px]">Preview not available for this file type.</p>
                          <a :href="previewFileUrl" target="_blank" class="text-[#6646E5] font-bold hover:underline mt-4 inline-block">Download File</a>
                      </div>
                  </template>
              </div>
         </div>
    </div>

</body>
</html>
