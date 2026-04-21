<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-16 relative">
                <h1 class="text-4xl font-black text-white tracking-tight mb-4">Biomes <span class="text-brand-500">& Dimensions</span></h1>
                <p class="text-gray-400 max-w-2xl mx-auto mb-6">Explore the vast ecosystems of the Minecraft world, from the peaceful plains of the Overworld to the scorched valleys of the Nether.</p>

                @if(Auth::check() && Auth::user()->is_admin)
                    <a href="{{ route('admin.biomes.create') }}" class="inline-flex items-center space-x-2 bg-gradient-to-r from-red-600 to-red-900 border border-red-400/50 px-6 py-3 rounded-full shadow-[0_0_15px_rgba(239,68,68,0.5)] hover:shadow-[0_0_30px_rgba(239,68,68,0.8)] hover:-translate-y-1 transition-all duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span class="text-white font-black tracking-widest uppercase text-xs">Deploy New Biome</span>
                    </a>
                @endif
            </div>

            <!-- Dimension Sections -->
            @foreach($dimensions as $dimension)
                <div class="mb-24 last:mb-0">
                    <div class="flex items-center space-x-4 mb-8">
                        <div class="w-12 h-1 bg-gradient-to-r from-transparent
                            {{ $dimension->color_theme == 'green' ? 'to-green-500' : '' }}
                            {{ $dimension->color_theme == 'red' ? 'to-red-500' : '' }}
                            {{ $dimension->color_theme == 'purple' ? 'to-purple-500' : '' }}
                        "></div>
                        <h2 class="text-2xl font-black text-white uppercase tracking-[0.2em]">{{ $dimension->name }}</h2>
                        <div class="text-[10px] font-mono text-gray-600">ID:0{{ $dimension->id }}</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @forelse($dimension->biomes as $biome)
                            <!-- Top-level Biome Card -->
                            <a href="{{ route('biomes.show', $biome) }}" class="glass-card rounded-[2.5rem] overflow-hidden group hover:-translate-y-2 transition-all duration-500 relative">
                                <div class="aspect-video relative overflow-hidden bg-gray-900">
                                    @if($biome->image)
                                        <img src="{{ asset('storage/' . $biome->image) }}" alt="{{ $biome->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-800 to-gray-900">
                                            <svg class="w-12 h-12 text-gray-700 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="1.5"></path></svg>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                                    {{-- Sub-biome badge --}}
                                    @if($biome->subBiomes->count() > 0)
                                        <div class="absolute top-4 left-4 flex items-center space-x-1.5 bg-black/60 backdrop-blur-sm border border-white/10 px-3 py-1 rounded-full">
                                            <svg class="w-3 h-3 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                                            <span class="text-[9px] font-black uppercase tracking-widest text-brand-300">{{ $biome->subBiomes->count() }} Sub-biomes</span>
                                        </div>
                                    @endif

                                    <div class="absolute bottom-6 left-6">
                                        <h3 class="text-xl font-bold text-white mb-1">{{ $biome->name }}</h3>
                                        <div class="flex items-center space-x-3">
                                            <span class="text-[10px] text-gray-400 uppercase tracking-widest font-black flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                                                {{ $biome->mobs->count() }} Creatures
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <p class="text-gray-500 text-xs italic line-clamp-2">"{{ $biome->description }}"</p>

                                    @if($biome->subBiomes->count() > 0)
                                        <div class="mt-4 pt-4 border-t border-white/5">
                                            <p class="text-[9px] font-black uppercase tracking-widest text-gray-600 mb-3">Known Variants</p>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($biome->subBiomes->take(4) as $sub)
                                                    <span class="px-2.5 py-1 text-[9px] font-bold uppercase tracking-wider bg-brand-500/10 border border-brand-500/20 text-brand-300 rounded-full">{{ $sub->name }}</span>
                                                @endforeach
                                                @if($biome->subBiomes->count() > 4)
                                                    <span class="px-2.5 py-1 text-[9px] font-bold uppercase tracking-wider bg-white/5 text-gray-500 rounded-full">+{{ $biome->subBiomes->count() - 4 }} more</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="col-span-full py-12 text-center glass-card rounded-[2rem]">
                                <p class="text-gray-500 uppercase tracking-widest text-[10px] font-black">No biomes explored in this dimension yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
