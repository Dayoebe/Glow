<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f766e">
    @php
        $stationSettings = \App\Models\Setting::get('station', []);
        $stationName = data_get($stationSettings, 'name', 'Glow FM');
        $stationFrequency = data_get($stationSettings, 'frequency', '99.1 MHz');
        $stationLogoUrl = data_get($stationSettings, 'logo_url', '');
        if (empty($stationLogoUrl)) {
            $stationLogoUrl = asset('glowfm logo.jpeg');
        }
        if (!empty($stationLogoUrl) && !\Illuminate\Support\Str::startsWith($stationLogoUrl, ['http://', 'https://'])) {
            $stationLogoUrl = url($stationLogoUrl);
        }
        $stationStreamUrl = data_get($stationSettings, 'stream_url', 'https://stream-176.zeno.fm/mwam2yirv1pvv');
        $user = auth()->user();
        $isAdminUser =
            $user &&
            ((method_exists($user, 'hasRole') && $user->hasRole('admin')) ||
                (isset($user->role) && $user->role === 'admin'));

        $adminMobileNav = [
            [
                'label' => 'Home',
                'icon' => 'fas fa-house',
                'href' => route('dashboard'),
                'patterns' => ['dashboard'],
            ],
            [
                'label' => 'News',
                'icon' => 'fas fa-newspaper',
                'href' => route('admin.news.index'),
                'patterns' => ['admin.news.*'],
            ],
            [
                'label' => 'Podcast',
                'icon' => 'fas fa-podcast',
                'href' => route('admin.podcasts.manage'),
                'patterns' => ['admin.podcasts.*'],
            ],
            [
                'label' => 'All Shows',
                'icon' => 'fas fa-microphone',
                'href' => route($isAdminUser ? 'admin.shows.index' : 'shows.index'),
                'patterns' => $isAdminUser ? ['admin.shows.*'] : ['shows.*'],
            ],
        ];
    @endphp
    <title>{{ $title ?? $stationName . ' - Admin Dashboard' }}</title>
    @if (!empty($stationLogoUrl))
        <link rel="icon" href="{{ $stationLogoUrl }}">
        <link rel="apple-touch-icon" href="{{ $stationLogoUrl }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        [x-cloak] {
            display: none !important;
        }

        .flash-auto-dismiss {
            overflow: hidden;
            max-height: 200px;
            pointer-events: none;
            animation: flashAutoDismiss 5s ease forwards;
        }

        @keyframes flashAutoDismiss {

            0%,
            85% {
                opacity: 1;
                max-height: 200px;
            }

            100% {
                opacity: 0;
                max-height: 0;
                padding-top: 0;
                padding-bottom: 0;
                margin-top: 0;
                margin-bottom: 0;
                border-width: 0;
            }
        }
    </style>
    @livewireStyles
</head>

