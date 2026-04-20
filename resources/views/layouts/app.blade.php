<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#047857">
    <title>{{ $title ?? 'Glow FM 99.1 - Your Station, Your Voice' }}</title>
    <meta name="google-adsense-account" content="ca-pub-3970534274644088">
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-SEVJRFYBL8"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-SEVJRFYBL8');
</script>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3970534274644088"
     crossorigin="anonymous"></script>
    
    @php
        $stationSettings = \App\Models\Setting::get('station', []);
        $stationName = data_get($stationSettings, 'name', 'Glow FM');
        $stationFrequency = data_get($stationSettings, 'frequency', '99.1 MHz');
        $stationTagline = data_get($stationSettings, 'tagline', 'Your Station, Your Voice');
        $stationLogoUrl = data_get($stationSettings, 'logo_url', '');
        if (empty($stationLogoUrl)) {
            $stationLogoUrl = asset('glowfm logo.jpeg');
        }
        if (!empty($stationLogoUrl) && !\Illuminate\Support\Str::startsWith($stationLogoUrl, ['http://', 'https://'])) {
            $stationLogoUrl = url($stationLogoUrl);
        }
        $metaTitle = $meta_title ?? ($title ?? trim($stationName . ' ' . $stationFrequency));
        $metaDescription = $meta_description ?? ($stationTagline . ' - The heartbeat of the city. Listen to the best music, engaging shows, and stay connected with your community.');
        $metaImage = $meta_image ?? $stationLogoUrl;
        if (!empty($metaImage) && !\Illuminate\Support\Str::startsWith($metaImage, ['http://', 'https://'])) {
            $metaImage = url($metaImage);
        }
        $metaImageAlt = $meta_image_alt ?? $metaTitle;
        $canonicalUrl = $canonical_url ?? request()->url();
        $metaRobots = $meta_robots ?? 'index, follow';
        $metaType = $meta_type ?? 'website';
        $metaPublishedTime = $meta_published_time ?? null;
        $metaModifiedTime = $meta_modified_time ?? null;
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
    <meta property="og:image:secure_url" content="{{ $metaImage }}">
    <meta property="og:image:alt" content="{{ $metaImageAlt }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:type" content="{{ $metaType }}">
    <meta property="og:site_name" content="{{ $stationName }}">
    <meta property="og:locale" content="{{ $locale }}">
    @if (!empty($metaPublishedTime))
        <meta property="article:published_time" content="{{ $metaPublishedTime }}">
    @endif
    @if (!empty($metaModifiedTime))
        <meta property="article:modified_time" content="{{ $metaModifiedTime }}">
        <meta property="og:updated_time" content="{{ $metaModifiedTime }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
    @if (!empty($twitterSite))
        <meta name="twitter:site" content="{{ $twitterSite }}">
    @endif
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ $stationName }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('icons/icon-512x512.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">
    @if (!empty($stationLogoUrl))
        <link rel="icon" href="{{ $stationLogoUrl }}">
    @endif
    <script type="application/ld+json">
        @json($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    </script>

    <style>
        [x-cloak]{display:none!important;}
        .flash-auto-dismiss{
            overflow:hidden;
            max-height:200px;
            pointer-events:none;
            animation:flashAutoDismiss 5s ease forwards;
        }
        @keyframes flashAutoDismiss{
            0%,85%{opacity:1;max-height:200px;}
            100%{opacity:0;max-height:0;padding-top:0;padding-bottom:0;margin-top:0;margin-bottom:0;border-width:0;}
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @livewireStyles
</head>

<body class="mobile-app-shell mobile-public-shell overflow-x-hidden bg-slate-950 font-sans antialiased text-slate-900" x-data="{ 
    mobileMenuOpen: false, 
    searchOpen: false,
    mobileNavCollapsed: false,
    mobileLivePanelOpen: true,
    scrolled: false,
    consentBannerOpen: false,
    consentChoice: null,
    installPromptEvent: null,
    canInstallApp: false,
    installInProgress: false,
    appInstalled: false,
    installStorageKey: 'glowfm_pwa_installed',
    installPromptAttempted: false,
    getStoredInstallState() {
        try {
            return localStorage.getItem(this.installStorageKey) === '1';
        } catch (e) {
            return false;
        }
    },
    setStoredInstallState() {
        try {
            localStorage.setItem(this.installStorageKey, '1');
        } catch (e) {}
    },
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
        this.initInstallApp();
    },
    initInstallApp() {
        const standaloneMode = (window.matchMedia && window.matchMedia('(display-mode: standalone)').matches)
            || window.navigator.standalone === true;
        const storedInstalled = this.getStoredInstallState();
        this.appInstalled = standaloneMode || storedInstalled;
        if (standaloneMode) {
            this.setStoredInstallState();
        }
        this.canInstallApp = false;

        window.addEventListener('beforeinstallprompt', (event) => {
            event.preventDefault();
            this.installPromptEvent = event;
            this.canInstallApp = !this.appInstalled;

            if (!this.appInstalled && !this.installPromptAttempted) {
                this.installPromptAttempted = true;
                setTimeout(() => this.installApp(), 200);
            }
        });

        window.addEventListener('appinstalled', () => {
            this.appInstalled = true;
            this.canInstallApp = false;
            this.installPromptEvent = null;
            this.mobileMenuOpen = false;
            this.setStoredInstallState();
        });
    },
    async installApp() {
        if (!this.installPromptEvent || this.installInProgress || this.appInstalled) {
            return;
        }

        this.installInProgress = true;
        try {
            await this.installPromptEvent.prompt();
            const { outcome } = await this.installPromptEvent.userChoice;
            if (outcome === 'accepted') {
                this.appInstalled = true;
                this.canInstallApp = false;
                this.mobileMenuOpen = false;
                this.setStoredInstallState();
            } else {
                this.canInstallApp = !this.appInstalled;
            }
        } catch (error) {
            console.error('Install prompt failed:', error);
            this.canInstallApp = !this.appInstalled;
        } finally {
            this.installInProgress = false;
            if (this.appInstalled) {
                this.installPromptEvent = null;
            }
        }
    },
    toggleLive() {
        this.$store.radio.toggle();
    },
    startLive() {
        this.$store.radio.start();
    },
    closeMobileChrome() {
        this.mobileMenuOpen = false;
        this.searchOpen = false;
        this.mobileNavCollapsed = true;
    },
    toggleMobileNav() {
        this.mobileNavCollapsed = !this.mobileNavCollapsed;
        if (!this.mobileNavCollapsed) {
            this.mobileMenuOpen = false;
            this.searchOpen = false;
        }
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
        $stationTagline = $stationTagline ?? data_get($stationSettings, 'tagline', 'Your Station, Your Voice');
        $stationPhone = data_get($stationSettings, 'phone', '+1 (234) 567-890');
        $stationEmail = data_get($stationSettings, 'email', 'info@glowfm.com');
        $stationAddress = data_get($stationSettings, 'address', '123 Radio Street, Broadcasting City, BC 12345');
        $stationStreamUrl = data_get($stationSettings, 'stream_url', 'https://stream-176.zeno.fm/mwam2yirv1pvv');
        $stationSocials = data_get($stationSettings, 'socials', []);
        $streamSettings = \App\Models\Setting::get('stream', []);
        $systemSettings = \App\Models\Setting::get('system', []);
        $authUser = auth()->user();
        $canAccessDashboard = $authUser && ($authUser->isAdmin() || $authUser->isStaff());
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
        $publicMobileNav = [
            [
                'label' => 'Home',
                'icon' => 'fas fa-house',
                'href' => route('home'),
                'active' => request()->routeIs('home'),
                'active_classes' => 'bg-sky-600 text-white shadow-lg',
                'inactive_classes' => 'bg-sky-50/80 text-sky-700 hover:bg-sky-100',
            ],
            [
                'label' => 'News',
                'icon' => 'fas fa-newspaper',
                'href' => route('news'),
                'active' => request()->routeIs('news', 'news.show'),
                'active_classes' => 'bg-orange-500 text-white shadow-lg',
                'inactive_classes' => 'bg-orange-50/80 text-orange-700 hover:bg-orange-100',
            ],
            [
                'label' => 'Podcast',
                'icon' => 'fas fa-podcast',
                'href' => route('podcasts.index'),
                'active' => request()->routeIs('podcasts.*'),
                'active_classes' => 'bg-indigo-600 text-white shadow-lg',
                'inactive_classes' => 'bg-indigo-50/80 text-indigo-700 hover:bg-indigo-100',
            ],
            [
                'label' => 'All Shows',
                'icon' => 'fas fa-microphone-lines',
                'href' => route('shows.index'),
                'active' => request()->routeIs('shows.*'),
                'active_classes' => 'bg-emerald-600 text-white shadow-lg',
                'inactive_classes' => 'bg-emerald-50/80 text-emerald-700 hover:bg-emerald-100',
            ],
        ];
    @endphp

    <!-- Fixed Header -->
    <header class="fixed inset-x-0 top-0 z-[80] transition-all duration-300">
        <!-- Top Bar -->
        <div class="hidden lg:block bg-slate-600 text-white">
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
                                    :class="$store.radio.audioPlaying ? 'animate-ping' : ''"></span>
                                <span class="relative inline-flex h-2 w-2 rounded-full"
                                    :class="$store.radio.audioPlaying ? 'bg-emerald-400' : 'bg-red-500'"></span>
                            </span>
                            <span class="font-medium" x-text="$store.radio.audioPlaying ? 'LIVE STREAMING' : '{{ $streamIsLive ? 'LIVE NOW' : 'OFFLINE' }}'"></span>
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
        <div class="w-full pt-[calc(env(safe-area-inset-top)+0.75rem)] lg:pt-0">
            <div class="mobile-app-surface relative overflow-hidden border-y border-white/70 shadow-2xl lg:overflow-visible lg:border-0 lg:bg-transparent lg:shadow-none lg:backdrop-blur-none"
                :class="scrolled ? 'lg:bg-white lg:shadow-lg' : 'lg:bg-white/95 lg:shadow-none lg:backdrop-blur-sm'">
                <nav class="relative z-[85] mx-auto flex max-w-screen-2xl items-center justify-between px-4 py-3 lg:h-20 lg:px-4 lg:py-0">

                    <!-- Logo -->
                    <a href="/" class="flex items-center space-x-3 group">
                        <div class="relative">
                            @if (!empty($stationLogoUrl))
                                <img src="{{ $stationLogoUrl }}" alt="{{ $stationName }} logo"
                                    class="h-11 w-11 rounded-2xl object-contain bg-white p-1 shadow-lg transition-transform duration-300 group-hover:scale-105 lg:h-12 lg:w-12 lg:rounded-xl">
                            @else
                                <div
                                    class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-600 shadow-lg transition-transform duration-300 group-hover:scale-105 lg:h-12 lg:w-12 lg:rounded-xl">
                                    <i class="fas fa-radio text-xl text-white lg:text-2xl"></i>
                                </div>
                            @endif
                            <div
                                class="absolute -top-1 -right-1 h-4 w-4 rounded-full border-2 border-white bg-red-500 animate-pulse">
                            </div>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-emerald-700 lg:hidden">Live Radio</p>
                            <h1 class="text-lg font-bold leading-none text-gray-900 lg:text-2xl">{{ $stationName }}</h1>
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
                        <a href="/news"
                            class="px-4 py-2 text-gray-700 font-medium hover:text-emerald-600 transition-colors duration-200 {{ request()->is('news*') ? 'text-emerald-600' : '' }}">
                            News
                        </a>
                        <a href="/shows"
                            class="px-4 py-2 text-gray-700 font-medium hover:text-emerald-600 transition-colors duration-200 {{ request()->is('shows*') ? 'text-emerald-600' : '' }}">
                            Shows
                        </a>
                        <a href="/schedule"
                            class="px-4 py-2 text-gray-700 font-medium hover:text-emerald-600 transition-colors duration-200 {{ request()->is('schedule') ? 'text-emerald-600' : '' }}">
                            Schedule
                        </a>
                        <a href="/vettas"
                            class="px-4 py-2 text-gray-700 font-medium hover:text-emerald-600 transition-colors duration-200 {{ request()->is('vettas*') ? 'text-emerald-600' : '' }}">
                            Vettas
                        </a>
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false"
                                class="flex items-center px-4 py-2 text-gray-700 font-medium transition-colors duration-200 hover:text-emerald-600 {{ request()->is('podcasts*') || request()->is('blog*') || request()->is('oaps*') || request()->is('team*') || request()->is('events*') || request()->is('contact*') || request()->is('careers*') ? 'text-emerald-600' : '' }}">
                                More
                                <i class="fas fa-chevron-down ml-2 text-xs"></i>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-2"
                                class="absolute left-0 z-[95] mt-2 w-56 rounded-lg border border-gray-200 bg-white py-2 shadow-lg">
                                <a href="/podcasts"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                    Podcasts
                                </a>
                                <a href="/blog"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                    Blog
                                </a>
                                <a href="/oaps"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                    OAPs
                                </a>
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
                                <div class="my-1 border-t border-gray-100"></div>
                                <a href="/careers"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                    Careers
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side Actions -->
                    <div class="flex items-center space-x-2 lg:space-x-4">
                        <!-- Install App Button -->
                        <button type="button" x-cloak x-show="canInstallApp && !appInstalled" @click="installApp"
                            :disabled="installInProgress"
                            class="hidden items-center space-x-2 rounded-full border border-emerald-200 bg-white px-4 py-2 font-semibold text-emerald-700 shadow-sm transition-colors disabled:cursor-not-allowed disabled:opacity-70 hover:bg-emerald-50 md:flex">
                            <i class="fas fa-download text-xs"></i>
                            <span x-text="installInProgress ? 'Installing...' : 'Install App'"></span>
                        </button>

                        <!-- Search Buttons -->
                        <button @click="searchOpen = !searchOpen"
                            class="hidden h-10 w-10 items-center justify-center rounded-lg text-gray-700 transition-all duration-200 hover:bg-emerald-50 hover:text-emerald-600 md:flex">
                            <i class="fas fa-search"></i>
                        </button>
                        <button @click="searchOpen = !searchOpen; mobileMenuOpen = false; mobileNavCollapsed = true"
                            class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900/5 text-gray-700 transition-all duration-200 hover:bg-emerald-50 hover:text-emerald-600 lg:hidden">
                            <i class="fas fa-search"></i>
                        </button>

                        <!-- Authentication Links -->
                        @auth
                            <!-- User Dropdown -->
                            <div x-data="{ userMenuOpen: false }" class="relative">
                                <button @click="userMenuOpen = !userMenuOpen"
                                    class="flex items-center space-x-2 rounded-full bg-emerald-50 px-3 py-2 text-emerald-700 transition-all duration-200 hover:bg-emerald-100 lg:px-4">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-600">
                                        <i class="fas fa-user text-sm text-white"></i>
                                    </div>
                                    <span class="hidden font-medium md:inline">{{ auth()->user()->name }}</span>
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>

                                <!-- Dropdown Menu -->
                                <div x-show="userMenuOpen" @click.away="userMenuOpen = false"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute right-0 z-[95] mt-2 w-48 rounded-lg border border-gray-200 bg-white py-2 shadow-lg">

                                    @if($canAccessDashboard)
                                        <a href="{{ route('dashboard') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                        </a>
                                        <div class="my-1 border-t"></div>
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
                                            class="mt-1 block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <!-- Login/Register Links -->
                            <div class="hidden md:flex items-center space-x-2">
                                <a href="{{ route('login') }}"
                                    class="px-4 py-2 font-medium text-emerald-600 transition-colors hover:text-emerald-700">
                                    Login
                                </a>
                                <a href="{{ route('register') }}"
                                    class="rounded-full bg-emerald-600 px-6 py-2 font-semibold text-white transition-colors hover:bg-emerald-700">
                                    Sign Up
                                </a>
                            </div>
                        @endauth

                        <!-- Listen Live Button -->
                        <button type="button" @click="startLive"
                            class="hidden items-center space-x-2 rounded-full bg-emerald-600 px-6 py-3 font-semibold text-white shadow-lg transition-all duration-300 hover:scale-105 hover:bg-emerald-700 hover:shadow-xl md:flex">
                            <i class="fas fa-play-circle text-xl"></i>
                            <span>Listen Live</span>
                        </button>

                        <!-- Mobile Menu Button -->
                        <button @click="mobileMenuOpen = !mobileMenuOpen; searchOpen = false; mobileNavCollapsed = true"
                            class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900/5 text-gray-700 transition-all duration-200 hover:bg-emerald-50 hover:text-emerald-600 lg:hidden">
                            <i class="fas" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
                        </button>
                    </div>
                </nav>

                <div class="border-t border-slate-200/70 px-4 pb-4 pt-2 lg:hidden">
                    <div class="mobile-app-surface-dark rounded-[1.6rem] px-4 py-3 text-white">
                        <button type="button" @click="mobileLivePanelOpen = !mobileLivePanelOpen"
                            class="flex w-full items-start justify-between gap-3 text-left">
                            <div class="min-w-0">
                                <p class="flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-emerald-200">
                                    <span class="relative flex h-2.5 w-2.5">
                                        <span class="absolute inline-flex h-full w-full rounded-full bg-emerald-300 opacity-75"
                                            :class="$store.radio.audioPlaying ? 'animate-ping' : ''"></span>
                                        <span class="relative inline-flex h-2.5 w-2.5 rounded-full"
                                            :class="$store.radio.audioPlaying ? 'bg-lime-300' : 'bg-emerald-300'"></span>
                                    </span>
                                    On Air Now
                                </p>
                                <p class="mt-2 truncate text-base font-semibold">{{ $currentProgramTitle }}</p>
                                <p class="mt-1 truncate text-xs text-slate-300">{{ $currentProgramHost }} • {{ $currentProgramTime }} WAT</p>
                            </div>
                            <span class="mt-1 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-white/10 text-white">
                                <i class="fas text-xs transition-transform duration-200"
                                    :class="mobileLivePanelOpen ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                            </span>
                        </button>

                        <div x-cloak x-show="mobileLivePanelOpen"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            class="mt-3">
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="truncate text-xs text-slate-300">{{ $streamTitle }}</p>
                                    <p class="mt-1 truncate text-[11px] text-slate-400">{{ $streamArtist }} • {{ $streamStatusMessage }}</p>
                                </div>
                                <button type="button" @click="startLive; closeMobileChrome()"
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white text-emerald-700 shadow-lg">
                                    <i class="fas fa-play text-sm"></i>
                                </button>
                            </div>
                            <div class="mt-3 flex items-center gap-2">
                                <a href="{{ route('schedule') }}" @click="closeMobileChrome()"
                                    class="inline-flex items-center rounded-full bg-white/10 px-3 py-2 text-xs font-medium text-white transition hover:bg-white/20">
                                    <i class="fas fa-calendar-alt mr-2 text-[11px]"></i>Schedule
                                </a>
                                <button type="button" x-cloak x-show="canInstallApp && !appInstalled" @click="installApp"
                                    :disabled="installInProgress"
                                    class="inline-flex items-center rounded-full bg-emerald-500/20 px-3 py-2 text-xs font-medium text-emerald-100 transition hover:bg-emerald-500/30 disabled:opacity-70">
                                    <i class="fas fa-download mr-2 text-[11px]"></i>
                                    <span x-text="installInProgress ? 'Installing...' : 'Install App'"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar (Dropdown) -->
        <div x-cloak x-show="searchOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="mobile-app-surface fixed inset-x-3 top-[calc(env(safe-area-inset-top)+7.25rem)] z-[70] rounded-[1.75rem] border border-white/70 shadow-2xl lg:relative lg:inset-auto lg:top-auto lg:z-[90] lg:rounded-none lg:border-t lg:border-white/0 lg:bg-white lg:shadow-xl"
            @click.away="searchOpen = false">
            <div class="mx-auto max-w-3xl px-4 py-5 lg:px-4 lg:py-6">
                <div class="relative">
                    <input type="text" placeholder="Search news, shows, events..."
                        class="w-full rounded-2xl border-2 border-gray-300 px-5 py-4 pr-12 text-base transition-colors focus:border-emerald-500 focus:outline-none lg:px-6 lg:text-lg">
                    <button
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 text-emerald-600 hover:text-emerald-700">
                        <i class="fas fa-search text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-cloak x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="mobile-app-surface fixed inset-x-3 top-[calc(env(safe-area-inset-top)+7.25rem)] bottom-[calc(env(safe-area-inset-bottom)+6rem)] z-[60] overflow-y-auto rounded-[2rem] border border-white/70 p-4 shadow-2xl lg:hidden">
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <a href="/" @click="closeMobileChrome()"
                        class="rounded-[1.35rem] border border-slate-200/80 px-4 py-4 text-sm font-semibold text-gray-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">
                        <i class="fas fa-house mb-3 block text-base text-emerald-600"></i>
                        Home
                    </a>
                    <a href="/news" @click="closeMobileChrome()"
                        class="rounded-[1.35rem] border border-slate-200/80 px-4 py-4 text-sm font-semibold text-gray-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">
                        <i class="fas fa-newspaper mb-3 block text-base text-emerald-600"></i>
                        News
                    </a>
                    <a href="/shows" @click="closeMobileChrome()"
                        class="rounded-[1.35rem] border border-slate-200/80 px-4 py-4 text-sm font-semibold text-gray-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">
                        <i class="fas fa-microphone-lines mb-3 block text-base text-emerald-600"></i>
                        Shows
                    </a>
                    <a href="/schedule" @click="closeMobileChrome()"
                        class="rounded-[1.35rem] border border-slate-200/80 px-4 py-4 text-sm font-semibold text-gray-700 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700">
                        <i class="fas fa-calendar-alt mb-3 block text-base text-emerald-600"></i>
                        Schedule
                    </a>
                </div>
                <div class="space-y-2 rounded-[1.5rem] border border-slate-200/80 p-2">
                    <a href="/about" @click="closeMobileChrome()"
                        class="block rounded-xl px-4 py-3 text-gray-700 font-medium hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                        About
                    </a>
                    <a href="/vettas" @click="closeMobileChrome()"
                        class="block rounded-xl px-4 py-3 text-gray-700 font-medium hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                        Vettas
                    </a>
                    <div x-data="{ open: false }" class="rounded-xl border border-slate-200/80">
                        <button @click="open = !open"
                            class="flex w-full items-center justify-between rounded-xl px-4 py-3 text-gray-700 font-medium transition-colors hover:bg-emerald-50 hover:text-emerald-600">
                            <span>More</span>
                            <i class="fas text-xs" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </button>
                        <div x-show="open" x-transition class="space-y-1 pb-2">
                            <a href="/podcasts" @click="closeMobileChrome()"
                                class="block px-6 py-2 text-sm text-gray-600 hover:text-emerald-600">
                                Podcasts
                            </a>
                            <a href="/blog" @click="closeMobileChrome()"
                                class="block px-6 py-2 text-sm text-gray-600 hover:text-emerald-600">
                                Blog
                            </a>
                            <a href="/oaps" @click="closeMobileChrome()"
                                class="block px-6 py-2 text-sm text-gray-600 hover:text-emerald-600">
                                OAPs
                            </a>
                            <a href="/team" @click="closeMobileChrome()"
                                class="block px-6 py-2 text-sm text-gray-600 hover:text-emerald-600">
                                Team
                            </a>
                            <a href="/events" @click="closeMobileChrome()"
                                class="block px-6 py-2 text-sm text-gray-600 hover:text-emerald-600">
                                Events
                            </a>
                            <a href="/contact" @click="closeMobileChrome()"
                                class="block px-6 py-2 text-sm text-gray-600 hover:text-emerald-600">
                                Contact
                            </a>
                            <a href="/careers" @click="closeMobileChrome()"
                                class="block px-6 py-2 text-sm text-gray-600 hover:text-emerald-600">
                                Careers
                            </a>
                        </div>
                    </div>
                </div>
                <div class="rounded-[1.5rem] bg-slate-900 px-4 py-4 text-white">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-emerald-200">Current Stream</p>
                    <p class="mt-2 text-sm font-semibold">{{ $streamTitle }}</p>
                    <p class="mt-1 text-xs text-slate-300">{{ $streamArtist }} • {{ $streamStatusMessage }}</p>
                </div>
                @auth
                    <div class="space-y-2">
                        @if($canAccessDashboard)
                            <a href="{{ route('dashboard') }}" @click="closeMobileChrome()"
                                class="block rounded-xl bg-emerald-600 px-4 py-3 font-semibold text-white transition-colors hover:bg-emerald-700">
                                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                            </a>
                        @endif
                        <a href="/profile" @click="closeMobileChrome()"
                            class="block rounded-xl px-4 py-3 font-medium text-gray-700 transition-colors hover:bg-emerald-50 hover:text-emerald-600">
                            <i class="fas fa-user-circle mr-2"></i> My Profile
                        </a>
                        <a href="/settings" @click="closeMobileChrome()"
                            class="block rounded-xl px-4 py-3 font-medium text-gray-700 transition-colors hover:bg-emerald-50 hover:text-emerald-600">
                            <i class="fas fa-cog mr-2"></i> Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full rounded-xl px-4 py-3 text-left font-medium text-red-600 transition-colors hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                @else
                    <div class="space-y-2">
                        <a href="{{ route('login') }}" @click="closeMobileChrome()"
                            class="block rounded-xl px-4 py-3 font-medium text-emerald-600 transition-colors hover:bg-emerald-50">
                            <i class="fas fa-right-to-bracket mr-2"></i> Login
                        </a>
                        <a href="{{ route('register') }}" @click="closeMobileChrome()"
                            class="block rounded-xl bg-emerald-600 px-4 py-3 font-semibold text-white transition-colors hover:bg-emerald-700">
                            <i class="fas fa-user-plus mr-2"></i> Sign Up
                        </a>
                    </div>
                @endauth
                <button type="button" @click="startLive; closeMobileChrome()"
                    class="block rounded-full bg-emerald-600 px-6 py-3 text-center font-semibold text-white shadow-lg transition hover:bg-emerald-700">
                    <i class="fas fa-play-circle mr-2"></i> Listen Live
                </button>
            </div>
        </div>
    </header>

    @if($streamIsLive)
        <div class="hidden lg:block bg-emerald-600 text-white">
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
    <div class="h-36 lg:h-28"></div>

    <div x-cloak x-show="mobileMenuOpen || searchOpen"
        x-transition:enter="transition-opacity ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[55] bg-slate-950/35 backdrop-blur-sm lg:hidden"
        @click="mobileMenuOpen = false; searchOpen = false"></div>

    @if (session()->has('error'))
        <div class="mobile-app-surface mx-3 rounded-2xl border border-red-200/80 bg-red-50/95 text-red-700 flash-auto-dismiss lg:mx-0 lg:rounded-none lg:border-x-0">
            <div class="container mx-auto px-4 py-3 flex items-start space-x-3 text-sm">
                <i class="fas fa-circle-exclamation mt-0.5"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('success'))
        <div class="mobile-app-surface mx-3 rounded-2xl border border-emerald-200/80 bg-emerald-50/95 text-emerald-700 flash-auto-dismiss lg:mx-0 lg:rounded-none lg:border-x-0">
            <div class="container mx-auto px-4 py-3 flex items-start space-x-3 text-sm">
                <i class="fas fa-circle-check mt-0.5"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="relative z-10 pb-28 lg:pb-0" @click="closeMobileChrome()">
        {{ $slot }}
    </main>

    <nav x-cloak x-show="!mobileNavCollapsed"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="mobile-app-surface mobile-dock-shadow fixed inset-x-4 bottom-[calc(env(safe-area-inset-bottom)+0.5rem)] z-40 mx-auto max-w-md rounded-[1.4rem] border border-white/70 px-2 py-1.5 lg:hidden">
        <div class="grid grid-cols-4 gap-1">
            @foreach ($publicMobileNav as $navItem)
                <a href="{{ $navItem['href'] }}" @click="closeMobileChrome()"
                    class="flex min-w-0 flex-col items-center justify-center rounded-xl px-1 py-1.5 text-[10px] font-semibold leading-none transition {{ $navItem['active'] ? $navItem['active_classes'] : $navItem['inactive_classes'] }}">
                    <i class="{{ $navItem['icon'] }} mb-0.5 text-xs"></i>
                    <span class="max-w-full truncate whitespace-nowrap">{{ $navItem['label'] }}</span>
                </a>
            @endforeach
        </div>
    </nav>

    <button type="button" x-cloak x-show="mobileNavCollapsed"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        @click="toggleMobileNav()"
        class="mobile-app-surface mobile-dock-shadow fixed bottom-[calc(env(safe-area-inset-bottom)+0.5rem)] left-1/2 z-40 flex -translate-x-1/2 items-center gap-2 rounded-full border border-white/70 px-3.5 py-1.5 text-xs font-semibold text-slate-700 lg:hidden">
        <i class="fas fa-chevron-up text-xs"></i>
        <span>Menu</span>
    </button>

    <!-- Footer -->
    <footer class="mt-14 bg-gray-900 pt-16 pb-8 text-white lg:mt-20" @click="closeMobileChrome()">
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
                        <li>
                            <a href="/vettas"
                                class="text-gray-400 hover:text-emerald-400 transition-colors duration-200 flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Vettas
                            </a>
                        </li>
                        <li>
                            <a href="/careers"
                                class="text-gray-400 hover:text-emerald-400 transition-colors duration-200 flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Careers
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
                        &copy; {{ date('Y') }}
                        <a href="https://dayoebe.github.io" target="_blank" rel="noopener noreferrer"
                            class="hover:text-emerald-400 transition-colors">
                            {{ $stationName }} {{ $stationFrequency }}
                        </a>.
                        All rights reserved.
                    </p>
                    <div class="flex items-center space-x-6 text-sm">
                        <a href="privacy-policy" class="text-gray-400 hover:text-emerald-400 transition-colors">Privacy Policy</a>
                        <a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Terms of Service</a>
                        <a href="#" class="text-gray-400 hover:text-emerald-400 transition-colors">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating Listen Live Player -->
    <div x-show="$store.radio.playerOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-full" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-full"
        class="hidden fixed inset-x-3 bottom-[calc(env(safe-area-inset-bottom)+6.5rem)] z-50 sm:left-auto sm:right-4 sm:inset-x-auto sm:bottom-[calc(env(safe-area-inset-bottom)+6.25rem)] lg:block lg:bottom-6 lg:right-6">
        <div class="overflow-hidden rounded-2xl bg-gray-900 text-white shadow-2xl w-full max-w-none sm:max-w-sm">
            <!-- Player Header -->
            <div class="bg-emerald-600 px-3 py-1.5 sm:px-4 sm:py-2 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="relative flex h-2 w-2 sm:h-2.5 sm:w-2.5">
                        <span class="absolute inline-flex h-full w-full rounded-full bg-white opacity-60"
                            :class="$store.radio.audioPlaying ? 'animate-ping' : ''"></span>
                        <span class="relative inline-flex h-2.5 w-2.5 rounded-full"
                            :class="$store.radio.audioPlaying ? 'bg-lime-300' : 'bg-white'"></span>
                    </span>
                    <span class="text-xs sm:text-sm font-semibold" x-text="$store.radio.audioPlaying ? 'STREAMING LIVE' : 'LIVE NOW'"></span>
                </div>
                <button @click="$store.radio.closePlayer()" class="text-white hover:text-gray-200 transition-colors">
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
                    <div class="flex items-end space-x-1" x-show="$store.radio.audioPlaying">
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
                            <i class="fas text-white text-sm sm:text-base" :class="$store.radio.audioPlaying ? 'fa-pause' : 'fa-play'"></i>
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

    @persist('live-radio-audio')
        <audio x-init="$store.radio.bind($el)" src="{{ $stationStreamUrl }}" preload="none"></audio>
    @endpersist

    @if ($errors->any())
        @php
            $errorCount = $errors->count();
        @endphp
        <div class="fixed inset-x-3 bottom-[calc(env(safe-area-inset-bottom)+6.5rem)] z-50 rounded-2xl bg-red-600 px-6 py-3 text-white shadow-lg sm:left-4 sm:right-auto sm:max-w-sm lg:bottom-4">
            <p class="text-sm font-semibold">Please check the form.</p>
            <p class="text-xs mt-1">{{ $errors->first() }}</p>
            @if ($errorCount > 1)
                <p class="text-[11px] mt-1 opacity-90">+{{ $errorCount - 1 }} more</p>
            @endif
        </div>
    @endif

    @if (session()->has('newsletter_success'))
        <div class="fixed inset-x-3 bottom-[calc(env(safe-area-inset-bottom)+6.5rem)] z-50 rounded-2xl bg-emerald-600 px-6 py-3 text-white shadow-lg flash-auto-dismiss sm:left-4 sm:right-auto sm:max-w-sm lg:bottom-4">
            {{ session('newsletter_success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed inset-x-3 bottom-[calc(env(safe-area-inset-bottom)+6.5rem)] z-50 rounded-2xl bg-red-600 px-6 py-3 text-white shadow-lg flash-auto-dismiss sm:left-4 sm:right-auto sm:max-w-sm lg:bottom-4">
            {{ session('error') }}
        </div>
    @endif

    <div x-cloak x-show="consentBannerOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-6"
        class="js-cookie-consent cookie-consent fixed inset-x-0 bottom-0 z-50 pb-[calc(env(safe-area-inset-bottom)+6.5rem)] hover:uppercase lg:pb-2">
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
    <script>
        const openShareUrl = (url) => {
            if (!url || typeof url !== 'string') return;
            window.location.href = url;
        };

        window.addEventListener('open-share-url', (event) => {
            openShareUrl(event?.detail?.url ?? event?.detail);
        });

        document.addEventListener('livewire:load', () => {
            if (window.livewire && typeof window.livewire.on === 'function') {
                window.livewire.on('open-share-url', (payload) => {
                    openShareUrl(payload?.url ?? payload);
                });
            }
        });

        document.addEventListener('livewire:initialized', () => {
            if (window.Livewire && typeof Livewire.on === 'function') {
                Livewire.on('open-share-url', ({ url }) => openShareUrl(url));
            }
        });
    </script>
</body>

</html>
