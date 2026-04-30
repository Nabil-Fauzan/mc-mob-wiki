<x-app-layout>
    <div class="py-12 relative" id="admin-dashboard">
        @if(session('success'))
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4 relative z-20">
                <div class="glass-card border border-green-500/20 text-green-300 px-4 py-3 rounded-2xl">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        <!-- Ambient Admin Background -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-gray-950"></div>
            <div class="absolute w-full h-full bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-red-900/40 via-gray-900/90 to-black"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/micro-carbon.png')] opacity-20 mix-blend-overlay"></div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10 space-y-8">

            <!-- Admin Header -->
            <div class="glass-card rounded-[2.5rem] border border-red-500/20 overflow-hidden relative shadow-[0_0_50px_rgba(239,68,68,0.1)] flex flex-col md:flex-row items-center justify-between p-8 bg-gray-900/60 backdrop-blur-xl">
                <div class="flex items-center space-x-6">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-red-500 to-red-800 flex items-center justify-center shadow-[0_0_20px_rgba(239,68,68,0.4)]">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-pink-400 tracking-tight uppercase">Master Control Center</h1>
                        <p class="text-gray-400 font-medium text-sm tracking-widest uppercase mt-1">Admin Security Gateway</p>
                    </div>
                </div>
                <div class="mt-4 md:mt-0 flex space-x-3">
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-white/5 hover:bg-white/10 text-white font-bold rounded-xl border border-white/10 transition-colors uppercase tracking-wider text-xs">Return to User Profile</a>
                </div>
                <a href="#oracle-console"
                   class="absolute bottom-4 right-4 inline-flex items-center gap-1.5 px-3 py-2 rounded-full border border-indigo-400/40 bg-indigo-500/15 text-indigo-200 text-[10px] font-black uppercase tracking-widest hover:bg-indigo-500/25 transition-all shadow-[0_0_20px_rgba(99,102,241,0.25)]">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-300 animate-pulse"></span>
                    Oracle Console
                </a>
            </div>

            <!-- Quick Action Hub -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="glass-card p-2 rounded-[2rem] border border-white/10 bg-gray-900/40">
                    <a href="{{ route('mobs.create') }}" class="flex items-center space-x-4 p-4 rounded-[1.75rem] hover:bg-red-500/10 transition-all group">
                        <div class="w-12 h-12 bg-red-500/20 rounded-xl flex items-center justify-center text-red-500 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <div>
                            <span class="block text-xs font-black text-white uppercase tracking-widest">Deploy Mob</span>
                            <span class="text-[9px] text-gray-500 uppercase font-bold">New Entity Entry</span>
                        </div>
                    </a>
                </div>

                <div class="glass-card p-2 rounded-[2rem] border border-white/10 bg-gray-900/40">
                    <a href="{{ route('admin.biomes.create') }}" class="flex items-center space-x-4 p-4 rounded-[1.75rem] hover:bg-brand-500/10 transition-all group">
                        <div class="w-12 h-12 bg-brand-500/20 rounded-xl flex items-center justify-center text-brand-500 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <span class="block text-xs font-black text-white uppercase tracking-widest">Map Biome</span>
                            <span class="text-[9px] text-gray-500 uppercase font-bold">New Environment</span>
                        </div>
                    </a>
                </div>

                <div class="glass-card p-2 rounded-[2rem] border border-white/10 bg-gray-900/40">
                    <a href="#bulk-ops" class="flex items-center space-x-4 p-4 rounded-[1.75rem] hover:bg-white/5 transition-all group">
                        <div class="w-12 h-12 bg-gray-500/20 rounded-xl flex items-center justify-center text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <span class="block text-xs font-black text-white uppercase tracking-widest">Expansion Hub</span>
                            <span class="text-[9px] text-gray-400 uppercase font-bold">Roadmap: Bulk Ops</span>
                        </div>
                    </a>
                </div>

                <div class="glass-card p-2 rounded-[2rem] border border-white/10 bg-gray-900/40">
                    <a href="#system-diagnostics" class="flex items-center space-x-4 p-4 rounded-[1.75rem] hover:bg-white/5 transition-all group">
                        <div class="w-12 h-12 bg-gray-500/20 rounded-xl flex items-center justify-center text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2"></path></svg>
                        </div>
                        <div>
                            <span class="block text-xs font-black text-white uppercase tracking-widest">Global Pail</span>
                            <span class="text-[9px] text-gray-400 uppercase font-bold">Roadmap: Live Ops</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Stat Card 1 -->
                <div class="glass-card rounded-3xl border border-red-500/10 p-6 bg-gray-900/40 hover:bg-gray-800/60 transition-colors group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 font-bold uppercase tracking-widest text-[10px] mb-1">Total Agents</p>
                            <h3 class="text-4xl font-black text-white group-hover:text-red-400 transition-colors">{{ $stats['total_users'] }}</h3>
                        </div>
                        <div class="p-3 bg-red-500/10 rounded-xl text-red-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Stat Card 2 -->
                <div class="glass-card rounded-3xl border-brand-500/10 p-6 bg-gray-900/40 hover:bg-gray-800/60 transition-colors group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 font-bold uppercase tracking-widest text-[10px] mb-1">Entities Tracked</p>
                            <h3 class="text-4xl font-black text-white group-hover:text-brand-400 transition-colors">{{ $stats['total_mobs'] }}</h3>
                        </div>
                        <div class="p-3 bg-brand-500/10 rounded-xl text-brand-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Stat Card 3 -->
                <div class="glass-card rounded-3xl border border-green-500/10 p-6 bg-gray-900/40 hover:bg-gray-800/60 transition-colors group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 font-bold uppercase tracking-widest text-[10px] mb-1">Field Notes</p>
                            <h3 class="text-4xl font-black text-white group-hover:text-green-400 transition-colors">{{ $stats['total_comments'] }}</h3>
                        </div>
                        <div class="p-3 bg-green-500/10 rounded-xl text-green-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- System Health Monitor -->
                <div id="system-diagnostics" class="lg:col-span-2 glass-card rounded-[2.5rem] border border-red-500/10 p-8 bg-gray-900/40 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-8 opacity-10">
                        <svg class="w-32 h-32 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2"></path></svg>
                    </div>
                    <div class="flex items-center space-x-3 mb-8">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-ping"></div>
                        <h2 class="text-lg font-black text-white uppercase tracking-widest">Real-time System Diagnostics</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                        <div class="space-y-4">
                            <div class="flex justify-between text-[10px] font-black uppercase tracking-widest text-gray-500">
                                <span>Processor Load</span>
                                <span class="text-red-400">{{ $diagnostics['uncategorized_mobs'] }}</span>
                            </div>
                            <div class="h-2 bg-white/5 rounded-full overflow-hidden border border-white/5">
                                <div class="h-full bg-gradient-to-r from-red-500 to-pink-500 w-[24%]"></div>
                            </div>
                            <p class="text-[9px] text-gray-600 font-mono italic">Uncategorized mobs</p>
                        </div>

                        <div class="space-y-4">
                            <div class="flex justify-between text-[10px] font-black uppercase tracking-widest text-gray-500">
                                <span>Memory Integrity</span>
                                <span class="text-red-400">{{ $diagnostics['mobs_without_biomes'] }}</span>
                            </div>
                            <div class="h-2 bg-white/5 rounded-full overflow-hidden border border-white/5">
                                <div class="h-full bg-gradient-to-r from-red-500 to-pink-500 w-[45%]"></div>
                            </div>
                            <p class="text-[9px] text-gray-600 font-mono italic">Entities missing biome mapping</p>
                        </div>

                        <div class="space-y-4">
                            <div class="flex justify-between text-[10px] font-black uppercase tracking-widest text-gray-500">
                                <span>Storage Matrix</span>
                                <span class="text-red-400">{{ $diagnostics['mobs_without_images'] }}</span>
                            </div>
                            <div class="h-2 bg-white/5 rounded-full overflow-hidden border border-white/5">
                                <div class="h-full bg-gradient-to-r from-red-500 to-pink-500 w-[68%]"></div>
                            </div>
                            <p class="text-[9px] text-gray-600 font-mono italic">Entities missing images</p>
                        </div>
                    </div>

                    <div class="mt-10 pt-8 border-t border-white/5 flex items-center justify-between">
                        <div class="flex space-x-6">
                            <div class="flex flex-col">
                                <span class="text-[9px] text-gray-600 uppercase font-black tracking-widest">Database Latency</span>
                                <span class="text-white font-mono text-sm">{{ $diagnostics['comments_last_24h'] }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[9px] text-gray-600 uppercase font-black tracking-widest">Cache Driver</span>
                                <span class="text-white font-mono text-sm">{{ $diagnostics['cache_driver'] }} / {{ $diagnostics['cache_health'] }}</span>
                            </div>
                        </div>
                        <button @click="window.notify('Diagnostics are server-driven and refreshed on page load.', 'info')" class="px-4 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-500 text-[10px] font-black uppercase tracking-widest border border-red-500/20 rounded-lg transition-all">Refresh Policy</button>
                    </div>
                </div>

                <!-- Global Broadcast Terminal -->
                <div id="global-comms" class="glass-card rounded-[2.5rem] border border-red-500/10 p-8 bg-gray-900/40" x-data="{ sending: false }">
                    <div class="flex items-center space-x-3 mb-6">
                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M16.5 13a3 3 0 000-6m-8.5 6a3 3 0 010-6"></path></svg>
                        <h2 class="text-lg font-black text-white uppercase tracking-widest">Broadcast Intel</h2>
                    </div>
                    <p class="text-xs text-gray-500 mb-6 font-medium">Transmit a global alert to all active agents in the Aether Wiki system.</p>

                    <textarea class="w-full bg-white/5 border border-white/5 rounded-2xl text-white text-sm p-4 placeholder-gray-700 focus:ring-red-500 focus:border-red-500 resize-none h-32 mb-4" placeholder="Enter transmission message..."></textarea>

                    <button @click="sending = true; setTimeout(() => { sending = false; window.notify('Transmission Successful', 'success') }, 1500)"
                            :disabled="sending"
                            class="w-full py-4 bg-red-600 hover:bg-red-700 disabled:bg-gray-800 text-white font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-red-600/20 transition-all active:scale-95 flex items-center justify-center">
                        <span x-show="!sending">Execute Broadcast</span>
                        <span x-show="sending" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Transmitting...
                        </span>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Users -->
                <div id="recent-users" class="glass-card rounded-[2rem] border border-white/5 p-8 bg-gray-900/40 flex-1">
                    <div class="flex items-center space-x-3 mb-6">
                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <h2 class="text-lg font-black text-white uppercase tracking-widest">Recent Registrations</h2>
                    </div>

                    <div class="space-y-4">
                        @foreach($recentUsers as $usr)
                            <div class="flex justify-between items-center bg-white/5 border border-white/5 rounded-xl p-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 rounded-lg overflow-hidden shrink-0 border border-brand-500/30">
                                        @if($usr->avatar_url)
                                            <img src="{{ $usr->avatar_url }}" alt="{{ $usr->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gray-800 flex items-center justify-center text-gray-400 font-bold text-xs">{{ substr($usr->name, 0, 2) }}</div>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('researchers.show', $usr) }}" class="text-sm font-bold text-white hover:text-red-400 transition-colors">{{ $usr->name }}</a>
                                        <p class="text-[10px] text-gray-500 uppercase tracking-wider">{{ $usr->email }}</p>
                                    </div>
                                </div>
                                <span class="text-[10px] text-gray-600 font-mono">{{ $usr->created_at->format('d M Y') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Comments -->
                <div class="glass-card rounded-[2rem] border border-white/5 p-8 bg-gray-900/40 flex-1">
                    <div class="flex items-center space-x-3 mb-6">
                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        <h2 class="text-lg font-black text-white uppercase tracking-widest">Global Comms</h2>
                    </div>

                    <div class="space-y-4">
                        @foreach($recentComments as $comment)
                            <div class="bg-white/5 border border-white/5 rounded-xl p-4">
                                <div class="flex justify-between items-end mb-2">
                                    <div class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">
                                        <a href="{{ route('researchers.show', $comment->user) }}" class="text-red-400 hover:underline">{{ $comment->user->name }}</a>
                                        on
                                        <a href="{{ route('mobs.show', $comment->mob) }}" class="text-brand-400 hover:underline">{{ $comment->mob->name }}</a>
                                    </div>
                                    <span class="text-[9px] text-gray-600 font-mono">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-gray-400 italic">"{{ Str::limit($comment->body, 60) }}"</p>
                                <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" class="mt-3">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[10px] px-3 py-1.5 rounded-lg bg-red-500/10 border border-red-500/30 text-red-300 hover:bg-red-500/20 font-bold uppercase tracking-widest">
                                        Moderate: Remove
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div id="bulk-ops" class="glass-card rounded-[2rem] border border-white/5 p-8 bg-gray-900/40">
                <div class="flex items-center justify-between gap-4 mb-6">
                    <h2 class="text-lg font-black text-white uppercase tracking-widest">Bulk Operations</h2>
                    <p class="text-xs text-gray-400">Select recent mobs and remove in one action.</p>
                </div>
                <form method="POST" action="{{ route('admin.mobs.bulk-delete') }}">
                    @csrf
                    @method('DELETE')
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-5">
                        @foreach($recentMobs as $mob)
                            <label class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-xl p-3">
                                <input type="checkbox" name="mob_ids[]" value="{{ $mob->id }}" class="rounded border-white/20 bg-black/30">
                                <span class="text-sm text-white font-semibold">{{ $mob->name }}</span>
                                <span class="text-[10px] text-gray-400 ml-auto">{{ $mob->category?->name ?? 'N/A' }}</span>
                            </label>
                        @endforeach
                    </div>
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold uppercase tracking-widest text-xs">
                        Delete Selected Mobs
                    </button>
                </form>
            </div>

            <div id="oracle-console" class="glass-card rounded-[2rem] border border-indigo-500/20 p-8 bg-gray-900/40">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-lg font-black text-white uppercase tracking-widest">Oracle Console</h2>
                    <span class="text-xs text-gray-400">Admin validation panel</span>
                </div>
                <div x-data="{ q: 'Creeper', mode: 'data', lang: 'id', out: '', loading: false }" class="space-y-4">
                    <div class="grid md:grid-cols-4 gap-3">
                        <input x-model="q" class="md:col-span-2 bg-black/30 border border-white/10 rounded-xl px-3 py-2 text-sm text-white" placeholder="Oracle query..." />
                        <select x-model="mode" class="bg-black/30 border border-white/10 rounded-xl px-3 py-2 text-sm text-white">
                            <option value="data">Data</option>
                            <option value="lore">Lore</option>
                        </select>
                        <select x-model="lang" class="bg-black/30 border border-white/10 rounded-xl px-3 py-2 text-sm text-white">
                            <option value="id">ID</option>
                            <option value="en">EN</option>
                        </select>
                    </div>
                    <button @click="loading = true; fetch('/api/oracle', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content') }, body: JSON.stringify({ query: q, mode, lang }) }).then(r => r.json()).then(d => out = JSON.stringify(d, null, 2)).finally(() => loading = false)" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-widest">Run Oracle Check</button>
                    <pre class="bg-black/40 border border-white/10 rounded-xl p-4 text-xs text-indigo-200 overflow-auto min-h-[140px]" x-text="loading ? 'Running...' : (out || 'No result yet.')"></pre>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
