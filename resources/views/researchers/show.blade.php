<x-app-layout>
    @php
        $bannerUrl = filter_var($user->banner, FILTER_VALIDATE_URL) ? $user->banner : ($user->banner ? asset('storage/'.$user->banner) : null);
    @endphp
    
    <div class="py-8 sm:py-12 px-4 sm:px-6 lg:px-8 relative min-h-screen">
        <!-- Background Banner -->
        <div class="absolute inset-0 h-[400px] w-full z-0">
            @if($bannerUrl)
                <img src="{{ $bannerUrl }}" alt="Profile Banner" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[#020617]/80 to-[#020617]"></div>
            @else
                <div class="w-full h-full bg-gradient-to-b from-brand-900/10 via-[#020617]/50 to-[#020617]"></div>
            @endif
        </div>

        <div class="max-w-7xl mx-auto space-y-8 relative z-10 pt-16">
            <div class="glass-card rounded-[2rem] sm:rounded-[2.75rem] border border-white/10 overflow-hidden relative p-5 sm:p-8 lg:p-10 backdrop-blur-xl bg-black/40 shadow-2xl">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(14,165,233,0.18),_transparent_35%)]"></div>
                <div class="relative flex flex-col lg:flex-row gap-6 sm:gap-8 lg:items-center">
                    <div class="shrink-0">
                        <div class="w-28 h-28 sm:w-40 sm:h-40 rounded-[1.75rem] overflow-hidden border border-white/10 shadow-[0_0_30px_rgba(14,165,233,0.2)] bg-gray-900">
                            @if($user->avatar_url)
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-brand-500 to-cyan-400 flex items-center justify-center text-white font-black text-3xl sm:text-5xl uppercase">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5">
                            <div>
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <span class="px-3 py-1 rounded-full bg-brand-500/10 border border-brand-500/20 text-[10px] font-black uppercase tracking-[0.2em] text-brand-300">
                                        Public Researcher Profile
                                    </span>
                                    @unless($user->profile_is_public)
                                        <span class="px-3 py-1 rounded-full bg-yellow-500/10 border border-yellow-500/20 text-[10px] font-black uppercase tracking-[0.2em] text-yellow-300">
                                            Private Preview
                                        </span>
                                    @endunless
                                </div>

                                <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight break-words">{{ $user->name }}</h1>
                                @if($user->active_title)
                                    <p class="mt-2 text-amber-400 font-black uppercase tracking-[0.25em] text-[12px] flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                                        {{ $user->active_title }}
                                    </p>
                                @endif
                                <p class="mt-2 text-brand-300 font-bold uppercase tracking-[0.25em] text-[11px]">{{ $rank }}</p>

                                <div class="mt-4 flex flex-wrap gap-2">
                                    @foreach($roles as $role)
                                        <span @class([
                                            'px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] border',
                                            'bg-red-500/10 text-red-300 border-red-500/20' => $role['tone'] === 'red',
                                            'bg-amber-500/10 text-amber-300 border-amber-500/20' => $role['tone'] === 'amber',
                                            'bg-sky-500/10 text-sky-300 border-sky-500/20' => $role['tone'] === 'sky',
                                            'bg-rose-500/10 text-rose-300 border-rose-500/20' => $role['tone'] === 'rose',
                                            'bg-emerald-500/10 text-emerald-300 border-emerald-500/20' => $role['tone'] === 'emerald',
                                        ])>
                                            {{ $role['label'] }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="glass-card rounded-[1.5rem] p-4 sm:p-5 border-white/10 w-full lg:max-w-sm">
                                <p class="text-[10px] text-gray-500 font-black uppercase tracking-[0.25em] mb-2">Shareable Link</p>
                                <p class="text-sm text-gray-300 break-all">{{ route('researchers.show', $user->public_slug) }}</p>
                                <div class="mt-4 flex flex-col sm:flex-row gap-3">
                                    <button
                                        type="button"
                                        onclick="navigator.clipboard.writeText('{{ route('researchers.show', $user->public_slug) }}'); window.notify('Researcher profile link copied', 'success');"
                                        class="px-4 py-2.5 bg-white/5 hover:bg-white/10 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all text-center border border-white/10"
                                    >
                                        Copy Link
                                    </button>
                                    
                                    @auth
                                        @if(auth()->id() !== $user->id)
                                            <form method="POST" action="{{ route('researchers.connect', $user->public_slug) }}" class="flex-1">
                                                @csrf
                                                <button type="submit" class="w-full px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ auth()->user()->isFollowing($user) ? 'bg-red-500/10 text-red-500 border border-red-500/20 hover:bg-red-500/20' : 'bg-brand-600 hover:bg-brand-700 text-white' }}">
                                                    {{ auth()->user()->isFollowing($user) ? 'Sever Connection' : 'Establish Connection' }}
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('profile.edit') }}" class="flex-1 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all text-center">
                                                Edit Profile
                                            </a>
                                        @endif
                                    @endauth

                                    @if(\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->is_admin && \Illuminate\Support\Facades\Auth::id() !== $user->id && !$user->is_admin)
                                        <form method="POST" action="{{ route('admin.impersonate', $user) }}">
                                            @csrf
                                            <button type="submit" class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all text-center">
                                                IMP
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
                            <div class="rounded-[1.5rem] border border-white/10 bg-black/20 p-4 col-span-2 md:col-span-1">
                                <p class="text-[10px] text-gray-500 font-black uppercase tracking-[0.2em]">Reputation</p>
                                <p class="mt-2 text-2xl sm:text-3xl font-black text-amber-300">{{ $stats['reputation'] }}</p>
                            </div>
                            <div class="rounded-[1.5rem] border border-white/10 bg-black/20 p-4">
                                <p class="text-[10px] text-gray-500 font-black uppercase tracking-[0.2em]">Notes</p>
                                <p class="mt-2 text-2xl sm:text-3xl font-black text-white">{{ $stats['comments_count'] }}</p>
                            </div>
                            <div class="rounded-[1.5rem] border border-white/10 bg-black/20 p-4">
                                <p class="text-[10px] text-gray-500 font-black uppercase tracking-[0.2em]">Tracked</p>
                                <p class="mt-2 text-2xl sm:text-3xl font-black text-rose-300">{{ $stats['favorites_count'] }}</p>
                            </div>
                            <div class="rounded-[1.5rem] border border-brand-500/10 bg-brand-900/10 p-4">
                                <p class="text-[10px] text-brand-500/70 font-black uppercase tracking-[0.2em]">Followers</p>
                                <p class="mt-2 text-2xl sm:text-3xl font-black text-brand-300">{{ $user->followers()->count() }}</p>
                            </div>
                            <div class="rounded-[1.5rem] border border-brand-500/10 bg-brand-900/10 p-4">
                                <p class="text-[10px] text-brand-500/70 font-black uppercase tracking-[0.2em]">Following</p>
                                <p class="mt-2 text-2xl sm:text-3xl font-black text-brand-300">{{ $user->following()->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
                <div class="xl:col-span-8 space-y-8">
                    @if($pinnedMobs && $pinnedMobs->count() > 0)
                        <section>
                            <div class="flex items-center mb-4">
                                <svg class="w-6 h-6 mr-2 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                                <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight">Pinned Research</h2>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($pinnedMobs as $mob)
                                    <a href="{{ route('mobs.show', $mob) }}" class="group block h-full">
                                        <div class="glass-card h-full p-4 rounded-2xl border border-white/5 hover:border-brand-500/50 transition-all duration-300 relative overflow-hidden">
                                            @if($mob->image)
                                                <div class="absolute inset-0 opacity-10 group-hover:opacity-20 transition-opacity">
                                                    <img src="{{ asset('storage/' . $mob->image) }}" class="w-full h-full object-cover blur-sm">
                                                </div>
                                            @endif
                                            <div class="relative z-10 flex flex-col items-center text-center">
                                                @if($mob->image)
                                                    <img src="{{ asset('storage/' . $mob->image) }}" class="w-20 h-20 object-contain drop-shadow-2xl group-hover:scale-110 transition-transform duration-500 mb-3" style="image-rendering: pixelated;">
                                                @endif
                                                <h3 class="font-bold text-white group-hover:text-brand-400 transition-colors">{{ $mob->name }}</h3>
                                                <span class="px-2 py-0.5 mt-2 bg-gray-900 rounded text-[10px] font-black uppercase text-gray-400 border border-white/10">{{ $mob->category->name }}</span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    <section>
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight">Achievements</h2>
                            @php
                                $allAchievements = \App\Models\Achievement::all();
                                $userAchievements = $user->achievements->pluck('id')->toArray();
                            @endphp
                            <span class="text-[10px] text-gray-500 font-black uppercase tracking-[0.2em]">
                                {{ count($userAchievements) }}/{{ count($allAchievements) }} unlocked
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($allAchievements as $achievement)
                                @php
                                    $unlocked = in_array($achievement->id, $userAchievements);
                                @endphp
                                <div @class([
                                    'rounded-[1.5rem] border p-5 transition-all flex items-center gap-4',
                                    'glass-card border-brand-500/20 shadow-[0_0_20px_rgba(14,165,233,0.12)]' => $unlocked,
                                    'bg-white/5 border-white/5 opacity-60' => ! $unlocked,
                                ])>
                                    <div class="text-3xl">{{ $achievement->icon }}</div>
                                    <div class="flex-1">
                                        <p class="text-sm font-black text-white">{{ $achievement->name }}</p>
                                        <p class="mt-1 text-xs text-gray-400 leading-relaxed">{{ $achievement->description }}</p>
                                    </div>
                                    <span class="text-[10px] font-black uppercase tracking-widest shrink-0 {{ $unlocked ? 'text-brand-300' : 'text-gray-500' }}">
                                        {{ $unlocked ? 'Unlocked' : 'Locked' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section>
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight">Favorite Entities</h2>
                            <span class="text-[10px] text-gray-500 font-black uppercase tracking-[0.2em]">{{ $favorites->count() }} tracked</span>
                        </div>

                        @if($favorites->isEmpty())
                            <div class="glass-card rounded-[1.75rem] p-10 text-center border-white/5">
                                <p class="text-gray-400">This researcher has not bookmarked any entities yet.</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                @foreach($favorites->take(6) as $mob)
                                    <a href="{{ route('mobs.show', $mob) }}" class="glass-card rounded-[1.75rem] overflow-hidden border-white/5 group hover:border-brand-500/30 transition-all">
                                        <div class="aspect-[16/10] bg-gray-950 overflow-hidden">
                                            @if($mob->image)
                                                <img src="{{ Storage::url($mob->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="{{ $mob->name }}">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-700 font-black uppercase tracking-widest">No Image</div>
                                            @endif
                                        </div>
                                        <div class="p-5">
                                            <h3 class="text-lg font-black text-white group-hover:text-brand-300 transition-colors">{{ $mob->name }}</h3>
                                            <p class="mt-2 text-[11px] text-gray-500 uppercase font-black tracking-[0.2em]">
                                                {{ $mob->category->name }}{{ $mob->biomes->first() ? ' / ' . $mob->biomes->first()->name : '' }}
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </section>
                </div>

                <div class="xl:col-span-4 space-y-8">
                    <section class="glass-card rounded-[1.75rem] p-5 sm:p-6 border-white/5">
                        <h2 class="text-xl font-black text-white mb-4">Profile Stats</h2>
                        <div class="space-y-4 text-sm">
                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">Joined</span>
                                <span class="text-white font-bold">{{ $stats['joined_at']->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">Public visibility</span>
                                <span class="text-white font-bold">{{ $user->profile_is_public ? 'Enabled' : 'Private' }}</span>
                            </div>
                            <div class="flex justify-between gap-4">
                                <span class="text-gray-500">Public slug</span>
                                <span class="text-brand-300 font-bold break-all text-right">{{ $user->public_slug }}</span>
                            </div>
                        </div>
                    </section>

                    <section>
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-black text-white">Top Observations</h2>
                            <span class="text-[10px] text-gray-500 font-black uppercase tracking-[0.2em]">Upvotable</span>
                        </div>

                        <div class="space-y-4">
                            @forelse($comments->take(8) as $comment)
                                <div class="glass-card rounded-[1.5rem] p-4 border-white/5">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <a href="{{ route('mobs.show', $comment->mob) }}" class="text-sm font-black text-brand-300 hover:text-brand-200 transition-colors">
                                                {{ $comment->mob->name }}
                                            </a>
                                            <p class="mt-1 text-[10px] text-gray-500 font-black uppercase tracking-[0.2em]">{{ $comment->created_at->diffForHumans() }}</p>
                                        </div>
                                        <div class="px-2.5 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-[10px] font-black uppercase tracking-widest text-amber-300">
                                            {{ $comment->votes_count }} rep
                                        </div>
                                    </div>

                                    <p class="mt-3 text-sm text-gray-300 leading-relaxed">"{{ \Illuminate\Support\Str::limit($comment->body, 140) }}"</p>
                                </div>
                            @empty
                                <div class="glass-card rounded-[1.5rem] p-8 text-center border-white/5">
                                    <p class="text-gray-400">No field notes recorded yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
