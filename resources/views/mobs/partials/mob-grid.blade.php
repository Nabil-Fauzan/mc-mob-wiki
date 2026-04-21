<!-- Mobs Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 sm:gap-8 mb-12">
    @forelse($mobs as $mob)
        @php
            preg_match('/\d+/', $mob->damage_normal ?: $mob->damage, $dM);
            $isThreatening = intval($dM[0] ?? 0) >= 10;
        @endphp
        <div class="glass-card rounded-[1.5rem] sm:rounded-[2rem] overflow-hidden group hover:-translate-y-2 transition-all duration-500 stagger-item" 
             style="animation-delay: {{ ($loop->index % 8) * 75 }}ms">
            <div class="aspect-[4/5] bg-gray-900 relative overflow-hidden {{ $isThreatening ? 'threat-pulse' : '' }}">
                @if($mob->image)
                    <img src="{{ asset('storage/' . $mob->image) }}" alt="{{ $mob->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-800 to-gray-900">
                        <svg class="w-16 h-16 text-gray-700 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
                
                <!-- Category Badge -->
                <div class="absolute top-3 right-3 sm:top-4 sm:right-4 flex flex-col space-y-2">
                    <span class="px-2.5 sm:px-3 py-1 text-[9px] sm:text-[10px] font-black uppercase tracking-widest rounded-full backdrop-blur-md border
                        {{ $mob->category->name == 'Hostile' ? 'bg-red-500/20 text-red-400 border-red-500/30' : '' }}
                        {{ $mob->category->name == 'Passive' ? 'bg-green-500/20 text-green-400 border-green-500/30' : '' }}
                        {{ $mob->category->name == 'Neutral' ? 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30' : '' }}
                    ">
                        {{ $mob->category->name }}
                    </span>
                    <button @click.prevent.stop="toggleCompare({{ $mob->id }}, '{{ $mob->name }}', '{{ asset('storage/' . $mob->image) }}')" 
                        class="p-2 backdrop-blur-md border border-white/10 rounded-full transition-all group/btn"
                        :class="isComparing({{ $mob->id }}) ? 'bg-indigo-600 border-indigo-500' : 'bg-black/20 hover:bg-white/10'">
                        <svg class="w-4 h-4 transition-transform group-hover/btn:scale-125" :class="isComparing({{ $mob->id }}) ? 'text-white' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" x-show="!isComparing({{ $mob->id }})"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" x-show="isComparing({{ $mob->id }})"></path>
                        </svg>
                    </button>
                    <!-- Favorite Toggle -->
                    <div class="flex flex-col items-center">
                        <button @click.prevent.stop="toggleFavorite({{ $mob->id }})" 
                            class="p-2 bg-black/20 backdrop-blur-md border border-white/10 rounded-full hover:bg-white/10 transition-all group/fav">
                            <svg class="w-4 h-4 transition-all duration-300 group-hover/fav:scale-125 favorite-heart-{{ $mob->id }} {{ $mob->is_favorited ? 'text-red-500 fill-current' : 'text-gray-400' }}" 
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                        <span class="text-[9px] font-black text-white mt-1 favorite-count-{{ $mob->id }}">{{ $mob->favorited_by_count }}</span>
                    </div>
                </div>

                <!-- Overlay on Hover -->
                <div class="absolute inset-x-0 bottom-0 p-4 sm:p-6 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-100 sm:opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <a href="{{ route('mobs.show', $mob) }}" class="w-full py-2 bg-white text-black text-center text-sm font-bold rounded-xl block sm:transform sm:translate-y-4 sm:group-hover:translate-y-0 transition-transform duration-500">
                        Quick View
                    </a>
                </div>
            </div>

            <div class="p-4 sm:p-6">
                <h3 class="text-lg sm:text-xl font-bold text-white mb-2 underline decoration-indigo-500/30 group-hover:decoration-indigo-500 transition-colors">{{ $mob->name }}</h3>
                <div class="flex flex-wrap items-center gap-1.5 mb-4 max-h-[24px] overflow-hidden">
                    @forelse($mob->biomes as $biome)
                        <span class="text-[10px] text-indigo-400/80 bg-indigo-500/5 border border-indigo-500/10 px-2 py-0.5 rounded-full whitespace-nowrap">
                            {{ $biome->name }}
                        </span>
                        @if($loop->iteration >= 2 && $mob->biomes->count() > 2)
                            <span class="text-[10px] text-gray-600 font-bold">+{{ $mob->biomes->count() - 2 }}</span>
                            @break
                        @endif
                    @empty
                        <span class="text-[10px] text-gray-500 bg-white/5 px-2 py-0.5 rounded-full">Global Presence</span>
                    @endforelse
                </div>

                <p class="text-sm text-gray-400 mb-5 sm:mb-6 italic leading-relaxed">
                    "{{ Str::limit($mob->description, 100) }}"
                </p>

                <!-- Direct Loot Preview -->
                <div class="mb-6 pt-4 border-t border-white/5">
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest block mb-2">Loot Intelligence Preview</span>
                    <div class="flex flex-wrap gap-1.5">
                        @forelse($mob->loot->take(4) as $drop)
                            <div class="flex items-center space-x-1 px-1.5 py-1 bg-white/5 rounded-md border border-white/5 hover:border-yellow-500/30 transition-all cursor-help" title="{{ $drop->item_name }}">
                                <span class="text-xs">{{ $drop->icon ?: '📦' }}</span>
                                <span class="text-[8px] text-gray-400 font-bold uppercase truncate max-w-[56px] sm:max-w-[40px]">{{ $drop->item_name }}</span>
                            </div>
                        @empty
                            <span class="text-[8px] text-gray-600 italic">No loot metadata</span>
                        @endforelse
                        @if($mob->loot->count() > 4)
                            <div class="flex items-center px-1.5 py-1 bg-white/5 rounded-md border border-white/5">
                                <span class="text-[8px] text-gray-400 font-bold">+{{ $mob->loot->count() - 4 }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="flex justify-between items-center pt-4 border-t border-white/5 gap-3">
                    <div class="flex -space-x-2">
                        @auth
                            <a href="{{ route('mobs.edit', $mob) }}" class="p-2 bg-indigo-500/10 text-indigo-400 rounded-lg hover:bg-indigo-500 hover:text-white transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            </a>
                            <form action="{{ route('mobs.destroy', $mob) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this mob entries?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-red-500/10 text-red-400 rounded-lg hover:bg-red-500 hover:text-white transition-all ml-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                </button>
                            </form>
                        @endauth
                    </div>
                    <div class="text-[10px] text-gray-600 font-mono tracking-tighter text-right">
                        {{ strtoupper($mob->category->name) }} // {{ $mob->id }}
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full glass-card p-20 text-center rounded-[3rem]">
            <div class="w-20 h-20 bg-gray-800 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">No Mobs Discovered</h3>
            <p class="text-gray-500">We couldn't find any mobs matching your current search parameters.</p>
            <button @click="search = ''; category = ''; fetchResults()" class="mt-8 inline-block text-indigo-500 font-bold hover:text-indigo-400">Clear Search Criteria</button>
        </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-16 pagination-ajax">
    {{ $mobs->links() }}
</div>
