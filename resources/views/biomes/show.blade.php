<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Breadcrumbs + Admin Controls -->
            <nav class="flex justify-between items-center mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 flex-wrap gap-y-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('biomes.index') }}" class="text-gray-400 hover:text-white text-sm font-medium transition-colors">Explorer</a>
                    </li>
                    @if($biome->parent)
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                                <a href="{{ route('biomes.show', $biome->parent) }}" class="ml-1 text-sm font-bold text-brand-400 hover:text-brand-300 transition-colors uppercase tracking-widest">{{ $biome->parent->name }}</a>
                            </div>
                        </li>
                    @endif
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 text-sm font-black text-white uppercase tracking-widest">{{ $biome->name }}</span>
                        </div>
                    </li>
                </ol>

                @if(Auth::check() && Auth::user()->is_admin)
                    <div class="flex items-center space-x-3">
                        {{-- Add sub-biome (only for top-level biomes) --}}
                        @if(!$biome->parent_id)
                            <a href="{{ route('admin.biomes.create', ['parent_id' => $biome->id]) }}" class="inline-flex items-center space-x-2 bg-brand-900/30 hover:bg-brand-600 border border-brand-500/30 hover:border-brand-500 px-4 py-2 rounded-xl transition-all group">
                                <svg class="w-4 h-4 text-brand-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <span class="text-brand-400 group-hover:text-white font-bold text-xs uppercase tracking-widest">Add Sub-biome</span>
                            </a>
                        @endif
                        <a href="{{ route('admin.biomes.edit', $biome) }}" class="inline-flex items-center space-x-2 bg-white/5 hover:bg-white/10 border border-white/10 px-4 py-2 rounded-xl transition-all">
                            <svg class="w-4 h-4 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <span class="text-gray-300 font-bold text-xs uppercase tracking-widest">Reconfigure</span>
                        </a>
                        <form action="{{ route('admin.biomes.destroy', $biome) }}" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to erase this from the registry? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center space-x-2 bg-red-900/20 hover:bg-red-600 border border-red-500/30 hover:border-red-500 px-4 py-2 rounded-xl transition-all group">
                                <svg class="w-4 h-4 text-red-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                <span class="text-red-500 group-hover:text-white font-bold text-xs uppercase tracking-widest">Eradicate</span>
                            </button>
                        </form>
                    </div>
                @endif
            </nav>

            <!-- Sub-biome indicator badge -->
            @if($biome->parent)
                <div class="inline-flex items-center space-x-2 bg-brand-500/10 border border-brand-500/20 px-4 py-2 rounded-full mb-6">
                    <svg class="w-4 h-4 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                    <span class="text-brand-300 text-xs font-black uppercase tracking-widest">Sub-biome of {{ $biome->parent->name }}</span>
                </div>
            @endif

            <!-- Hero Section -->
            <div class="glass-card rounded-[3rem] overflow-hidden mb-12">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    <div class="h-64 lg:h-auto overflow-hidden relative">
                        @if($biome->image)
                            <img src="{{ asset('storage/' . $biome->image) }}" alt="{{ $biome->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-800 to-gray-900">
                                <svg class="w-20 h-20 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="1.5"></path></svg>
                            </div>
                        @endif
                        <div class="absolute inset-x-0 bottom-0 p-8 bg-gradient-to-t from-black to-transparent lg:hidden">
                            <h1 class="text-4xl font-black text-white">{{ $biome->name }}</h1>
                        </div>
                    </div>
                    <div class="p-8 lg:p-12">
                        <div class="hidden lg:block mb-6">
                            <span class="text-xs font-black uppercase tracking-[0.3em] text-indigo-500 mb-2 block">Region Discovery</span>
                            <h1 class="text-5xl font-black text-white">{{ $biome->name }}</h1>
                        </div>
                        <p class="text-gray-400 leading-relaxed text-lg mb-8 italic">
                            "{{ $biome->description }}"
                        </p>
                        <div class="grid grid-cols-2 gap-8 border-t border-white/10 pt-8">
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-600 block mb-1">Atmosphere</span>
                                <span class="text-white font-bold">{{ $biome->dimension->name }} Dimension</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-600 block mb-1">Native Species</span>
                                <span class="text-white font-bold">{{ $biome->mobs->count() }} Creatures Cataloged</span>
                            </div>
                            @if($biome->subBiomes->count() > 0)
                                <div class="col-span-2">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-600 block mb-1">Known Variants</span>
                                    <span class="text-white font-bold">{{ $biome->subBiomes->count() }} Sub-biome{{ $biome->subBiomes->count() > 1 ? 's' : '' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sub-biomes Section (only for top-level biomes with sub-biomes) -->
            @if($biome->subBiomes->count() > 0)
                <div class="mb-16">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-4">
                            <h2 class="text-2xl font-bold text-white">Known <span class="text-brand-500">Variants</span></h2>
                            <div class="flex-1 h-[1px] bg-white/10 w-24"></div>
                        </div>
                        @if(Auth::check() && Auth::user()->is_admin)
                            <a href="{{ route('admin.biomes.create', ['parent_id' => $biome->id]) }}" class="inline-flex items-center space-x-2 bg-brand-900/20 border border-brand-500/20 hover:border-brand-500/60 px-4 py-2 rounded-xl transition-all text-brand-400 hover:text-brand-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <span class="text-xs font-black uppercase tracking-widest">New Variant</span>
                            </a>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                        @foreach($biome->subBiomes as $sub)
                            <a href="{{ route('biomes.show', $sub) }}" class="group glass-card rounded-[2rem] overflow-hidden hover:-translate-y-2 transition-all duration-500 border border-white/5 hover:border-brand-500/30 flex flex-col">
                                <div class="aspect-video relative overflow-hidden bg-gray-900 border-b border-white/5">
                                    @if($sub->image)
                                        <img src="{{ asset('storage/' . $sub->image) }}" alt="{{ $sub->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-brand-900/20 via-gray-900 to-black flex items-center justify-center relative">
                                            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-brand-500 via-transparent to-transparent"></div>
                                            <svg class="w-10 h-10 text-brand-500/30 opacity-50 group-hover:scale-125 group-hover:text-brand-400 group-hover:opacity-100 transition-all duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/20 to-transparent group-hover:opacity-70 transition-opacity"></div>
                                    
                                    <!-- Edit control (Admin) -->
                                    @if(Auth::check() && Auth::user()->is_admin)
                                        <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0">
                                            <object>
                                                <a href="{{ route('admin.biomes.edit', $sub) }}" class="p-2 bg-black/60 backdrop-blur-md rounded-xl border border-white/10 text-brand-400 hover:text-white hover:bg-brand-600 transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                            </object>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-6 flex-1 flex flex-col justify-between">
                                    <div>
                                        <h3 class="text-lg font-black text-white mb-2 group-hover:text-brand-400 transition-colors tracking-tight">{{ $sub->name }}</h3>
                                        <p class="text-gray-400 text-[11px] leading-relaxed italic line-clamp-3 mb-6">"{{ $sub->description }}"</p>
                                    </div>

                                    <div class="flex items-center justify-between pt-4 border-t border-white/5">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-1.5 h-1.5 rounded-full bg-brand-500 animate-pulse"></div>
                                            <span class="text-[9px] font-black uppercase tracking-[0.2em] text-gray-500">{{ $sub->mobs->count() }} Species</span>
                                        </div>
                                        <span class="text-[9px] font-black uppercase tracking-[0.2em] text-brand-500/80 group-hover:text-brand-400">Variant</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Inhabitant Mobs -->
            <div>
                <div class="flex items-center space-x-4 mb-8">
                    <h2 class="text-2xl font-bold text-white">Known <span class="text-brand-500">Inhabitants</span></h2>
                    <div class="flex-1 h-[1px] bg-white/10"></div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @forelse($biome->mobs as $mob)
                        <div class="glass-card rounded-[2rem] overflow-hidden group hover:-translate-y-2 transition-all duration-500">
                            <div class="aspect-[4/5] bg-gray-900 relative overflow-hidden">
                                @if($mob->image)
                                    <img src="{{ asset('storage/' . $mob->image) }}" alt="{{ $mob->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-800">
                                        <svg class="w-12 h-12 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="1.5"></path></svg>
                                    </div>
                                @endif
                                <div class="absolute top-4 right-4">
                                    <span class="px-3 py-1 text-[8px] font-black uppercase tracking-widest rounded-full backdrop-blur-md border border-white/10 bg-black/40 text-gray-300">
                                        {{ $mob->category->name }}
                                    </span>
                                </div>
                                <div class="absolute inset-x-0 bottom-0 p-6 bg-gradient-to-t from-black/80 to-transparent">
                                    <h3 class="text-xl font-bold text-white mb-2">{{ $mob->name }}</h3>
                                    <a href="{{ route('mobs.show', $mob) }}" class="text-xs text-brand-400 font-bold hover:text-brand-300 transition-colors flex items-center">
                                        View Full Intel
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-20 text-center glass-card rounded-[3rem]">
                            <h3 class="text-xl font-bold text-gray-600 uppercase tracking-widest">No creatures detected in this region.</h3>
                            <p class="text-gray-700 mt-2">Check back later for new discoveries.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
