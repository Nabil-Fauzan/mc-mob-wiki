<x-app-layout>
    <x-slot name="header">
        <!-- Swiper CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <style>
            .hero-swiper {
                width: 100%;
                height: 80vh;
                border-radius: 2rem;
                overflow: hidden;
            }
            .swiper-slide {
                position: relative;
            }
            .swiper-slide img {
                width: 100%;
                height: 100%;
                object-cover: cover;
            }
            .slide-content {
                position: absolute;
                bottom: 10%;
                left: 5%;
                z-index: 10;
                max-width: 600px;
            }
        </style>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <!-- Hero Section with Swiper -->
        <div class="max-w-7xl mx-auto mb-16">
            <div class="swiper hero-swiper shadow-2xl shadow-indigo-500/10">
                <div class="swiper-wrapper">
                    <!-- Slide 1 -->
                    <div class="swiper-slide group">
                        <img src="{{ asset('images/hero1.png') }}" alt="Explore Minecraft" class="brightness-50 group-hover:scale-105 transition-transform duration-[10s] ease-linear">
                        <div class="slide-content fade-in-up">
                            <span class="inline-block px-4 py-1.5 bg-green-500/20 text-green-400 rounded-full text-xs font-bold uppercase tracking-widest mb-4 backdrop-blur-md border border-green-500/30">New Content</span>
                            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 leading-tight">Master the <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-emerald-500">Overworld</span></h1>
                            <p class="text-xl text-gray-300 mb-8 leading-relaxed">Discover every creature, from the friendliest farm animals to the most dangerous night-stalkers.</p>
                            <div class="flex space-x-4">
                                <a href="{{ route('mobs.index') }}" class="btn-primary-mc">Explore Wiki</a>
                                @guest
                                    <a href="{{ route('register') }}" class="px-8 py-3 bg-white/10 backdrop-blur-md text-white font-bold rounded-lg border border-white/20 hover:bg-white/20 transition-all">Join Contributors</a>
                                @endguest
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2 -->
                    <div class="swiper-slide group">
                        <img src="{{ asset('images/hero2.png') }}" alt="Face the Darkness" class="brightness-50 group-hover:scale-105 transition-transform duration-[10s] ease-linear">
                        <div class="slide-content">
                            <span class="inline-block px-4 py-1.5 bg-purple-500/20 text-purple-400 rounded-full text-xs font-bold uppercase tracking-widest mb-4 backdrop-blur-md border border-purple-500/30">Dangerous Mobs</span>
                            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 leading-tight">Venture into <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-500">The End</span></h1>
                            <p class="text-xl text-gray-300 mb-8 leading-relaxed">Learn the secrets of the Endermen and prepare your ultimate equipment for the final boss.</p>
                            <a href="{{ route('mobs.index', ['category' => '1']) }}" class="btn-primary-mc">View Hostile Mobs</a>
                        </div>
                    </div>

                    <!-- Slide 3 -->
                    <div class="swiper-slide group">
                        <img src="{{ asset('images/hero3.png') }}" alt="Cozy Adventures" class="brightness-50 group-hover:scale-105 transition-transform duration-[10s] ease-linear">
                        <div class="slide-content">
                            <span class="inline-block px-4 py-1.5 bg-orange-500/20 text-orange-400 rounded-full text-xs font-bold uppercase tracking-widest mb-4 backdrop-blur-md border border-orange-500/30">Peaceful Life</span>
                            <h1 class="text-5xl md:text-7xl font-black text-white mb-6 leading-tight">Cozy <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-yellow-500">Villages</span></h1>
                            <p class="text-xl text-gray-300 mb-8 leading-relaxed">Meet the local traders and find the best biomes to build your dream homestead.</p>
                            <a href="{{ route('mobs.index', ['category' => '2']) }}" class="btn-primary-mc">View Passive Mobs</a>
                        </div>
                    </div>
                </div>
                <!-- Add Pagination/Navigation -->
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next text-white/50 hover:text-white transition-colors duration-300 mr-4"></div>
                <div class="swiper-button-prev text-white/50 hover:text-white transition-colors duration-300 ml-4"></div>
            </div>
        </div>

        <!-- Featured Categories -->
        <div class="max-w-7xl mx-auto mb-24">
            <h2 class="text-3xl font-bold text-white mb-12 text-center">Classifications <span class="text-indigo-500 font-mono">_</span></h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Hostile -->
                <div class="glass-card p-8 rounded-3xl group hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-600/10 blur-3xl rounded-full group-hover:bg-red-600/20 transition-all"></div>
                    <div class="w-16 h-16 bg-red-600/20 border border-red-500/30 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Hostile</h3>
                    <p class="text-gray-400 mb-6 leading-relaxed">Dangerous creatures that attack on sight. Learn their attack patterns to survive the night.</p>
                    <a href="{{ route('mobs.index') }}?category=1" class="text-red-400 font-bold hover:text-red-300 inline-flex items-center">
                        Explore Danger 
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </a>
                </div>

                <!-- Neutral -->
                <div class="glass-card p-8 rounded-3xl group hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-yellow-600/10 blur-3xl rounded-full group-hover:bg-yellow-600/20 transition-all"></div>
                    <div class="w-16 h-16 bg-yellow-600/20 border border-yellow-500/30 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Neutral</h3>
                    <p class="text-gray-400 mb-6 leading-relaxed">Safe until provoked. Discover what triggers these misunderstood legendary creatures.</p>
                    <a href="{{ route('mobs.index') }}?category=3" class="text-yellow-400 font-bold hover:text-yellow-300 inline-flex items-center">
                        Handle with Care
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </a>
                </div>

                <!-- Passive -->
                <div class="glass-card p-8 rounded-3xl group hover:-translate-y-2 transition-all duration-500 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-green-600/10 blur-3xl rounded-full group-hover:bg-green-600/20 transition-all"></div>
                    <div class="w-16 h-16 bg-green-600/20 border border-green-500/30 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Passive</h3>
                    <p class="text-gray-400 mb-6 leading-relaxed">Harmless animals and helpful allies. Find out how to farm and breed them effectively.</p>
                    <a href="{{ route('mobs.index') }}?category=2" class="text-green-400 font-bold hover:text-green-300 inline-flex items-center">
                        Meet Friends
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Wiki Stats -->
        <div class="max-w-4xl mx-auto glass-card rounded-[3rem] p-12 text-center mb-24 border-indigo-500/20">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <div class="text-4xl font-black text-white mb-2 underline decoration-indigo-500 decoration-4">{{ $stats['mobs'] }}</div>
                    <div class="text-gray-400 text-sm uppercase tracking-tighter">Total Mobs</div>
                </div>
                <div>
                    <div class="text-4xl font-black text-white mb-2 underline decoration-green-500 decoration-4">{{ $stats['biomes'] }}</div>
                    <div class="text-gray-400 text-sm uppercase tracking-tighter">Biomes</div>
                </div>
                <div>
                    <div class="text-4xl font-black text-white mb-2 underline decoration-purple-500 decoration-4">{{ $stats['dimensions'] }}</div>
                    <div class="text-gray-400 text-sm uppercase tracking-tighter">Dimensions</div>
                </div>
                <div>
                    <div class="text-4xl font-black text-white mb-2 underline decoration-yellow-500 decoration-4">∞</div>
                    <div class="text-gray-400 text-sm uppercase tracking-tighter">Possibilities</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const swiper = new Swiper('.hero-swiper', {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
            });
        });
    </script>
</x-app-layout>
