<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-100 antialiased bg-[#020617] selection:bg-brand-500/30">
        <!-- Background Decorations -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
            <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] bg-brand-600/20 blur-[120px] rounded-full animate-pulse"></div>
            <div class="absolute -bottom-[10%] -right-[10%] w-[50%] h-[50%] bg-brand-400/10 blur-[120px] rounded-full" style="animation: float 8s ease-in-out infinite;"></div>
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="animate-float">
                <a href="/">
                    <div class="flex items-center space-x-3 mb-8">
                        <div class="w-14 h-14 bg-brand-600 rounded-2xl flex items-center justify-center shadow-[0_0_30px_rgba(2,132,199,0.4)]">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2-2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <span class="text-3xl font-black text-white tracking-tighter">MobWiki</span>
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-10 py-12 glass-card rounded-[2.5rem] relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-brand-500/10 blur-3xl -z-10"></div>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
