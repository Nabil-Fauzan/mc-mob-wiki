<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header section -->
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
                <div>
                    <h1 class="text-4xl font-black text-white tracking-tight">Global <span class="text-indigo-500">Intelligence</span></h1>
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
                <div class="lg:col-span-1 glass-card rounded-[2.5rem] p-8 border-indigo-500/10">
                    <h3 class="text-sm font-black text-white uppercase tracking-widest mb-8 flex items-center">
                        <span class="w-2 h-2 bg-indigo-500 rounded-full mr-3 animate-pulse"></span>
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Vitality HUD -->
                <div class="glass-card rounded-[2.5rem] p-8 flex flex-col justify-center space-y-8">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Average Vitality (Health)</span>
                            <span class="text-lg font-black text-red-500">{{ number_format($avgHealth, 1) }} HP</span>
                        </div>
                        <div class="h-1.5 bg-white/5 rounded-full overflow-hidden">
                            <div class="h-full bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.4)]" style="width: {{ ($avgHealth / 100) * 100 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Average Lethality (Damage)</span>
                            <span class="text-lg font-black text-orange-500">{{ number_format($avgDamage, 1) }} DPS</span>
                        </div>
                        <div class="h-1.5 bg-white/5 rounded-full overflow-hidden">
                            <div class="h-full bg-orange-500 shadow-[0_0_10px_rgba(249,115,22,0.4)]" style="width: {{ ($avgDamage / 20) * 100 }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Lethality Ranking -->
                <div class="lg:col-span-2 glass-card rounded-[2.5rem] p-8">
                    <h3 class="text-sm font-black text-white uppercase tracking-widest mb-8 flex items-center">
                        <span class="w-2 h-2 bg-red-500 rounded-full mr-3"></span>
                        High Threat Entities (Top 5)
                    </h3>
                    <div class="space-y-4">
                        @foreach($topDeadly as $index => $mob)
                            <div class="flex items-center p-4 bg-white/5 hover:bg-white/10 rounded-2xl border border-white/5 transition-all group">
                                <div class="w-8 text-xs font-black text-gray-700 font-mono">{{ $index + 1 }}</div>
                                <div class="w-12 h-12 rounded-xl border border-white/10 overflow-hidden bg-gray-900 mr-4">
                                    @if($mob->image)
                                        <img src="{{ asset('storage/' . $mob->image) }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-black text-white tracking-tight">{{ $mob->name }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase tracking-widest">{{ $mob->biome->name }} · {{ $mob->category->name }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs font-black text-indigo-400 uppercase tracking-tighter">Threat Score</div>
                                    <div class="text-lg font-black text-white">{{ number_format($mob->threat_score, 0) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
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
                            'rgba(99, 102, 241, 0.6)'
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
                        backgroundColor: 'rgba(99, 102, 241, 0.4)',
                        borderColor: '#6366f1',
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
