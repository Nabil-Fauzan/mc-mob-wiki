<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl sm:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-amber-600 uppercase tracking-tighter mb-4">
                    Hall of Fame
                </h1>
                <p class="text-gray-400 max-w-2xl mx-auto text-sm sm:text-base">
                    Recognizing the most dedicated and skilled researchers in the Aether Ocean registry.
                </p>
            </div>

            <!-- Tabs -->
            <div class="flex justify-center mb-12">
                <div class="inline-flex bg-gray-900/50 p-1.5 rounded-2xl border border-white/10">
                    <a href="{{ route('leaderboard', ['type' => 'xp']) }}" 
                       class="px-6 py-2.5 rounded-xl text-sm font-bold uppercase tracking-widest transition-all {{ $type === 'xp' ? 'bg-amber-500 text-black shadow-[0_0_15px_rgba(245,158,11,0.4)]' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        Top XP / Level
                    </a>
                    <a href="{{ route('leaderboard', ['type' => 'reputation']) }}" 
                       class="px-6 py-2.5 rounded-xl text-sm font-bold uppercase tracking-widest transition-all {{ $type === 'reputation' ? 'bg-amber-500 text-black shadow-[0_0_15px_rgba(245,158,11,0.4)]' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        Top Reputation
                    </a>
                </div>
            </div>

            <!-- Leaderboard List -->
            <div class="space-y-4">
                @foreach($users as $index => $user)
                    @php
                        // Determine rank across pagination
                        $rank = $users->firstItem() + $index;
                        $isTop3 = $rank <= 3;
                    @endphp
                    <div @class([
                        'glass-card rounded-[2rem] p-4 sm:p-6 flex items-center gap-4 sm:gap-6 transition-all hover:scale-[1.01]',
                        'border-amber-500/30 bg-gradient-to-r from-amber-500/10 to-transparent' => $rank === 1,
                        'border-gray-400/30 bg-gradient-to-r from-gray-400/10 to-transparent' => $rank === 2,
                        'border-amber-700/30 bg-gradient-to-r from-amber-700/10 to-transparent' => $rank === 3,
                        'border-white/5' => !$isTop3,
                    ])>
                        <!-- Rank Number -->
                        <div class="w-12 h-12 shrink-0 flex items-center justify-center">
                            @if($rank === 1)
                                <span class="text-4xl">👑</span>
                            @elseif($rank === 2)
                                <span class="text-3xl">🥈</span>
                            @elseif($rank === 3)
                                <span class="text-3xl">🥉</span>
                            @else
                                <span class="text-2xl font-black text-gray-600">#{{ $rank }}</span>
                            @endif
                        </div>

                        <!-- Avatar -->
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl overflow-hidden shrink-0 border border-white/10 bg-gray-900">
                            @if($user->avatar_url)
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-brand-500 to-cyan-400 flex items-center justify-center text-white font-black text-xl uppercase">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('researchers.show', $user->public_slug) }}" class="text-lg sm:text-xl font-black text-white hover:text-brand-400 transition-colors truncate block">
                                {{ $user->name }}
                            </a>
                            <p class="text-[10px] sm:text-xs text-gray-500 uppercase tracking-widest mt-1">
                                Level {{ $user->level }} Researcher
                            </p>
                        </div>

                        <!-- Stats -->
                        <div class="text-right shrink-0">
                            @if($type === 'xp')
                                <p class="text-xl sm:text-2xl font-black text-amber-400">{{ number_format($user->xp) }}</p>
                                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Total XP</p>
                            @else
                                <p class="text-xl sm:text-2xl font-black text-amber-400">{{ number_format($user->comment_votes_count ?? 0) }}</p>
                                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Reputation</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex justify-center">
                {{ $users->appends(['type' => $type])->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
