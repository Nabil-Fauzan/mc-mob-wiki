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
            <div class="flex flex-col items-center mb-12 space-y-4">
                <div class="inline-flex bg-gray-900/50 p-1.5 rounded-2xl border border-white/10">
                    <a href="{{ route('leaderboard', ['type' => 'xp']) }}" 
                       class="px-6 py-2.5 rounded-xl text-sm font-bold uppercase tracking-widest transition-all {{ !$isMob ? 'bg-amber-500 text-black shadow-[0_0_15px_rgba(245,158,11,0.4)]' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        Pengguna
                    </a>
                    <a href="{{ route('leaderboard', ['type' => 'mobs_hp']) }}" 
                       class="px-6 py-2.5 rounded-xl text-sm font-bold uppercase tracking-widest transition-all {{ $isMob ? 'bg-red-500 text-black shadow-[0_0_15px_rgba(239,68,68,0.4)]' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        Monster
                    </a>
                </div>

                <!-- Sub filters -->
                @if(!$isMob)
                <div class="inline-flex bg-gray-900/50 p-1 rounded-xl border border-white/10">
                    <a href="{{ route('leaderboard', ['type' => 'xp']) }}" class="px-4 py-1.5 rounded-lg text-xs font-bold uppercase tracking-widest transition-all {{ $type === 'xp' ? 'bg-white/10 text-white' : 'text-gray-500 hover:text-gray-300' }}">Top XP</a>
                    <a href="{{ route('leaderboard', ['type' => 'reputation']) }}" class="px-4 py-1.5 rounded-lg text-xs font-bold uppercase tracking-widest transition-all {{ $type === 'reputation' ? 'bg-white/10 text-white' : 'text-gray-500 hover:text-gray-300' }}">Reputation</a>
                </div>
                @else
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <div class="inline-flex bg-gray-900/50 p-1 rounded-xl border border-white/10">
                        <a href="{{ route('leaderboard', ['type' => 'mobs_hp', 'difficulty' => $difficulty]) }}" class="px-4 py-1.5 rounded-lg text-xs font-bold uppercase tracking-widest transition-all {{ $type === 'mobs_hp' ? 'bg-white/10 text-white' : 'text-gray-500 hover:text-gray-300' }}">Toughest (HP)</a>
                        <a href="{{ route('leaderboard', ['type' => 'mobs_dmg', 'difficulty' => $difficulty]) }}" class="px-4 py-1.5 rounded-lg text-xs font-bold uppercase tracking-widest transition-all {{ $type === 'mobs_dmg' ? 'bg-white/10 text-white' : 'text-gray-500 hover:text-gray-300' }}">Deadliest (ATK)</a>
                    </div>
                    
                    <div class="inline-flex bg-gray-900/50 p-1 rounded-xl border border-white/10">
                        <a href="{{ route('leaderboard', ['type' => $type, 'difficulty' => 'easy']) }}" class="px-4 py-1.5 rounded-lg text-xs font-bold uppercase tracking-widest transition-all {{ $difficulty === 'easy' ? 'bg-green-500/20 text-green-400' : 'text-gray-500 hover:text-gray-300' }}">Easy</a>
                        <a href="{{ route('leaderboard', ['type' => $type, 'difficulty' => 'normal']) }}" class="px-4 py-1.5 rounded-lg text-xs font-bold uppercase tracking-widest transition-all {{ $difficulty === 'normal' ? 'bg-yellow-500/20 text-yellow-400' : 'text-gray-500 hover:text-gray-300' }}">Normal</a>
                        <a href="{{ route('leaderboard', ['type' => $type, 'difficulty' => 'hard']) }}" class="px-4 py-1.5 rounded-lg text-xs font-bold uppercase tracking-widest transition-all {{ $difficulty === 'hard' ? 'bg-red-500/20 text-red-400' : 'text-gray-500 hover:text-gray-300' }}">Hard</a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Leaderboard List -->
            <div class="space-y-4">
                @foreach($records as $index => $record)
                    @php
                        // Determine rank across pagination
                        $rank = $records->firstItem() + $index;
                        $isTop3 = $rank <= 3;
                        $primaryColor = $isMob ? 'red' : 'amber';
                    @endphp
                    <div @class([
                        'glass-card rounded-[2rem] p-4 sm:p-6 flex items-center gap-4 sm:gap-6 transition-all hover:scale-[1.01]',
                        "border-{$primaryColor}-500/30 bg-gradient-to-r from-{$primaryColor}-500/10 to-transparent" => $rank === 1,
                        'border-gray-400/30 bg-gradient-to-r from-gray-400/10 to-transparent' => $rank === 2,
                        "border-{$primaryColor}-700/30 bg-gradient-to-r from-{$primaryColor}-700/10 to-transparent" => $rank === 3,
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
                            @if(!$isMob)
                                @if($record->avatar_url)
                                    <img src="{{ $record->avatar_url }}" alt="{{ $record->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-brand-500 to-cyan-400 flex items-center justify-center text-white font-black text-xl uppercase">
                                        {{ substr($record->name, 0, 2) }}
                                    </div>
                                @endif
                            @else
                                <img src="https://minecraft-api.vercel.app/api/entity/{{ strtolower(str_replace(' ', '-', $record->name)) }}" alt="{{ $record->name }}" class="w-full h-full object-contain p-2" onerror="this.src = 'https://ui-avatars.com/api/?name={{ urlencode($record->name) }}&background=0f172a&color=0ea5e9'">
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            @if(!$isMob)
                                <a href="{{ route('researchers.show', $record->public_slug) }}" class="text-lg sm:text-xl font-black text-white hover:text-brand-400 transition-colors truncate block">
                                    {{ $record->name }}
                                </a>
                                <p class="text-[10px] sm:text-xs text-gray-500 uppercase tracking-widest mt-1">
                                    Level {{ $record->level }} Researcher
                                </p>
                            @else
                                <a href="{{ route('mobs.show', $record) }}" class="text-lg sm:text-xl font-black text-white hover:text-brand-400 transition-colors truncate block">
                                    {{ $record->name }}
                                </a>
                                <p class="text-[10px] sm:text-xs text-gray-500 uppercase tracking-widest mt-1">
                                    {{ $record->category->name ?? 'Unknown' }}
                                </p>
                            @endif
                        </div>

                        <!-- Stats -->
                        <div class="text-right shrink-0">
                            @if(!$isMob)
                                @if($type === 'xp')
                                    <p class="text-xl sm:text-2xl font-black text-amber-400">{{ number_format($record->xp) }}</p>
                                    <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Total XP</p>
                                @else
                                    <p class="text-xl sm:text-2xl font-black text-amber-400">{{ number_format($record->comment_votes_count ?? 0) }}</p>
                                    <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Reputation</p>
                                @endif
                            @else
                                @if($type === 'mobs_hp')
                                    <p class="text-xl sm:text-2xl font-black text-red-400">{{ number_format($record->getAttribute('health_'.$difficulty)) }}</p>
                                    <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">HP ({{ ucfirst($difficulty) }})</p>
                                @else
                                    <p class="text-xl sm:text-2xl font-black text-red-400">{{ number_format($record->getAttribute('damage_'.$difficulty), 1) }}</p>
                                    <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Damage ({{ ucfirst($difficulty) }})</p>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex justify-center">
                {{ $records->appends(['type' => $type, 'difficulty' => $difficulty])->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
