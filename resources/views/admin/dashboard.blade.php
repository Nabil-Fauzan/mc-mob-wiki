<x-app-layout>
    <div class="py-12 relative">
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

                <!-- Stat Card 4 -->
                <div class="glass-card rounded-3xl border border-purple-500/10 p-6 bg-gray-900/40 hover:bg-gray-800/60 transition-colors group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 font-bold uppercase tracking-widest text-[10px] mb-1">New Intel (7 Days)</p>
                            <h3 class="text-4xl font-black text-white group-hover:text-purple-400 transition-colors">+{{ $stats['new_users'] }}</h3>
                        </div>
                        <div class="p-3 bg-purple-500/10 rounded-xl text-purple-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Users -->
                <div class="glass-card rounded-[2rem] border border-white/5 p-8 bg-gray-900/40 flex-1">
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
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
