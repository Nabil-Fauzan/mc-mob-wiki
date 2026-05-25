<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            {{ __('Aether Protocol Security') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Manage and revoke active sessions across all devices.') }}
        </p>
    </header>

    @php
        $sessions = \Illuminate\Support\Facades\DB::table('sessions')
            ->where('user_id', Auth::id())
            ->orderBy('last_activity', 'desc')
            ->get();
    @endphp

    <div class="mt-6 space-y-4">
        @foreach($sessions as $session)
            <div class="p-4 bg-gray-900/50 border {{ $session->id === request()->session()->getId() ? 'border-brand-500/50' : 'border-white/5' }} rounded-2xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-black/40 flex items-center justify-center border border-white/5">
                        @if(str_contains(strtolower($session->user_agent), 'mobile') || str_contains(strtolower($session->user_agent), 'android') || str_contains(strtolower($session->user_agent), 'iphone'))
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        @else
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-200">
                            {{ $session->ip_address }}
                            @if($session->id === request()->session()->getId())
                                <span class="ml-2 px-2 py-0.5 bg-brand-500/20 text-brand-400 text-[10px] uppercase font-black rounded">Current Device</span>
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 truncate max-w-xs md:max-w-md" title="{{ $session->user_agent }}">{{ $session->user_agent }}</p>
                        <p class="text-[10px] text-gray-600 mt-1 uppercase tracking-widest font-bold">Last Active: {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}</p>
                    </div>
                </div>

                @if($session->id !== request()->session()->getId())
                    <form method="POST" action="{{ route('profile.sessions.destroy', $session->id) }}">
                        @csrf
                        @method('delete')
                        <button class="px-4 py-2 bg-red-950 hover:bg-red-900 text-red-500 text-xs font-black uppercase tracking-widest rounded-xl border border-red-500/20 transition-all">
                            Revoke
                        </button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
</section>
