<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Glow FM 99.1 - Your Voice, Your Music' }}</title>
    <meta name="description"
        content="Glow FM 99.1 - The heartbeat of the city. Listen to the best music, engaging shows, and stay connected with your community.">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @livewireStyles
</head>

<body class="bg-white font-sans antialiased" x-data="{ 
    mobileMenuOpen: false, 
    searchOpen: false,
    scrolled: false,
    playerOpen: true
}" x-init="
    window.addEventListener('scroll', () => {
        scrolled = window.pageYOffset > 20
    })
">

    <!-- Fixed Header -->
    <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
        :class="scrolled ? 'bg-white shadow-lg' : 'bg-white/95 backdrop-blur-sm'">
        <!-- Top Bar -->
        <div class="bg-slate-600 text-white">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between h-10 text-sm">
                    <div class="flex items-center space-x-6">
                        <a href="tel:+1234567890"
                            class="flex items-center space-x-2 hover:text-emerald-100 transition-colors">
                            <i class="fas fa-phone text-xs"></i>
                            <span class="hidden md:inline">+1 (234) 567-890</span>
                        </a>
                        <a href="mailto:info@glowfm.com"
                            class="flex items-center space-x-2 hover:text-emerald-100 transition-colors">
                            <i class="fas fa-envelope text-xs"></i>
                            <span class="hidden md:inline">info@glowfm.com</span>
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="hidden sm:flex items-center space-x-2 text-xs">
                            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                            <span class="font-medium">NOW PLAYING: Morning Vibes with MC Olumiko</span>
                        </span>
                        <div class="flex items-center space-x-3">
                            <a href="#" class="hover:text-emerald-100 transition-colors" aria-label="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="hover:text-emerald-100 transition-colors" aria-label="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="hover:text-emerald-100 transition-colors" aria-label="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="hover:text-emerald-100 transition-colors" aria-label="YouTube">
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
                        <h1 class="text-2xl font-bold text-gray-900 leading-none">Glow FM</h1>
                        <p class="text-xs font-semibold text-emerald-600">99.1 MHz</p>
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
                    <a href="/shows"
                        class="px-4 py-2 text-gray-700 font-medium hover:text-emerald-600 transition-colors duration-200 {{ request()->is('shows*') ? 'text-emerald-600' : '' }}">
                        Shows
                    </a>
                    <a href="/schedule"
                        class="px-4 py-2 text-gray-700 font-medium hover:text-emerald-600 transition-colors duration-200 {{ request()->is('schedule') ? 'text-emerald-600' : '' }}">
                        Schedule
                    </a>
                    <a href="/podcasts"
                        class="px-4 py-2 text-gray-700 font-medium hover:text-emerald-600 transition-colors duration-200 {{ request()->is('podcasts*') ? 'text-emerald-600' : '' }}">
                        Podcasts
                    </a>
                    <a href="/news"
                        class="px-4 py-2 text-gray-700 font-medium hover:text-emerald-600 transition-colors duration-200 {{ request()->is('news*') ? 'text-emerald-600' : '' }}">
                        News
                    </a>
                      <a href="/blog"
        class="px-4 py-2 text-gray-700 font-medium hover:text-emerald-600 transition-colors duration-200 {{ request()->is('blog*') ? 'text-emerald-600' : '' }}">
        Blog
    </a>
                    <a href="/events"
                        class="px-4 py-2 text-gray-700 font-medium hover:text-emerald-600 transition-colors duration-200 {{ request()->is('events*') ? 'text-emerald-600' : '' }}">
                        Events
                    </a>
                    <a href="/contact"
                        class="px-4 py-2 text-gray-700 font-medium hover:text-emerald-600 transition-colors duration-200 {{ request()->is('contact') ? 'text-emerald-600' : '' }}">
                        Contact
                    </a>
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
                    <a href="#"
                        class="hidden md:flex items-center space-x-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-play-circle text-xl"></i>
                        <span>Listen Live</span>
                    </a>

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
                <a href="/shows"
                    class="block px-4 py-3 text-gray-700 font-medium hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors">
                    Shows
                </a>
                <a href="/schedule"
                    class="block px-4 py-3 text-gray-700 font-medium hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors">
                    Schedule
                </a>
                <a href="/podcasts"
                    class="block px-4 py-3 text-gray-700 font-medium hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors">
                    Podcasts
                </a>
                <a href="/news"
                    class="block px-4 py-3 text-gray-700 font-medium hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors">
                    News
                </a>
                <a href="/blog"
            class="block px-4 py-3 text-gray-700 font-medium hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors">
            Blog
        </a>
                <a href="/events"
                    class="block px-4 py-3 text-gray-700 font-medium hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors">
                    Events
                </a>
                <a href="/contact"
                    class="block px-4 py-3 text-gray-700 font-medium hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors">
                    Contact
                </a>
                <div class="pt-4">
                    <button @click="searchOpen = !searchOpen"
                        class="w-full px-4 py-3 text-gray-700 font-medium hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition-colors text-left">
                        <i class="fas fa-search mr-2"></i> Search
                    </button>
                </div>
                <a href="#"
                    class="block mt-4 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-full text-center shadow-lg">
                    <i class="fas fa-play-circle mr-2"></i> Listen Live
                </a>
            </div>
        </div>
    </header>

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
                            <h3 class="text-xl font-bold">Glow FM</h3>
                            <p class="text-sm text-emerald-400">99.1 MHz</p>
                        </div>
                    </div>
                    <p class="text-gray-400 mb-6 leading-relaxed">
                        Your voice, your music. Broadcasting the heartbeat of the city of Akure with the best music, engaging
                        shows, and vibrant community connection.
                    </p>
                    <div class="flex items-center space-x-3">
                        <a href="#"
                            class="w-10 h-10 bg-gray-800 hover:bg-emerald-600 rounded-lg flex items-center justify-center transition-colors duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-gray-800 hover:bg-emerald-600 rounded-lg flex items-center justify-center transition-colors duration-300">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-gray-800 hover:bg-emerald-600 rounded-lg flex items-center justify-center transition-colors duration-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#"
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
                                123 Radio Street, <br>
                                Broadcasting City, BC 12345
                            </span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-phone text-emerald-400"></i>
                            <a href="tel:+1234567890" class="text-gray-400 hover:text-emerald-400 transition-colors">
                                +1 (234) 567-890
                            </a>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-emerald-400"></i>
                            <a href="mailto:info@glowfm.com"
                                class="text-gray-400 hover:text-emerald-400 transition-colors">
                                info@glowfm.com
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
                    <form class="space-y-3">
                        <input type="email" placeholder="Your email address"
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
                        &copy; {{ date('Y') }} Glow FM 99.1. All rights reserved.
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
                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    <span class="text-sm font-semibold">LIVE NOW</span>
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
                        <h4 class="font-semibold text-sm truncate">Blinding Lights</h4>
                        <p class="text-xs text-gray-400 truncate">The Weeknd</p>
                        <div class="flex items-center space-x-2 mt-1">
                            <i class="fas fa-microphone text-emerald-400 text-xs"></i>
                            <span class="text-xs text-gray-400">MC Olumiko - Morning Vibes</span>
                        </div>
                    </div>
                </div>

                <!-- Controls -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <button
                            class="w-10 h-10 bg-emerald-600 hover:bg-emerald-700 rounded-full flex items-center justify-center transition-colors">
                            <i class="fas fa-play text-white"></i>
                        </button>
                        <button
                            class="w-8 h-8 bg-gray-800 hover:bg-gray-700 rounded-full flex items-center justify-center transition-colors">
                            <i class="fas fa-volume-up text-white text-sm"></i>
                        </button>
                    </div>
                    <a href="#"
                        class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white text-xs font-medium rounded-lg transition-colors">
                        Full Player
                    </a>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
</body>

</html>