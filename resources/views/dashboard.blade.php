<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header & Level Progress -->
            <div class="mb-12 glass-card rounded-[3rem] p-8 sm:p-12 border-brand-500/20 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-12 opacity-10 hidden lg:block">
                    <svg class="w-48 h-48 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 relative">
                    <div class="flex items-center space-x-6">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-[2rem] bg-gradient-to-br from-brand-500 to-accent-500 p-1 shadow-[0_0_30px_rgba(14,165,233,0.3)]">
                            <div class="w-full h-full bg-gray-900 rounded-[1.8rem] flex items-center justify-center text-3xl font-black text-white">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </div>
                        <div>
                            <span class="text-[10px] font-black text-brand-500 uppercase tracking-[0.4em] mb-1 block">Researcher ID: #{{ str_pad(auth()->id(), 4, '0', STR_PAD_LEFT) }}</span>
                            <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tighter">{{ auth()->user()->name }}</h1>
                            <div class="flex items-center mt-3 space-x-4">
                                <span class="px-3 py-1 bg-brand-500/10 border border-brand-500/20 rounded-full text-[10px] font-black text-brand-400 uppercase tracking-widest">
                                    Rank: {{ $stats['level'] > 5 ? 'Master Researcher' : ($stats['level'] > 2 ? 'Senior Agent' : 'Field Scout') }}
                                </span>
                                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ $stats['xp'] }} Research XP</span>
                            </div>
                        </div>
                    </div>

                    <div class="w-full lg:w-80 space-y-4">
                        <div class="flex justify-between items-end">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Protocol Level {{ $stats['level'] }}</span>
                            <span class="text-[10px] font-black text-brand-500 uppercase tracking-widest">{{ $stats['progress'] }}% to Level {{ $stats['level'] + 1 }}</span>
                        </div>
                        <div class="h-3 bg-white/5 rounded-full overflow-hidden p-0.5 border border-white/5">
                            <div class="h-full bg-gradient-to-r from-brand-600 via-accent-500 to-brand-400 rounded-full shadow-[0_0_15px_rgba(14,165,233,0.5)] transition-all duration-1000" style="width: {{ $stats['progress'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Achievement Badges -->
            <div class="mb-12 overflow-x-auto scrollbar-none">
                <div class="flex space-x-6 min-w-max pb-4">
                    @php
                        $badges = [
                            ['name' => 'First Blood', 'unlocked' => $stats['comments_count'] > 0, 'desc' => 'Record first field note', 'icon' => '🩸'],
                            ['name' => 'Archivist', 'unlocked' => $stats['favorites_count'] >= 5, 'desc' => '5 species favorited', 'icon' => '📚'],
                            ['name' => 'Elite Agent', 'unlocked' => $stats['level'] >= 3, 'desc' => 'Reach level 3', 'icon' => '🛡️'],
                            ['name' => 'Ghost Hunter', 'unlocked' => false, 'desc' => 'Analyze 5 Nether mobs', 'icon' => '👻'],
                            ['name' => 'Dimension Hopper', 'unlocked' => true, 'desc' => 'Visit all dimension hubs', 'icon' => '🌀'],
                        ];
                    @endphp
                    @foreach($badges as $badge)
                        <div class="glass-card px-6 py-4 rounded-2xl border-white/5 flex items-center space-x-4 {{ $badge['unlocked'] ? 'opacity-100' : 'opacity-30 grayscale' }}">
                            <div class="text-2xl">{{ $badge['icon'] }}</div>
                            <div>
                                <h4 class="text-[10px] font-black text-white uppercase tracking-widest mb-0.5">{{ $badge['name'] }}</h4>
                                <p class="text-[8px] text-gray-500 font-bold uppercase tracking-tighter">{{ $badge['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Bookmarked Species (Grid-ish) -->
                <div class="lg:col-span-2">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-black text-white flex items-center">
                            <span class="w-2 h-2 bg-brand-500 rounded-full mr-3"></span>
                            Intel Archive
                        </h3>
                        <a href="{{ route('mobs.index') }}" class="text-[10px] font-black text-brand-500 uppercase tracking-[0.2em] hover:text-brand-400 transition-colors">Expand Registry +</a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($favorites as $mob)
                            <div class="glass-card p-4 rounded-[2rem] border-white/5 group hover:border-brand-500/30 transition-all bg-gray-900/40 relative overflow-hidden">
                                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-20 transition-opacity">
                                    <svg class="w-16 h-16 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                                <div class="flex items-center space-x-4 relative">
                                    <div class="w-16 h-16 rounded-2xl bg-black border border-white/10 overflow-hidden flex-shrink-0 group-hover:scale-105 transition-transform duration-500">
                                        @if($mob->image)
                                            <img src="{{ asset('storage/' . $mob->image) }}" class="w-full h-full object-contain p-2">
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-black text-white truncate">{{ $mob->name }}</h4>
                                        <p class="text-[10px] text-gray-500 uppercase tracking-widest">{{ $mob->category->name }} · {{ $mob->biomes->first()->name ?? 'Global' }}</p>
                                    </div>
                                    <a href="{{ route('mobs.show', $mob) }}" class="w-10 h-10 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-500 hover:text-white hover:bg-brand-500 transition-all active:scale-90">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-20 text-center glass-card rounded-[3rem] border-dashed border-white/10">
                                <p class="text-gray-500 font-medium">Your personal archive is currently empty.</p>
                                <a href="{{ route('mobs.index') }}" class="mt-4 inline-block text-brand-500 font-bold hover:underline">Explore Wiki</a>
                            </div>
                        @endforelse
                    </div>

                    <!-- Quick Command -->
                    <div class="mt-12 glass-card p-8 rounded-[2.5rem] border-green-500/20 bg-gradient-to-r from-brand-900/20 to-transparent flex flex-col md:flex-row items-center justify-between gap-6">
                        <div>
                            <h4 class="text-xl font-black text-white mb-1">New Entity Discovered?</h4>
                            <p class="text-xs text-gray-500 font-medium">Contribute to the Aether Ocean registry and earn 350 XP.</p>
                        </div>
                        <a href="{{ route('mobs.create') }}" class="px-8 py-3 bg-white text-black text-[10px] font-black rounded-xl hover:bg-brand-500 hover:text-white transition-all uppercase tracking-widest shadow-xl shadow-white/10">
                            Register Discovery
                        </a>
                    </div>
                </div>

                <!-- Proficiency Radar & Specialization Tree -->
                <div class="space-y-12">
                    <div class="glass-card p-8 rounded-[2.5rem] border-brand-500/20 bg-gray-900/40">
                        <h3 class="text-xs font-black text-gray-500 uppercase tracking-[0.3em] mb-8 flex justify-between items-center">
                            <span>Researcher Proficiency</span>
                            <span class="text-brand-500">Live Data</span>
                        </h3>
                        <div class="aspect-square">
                            <canvas id="proficiencyRadar"></canvas>
                        </div>
                    </div>

                    <!-- Specialization Tree (Mini) -->
                    <div class="glass-card p-8 rounded-[2.5rem] border-brand-500/20 bg-gray-900/40 relative overflow-hidden">
                        <div class="absolute inset-0 opacity-5 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-brand-500 via-transparent to-transparent"></div>
                        <h3 class="text-xs font-black text-white uppercase tracking-[0.3em] mb-8 relative">Specialization Tree</h3>
                        
                        <div class="space-y-10 relative">
                            <!-- Combat Path -->
                            <div class="relative">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-xl bg-red-500/20 flex items-center justify-center text-red-500 border border-red-500/20 shadow-[0_0_15px_rgba(239,68,68,0.2)]">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-black text-white uppercase tracking-widest">Combat Expert</h4>
                                            <p class="text-[8px] text-gray-500 font-bold uppercase">Hostile Entity Analysis</p>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-mono text-red-400">{{ $stats['skills']['combat'] }}%</span>
                                </div>
                                <div class="h-1.5 bg-white/5 rounded-full overflow-hidden">
                                    <div class="h-full bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.5)] transition-all duration-1000" style="width: {{ $stats['skills']['combat'] }}%"></div>
                                </div>
                                <!-- Tree Nodes -->
                                <div class="flex justify-between mt-4">
                                    <div class="w-6 h-6 rounded-lg border {{ $stats['skills']['combat'] >= 25 ? 'bg-red-500 border-red-400' : 'bg-gray-800 border-white/5' }} transition-colors" title="Slayer I"></div>
                                    <div class="w-6 h-6 rounded-lg border {{ $stats['skills']['combat'] >= 50 ? 'bg-red-500 border-red-400' : 'bg-gray-800 border-white/5' }} transition-colors" title="Tactician"></div>
                                    <div class="w-6 h-6 rounded-lg border {{ $stats['skills']['combat'] >= 75 ? 'bg-red-500 border-red-400' : 'bg-gray-800 border-white/5' }} transition-colors" title="Elite Hunter"></div>
                                    <div class="w-6 h-6 rounded-lg border {{ $stats['skills']['combat'] >= 100 ? 'bg-red-500 border-red-400' : 'bg-gray-800 border-white/5' }} transition-colors" title="Death's Shadow"></div>
                                </div>
                            </div>

                            <!-- Survival Path -->
                            <div class="relative">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center text-green-500 border border-green-500/20 shadow-[0_0_15px_rgba(34,197,94,0.2)]">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-black text-white uppercase tracking-widest">Survivalist</h4>
                                            <p class="text-[8px] text-gray-500 font-bold uppercase">Passive Species Research</p>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-mono text-green-400">{{ $stats['skills']['survival'] }}%</span>
                                </div>
                                <div class="h-1.5 bg-white/5 rounded-full overflow-hidden">
                                    <div class="h-full bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.5)] transition-all duration-1000" style="width: {{ $stats['skills']['survival'] }}%"></div>
                                </div>
                                <div class="flex justify-between mt-4">
                                    <div class="w-6 h-6 rounded-lg border {{ $stats['skills']['survival'] >= 25 ? 'bg-green-500 border-green-400' : 'bg-gray-800 border-white/5' }} transition-colors"></div>
                                    <div class="w-6 h-6 rounded-lg border {{ $stats['skills']['survival'] >= 50 ? 'bg-green-500 border-green-400' : 'bg-gray-800 border-white/5' }} transition-colors"></div>
                                    <div class="w-6 h-6 rounded-lg border {{ $stats['skills']['survival'] >= 75 ? 'bg-green-500 border-green-400' : 'bg-gray-800 border-white/5' }} transition-colors"></div>
                                    <div class="w-6 h-6 rounded-lg border {{ $stats['skills']['survival'] >= 100 ? 'bg-green-500 border-green-400' : 'bg-gray-800 border-white/5' }} transition-colors"></div>
                                </div>
                            </div>

                            <!-- Explorer Path -->
                            <div class="relative">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-xl bg-brand-500/20 flex items-center justify-center text-brand-500 border border-brand-500/20 shadow-[0_0_15px_rgba(14,165,233,0.2)]">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-black text-white uppercase tracking-widest">Grand Explorer</h4>
                                            <p class="text-[8px] text-gray-500 font-bold uppercase">Dimensional Mapping</p>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-mono text-brand-400">{{ $stats['skills']['explorer'] }}%</span>
                                </div>
                                <div class="h-1.5 bg-white/5 rounded-full overflow-hidden">
                                    <div class="h-full bg-brand-500 shadow-[0_0_10px_rgba(14,165,233,0.5)] transition-all duration-1000" style="width: {{ $stats['skills']['explorer'] }}%"></div>
                                </div>
                                <div class="flex justify-between mt-4">
                                    <div class="w-6 h-6 rounded-lg border {{ $stats['skills']['explorer'] >= 25 ? 'bg-brand-500 border-brand-400' : 'bg-gray-800 border-white/5' }} transition-colors"></div>
                                    <div class="w-6 h-6 rounded-lg border {{ $stats['skills']['explorer'] >= 50 ? 'bg-brand-500 border-brand-400' : 'bg-gray-800 border-white/5' }} transition-colors"></div>
                                    <div class="w-6 h-6 rounded-lg border {{ $stats['skills']['explorer'] >= 75 ? 'bg-brand-500 border-brand-400' : 'bg-gray-800 border-white/5' }} transition-colors"></div>
                                    <div class="w-6 h-6 rounded-lg border {{ $stats['skills']['explorer'] >= 100 ? 'bg-brand-500 border-brand-400' : 'bg-gray-800 border-white/5' }} transition-colors"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl font-black text-white mb-8 flex items-center">
                            <span class="w-2 h-2 bg-brand-400 rounded-full mr-3"></span>
                            Recent Intel
                        </h3>
                        
                        <div class="space-y-4">
                            @forelse($stats['recent_comments'] as $comment)
                                <div class="glass-card p-6 rounded-2xl border-white/5 hover:bg-white/5 transition-colors group">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="text-[9px] font-black text-brand-400 uppercase tracking-widest">{{ $comment->mob->name }}</span>
                                        <span class="text-[9px] text-gray-600 font-mono">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-gray-400 leading-relaxed italic group-hover:text-gray-300 transition-colors">"{{ Str::limit($comment->body, 80) }}"</p>
                                </div>
                            @empty
                                <div class="py-12 text-center bg-white/5 rounded-3xl border border-dashed border-white/5">
                                    <p class="text-xs text-gray-600">No recent observations recorded.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('proficiencyRadar').getContext('2d');
                    new Chart(ctx, {
                        type: 'radar',
                        data: {
                            labels: ['Combat', 'Survival', 'Lore', 'Dimensions', 'Rarity'],
                            datasets: [{
                                label: 'User Proficiency',
                                data: [
                                    {{ min(100, $stats['comments_count'] * 20) }}, 
                                    {{ min(100, $stats['favorites_count'] * 15) }}, 
                                    {{ min(100, $stats['level'] * 15) }}, 
                                    75, 
                                    45
                                ],
                                fill: true,
                                backgroundColor: 'rgba(14, 165, 233, 0.2)',
                                borderColor: 'rgb(14, 165, 233)',
                                pointBackgroundColor: 'rgb(14, 165, 233)',
                                pointBorderColor: '#fff',
                            }]
                        },
                        options: {
                            scales: {
                                r: {
                                    angleLines: { color: 'rgba(255, 255, 255, 0.05)' },
                                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                    pointLabels: { color: '#64748b', font: { size: 10, weight: '900' } },
                                    ticks: { display: false },
                                    suggestedMin: 0,
                                    suggestedMax: 100
                                }
                            },
                            plugins: { legend: { display: false } }
                        }
                    });
                });
            </script>
        </div>
    </div>
</x-app-layout>
