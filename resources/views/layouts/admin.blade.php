<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $stationSettings = \App\Models\Setting::get('station', []);
        $stationName = data_get($stationSettings, 'name', 'Glow FM');
        $stationFrequency = data_get($stationSettings, 'frequency', '99.1 MHz');
        $stationLogoUrl = data_get($stationSettings, 'logo_url', '');
        if (!empty($stationLogoUrl) && !\Illuminate\Support\Str::startsWith($stationLogoUrl, ['http://', 'https://'])) {
            $stationLogoUrl = url($stationLogoUrl);
        }
    @endphp
    <title>{{ $title ?? ($stationName . ' - Admin Dashboard') }}</title>
    @if (!empty($stationLogoUrl))
        <link rel="icon" href="{{ $stationLogoUrl }}">
        <link rel="apple-touch-icon" href="{{ $stationLogoUrl }}">
    @endif
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>[x-cloak]{display:none!important;}</style>
    @livewireStyles
</head>
<body class="bg-gray-50 font-sans antialiased" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen bg-gray-50">
        <!-- Sidebar -->
        <aside 
            class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out flex flex-col lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            aria-label="Admin sidebar"
        >
            <!-- Brand -->
            <div class="flex items-center justify-between h-16 px-5 border-b border-gray-200 bg-emerald-600">
                <div class="flex items-center space-x-3">
                    @if (!empty($stationLogoUrl))
                        <img src="{{ $stationLogoUrl }}" alt="{{ $stationName }} logo"
                            class="w-10 h-10 rounded-lg object-contain bg-white shadow-sm p-1">
                    @else
                        <div class="flex items-center justify-center w-10 h-10 bg-white rounded-lg shadow-sm">
                            <i class="fas fa-radio text-emerald-600 text-xl"></i>
                        </div>
                    @endif
                    <div class="text-white leading-tight">
                        <h1 class="text-lg font-bold">{{ $stationName }}</h1>
                        <p class="text-xs text-emerald-100">{{ $stationFrequency }}</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-white hover:text-emerald-100">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Scrollable Menu -->
            <nav class="flex-1 min-h-0 overflow-auto px-4 py-6 space-y-6">
                @php
                    $user = auth()->user();
                @endphp
                @foreach(config('menu') as $section)
                    @php
                        $sectionRoles = $section['roles'] ?? [];
                        $showSection = empty($sectionRoles) || ($user && (
                            (method_exists($user, 'hasAnyRole') && $user->hasAnyRole($sectionRoles)) ||
                            (isset($user->role) && in_array($user->role, $sectionRoles, true))
                        ));
                    @endphp
                    @continue(!$showSection)
                    <div>
                        <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            {{ $section['group'] }}
                        </h3>
                        <div class="space-y-1">
                            @foreach($section['items'] as $item)
                                @php
                                    $itemRoles = $item['roles'] ?? [];
                                    $showItem = empty($itemRoles) || ($user && (
                                        (method_exists($user, 'hasAnyRole') && $user->hasAnyRole($itemRoles)) ||
                                        (isset($user->role) && in_array($user->role, $itemRoles, true))
                                    ));
                                @endphp
                                @continue(!$showItem)
                                @if(isset($item['children']))
                                    <details class="group">
                                        <summary class="flex items-center justify-between px-3 py-2.5 text-sm font-medium text-gray-700 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 transition-colors duration-150 cursor-pointer">
                                            <span class="flex items-center space-x-3">
                                                <i class="{{ $item['icon'] }} w-5 text-center"></i>
                                                <span>{{ $item['title'] }}</span>
                                            </span>
                                            <i class="fas fa-chevron-down text-xs transition-transform duration-200 group-open:rotate-180"></i>
                                        </summary>
                                        <div class="ml-8 mt-1 space-y-1">
                                            @foreach($item['children'] as $child)
                                                <a 
                                                    href="{{ $child['route'] === '#' ? '#' : route($child['route']) }}" 
                                                    class="block px-3 py-2 text-sm text-gray-600 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 transition-colors duration-150"
                                                >
                                                    {{ $child['title'] }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </details>
                                @else
                                    <a 
                                        href="{{ $item['route'] === '#' ? '#' : route($item['route']) }}" 
                                        class="flex items-center space-x-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-150
                                            {{ request()->routeIs($item['active']) ? 'bg-emerald-50 text-emerald-600' : 'text-gray-700 hover:bg-emerald-50 hover:text-emerald-600' }}"
                                    >
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
            <header class="flex items-center justify-between h-16 px-6 bg-white border-b border-gray-200 shadow-sm">
                <div class="flex items-center space-x-4">
                    <!-- Mobile menu button -->
                    <button 
                        @click="sidebarOpen = true" 
                        class="lg:hidden text-gray-600 hover:text-gray-900 focus:outline-none"
                    >
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <!-- Page Title -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">{{ $header ?? 'Dashboard' }}</h2>
                    </div>
                </div>

                <!-- Right Side: Search, Notifications, Profile -->
                <div class="flex items-center space-x-4">
                    
                    <!-- Search -->
                    <div class="hidden md:block relative">
                        <input 
                            type="text" 
                            placeholder="Search..." 
                            class="w-64 pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        >
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <!-- Notifications -->
                    <div x-data="{ open: false }" class="relative">
                        <button 
                            @click="open = !open"
                            class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors duration-150"
                        >
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- Notification Dropdown -->
                        <div 
                            x-show="open"
                            x-cloak
                            @click.away="open = false"
                            x-transition
                            class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
                        >
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <a href="#" class="flex items-start p-4 hover:bg-gray-50 transition-colors duration-150">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-music text-emerald-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm text-gray-900">New song request received</p>
                                        <p class="text-xs text-gray-500 mt-1">2 minutes ago</p>
                                    </div>
                                </a>
                                <a href="#" class="flex items-start p-4 hover:bg-gray-50 transition-colors duration-150">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-comment text-blue-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm text-gray-900">New listener message</p>
                                        <p class="text-xs text-gray-500 mt-1">15 minutes ago</p>
                                    </div>
                                </a>
                                <a href="#" class="flex items-start p-4 hover:bg-gray-50 transition-colors duration-150">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
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
                                <a href="#" class="block text-center text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                                    View all notifications
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- User Profile Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button 
                            @click="open = !open"
                            class="flex items-center space-x-3 p-2 hover:bg-gray-100 rounded-lg transition-colors duration-150"
                        >
                            <img 
                                src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin User') }}&background=10b981&color=fff" 
                                alt="Profile" 
                                class="w-8 h-8 rounded-full"
                            >
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'Admin User' }}</p>
                                <p class="text-xs text-gray-500">Administrator</p>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-gray-600"></i>
                        </button>

                        <!-- Profile Dropdown -->
                        <div 
                            x-show="open"
                            x-cloak
                            @click.away="open = false"
                            x-transition
                            class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
                        >
                            <div class="p-3 border-b border-gray-200">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'Admin User' }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ auth()->user()->email ?? 'admin@glowfm.com' }}</p>
                            </div>
                            <div class="py-2">
                                <a href="{{ route('admin.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-user w-5"></i>
                                    <span class="ml-2">My Profile</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-cog w-5"></i>
                                    <span class="ml-2">Settings</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-question-circle w-5"></i>
                                    <span class="ml-2">Help & Support</span>
                                </a>
                            </div>
                            <div class="border-t border-gray-200 py-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
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
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                @if (session()->has('error'))
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <p class="font-semibold">Please fix the errors below.</p>
                        <p class="mt-1">{{ $errors->first() }}</p>
                    </div>
                @endif
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div 
        x-show="sidebarOpen"
        x-cloak
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 lg:hidden"
        @click="sidebarOpen = false"
        aria-hidden="true"
    ></div>

    @livewireScripts
</body>
</html>
