<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Glow FM 99.1 - Your Voice, Your Music' }}</title>
    <meta name="google-adsense-account" content="ca-pub-3970534274644088">
    
    <meta name="description"
        content="{{ $meta_description ?? 'Glow FM 99.1 - The heartbeat of the city. Listen to the best music, engaging shows, and stay connected with your community.' }}">
    <meta property="og:title" content="{{ $meta_title ?? ($title ?? 'Glow FM 99.1') }}">
    <meta property="og:description" content="{{ $meta_description ?? 'Glow FM 99.1 - The heartbeat of the city.' }}">
    <meta property="og:image" content="{{ $meta_image ?? '' }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:type" content="{{ $meta_type ?? 'website' }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @livewireStyles
</head>

<body class="bg-white font-sans antialiased" x-data="{ 
    mobileMenuOpen: false, 
    searchOpen: false,
    scrolled: false,
    playerOpen: true,
    audioPlaying: false,
    consentBannerOpen: true,
    consentChoice: null,
    toggleLive() {
        const audio = this.$refs.liveAudio;
        if (!audio || !audio.src) return;
        if (audio.paused) {
            audio.play();
            this.audioPlaying = true;
        } else {
            audio.pause();
            this.audioPlaying = false;
        }
    },
    startLive() {
        const audio = this.$refs.liveAudio;
        if (!audio || !audio.src) return;
        audio.play();
        this.audioPlaying = true;
        this.playerOpen = true;
    },
    setConsent(choice) {
        this.consentChoice = choice;
        this.consentBannerOpen = false;
        try {
            localStorage.setItem('cmp_consent', JSON.stringify({
                choice,
                ts: Date.now(),
            }));
        } catch (e) {}
    }
}" x-init="
    try {
        const storedConsent = localStorage.getItem('cmp_consent');
        if (storedConsent) {
            const parsedConsent = JSON.parse(storedConsent);
            consentChoice = parsedConsent.choice || null;
            consentBannerOpen = false;
        }
    } catch (e) {}
    window.addEventListener('scroll', () => {
        scrolled = window.pageYOffset > 20
    })
