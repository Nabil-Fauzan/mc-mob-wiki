<x-app-layout>
    <div class="py-12 relative">
        <!-- Ambient Background -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-gray-950"></div>
            <div class="absolute w-full h-full bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-900/40 via-gray-900/90 to-black"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay"></div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10 space-y-8">
            
            <!-- Hero Section -->
            <div class="glass-card rounded-[2.5rem] border border-white/10 overflow-hidden relative shadow-[0_0_50px_rgba(79,70,229,0.15)] flex flex-col md:flex-row items-center gap-8 p-12 bg-gray-900/50 backdrop-blur-xl">
                <!-- Avatar -->
                <div class="relative shrink-0">
                    <div class="w-48 h-48 rounded-[2rem] overflow-hidden border-4 border-indigo-500/30 shadow-[0_0_30px_rgba(79,70,229,0.3)] bg-gray-900">
                        @if($user->avatar_url)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-black text-6xl uppercase shadow-inner">
                                {{ substr($user->name, 0, 2) }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="text-center md:text-left flex-1">
                    <h1 class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 tracking-tight drop-shadow-sm mb-4">
                        {{ $user->name }}
                    </h1>
                    
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mb-6">
                        @if($user->is_admin)
                            <div class="inline-flex items-center space-x-2 bg-gradient-to-r from-red-600 to-red-900 border border-red-400/50 px-5 py-2.5 rounded-full shadow-[0_0_15px_rgba(239,68,68,0.5)] animate-pulse">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                <span class="text-white font-black tracking-widest uppercase text-sm">Master Admin</span>
                            </div>
                        @endif

                        <div class="inline-flex items-center space-x-3 bg-white/5 border border-white/10 px-5 py-2.5 rounded-full">
                            @if($totalComments >= 50)
                                <svg class="w-5 h-5 text-red-500 drop-shadow-[0_0_8px_rgba(239,68,68,0.8)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            @elseif($totalComments >= 20)
                            <svg class="w-5 h-5 text-yellow-400 drop-shadow-[0_0_5px_rgba(250,204,21,0.8)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        @elseif($totalComments >= 5)
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        @else
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        @endif
                        <span class="text-white font-bold tracking-widest uppercase text-sm">Rank: {{ $rank }}</span>
                        </div>
                    </div>

                    <p class="text-gray-400 max-w-2xl text-lg leading-relaxed">
                        A dedicated researcher exploring the depths of the Wiki. Has recorded <span class="text-white font-bold">{{ $totalComments }}</span> field observations and actively tracking <span class="text-white font-bold">{{ $favorites->count() }}</span> unique entities.
                    </p>
                </div>
            </div>

            <!-- Content Grid 12 cols -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left: Favorite Mobs (8 cols) -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <svg class="w-8 h-8 text-red-500 fill-current drop-shadow-[0_0_10px_rgba(239,68,68,0.5)]" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        <h2 class="text-2xl font-black text-white tracking-widest uppercase">Favorite Entities</h2>
                    </div>

                    @if($favorites->isEmpty())
                        <div class="glass-card rounded-[2rem] border border-white/5 p-12 text-center bg-gray-900/30">
                            <svg class="w-16 h-16 mx-auto text-gray-700 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <h3 class="text-xl font-bold text-gray-300 mb-2">No Targeted Entities Yet</h3>
                            <p class="text-gray-500">This researcher hasn't marked any entities as favorites.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($favorites as $mob)
                                <a href="{{ route('mobs.show', $mob) }}" class="group block glass-card rounded-3xl border border-white/5 overflow-hidden hover:border-indigo-500/50 transition-all duration-300 bg-gray-900/40 hover:bg-gray-800/60 hover:shadow-[0_0_30px_rgba(79,70,229,0.15)] hover:-translate-y-1">
                                    @if($mob->image)
                                        <div class="h-48 overflow-hidden relative">
                                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent z-10 opacity-80"></div>
                                            <img src="{{ Storage::url($mob->image) }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700" alt="{{ $mob->name }}">
                                        </div>
                                    @else
                                        <div class="h-48 bg-gray-800 flex items-center justify-center relative">
                                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent z-10 opacity-80"></div>
                                            <span class="text-gray-600 font-bold uppercase tracking-widest text-xl">No Image</span>
                                        </div>
                                    @endif
                                    <div class="p-6 relative z-20 -mt-12">
                                        <h3 class="text-xl font-black text-white tracking-wide mb-1 group-hover:text-indigo-400 transition-colors">{{ $mob->name }}</h3>
                                        <span class="text-xs font-bold uppercase text-gray-500">{{ $mob->biome->name ?? 'Unknown Biome' }} • {{ $mob->category->name }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Right: Recent Field Notes (4 cols) -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <svg class="w-8 h-8 text-indigo-400 drop-shadow-[0_0_10px_rgba(129,140,248,0.5)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <h2 class="text-2xl font-black text-white tracking-widest uppercase">Field Notes</h2>
                    </div>

                    <div class="glass-card rounded-[2rem] border border-white/5 p-6 bg-gray-900/40">
                        @if($comments->isEmpty())
                            <div class="text-center py-8">
                                <p class="text-gray-500 font-medium text-sm">No field notes recorded yet.</p>
                            </div>
                        @else
                            <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-white/10 before:to-transparent">
                                @foreach($comments->take(10) as $comment)
                                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                                        <!-- Timeline dot -->
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-gray-900 bg-indigo-500 text-white shadow shadow-indigo-500/50 shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 relative z-10">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                        </div>
                                        
                                        <!-- Content -->
                                        <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-4 rounded-2xl bg-white/5 border border-white/5 hover:border-indigo-500/30 transition-colors">
                                            <div class="flex flex-col space-y-1 mb-2">
                                                <a href="{{ route('mobs.show', $comment->mob) }}" class="text-sm font-bold text-indigo-400 hover:text-indigo-300">
                                                    On: {{ $comment->mob->name }}
                                                </a>
                                                <span class="text-[10px] text-gray-500 font-mono tracking-wider">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm text-gray-300 leading-relaxed italic border-l-2 border-indigo-500/50 pl-3">
                                                "{{ Str::limit($comment->body, 120) }}"
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
