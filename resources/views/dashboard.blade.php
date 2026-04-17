<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Hero Stats Segment -->
            <div class="mb-12">
                <h1 class="text-4xl font-black text-white tracking-tight mb-2">Command <span class="text-indigo-500">Center</span></h1>
                <p class="text-gray-400 text-sm">Synchronized intelligence for researcher {{ auth()->user()->name }}.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                    <div class="glass-card p-6 rounded-[2rem] border-indigo-500/20 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-indigo-500/10 rounded-2xl flex items-center justify-center text-indigo-500">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path></svg>
                            </div>
                            <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">Bookmarks</span>
                        </div>
                        <p class="text-4xl font-black text-white">{{ $stats['favorites_count'] }}</p>
                        <p class="text-xs text-gray-500 mt-1 uppercase tracking-tighter">Species in personal archive</p>
                    </div>
                    
                    <div class="glass-card p-6 rounded-[2rem] border-purple-500/20 group">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-purple-500/10 rounded-2xl flex items-center justify-center text-purple-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                            </div>
                            <span class="text-[10px] font-black text-purple-500 uppercase tracking-widest">Observations</span>
                        </div>
                        <p class="text-4xl font-black text-white">{{ $stats['comments_count'] }}</p>
                        <p class="text-xs text-gray-500 mt-1 uppercase tracking-tighter">Field notes recorded</p>
                    </div>

                    <div class="glass-card p-6 rounded-[2rem] border-green-500/20 flex flex-col justify-center bg-gradient-to-br from-indigo-500/10 to-transparent">
                        <h4 class="text-xs font-black text-white uppercase tracking-widest mb-4">Quick Registry</h4>
                        <a href="{{ route('mobs.create') }}" class="w-full py-3 bg-white text-black text-center text-xs font-black rounded-xl hover:bg-gray-200 transition-all uppercase tracking-widest">
                            New Discovery
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Bookmarked Species (Grid-ish) -->
                <div class="lg:col-span-2">
                    <h3 class="text-xl font-black text-white mb-8 flex items-center">
                        <span class="w-2 h-2 bg-indigo-500 rounded-full mr-3"></span>
                        Archived Species
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($favorites as $mob)
                            <div class="glass-card p-4 rounded-[2rem] border-white/5 group hover:border-indigo-500/30 transition-all">
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 rounded-2xl bg-gray-950 border border-white/10 overflow-hidden flex-shrink-0">
                                        @if($mob->image)
                                            <img src="{{ asset('storage/' . $mob->image) }}" class="w-full h-full object-contain p-2">
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-black text-white truncate">{{ $mob->name }}</h4>
                                        <p class="text-[10px] text-gray-500 uppercase tracking-widest">{{ $mob->category->name }} · {{ $mob->biome->name }}</p>
                                    </div>
                                    <a href="{{ route('mobs.show', $mob) }}" class="w-8 h-8 rounded-full border border-white/10 flex items-center justify-center text-gray-500 hover:text-white hover:bg-white/10 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-20 text-center glass-card rounded-[3rem] border-dashed border-white/10">
                                <p class="text-gray-500 font-medium">Your personal archive is currently empty.</p>
                                <a href="{{ route('mobs.index') }}" class="mt-4 inline-block text-indigo-500 font-bold hover:underline">Explore Wiki</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Activity Feed -->
                <div>
                    <h3 class="text-xl font-black text-white mb-8 flex items-center">
                        <span class="w-2 h-2 bg-purple-500 rounded-full mr-3"></span>
                        Recent Intel
                    </h3>
                    
                    <div class="space-y-4">
                        @forelse($stats['recent_comments'] as $comment)
                            <div class="glass-card p-4 rounded-2xl border-white/5">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[9px] font-black text-indigo-400 uppercase tracking-widest">{{ $comment->mob->name }}</span>
                                    <span class="text-[9px] text-gray-600 font-mono">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-gray-400 leading-relaxed italic">"{{ Str::limit($comment->body, 80) }}"</p>
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
    </div>
</x-app-layout>
