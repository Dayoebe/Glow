<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#07162f">
    @php
        $googleAnalyticsId = config('services.google_site_tags.analytics_id');
        $googleAdsenseClient = config('services.google_site_tags.adsense_client');
        $googleSiteTagsEnabled = (bool) config('services.google_site_tags.enabled')
            && !in_array(request()->getHost(), ['localhost', '127.0.0.1', '::1'], true);
    @endphp
    <title>{{ $metaTitle ?? config('app.name', 'Glow') }}</title>
    @if ($googleSiteTagsEnabled)
        <meta name="google-adsense-account" content="{{ $googleAdsenseClient }}">
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $googleAnalyticsId }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', @js($googleAnalyticsId));
        </script>
        <script async
            src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $googleAdsenseClient }}"
            crossorigin="anonymous"></script>
    @endif
    
    @php
        $stationProfile = \App\Support\Seo::station();
        $stationSettings = \App\Models\Setting::get('station', []);
        $stationName = data_get($stationSettings, 'name', $stationProfile['display_name']);
        $stationFrequency = data_get($stationSettings, 'frequency', $stationProfile['display_frequency']);
        $stationTagline = data_get($stationSettings, 'tagline', $stationProfile['tagline']);
        $stationLogoUrl = data_get($stationSettings, 'logo_url', '');
        if (empty($stationLogoUrl)) {
            $stationLogoUrl = $stationProfile['logo'] ?: asset('glowfm logo.jpeg');
        }
        if (!empty($stationLogoUrl) && !\Illuminate\Support\Str::startsWith($stationLogoUrl, ['http://', 'https://'])) {
            $stationLogoUrl = url($stationLogoUrl);
        }
        $metaTitle = \App\Support\Seo::text(
            $meta_title ?? ($title ?? trim($stationProfile['name'] . ' ' . $stationProfile['frequency'])),
            70
        );
        $metaDescription = \App\Support\Seo::text(
            $meta_description ?? ($stationTagline . ' - Glow 99.1 FM is a radio station and digital news platform in Akure, Ondo State, Nigeria.'),
            165
        );
        $metaImage = \App\Support\Seo::socialImageUrl($meta_image ?? null, $stationLogoUrl)
            ?? $stationProfile['logo'];
        $metaImageIsSocialCrop = str_contains($metaImage, '/image/upload/f_jpg,q_auto:good,c_fill,g_auto,w_1200,h_630/');
        $metaImageAlt = $meta_image_alt ?? $metaTitle;
        $canonicalUrl = \App\Support\Seo::absoluteUrl($canonical_url ?? request()->url()) ?: request()->url();
        $metaRobots = $meta_robots ?? \App\Support\Seo::robotsDirectives();
        $metaType = $meta_type ?? 'website';
        $metaPublishedTime = $meta_published_time ?? null;
        $metaModifiedTime = $meta_modified_time ?? null;
        $metaAuthor = $meta_author ?? null;
        $metaSection = $meta_section ?? null;
        $metaTags = collect($meta_tags ?? [])->filter()->values();
        $locale = str_replace('-', '_', app()->getLocale());
        $twitterSite = $twitter_site ?? data_get($stationSettings, 'twitter_handle', '');
        $defaultBreadcrumbs = [['name' => 'Home', 'url' => route('home')]];
        if (!request()->routeIs('home')) {
            $segmentUrl = url('/');
            foreach (request()->segments() as $segment) {
                $segmentUrl .= '/' . $segment;
                if (str_contains($segment, '{')) {
                    continue;
                }
                $defaultBreadcrumbs[] = [
                    'name' => \Illuminate\Support\Str::headline(str_replace(['-', '_'], ' ', $segment)),
                    'url' => $segmentUrl,
                ];
            }
        }
        $structuredData = $structured_data ?? \App\Support\Seo::siteGraph([
            'title' => $metaTitle,
            'description' => $metaDescription,
            'url' => $canonicalUrl,
            'image' => $metaImage,
            'type' => request()->routeIs('home') ? 'WebPage' : 'WebPage',
            'breadcrumbs' => $defaultBreadcrumbs,
        ]);
    @endphp
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="robots" content="{{ $metaRobots }}">
    <meta name="googlebot" content="{{ $metaRobots }}">
    @if (!empty($metaAuthor))
        <meta name="author" content="{{ $metaAuthor }}">
    @endif
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta property="og:image:secure_url" content="{{ $metaImage }}">
    @if ($metaImageIsSocialCrop)
        <meta property="og:image:type" content="image/jpeg">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
    @endif
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
    @if (!empty($metaAuthor))
        <meta property="article:author" content="{{ $metaAuthor }}">
    @endif
    @if (!empty($metaSection))
        <meta property="article:section" content="{{ $metaSection }}">
    @endif
    @foreach ($metaTags as $metaTag)
        <meta property="article:tag" content="{{ $metaTag }}">
    @endforeach
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
    <meta name="twitter:image:alt" content="{{ $metaImageAlt }}">
    @if (!empty($twitterSite))
        <meta name="twitter:site" content="{{ $twitterSite }}">
    @endif
    <link rel="alternate" type="application/rss+xml" title="{{ $stationProfile['name'] }} Latest News RSS" href="{{ route('news.feed') }}">
    <link rel="alternate" type="application/rss+xml" title="{{ $stationProfile['name'] }} Podcasts RSS" href="{{ route('podcasts.feed') }}">
    <link rel="alternate" type="application/rss+xml" title="{{ $stationProfile['name'] }} Programs RSS" href="{{ route('shows.feed') }}">
    <link rel="alternate" type="text/markdown" title="{{ $stationProfile['name'] }} llms.txt" href="{{ route('llms') }}">
    <link rel="sitemap" type="application/xml" title="{{ $stationProfile['name'] }} Sitemap" href="{{ route('sitemap') }}">
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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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

