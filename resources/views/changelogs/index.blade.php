<x-app-layout>
    <div class="py-12 sm:py-20 px-4 sm:px-6 lg:px-8 relative min-h-screen">
        <!-- Background Elements -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-brand-500/10 rounded-full blur-[120px] mix-blend-screen translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-purple-500/10 rounded-full blur-[150px] mix-blend-screen -translate-x-1/2 translate-y-1/2"></div>
            
            <!-- Grid pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0zNiAzNHYtNGgtMnY0aC00djJoNHY0aDJ2LTRoNHYtMmgtNHptMC0zMFYwaC0ydjRoLTR2Mmg0djRoMnYtNGg0VjRoLTR6bS0zMCAwVjBoLTJ2NGgtNHYyaDR2NGgydi00aDRWNGgtNHpNMCAzNGgtMnY0aC00djJoNHY0aDJ2LTRoNHYtMmgtNHpNMzYgNjR2LTRoLTJ2NGgtNHYyaDR2NGgydi00aDR2LTJoLTR6bS0zMCAwdi00aC0ydjRoLTR2Mmg0djRoMnYtNGg0di0yaC00eiIgZmlsbD0icmdiYSgyNTUsIDI1NSwgMjU1LCAwLjAzKSIvPjwvZz48L3N2Zz4=')] opacity-50"></div>
        </div>

        <div class="max-w-5xl mx-auto relative z-10">
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-16">
                <div>
                    <h1 class="text-4xl md:text-6xl font-black text-white tracking-tight uppercase" style="text-shadow: 0 0 40px rgba(14,165,233,0.5);">
                        System <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-400 to-purple-500">Changelogs</span>
                    </h1>
                    <p class="text-gray-400 mt-4 text-sm md:text-base max-w-xl font-medium tracking-wide">
                        The definitive historical record of Aether Protocol's evolution. Powered by the Oracle AI framework.
                    </p>
                </div>
                
                @if(auth()->check() && auth()->user()->is_admin)
                    <form action="{{ route('changelogs.generate') }}" method="POST">
                        @csrf
                        <button type="submit" class="group relative px-6 py-3 rounded-2xl bg-brand-500/10 border border-brand-500/30 text-brand-400 hover:bg-brand-500 hover:text-white transition-all overflow-hidden flex items-center gap-3">
                            <div class="absolute inset-0 bg-gradient-to-r from-brand-500 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <svg class="w-5 h-5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            <span class="relative z-10 font-bold uppercase tracking-widest text-xs">Generate Next Version</span>
                        </button>
                    </form>
                @endif
            </div>

            <!-- Timeline -->
            <div class="space-y-12 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-white/10 before:to-transparent">
                @forelse($changelogs as $index => $log)
                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                        
                        <!-- Timeline Node -->
                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-gray-900 bg-brand-500 shadow-[0_0_20px_rgba(14,165,233,0.5)] md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10 shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        
                        <!-- Content Card -->
                        <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] glass-card p-6 sm:p-8 rounded-[2rem] border border-white/5 hover:border-brand-500/30 transition-all hover:bg-white/[0.03]">
                            <div class="flex items-center justify-between mb-6 border-b border-white/5 pb-6">
                                <div>
                                    <h3 class="text-2xl font-black text-white mb-2">{{ $log->title }}</h3>
                                    <div class="flex items-center gap-3">
                                        <span class="px-2 py-1 rounded-md bg-brand-500/20 text-brand-400 text-xs font-mono font-bold">{{ $log->version }}</span>
                                        <span class="text-xs text-gray-500 font-mono">{{ $log->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="prose prose-invert prose-brand max-w-none prose-h2:text-xl prose-h2:mb-4 prose-h3:text-lg prose-p:text-gray-400 prose-p:leading-relaxed prose-li:text-gray-400">
                                {!! Str::markdown($log->ai_summary) !!}
                            </div>

                            @if(auth()->check() && auth()->user()->is_admin)
                            <div class="mt-8 pt-6 border-t border-white/5">
                                <details class="group/details">
                                    <summary class="text-xs text-gray-500 uppercase tracking-widest cursor-pointer hover:text-white transition-colors font-bold flex items-center gap-2">
                                        <svg class="w-4 h-4 group-open/details:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        View Raw Commits
                                    </summary>
                                    <div class="mt-4 p-4 rounded-xl bg-black/50 border border-white/5 overflow-x-auto">
                                        <pre class="text-[10px] text-gray-400 font-mono m-0 leading-relaxed">{{ $log->raw_commits }}</pre>
                                    </div>
                                </details>
                            </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-20 text-center glass-card rounded-[3rem] border border-dashed border-white/10 relative z-10 bg-black/20">
                        <svg class="w-16 h-16 mx-auto text-gray-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <h3 class="text-2xl font-black text-white mb-2">No Records Found</h3>
                        <p class="text-gray-500 font-medium">The Aether Protocol has no documented evolution history yet.</p>
                        @if(auth()->check() && auth()->user()->is_admin)
                            <p class="text-sm text-brand-400 mt-4">Click "Generate Next Version" above to initiate the first AI scan.</p>
                        @endif
                    </div>
                @endforelse
            </div>
            
        </div>
    </div>
</x-app-layout>