">
    @php
        $stationSettings = \App\Models\Setting::get('station', []);
        $stationName = data_get($stationSettings, 'name', 'Glow FM');
        $stationFrequency = data_get($stationSettings, 'frequency', '99.1 MHz');
        $stationTagline = data_get($stationSettings, 'tagline', 'Your Voice, Your Music');
        $stationPhone = data_get($stationSettings, 'phone', '+1 (234) 567-890');
        $stationEmail = data_get($stationSettings, 'email', 'info@glowfm.com');
        $stationAddress = data_get($stationSettings, 'address', '123 Radio Street, Broadcasting City, BC 12345');
        $stationStreamUrl = data_get($stationSettings, 'stream_url', 'https://stream-176.zeno.fm/mwam2yirv1pvv');
        $stationSocials = data_get($stationSettings, 'socials', []);
        $streamSettings = \App\Models\Setting::get('stream', []);
        $streamIsLive = data_get($streamSettings, 'is_live', true);
        $streamStatusMessage = data_get($streamSettings, 'status_message', 'Broadcasting live now');
        $streamTitle = data_get($streamSettings, 'now_playing_title', 'Blinding Lights');
        $streamArtist = data_get($streamSettings, 'now_playing_artist', 'The Weeknd');
        $streamShowName = data_get($streamSettings, 'show_name', 'Morning Vibes');
    @endphp

    <!-- Fixed Header -->
    <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
        :class="scrolled ? 'bg-white shadow-lg' : 'bg-white/95 backdrop-blur-sm'">
        <!-- Top Bar -->
        <div class="bg-slate-600 text-white">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between h-10 text-sm">
                    <div class="flex items-center space-x-6">
                        <a href="tel:{{ $stationPhone }}"
                            class="flex items-center space-x-2 hover:text-emerald-100 transition-colors">
                            <i class="fas fa-phone text-xs"></i>
                            <span class="hidden md:inline">{{ $stationPhone }}</span>
                        </a>
                        <a href="mailto:{{ $stationEmail }}"
                            class="flex items-center space-x-2 hover:text-emerald-100 transition-colors">
                            <i class="fas fa-envelope text-xs"></i>
                            <span class="hidden md:inline">{{ $stationEmail }}</span>
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="hidden sm:flex items-center space-x-2 text-xs">
                            <span class="relative flex h-2 w-2">
                                <span class="absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"
                                    :class="audioPlaying ? 'animate-ping' : ''"></span>
                                <span class="relative inline-flex h-2 w-2 rounded-full"
                                    :class="audioPlaying ? 'bg-emerald-400' : 'bg-red-500'"></span>
                            </span>
                            <span class="font-medium" x-text="audioPlaying ? 'LIVE STREAMING' : '{{ $streamIsLive ? 'LIVE NOW' : 'OFFLINE' }}'"></span>
                            <span class="text-emerald-200">•</span>
                            <span class="font-medium">{{ $streamShowName }}</span>
                        </span>
                        <div class="flex items-center space-x-3">
                            <a href="{{ data_get($stationSocials, 'facebook', '#') }}" class="hover:text-emerald-100 transition-colors" aria-label="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="{{ data_get($stationSocials, 'twitter', '#') }}" class="hover:text-emerald-100 transition-colors" aria-label="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="{{ data_get($stationSocials, 'instagram', '#') }}" class="hover:text-emerald-100 transition-colors" aria-label="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="{{ data_get($stationSocials, 'youtube', '#') }}" class="hover:text-emerald-100 transition-colors" aria-label="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Navigation -->
        <div class="container mx-auto px-4">
            <nav class="flex items-center justify-between h-20">

                <!-- Logo -->
                <a href="/" class="flex items-center space-x-3 group">
                    <div class="relative">
                        <div
                            class="w-12 h-12 bg-emerald-600 rounded-xl shadow-lg flex items-center justify-center transform group-hover:scale-105 transition-transform duration-300">
                            <i class="fas fa-radio text-white text-2xl"></i>
                        </div>
                        <div
                            class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full border-2 border-white animate-pulse">
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 leading-none">{{ $stationName }}</h1>
                        <p class="text-xs font-semibold text-emerald-600">{{ $stationFrequency }}</p>
                    </div>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center space-x-1">
                    <a href="/"
                        class="px-4 py-2 text-gray-700 font-medium hover:text-emerald-600 transition-colors duration-200 {{ request()->is('/') ? 'text-emerald-600' : '' }}">
                        Home
                    </a>
                    <a href="/about"
                        class="px-4 py-2 text-gray-700 font-medium hover:text-emerald-600 transition-colors duration-200 {{ request()->is('about') ? 'text-emerald-600' : '' }}">
                        About
                    </a>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.away="open = false"
                            class="px-4 py-2 text-gray-700 font-medium hover:text-emerald-600 transition-colors duration-200 flex items-center {{ request()->is('shows*') || request()->is('schedule') || request()->is('oaps*') ? 'text-emerald-600' : '' }}">
                            Program
                            <i class="fas fa-chevron-down text-xs ml-2"></i>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2"
                            class="absolute left-0 mt-2 w-52 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                            <a href="/shows"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                Shows
                            </a>
                            <a href="/schedule"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                Schedule
                            </a>
                            <a href="/oaps"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                OAPs
                            </a>
                        </div>
                    </div>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.away="open = false"
                            class="px-4 py-2 text-gray-700 font-medium hover:text-emerald-600 transition-colors duration-200 flex items-center {{ request()->is('podcasts*') || request()->is('news*') || request()->is('blog*') ? 'text-emerald-600' : '' }}">
                            Media
                            <i class="fas fa-chevron-down text-xs ml-2"></i>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2"
                            class="absolute left-0 mt-2 w-52 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                            <a href="/podcasts"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                Podcasts
                            </a>
                            <a href="/news"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                News
                            </a>
                            <a href="/blog"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                Blog
                            </a>
                        </div>
                    </div>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.away="open = false"
                            class="px-4 py-2 text-gray-700 font-medium hover:text-emerald-600 transition-colors duration-200 flex items-center {{ request()->is('team') || request()->is('events*') || request()->is('contact') ? 'text-emerald-600' : '' }}">
                            Community
                            <i class="fas fa-chevron-down text-xs ml-2"></i>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2"
                            class="absolute left-0 mt-2 w-52 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                            <a href="/team"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                Team
                            </a>
                            <a href="/events"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                Events
                            </a>
                            <a href="/contact"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                Contact
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Search Button -->
                    <button @click="searchOpen = !searchOpen"
                        class="hidden md:flex items-center justify-center w-10 h-10 text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all duration-200">
                        <i class="fas fa-search"></i>
                    </button>

                    <!-- Authentication Links -->
                    @auth
                        <!-- User Dropdown -->
                        <div x-data="{ userMenuOpen: false }" class="relative">
                            <button @click="userMenuOpen = !userMenuOpen"
                                class="flex items-center space-x-2 px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-full transition-all duration-200">
                                <div class="w-8 h-8 bg-emerald-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <span class="font-medium hidden md:inline">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="userMenuOpen" @click.away="userMenuOpen = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">

                                @if(auth()->user()->is_admin)
                                    <a href="/dashboard"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                    </a>
                                    <div class="border-t my-1"></div>
                                @endif

                                <a href="/profile"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                    <i class="fas fa-user-circle mr-2"></i>My Profile
                                </a>
                                <a href="/settings"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                    <i class="fas fa-cog mr-2"></i>Settings
                                </a>

                                <!-- Logout Form -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 mt-1">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Login/Register Links -->
                        <div class="hidden md:flex items-center space-x-2">
                            <a href="{{ route('login') }}"
                                class="px-4 py-2 text-emerald-600 hover:text-emerald-700 font-medium transition-colors">
                                Login
                            </a>
                            <a href="{{ route('register') }}"
                                class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-full transition-colors">
                                Sign Up
                            </a>
                        </div>
                    @endauth

                    <!-- Listen Live Button -->
                    <button type="button" @click="startLive"
                        class="hidden md:flex items-center space-x-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-play-circle text-xl"></i>
                        <span>Listen Live</span>
                    </button>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="lg:hidden flex items-center justify-center w-10 h-10 text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all duration-200">
                        <i class="fas" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
                    </button>
                </div>
            </nav>
        </div>

        <!-- Search Bar (Dropdown) -->
        <div x-show="searchOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4" class="border-t border-gray-200 bg-white shadow-xl"
            @click.away="searchOpen = false">
            <div class="container mx-auto px-4 py-6">
                <div class="max-w-3xl mx-auto relative">
                    <input type="text" placeholder="Search news, shows, events..."
                        class="w-full px-6 py-4 pr-12 text-lg border-2 border-gray-300 rounded-2xl focus:outline-none focus:border-emerald-500 transition-colors">
                    <button
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-emerald-600 hover:text-emerald-700">
                        <i class="fas fa-search text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="lg:hidden border-t border-gray-200 bg-white shadow-xl">
            <div class="container mx-auto px-4 py-6 space-y-2">
                <a href="/"
                    class="block px-4 py-3 text-gray-700 font-medium hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors">
                    Home
                </a>
                <a href="/about"
                    class="block px-4 py-3 text-gray-700 font-medium hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors">
                    About
                </a>
                <div x-data="{ open: false }" class="rounded-lg border border-gray-200">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3 text-gray-700 font-medium hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors">
                        <span>Program</span>
                        <i class="fas text-xs" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-transition class="pb-2">
                        <a href="/shows"
                            class="block px-6 py-2 text-sm text-gray-600 hover:text-emerald-600">
                            Shows
                        </a>
                        <a href="/schedule"
                            class="block px-6 py-2 text-sm text-gray-600 hover:text-emerald-600">
                            Schedule
                        </a>
                        <a href="/oaps"
                            class="block px-6 py-2 text-sm text-gray-600 hover:text-emerald-600">
                            OAPs
                        </a>
                    </div>
                </div>
                <div x-data="{ open: false }" class="rounded-lg border border-gray-200">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3 text-gray-700 font-medium hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors">
                        <span>Media</span>
                        <i class="fas text-xs" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-transition class="pb-2">
                        <a href="/podcasts"
                            class="block px-6 py-2 text-sm text-gray-600 hover:text-emerald-600">
                            Podcasts
                        </a>
                        <a href="/news"
                            class="block px-6 py-2 text-sm text-gray-600 hover:text-emerald-600">
                            News
                        </a>
                        <a href="/blog"
                            class="block px-6 py-2 text-sm text-gray-600 hover:text-emerald-600">
                            Blog
                        </a>
                    </div>
                </div>
                <div x-data="{ open: false }" class="rounded-lg border border-gray-200">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-3 text-gray-700 font-medium hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors">
                        <span>Community</span>
                        <i class="fas text-xs" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-transition class="pb-2">
                        <a href="/team"
                            class="block px-6 py-2 text-sm text-gray-600 hover:text-emerald-600">
                            Team
                        </a>
                        <a href="/events"
                            class="block px-6 py-2 text-sm text-gray-600 hover:text-emerald-600">
                            Events
                        </a>
                        <a href="/contact"
                            class="block px-6 py-2 text-sm text-gray-600 hover:text-emerald-600">
                            Contact
                        </a>
                    </div>
                </div>
                <div class="pt-4">
                    <button @click="searchOpen = !searchOpen"
                        class="w-full px-4 py-3 text-gray-700 font-medium hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors text-left">
                        <i class="fas fa-search mr-2"></i> Search
                    </button>
                </div>
                <button type="button" @click="startLive"
                    class="block mt-4 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-full text-center shadow-lg">
                    <i class="fas fa-play-circle mr-2"></i> Listen Live
                </button>
            </div>
        </div>
    </header>

    @if($streamIsLive)
        <div class="bg-emerald-600 text-white">
            <div class="container mx-auto px-4 py-2">
                <div class="flex items-center space-x-4 text-sm">
                    <span class="flex items-center space-x-2 font-semibold">
                        <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                        <span>NOW PLAYING</span>
                    </span>
                    <marquee class="flex-1">
                        {{ $streamTitle }} — {{ $streamArtist }} • {{ $streamShowName }} • {{ $streamStatusMessage }}
                    </marquee>
                    <button type="button" @click="startLive"
                        class="hidden sm:inline-flex items-center space-x-2 px-3 py-1 bg-white/20 hover:bg-white/30 rounded-full text-xs font-semibold">
                        <i class="fas fa-play-circle"></i>
                        <span>Listen Live</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Spacer for Fixed Header -->
    <div class="h-28"></div>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-16 pb-8 mt-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">

                <!-- About Section -->
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-12 h-12 bg-emerald-600 rounded-xl shadow-lg flex items-center justify-center">
                            <i class="fas fa-radio text-white text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">{{ $stationName }}</h3>
                            <p class="text-sm text-emerald-400">{{ $stationFrequency }}</p>
                        </div>
                    </div>
                    <p class="text-gray-400 mb-6 leading-relaxed">
                        {{ $stationTagline }}. Broadcasting the heartbeat of the city of Akure with the best music, engaging
                        shows, and vibrant community connection.
                    </p>
                    <div class="flex items-center space-x-3">
                        <a href="{{ data_get($stationSocials, 'facebook', '#') }}"
                            class="w-10 h-10 bg-gray-800 hover:bg-emerald-600 rounded-lg flex items-center justify-center transition-colors duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="{{ data_get($stationSocials, 'twitter', '#') }}"
                            class="w-10 h-10 bg-gray-800 hover:bg-emerald-600 rounded-lg flex items-center justify-center transition-colors duration-300">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="{{ data_get($stationSocials, 'instagram', '#') }}"
                            class="w-10 h-10 bg-gray-800 hover:bg-emerald-600 rounded-lg flex items-center justify-center transition-colors duration-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="{{ data_get($stationSocials, 'youtube', '#') }}"
                            class="w-10 h-10 bg-gray-800 hover:bg-emerald-600 rounded-lg flex items-center justify-center transition-colors duration-300">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-bold mb-6">Quick Links</h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="/about"
                                class="text-gray-400 hover:text-emerald-400 transition-colors duration-200 flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                About Us
                            </a>
                        </li>
                        <li>
                            <a href="/shows"
                                class="text-gray-400 hover:text-emerald-400 transition-colors duration-200 flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Our Shows
                            </a>
                        </li>
                        <li>
                            <a href="/schedule"
                                class="text-gray-400 hover:text-emerald-400 transition-colors duration-200 flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Schedule
                            </a>
                        </li>
                        <li>
                            <a href="/podcasts"
                                class="text-gray-400 hover:text-emerald-400 transition-colors duration-200 flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Our Podcasts
                            </a>
                        </li>
                        <li>
                            <a href="/news"
                                class="text-gray-400 hover:text-emerald-400 transition-colors duration-200 flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                News & Blog
                            </a>
                        </li>
                        <li>
                            <a href="/contact"
                                class="text-gray-400 hover:text-emerald-400 transition-colors duration-200 flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Contact Us
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-lg font-bold mb-6">Contact Info</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start space-x-3">
                            <i class="fas fa-map-marker-alt text-emerald-400 mt-1"></i>
                            <span class="text-gray-400">
                                {{ $stationAddress }}
                            </span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-phone text-emerald-400"></i>
                            <a href="tel:{{ $stationPhone }}" class="text-gray-400 hover:text-emerald-400 transition-colors">
                                {{ $stationPhone }}
                            </a>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-emerald-400"></i>
                            <a href="mailto:{{ $stationEmail }}"
                                class="text-gray-400 hover:text-emerald-400 transition-colors">
                                {{ $stationEmail }}
                            </a>
                        </li>
                        <li class="flex items-start space-x-3">
                            <i class="fas fa-clock text-emerald-400 mt-1"></i>
                            <span class="text-gray-400">
                                24/7 Broadcasting<br>
                                Office: Mon-Fri, 9AM - 6PM
                            </span>
                        </li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div>
                    <h3 class="text-lg font-bold mb-6">Newsletter</h3>
                    <p class="text-gray-400 mb-4">
                        Subscribe to get updates on shows, events, and exclusive content!
                    </p>
                    <form method="POST" action="{{ route('newsletter.subscribe') }}" class="space-y-3">
                        @csrf
                        <input type="hidden" name="source" value="footer">
                        <input type="email" name="email" required placeholder="Your email address"
                            class="w-full px-4 py-3 bg-gray-800 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                        <button type="submit"
                            class="w-full px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors duration-300">
                            Subscribe
                        </button>
                    </form>

                    <!-- Recent Shows -->
                    <div class="mt-6 pt-6 border-t border-gray-800">
                        <h4 class="text-sm font-semibold mb-3 text-gray-300">Recent Shows</h4>
                        <div class="space-y-2">
                            <a href="#"
                                class="flex items-center space-x-2 text-gray-400 hover:text-emerald-400 transition-colors text-sm">
                                <i class="fas fa-microphone text-xs"></i>
                                <span>Morning Vibes</span>
                            </a>
                            <a href="#"
                                class="flex items-center space-x-2 text-gray-400 hover:text-emerald-400 transition-colors text-sm">
                                <i class="fas fa-microphone text-xs"></i>
                                <span>Afternoon Drive</span>
                            </a>
                            <a href="#"
                                class="flex items-center space-x-2 text-gray-400 hover:text-emerald-400 transition-colors text-sm">
                                <i class="fas fa-microphone text-xs"></i>
                                <span>Night Grooves</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="pt-8 border-t border-gray-800">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
                    <p class="text-gray-400 text-sm">
                        &copy; {{ date('Y') }} {{ $stationName }} {{ $stationFrequency }}. All rights reserved.
                    </p>
                    <div class="flex items-center space-x-6 text-sm">
                        <a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Privacy Policy</a>
                        <a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Terms of Service</a>
                        <a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating Listen Live Player -->
    <div x-show="playerOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-full" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-full" class="fixed bottom-6 right-6 z-50">
        <div class="bg-gray-900 text-white rounded-2xl shadow-2xl overflow-hidden max-w-sm">
            <!-- Player Header -->
            <div class="bg-emerald-600 px-4 py-2 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="absolute inline-flex h-full w-full rounded-full bg-white opacity-60"
                            :class="audioPlaying ? 'animate-ping' : ''"></span>
                        <span class="relative inline-flex h-2.5 w-2.5 rounded-full"
                            :class="audioPlaying ? 'bg-lime-300' : 'bg-white'"></span>
                    </span>
                    <span class="text-sm font-semibold" x-text="audioPlaying ? 'STREAMING LIVE' : 'LIVE NOW'"></span>
                </div>
                <button @click="playerOpen = false" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Player Content -->
            <div class="p-4">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="flex-shrink-0">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-lg shadow-lg flex items-center justify-center">
                            <i class="fas fa-music text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-sm truncate">{{ $streamTitle }}</h4>
                        <p class="text-xs text-gray-400 truncate">{{ $streamArtist }}</p>
                        <div class="flex items-center space-x-2 mt-1">
                            <i class="fas fa-microphone text-emerald-400 text-xs"></i>
                            <span class="text-xs text-gray-400">{{ $streamShowName }}</span>
                        </div>
                    </div>
                    <div class="flex items-end space-x-1" x-show="audioPlaying">
                        <span class="w-1 h-3 bg-emerald-400 rounded-full animate-pulse"></span>
                        <span class="w-1 h-5 bg-emerald-300 rounded-full animate-pulse"></span>
                        <span class="w-1 h-4 bg-emerald-200 rounded-full animate-pulse"></span>
                    </div>
                </div>

                <!-- Controls -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <button type="button" @click="toggleLive"
                            class="w-10 h-10 bg-emerald-600 hover:bg-emerald-700 rounded-full flex items-center justify-center transition-colors">
                            <i class="fas text-white" :class="audioPlaying ? 'fa-pause' : 'fa-play'"></i>
                        </button>
                        <button
                            class="w-8 h-8 bg-gray-800 hover:bg-gray-700 rounded-full flex items-center justify-center transition-colors">
                            <i class="fas fa-volume-up text-white text-sm"></i>
                        </button>
                    </div>
                    <span class="text-xs text-gray-400">{{ $streamStatusMessage }}</span>
                    <a href="{{ $stationStreamUrl }}" target="_blank" rel="noopener"
                        class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white text-xs font-medium rounded-lg transition-colors">
                        Full Player
                    </a>
                </div>
            </div>
        </div>
    </div>

    <audio x-ref="liveAudio" src="{{ $stationStreamUrl }}" preload="none"></audio>

    @if (session()->has('newsletter_success'))
        <div class="fixed bottom-4 left-4 z-50 bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('newsletter_success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed bottom-4 left-4 z-50 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('error') }}
        </div>
    @endif

    <div x-show="consentBannerOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-6"
        class="fixed bottom-4 right-4 left-4 md:left-auto z-50 max-w-2xl bg-white border border-gray-200 shadow-2xl rounded-2xl p-5">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <p class="text-sm font-semibold text-gray-900">Consent needed for ads in the EEA, UK, and Switzerland</p>
                <p class="text-sm text-gray-600 mt-1">
                    We use a Google-certified consent management platform (CMP) to request your permission for ads and
                    measurement. Please choose an option to continue.
                </p>
                <a href="#" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium mt-2 inline-flex items-center">
                    Learn more about your choices
                    <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" @click="setConsent('reject')"
                    class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-semibold rounded-full hover:bg-gray-50 transition-colors">
                    Reject non-essential
                </button>
                <button type="button" @click="setConsent('accept')"
                    class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-full shadow-sm transition-colors">
                    Accept all
                </button>
            </div>
        </div>
    </div>

    @livewireScripts
</body>

</html>