<body class="mobile-app-shell mobile-public-shell overflow-x-hidden bg-glow-ivory font-public antialiased text-glow-ink" x-data="{
    mobileMenuOpen: false, 
    searchOpen: false,
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
        $stationName = $stationName ?? data_get($stationSettings, 'name', $stationProfile['display_name']);
        $stationFrequency = $stationFrequency ?? data_get($stationSettings, 'frequency', $stationProfile['display_frequency']);
        $stationTagline = $stationTagline ?? data_get($stationSettings, 'tagline', $stationProfile['tagline']);
        $stationPhone = data_get($stationSettings, 'phone', $stationProfile['phone']);
        $stationEmail = data_get($stationSettings, 'email', $stationProfile['email']);
        $stationAddress = data_get($stationSettings, 'address', $stationProfile['address']);
        $stationStreamUrl = data_get($stationSettings, 'stream_url', $stationProfile['stream_url']);
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
        $streamTrackLabel = collect([$streamTitle, $streamArtist])
            ->filter(fn ($value) => filled($value))
            ->implode(' — ');
        $recentShows = \App\Models\Show\Show::active()
            ->latest('created_at')
            ->take(3)
            ->get();
        $primaryNavigation = [
            ['label' => 'Home', 'href' => route('home'), 'active' => request()->routeIs('home')],
            ['label' => 'News', 'href' => route('news'), 'active' => request()->routeIs('news', 'news.show')],
            ['label' => 'Shows', 'href' => route('shows.index'), 'active' => request()->routeIs('shows.*')],
            ['label' => 'Schedule', 'href' => route('schedule'), 'active' => request()->routeIs('schedule')],
            ['label' => 'Podcasts', 'href' => route('podcasts.index'), 'active' => request()->routeIs('podcasts.*')],
        ];
        $exploreNavigation = [
            ['label' => 'Listen Live', 'icon' => 'fas fa-headphones', 'href' => route('listen.live'), 'active' => request()->is('listen-live')],
            ['label' => 'About Glow', 'icon' => 'fas fa-circle-info', 'href' => route('about'), 'active' => request()->is('about')],
            ['label' => 'Vettas', 'icon' => 'fas fa-star', 'href' => route('vettas.index'), 'active' => request()->is('vettas*')],
            ['label' => 'Blog', 'icon' => 'fas fa-pen-nib', 'href' => route('blog.index'), 'active' => request()->is('blog*')],
            ['label' => 'Presenters', 'icon' => 'fas fa-microphone-lines', 'href' => route('oaps.index'), 'active' => request()->is('oaps*')],
            ['label' => 'Our Team', 'icon' => 'fas fa-people-group', 'href' => route('staff.index'), 'active' => request()->is('team*')],
            ['label' => 'Events', 'icon' => 'fas fa-calendar-day', 'href' => route('events.index'), 'active' => request()->is('events*')],
            ['label' => 'Contact', 'icon' => 'fas fa-envelope', 'href' => route('contact'), 'active' => request()->is('contact*')],
            ['label' => 'Advertise', 'icon' => 'fas fa-bullhorn', 'href' => route('advertise'), 'active' => request()->is('advertise')],
            ['label' => 'Careers', 'icon' => 'fas fa-briefcase', 'href' => route('careers.index'), 'active' => request()->is('careers*')],
        ];
        $moreNavigationIsActive = collect($exploreNavigation)->contains('active', true);
    @endphp

    <!-- Fixed Header -->
    <header class="fixed inset-x-0 top-0 z-[80] transition-all duration-300">
        <!-- Live broadcast rail -->
        <div class="hidden bg-glow-ink text-white lg:block">
            <div class="mx-auto flex h-9 max-w-7xl items-center justify-between gap-6 px-5 text-xs">
                <div class="flex min-w-0 items-center gap-3">
                    <span class="inline-flex shrink-0 items-center gap-2 font-extrabold uppercase tracking-[0.16em] text-white">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-60"
                                :class="$store.radio.audioPlaying ? 'animate-ping' : ''"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-red-500"></span>
                        </span>
                        <span x-text="$store.radio.audioPlaying ? 'Listening live' : '{{ $streamIsLive ? 'On air' : 'Off air' }}'">{{ $streamIsLive ? 'On air' : 'Off air' }}</span>
                    </span>
                    <span class="h-4 w-px bg-white/20"></span>
                    <p class="min-w-0 truncate text-slate-300">
                        <strong class="font-bold text-white">{{ $currentProgramTitle }}</strong>
                        @if ($streamTrackLabel)
                            <span class="mx-1 text-slate-500">/</span>
                            {{ $streamTrackLabel }}
                        @endif
                    </p>
                    <button type="button" @click="startLive"
                        class="shrink-0 font-bold text-glow-amber transition hover:text-white">
                        Listen now <i class="fas fa-arrow-right ml-1 text-[10px]" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="flex shrink-0 items-center gap-4 text-slate-300">
                    <a href="tel:{{ $stationPhone }}" class="transition hover:text-white" aria-label="Call {{ $stationName }}">
                        <i class="fas fa-phone mr-1.5 text-[10px]" aria-hidden="true"></i>{{ $stationPhone }}
                    </a>
                    <span class="font-semibold tabular-nums text-white"
                        data-station-timezone="{{ $stationTimezone }}"
                        x-data="{
                            now: '',
                            init() {
                                const formatter = new Intl.DateTimeFormat([], {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    timeZone: this.$el.dataset.stationTimezone || 'UTC',
                                });
                                const format = () => this.now = formatter.format(new Date());
                                format();
                                setInterval(format, 1000);
                            }
                        }"
                        x-text="`${now} WAT`"></span>
                </div>
            </div>
        </div>

        <!-- Main Navigation -->
        <div class="w-full bg-glow-paper pt-[env(safe-area-inset-top)] lg:pt-0">
            <div class="relative overflow-visible border-b border-slate-200/90 bg-glow-paper/95 transition-shadow duration-200"
                :class="scrolled ? 'shadow-[0_12px_32px_rgba(7,22,47,0.08)]' : 'shadow-none'">
                <nav class="relative z-[85] mx-auto flex h-[4.5rem] max-w-7xl items-center justify-between gap-3 px-4 sm:px-5">

                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="group flex min-w-0 items-center gap-3" aria-label="{{ $stationName }} home">
                        <div class="relative shrink-0">
                            @if (!empty($stationLogoUrl))
                                <img src="{{ $stationLogoUrl }}" alt="{{ $stationName }} logo"
                                    width="48" height="48" loading="eager" decoding="async"
                                    class="h-11 w-11 rounded-lg border border-slate-200 bg-white object-contain p-0.5 transition-transform duration-200 group-hover:-rotate-2 lg:h-12 lg:w-12">
                            @else
                                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-glow-orange lg:h-12 lg:w-12">
                                    <i class="fas fa-radio text-xl text-white lg:text-2xl"></i>
                                </div>
                            @endif
                            <span class="absolute -right-1 -top-1 h-3 w-3 rounded-full border-2 border-white bg-red-500"
                                aria-hidden="true"></span>
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-lg font-extrabold leading-none tracking-[-0.04em] text-glow-ink lg:text-xl">
                                {{ $stationName }}
                            </p>
                            <p class="mt-1 text-[10px] font-extrabold uppercase tracking-[0.22em] text-glow-orange">
                                {{ $stationFrequency }} <span class="text-slate-400">Akure</span>
                            </p>
                        </div>
                    </a>

                    <!-- Desktop Navigation -->
                    <div class="hidden items-center lg:flex">
                        @foreach ($primaryNavigation as $navItem)
                            <x-public.nav-link :href="$navItem['href']" :active="$navItem['active']">
                                {{ $navItem['label'] }}
                            </x-public.nav-link>
                        @endforeach

                        <div x-data="{ open: false }" class="relative">
                            <button type="button" @click="open = !open" @click.away="open = false"
                                class="public-nav-link {{ $moreNavigationIsActive ? 'public-nav-link-active' : '' }}"
                                :aria-expanded="open.toString()" aria-haspopup="true">
                                Explore
                                <i class="fas fa-chevron-down ml-2 text-[9px] transition-transform"
                                    :class="open ? 'rotate-180' : ''" aria-hidden="true"></i>
                            </button>
                            <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-2"
                                class="absolute right-0 z-[95] mt-2 w-[30rem] rounded-xl border border-slate-200 bg-white p-3 shadow-[0_24px_70px_rgba(7,22,47,0.16)]">
                                <div class="mb-2 flex items-center justify-between border-b border-slate-100 px-2 pb-3">
                                    <div>
                                        <p class="public-kicker">Explore Glow</p>
                                        <p class="mt-1 text-xs text-slate-500">Radio, stories and community</p>
                                    </div>
                                    <span class="text-2xl text-glow-orange"><i class="fas fa-wave-square" aria-hidden="true"></i></span>
                                </div>
                                <div class="grid grid-cols-2 gap-1">
                                    @foreach ($exploreNavigation as $navItem)
                                        <a href="{{ $navItem['href'] }}"
                                            @if ($navItem['active']) aria-current="page" @endif
                                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-bold transition {{ $navItem['active'] ? 'bg-orange-50 text-glow-orange' : 'text-slate-700 hover:bg-slate-50 hover:text-glow-orange' }}">
                                            <span class="flex h-8 w-8 items-center justify-center rounded-md bg-slate-100 text-xs text-glow-navy">
                                                <i class="{{ $navItem['icon'] }}" aria-hidden="true"></i>
                                            </span>
                                            {{ $navItem['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side Actions -->
                    <div class="flex shrink-0 items-center gap-1.5 sm:gap-2">
                        <!-- Install App Button -->
                        <button type="button" x-cloak x-show="canInstallApp && !appInstalled" @click="installApp"
                            :disabled="installInProgress"
                            class="hidden h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-glow-navy transition hover:border-orange-200 hover:bg-orange-50 hover:text-glow-orange disabled:cursor-not-allowed disabled:opacity-50 xl:flex"
                            aria-label="Install Glow FM app">
                            <i class="fas fa-download text-xs" aria-hidden="true"></i>
                        </button>

                        <!-- Search Buttons -->
                        <button type="button" @click="searchOpen = !searchOpen; mobileMenuOpen = false"
                            class="flex h-10 w-10 items-center justify-center rounded-lg text-glow-ink transition hover:bg-slate-100 hover:text-glow-orange"
                            :aria-expanded="searchOpen.toString()" aria-label="Search Glow FM">
                            <i class="fas fa-search text-sm" aria-hidden="true"></i>
                        </button>

                        <!-- Authentication Links -->
                        @auth
                            <div x-data="{ userMenuOpen: false }" class="relative hidden md:block">
                                <button type="button" @click="userMenuOpen = !userMenuOpen"
                                    class="flex h-10 items-center gap-2 rounded-lg border border-slate-200 px-2 text-glow-ink transition hover:border-orange-200 hover:bg-orange-50"
                                    :aria-expanded="userMenuOpen.toString()" aria-label="Open account menu">
                                    <div class="flex h-7 w-7 items-center justify-center rounded-md bg-glow-midnight">
                                        <i class="fas fa-user text-xs text-white" aria-hidden="true"></i>
                                    </div>
                                    <span class="hidden max-w-24 truncate text-xs font-bold xl:inline">{{ auth()->user()->name }}</span>
                                    <i class="fas fa-chevron-down text-[9px]" aria-hidden="true"></i>
                                </button>

                                <div x-cloak x-show="userMenuOpen" @click.away="userMenuOpen = false"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute right-0 z-[95] mt-2 w-52 rounded-xl border border-slate-200 bg-white p-2 shadow-[0_20px_55px_rgba(7,22,47,0.16)]">
                                    @if($canAccessDashboard)
                                        <a href="{{ route('dashboard') }}"
                                            class="block rounded-lg px-3 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-orange-50 hover:text-glow-orange">
                                            <i class="fas fa-tachometer-alt mr-2 text-xs" aria-hidden="true"></i>Dashboard
                                        </a>
                                        <div class="my-1 border-t border-slate-100"></div>
                                    @endif

                                    <a href="{{ route('profile') }}"
                                        class="block rounded-lg px-3 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-50 hover:text-glow-orange">
                                        <i class="fas fa-user-circle mr-2 text-xs" aria-hidden="true"></i>My Profile
                                    </a>
                                    <a href="{{ route('settings') }}"
                                        class="block rounded-lg px-3 py-2.5 text-sm font-bold text-slate-700 transition hover:bg-slate-50 hover:text-glow-orange">
                                        <i class="fas fa-cog mr-2 text-xs" aria-hidden="true"></i>Settings
                                    </a>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="mt-1 block w-full rounded-lg px-3 py-2.5 text-left text-sm font-bold text-red-600 transition hover:bg-red-50">
                                            <i class="fas fa-sign-out-alt mr-2 text-xs" aria-hidden="true"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="hidden items-center md:flex">
                                <a href="{{ route('login') }}"
                                    class="px-2 py-2 text-sm font-bold text-glow-ink transition hover:text-glow-orange">
                                    Login
                                </a>
                                <a href="{{ route('register') }}"
                                    class="ml-1 hidden rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold text-glow-ink transition hover:border-orange-200 hover:text-glow-orange xl:inline-flex">
                                    Sign up
                                </a>
                            </div>
                        @endauth

                        <!-- Listen Live Button -->
                        <button type="button" @click="startLive"
                            class="hidden h-10 items-center gap-2 rounded-lg bg-glow-orange px-4 text-sm font-extrabold text-white shadow-[0_10px_25px_rgba(242,106,46,0.22)] transition hover:bg-glow-coral lg:flex">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white text-[9px] text-glow-orange">
                                <i x-show="!$store.radio.audioPlaying" class="fas fa-play" aria-hidden="true"></i>
                                <i x-cloak x-show="$store.radio.audioPlaying" class="fas fa-pause" aria-hidden="true"></i>
                            </span>
                            <span x-text="$store.radio.audioPlaying ? 'Listening' : 'Listen live'">Listen live</span>
                        </button>

                        <!-- Mobile Menu Button -->
                        <button type="button" @click="mobileMenuOpen = !mobileMenuOpen; searchOpen = false"
                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-glow-midnight text-white transition hover:bg-glow-navy lg:hidden"
                            :aria-expanded="mobileMenuOpen.toString()" aria-label="Open site menu">
                            <i x-show="!mobileMenuOpen" class="fas fa-bars text-sm" aria-hidden="true"></i>
                            <i x-cloak x-show="mobileMenuOpen" class="fas fa-times text-sm" aria-hidden="true"></i>
                        </button>
                    </div>
                </nav>

            </div>
        </div>

        <!-- Search Bar (Dropdown) -->
        <div x-cloak x-show="searchOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="absolute inset-x-0 top-full z-[90] border-b border-slate-200 bg-white shadow-[0_20px_45px_rgba(7,22,47,0.12)]"
            @click.away="searchOpen = false">
            <form action="{{ route('news') }}" method="GET" class="mx-auto max-w-3xl px-4 py-5 sm:px-5 lg:py-6"
                role="search">
                <label for="global-news-search" class="public-kicker">Search the newsroom</label>
                <div class="relative mt-2">
                    <input id="global-news-search" type="search" name="searchQuery"
                        value="{{ request('searchQuery') }}" placeholder="Search stories, topics and people"
                        class="h-12 w-full rounded-lg border border-slate-300 bg-white px-4 pr-14 text-sm text-glow-ink placeholder:text-slate-400 focus:border-glow-orange focus:outline-none focus:ring-4 focus:ring-orange-100">
                    <button type="submit" aria-label="Submit search"
                        class="absolute right-1.5 top-1/2 flex h-9 w-10 -translate-y-1/2 items-center justify-center rounded-md bg-glow-orange text-white transition hover:bg-glow-coral">
                        <i class="fas fa-search text-sm" aria-hidden="true"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Mobile Menu -->
        <div x-cloak x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="fixed inset-x-0 bottom-[calc(env(safe-area-inset-bottom)+5.25rem)] top-[calc(env(safe-area-inset-top)+4.5rem)] z-[70] overflow-y-auto border-t border-slate-200 bg-white px-5 pb-8 pt-5 shadow-[0_24px_70px_rgba(7,22,47,0.18)] lg:hidden">
            <div class="mx-auto max-w-lg space-y-6">
                <div class="flex items-center gap-3 rounded-xl bg-glow-midnight p-3.5 text-white">
                    <button type="button" @click="toggleLive"
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-glow-orange text-sm text-white transition hover:bg-glow-coral"
                        :aria-label="$store.radio.audioPlaying ? 'Pause live radio' : 'Play live radio'">
                        <i class="fas" :class="$store.radio.audioPlaying ? 'fa-pause' : 'fa-play'" aria-hidden="true"></i>
                    </button>
                    <div class="min-w-0 flex-1">
                        <p class="flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-[0.18em] text-glow-amber">
                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> On air now
                        </p>
                        <p class="mt-1 truncate text-sm font-extrabold">{{ $currentProgramTitle }}</p>
                        <p class="mt-0.5 truncate text-[11px] text-slate-300">{{ $currentProgramHost }} · {{ $currentProgramTime }} WAT</p>
                    </div>
                    <a href="{{ route('listen.live') }}" @click="closeMobileChrome()"
                        class="text-xs font-bold text-white transition hover:text-glow-amber">
                        Details
                    </a>
                </div>

                <div>
                    <p class="public-kicker mb-2">Main menu</p>
                    <nav class="grid grid-cols-2 gap-x-5">
                        @foreach ($primaryNavigation as $navItem)
                            <a href="{{ $navItem['href'] }}" @click="closeMobileChrome()"
                                @if ($navItem['active']) aria-current="page" @endif
                                class="public-menu-link {{ $navItem['active'] ? 'text-glow-orange' : '' }}">
                                {{ $navItem['label'] }}
                                <i class="fas fa-arrow-up-right-from-square text-[9px] text-slate-400" aria-hidden="true"></i>
                            </a>
                        @endforeach
                    </nav>
                </div>
                <div>
                    <p class="public-kicker mb-2">Explore</p>
                    <nav class="grid grid-cols-2 gap-x-5">
                        @foreach ($exploreNavigation as $navItem)
                            <a href="{{ $navItem['href'] }}" @click="closeMobileChrome()"
                                @if ($navItem['active']) aria-current="page" @endif
                                class="public-menu-link {{ $navItem['active'] ? 'text-glow-orange' : '' }}">
                                <span class="flex min-w-0 items-center gap-2.5">
                                    <i class="{{ $navItem['icon'] }} w-4 shrink-0 text-center text-[11px] text-glow-orange" aria-hidden="true"></i>
                                    <span class="truncate">{{ $navItem['label'] }}</span>
                                </span>
                            </a>
                        @endforeach
                    </nav>
                </div>

                @auth
                    <div class="grid grid-cols-2 gap-2 border-t border-slate-200 pt-5">
                        @if($canAccessDashboard)
                            <a href="{{ route('dashboard') }}" @click="closeMobileChrome()"
                                class="rounded-lg bg-glow-midnight px-4 py-3 text-center text-sm font-bold text-white transition hover:bg-glow-navy">
                                <i class="fas fa-tachometer-alt mr-2 text-xs" aria-hidden="true"></i>Dashboard
                            </a>
                        @endif
                        <a href="{{ route('profile') }}" @click="closeMobileChrome()"
                            class="rounded-lg border border-slate-200 px-4 py-3 text-center text-sm font-bold text-glow-ink transition hover:border-orange-200 hover:text-glow-orange">
                            <i class="fas fa-user-circle mr-2 text-xs" aria-hidden="true"></i>Profile
                        </a>
                        <a href="{{ route('settings') }}" @click="closeMobileChrome()"
                            class="rounded-lg border border-slate-200 px-4 py-3 text-center text-sm font-bold text-glow-ink transition hover:border-orange-200 hover:text-glow-orange">
                            <i class="fas fa-cog mr-2 text-xs" aria-hidden="true"></i>Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full rounded-lg border border-red-200 px-4 py-3 text-center text-sm font-bold text-red-600 transition hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-2 text-xs" aria-hidden="true"></i>Logout
                            </button>
                        </form>
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-2 border-t border-slate-200 pt-5">
                        <a href="{{ route('login') }}" @click="closeMobileChrome()"
                            class="rounded-lg border border-slate-200 px-4 py-3 text-center text-sm font-bold text-glow-ink transition hover:border-orange-200 hover:text-glow-orange">
                            <i class="fas fa-right-to-bracket mr-2 text-xs" aria-hidden="true"></i>Login
                        </a>
                        <a href="{{ route('register') }}" @click="closeMobileChrome()"
                            class="rounded-lg bg-glow-orange px-4 py-3 text-center text-sm font-bold text-white transition hover:bg-glow-coral">
                            <i class="fas fa-user-plus mr-2 text-xs" aria-hidden="true"></i>Sign up
                        </a>
                    </div>
                @endauth

                <button type="button" x-cloak x-show="canInstallApp && !appInstalled" @click="installApp"
                    :disabled="installInProgress"
                    class="flex w-full items-center justify-center gap-2 rounded-lg border border-slate-200 px-4 py-3 text-sm font-bold text-glow-ink transition hover:border-orange-200 hover:bg-orange-50 disabled:opacity-50">
                    <i class="fas fa-download text-xs text-glow-orange" aria-hidden="true"></i>
                    <span x-text="installInProgress ? 'Installing Glow FM...' : 'Install the Glow FM app'"></span>
                </button>
            </div>
        </div>
    </header>

    <!-- Spacer for Fixed Header -->
    <div class="h-[calc(env(safe-area-inset-top)+4.5rem)] lg:h-[6.75rem]"></div>

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
        <div class="mobile-app-surface mx-3 rounded-xl border border-green-200/80 bg-green-50/95 text-green-700 flash-auto-dismiss lg:mx-0 lg:rounded-none lg:border-x-0">
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

    <nav aria-label="Mobile primary navigation"
        class="fixed inset-x-3 bottom-[calc(env(safe-area-inset-bottom)+0.5rem)] z-[75] mx-auto max-w-md rounded-xl border border-white/10 bg-glow-ink px-2 py-1.5 shadow-[0_20px_55px_rgba(7,22,47,0.3)] lg:hidden">
        <div class="grid grid-cols-5 items-end">
            <a href="{{ route('home') }}" @click="closeMobileChrome()"
                @if (request()->routeIs('home')) aria-current="page" @endif
                class="flex h-12 min-w-0 flex-col items-center justify-center gap-1 rounded-lg text-[9px] font-bold {{ request()->routeIs('home') ? 'text-glow-amber' : 'text-slate-300' }}">
                <i class="fas fa-house text-sm" aria-hidden="true"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('news') }}" @click="closeMobileChrome()"
                @if (request()->routeIs('news', 'news.show')) aria-current="page" @endif
                class="flex h-12 min-w-0 flex-col items-center justify-center gap-1 rounded-lg text-[9px] font-bold {{ request()->routeIs('news', 'news.show') ? 'text-glow-amber' : 'text-slate-300' }}">
                <i class="fas fa-newspaper text-sm" aria-hidden="true"></i>
                <span>News</span>
            </a>
            <button type="button" @click="toggleLive"
                class="-mt-5 flex min-w-0 flex-col items-center justify-end gap-1 text-[9px] font-extrabold text-white"
                :aria-label="$store.radio.audioPlaying ? 'Pause live radio' : 'Play live radio'">
                <span class="flex h-14 w-14 items-center justify-center rounded-full border-4 border-glow-ink bg-glow-orange text-base shadow-[0_10px_28px_rgba(242,106,46,0.38)] transition hover:bg-glow-coral">
                    <i x-show="!$store.radio.audioPlaying" class="fas fa-play" aria-hidden="true"></i>
                    <i x-cloak x-show="$store.radio.audioPlaying" class="fas fa-pause" aria-hidden="true"></i>
                </span>
                <span x-text="$store.radio.audioPlaying ? 'Playing' : 'Listen'">Listen</span>
            </button>
            <a href="{{ route('schedule') }}" @click="closeMobileChrome()"
                @if (request()->routeIs('schedule')) aria-current="page" @endif
                class="flex h-12 min-w-0 flex-col items-center justify-center gap-1 rounded-lg text-[9px] font-bold {{ request()->routeIs('schedule') ? 'text-glow-amber' : 'text-slate-300' }}">
                <i class="fas fa-calendar-days text-sm" aria-hidden="true"></i>
                <span>Schedule</span>
            </a>
            <button type="button" @click="mobileMenuOpen = !mobileMenuOpen; searchOpen = false"
                class="flex h-12 min-w-0 flex-col items-center justify-center gap-1 rounded-lg text-[9px] font-bold text-slate-300"
                :class="mobileMenuOpen ? '!text-glow-amber' : 'text-slate-300'"
                :aria-expanded="mobileMenuOpen.toString()" aria-label="Toggle site menu">
                <i x-show="!mobileMenuOpen" class="fas fa-bars text-sm" aria-hidden="true"></i>
                <i x-cloak x-show="mobileMenuOpen" class="fas fa-times text-sm" aria-hidden="true"></i>
                <span>Menu</span>
            </button>
        </div>
    </nav>

    <!-- Footer -->
    <footer class="public-site-footer mt-16 border-t-4 border-glow-orange bg-glow-ink pb-28 pt-14 text-white lg:mt-24 lg:pb-8 lg:pt-16"
        @click="closeMobileChrome()">
        <div class="mx-auto max-w-7xl px-5">
            <div class="mb-12 grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-12 lg:gap-8">

                <!-- About Section -->
                <div class="lg:col-span-4">
                    <div class="mb-6 flex items-center gap-3">
                        @if (!empty($stationLogoUrl))
                            <img src="{{ $stationLogoUrl }}" alt="{{ $stationName }} logo"
                                width="48" height="48" loading="lazy" decoding="async"
                                class="h-12 w-12 rounded-lg border border-white/10 bg-white object-contain p-0.5">
                        @else
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-glow-orange">
                                <i class="fas fa-radio text-xl text-white" aria-hidden="true"></i>
                            </div>
                        @endif
                        <div>
                            <h2 class="text-xl font-extrabold tracking-[-0.03em]">{{ $stationName }}</h2>
                            <p class="mt-0.5 text-[11px] font-extrabold uppercase tracking-[0.18em] text-glow-amber">
                                {{ $stationFrequency }} · Akure
                            </p>
                        </div>
                    </div>
                    <p class="mb-6 max-w-md text-sm leading-7 text-slate-300">
                        {{ $stationTagline }}. Broadcasting the heartbeat of the city of Akure with the best music, engaging
                        shows, and vibrant community connection.
                    </p>
                    <div class="flex items-center gap-2.5">
                        <x-public.social-link :href="data_get($stationSocials, 'facebook', '#')" label="Facebook"
                            icon="fab fa-facebook-f" />
                        <x-public.social-link :href="data_get($stationSocials, 'x', data_get($stationSocials, 'twitter', '#'))" label="X"
                            icon="fab fa-x-twitter" />
                        <x-public.social-link :href="data_get($stationSocials, 'instagram', '#')" label="Instagram"
                            icon="fab fa-instagram" />
                        <x-public.social-link :href="data_get($stationSocials, 'youtube', '#')" label="YouTube"
                            icon="fab fa-youtube" />
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="public-footer-nav lg:col-span-2">
                    <h2 class="mb-5 text-sm font-extrabold uppercase tracking-[0.16em] text-white">Explore</h2>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('about') }}"
                                class="public-footer-link flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                About Us
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('shows.index') }}"
                                class="public-footer-link flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Our Shows
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('schedule') }}"
                                class="public-footer-link flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Schedule
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('listen.live') }}"
                                class="public-footer-link flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Listen Live
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('podcasts.index') }}"
                                class="public-footer-link flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Our Podcasts
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('news') }}"
                                class="public-footer-link flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                News & Blog
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('contact') }}"
                                class="public-footer-link flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Contact Us
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('advertise') }}"
                                class="public-footer-link flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Advertise
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('vettas.index') }}"
                                class="public-footer-link flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Vettas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('careers.index') }}"
                                class="public-footer-link flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                Careers
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="lg:col-span-3">
                    <h2 class="mb-5 text-sm font-extrabold uppercase tracking-[0.16em] text-white">Contact</h2>
                    <ul class="space-y-4 text-sm">
                        <li class="flex items-start space-x-3">
                            <i class="fas fa-map-marker-alt mt-1 w-4 text-glow-orange" aria-hidden="true"></i>
                            <span class="leading-6 text-slate-300">
                                {{ $stationAddress }}
                            </span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-phone w-4 text-glow-orange" aria-hidden="true"></i>
                            <a href="tel:{{ $stationPhone }}" class="text-slate-300 transition hover:text-white">
                                {{ $stationPhone }}
                            </a>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-envelope w-4 text-glow-orange" aria-hidden="true"></i>
                            <a href="mailto:{{ $stationEmail }}"
                                class="break-all text-slate-300 transition hover:text-white">
                                {{ $stationEmail }}
                            </a>
                        </li>
                        <li class="flex items-start space-x-3">
                            <i class="fas fa-clock mt-1 w-4 text-glow-orange" aria-hidden="true"></i>
                            <span class="leading-6 text-slate-300">
                                24/7 Broadcasting<br>
                                Office: Mon-Fri, 9AM - 6PM
                            </span>
                        </li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div class="lg:col-span-3">
                    <h2 class="mb-5 text-sm font-extrabold uppercase tracking-[0.16em] text-white">Stay connected</h2>
                    <p class="mb-4 text-sm leading-6 text-slate-300">
                        Get the stories, shows and community updates that matter, straight from Glow FM.
                    </p>
                    <a href="{{ route('home') }}#newsletter"
                        class="inline-flex h-11 items-center gap-2 rounded-lg bg-glow-orange px-4 text-sm font-extrabold text-white transition hover:bg-glow-coral">
                        Get Glow updates
                        <i class="fas fa-arrow-right text-[10px]" aria-hidden="true"></i>
                    </a>

                    <!-- Recent Shows -->
                    <div class="mt-6 border-t border-white/10 pt-5">
                        <h3 class="mb-3 text-xs font-extrabold uppercase tracking-[0.14em] text-slate-300">Latest shows</h3>
                        <div class="space-y-2">
                            @forelse($recentShows as $show)
                                <a href="{{ route('shows.show', $show->slug) }}"
                                    class="flex items-center space-x-2 text-sm text-slate-300 transition hover:text-white">
                                    <i class="fas fa-microphone text-[10px] text-glow-orange" aria-hidden="true"></i>
                                    <span>{{ $show->title }}</span>
                                </a>
                            @empty
                                <span class="text-sm text-slate-500">No shows yet.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-white/10 pt-7">
                <div class="flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
                    <p class="text-xs text-slate-400">
                        &copy; {{ date('Y') }}
                        <a href="https://dayoebe.github.io" target="_blank" rel="noopener noreferrer"
                            class="transition hover:text-white">
                            {{ $stationName }} {{ $stationFrequency }}
                        </a>.
                        All rights reserved.
                    </p>
                    <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-xs">
                        <a href="{{ route('editorial.standards') }}" class="text-slate-400 transition hover:text-white">Editorial Standards</a>
                        <a href="{{ route('privacy.policy') }}" class="text-slate-400 transition hover:text-white">Privacy Policy</a>
                        <button type="button" @click="consentBannerOpen = true"
                            class="text-slate-400 transition hover:text-white">Cookie settings</button>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating Listen Live Player -->
    <div x-cloak x-show="$store.radio.playerOpen && !consentBannerOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-6"
        class="fixed bottom-5 right-5 z-[70] hidden w-[25rem] max-w-[calc(100vw-2.5rem)] lg:block">
        <div class="overflow-hidden rounded-xl border border-white/10 bg-glow-ink text-white shadow-[0_24px_70px_rgba(7,22,47,0.35)]">
            <div class="flex items-center gap-3 p-3.5">
                <button type="button" @click="toggleLive"
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-glow-orange text-sm text-white transition hover:bg-glow-coral"
                    :aria-label="$store.radio.audioPlaying ? 'Pause live radio' : 'Play live radio'">
                    <i x-show="!$store.radio.audioPlaying" class="fas fa-play" aria-hidden="true"></i>
                    <i x-cloak x-show="$store.radio.audioPlaying" class="fas fa-pause" aria-hidden="true"></i>
                </button>

                <div class="min-w-0 flex-1">
                    <p class="flex items-center gap-2 text-[9px] font-extrabold uppercase tracking-[0.18em] text-glow-amber">
                        <span class="relative flex h-1.5 w-1.5">
                            <span class="absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-60"
                                :class="$store.radio.audioPlaying ? 'animate-ping' : ''"></span>
                            <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-red-500"></span>
                        </span>
                        <span x-text="$store.radio.audioPlaying ? 'Streaming live' : 'On air now'">On air now</span>
                    </p>
                    <p class="mt-1 truncate text-sm font-extrabold">{{ $currentProgramTitle }}</p>
                    <p class="mt-0.5 truncate text-[11px] text-slate-300">{{ $streamTrackLabel ?: $streamStatusMessage }}</p>
                </div>

                <div class="flex h-6 items-end gap-0.5" x-show="$store.radio.audioPlaying" aria-hidden="true">
                    <span class="h-3 w-0.5 rounded-full bg-glow-orange animate-pulse"></span>
                    <span class="h-5 w-0.5 rounded-full bg-glow-amber animate-pulse"></span>
                    <span class="h-4 w-0.5 rounded-full bg-glow-coral animate-pulse"></span>
                </div>

                <button type="button" @click="$store.radio.closePlayer()"
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md text-slate-400 transition hover:bg-white/10 hover:text-white"
                    aria-label="Close player">
                    <i class="fas fa-times text-xs" aria-hidden="true"></i>
                </button>
            </div>

            <div class="flex items-center justify-between gap-3 border-t border-white/10 bg-white/[0.035] px-3.5 py-2.5">
                <div class="min-w-0 text-[10px] text-slate-400">
                    <span class="truncate">{{ $currentProgramHost }}</span>
                    <span class="mx-1 text-slate-600">·</span>
                    <span>{{ $currentProgramTime }} WAT</span>
                </div>
                <div class="flex shrink-0 items-center gap-3 text-[10px] font-bold">
                    <a href="{{ route('schedule') }}" class="text-slate-300 transition hover:text-white">Schedule</a>
                    <a href="{{ route('listen.live') }}"
                        class="inline-flex items-center gap-1.5 rounded-md bg-white/10 px-2.5 py-1.5 text-white transition hover:bg-white/15">
                        Full player <i class="fas fa-arrow-right text-[8px]" aria-hidden="true"></i>
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
        <div class="fixed inset-x-3 bottom-[calc(env(safe-area-inset-bottom)+6.5rem)] z-50 rounded-xl bg-glow-midnight px-6 py-3 text-white shadow-lg flash-auto-dismiss sm:left-4 sm:right-auto sm:max-w-sm lg:bottom-4">
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
        class="js-cookie-consent cookie-consent fixed inset-x-0 bottom-0 z-[76] pb-[calc(env(safe-area-inset-bottom)+6rem)] lg:pb-4">
        <div class="mx-auto max-w-3xl px-4">
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-[0_20px_55px_rgba(7,22,47,0.2)]">
                <div class="flex items-center justify-between gap-3 sm:gap-5">
                    <div class="min-w-0 flex-1">
                        <p class="cookie-consent__message text-sm font-bold text-glow-ink">
                            We use cookies to improve your Glow FM experience.
                        </p>
                        <a href="{{ route('privacy.policy') }}" class="mt-1 inline-block text-xs font-semibold text-glow-orange hover:text-glow-coral">
                            Read our privacy policy
                        </a>
                    </div>
                    <div class="shrink-0">
                        <button type="button" @click="setConsent('accept')"
                            class="js-cookie-consent-agree cookie-consent__agree flex h-10 cursor-pointer items-center justify-center rounded-lg bg-glow-orange px-4 text-sm font-extrabold text-white transition hover:bg-glow-coral sm:px-5">
                            {{ trans('Agree') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-livewire-scripts />
</body>

</html>
