<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h1 class="text-4xl font-black text-white tracking-tight">Mob <span class="text-indigo-500">Comparison</span></h1>
                    <p class="text-gray-400 mt-2">Side-by-side analysis of Minecraft species and entities.</p>
                </div>
                <a href="{{ route('mobs.index') }}" class="px-6 py-2.5 bg-white/5 hover:bg-white/10 text-white font-bold rounded-xl border border-white/10 transition-all flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Wiki
                </a>
            </div>

            <!-- Comparison Grid -->
            <div class="grid grid-cols-1 md:grid-cols-{{ count($mobs) }} gap-8">
                @foreach($mobs as $mob)
                    <div class="glass-card rounded-[3rem] overflow-hidden group hover:-translate-y-2 transition-all duration-500">
                        <!-- Mob Hero Header -->
                        <div class="aspect-video relative overflow-hidden">
                            @if($mob->image)
                                <img src="{{ asset('storage/' . $mob->image) }}" alt="{{ $mob->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full bg-gray-900 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="1.5"></path></svg>
                                </div>
                            @endif
                            <div class="absolute inset-x-0 bottom-0 p-6 bg-gradient-to-t from-black/80 to-transparent">
                                <span class="text-[10px] font-black uppercase tracking-widest text-indigo-400 block mb-1">{{ $mob->category->name }}</span>
                                <h2 class="text-3xl font-black text-white tracking-tight">{{ $mob->name }}</h2>
                            </div>
                        </div>

                        <!-- Stats & Info -->
                        <div class="p-8 space-y-8">
                            <!-- Health Stat -->
                            <div>
                                <div class="flex justify-between items-end mb-3">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">Durability</span>
                                        <span class="text-lg font-bold text-white">Health Points</span>
                                    </div>
                                    <span class="text-2xl font-black text-red-500">{{ $mob->health }}</span>
                                </div>
                                <div class="h-2 bg-white/5 rounded-full overflow-hidden">
                                    @php
                                        preg_match('/\d+/', $mob->health, $matches);
                                        $healthVal = $matches[0] ?? 20;
                                        $healthPct = min(($healthVal / 100) * 100, 100);
                                    @endphp
                                    <div class="h-full bg-gradient-to-r from-red-600 to-red-400 rounded-full shadow-[0_0_10px_rgba(239,68,68,0.5)]" style="width: {{ $healthPct }}%"></div>
                                </div>
                            </div>

                            <!-- Damage Stat -->
                            <div>
                                <div class="flex justify-between items-end mb-3">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">Lethality</span>
                                        <span class="text-lg font-bold text-white">Damage Power</span>
                                    </div>
                                    <span class="text-2xl font-black text-orange-500">{{ $mob->damage }}</span>
                                </div>
                                <div class="h-2 bg-white/5 rounded-full overflow-hidden">
                                    @php
                                        preg_match('/\d+/', $mob->damage, $matches);
                                        $damageVal = $matches[0] ?? 5;
                                        $damagePct = min(($damageVal / 20) * 100, 100);
                                    @endphp
                                    <div class="h-full bg-gradient-to-r from-orange-600 to-orange-400 rounded-full shadow-[0_0_10px_rgba(249,115,22,0.5)]" style="width: {{ $damagePct }}%"></div>
                                </div>
                            </div>

                            <!-- Detailed Comparison List -->
                            <div class="space-y-4 pt-8 border-t border-white/5">
                                <div class="flex justify-between items-start text-sm">
                                    <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px] mt-1">Natural Habitats</span>
                                    <span class="text-white font-bold text-right max-w-[150px] leading-tight">{{ $mob->biomes->pluck('name')->implode(', ') ?: ($mob->spawning_conditions ? 'Classified Location' : 'Global Presence') }}</span>
                                </div>
                                @if($mob->spawning_conditions)
                                    <div class="flex justify-between items-start text-sm pt-4 border-t border-white/5">
                                        <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px] mt-1">Encounter Info</span>
                                        <span class="text-indigo-300 font-bold text-right max-w-[150px] leading-tight italic text-[11px]">"{{ $mob->spawning_conditions }}"</span>
                                    </div>
                                @endif
                                <div class="pt-4">
                                    <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px] block mb-2">Item Drops</span>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(explode(',', $mob->drops) as $drop)
                                            <span class="px-3 py-1 bg-white/5 rounded-lg text-[10px] text-gray-300 font-bold border border-white/10">
                                                {{ trim($drop) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Detailed Description -->
                            <div class="pt-8 border-t border-white/5">
                                <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px] block mb-4">Strategic Overview</span>
                                <p class="text-gray-400 text-sm leading-relaxed italic">
                                    "{{ Str::limit($mob->description, 120) }}"
                                </p>
                            </div>

                            <!-- Action -->
                            <div class="pt-8">
                                <a href="{{ route('mobs.show', $mob) }}" class="w-full py-4 bg-indigo-500/10 hover:bg-indigo-500 text-indigo-400 hover:text-white text-center font-black rounded-2xl block transition-all border border-indigo-500/20">
                                    Full Intel Report
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Footer Note -->
            <div class="mt-20 text-center">
                <div class="inline-block p-1 bg-white/5 rounded-full px-6 py-2 border border-white/10">
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">
                        Comparison algorithm based on in-game survival statistics and entity data.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
