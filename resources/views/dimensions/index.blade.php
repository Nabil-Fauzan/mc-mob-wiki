<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl font-black text-white tracking-tight">Multiverse <span class="text-indigo-500">Intelligence Bureau</span></h1>
                <p class="text-gray-400 text-sm italic">Advanced analytical data across all known dimensional planes.</p>
            </div>
            <div class="px-6 py-2 bg-indigo-500/10 border border-indigo-500/30 rounded-full">
                <span class="text-xs font-black text-indigo-400 uppercase tracking-widest">Active Surveillance</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 space-y-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Summary Matrix Header -->
            <div class="glass-card rounded-[3rem] p-10 mb-12 border-indigo-500/10">
                <div class="text-center mb-10">
                    <h2 class="text-2xl font-black text-white uppercase tracking-tighter">Cross-Dimensional Threat Matrix</h2>
                    <div class="h-1 w-20 bg-indigo-500 mx-auto mt-2 rounded-full"></div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($stats as $data)
                        @php
                            $themeColor = 'emerald';
                            if(Str::contains($data['dimension']->name, 'Nether')) $themeColor = 'red';
                            if(Str::contains($data['dimension']->name, 'End')) $themeColor = 'purple';
                        @endphp
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-end">
                                <span class="text-xs font-black text-gray-500 uppercase tracking-widest">{{ $data['dimension']->name }} Risk</span>
                                <span class="text-xl font-black text-{{ $themeColor }}-500">{{ $data['danger_level'] }}%</span>
                            </div>
                            <div class="h-2 w-full bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full bg-{{ $themeColor }}-500 rounded-full shadow-[0_0_15px_rgba(var(--{{ $themeColor }}-500),0.5)] transition-all duration-1000" style="width: {{ $data['danger_level'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Dimension Deep Dives -->
            <div class="space-y-16">
                @foreach($stats as $data)
                    @php
                        $dim = $data['dimension'];
                        $themeColor = 'emerald';
                        $accentGradient = 'from-emerald-500 to-teal-600';
                        $glowColor = 'rgba(16,185,129,0.2)';
                        
                        if(Str::contains($dim->name, 'Nether')) {
                            $themeColor = 'red';
                            $accentGradient = 'from-red-600 to-orange-700';
                            $glowColor = 'rgba(239,68,68,0.2)';
                        } elseif(Str::contains($dim->name, 'End')) {
                            $themeColor = 'purple';
                            $accentGradient = 'from-purple-600 to-indigo-700';
                            $glowColor = 'rgba(168,85,247,0.2)';
                        }
                    @endphp

                    <div class="glass-card rounded-[4rem] overflow-hidden border-white/5 group hover:border-{{ $themeColor }}-500/30 transition-all duration-700 shadow-2xl stagger-item" 
                         style="box-shadow: 0 0 80px {{ $glowColor }}; animation-delay: {{ $loop->index * 150 }}ms">
                        <div class="grid grid-cols-1 lg:grid-cols-12">
                            <!-- Visual Sidebar -->
                            <div class="lg:col-span-4 relative overflow-hidden bg-gradient-to-br {{ $accentGradient }} p-12 flex flex-col justify-between min-h-[400px]">
                                <div class="relative z-10">
                                    <span class="text-xs font-black text-white/60 uppercase tracking-[0.3em] mb-2 block">Sector Intelligence</span>
                                    <h3 class="text-5xl font-black text-white tracking-tighter leading-none">{{ $dim->name }}</h3>
                                    <p class="mt-6 text-white/80 text-sm leading-relaxed italic font-medium">
                                        "{{ $dim->description ?: 'Unknown atmospheric conditions. Use caution during deployment.' }}"
                                    </p>
                                </div>
                                
                                <div class="relative z-10 grid grid-cols-2 gap-4 mt-12">
                                    <div class="bg-black/20 backdrop-blur-md p-4 rounded-3xl border border-white/10">
                                        <span class="block text-[10px] font-black text-white/50 uppercase mb-1">Ecosystems</span>
                                        <span class="text-2xl font-black text-white">{{ $data['biome_count'] }}</span>
                                    </div>
                                    <div class="bg-black/20 backdrop-blur-md p-4 rounded-3xl border border-white/10">
                                        <span class="block text-[10px] font-black text-white/50 uppercase mb-1">Entitites</span>
                                        <span class="text-2xl font-black text-white">{{ $data['mob_count'] }}</span>
                                    </div>
                                </div>

                                <!-- Background Decorative Element -->
                                <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
                            </div>

                            <!-- Data Matrix -->
                            <div class="lg:col-span-8 p-12 bg-white/[0.02]">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                                    <!-- Averages & Risk -->
                                    <div class="space-y-8">
                                        <div>
                                            <h4 class="text-sm font-black text-gray-500 uppercase tracking-widest mb-6">Atmospheric Lethality</h4>
                                            <div class="grid grid-cols-1 gap-6">
                                                <div>
                                                    <div class="flex justify-between text-xs font-bold mb-2">
                                                        <span class="text-gray-400">Vitality Index (Avg HP)</span>
                                                        <span class="text-white">{{ $data['avg_health'] }} pts</span>
                                                    </div>
                                                    <div class="h-1.5 bg-white/5 rounded-full overflow-hidden">
                                                        <div class="h-full bg-{{ $themeColor }}-500 rounded-full" style="width: {{ min(($data['avg_health'] / 100) * 100, 100) }}%"></div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="flex justify-between text-xs font-bold mb-2">
                                                        <span class="text-gray-400">Combat Power (Avg ATK)</span>
                                                        <span class="text-white">{{ $data['avg_damage'] }} pts</span>
                                                    </div>
                                                    <div class="h-1.5 bg-white/5 rounded-full overflow-hidden">
                                                        <div class="h-full bg-{{ $themeColor }}-500 rounded-full" style="width: {{ min(($data['avg_damage'] / 20) * 100, 100) }}%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pt-8 border-t border-white/5">
                                            <h4 class="text-sm font-black text-gray-500 uppercase tracking-widest mb-4">Resource Intelligence</h4>
                                            <div class="flex items-center space-x-4">
                                                <div class="w-12 h-12 rounded-2xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-width="2"></path></svg>
                                                </div>
                                                <div>
                                                    <span class="block text-2xl font-black text-white">{{ $data['unique_loot_count'] }}</span>
                                                    <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Unique Resources Discovered</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Top Guardians -->
                                    <div>
                                        <h4 class="text-sm font-black text-gray-500 uppercase tracking-widest mb-6">Dimension Guardians</h4>
                                        <div class="space-y-4">
                                            @forelse($data['guardians'] as $mob)
                                                <a href="{{ route('mobs.show', $mob) }}" class="flex items-center p-4 bg-white/5 rounded-2xl border border-white/5 hover:border-{{ $themeColor }}-500/40 transition-all group/guardian">
                                                    <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-900 mr-4">
                                                        @if($mob->image)
                                                            <img src="{{ asset('storage/' . $mob->image) }}" alt="{{ $mob->name }}" class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center text-gray-700">?</div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1">
                                                        <span class="block text-sm font-black text-white group-hover/guardian:text-{{ $themeColor }}-400 transition-colors">{{ $mob->name }}</span>
                                                        <span class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter">Threat Score: {{ round($mob->threat_score) }}</span>
                                                    </div>
                                                    <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2"></path></svg>
                                                </a>
                                            @empty
                                                <div class="py-8 text-center bg-white/5 rounded-2xl border border-dashed border-white/10">
                                                    <span class="text-xs text-gray-600 italic">No guardians identified in this sector.</span>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Comparative Summary Table -->
            <div class="mt-24">
                <div class="text-center mb-12">
                    <span class="text-xs font-black text-indigo-500 uppercase tracking-widest">Cross-Platform Analysis</span>
                    <h3 class="text-4xl font-black text-white tracking-tighter">Stability Comparison Matrix</h3>
                </div>
                
                <div class="glass-card rounded-[3rem] overflow-hidden border-white/5">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-white/5">
                                <th class="px-8 py-6 text-xs font-black text-gray-400 uppercase tracking-widest">Dimension Sector</th>
                                <th class="px-8 py-6 text-xs font-black text-gray-400 uppercase tracking-widest">Avg Lethality</th>
                                <th class="px-8 py-6 text-xs font-black text-gray-400 uppercase tracking-widest">Elite Presence</th>
                                <th class="px-8 py-6 text-xs font-black text-gray-400 uppercase tracking-widest text-right">Danger Rating</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($stats as $data)
                                @php
                                    $themeColor = 'emerald';
                                    if(Str::contains($data['dimension']->name, 'Nether')) $themeColor = 'red';
                                    if(Str::contains($data['dimension']->name, 'End')) $themeColor = 'purple';
                                @endphp
                                <tr class="hover:bg-white/[0.02] transition-colors">
                                    <td class="px-8 py-8">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full bg-{{ $themeColor }}-500 mr-4 shadow-[0_0_10px_rgba(var(--{{ $themeColor }}-500),0.8)]"></div>
                                            <span class="font-black text-white tracking-tight uppercase">{{ $data['dimension']->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-8 font-mono text-gray-400">{{ $data['avg_damage'] }} <span class="text-[10px] text-gray-600">ATK AVG</span></td>
                                    <td class="px-8 py-8">
                                        <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-full text-[10px] font-black text-gray-400 uppercase">
                                            {{ $data['guardians']->count() }} Master Ranked
                                        </span>
                                    </td>
                                    <td class="px-8 py-8 text-right">
                                        <span class="text-2xl font-black text-{{ $themeColor }}-500">{{ $data['danger_level'] }}<span class="text-sm font-bold text-gray-600">/100</span></span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
