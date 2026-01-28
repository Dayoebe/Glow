<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Glow FM 99.1 - Your Voice, Your Music' }}</title>
    <meta name="google-adsense-account" content="ca-pub-3970534274644088">
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-SEVJRFYBL8"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-SEVJRFYBL8');
</script>
    
    @php
        $stationSettings = \App\Models\Setting::get('station', []);
        $stationName = data_get($stationSettings, 'name', 'Glow FM');
        $stationFrequency = data_get($stationSettings, 'frequency', '99.1 MHz');
        $stationTagline = data_get($stationSettings, 'tagline', 'Your Voice, Your Music');
        $stationLogoUrl = data_get($stationSettings, 'logo_url', '');
        if (!empty($stationLogoUrl) && !\Illuminate\Support\Str::startsWith($stationLogoUrl, ['http://', 'https://'])) {
            $stationLogoUrl = url($stationLogoUrl);
        }
        $metaTitle = $meta_title ?? ($title ?? trim($stationName . ' ' . $stationFrequency));
        $metaDescription = $meta_description ?? ($stationTagline . ' - The heartbeat of the city. Listen to the best music, engaging shows, and stay connected with your community.');
        $metaImage = $meta_image ?? $stationLogoUrl;
        if (!empty($metaImage) && !\Illuminate\Support\Str::startsWith($metaImage, ['http://', 'https://'])) {
            $metaImage = url($metaImage);
        }
        $canonicalUrl = $canonical_url ?? request()->url();
        $metaRobots = $meta_robots ?? 'index, follow';
        $metaType = $meta_type ?? 'website';
        $locale = str_replace('-', '_', app()->getLocale());
        $twitterSite = $twitter_site ?? data_get($stationSettings, 'twitter_handle', '');
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'RadioStation',
            'name' => $stationName,
            'url' => $canonicalUrl,
            'logo' => $stationLogoUrl,
            'slogan' => $stationTagline,
            'sameAs' => array_values(array_filter([
                data_get($stationSettings, 'socials.facebook'),
                data_get($stationSettings, 'socials.x'),
                data_get($stationSettings, 'socials.twitter'),
                data_get($stationSettings, 'socials.instagram'),
                data_get($stationSettings, 'socials.youtube'),
            ])),
        ];
    @endphp
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="robots" content="{{ $metaRobots }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:type" content="{{ $metaType }}">
    <meta property="og:site_name" content="{{ $stationName }}">
    <meta property="og:locale" content="{{ $locale }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
    @if (!empty($twitterSite))
        <meta name="twitter:site" content="{{ $twitterSite }}">
    @endif
    @if (!empty($stationLogoUrl))
        <link rel="icon" href="{{ $stationLogoUrl }}">
    @endif
    <script type="application/ld+json">
        @json($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    </script>

    <style>[x-cloak]{display:none!important;}</style>
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
    consentBannerOpen: false,
    consentChoice: null,
    init() {
        try {
            const storedConsent = localStorage.getItem('cmp_consent');
            if (storedConsent) {
                const parsedConsent = JSON.parse(storedConsent);
                this.consentChoice = parsedConsent.choice || null;
                this.consentBannerOpen = false;
            } else {
                this.consentBannerOpen = true;
            }
        } catch (e) {}
        window.addEventListener('scroll', () => {
            this.scrolled = window.pageYOffset > 20;
        });
    },
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
}" x-init="init()">
    @php
        $stationName = $stationName ?? data_get($stationSettings, 'name', 'Glow FM');
        $stationFrequency = $stationFrequency ?? data_get($stationSettings, 'frequency', '99.1 MHz');
        $stationTagline = $stationTagline ?? data_get($stationSettings, 'tagline', 'Your Voice, Your Music');
        $stationPhone = data_get($stationSettings, 'phone', '+1 (234) 567-890');
        $stationEmail = data_get($stationSettings, 'email', 'info@glowfm.com');
        $stationAddress = data_get($stationSettings, 'address', '123 Radio Street, Broadcasting City, BC 12345');
        $stationStreamUrl = data_get($stationSettings, 'stream_url', 'https://stream-176.zeno.fm/mwam2yirv1pvv');
        $stationSocials = data_get($stationSettings, 'socials', []);
        $streamSettings = \App\Models\Setting::get('stream', []);
        $systemSettings = \App\Models\Setting::get('system', []);
        $stationTimezone = 'Africa/Lagos'; // Enforce WAT
        $streamIsLive = data_get($streamSettings, 'is_live', true);
        $streamStatusMessage = data_get($streamSettings, 'status_message', 'Broadcasting live now');
        $streamTitle = data_get($streamSettings, 'now_playing_title', 'Blinding Lights');
        $streamArtist = data_get($streamSettings, 'now_playing_artist', 'The Weeknd');
        $streamShowName = data_get($streamSettings, 'show_name');
        $streamShowHost = data_get($streamSettings, 'show_host');
        $streamShowTime = data_get($streamSettings, 'show_time');
        $now = now($stationTimezone);
        $day = strtolower($now->format('l'));
        $time = $now->format('H:i:s');
        $currentSlot = \App\Models\Show\ScheduleSlot::query()
            ->with(['show', 'oap'])
            ->active()
            ->forDay($day)
            ->where('start_time', '<=', $time)
            ->where('end_time', '>', $time)
            ->orderBy('start_time', 'desc')
            ->first();

        if ($currentSlot && !$currentSlot->isActiveOn($now)) {
            $currentSlot = null;
        }

        $currentProgramTitle = $currentSlot?->show?->title ?: ($streamShowName ?: 'Unknown');
        $currentProgramHost = $currentSlot?->oap?->name ?: ($streamShowHost ?: ($streamArtist ?: 'Unknown'));
        $currentProgramTime = $currentSlot?->time_range ?: ($streamShowTime ?: 'Unknown');
        $recentShows = \App\Models\Show\Show::active()
            ->latest('created_at')
            ->take(3)
            ->get();
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
                            <span class="font-medium">{{ $currentProgramTitle }}</span>
                            <span class="text-emerald-200">•</span>
                            <span class="font-medium tabular-nums"
                                  data-station-timezone="{{ $stationTimezone }}"
                                  x-data="{
                                    now: '',
                                    tz: null,
                                    init() {
                                        this.tz = this.$el.dataset.stationTimezone || 'UTC';
                                        const formatter = new Intl.DateTimeFormat([], {
                                            hour: '2-digit',
                                            minute: '2-digit',
                                            timeZone: this.tz,
                                        });
                                        const format = () => {
                                            this.now = formatter.format(new Date());
                                        };
                                        format();
                                        setInterval(format, 1000);
                                    }
                                  }"
                                  x-text="now"></span>
                            <span class="text-emerald-200 text-[11px] font-semibold">WAT</span>
                        </span>
                        <div class="flex items-center space-x-3">
                            <a href="{{ data_get($stationSocials, 'facebook', '#') }}" class="hover:text-emerald-100 transition-colors" aria-label="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="{{ data_get($stationSocials, 'x', data_get($stationSocials, 'twitter', '#')) }}" class="hover:text-emerald-100 transition-colors" aria-label="X">
                                <i class="fab fa-x-twitter"></i>
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
                        @if (!empty($stationLogoUrl))
                            <img src="{{ $stationLogoUrl }}" alt="{{ $stationName }} logo"
                                class="w-12 h-12 rounded-xl object-contain bg-white shadow-lg p-1 transform group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div
                                class="w-12 h-12 bg-emerald-600 rounded-xl shadow-lg flex items-center justify-center transform group-hover:scale-105 transition-transform duration-300">
                                <i class="fas fa-radio text-white text-2xl"></i>
                            </div>
                        @endif
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
                @auth
                    <div class="pt-2 space-y-2">
                        <a href="/profile"
                            class="block px-4 py-3 text-gray-700 font-medium hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors">
                            <i class="fas fa-user-circle mr-2"></i> My Profile
                        </a>
                        <a href="/settings"
                            class="block px-4 py-3 text-gray-700 font-medium hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors">
                            <i class="fas fa-cog mr-2"></i> Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-3 text-red-600 font-medium hover:bg-red-50 rounded-lg transition-colors">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                @else
                    <div class="pt-2 space-y-2">
                        <a href="{{ route('login') }}"
                            class="block px-4 py-3 text-emerald-600 font-medium hover:bg-emerald-50 rounded-lg transition-colors">
                            <i class="fas fa-right-to-bracket mr-2"></i> Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="block px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors">
                            <i class="fas fa-user-plus mr-2"></i> Sign Up
                        </a>
                    </div>
                @endauth
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

    @if (session()->has('error'))
        <div class="bg-red-50 border-b border-red-200 text-red-700">
            <div class="container mx-auto px-4 py-3 flex items-start space-x-3 text-sm">
                <i class="fas fa-circle-exclamation mt-0.5"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('success'))
        <div class="bg-emerald-50 border-b border-emerald-200 text-emerald-700">
            <div class="container mx-auto px-4 py-3 flex items-start space-x-3 text-sm">
                <i class="fas fa-circle-check mt-0.5"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

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
                        @if (!empty($stationLogoUrl))
                            <img src="{{ $stationLogoUrl }}" alt="{{ $stationName }} logo"
                                class="w-12 h-12 rounded-xl object-contain bg-white shadow-lg p-1">
                        @else
                            <div class="w-12 h-12 bg-emerald-600 rounded-xl shadow-lg flex items-center justify-center">
                                <i class="fas fa-radio text-white text-2xl"></i>
                            </div>
                        @endif
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
                        <a href="{{ data_get($stationSocials, 'x', data_get($stationSocials, 'twitter', '#')) }}"
                            class="w-10 h-10 bg-gray-800 hover:bg-emerald-600 rounded-lg flex items-center justify-center transition-colors duration-300">
                            <i class="fab fa-x-twitter"></i>
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
                            @forelse($recentShows as $show)
                                <a href="{{ route('shows.show', $show->slug) }}"
                                    class="flex items-center space-x-2 text-gray-400 hover:text-emerald-400 transition-colors text-sm">
                                    <i class="fas fa-microphone text-xs"></i>
                                    <span>{{ $show->title }}</span>
                                </a>
                            @empty
                                <span class="text-gray-500 text-sm">No shows yet.</span>
                            @endforelse
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
        x-transition:leave-end="opacity-0 translate-y-full" class="fixed bottom-4 right-4 sm:bottom-6 sm:right-6 z-50">
        <div class="bg-gray-900 text-white rounded-2xl shadow-2xl overflow-hidden max-w-[18rem] sm:max-w-sm">
            <!-- Player Header -->
            <div class="bg-emerald-600 px-3 py-1.5 sm:px-4 sm:py-2 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="relative flex h-2 w-2 sm:h-2.5 sm:w-2.5">
                        <span class="absolute inline-flex h-full w-full rounded-full bg-white opacity-60"
                            :class="audioPlaying ? 'animate-ping' : ''"></span>
                        <span class="relative inline-flex h-2.5 w-2.5 rounded-full"
                            :class="audioPlaying ? 'bg-lime-300' : 'bg-white'"></span>
                    </span>
                    <span class="text-xs sm:text-sm font-semibold" x-text="audioPlaying ? 'STREAMING LIVE' : 'LIVE NOW'"></span>
                </div>
                <button @click="playerOpen = false" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-sm sm:text-base"></i>
                </button>
            </div>

            <!-- Player Content -->
            <div class="p-3 sm:p-4">
                <div class="flex items-center space-x-3 sm:space-x-4 mb-3 sm:mb-4">
                    <div class="flex-shrink-0">
                        <div
                            class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-lg shadow-lg flex items-center justify-center">
                            <i class="fas fa-music text-white text-xl sm:text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-xs sm:text-sm truncate">{{ $streamTitle }}</h4>
                        <p class="text-xs text-gray-400 truncate">{{ $streamArtist }}</p>
                        <div class="flex items-center space-x-2 mt-1">
                            <i class="fas fa-microphone text-emerald-400 text-xs"></i>
                            <span class="text-xs text-gray-400 truncate">{{ $currentProgramTitle }}</span>
                        </div>
                        <div class="flex items-center space-x-2 mt-1">
                            <i class="fas fa-user text-emerald-400 text-[10px]"></i>
                            <span class="text-[11px] text-gray-400 truncate">{{ $currentProgramHost }}</span>
                        </div>
                        <div class="flex items-center space-x-2 mt-1">
                            <i class="fas fa-clock text-emerald-400 text-[10px]"></i>
                            <span class="text-[11px] text-gray-400">{{ $currentProgramTime }}</span>
                            <span class="text-[10px] text-emerald-300 font-semibold">WAT</span>
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
                            class="w-9 h-9 sm:w-10 sm:h-10 bg-emerald-600 hover:bg-emerald-700 rounded-full flex items-center justify-center transition-colors">
                            <i class="fas text-white text-sm sm:text-base" :class="audioPlaying ? 'fa-pause' : 'fa-play'"></i>
                        </button>
                        <button
                            class="w-7 h-7 sm:w-8 sm:h-8 bg-gray-800 hover:bg-gray-700 rounded-full flex items-center justify-center transition-colors">
                            <i class="fas fa-volume-up text-white text-xs sm:text-sm"></i>
                        </button>
                    </div>
                    <span class="text-[11px] sm:text-xs text-gray-400">{{ $streamStatusMessage }}</span>
                    <a href="{{ $stationStreamUrl }}" target="_blank" rel="noopener"
                        class="px-3 py-1.5 sm:px-4 sm:py-2 bg-gray-800 hover:bg-gray-700 text-white text-[11px] sm:text-xs font-medium rounded-lg transition-colors">
                        Full Player
                    </a>
                </div>
            </div>
        </div>
    </div>

    <audio x-ref="liveAudio" src="{{ $stationStreamUrl }}" preload="none"></audio>

    @if ($errors->any())
        @php
            $errorCount = $errors->count();
        @endphp
        <div class="fixed bottom-4 left-4 z-50 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg max-w-sm">
            <p class="text-sm font-semibold">Please check the form.</p>
            <p class="text-xs mt-1">{{ $errors->first() }}</p>
            @if ($errorCount > 1)
                <p class="text-[11px] mt-1 opacity-90">+{{ $errorCount - 1 }} more</p>
            @endif
        </div>
    @endif

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

    <div x-cloak x-show="consentBannerOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-6"
        class="js-cookie-consent cookie-consent fixed hover:uppercase bottom-0 inset-x-0 pb-2 z-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="p-2 rounded-lg bg-indigo-100">
                <div class="flex flex-col sm:flex-row items-center justify-between flex-wrap">
                    <div class="w-full sm:w-auto flex-1 items-center mb-2 sm:mb-0">
                        <p class="ml-3 text-black cookie-consent__message">
                            We use cookies to improve your experience.
                        </p>
                        <a href="{{ route('privacy.policy') }}" class="ml-3 text-xs text-blue-800 hover:text-blue-900 underline">
                            Privacy Policy
                        </a>
                    </div>
                    <div class="mt-2 flex-shrink-0 sm:mt-0 sm:ml-2 sm:order-last">
                        <button type="button" @click="setConsent('accept')"
                            class="js-cookie-consent-agree cookie-consent__agree cursor-pointer flex items-center justify-center px-4 py-2 rounded-md text-sm font-medium text-white hover:text-black bg-blue-800 hover:bg-blue-300">
                            {{ trans('Agree') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('click', async (event) => {
            const button = event.target.closest('[data-copy-link]');
            if (!button) return;

            event.preventDefault();
            const link = button.getAttribute('data-copy-link') || '';
            if (!link) return;

            const textTarget = button.querySelector('[data-copy-text]');
            const originalText = textTarget ? textTarget.textContent : '';

            try {
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    await navigator.clipboard.writeText(link);
                } else {
                    const textarea = document.createElement('textarea');
                    textarea.value = link;
                    textarea.setAttribute('readonly', '');
                    textarea.style.position = 'absolute';
                    textarea.style.left = '-9999px';
                    document.body.appendChild(textarea);
                    textarea.select();
                    document.execCommand('copy');
                    textarea.remove();
                }
                if (textTarget) {
                    textTarget.textContent = 'Copied!';
                    setTimeout(() => {
                        textTarget.textContent = originalText;
                    }, 1500);
                }
            } catch (e) {
                if (textTarget) {
                    textTarget.textContent = 'Copy failed';
                    setTimeout(() => {
                        textTarget.textContent = originalText;
                    }, 1500);
                }
            }
        });
    </script>
    @livewireScripts
</body>

</html>
