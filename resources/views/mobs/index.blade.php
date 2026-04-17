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
        sort: '{{ request('sort', 'newest') }}',
        isLoading: false,
        compareList: [],
        
        init() {
            this.initPaginationLinks();
            // Load comparison list from localStorage
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
                sort: this.sort
            });
            
            const url = `{{ route('mobs.index') }}?${params.toString()}`;
            
            try {
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const html = await response.text();
                document.getElementById('mob-grid-container').innerHTML = html;
                
                // Update URL without reload
                window.history.pushState({}, '', url);
                
                // Re-initialize any listeners if necessary
                this.initPaginationLinks();
            } catch (error) {
                console.error('Error fetching results:', error);
            } finally {
                this.isLoading = false;
            }
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
                
                // Update the heart icons and counts manually in the DOM for instant feedback
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
                
                heartCounts.forEach(count => {
                    count.textContent = data.count;
                });

            } catch (error) {
                console.error('Error toggling favorite:', error);
            }
        }
    }" class="py-12 relative overflow-hidden">
        <!-- Floating Comparison Bar -->
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
            <!-- Search and Filter -->
            <div class="glass-card rounded-[2rem] mb-12 overflow-hidden">
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div>
                            <x-input-label for="search" :value="__('Search Species')" class="text-gray-400 mb-2 ml-1" />
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </span>
                                <input id="search" type="text" 
                                    x-model.debounce.300ms="search"
                                    @input="fetchResults()"
                                    class="block w-full pl-10 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" 
                                    placeholder="Creeper, Enderman..." />
                            </div>
                        </div>
                        <div>
                            <x-input-label for="category" :value="__('Classification')" class="text-gray-400 mb-2 ml-1" />
                            <div class="relative">
                                <select id="category" 
                                    x-model="category"
                                    @change="fetchResults()"
                                    class="block w-full bg-white/5 border border-white/10 rounded-xl text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all appearance-none cursor-pointer">
                                    <option value="" class="bg-gray-900 text-gray-400">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" class="bg-gray-900 text-white">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 7.293 8.172 5.858 9.61l3.435 3.34z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <x-input-label for="sort" :value="__('Sort Intelligence')" class="text-gray-400 mb-2 ml-1" />
                            <div class="relative">
                                <select id="sort" 
                                    x-model="sort"
                                    @change="fetchResults()"
                                    class="block w-full bg-white/5 border border-white/10 rounded-xl text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all appearance-none cursor-pointer">
                                    <option value="newest" class="bg-gray-900 text-white">Latest Discovery</option>
                                    <option value="name_asc" class="bg-gray-900 text-white">Name (A-Z)</option>
                                    <option value="health_desc" class="bg-gray-900 text-white">Vitality (Highest)</option>
                                    <option value="damage_desc" class="bg-gray-900 text-white">Lethality (Highest)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 7.293 8.172 5.858 9.61l3.435 3.34z"/></svg>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-end space-x-3">
                            <button @click="search = ''; category = ''; sort = 'newest'; fetchResults()" 
                                x-show="search || category || sort !== 'newest'"
                                x-transition
                                class="px-6 py-2.5 bg-white/5 hover:bg-white/10 text-gray-300 rounded-xl border border-white/10 transition-all w-full md:w-auto">
                                Reset Filters
                            </button>
                            <div x-show="isLoading" class="flex items-center text-indigo-400 animate-pulse ml-auto px-4 py-2">
                                <svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Syncing...</span>
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