<body class="mobile-app-shell mobile-admin-shell overflow-x-hidden bg-slate-950 font-sans antialiased text-slate-900"
    x-data="{ sidebarOpen: false }" data-no-livewire-nav>
    <div class="min-h-screen bg-transparent lg:bg-gray-50">
        <!-- Sidebar -->
        <aside
            class="mobile-app-surface fixed left-3 top-[calc(env(safe-area-inset-top)+0.75rem)] bottom-[calc(env(safe-area-inset-bottom)+6.5rem)] z-50 flex w-[min(88vw,22rem)] flex-col overflow-hidden rounded-[2rem] border border-white/70 shadow-2xl transform transition-transform duration-300 ease-in-out lg:inset-y-0 lg:left-0 lg:top-0 lg:bottom-0 lg:w-72 lg:rounded-none lg:border-r lg:border-gray-200 lg:bg-white lg:shadow-none lg:backdrop-blur-none lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" aria-label="Admin sidebar">
            <!-- Brand -->
            <div
                class="flex items-center justify-between border-b border-white/70 bg-emerald-600 px-5 py-5 lg:h-16 lg:px-5 lg:py-0 lg:border-gray-200">
                <a href="{{ route('home') }}" class="group flex items-center space-x-3">
                    @if (!empty($stationLogoUrl))
                        <img src="{{ $stationLogoUrl }}" alt="{{ $stationName }} logo"
                            class="w-10 h-10 rounded-lg object-contain bg-white shadow-sm p-1 transition-transform duration-200 group-hover:scale-105">
                    @else
                        <div
                            class="flex items-center justify-center w-10 h-10 bg-white rounded-lg shadow-sm transition-transform duration-200 group-hover:scale-105">
                            <i class="fas fa-radio text-emerald-600 text-xl"></i>
                        </div>
                    @endif
                    <div class="text-white leading-tight">
                        <h1 class="text-lg font-bold group-hover:text-emerald-50">{{ $stationName }}</h1>
                        <p class="text-xs text-emerald-100">{{ $stationFrequency }}</p>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-white hover:text-emerald-100">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Scrollable Menu -->
            <nav class="flex-1 min-h-0 overflow-auto px-4 py-6 space-y-6">
                @php
                    $user = auth()->user();
                @endphp
                @foreach (config('menu') as $section)
                    @php
                        $sectionRoles = $section['roles'] ?? [];
                        $showSection =
                            empty($sectionRoles) ||
                            ($user &&
                                ((method_exists($user, 'hasAnyRole') && $user->hasAnyRole($sectionRoles)) ||
                                    (isset($user->role) && in_array($user->role, $sectionRoles, true))));
                    @endphp
                    @continue(!$showSection)
                    <div>
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            {{ $section['group'] }}
                        </h3>
                        <div class="space-y-1">
                            @foreach ($section['items'] as $item)
                                @php
                                    $itemRoles = $item['roles'] ?? [];
                                    $showItem =
                                        empty($itemRoles) ||
                                        ($user &&
                                            ((method_exists($user, 'hasAnyRole') && $user->hasAnyRole($itemRoles)) ||
                                                (isset($user->role) && in_array($user->role, $itemRoles, true))));
                                @endphp
                                @continue(!$showItem)
                                @if (isset($item['children']))
                                    <details class="group">
                                        <summary
                                            class="flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 transition-colors duration-150 cursor-pointer">
                                            <span class="flex items-center space-x-3">
                                                <i class="{{ $item['icon'] }} w-5 text-center"></i>
                                                <span>{{ $item['title'] }}</span>
                                            </span>
                                            <i
                                                class="fas fa-chevron-down text-xs transition-transform duration-200 group-open:rotate-180"></i>
                                        </summary>
                                        <div class="ml-8 mt-1 space-y-1">
                                            @foreach ($item['children'] as $child)
                                                <a href="{{ $child['route'] === '#' ? '#' : route($child['route']) }}"
                                                    class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 transition-colors duration-150">
                                                    {{ $child['title'] }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </details>
                                @else
                                    <a href="{{ $item['route'] === '#' ? '#' : route($item['route']) }}"
                                        class="flex items-center space-x-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150
                                            {{ request()->routeIs($item['active']) ? 'bg-emerald-50 text-emerald-600' : 'text-gray-700 hover:bg-emerald-50 hover:text-emerald-600' }}">
                                        <i class="{{ $item['icon'] }} w-5 text-center"></i>
                                        <span>{{ $item['title'] }}</span>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </nav>

            <!-- Footer -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center space-x-3 px-3 py-2 bg-emerald-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-emerald-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-broadcast-tower text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-emerald-900">Live Status</p>
                        <p class="text-xs text-emerald-600 flex items-center">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full mr-1.5 animate-pulse"></span>
                            On Air
                        </p>
                    </div>
                </div>
                <div class="mt-3 px-3 py-2 bg-gray-50 rounded-lg text-xs text-gray-600 flex items-center justify-between"
                    x-data="{
                        now: '',
                        init() {
                            const format = () => {
                                const d = new Date();
                                this.now = d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                            };
                            format();
                            setInterval(format, 1000);
                        }
                    }">
                    <span class="font-medium text-gray-700">Current Time</span>
                    <span class="tabular-nums" x-text="now"></span>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex flex-col min-h-screen lg:pl-72">

            <!-- Top Navigation Bar -->
            <header class="sticky top-0 z-30 px-3 pt-[calc(env(safe-area-inset-top)+0.75rem)] lg:px-6 lg:pt-0">
                <div
                    class="mobile-app-surface flex min-h-16 items-center justify-between rounded-[1.75rem] border border-white/70 px-4 py-3 shadow-xl lg:h-16 lg:rounded-none lg:border-b lg:border-x-0 lg:border-t-0 lg:border-gray-200 lg:bg-white lg:px-6 lg:py-0 lg:shadow-sm lg:backdrop-blur-none">
                    <div class="flex items-center space-x-3 lg:space-x-4">
                        <!-- Mobile menu button -->
                        <button @click="sidebarOpen = true"
                            class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900/5 text-gray-600 transition hover:bg-emerald-50 hover:text-emerald-700 focus:outline-none lg:hidden">
                            <i class="fas fa-bars text-lg"></i>
                        </button>

                        <!-- Page Title -->
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-emerald-700 lg:hidden">
                                Admin Workspace</p>
                            <h2 class="text-lg font-semibold text-gray-900 lg:text-xl">{{ $header ?? 'Dashboard' }}
                            </h2>
                            <p class="hidden text-xs text-gray-500 lg:block">{{ $stationName }}
                                {{ $stationFrequency }}</p>
                        </div>
                    </div>

                    <!-- Right Side: Search, Notifications, Profile -->
                    <div class="flex items-center space-x-2 lg:space-x-4">

                        <!-- Search -->
                        <div class="hidden md:block relative">
                            <input type="text" placeholder="Search..."
                                class="w-64 pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                            <i
                                class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>

                        <!-- Notifications -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="relative flex h-11 w-11 items-center justify-center rounded-2xl text-gray-600 transition-colors duration-150 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>

                            <!-- Notification Dropdown -->
                            <div x-show="open" x-cloak @click.away="open = false" x-transition
                                class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                <div class="p-4 border-b border-gray-200">
                                    <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <a href="#"
                                        class="flex items-start p-4 hover:bg-gray-50 transition-colors duration-150">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-music text-emerald-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm text-gray-900">New song request received</p>
                                            <p class="text-xs text-gray-500 mt-1">2 minutes ago</p>
                                        </div>
                                    </a>
                                    <a href="#"
                                        class="flex items-start p-4 hover:bg-gray-50 transition-colors duration-150">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-comment text-blue-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm text-gray-900">New listener message</p>
                                            <p class="text-xs text-gray-500 mt-1">15 minutes ago</p>
                                        </div>
                                    </a>
                                    <a href="#"
                                        class="flex items-start p-4 hover:bg-gray-50 transition-colors duration-150">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-broadcast-tower text-amber-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm text-gray-900">Stream status update</p>
                                            <p class="text-xs text-gray-500 mt-1">1 hour ago</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="p-3 border-t border-gray-200">
                                    <a href="#"
                                        class="block text-center text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                                        View all notifications
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- User Profile Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="flex items-center space-x-3 rounded-2xl p-2 transition-colors duration-150 hover:bg-gray-100">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin User') }}&background=10b981&color=fff"
                                    alt="Profile" class="w-8 h-8 rounded-full">
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ auth()->user()->name ?? 'Admin User' }}</p>
                                    <p class="text-xs text-gray-500">Administrator</p>
                                </div>
                                <i class="fas fa-chevron-down text-xs text-gray-600"></i>
                            </button>

                            <!-- Profile Dropdown -->
                            <div x-show="open" x-cloak @click.away="open = false" x-transition
                                class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                <div class="p-3 border-b border-gray-200">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ auth()->user()->name ?? 'Admin User' }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        {{ auth()->user()->email ?? 'admin@glowfm.com' }}</p>
                                </div>
                                <div class="py-2">
                                    <a href="{{ route('admin.profile') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-user w-5"></i>
                                        <span class="ml-2">My Profile</span>
                                    </a>
                                    <a href="#"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-cog w-5"></i>
                                        <span class="ml-2">Settings</span>
                                    </a>
                                    <a href="#"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-question-circle w-5"></i>
                                        <span class="ml-2">Help & Support</span>
                                    </a>
                                </div>
                                <div class="border-t border-gray-200 py-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            <i class="fas fa-sign-out-alt w-5"></i>
                                            <span class="ml-2">Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto px-3 pb-24 pt-4 lg:bg-gray-50 lg:p-6">
                <div class="mx-auto max-w-7xl">
                    @if (session()->has('error'))
                        <div
                            class="mobile-app-surface mb-4 rounded-2xl border border-red-200/80 bg-red-50/95 px-4 py-3 text-sm text-red-700 flash-auto-dismiss">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div
                            class="mobile-app-surface mb-4 rounded-2xl border border-red-200/80 bg-red-50/95 px-4 py-3 text-sm text-red-700">
                            <p class="font-semibold">Please fix the errors below.</p>
                            <p class="mt-1">{{ $errors->first() }}</p>
                        </div>
                    @endif
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <nav
        class="mobile-app-surface mobile-dock-shadow fixed inset-x-4 bottom-[calc(env(safe-area-inset-bottom)+0.5rem)] z-40 mx-auto max-w-md rounded-[1.4rem] border border-white/70 px-2 py-1.5 lg:hidden">
        <div class="grid grid-cols-4 gap-1">
            @foreach ($adminMobileNav as $navItem)
                <a href="{{ $navItem['href'] }}"
                    class="flex min-w-0 flex-col items-center justify-center rounded-xl px-1 py-1.5 text-[10px] font-semibold leading-none transition {{ request()->routeIs(...$navItem['patterns']) ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-900/5 hover:text-emerald-700' }}">
                    <i class="{{ $navItem['icon'] }} mb-0.5 text-xs"></i>
                    <span class="max-w-full truncate whitespace-nowrap">{{ $navItem['label'] }}</span>
                </a>
            @endforeach
        </div>
    </nav>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" x-cloak x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-slate-950/45 backdrop-blur-sm lg:hidden"
        @click="sidebarOpen = false" aria-hidden="true"></div>

    @persist('live-radio-audio')
        <audio x-init="$store.radio.bind($el)" src="{{ $stationStreamUrl }}" preload="none"></audio>
    @endpersist

    @livewireScripts
</body>

</html>
