<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h1 class="text-4xl font-black text-white tracking-tight">Mob <span class="text-brand-500">Comparison</span></h1>
                    <p class="text-gray-400 mt-2">Side-by-side analysis of Minecraft species and entities.</p>
                </div>
                <a href="{{ route('mobs.index') }}" class="px-6 py-2.5 bg-white/5 hover:bg-white/10 text-white font-bold rounded-xl border border-white/10 transition-all flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Wiki
                </a>
            </div>

            @php
                $maxDamage = 0;
                $maxHealth = 0;
                $maxXP = 0;
                
                foreach($mobs as $m) {
                    preg_match('/\d+/', $m->damage, $dMod);
                    $d = intval($dMod[0] ?? 0);
                    if($d > $maxDamage) $maxDamage = $d;

                    preg_match('/\d+/', $m->health, $hMod);
                    $h = intval($hMod[0] ?? 0);
                    if($h > $maxHealth) $maxHealth = $h;
                    
                    if($m->xp_reward) {
                        preg_match('/\d+/', $m->xp_reward, $xMod);
                        $x = intval($xMod[0] ?? 0);
                        if($x > $maxXP) $maxXP = $x;
                    }
                }
            @endphp

            <!-- Comparison Grid -->
            <div class="relative">
                @if(count($mobs) === 2)
                    <div class="hidden md:flex absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-20 w-16 h-16 bg-black border-4 border-brand-500/30 rounded-full items-center justify-center shadow-[0_0_30px_rgba(14,165,233,0.4)]">
                        <span class="text-xl font-black text-white italic tracking-tighter">VS</span>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-{{ count($mobs) }} gap-8">
                    @foreach($mobs as $mob)
                        @php
                            preg_match('/\d+/', $mob->damage, $dMatches);
                            $isBestDamage = $maxDamage > 0 && intval($dMatches[0] ?? 0) === $maxDamage;
                            
                            preg_match('/\d+/', $mob->health, $hMatches);
                            $isBestHealth = $maxHealth > 0 && intval($hMatches[0] ?? 0) === $maxHealth;

                            $isBestXP = false;
                            if($mob->xp_reward) {
                                preg_match('/\d+/', $mob->xp_reward, $xMatches);
                                $isBestXP = $maxXP > 0 && intval($xMatches[0] ?? 0) === $maxXP;
                            }
                        @endphp

                        <div class="glass-card rounded-[3rem] overflow-hidden group hover:-translate-y-2 transition-all duration-500 relative 
                            {{ $isBestDamage ? 'border-orange-500/30 shadow-[0_0_40px_rgba(249,115,22,0.15)]' : 'border-white/5' }}">
                            
                            <!-- Superiority Badge -->
                            @if($isBestDamage)
                                <div class="absolute top-6 right-6 z-10 bg-gradient-to-r from-orange-600 to-red-600 px-3 py-1 rounded-full shadow-lg border border-white/20">
                                    <span class="text-[9px] font-black text-white uppercase tracking-widest flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                        Apex Predator
                                    </span>
                                </div>
                            @elseif($isBestHealth)
                                <div class="absolute top-6 right-6 z-10 bg-brand-600 px-3 py-1 rounded-full shadow-lg border border-white/20">
                                    <span class="text-[9px] font-black text-white uppercase tracking-widest flex items-center">
                                        Fortified
                                    </span>
                                </div>
                            @endif

                            <!-- Mob Hero Header -->
                            <div class="aspect-video relative overflow-hidden">
                                @if($mob->image)
                                    <img src="{{ asset('storage/' . $mob->image) }}" alt="{{ $mob->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full bg-gray-900 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="1.5"></path></svg>
                                    </div>
                                @endif
                                <div class="absolute inset-x-0 bottom-0 p-6 bg-gradient-to-t from-black/90 via-black/40 to-transparent">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-brand-400 block mb-1">{{ $mob->category->name }}</span>
                                    <h2 class="text-3xl font-black text-white tracking-tight">{{ $mob->name }}</h2>
                                </div>
                            </div>

                            <!-- Stats & Info -->
                            <div class="p-8 space-y-8">
                                <!-- Damage Stat (Primary) -->
                                <div>
                                    <div class="flex justify-between items-end mb-3">
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-black uppercase tracking-widest {{ $isBestDamage ? 'text-orange-400' : 'text-gray-500' }}">
                                                Lethality {{ $isBestDamage ? '(Peak)' : '' }}
                                            </span>
                                            <span class="text-lg font-bold text-white">Damage Power</span>
                                        </div>
                                        <span class="text-2xl font-black {{ $isBestDamage ? 'text-orange-500 scale-110' : 'text-orange-500/60' }} transition-transform">
                                            {{ $mob->damage }}
                                        </span>
                                    </div>
                                    <div class="h-3 bg-white/5 rounded-full overflow-hidden p-0.5 border border-white/5">
                                        @php
                                            $damageVal = intval($dMatches[0] ?? 5);
                                            $damagePct = min(($damageVal / 20) * 100, 100);
                                        @endphp
                                        <div class="h-full bg-gradient-to-r from-orange-600 via-red-500 to-orange-400 rounded-full transition-all duration-1000 shadow-[0_0_15px_rgba(249,115,22,0.6)]" style="width: {{ $damagePct }}%"></div>
                                    </div>
                                </div>

                                <!-- Health Stat -->
                                <div>
                                    <div class="flex justify-between items-end mb-3">
                                        <div class="flex flex-col">
                                            <span class="text-[10px] font-black uppercase tracking-widest {{ $isBestHealth ? 'text-red-400' : 'text-gray-500' }}">
                                                Durability {{ $isBestHealth ? '(Peak)' : '' }}
                                            </span>
                                            <span class="text-lg font-bold text-white">Health Points</span>
                                        </div>
                                        <span class="text-2xl font-black {{ $isBestHealth ? 'text-red-500 scale-110' : 'text-red-500/60' }} transition-transform">
                                            {{ $mob->health }}
                                        </span>
                                    </div>
                                    <div class="h-3 bg-white/5 rounded-full overflow-hidden p-0.5 border border-white/5">
                                        @php
                                            $healthVal = intval($hMatches[0] ?? 20);
                                            $healthPct = min(($healthVal / 100) * 100, 100);
                                        @endphp
                                        <div class="h-full bg-gradient-to-r from-red-700 via-red-500 to-rose-400 rounded-full transition-all duration-1000 shadow-[0_0_15px_rgba(239,68,68,0.5)]" style="width: {{ $healthPct }}%"></div>
                                    </div>
                                </div>

                                <!-- Detailed Comparison List -->
                                <div class="space-y-4 pt-8 border-t border-white/5">
                                    <div class="flex justify-between items-start text-sm">
                                        <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px] mt-1">Resource Value (XP)</span>
                                        <div class="flex items-center">
                                            @if($isBestXP)
                                                <svg class="w-3 h-3 text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            @endif
                                            <span class="text-white font-black {{ $isBestXP ? 'text-yellow-500' : '' }}">{{ $mob->xp_reward ?: '0' }} Points</span>
                                        </div>
                                    </div>

                                    <div class="flex flex-col space-y-3 pt-4 border-t border-white/5">
                                        <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Loot Intelligence</span>
                                        <div class="flex flex-wrap gap-2">
                                            @forelse($mob->loot as $drop)
                                                <div class="flex items-center space-x-1.5 px-2 py-1 bg-white/5 rounded-lg border border-white/10 group hover:border-yellow-500/30 transition-all">
                                                    <span class="text-xs">{{ $drop->icon ?: '📦' }}</span>
                                                    <span class="text-[9px] text-gray-300 font-black uppercase tracking-tighter">{{ $drop->item_name }}</span>
                                                </div>
                                            @empty
                                                <span class="text-[9px] text-gray-600 italic">No significant loot</span>
                                            @endforelse
                                        </div>
                                    </div>

                                    @if($mob->melee_attack || $mob->ranged_attack)
                                        <div class="flex flex-col space-y-2 pt-4 border-t border-white/5">
                                            <span class="text-gray-500 font-bold uppercase tracking-widest text-[10px]">Arsenal Intel</span>
                                            <div class="grid grid-cols-2 gap-2">
                                                @if($mob->melee_attack)
                                                    <div class="bg-red-500/5 p-2 rounded-lg border border-red-500/10">
                                                        <span class="text-[7px] font-black text-red-500 uppercase block">Melee</span>
                                                        <p class="text-[9px] text-gray-400 truncate">{{ $mob->melee_attack }}</p>
                                                    </div>
                                                @endif
                                                @if($mob->ranged_attack)
                                                    <div class="bg-blue-500/5 p-2 rounded-lg border border-blue-500/10">
                                                        <span class="text-[7px] font-black text-blue-500 uppercase block">Ranged</span>
                                                        <p class="text-[9px] text-gray-400 truncate">{{ $mob->ranged_attack }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Action -->
                                <div class="pt-8">
                                    <a href="{{ route('mobs.show', $mob) }}" class="w-full py-4 bg-white/5 hover:bg-white/10 text-white text-center font-black rounded-2xl block transition-all border border-white/10 group-hover:border-brand-500/50">
                                        Access Full Intel
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
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
