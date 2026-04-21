<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex flex-col">
                <!-- Breadcrumbs -->
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3 text-[10px] uppercase font-black tracking-widest text-gray-500">
                        <li class="inline-flex items-center">
                            <a href="{{ route('mobs.index') }}" class="hover:text-brand-400">Wiki</a>
                        </li>
                        @if($mob->biomes->first()?->dimension)
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-600 mx-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                                <a href="{{ route('dimensions.index') }}" class="hover:text-brand-400">{{ $mob->biomes->first()->dimension->name }}</a>
                            </div>
                        </li>
                        @endif
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-600 mx-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                                <span class="text-brand-500">{{ $mob->name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="text-4xl font-black text-white tracking-tighter">
                    {{ $mob->name }} <span class="text-brand-500 animate-pulse">_</span>
                </h2>
            </div>
            <div class="flex space-x-3" x-data="{ favorited: {{ $mob->favoritedBy()->where('user_id', auth()->id())->exists() ? 'true' : 'false' }}, count: {{ $mob->favoritedBy()->count() }} }">
                <button @click="
                    fetch('{{ route('mobs.favorite', $mob) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        favorited = data.favorited;
                        count = data.count;
                        window.notify(favorited ? 'Added to favorites' : 'Removed from favorites', favorited ? 'success' : 'info');
                    })"
                    class="flex items-center space-x-2 px-4 py-2 bg-white/5 hover:bg-white/10 rounded-xl border border-white/10 transition-all group">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-125" :class="favorited ? 'text-red-500 fill-current' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <span class="text-xs font-black text-white" x-text="count"></span>
                </button>
                @auth
                    <a href="{{ route('mobs.edit', $mob) }}" class="btn-primary-mc flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        Edit Registry
                    </a>
                @endauth
                <a href="{{ route('mobs.index') }}" class="px-6 py-2.5 bg-white/5 hover:bg-white/10 text-white font-bold rounded-lg border border-white/10 transition-all">
                    Back to Wiki
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-init="
        let recent = JSON.parse(localStorage.getItem('recent_research') || '[]');
        recent = recent.filter(m => m.id !== {{ $mob->id }});
        recent.unshift({
            id: {{ $mob->id }},
            name: '{{ $mob->name }}',
            image: '{{ $mob->image }}'
        });
        localStorage.setItem('recent_research', JSON.stringify(recent.slice(0, 6)));
    ">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-card rounded-[3rem] overflow-hidden fade-in-up">
                <div class="p-8 md:p-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <!-- Left Column: Visuals & Quick Stats -->
                        <div class="space-y-8">
                            <div class="aspect-square bg-gray-950 rounded-[2rem] overflow-hidden border border-white/10 relative group">
                                <div class="absolute inset-0 bg-gradient-to-br from-brand-500/5 to-transparent"></div>
                                @if($mob->image)
                                    <img src="{{ asset('storage/' . $mob->image) }}" alt="{{ $mob->name }}" class="w-full h-full object-contain p-8 group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-800">
                                        <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute bottom-4 left-4">
                                    <span class="px-3 py-1 bg-black/40 backdrop-blur-md border border-white/10 text-xs text-brand-400 rounded-full font-mono">UID: {{ str_pad($mob->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                            
                            <div class="bg-white/5 p-8 rounded-[2rem] border border-white/10">
                                <h4 class="text-xs font-black text-gray-500 uppercase tracking-[0.2em] mb-6 flex items-center">
                                    <span class="w-8 h-px bg-gray-800 mr-3"></span>
                                    Combat & Health
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Health Breakdown -->
                                    <div class="glass-card p-6 rounded-2xl border-red-500/10">
                                        <div class="flex items-center space-x-3 mb-6">
                                            <div class="p-2 bg-red-500/20 rounded-lg">
                                                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path></svg>
                                            </div>
                                            <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Vitality Intel (HP)</span>
                                        </div>
                                        
                                        <div class="space-y-4">
                                            <div class="flex justify-between items-center group">
                                                <span class="text-[10px] font-black text-green-500/70 uppercase">Easy</span>
                                                <span class="text-lg font-black text-white tracking-tight">{{ $mob->health_easy ?: '?' }}</span>
                                            </div>
                                            <div class="flex justify-between items-center group">
                                                <span class="text-[10px] font-black text-yellow-500/70 uppercase">Normal</span>
                                                <span class="text-lg font-black text-white tracking-tight">{{ $mob->health_normal ?: '?' }}</span>
                                            </div>
                                            <div class="flex justify-between items-center group">
                                                <span class="text-[10px] font-black text-red-500/70 uppercase">Hard</span>
                                                <span class="text-lg font-black text-white tracking-tight">{{ $mob->health_hard ?: '?' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Damage Breakdown -->
                                    <div class="glass-card p-6 rounded-2xl border-orange-500/10">
                                        <div class="flex items-center space-x-3 mb-6">
                                            <div class="p-2 bg-orange-500/20 rounded-lg">
                                                <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path d="M13.172 2.172a4 4 0 00-5.656 0L3.828 5.858a4 4 0 105.656 5.656L10 10.343l.515.515a4 4 0 005.656-5.656l-3-3z"></path></svg>
                                            </div>
                                            <span class="text-xs font-black text-gray-400 uppercase tracking-widest">General Damage (ATK)</span>
                                        </div>
                                        
                                        <div class="space-y-4">
                                            <div class="flex justify-between items-center group">
                                                <span class="text-[10px] font-black text-green-500/70 uppercase">Easy</span>
                                                <span class="text-lg font-black text-white tracking-tight">{{ $mob->damage_easy ?: '?' }}</span>
                                            </div>
                                            <div class="flex justify-between items-center group">
                                                <span class="text-[10px] font-black text-yellow-500/70 uppercase">Normal</span>
                                                <span class="text-lg font-black text-white tracking-tight">{{ $mob->damage_normal ?: '?' }}</span>
                                            </div>
                                            <div class="flex justify-between items-center group">
                                                <span class="text-[10px] font-black text-red-500/70 uppercase">Hard</span>
                                                <span class="text-lg font-black text-white tracking-tight">{{ $mob->damage_hard ?: '?' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Specific Attack Profiles -->
                                    @if($mob->melee_attack || $mob->ranged_attack)
                                        <div class="col-span-full glass-card p-6 rounded-2xl border-brand-500/10 mt-2">
                                            <h5 class="text-[10px] font-black text-brand-400 uppercase tracking-widest mb-6 flex items-center">
                                                <span class="w-2 h-2 bg-brand-500 rounded-full mr-2 animate-pulse"></span>
                                                Specialized Attack Arsenal
                                            </h5>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                                @if($mob->melee_attack)
                                                    <div class="space-y-2">
                                                        <span class="text-[9px] font-black text-red-400 uppercase tracking-tighter block mb-1">Melee Profile</span>
                                                        <p class="text-sm text-gray-300 leading-relaxed font-medium bg-black/20 p-3 rounded-xl border border-white/5 italic">
                                                            "{{ $mob->melee_attack }}"
                                                        </p>
                                                    </div>
                                                @endif
                                                @if($mob->ranged_attack)
                                                    <div class="space-y-2">
                                                        <span class="text-[9px] font-black text-blue-400 uppercase tracking-tighter block mb-1">Ranged Profile</span>
                                                        <p class="text-sm text-gray-300 leading-relaxed font-medium bg-black/20 p-3 rounded-xl border border-white/5 italic">
                                                            "{{ $mob->ranged_attack }}"
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Interactive Radar Chart -->
                                    <div class="col-span-full glass-card p-8 rounded-[2.5rem] border-brand-500/10 mt-4 overflow-hidden relative">
                                        <h5 class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-6">Threat Intelligence Radar</h5>
                                        <div class="aspect-square max-w-[300px] mx-auto opacity-80 hover:opacity-100 transition-opacity">
                                            <canvas id="threatRadar"></canvas>
                                        </div>
                                        <div class="absolute top-8 right-8 text-[8px] font-black text-brand-500 uppercase tracking-widest leading-none">
                                            Analytical Node [0.4]<br>
                                            <span class="text-gray-700 italic">Simulated Data</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                @php
                                    preg_match('/\d+/', $mob->health_normal ?: $mob->health, $hM);
                                    preg_match('/\d+/', $mob->damage_normal ?: $mob->damage, $dM);
                                    $h = intval($hM[0] ?? 20);
                                    $d = intval($dM[0] ?? 5);
                                    $x = intval($mob->xp_reward ?: 5);
                                    $r = $mob->category->name == 'Hostile' ? 80 : ($mob->category->name == 'Neutral' ? 50 : 30);
                                @endphp

                                const ctx = document.getElementById('threatRadar').getContext('2d');
                                new Chart(ctx, {
                                    type: 'radar',
                                    data: {
                                        labels: ['Vitality', 'Lethality', 'EXP Value', 'Complexity', 'Spawn Weight'],
                                        datasets: [{
                                            label: '{{ $mob->name }} Profile',
                                            data: [
                                                {{ min(($h/100)*100, 100) }}, 
                                                {{ min(($d/20)*100, 100) }}, 
                                                {{ min(($x/50)*100, 100) }}, 
                                                {{ $r }}, 
                                                70
                                            ],
                                            fill: true,
                                            backgroundColor: 'rgba(14, 165, 233, 0.2)',
                                            borderColor: 'rgb(14, 165, 233)',
                                            pointBackgroundColor: 'rgb(14, 165, 233)',
                                            pointBorderColor: '#fff',
                                            pointHoverBackgroundColor: '#fff',
                                            pointHoverBorderColor: 'rgb(14, 165, 233)'
                                        }]
                                    },
                                    options: {
                                        elements: { line: { borderWidth: 3 } },
                                        scales: {
                                            r: {
                                                angleLines: { color: 'rgba(255, 255, 255, 0.05)' },
                                                grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                                pointLabels: { color: '#94a3b8', font: { size: 10, weight: '900' } },
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

                        <!-- Right Column: Description & Metadata -->
                        <div class="flex flex-col h-full">
                            <div class="mb-8">
                                <h1 class="text-6xl font-black text-white mb-8 tracking-tighter">{{ $mob->name }}</h1>
                                
                                <div class="space-y-6">
                                    <h4 class="text-xs font-black text-brand-500 uppercase tracking-[0.2em] flex items-center">
                                        Description
                                        <span class="flex-1 h-px bg-white/10 ml-4"></span>
                                    </h4>
                                    <div class="text-xl text-gray-400 leading-relaxed font-medium">
                                        {!! nl2br(e($mob->description)) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-auto pt-8 border-t border-white/10 grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="glass-card p-6 rounded-2xl">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <div class="w-8 h-8 bg-green-500/10 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        </div>
                                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Natural Habitat</span>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        @forelse($mob->biomes as $biome)
                                            <a href="{{ route('biomes.show', $biome) }}" class="bg-brand-500/10 hover:bg-brand-500/20 border border-brand-500/20 px-3 py-1 rounded-full text-[10px] font-bold text-brand-300 transition-all">
                                                {{ $biome->name }}
                                            </a>
                                        @empty
                                            @if(!$mob->spawning_conditions)
                                                <span class="text-gray-500 text-sm italic">Area Classified</span>
                                            @endif
                                        @endforelse
                                    </div>
                                    
                                    @if($mob->spawning_conditions)
                                        <div class="mt-4 pt-4 border-t border-white/5">
                                            <span class="text-[9px] font-black uppercase tracking-widest text-brand-400 block mb-2">Special Conditions</span>
                                            <p class="text-xs text-gray-300 font-medium leading-relaxed italic">
                                                "{{ $mob->spawning_conditions }}"
                                            </p>
                                        </div>
                                    @endif
                                </div>
                                <div class="glass-card p-6 rounded-2xl border-yellow-500/10">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                            </div>
                                            <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Loot Intelligence</span>
                                        </div>
                                        @if($mob->xp_reward)
                                            <div class="flex items-center bg-green-500/10 px-2 py-1 rounded-md border border-green-500/20">
                                                <span class="text-[9px] font-black text-green-500 uppercase mr-1">XP</span>
                                                <span class="text-xs font-mono font-bold text-white">{{ $mob->xp_reward }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="space-y-2">
                                        @forelse($mob->loot as $item)
                                            <div class="flex items-center justify-between p-2 rounded-xl bg-white/5 border border-white/5 group hover:border-yellow-500/30 transition-all">
                                                <div class="flex items-center space-x-3">
                                                    <span class="text-lg">{{ $item->icon ?: '📦' }}</span>
                                                    <div>
                                                        <p class="text-xs font-bold text-white leading-none mb-1">{{ $item->item_name }}</p>
                                                        <div class="flex items-center space-x-2">
                                                            <span class="text-[8px] font-black uppercase tracking-tighter
                                                                {{ $item->rarity == 'Common' ? 'text-gray-400' : '' }}
                                                                {{ $item->rarity == 'Uncommon' ? 'text-green-400' : '' }}
                                                                {{ $item->rarity == 'Rare' ? 'text-blue-400' : '' }}
                                                                {{ $item->rarity == 'Legendary' ? 'text-purple-400' : '' }}
                                                            ">{{ $item->rarity }}</span>
                                                            <span class="w-1 h-1 bg-white/10 rounded-full"></span>
                                                            <span class="text-[8px] font-bold text-gray-500">{{ $item->chance }} chance</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="text-[10px] font-mono font-black text-yellow-500 bg-yellow-500/10 px-1.5 py-0.5 rounded">x{{ $item->quantity }}</span>
                                            </div>
                                        @empty
                                            @if($mob->drops)
                                                <p class="text-xs text-gray-300 font-medium italic opacity-60">"{{ $mob->drops }}"</p>
                                            @else
                                                <div class="py-4 text-center">
                                                    <span class="text-[10px] text-gray-600 italic">No loot intelligence recovered</span>
                                                </div>
                                            @endif
                                        @endforelse
                                    </div>

                                    <!-- Loot Probability Calculator -->
                                    <div class="mt-6 pt-6 border-t border-white/5" x-data="{ kills: 10 }">
                                        <div class="flex justify-between items-center mb-4">
                                            <span class="text-[9px] font-black text-yellow-600 uppercase tracking-widest">Farming Intelligence</span>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-[10px] text-gray-600 font-bold">Kills:</span>
                                                <input type="number" x-model="kills" class="w-12 bg-white/5 border-none focus:ring-0 text-white text-[10px] font-bold p-0 text-center">
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            @foreach($mob->loot->take(3) as $item)
                                                <div class="flex justify-between text-[10px]">
                                                    <span class="text-gray-500">{{ $item->item_name }}</span>
                                                    @php
                                                        preg_match('/\d+/', $item->chance, $cM);
                                                        $c = intval($cM[0] ?? 100);
                                                    @endphp
                                                    <span class="text-yellow-500 font-black" x-text="(Math.min(100, Math.round((1 - Math.pow(1 - ({{ $c/100 }}), kills)) * 100))) + '% chance'"></span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Professional Alert -->
                            <div class="mt-10 p-6 bg-brand-500/5 border border-brand-500/20 rounded-3xl flex items-start space-x-4">
                                <div class="flex-shrink-0 w-10 h-10 bg-brand-500/10 rounded-xl flex items-center justify-center text-brand-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-brand-400 mb-1">Behavior Protocol</p>
                                    <p class="text-xs text-brand-300/70 leading-relaxed">Intelligence reports suggest that {{ strtolower($mob->name) }} species exhibit {{ strtolower($mob->category->name) }} behavior traits. Exercise standard field protocols when engaging.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Community Intelligence (Comments) -->
            <div class="mt-12 max-w-4xl mx-auto">
                <h3 class="text-xl font-black text-white mb-8 flex items-center">
                    <svg class="w-6 h-6 text-brand-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    Field Observations
                </h3>

                @auth
                    <form action="{{ route('comments.store', $mob) }}" method="POST" class="mb-10">
                        @csrf
                        <div class="glass-card p-6 rounded-[2rem] border-brand-500/20">
                            <textarea name="body" rows="3" class="w-full bg-transparent border-none focus:ring-0 text-white placeholder-gray-600 resize-none font-medium" placeholder="Record your observations about {{ strtolower($mob->name) }} species..."></textarea>
                            <div class="flex justify-between items-center mt-4 pt-4 border-t border-white/5">
                                <p class="text-[10px] text-gray-600 font-mono tracking-widest uppercase">Encryption Active: {{ auth()->user()->name }}</p>
                                <button type="submit" class="px-6 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs font-black rounded-full transition-all shadow-lg shadow-brand-600/30 uppercase tracking-widest">Transmit Note</button>
                            </div>
                        </div>
                    </form>
                @endauth

                <div class="space-y-6">
                    @forelse($mob->comments as $comment)
                        <div x-data="{ editing: false }" class="glass-card p-6 rounded-[2rem] border-white/5 relative group">
                            <div class="flex items-start space-x-4">
                                <a href="{{ route('researchers.show', $comment->user) }}" class="block shrink-0 transition-transform hover:scale-105">
                                    @if($comment->user->avatar_url)
                                        <div class="w-10 h-10 rounded-xl overflow-hidden border border-brand-500/30 shadow-[0_0_10px_rgba(14,165,233,0.2)]">
                                            <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}" class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-accent-400 flex items-center justify-center text-white font-black text-xs uppercase shadow-inner">
                                            {{ substr($comment->user->name, 0, 2) }}
                                        </div>
                                    @endif
                                </a>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <a href="{{ route('researchers.show', $comment->user) }}" class="text-sm font-black text-white hover:text-brand-400 transition-colors">
                                            {{ $comment->user->name }}
                                        </a>
                                        <span class="text-[9px] text-gray-600 font-mono uppercase">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    
                                    <div x-show="!editing" class="text-gray-400 text-sm leading-relaxed">
                                        {{ $comment->body }}
                                    </div>

                                    @can('update', $comment)
                                        <div x-show="editing" x-cloak>
                                            <form action="{{ route('comments.update', $comment) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <textarea name="body" rows="2" class="w-full bg-white/5 border border-white/10 rounded-xl text-white text-sm p-3 mb-2 focus:ring-brand-500">{{ $comment->body }}</textarea>
                                                <div class="flex space-x-2">
                                                    <button type="submit" class="px-3 py-1 bg-brand-600 text-[10px] font-bold text-white rounded-lg">Save Changes</button>
                                                    <button @click="editing = false" type="button" class="px-3 py-1 bg-white/5 text-[10px] font-bold text-gray-400 rounded-lg">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endcan
                                    
                                    <div class="absolute top-6 right-6 flex items-center space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        @can('update', $comment)
                                            <button x-show="!editing" @click="editing = true" class="text-gray-500 hover:text-brand-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                            </button>
                                        @endcan
                                        
                                        @can('delete', $comment)
                                            <form x-show="!editing" action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Expunge this field note permanently?')" class="inline-flex">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-500 hover:text-red-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 bg-white/5 rounded-[2.5rem] border border-dashed border-white/10">
                            <p class="text-sm text-gray-600 font-medium">No field notes have been recorded for this entity yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            </div>

            <!-- Related Entities -->
            @if($relatedMobs->count() > 0)
                <div class="mt-24">
                    <div class="flex items-center justify-between mb-12">
                        <div>
                            <h3 class="text-3xl font-black text-white tracking-tighter uppercase">Related Biological Intelligence</h3>
                            <p class="text-gray-500 text-sm mt-2 italic">Entities often encountered in the same dimensional parameters.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                        @foreach($relatedMobs as $rel)
                            <a href="{{ route('mobs.show', $rel) }}" class="glass-card p-6 rounded-[2.5rem] border-white/5 group hover:border-brand-500/40 transition-all hover:-translate-y-2 duration-500">
                                <div class="aspect-square bg-gray-950 rounded-3xl overflow-hidden mb-6">
                                    @if($rel->image)
                                        <img src="{{ asset('storage/' . $rel->image) }}" alt="{{ $rel->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-800">?</div>
                                    @endif
                                </div>
                                <h4 class="text-lg font-black text-white group-hover:text-brand-400 transition-colors uppercase tracking-tight">{{ $rel->name }}</h4>
                                <span class="text-[9px] font-black text-gray-600 uppercase tracking-widest">{{ $rel->category->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
