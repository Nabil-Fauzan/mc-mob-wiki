<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="font-sans antialiased bg-[#020617] text-gray-100 selection:bg-brand-500/30"
          x-data="{ 
            paletteOpen: false,
            paletteSearch: '',
            liveResults: [],
            recentResearch: [],
            isLoading: false,
            quickLinks: [
                { name: 'Research Center (Wiki)', url: '{{ route('mobs.index') }}', desc: 'Browse all entitites', icon: '🔍' },
                { name: 'Dimension Hub', url: '{{ route('dimensions.index') }}', desc: 'Cross-dimensional stats', icon: '🌌' },
                { name: 'Biome Discovery', url: '{{ route('biomes.index') }}', desc: 'Environmental intel', icon: '🌳' },
                { name: 'Analytics Terminal', url: '{{ route('stats.index') }}', desc: 'Global data charts', icon: '📊' },
                { name: 'Comparison Lab', url: '{{ route('mobs.comparison') }}', desc: 'Combat per mob', icon: '⚔️' },
            ],
            init() {
                this.recentResearch = JSON.parse(localStorage.getItem('recent_research') || '[]');
                window.notify = (content, type = 'info') => {
                    window.dispatchEvent(new CustomEvent('notify', { detail: { content, type } }));
                };
                
                this.$watch('paletteOpen', value => {
                    if (value) {
                        this.recentResearch = JSON.parse(localStorage.getItem('recent_research') || '[]');
                    }
                });

                this.$watch('paletteSearch', value => {
                    this.fetchLiveResults(value);
                });
            },
            async fetchLiveResults(query) {
                if (query.length < 2) {
                    this.liveResults = [];
                    return;
                }
                this.isLoading = true;
                try {
                    const response = await fetch(`/api/search?q=${encodeURIComponent(query)}`);
                    this.liveResults = await response.json();
                } catch (error) {
                    console.error('Search error:', error);
                } finally {
                    this.isLoading = false;
                }
            },
            filteredLinks() {
                return this.quickLinks.filter(l => l.name.toLowerCase().includes(this.paletteSearch.toLowerCase()));
            }
          }"
          @keydown.window.ctrl.k.prevent="paletteOpen = true"
          @keydown.window.cmd.k.prevent="paletteOpen = true"
          @keydown.escape="paletteOpen = false">
        
        <!-- Command Palette Modal -->
        <div x-show="paletteOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-start justify-center pt-[15vh] px-4 sm:px-6 lg:px-8 bg-black/60 backdrop-blur-sm"
             x-cloak>
            
            <div @click.away="paletteOpen = false" 
                 class="w-full max-w-2xl bg-[#0f172a] border border-white/10 rounded-[2rem] shadow-2xl overflow-hidden">
                
                <!-- Search Input -->
                <div class="relative p-6 border-b border-white/5">
                    <span class="absolute inset-y-0 left-6 flex items-center pr-3 pointer-events-none text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" 
                        x-model="paletteSearch"
                        x-ref="paletteInput"
                        @show.window="if(paletteOpen) $nextTick(() => $refs.paletteInput.focus())"
                        class="block w-full pl-12 pr-4 py-4 bg-transparent border-none text-white text-xl placeholder-gray-600 focus:ring-0 font-bold" 
                        placeholder="Search Intelligence Terminal... (Ctrl+K)" />
                    <div x-show="isLoading" class="absolute right-6 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin h-5 w-5 text-brand-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </div>

                <!-- Results -->
                <div class="max-h-[60vh] overflow-y-auto p-4 space-y-6">
                    <!-- Live Entity Search Results (ORACLE) -->
                    <template x-if="paletteSearch.length >= 2">
                        <div>
                            <h4 class="px-4 text-[9px] font-black text-brand-500 uppercase tracking-[0.2em] mb-3">Entity Intelligence Found</h4>
                            <div class="space-y-2">
                                <template x-for="mob in liveResults" :key="mob.id">
                                    <a :href="'/mobs/' + mob.id" class="flex items-center p-3 rounded-2xl hover:bg-brand-500/10 border border-transparent hover:border-brand-500/20 transition-all group">
                                        <div class="w-14 h-14 rounded-xl bg-gray-950 overflow-hidden mr-4 border border-white/5">
                                            <img :src="'/storage/' + mob.image" class="w-full h-full object-cover" x-show="mob.image">
                                            <div class="w-full h-full flex items-center justify-center text-gray-800" x-show="!mob.image">?</div>
                                        </div>
                                        <div class="flex-1">
                                            <span class="block text-white font-black uppercase tracking-tight text-sm" x-text="mob.name"></span>
                                            <div class="flex items-center space-x-3 mt-1">
                                                <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest" x-text="mob.category.name"></span>
                                                <span class="w-1 h-1 bg-gray-800 rounded-full"></span>
                                                <span class="text-[9px] font-mono text-red-500/70" x-text="mob.health_normal + ' HP'"></span>
                                            </div>
                                        </div>
                                        <svg class="w-5 h-5 text-brand-500 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                    </a>
                                </template>
                                <template x-if="liveResults.length === 0 && !isLoading">
                                    <div class="p-8 text-center text-gray-600 text-xs italic">No matching entities in database</div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Recent Research (CHRONOS) -->
                    <template x-if="paletteSearch.length < 2 && recentResearch.length > 0">
                        <div>
                            <h4 class="px-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] mb-3">Recent Research History</h4>
                            <div class="grid grid-cols-2 gap-2">
                                <template x-for="item in recentResearch" :key="item.id">
                                    <a :href="'/mobs/' + item.id" class="flex items-center p-3 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/5 transition-all group">
                                        <div class="w-10 h-10 rounded-lg bg-gray-950 overflow-hidden mr-3">
                                            <img :src="'/storage/' + item.image" class="w-full h-full object-cover" x-show="item.image">
                                        </div>
                                        <span class="text-[11px] font-bold text-gray-300 truncate group-hover:text-white" x-text="item.name"></span>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Navigation Links -->
                    <div>
                        <h4 class="px-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] mb-3" x-text="paletteSearch.length >= 2 ? 'Related Systems' : 'System Navigation'"></h4>
                        <div class="space-y-1">
                            <template x-for="link in filteredLinks()" :key="link.url">
                                <a :href="link.url" class="flex items-center p-4 rounded-2xl hover:bg-white/5 border border-transparent hover:border-white/10 transition-all group">
                                    <div class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-2xl mr-4 group-hover:scale-110 transition-transform" x-text="link.icon"></div>
                                    <div class="flex-1">
                                        <span class="block text-white font-black uppercase tracking-widest text-[11px]" x-text="link.name"></span>
                                        <span class="text-xs text-gray-500" x-text="link.desc"></span>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-700 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-4 bg-black/20 border-t border-white/5 flex justify-between items-center px-8">
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center space-x-1">
                            <span class="px-1.5 py-0.5 bg-gray-800 rounded text-[10px] text-gray-400 font-bold">ESC</span>
                            <span class="text-[10px] text-gray-600 font-bold uppercase">Close</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <span class="px-1.5 py-0.5 bg-gray-800 rounded text-[10px] text-gray-400 font-bold">↵</span>
                            <span class="text-[10px] text-gray-600 font-bold uppercase">Navigate</span>
                        </div>
                    </div>
                    <span class="text-[10px] text-gray-700 font-black tracking-widest uppercase italic">Wiki Protocol [v.2]</span>
                </div>
            </div>
        </div>

        <!-- Background Decorations -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-brand-600/20 blur-[120px] rounded-full animate-pulse"></div>
            <div class="absolute top-[20%] -right-[10%] w-[35%] h-[35%] bg-accent-400/10 blur-[120px] rounded-full" style="animation: float 8s ease-in-out infinite;"></div>
            <div class="absolute -bottom-[10%] left-[20%] w-[30%] h-[30%] bg-brand-400/10 blur-[120px] rounded-full"></div>
        </div>

        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-transparent">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <!-- Floating Mobile Navigation -->
            <div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-40 w-[90%] max-w-md md:hidden nav-appear" 
                 x-data="{ active: '{{ Route::currentRouteName() }}' }">
                <div class="glass-card rounded-full p-2 px-6 flex items-center justify-between border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.5)]">
                    <a href="{{ route('mobs.index') }}" class="p-3 transition-all rounded-full" 
                       :class="active === 'mobs.index' ? 'bg-brand-500 text-white shadow-lg shadow-brand-500/40' : 'text-gray-500 hover:text-white'">
                        <span class="text-xl">🧭</span>
                    </a>
                    <a href="{{ route('dimensions.index') }}" class="p-3 transition-all rounded-full"
                       :class="active === 'dimensions.index' ? 'bg-brand-500 text-white shadow-lg shadow-brand-500/40' : 'text-gray-500 hover:text-white'">
                        <span class="text-xl">🌌</span>
                    </a>
                    <a href="{{ route('mobs.create') }}" class="p-3 transition-all rounded-full"
                       :class="active === 'mobs.create' ? 'bg-brand-500 text-white shadow-lg shadow-brand-500/40' : 'text-gray-500 hover:text-white'">
                        <span class="text-xl">➕</span>
                    </a>
                    <a href="{{ route('mobs.comparison') }}" class="p-3 transition-all rounded-full"
                       :class="active === 'mobs.comparison' ? 'bg-brand-500 text-white shadow-lg shadow-brand-500/40' : 'text-gray-500 hover:text-white'">
                        <span class="text-xl">🧪</span>
                    </a>
                </div>
            </div>

            <!-- Mobile Quick Trigger -->
            <button @click="paletteOpen = true" 
                    class="fixed bottom-24 right-6 z-40 w-12 h-12 bg-brand-600 text-white rounded-full shadow-2xl flex items-center justify-center border border-white/20 md:hidden nav-appear animate-float"
                    style="animation-delay: 200ms">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
        </div>

        <x-toast />
    </body>
</html>
