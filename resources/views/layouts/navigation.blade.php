<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-black/20 backdrop-blur-lg border-b border-white/10 shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="group">
                        <div class="flex items-center space-x-2">
                            <div class="w-10 h-10 bg-brand-600 rounded-lg flex items-center justify-center shadow-[0_0_15px_rgba(14,165,233,0.5)] group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2-2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-400">MobWiki</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('mobs.index')" :active="request()->routeIs('mobs.index')" class="text-gray-300 hover:text-white transition-colors duration-300">
                        {{ __('Registry') }}
                    </x-nav-link>
                    <x-nav-link :href="route('biomes.index')" :active="request()->routeIs('biomes.*')" class="text-gray-300 hover:text-white transition-colors duration-300">
                        {{ __('Explorer') }}
                    </x-nav-link>
                    <x-nav-link :href="route('stats.index')" :active="request()->routeIs('stats.*')" class="text-gray-300 hover:text-white transition-colors duration-300">
                        {{ __('Global Intel') }}
                    </x-nav-link>
                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-300 hover:text-white transition-colors duration-300">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Search Hint (Desktop) -->
            <div class="hidden lg:flex items-center flex-1 max-w-xs mx-8">
                <button @click="paletteOpen = true" class="w-full flex items-center justify-between px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-gray-500 hover:bg-white/10 hover:border-brand-500/30 transition-all group">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 group-hover:text-brand-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        <span class="text-xs font-bold uppercase tracking-widest">Search...</span>
                    </div>
                    <span class="text-[9px] font-black bg-white/10 px-1.5 py-0.5 rounded border border-white/5 group-hover:border-brand-500/30 transition-colors">CTRL + K</span>
                </button>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-4 py-2 bg-white/5 border border-white/10 text-sm leading-4 font-medium rounded-full text-white hover:bg-white/10 focus:outline-none transition ease-in-out duration-150 backdrop-blur-sm">
                                    <img class="h-6 w-6 rounded-full mr-2 border border-brand-500/30" src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=0EA5E9&background=E0F2FE' }}" alt="{{ Auth::user()->name }}" />
                                    <div>{{ Auth::user()->name }}</div>

                                    <div class="ms-2">
                                        <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-3 bg-white/5 border-b border-white/10 mb-2">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-accent-500 p-0.5">
                                            <div class="w-full h-full bg-gray-900 rounded-[0.6rem] overflow-hidden flex items-center justify-center text-xs font-black text-white">
                                                <img class="w-full h-full object-cover" src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=0EA5E9&background=E0F2FE' }}" alt="{{ Auth::user()->name }}" />
                                            </div>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[10px] font-black text-brand-500 uppercase tracking-widest leading-none mb-1">Active Agent</p>
                                            <p class="text-sm font-black text-white truncate">{{ Auth::user()->name }}</p>
                                        </div>
                                    </div>
                                    @php
                                        $fCount = Auth::user()->favorite_mobs()->count();
                                        $cCount = Auth::user()->comments()->count();
                                        $uXp = ($fCount * 125) + ($cCount * 350);
                                        $uLvl = floor(sqrt($uXp / 100)) + 1;
                                        $uNextLvlXp = pow($uLvl, 2) * 100;
                                        $uProgress = min(100, round(($uXp / $uNextLvlXp) * 100));
                                    @endphp
                                    <div class="space-y-1.5">
                                        <div class="flex justify-between text-[8px] font-black uppercase tracking-widest text-gray-500">
                                            <span>Lvl {{ $uLvl }}</span>
                                            <span>{{ $uProgress }}%</span>
                                        </div>
                                        <div class="h-1 bg-white/10 rounded-full overflow-hidden">
                                            <div class="h-full bg-brand-500" style="width: {{ $uProgress }}%"></div>
                                        </div>
                                    </div>
                                </div>

                                <x-dropdown-link :href="route('profile.edit')" class="hover:bg-brand-600/20 group flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500 group-hover:text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                @if(Auth::user()->is_admin)
                                    <x-dropdown-link :href="route('admin.dashboard')" class="text-red-400 hover:bg-red-600/20 group flex items-center font-black uppercase tracking-widest text-[10px]">
                                        <svg class="w-4 h-4 mr-2 text-red-500 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        {{ __('Master Control') }}
                                    </x-dropdown-link>
                                @endif

                                <div class="border-t border-white/5 my-1"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();"
                                            class="hover:bg-red-600/20 group flex items-center text-gray-400 hover:text-red-400">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @else
                    <div class="flex space-x-4 items-center">
                        <a href="{{ route('login') }}" class="text-sm text-gray-300 hover:text-white transition-colors">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center px-5 py-2.5 bg-brand-600 text-white text-sm font-bold rounded-full hover:bg-brand-700 shadow-lg shadow-brand-600/20 transition-all transform hover:-translate-y-0.5">
                                Join Now
                            </a>
                        @endif
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/10 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-black/40 backdrop-blur-xl border-t border-white/10">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('mobs.index')" :active="request()->routeIs('mobs.index')" class="text-gray-300">
                {{ __('Registry') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('biomes.index')" :active="request()->routeIs('biomes.*')" class="text-gray-300">
                {{ __('Explorer') }}
            </x-responsive-nav-link>
            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-300">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-white/5">
            @auth
                <div class="px-4 flex items-center">
                    <img class="h-10 w-10 rounded-full mr-3 border-2 border-brand-500/50" src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=0EA5E9&background=E0F2FE' }}" alt="{{ Auth::user()->name }}" />
                    <div>
                        <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')" class="text-gray-300">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    @if(Auth::user()->is_admin)
                        <x-responsive-nav-link :href="route('admin.dashboard')" class="text-red-400 font-black uppercase tracking-widest text-[10px]">
                            {{ __('Master Control') }}
                        </x-responsive-nav-link>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();"
                                class="text-red-400">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1 p-4">
                    <a href="{{ route('login') }}" class="block w-full text-center py-3 text-gray-300 hover:text-white border border-white/10 rounded-xl mb-2">Log in</a>
                    <a href="{{ route('register') }}" class="block w-full text-center py-3 bg-brand-600 text-white font-bold rounded-xl shadow-lg">Join Now</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
