<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Breadcrumbs -->
            <nav class="flex justify-between items-center mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('biomes.index') }}" class="text-gray-400 hover:text-white text-sm font-medium transition-colors">Explorer</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 text-sm font-black text-indigo-500 uppercase tracking-widest">{{ $biome->dimension->name }}</span>
                        </div>
                    </li>
                </ol>

                @if(Auth::check() && Auth::user()->is_admin)
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.biomes.edit', $biome) }}" class="inline-flex items-center space-x-2 bg-white/5 hover:bg-white/10 border border-white/10 px-4 py-2 rounded-xl transition-all">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <span class="text-gray-300 font-bold text-xs uppercase tracking-widest">Reconfigure</span>
                        </a>
                        <form action="{{ route('admin.biomes.destroy', $biome) }}" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to completely erase this ecosystem from the registry? This action cannot be undone.');">
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
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inhabitant Mobs -->
            <div>
                <div class="flex items-center space-x-4 mb-8">
                    <h2 class="text-2xl font-bold text-white">Known <span class="text-indigo-500">Inhabitants</span></h2>
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
                                    <a href="{{ route('mobs.show', $mob) }}" class="text-xs text-indigo-400 font-bold hover:text-indigo-300 transition-colors flex items-center">
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
