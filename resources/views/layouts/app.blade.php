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

        <style>
            .terminal-glass {
                background: linear-gradient(135deg, rgba(2, 6, 23, 0.95), rgba(15, 23, 42, 0.98));
                box-shadow: 0 0 50px rgba(220, 38, 38, 0.15), inset 0 0 20px rgba(220, 38, 38, 0.1);
            }
            .scanline {
                width: 100%;
                height: 100px;
                z-index: 5;
                background: linear-gradient(0deg, rgba(0, 0, 0, 0) 0%, rgba(220, 38, 38, 0.05) 50%, rgba(0, 0, 0, 0) 100%);
                opacity: 0.1;
                position: absolute;
                bottom: 100%;
                animation: scanline 6s linear infinite;
            }
            @keyframes scanline {
                0% { bottom: 100%; }
                100% { bottom: -100px; }
            }
            .terminal-grid {
                background-image: radial-gradient(rgba(220, 38, 38, 0.05) 1px, transparent 0);
                background-size: 30px 30px;
            }
            .oracle-glow {
                text-shadow: 0 0 10px rgba(220, 38, 38, 0.5);
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-[#020617] text-gray-100 selection:bg-brand-500/30 overflow-x-hidden {{
                $theme === 'Nether' ? 'theme-nether' : ($theme === 'The End' ? 'theme-end' : '')
          }}"
          :class="{
            'performance-mode': performanceMode,
            'theme-light': themeMode === 'light',
            'theme-nether': themePreset === 'nether',
            'theme-end': themePreset === 'end',
            'theme-overworld': themePreset === 'overworld',
            'high-contrast': accessibility.highContrast,
            'reduced-transparency': accessibility.reducedTransparency,
            'font-dyslexia': accessibility.dyslexiaFont
          }"
          x-data="aetherProtocol"
          @keydown.window.ctrl.k.prevent="paletteOpen = true"
          @keydown.window.cmd.k.prevent="paletteOpen = true"
          @keydown.escape="paletteOpen = false"
          @keydown.window.ctrl.s.prevent="if($el.querySelector('form')) $el.querySelector('form').submit()"
          @keydown.window.cmd.s.prevent="if($el.querySelector('form')) $el.querySelector('form').submit()"
          @auth
            @if(auth()->user()->is_admin)
              @keydown.window.alt.g.prevent="window.location.href = '{{ route('admin.dashboard') }}'"
              @keydown.window.alt.c.prevent="window.location.href = '{{ route('mobs.create') }}'"
              @keydown.window.alt.b.prevent="window.location.href = '{{ route('admin.biomes.create') }}'"
              @keydown.window.alt.m.prevent="window.location.href = '{{ route('admin.dashboard') }}#global-comms'"
              @keydown.window.alt.u.prevent="window.location.href = '{{ route('admin.dashboard') }}#recent-users'"
            @endif
          @endauth>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('aetherProtocol', () => ({
                    paletteOpen: false,
                    paletteSearch: '',
                    liveResults: [],
                    recentResearch: [],
                    isLoading: false,
                    mouseX: 0,
                    mouseY: 0,
                    terminalOpen: false,
                    terminalInput: '',
                    terminalOutput: ['AETHER PROTOCOL [v.2] - UNAUTHORIZED ACCESS PROHIBITED'],
                    konami: [],
                    konamiCode: ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 'b', 'a'],
                    selectedIndex: -1,
                    themeMode: 'dark',
                    themePreset: 'overworld',
                    themePanelOpen: false,
                    accessibility: { highContrast: false, reducedTransparency: false, dyslexiaFont: false },
                    performanceMode: false,
                    isAdmin: {{ auth()->check() && auth()->user()->is_admin ? 'true' : 'false' }},
                    quickLinks: [
                        { name: 'Research Center (Wiki)', url: '{{ route('mobs.index') }}', desc: 'Browse all entitites', icon: 'Search' },
                        { name: 'Dimension Hub', url: '{{ route('dimensions.index') }}', desc: 'Cross-dimensional stats', icon: 'Worlds' },
                        { name: 'Biome Discovery', url: '{{ route('biomes.index') }}', desc: 'Environmental intel', icon: 'Biomes' },
                        { name: 'Analytics Terminal', url: '{{ route('stats.index') }}', desc: 'Global data charts', icon: 'Stats' },
                        { name: 'Comparison Lab', url: '{{ route('mobs.comparison') }}', desc: 'Combat per mob', icon: 'Compare' },
                    ],
                    init() {
                        this.recentResearch = JSON.parse(localStorage.getItem('recent_research') || '[]');
                        this.performanceMode = localStorage.getItem('performance_mode') === '1';
                        this.themeMode = localStorage.getItem('theme_mode') || 'dark';
                        this.themePreset = localStorage.getItem('theme_preset') || 'overworld';
                        this.accessibility = JSON.parse(localStorage.getItem('a11y_pack') || JSON.stringify(this.accessibility));
                        const url = new URL(window.location.href);
                        const t = url.searchParams.get('theme');
                        if (t && ['overworld', 'nether', 'end'].includes(t)) this.themePreset = t;
                        const m = url.searchParams.get('mode');
                        if (m && ['dark', 'light'].includes(m)) this.themeMode = m;
                        window.notify = (content, type = 'info') => {
                            window.dispatchEvent(new CustomEvent('notify', { detail: { content, type } }));
                        };

                        window.trackResearch = (mob) => {
                            let history = JSON.parse(localStorage.getItem('recent_research') || '[]');
                            history = history.filter(h => h.id !== mob.id);
                            history.unshift({
                                id: mob.id,
                                name: mob.name,
                                image: mob.image,
                                category: mob.category,
                                url: mob.url
                            });
                            history = history.slice(0, 6);
                            localStorage.setItem('recent_research', JSON.stringify(history));
                            this.recentResearch = history;
                        };

                        this.$watch('paletteOpen', value => {
                            if (value) {
                                this.recentResearch = JSON.parse(localStorage.getItem('recent_research') || '[]');
                                this.selectedIndex = -1;
                            }
                        });

                        this.$watch('paletteSearch', value => {
                            this.fetchLiveResults(value);
                            this.selectedIndex = -1;
                        });

                        window.addEventListener('mousemove', (e) => {
                            this.mouseX = e.clientX;
                            this.mouseY = e.clientY;
                        });

                        window.addEventListener('keydown', (e) => {
                            this.konami.push(e.key);
                            this.konami = this.konami.slice(-10);
                            if (JSON.stringify(this.konami) === JSON.stringify(this.konamiCode)) {
                                if (this.isAdmin) {
                                    this.terminalOpen = true;
                                    this.konami = [];
                                    window.notify('ADMIN OVERRIDE DETECTED', 'warning');
                                } else {
                                    window.notify('ACCESS DENIED: Protocol Restricted to Rank Admin', 'error');
                                    this.konami = [];
                                }
                            }
                        });
                    },
                    togglePerformanceMode() {
                        this.performanceMode = !this.performanceMode;
                        localStorage.setItem('performance_mode', this.performanceMode ? '1' : '0');
                        window.notify(this.performanceMode ? 'Performance Mode ON' : 'Performance Mode OFF', 'info');
                    },
                    applyTheme(preset = this.themePreset, mode = this.themeMode) {
                        this.themePreset = preset;
                        this.themeMode = mode;
                        localStorage.setItem('theme_preset', this.themePreset);
                        localStorage.setItem('theme_mode', this.themeMode);
                        const url = new URL(window.location.href);
                        url.searchParams.set('theme', this.themePreset);
                        url.searchParams.set('mode', this.themeMode);
                        window.history.replaceState({}, '', url.toString());
                    },
                    toggleA11y(key) {
                        this.accessibility[key] = !this.accessibility[key];
                        localStorage.setItem('a11y_pack', JSON.stringify(this.accessibility));
                    },
                    get totalResults() {
                        if (this.paletteSearch.length >= 2) return this.liveResults.length + this.filteredLinks().length;
                        return this.recentResearch.length + this.filteredLinks().length;
                    },
                    navigatePalette(dir) {
                        if (this.totalResults === 0) return;
                        if (dir === 'down') {
                            this.selectedIndex = (this.selectedIndex + 1) % this.totalResults;
                        } else {
                            this.selectedIndex = (this.selectedIndex - 1 + this.totalResults) % this.totalResults;
                        }
                        this.$nextTick(() => {
                            const el = document.querySelector('[data-index="' + this.selectedIndex + '"]');
                            if (el) el.scrollIntoView({ block: 'nearest' });
                        });
                    },
                    openSelected(isEdit = false) {
                        if (this.selectedIndex === -1) return;
                        const el = document.querySelector('[data-index="' + this.selectedIndex + '"]');
                        if (el) {
                            if (isEdit && el.dataset.editUrl && this.isAdmin) {
                                window.location.href = el.dataset.editUrl;
                            } else {
                                el.click();
                            }
                        }
                    },
                    executeTerminal() {
                        const cmd = this.terminalInput.toLowerCase().trim();
                        this.terminalOutput.push('> ' + this.terminalInput);

                        if (cmd === 'help') {
                            this.terminalOutput.push('Available: help, override/admin, intel, oracle [query], registry, explorer, cls, exit');
                            this.terminalOutput.push('Admin Shortcuts: Alt+G (Dashboard), Alt+M (Oracle Console), Alt+U (Recent Users)');
                        } else if (cmd.startsWith('oracle')) {
                            const query = cmd.replace('oracle', '').trim();
                            if (!query) {
                                this.terminalOutput.push('ORACLE: Please provide a research subject for analysis.');
                                return;
                            }
                            this.terminalOutput.push('ORACLE: INITIALIZING DEEP ANALYSIS...');
                            this.terminalOutput.push('ORACLE: ACCESSING MULTIVERSAL DATA...');

                            fetch('/api/oracle', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                },
                                body: JSON.stringify({ query, lang: 'id', mode: 'lore' })
                            })
                                .then(res => res.json())
                                .then(data => {
                                    this.terminalOutput.push(data.response);
                                    this.terminalOutput.push('ORACLE: ANALYSIS COMPLETE.');
                                })
                                .catch(err => {
                                    this.terminalOutput.push('ORACLE: [SIGNAL INTERRUPTED] Multiverse communication failed.');
                                })
                                .finally(() => {
                                    this.$nextTick(() => {
                                        const el = document.getElementById('terminal-scroll');
                                        if (el) el.scrollTop = el.scrollHeight;
                                    });
                                });
                        } else if (cmd === 'override' || cmd === 'admin') {
                            window.location.href = '{{ route('admin.dashboard') }}';
                        } else if (cmd === 'intel') {
                            this.terminalOutput.push('SCANNING REGISTRY...');
                            this.terminalOutput.push('-------------------------');
                            this.terminalOutput.push('TOTAL ENTITIES: {{ \App\Models\Mob::count() }}');
                            this.terminalOutput.push('UNCATEGORIZED: {{ \App\Models\Mob::whereNull('category_id')->count() }}');
                            this.terminalOutput.push('MISSING BIOMES: {{ \App\Models\Mob::doesntHave('biomes')->count() }}');
                            this.terminalOutput.push('MISSING IMAGES: {{ \App\Models\Mob::whereNull('image')->count() }}');
                            this.terminalOutput.push('-------------------------');
                            this.terminalOutput.push('SCAN COMPLETE. ALL SYSTEMS NOMINAL.');
                        } else if (cmd === 'registry') {
                            window.location.href = '{{ route('mobs.index') }}';
                        } else if (cmd === 'explorer') {
                            window.location.href = '{{ route('biomes.index') }}';
                        } else if (cmd === 'cls') {
                            this.terminalOutput = ['AETHER PROTOCOL [v.2]'];
                        } else if (cmd === 'exit') {
                            this.terminalOpen = false;
                        } else {
                            this.terminalOutput.push('Unknown command: ' + cmd);
                        }

                        this.terminalInput = '';
                        this.$nextTick(() => {
                            const el = document.getElementById('terminal-scroll');
                            if (el) el.scrollTop = el.scrollHeight;
                        });
                    },
                    async fetchLiveResults(query) {
                        if (query.length < 2) {
                            this.liveResults = [];
                            return;
                        }
                        this.isLoading = true;
                        try {
                            const response = await fetch('/api/search?q=' + encodeURIComponent(query));
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
                }));
            });
        </script>

        <!-- Command Palette Modal -->
        <div x-show="paletteOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[100] flex items-start justify-center pt-4 sm:pt-[15vh] px-3 sm:px-6 lg:px-8 bg-black/60 backdrop-blur-sm"
             x-cloak>

            <div @click.away="paletteOpen = false"
                 class="w-full max-w-2xl bg-[#0f172a] border border-white/10 rounded-[1.5rem] sm:rounded-[2rem] shadow-2xl overflow-hidden max-h-[calc(100svh-2rem)] sm:max-h-none">

                <!-- Search Input -->
                <div class="relative p-4 sm:p-6 border-b border-white/5">
                    <span class="absolute inset-y-0 left-4 sm:left-6 flex items-center pr-3 pointer-events-none text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text"
                        x-model="paletteSearch"
                        x-ref="paletteInput"
                        @show.window="if(paletteOpen) $nextTick(() => $refs.paletteInput.focus())"
                        @keydown.down.prevent="navigatePalette('down')"
                        @keydown.up.prevent="navigatePalette('up')"
                        @keydown.enter.prevent="openSelected()"
                        @keydown.e.prevent="openSelected(true)"
                        class="block w-full pl-11 sm:pl-12 pr-4 py-3 sm:py-4 bg-transparent border-none text-white text-base sm:text-xl placeholder-gray-600 focus:ring-0 font-bold"
                        placeholder="Search Intelligence Terminal... (Ctrl+K)" />
                    <div x-show="isLoading" class="absolute right-4 sm:right-6 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin h-5 w-5 text-brand-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </div>

                <!-- Results -->
                <div class="max-h-[calc(100svh-10rem)] sm:max-h-[60vh] overflow-y-auto p-3 sm:p-4 space-y-5 sm:space-y-6">
                    <!-- Live Entity Search Results (ORACLE) -->
                    <template x-if="paletteSearch.length >= 2">
                        <div>
                            <div class="flex items-center justify-between px-4 mb-3">
                                <h4 class="text-[9px] font-black text-brand-500 uppercase tracking-[0.2em]">Entity Intelligence Found</h4>
                                <span class="text-[8px] font-mono text-gray-700" x-text="liveResults.length + ' Result(s)'"></span>
                            </div>
                            <div class="space-y-2">
                                <template x-for="(mob, index) in liveResults" :key="mob.id">
                                    <a :href="mob.url"
                                       @click="window.trackResearch(mob)"
                                       :data-index="index"
                                       :data-edit-url="'/mobs/' + mob.id + '/edit'"
                                       :class="selectedIndex === index ? 'bg-brand-500/20 border-brand-500/40' : 'hover:bg-brand-500/10 border-transparent'"
                                       class="flex items-center p-3 rounded-2xl border transition-all group">
                                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-black overflow-hidden mr-4 border border-white/5 shadow-xl group-hover:scale-105 transition-transform duration-500 flex-shrink-0">
                                                <img :src="mob.image" :alt="mob.name" class="w-full h-full object-cover" onerror="this.src = 'https://ui-avatars.com/api/?name=' + this.alt + '&background=0f172a&color=0ea5e9'">
                                            <div class="w-full h-full flex items-center justify-center text-gray-800" x-show="!mob.image">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <span class="block text-white font-black uppercase tracking-tight text-sm truncate group-hover:text-brand-400 transition-colors" x-text="mob.name"></span>
                                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1">
                                                <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest" x-text="mob.category"></span>
                                                <span class="w-1 h-1 bg-gray-800 rounded-full"></span>
                                                <span class="text-[9px] font-bold text-brand-500/70 uppercase tracking-tighter" x-text="mob.habitat"></span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <template x-if="isAdmin">
                                                <a :href="'/mobs/' + mob.id + '/edit'"
                                                   class="p-2 bg-white/5 hover:bg-yellow-500/20 text-gray-500 hover:text-yellow-500 rounded-lg transition-all"
                                                   @click.stop
                                                   title="Protocol Override: Quick Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                                </a>
                                            </template>
                                            <svg class="w-5 h-5 text-brand-500 opacity-0 group-hover:opacity-100 transition-all group-hover:translate-x-1 duration-300 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                        </div>
                                    </a>
                                </template>
                                <template x-if="liveResults.length === 0 && !isLoading">
                                    <div class="p-12 text-center">
                                        <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-8 h-8 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                        </div>
                                        <p class="text-xs text-gray-600 font-bold uppercase tracking-widest italic">No intelligence matching query</p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Recent Research (CHRONOS) -->
                    <template x-if="paletteSearch.length < 2 && recentResearch.length > 0">
                        <div>
                            <div class="flex items-center justify-between px-4 mb-3">
                                <h4 class="text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">Recently Analyzed Subjects</h4>
                                <button @click="localStorage.removeItem('recent_research'); recentResearch = []; window.notify('Research history purged', 'info');" class="text-[8px] font-black text-red-500/50 hover:text-red-500 uppercase tracking-widest transition-colors">Clear History</button>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <template x-for="(item, index) in recentResearch" :key="item.id">
                                    <a :href="item.url"
                                       :data-index="index"
                                       :data-edit-url="'/mobs/' + item.id + '/edit'"
                                       :class="selectedIndex === index ? 'bg-brand-500/20 border-brand-500/40' : 'bg-white/5 border-white/5 hover:bg-brand-500/10 hover:border-brand-500/20'"
                                       class="flex items-center p-2.5 rounded-2xl border transition-all group relative overflow-hidden">
                                        <div class="absolute inset-0 bg-gradient-to-r from-brand-500/0 to-brand-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                        <div class="w-10 h-10 rounded-lg bg-black overflow-hidden mr-3 border border-white/5 shadow-lg group-hover:scale-110 transition-transform duration-500 flex-shrink-0">
                                            <template x-if="item.image">
                                                <img :src="item.image.includes('http') ? item.image : '/storage/' + item.image"
                                                     :alt="item.name"
                                                     class="w-full h-full object-cover"
                                                     onerror="this.src = 'https://ui-avatars.com/api/?name=' + this.alt + '&background=0f172a&color=0ea5e9'">
                                            </template>
                                            <div class="w-full h-full flex items-center justify-center text-gray-800" x-show="!item.image">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                            </div>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="block text-[11px] font-black text-white truncate group-hover:text-brand-400 transition-colors uppercase tracking-tight" x-text="item.name"></span>
                                            <span class="text-[8px] font-bold text-gray-500 uppercase tracking-widest" x-text="item.category"></span>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- Navigation Links -->
                    <div>
                        <h4 class="px-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] mb-3" x-text="paletteSearch.length >= 2 ? 'Related Systems' : 'System Navigation'"></h4>
                        <div class="space-y-1">
                            <template x-for="(link, index) in filteredLinks()" :key="link.url">
                                <a :href="link.url"
                                   :data-index="(paletteSearch.length >= 2 ? liveResults.length : recentResearch.length) + index"
                                   :class="selectedIndex === (paletteSearch.length >= 2 ? liveResults.length : recentResearch.length) + index ? 'bg-white/10 border-white/20' : 'hover:bg-white/5 border-transparent'"
                                   class="flex items-center p-4 rounded-2xl border transition-all group">
                                    <div class="min-w-0 flex-1">
                                        <span class="block text-white font-black uppercase tracking-widest text-[11px]" x-text="link.name"></span>
                                        <span class="text-xs text-gray-500" x-text="link.desc"></span>
                                    </div>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-brand-400" x-text="link.icon"></span>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-4 bg-black/20 border-t border-white/5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 px-4 sm:px-8">
                    <div class="flex items-center space-x-4 sm:space-x-6">
                        <div class="flex items-center space-x-1">
                            <span class="px-1.5 py-0.5 bg-gray-800 rounded text-[10px] text-gray-400 font-bold">ESC</span>
                            <span class="text-[10px] text-gray-600 font-bold uppercase">Close</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <span class="px-1.5 py-0.5 bg-gray-800 rounded text-[10px] text-gray-400 font-bold">Enter</span>
                            <span class="text-[10px] text-gray-600 font-bold uppercase">Navigate</span>
                        </div>
                    </div>
                    <span class="text-[10px] text-gray-700 font-black tracking-widest uppercase italic">Wiki Protocol [v.2]</span>
                </div>
            </div>
        </div>

        <!-- Background Decorations -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
            <!-- Dynamic Mouse Glow (Aether Atmosphere) -->
            <div class="hidden lg:block absolute transition-opacity duration-700 pointer-events-none opacity-0"
                 :style="{
                    left: (mouseX - 400) + 'px',
                    top: (mouseY - 400) + 'px',
                    opacity: (mouseX === 0 ? 0 : 1)
                 }">
                <div class="w-[800px] h-[800px] bg-brand-500/10 blur-[150px] rounded-full"></div>
            </div>

            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-brand-600/20 blur-[120px] rounded-full animate-pulse"></div>
            <div class="absolute top-[20%] -right-[10%] w-[35%] h-[35%] bg-accent-400/10 blur-[120px] rounded-full" style="animation: float 8s ease-in-out infinite;"></div>
            <div class="absolute -bottom-[10%] left-[20%] w-[30%] h-[30%] bg-brand-400/10 blur-[120px] rounded-full"></div>
        </div>

        <!-- Theme Preview Panel -->
        <div class="fixed bottom-24 right-4 z-40" x-data>
            <button @click="themePanelOpen = !themePanelOpen" class="px-3 py-2 rounded-xl bg-white/10 border border-white/20 text-xs font-bold">Theme</button>
            <div x-show="themePanelOpen" x-transition class="mt-2 w-56 p-3 glass-card rounded-2xl border border-white/10 space-y-2">
                <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Theme Preset</p>
                <div class="grid grid-cols-3 gap-2">
                    <button @click="applyTheme('overworld', themeMode)" class="h-8 rounded-lg bg-emerald-500/70"></button>
                    <button @click="applyTheme('nether', themeMode)" class="h-8 rounded-lg bg-red-500/70"></button>
                    <button @click="applyTheme('end', themeMode)" class="h-8 rounded-lg bg-purple-500/70"></button>
                </div>
                <div class="flex gap-2">
                    <button @click="applyTheme(themePreset, 'dark')" class="flex-1 px-2 py-1 text-xs rounded-lg border border-white/20">Dark</button>
                    <button @click="applyTheme(themePreset, 'light')" class="flex-1 px-2 py-1 text-xs rounded-lg border border-white/20">Light</button>
                </div>
                <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Accessibility Pack</p>
                <label class="flex items-center justify-between text-xs"><span>High Contrast</span><input type="checkbox" @click="toggleA11y('highContrast')" :checked="accessibility.highContrast"></label>
                <label class="flex items-center justify-between text-xs"><span>Reduced Transparency</span><input type="checkbox" @click="toggleA11y('reducedTransparency')" :checked="accessibility.reducedTransparency"></label>
                <label class="flex items-center justify-between text-xs"><span>Dyslexia Font</span><input type="checkbox" @click="toggleA11y('dyslexiaFont')" :checked="accessibility.dyslexiaFont"></label>
            </div>
        </div>

        <!-- Secret Admin Terminal -->
        <div x-show="terminalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed inset-0 z-[110] flex items-center justify-center p-4 sm:p-6 bg-black/80 backdrop-blur-md"
             x-cloak>

            <div @click.away="terminalOpen = false"
                 class="w-full max-w-3xl terminal-glass border border-red-500/30 rounded-lg shadow-[0_0_50px_rgba(239,68,68,0.2)] overflow-hidden flex flex-col font-mono text-sm relative">

                <!-- Scanline Effect -->
                <div class="scanline"></div>

                <!-- Terminal Header -->
                <div class="px-4 py-2 bg-red-950/20 border-b border-red-500/20 flex justify-between items-center relative z-10">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></div>
                        <span class="text-[10px] text-red-500 font-bold uppercase tracking-widest oracle-glow">Master Override Terminal</span>
                    </div>
                    <button @click="terminalOpen = false" class="text-red-500 hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </button>
                </div>

                <!-- Terminal Body -->
                <div id="terminal-scroll" class="p-4 h-80 overflow-y-auto space-y-1 bg-black/40 terminal-grid relative z-10">
                    <template x-for="(line, index) in terminalOutput" :key="index">
                        <div class="text-red-400/80 break-words"
                             :class="line.startsWith('ORACLE:') ? 'text-red-300 font-black oracle-glow' : ''"
                             x-text="line"></div>
                    </template>
                </div>

                <!-- Terminal Input -->
                <div class="p-4 bg-red-950/10 border-t border-red-500/20">
                    <div class="flex items-center space-x-2">
                        <span class="text-red-500 font-black">#</span>
                        <input type="text"
                               x-model="terminalInput"
                               x-ref="terminalInput"
                               @show.window="if(terminalOpen) $nextTick(() => $refs.terminalInput.focus())"
                               @keydown.enter="executeTerminal()"
                               class="flex-1 bg-transparent border-none focus:ring-0 text-red-500 font-bold p-0"
                               placeholder="Type command (help)..." />
                    </div>
                </div>
            </div>
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
            <main class="pb-32 md:pb-0">
                {{ $slot }}
            </main>

            <!-- Floating Mobile Navigation -->
            <div class="fixed bottom-4 left-1/2 -translate-x-1/2 z-40 w-[calc(100%-1rem)] max-w-md md:hidden nav-appear"
                 x-data="{ active: '{{ Route::currentRouteName() }}' }">
                <div class="glass-card rounded-[1.75rem] p-2 px-3 sm:px-4 flex items-center justify-between border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.5)] text-xs font-bold uppercase tracking-wide">
                    <a href="{{ route('mobs.index') }}" class="p-3 transition-all rounded-full"
                       :class="active === 'mobs.index' ? 'bg-brand-500 text-white shadow-lg shadow-brand-500/40' : 'text-gray-500 hover:text-white'">
                        <span>Index</span>
                    </a>
                    <a href="{{ route('dimensions.index') }}" class="p-3 transition-all rounded-full"
                       :class="active === 'dimensions.index' ? 'bg-brand-500 text-white shadow-lg shadow-brand-500/40' : 'text-gray-500 hover:text-white'">
                        <span>Worlds</span>
                    </a>
                    <a href="{{ route('mobs.create') }}" class="p-3 transition-all rounded-full"
                       :class="active === 'mobs.create' ? 'bg-brand-500 text-white shadow-lg shadow-brand-500/40' : 'text-gray-500 hover:text-white'">
                        <span>New</span>
                    </a>
                    <a href="{{ route('mobs.comparison') }}" class="p-3 transition-all rounded-full"
                       :class="active === 'mobs.comparison' ? 'bg-brand-500 text-white shadow-lg shadow-brand-500/40' : 'text-gray-500 hover:text-white'">
                        <span>Compare</span>
                    </a>
                </div>
            </div>

            <!-- Mobile Quick Trigger -->
            <button @click="paletteOpen = true"
                    class="fixed bottom-24 right-4 z-40 w-11 h-11 bg-brand-600 text-white rounded-full shadow-2xl flex items-center justify-center border border-white/20 md:hidden nav-appear animate-float"
                    style="animation-delay: 200ms">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
        </div>

        <x-toast />
    </body>
</html>
