<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-black text-white flex items-center gap-4">
                        <a href="{{ route('mobs.show', $mob) }}" class="text-gray-500 hover:text-white transition-colors">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                        Revision History
                    </h1>
                    <p class="text-gray-400 mt-2">Tracking changes for <span class="text-brand-400 font-bold">{{ $mob->name }}</span></p>
                </div>
            </div>

            @if($revisions->isEmpty())
                <div class="glass-card rounded-[2rem] p-12 text-center border-dashed border-white/10">
                    <p class="text-gray-500 text-lg">No modifications have been recorded for this entity.</p>
                </div>
            @else
                <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-white/10 before:to-transparent">
                    @foreach($revisions as $revision)
                        <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                            <!-- Icon -->
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white/10 bg-gray-900 shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shadow-sm relative z-10 text-brand-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>

                            <!-- Card -->
                            <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] glass-card p-5 sm:p-6 rounded-2xl border-white/5 hover:border-brand-500/30 transition-all">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-[10px] font-black text-brand-500 uppercase tracking-widest bg-brand-500/10 px-2.5 py-1 rounded-full">
                                        {{ str_replace('_', ' ', $revision->field) }}
                                    </span>
                                    <span class="text-[10px] text-gray-500 font-mono">{{ $revision->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                
                                <p class="text-xs text-gray-400 mb-4">
                                    Modified by <span class="text-white font-bold">{{ $revision->user ? $revision->user->name : 'System/Admin' }}</span>
                                </p>

                                @if($revision->field === 'image')
                                    <div class="grid grid-cols-2 gap-4 mt-2">
                                        <div class="bg-black/30 rounded-xl p-2 border border-red-500/20">
                                            <p class="text-[9px] text-red-400 uppercase tracking-widest font-black mb-2 text-center">Previous</p>
                                            @if($revision->old_value)
                                                <img src="{{ asset('storage/' . $revision->old_value) }}" class="w-full h-24 object-contain rounded-lg">
                                            @else
                                                <div class="w-full h-24 flex items-center justify-center text-gray-600 text-xs">No Image</div>
                                            @endif
                                        </div>
                                        <div class="bg-black/30 rounded-xl p-2 border border-green-500/20">
                                            <p class="text-[9px] text-green-400 uppercase tracking-widest font-black mb-2 text-center">New</p>
                                            @if($revision->new_value)
                                                <img src="{{ asset('storage/' . $revision->new_value) }}" class="w-full h-24 object-contain rounded-lg">
                                            @else
                                                <div class="w-full h-24 flex items-center justify-center text-gray-600 text-xs">No Image</div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="space-y-3">
                                        <div class="bg-red-500/5 border border-red-500/10 rounded-xl p-3">
                                            <p class="text-[9px] text-red-400 uppercase tracking-widest font-black mb-1">- Previous Value</p>
                                            <p class="text-sm text-gray-300 whitespace-pre-wrap font-mono text-[11px]">{{ $revision->old_value ?? '(empty)' }}</p>
                                        </div>
                                        <div class="bg-green-500/5 border border-green-500/10 rounded-xl p-3">
                                            <p class="text-[9px] text-green-400 uppercase tracking-widest font-black mb-1">+ New Value</p>
                                            <p class="text-sm text-gray-300 whitespace-pre-wrap font-mono text-[11px]">{{ $revision->new_value ?? '(empty)' }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if(auth()->user() && auth()->user()->is_admin)
                                    <form action="{{ route('mobs.revert', [$mob, $revision]) }}" method="POST" class="mt-4 pt-4 border-t border-white/5 text-right">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Are you sure you want to revert to this exact old value?')" class="text-xs font-black text-rose-500 hover:text-rose-400 uppercase tracking-widest transition-colors">
                                            Revert Edit
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
