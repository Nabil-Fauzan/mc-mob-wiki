<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl font-black text-white tracking-tight">Minecraft <span class="text-indigo-500">Wiki</span></h1>
                <p class="text-gray-400 text-sm">Explore the database of overworld and beyond.</p>
            </div>
            @auth
                <a href="{{ route('mobs.create') }}" class="btn-primary-mc">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>New Mob Entry</span>
                </a>
            @endauth
        </div>
    </x-slot>

    <div x-data="{ 
        search: '{{ request('search') }}', 
        category: '{{ request('category') }}',
        biome: '{{ request('biome') }}',
        loot_search: '{{ request('loot_search') }}',
        is_melee: {{ request('is_melee') == 'true' ? 'true' : 'false' }},
        is_ranged: {{ request('is_ranged') == 'true' ? 'true' : 'false' }},
        sort: '{{ request('sort', 'newest') }}',
        showAdvanced: false,
        isLoading: false,
        compareList: [],
        
        init() {
            this.initPaginationLinks();
            const saved = localStorage.getItem('mob_compare_list');
            if (saved) {
                this.compareList = JSON.parse(saved);
            }
        },

        toggleCompare(id, name, image) {
            const index = this.compareList.findIndex(m => m.id === id);
            if (index === -1) {
                if (this.compareList.length >= 3) {
                    alert('You can only compare up to 3 mobs at once.');
                    return;
                }
                this.compareList.push({ id, name, image });
            } else {
                this.compareList.splice(index, 1);
            }
            localStorage.setItem('mob_compare_list', JSON.stringify(this.compareList));
        },

        isComparing(id) {
            return this.compareList.some(m => m.id === id);
        },

        get comparisonUrl() {
            const ids = this.compareList.map(m => m.id).join(',');
            return `{{ route('mobs.comparison') }}?ids=${ids}`;
        },
        
        async fetchResults() {
            this.isLoading = true;
            const params = new URLSearchParams({
                search: this.search,
                category: this.category,
                biome: this.biome,
                loot_search: this.loot_search,
                is_melee: this.is_melee,
                is_ranged: this.is_ranged,
                sort: this.sort
            });
            
            const url = `{{ route('mobs.index') }}?${params.toString()}`;
            
            try {
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const html = await response.text();
                document.getElementById('mob-grid-container').innerHTML = html;
                window.history.pushState({}, '', url);
                this.initPaginationLinks();
            } catch (error) {
                console.error('Error fetching results:', error);
            } finally {
                this.isLoading = false;
            }
        },

        resetFilters() {
            this.search = '';
            this.category = '';
            this.biome = '';
            this.loot_search = '';
            this.is_melee = false;
            this.is_ranged = false;
            this.sort = 'newest';
            this.fetchResults();
        },

        initPaginationLinks() {
            document.querySelectorAll('#mob-grid-container .pagination-ajax a').forEach(link => {
                link.addEventListener('click', async (e) => {
                    e.preventDefault();
                    this.isLoading = true;
                    const pageUrl = link.getAttribute('href');
                    try {
                        const response = await fetch(pageUrl, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const html = await response.text();
                        document.getElementById('mob-grid-container').innerHTML = html;
                        window.history.pushState({}, '', pageUrl);
                        this.initPaginationLinks();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    } catch (error) {
                        console.error('Pagination error:', error);
                    } finally {
                        this.isLoading = false;
                    }
                });
            });
        },

        async toggleFavorite(id) {
            try {
                const response = await fetch(`/mobs/${id}/favorite`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });
                if (response.status === 401) {
                    window.location.href = '{{ route('login') }}';
                    return;
                }
                const data = await response.json();
                const heartIcons = document.querySelectorAll(`.favorite-heart-${id}`);
                const heartCounts = document.querySelectorAll(`.favorite-count-${id}`);
                heartIcons.forEach(icon => {
                    if (data.favorited) {
                        icon.classList.add('text-red-500', 'fill-current');
                        icon.classList.remove('text-gray-400');
                    } else {
                        icon.classList.remove('text-red-500', 'fill-current');
                        icon.classList.add('text-gray-400');
                    }
                });
                heartCounts.forEach(count => { count.textContent = data.count; });
            } catch (error) {
                console.error('Error toggling favorite:', error);
            }
        }
    }" class="py-12 relative overflow-hidden">
        <!-- Floating Comparison Bar (Remains same) -->
        <div x-show="compareList.length > 0" 
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="translate-y-full opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-y-0 opacity-100"
             x-transition:leave-end="translate-y-full opacity-0"
             class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 w-full max-w-2xl px-4">
            <div class="glass-card rounded-full p-3 pl-6 flex items-center justify-between border-indigo-500/30 shadow-[0_0_50px_rgba(79,70,229,0.3)]">
                <div class="flex items-center -space-x-3 overflow-hidden">
                    <template x-for="mob in compareList" :key="mob.id">
                        <div class="w-10 h-10 rounded-full border-2 border-[#020617] overflow-hidden bg-gray-950 group relative">
                            <img :src="mob.image" :alt="mob.name" class="w-full h-full object-cover">
                            <button @click="toggleCompare(mob.id)" class="absolute inset-0 bg-red-600/80 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </template>
                    <div class="pl-6 flex flex-col">
                        <span class="text-xs font-black text-white uppercase tracking-widest"><span x-text="compareList.length"></span>/3 Selected</span>
                        <span class="text-[10px] text-gray-400">Comparing Stats</span>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button @click="compareList = []; localStorage.removeItem('mob_compare_list')" class="text-xs text-gray-500 hover:text-white transition-colors uppercase font-bold tracking-tighter">Clear All</button>
                    <a :href="comparisonUrl" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-black rounded-full transition-all shadow-lg shadow-indigo-600/40">
                        Compare Now
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Search and Filter Module -->
            <div class="glass-card rounded-[2.5rem] mb-12 overflow-hidden border-indigo-500/10 shadow-2xl">
                <div class="p-8 lg:p-10">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <!-- Primary Search -->
                        <div class="md:col-span-2">
                            <x-input-label for="search" :value="__('Global Entity Search')" class="text-gray-500 mb-2 font-black uppercase tracking-widest text-[9px]" />
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-indigo-500">
                                    <svg class="h-5 w-5 text-gray-600 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </span>
                                <input id="search" type="text" 
                                    x-model.debounce.300ms="search"
                                    @input="fetchResults()"
                                    class="block w-full pl-12 pr-4 py-4 bg-white/5 border-white/5 rounded-[1.25rem] text-white placeholder-gray-600 focus:ring-2 focus:ring-indigo-500/50 focus:bg-white/10 transition-all font-bold" 
                                    placeholder="Search by name or description..." />
                            </div>
                        </div>

                        <!-- Classification Filter -->
                        <div>
                            <x-input-label for="category" :value="__('Classification')" class="text-gray-500 mb-2 font-black uppercase tracking-widest text-[9px]" />
                            <div class="relative">
                                <select id="category" 
                                    x-model="category"
                                    @change="fetchResults()"
                                    class="block w-full py-4 pl-4 pr-10 bg-white/5 border-white/5 rounded-[1.25rem] text-white focus:ring-2 focus:ring-indigo-500/50 transition-all appearance-none cursor-pointer font-bold">
                                    <option value="" class="bg-gray-950 text-gray-500">All Species</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" class="bg-gray-950 text-white font-bold">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-600">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 7.293 8.172 5.858 9.61l3.435 3.34z"/></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Sorting -->
                        <div>
                            <x-input-label for="sort" :value="__('Intelligence Priority')" class="text-gray-500 mb-2 font-black uppercase tracking-widest text-[9px]" />
                            <div class="relative">
                                <select id="sort" 
                                    x-model="sort"
                                    @change="fetchResults()"
                                    class="block w-full py-4 pl-4 pr-10 bg-white/5 border-white/5 rounded-[1.25rem] text-white focus:ring-2 focus:ring-indigo-500/50 transition-all appearance-none cursor-pointer font-bold">
                                    <option value="newest" class="bg-gray-950 text-white font-bold">Latest Discovery</option>
                                    <option value="name_asc" class="bg-gray-950 text-white font-bold">Name (A-Z)</option>
                                    <option value="health_desc" class="bg-gray-950 text-white font-bold">Vitality (Highest)</option>
                                    <option value="damage_desc" class="bg-gray-950 text-white font-bold">Lethality (Highest)</option>
                                    <option value="xp_desc" class="bg-gray-950 text-white font-bold">Resource Value (XP)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-600">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 7.293 8.172 5.858 9.61l3.435 3.34z"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Advanced Filter Trigger -->
                    <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-4 border-t border-white/5 pt-8">
                        <button @click="showAdvanced = !showAdvanced" 
                            class="flex items-center space-x-2 text-xs font-black uppercase tracking-widest text-indigo-400 hover:text-indigo-300 transition-colors">
                            <span x-text="showAdvanced ? 'Hide Advanced Filters' : 'Show Advanced Filters'"></span>
                            <svg class="w-4 h-4 transition-transform duration-300" :class="showAdvanced ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        
                        <div class="flex items-center space-x-4">
                            <button @click="resetFilters()" 
                                x-show="search || category || biome || loot_search || is_melee || is_ranged || sort !== 'newest'"
                                x-transition
                                class="text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-red-500 transition-colors">
                                Reset Parameter Intelligence
                            </button>
                            <div x-show="isLoading" class="flex items-center text-indigo-500 text-[10px] font-bold uppercase tracking-[0.2em] animate-pulse">
                                <svg class="animate-spin h-3 w-3 mr-2" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Syncing...
                            </div>
                        </div>
                    </div>

                    <!-- Advanced Filter Panel -->
                    <div x-show="showAdvanced" 
                        x-collapse
                        class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-8 p-8 bg-black/20 rounded-[1.5rem] border border-white/5">
                        
                        <!-- Biome Filter (Grouped) -->
                        <div>
                            <x-input-label for="biome" :value="__('Habitat Intelligence (Biome)')" class="text-gray-500 mb-2 font-black uppercase tracking-widest text-[9px]" />
                            <div class="relative">
                                <select id="biome" 
                                    x-model="biome"
                                    @change="fetchResults()"
                                    class="block w-full py-3 pl-4 pr-10 bg-white/5 border-white/5 rounded-xl text-white focus:ring-2 focus:ring-indigo-500/50 appearance-none cursor-pointer font-bold text-sm">
                                    <option value="" class="bg-gray-950 text-gray-500">All Biomes</option>
                                    @foreach($allBiomes->groupBy('dimension.name') as $dimension => $biomes)
                                        <optgroup label="{{ $dimension }}" class="bg-gray-950 text-indigo-400 uppercase font-black text-[10px]">
                                            @foreach($biomes as $b)
                                                <option value="{{ $b->id }}" class="bg-gray-950 text-white font-medium">
                                                    {{ $b->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-600">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 7.293 8.172 5.858 9.61l3.435 3.34z"/></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Loot Search (Partial Match) -->
                        <div>
                            <x-input-label for="loot_search" :value="__('Loot Intelligence (Keyword)')" class="text-gray-500 mb-2 font-black uppercase tracking-widest text-[9px]" />
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-600 group-focus-within:text-yellow-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </span>
                                <input id="loot_search" type="text" 
                                    x-model.debounce.400ms="loot_search"
                                    @input="fetchResults()"
                                    class="block w-full pl-10 pr-4 py-3 bg-white/5 border-white/5 rounded-xl text-white placeholder-gray-600 focus:ring-2 focus:ring-yellow-500/50 transition-all font-bold text-sm" 
                                    placeholder="e.g. Iron, Pearl, Gunpowder..." />
                            </div>
                        </div>

                        <!-- Combat Behavior Toggles -->
                        <div>
                            <x-input-label :value="__('Combat Matrix Proximity')" class="text-gray-500 mb-2 font-black uppercase tracking-widest text-[9px]" />
                            <div class="flex items-center space-x-6 mt-3">
                                <label class="flex items-center cursor-pointer group">
                                    <div class="relative">
                                        <input type="checkbox" x-model="is_melee" @change="fetchResults()" class="sr-only">
                                        <div class="w-10 h-5 bg-white/5 rounded-full shadow-inner transition-colors" :class="is_melee ? 'bg-red-500/40' : 'bg-white/5'"></div>
                                        <div class="absolute left-0 top-0 w-5 h-5 bg-gray-500 rounded-full shadow transition-transform" :class="is_melee ? 'translate-x-full bg-red-500' : ''"></div>
                                    </div>
                                    <span class="ml-3 text-xs font-black uppercase tracking-tighter" :class="is_melee ? 'text-red-400' : 'text-gray-600'">Melee</span>
                                </label>
                                <label class="flex items-center cursor-pointer group">
                                    <div class="relative">
                                        <input type="checkbox" x-model="is_ranged" @change="fetchResults()" class="sr-only">
                                        <div class="w-10 h-5 bg-white/5 rounded-full shadow-inner transition-colors" :class="is_ranged ? 'bg-blue-500/40' : 'bg-white/5'"></div>
                                        <div class="absolute left-0 top-0 w-5 h-5 bg-gray-500 rounded-full shadow transition-transform" :class="is_ranged ? 'translate-x-full bg-blue-500' : ''"></div>
                                    </div>
                                    <span class="ml-3 text-xs font-black uppercase tracking-tighter" :class="is_ranged ? 'text-blue-400' : 'text-gray-600'">Ranged</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobs Grid Container -->
            <div id="mob-grid-container" :class="{ 'opacity-50 pointer-events-none transition-opacity duration-300': isLoading }">
                @include('mobs.partials.mob-grid')
            </div>
        </div>
    </div>
</x-app-layout>
