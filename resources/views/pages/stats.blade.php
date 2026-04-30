<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header section -->
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
                <div>
                    <h1 class="text-4xl font-black text-white tracking-tight">Global <span class="text-brand-500">Intelligence</span></h1>
                    <p class="text-gray-400 mt-2">Real-time analytical data from the Minecraft Mob Wiki database.</p>
                </div>
                <div class="flex space-x-6">
                    <div class="text-right">
                        <span class="text-[10px] font-black text-gray-600 uppercase tracking-widest block">Cataloged Species</span>
                        <span class="text-3xl font-black text-white">{{ $totalStats['mobs'] }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-black text-gray-600 uppercase tracking-widest block">Mapped Regions</span>
                        <span class="text-3xl font-black text-white">{{ $totalStats['biomes'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Main Analytics Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
                <!-- Classification Chart -->
                <div class="lg:col-span-1 glass-card rounded-[2.5rem] p-8 border-brand-500/10">
                    <h3 class="text-sm font-black text-white uppercase tracking-widest mb-8 flex items-center">
                        <span class="w-2 h-2 bg-brand-500 rounded-full mr-3 animate-pulse"></span>
                        Species Distribution
                    </h3>
                    <div class="aspect-square relative">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>

                <!-- Ecosystem Density Chart -->
                <div class="lg:col-span-2 glass-card rounded-[2.5rem] p-8">
                    <h3 class="text-sm font-black text-white uppercase tracking-widest mb-8 flex items-center">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                        Ecosystem Population Density
                    </h3>
                    <div class="h-64 sm:h-80 relative">
                        <canvas id="dimensionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Dimensional Flux (Stability Monitor) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                @foreach($totalStats['stability'] as $stability)
                    <div class="glass-card rounded-3xl p-6 border-white/5 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:scale-110 transition-transform">
                            <svg class="w-16 h-16 {{ $stability['status'] == 'Critical' ? 'text-red-500' : 'text-brand-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div class="relative">
                            <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest block mb-1">Dimension Flux</span>
                            <h4 class="text-xl font-black text-white mb-4">{{ $stability['name'] }}</h4>
                            <div class="flex items-end justify-between mb-2">
                                <span class="text-[10px] font-bold {{ $stability['status'] == 'Stable' ? 'text-green-500' : ($stability['status'] == 'Volatile' ? 'text-yellow-500' : 'text-red-500') }} uppercase tracking-tighter">{{ $stability['status'] }}</span>
                                <span class="text-xs font-mono text-white">{{ $stability['stability'] }}% Stability</span>
                            </div>
                            <div class="h-1 bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full {{ $stability['status'] == 'Stable' ? 'bg-green-500' : ($stability['status'] == 'Volatile' ? 'bg-yellow-500' : 'bg-red-500') }} transition-all duration-1000" style="width: {{ $stability['stability'] }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
                <!-- Vitality HUD -->
                <div class="glass-card rounded-[2.5rem] p-8 flex flex-col justify-center space-y-8 relative overflow-hidden">
                    <div class="absolute inset-0 bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.02)_50%,transparent_75%)] bg-[length:250%_250%] animate-[shimmer_5s_infinite]"></div>
                    <h3 class="text-sm font-black text-white uppercase tracking-widest flex items-center relative">
                        <svg class="w-4 h-4 mr-2 text-brand-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Global Biometrics HUD
                    </h3>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">Health Mean</span>
                            <span class="text-xl font-black text-white">{{ number_format($avgHealth, 1) }} <span class="text-[10px] text-red-500 uppercase">hp</span></span>
                        </div>
                        <div class="h-2 bg-white/5 rounded-full overflow-hidden p-0.5 border border-white/5">
                            <div class="h-full bg-gradient-to-r from-red-600 to-red-400 shadow-[0_0_15px_rgba(239,68,68,0.5)] rounded-full transition-all duration-1000" style="width: {{ ($avgHealth / 100) * 100 }}%"></div>
                        </div>
                        <div class="flex justify-between mt-2 text-[8px] font-bold text-gray-700 uppercase tracking-widest">
                            <span>Min: 1.0</span>
                            <span>Max: 500.0</span>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-black text-gray-500 uppercase tracking-[0.2em]">Lethality Mean</span>
                            <span class="text-xl font-black text-white">{{ number_format($avgDamage, 1) }} <span class="text-[10px] text-orange-500 uppercase">atk</span></span>
                        </div>
                        <div class="h-2 bg-white/5 rounded-full overflow-hidden p-0.5 border border-white/5">
                            <div class="h-full bg-gradient-to-r from-orange-600 to-orange-400 shadow-[0_0_15px_rgba(249,115,22,0.5)] rounded-full transition-all duration-1000" style="width: {{ ($avgDamage / 20) * 100 }}%"></div>
                        </div>
                        <div class="flex justify-between mt-2 text-[8px] font-bold text-gray-700 uppercase tracking-widest">
                            <span>Min: 0.0</span>
                            <span>Max: 50.0</span>
                        </div>
                    </div>
                </div>

                <!-- Lethality Ranking -->
                <div class="lg:col-span-2 glass-card rounded-[2.5rem] p-8 border-red-500/10">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-sm font-black text-white uppercase tracking-widest flex items-center">
                            <span class="w-2 h-2 bg-red-500 rounded-full mr-3 animate-ping"></span>
                            Priority Threat Targets
                        </h3>
                        <span class="text-[10px] font-black text-red-500 bg-red-500/10 px-3 py-1 rounded-full uppercase tracking-widest border border-red-500/20">Class S+ Detected</span>
                    </div>
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($topDeadly as $index => $mob)
                            @php
                                $class = $mob->threat_score >= 100 ? 'S' : ($mob->threat_score >= 60 ? 'A' : 'B');
                                $color = $class == 'S' ? 'text-red-500' : ($class == 'A' ? 'text-orange-500' : 'text-yellow-500');
                            @endphp
                            <div class="flex items-center p-4 bg-gray-950/40 hover:bg-red-500/5 rounded-2xl border border-white/5 hover:border-red-500/30 transition-all group overflow-hidden relative">
                                <div class="absolute inset-0 bg-gradient-to-r from-red-500/0 via-red-500/0 to-red-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <div class="w-10 text-xl font-black {{ $color }} font-mono italic">{{ $class }}</div>
                                <div class="w-12 h-12 rounded-xl border border-white/10 overflow-hidden bg-black mr-4 shadow-xl group-hover:scale-110 transition-transform duration-500">
                                    @if($mob->image)
                                        <img src="{{ asset('storage/' . $mob->image) }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-black text-white tracking-tight group-hover:text-red-400 transition-colors">{{ $mob->name }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase tracking-widest">Habitat: {{ $mob->biomes->first()->name ?? 'Global' }}</div>
                                </div>
                                <div class="text-right relative">
                                    <div class="text-[9px] font-black text-gray-600 uppercase tracking-widest mb-1">Threat Score</div>
                                    <div class="text-xl font-black text-white group-hover:scale-110 transition-transform origin-right">{{ number_format($mob->threat_score, 0) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Global Researcher Leaderboard -->
            <div class="glass-card rounded-[3rem] p-10 border-brand-500/10">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
                    <div>
                        <h3 class="text-2xl font-black text-white tracking-tight">Elite Researcher <span class="text-brand-500">Leaderboard</span></h3>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">Top agents currently mapping the Aether Ocean protocol.</p>
                    </div>
                    <div class="px-4 py-2 bg-brand-500/10 border border-brand-500/20 rounded-xl text-[10px] font-black text-brand-400 uppercase tracking-widest">
                        Updated Live
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                    @foreach($totalStats['top_researchers'] as $index => $researcher)
                        <div class="relative group">
                            <div class="absolute -top-3 -left-3 w-8 h-8 rounded-full bg-gray-900 border border-white/10 flex items-center justify-center text-[10px] font-black text-brand-500 z-10 shadow-xl">
                                #{{ $index + 1 }}
                            </div>
                            <div class="glass-card p-6 rounded-[2rem] border-white/5 text-center group-hover:border-brand-500/30 transition-all hover:-translate-y-2 duration-500">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-500 to-accent-500 p-0.5 mx-auto mb-4">
                                    <div class="w-full h-full bg-gray-950 rounded-[0.9rem] flex items-center justify-center text-xl font-black text-white">
                                        {{ substr($researcher->name, 0, 1) }}
                                    </div>
                                </div>
                                <h4 class="text-sm font-black text-white truncate mb-1">{{ $researcher->name }}</h4>
                                <p class="text-[10px] font-bold text-brand-500 uppercase tracking-widest mb-3">LVL {{ $researcher->lvl }}</p>
                                <div class="text-[9px] text-gray-600 font-black uppercase tracking-tighter">{{ number_format($researcher->xp) }} XP</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Category Distribution Chart
            const catCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(catCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($categoryLabels) !!},
                    datasets: [{
                        data: {!! json_encode($categoryData) !!},
                        backgroundColor: [
                            'rgba(239, 68, 68, 0.6)', 
                            'rgba(34, 197, 94, 0.6)', 
                            'rgba(234, 179, 8, 0.6)',
                            'rgba(14, 165, 233, 0.6)'
                        ],
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 2,
                        hoverOffset: 20
                    }]
                },
                options: {
                    cutout: '75%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#9ca3af',
                                font: { weight: 'bold', family: 'Inter' },
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });

            // Dimension Density Chart
            const dimCtx = document.getElementById('dimensionChart').getContext('2d');
            new Chart(dimCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($dimensionLabels) !!},
                    datasets: [{
                        label: 'Population Load',
                        data: {!! json_encode($dimensionData) !!},
                        backgroundColor: 'rgba(14, 165, 233, 0.4)',
                        borderColor: '#0ea5e9',
                        borderWidth: 2,
                        borderRadius: 12,
                        barThickness: 40
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(255, 255, 255, 0.05)' },
                            ticks: { color: '#4b5563', font: { weight: 'bold' } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#9ca3af', font: { weight: 'black' } }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
