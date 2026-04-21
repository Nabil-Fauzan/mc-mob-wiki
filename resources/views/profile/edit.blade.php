<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Cinematic Header -->
            <div class="mb-12 relative">
                <div class="absolute -left-4 top-1/2 -translate-y-1/2 w-1 h-12 bg-brand-500 rounded-full shadow-[0_0_15px_rgba(14,165,233,0.5)]"></div>
                <h1 class="text-4xl font-black text-white tracking-tight">Researcher <span class="text-brand-500 uppercase">Profile</span></h1>
                <p class="text-gray-500 mt-2 font-medium uppercase tracking-[0.2em] text-[10px]">Data Terminal & Identity Management</p>
            </div>

            <div class="space-y-8">
                {{-- Admin Gateway Card (if applicable) --}}
                @if(Auth::user()->is_admin)
                    <div class="glass-card p-1 rounded-[2.5rem] bg-gradient-to-r from-red-600/20 via-transparent to-transparent border border-red-500/20 relative overflow-hidden group">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_0%_50%,_var(--tw-gradient-stops))] from-red-500/5 via-transparent to-transparent"></div>
                        <div class="p-8 relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                            <div class="flex items-center space-x-6 text-center md:text-left">
                                <div class="w-16 h-16 bg-red-900/30 rounded-2xl flex items-center justify-center shadow-[0_0_20px_rgba(239,68,68,0.2)] group-hover:scale-110 transition-transform duration-500">
                                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-black text-white tracking-tight italic">SECURITY CLEARANCE: <span class="text-red-500">OMEGA</span></h2>
                                    <p class="text-[10px] text-red-400 font-bold uppercase tracking-[0.3em] mt-1">Master Administrator Authorized Access</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.dashboard') }}" class="px-8 py-3 bg-red-600 hover:bg-red-500 text-white font-black uppercase tracking-widest text-xs rounded-full shadow-[0_0_30px_rgba(220,38,38,0.3)] hover:shadow-[0_0_40px_rgba(220,38,38,0.5)] transition-all transform hover:-translate-y-1">
                                Enter Admin Gateway
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Profile Information --}}
                <div class="glass-card p-10 rounded-[3rem] border border-white/5 relative overflow-hidden">
                    <div class="max-w-2xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- Update Password --}}
                <div class="glass-card p-10 rounded-[3rem] border border-white/5 relative overflow-hidden">
                    <div class="max-w-2xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                {{-- Danger Zone (Delete Account) --}}
                <div class="glass-card p-10 rounded-[3rem] border border-red-500/10 bg-red-950/5 relative overflow-hidden">
                    <div class="max-w-2xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
