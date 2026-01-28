<div>
    @php
        $glowPosterName = 'Glow FM';
        $glowPosterLogo = asset('glowfm logo.jpeg');
    @endphp
    <!-- Page Header -->
    <section
        class="relative bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0"
                style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
            </div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6">News & Updates</h1>
                <p class="text-xl md:text-2xl text-emerald-100 leading-relaxed">
                    Stay informed with the latest news, stories, and updates from Glow FM and the music world
                </p>
            </div>
        </div>
    </section>

    <!-- Featured News -->
    @if($featuredHero)
        <section class="py-12 bg-white">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Hero -->
                    <div class="lg:col-span-2 bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl overflow-hidden shadow-2xl">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                            <div class="relative h-80 lg:h-auto">
                                <a href="{{ route('news.show', $featuredHero['slug']) }}">
                                    <x-initials-image
                                        :src="$featuredHero['featured_image'] ?? null"
                                        :title="$featuredHero['title'] ?? ''"
                                        imgClass="w-full h-full object-cover"
                                        fallbackClass="bg-emerald-700/90"
                                        textClass="text-4xl font-bold text-white"
                                    />
                                </a>
                                <div class="absolute top-6 left-6">
                                    <span class="px-4 py-2 bg-emerald-600 text-white text-sm font-bold rounded-full shadow-lg">
                                        <i class="fas fa-star mr-1"></i> HERO
                                    </span>
                                </div>
                            </div>
                            <div class="p-8 lg:p-10 text-white flex flex-col justify-center">
                                <div class="flex items-center space-x-4 mb-4">
                                    <span class="px-3 py-1 bg-emerald-600 text-white text-xs font-semibold rounded-full">
                                        {{ $featuredHero['category']['name'] }}
                                    </span>
                                    <span class="text-emerald-300 text-sm">
                                        <i class="fas fa-clock mr-1"></i> {{ $featuredHero['read_time'] }}
                                    </span>
                                    <span class="text-emerald-300 text-sm">
                                        <i class="fas fa-eye mr-1"></i> {{ number_format($featuredHero['views']) }} views
                                    </span>
                                </div>
                                <h2 class="text-3xl lg:text-4xl font-bold mb-4 leading-tight">
                                    <a href="{{ route('news.show', $featuredHero['slug']) }}" class="hover:text-emerald-200 transition-colors">
                                        {{ $featuredHero['title'] }}
                                    </a>
                                </h2>
                                <p class="text-gray-300 text-lg mb-6 leading-relaxed">{{ $featuredHero['excerpt'] }}</p>
                                <a href="/news/{{ $featuredHero['slug'] }}"
                                    class="inline-flex items-center space-x-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-full transition-all duration-300 w-fit">
                                    <span>Read Full Story</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Secondary -->
                    <div class="space-y-6">
                        @foreach($featuredSecondary as $secondary)
                            <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group border border-gray-100">
                                <div class="relative h-44 overflow-hidden">
                                    <a href="{{ route('news.show', $secondary['slug']) }}">
                                        <x-initials-image
                                            :src="$secondary['featured_image'] ?? null"
                                            :title="$secondary['title'] ?? ''"
                                            imgClass="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                                            fallbackClass="bg-emerald-700/90"
                                            textClass="text-3xl font-bold text-white"
                                        />
                                    </a>
                                    <div class="absolute top-3 left-3">
                                        <span class="px-3 py-1 bg-emerald-600 text-white text-xs font-semibold rounded-full">
                                            SECONDARY
                                        </span>
                                    </div>
                                </div>
                                <div class="p-5">
                                    <div class="flex items-center space-x-3 text-xs text-gray-500 mb-2">
                                        <span class="px-2 py-1 bg-emerald-50 text-emerald-700 rounded-full">
                                            {{ $secondary['category']['name'] }}
                                        </span>
                                        <span><i class="fas fa-clock mr-1"></i>{{ $secondary['read_time'] }}</span>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                        <a href="{{ route('news.show', $secondary['slug']) }}" class="hover:text-emerald-600 transition-colors">
                                            {{ $secondary['title'] }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $secondary['excerpt'] }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Main Content Area -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <x-ad-slot placement="news" />
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                <!-- Sidebar -->
                <aside class="lg:col-span-1 space-y-8">

                    <!-- Search -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-search text-emerald-600 mr-2"></i>
                            Search News
                        </h3>
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.500ms="searchQuery"
                                placeholder="Search articles..."
                                class="w-full px-4 py-3 pr-10 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-emerald-500 transition-colors">
                            <i
                                class="fas fa-search absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-folder text-emerald-600 mr-2"></i>
                            Categories
                        </h3>
                        <div class="space-y-2">
                            @foreach($categories as $category)
                                <button wire:click="$set('selectedCategory', '{{ $category['slug'] }}')"
                                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $selectedCategory === $category['slug'] ? 'bg-emerald-50 text-emerald-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span class="flex items-center space-x-2">
                                        <i class="{{ $category['icon'] }} text-{{ $category['color'] }}-600"></i>
                                        <span>{{ $category['name'] }}</span>
                                    </span>
                                    <span class="text-sm bg-gray-100 px-2 py-1 rounded-full">{{ $category['count'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    @if(count($featuredSidebar) > 0)
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-star text-emerald-600 mr-2"></i>
                                Featured Picks
                            </h3>
                            <div class="space-y-4">
                                @foreach($featuredSidebar as $sidebarItem)
                                    <a href="{{ route('news.show', $sidebarItem['slug']) }}" class="flex items-start space-x-3 group">
                                        <div class="relative w-16 h-16 rounded-lg overflow-hidden flex-shrink-0">
                                            <x-initials-image
                                                :src="$sidebarItem['featured_image'] ?? null"
                                                :title="$sidebarItem['title'] ?? ''"
                                                imgClass="w-full h-full object-cover"
                                                fallbackClass="bg-emerald-700/90"
                                                textClass="text-xl font-bold text-white"
                                            />
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-sm font-semibold text-gray-900 group-hover:text-emerald-600 transition-colors line-clamp-2">
                                                {{ $sidebarItem['title'] }}
                                            </h4>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <i class="fas fa-clock mr-1"></i> {{ $sidebarItem['read_time'] }}
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Trending News -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-fire text-red-600 mr-2"></i>
                            Trending Now
                        </h3>
                        <div class="space-y-4">
                            @foreach($trendingNews as $index => $trending)
                                <a href="/news/{{ $trending['id'] }}" class="flex items-start space-x-3 group">
                                    <span
                                        class="flex-shrink-0 w-8 h-8 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center font-bold text-sm">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="flex-1">
                                        <h4
                                            class="text-sm font-semibold text-gray-900 group-hover:text-emerald-600 transition-colors line-clamp-2">
                                            {{ $trending['title'] }}
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-eye mr-1"></i> {{ number_format($trending['views']) }} views
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Popular Tags -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-tags text-emerald-600 mr-2"></i>
                            Popular Tags
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($popularTags as $tag)
                                <a href="#"
                                    class="px-3 py-1.5 bg-gray-100 hover:bg-emerald-100 text-gray-700 hover:text-emerald-700 text-sm rounded-full transition-colors">
                                    #{{ $tag }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Newsletter Signup -->
                    <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-2xl shadow-lg p-6 text-white">
                        <div class="text-center">
                            <i class="fas fa-envelope-open-text text-4xl mb-4"></i>
                            <h3 class="text-xl font-bold mb-2">Stay Updated</h3>
                            <p class="text-emerald-100 text-sm mb-4">
                                Subscribe to get the latest news delivered to your inbox
                            </p>
                            <form class="space-y-3">
                                <input type="email" placeholder="Your email"
                                    class="w-full px-4 py-2 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-white">
                                <button
                                    class="w-full px-4 py-2 bg-white text-emerald-600 font-semibold rounded-lg hover:bg-emerald-50 transition-colors">
                                    Subscribe
                                </button>
                            </form>
                        </div>
                    </div>

                </aside>

                <!-- News Grid -->
                <div class="lg:col-span-3">

                    <!-- Filter Info -->
                    <div
                        class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-4 sm:space-y-0">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">
                                @if($selectedCategory === 'all')
                                    All News
                                @else
                                    {{ collect($categories)->firstWhere('slug', $selectedCategory)['name'] ?? 'News' }}
                                @endif
                            </h2>
                            <p class="text-gray-600 mt-1">{{ $newsArticles->total() }} articles found</p>
                        </div>

                        @if($searchQuery || $selectedCategory !== 'all')
                            <button wire:click="$set('searchQuery', ''); $set('selectedCategory', 'all')"
                                class="flex items-center space-x-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors">
                                <i class="fas fa-times"></i>
                                <span>Clear Filters</span>
                            </button>
                        @endif
                    </div>

                    <!-- News Articles Grid -->
                    @if(count($newsArticles) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            @foreach($newsArticles as $article)
                                <article
                                    class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group">
                                    <!-- Image -->
                                    <div class="relative h-56 overflow-hidden">
                                        <a href="{{ route('news.show', $article['slug']) }}">
                                            <x-initials-image
                                                :src="$article['featured_image'] ?? null"
                                                :title="$article['title'] ?? ''"
                                                imgClass="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                                                fallbackClass="bg-emerald-700/90"
                                                textClass="text-3xl font-bold text-white"
                                            />
                                        </a>
                                        <div class="absolute top-4 left-4">
                                            <span
                                                class="px-3 py-1 bg-{{ $article['category']['slug'] === 'station-news' ? 'blue' : ($article['category']['slug'] === 'music' ? 'purple' : ($article['category']['slug'] === 'interviews' ? 'amber' : 'pink')) }}-600 text-white text-xs font-semibold rounded-full">
                                                {{ $article['category']['name'] }}
                                            </span>
                                        </div>
                                        <div class="absolute bottom-4 right-4 flex items-center space-x-2">
                                            <span class="px-2 py-1 bg-black/70 text-white text-xs rounded-full">
                                                <i class="fas fa-eye mr-1"></i> {{ number_format($article['views']) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-6">
                                        <!-- Meta Info -->
                                        <div class="flex items-center space-x-4 text-sm text-gray-500 mb-3">
                                            <span class="flex items-center space-x-1">
                                                <i class="fas fa-calendar text-xs"></i>
                                                <span>{{ \Carbon\Carbon::parse($article['published_at'])->format('M d, Y') }}</span>
                                            </span>
                                            <span class="flex items-center space-x-1">
                                                <i class="fas fa-clock text-xs"></i>
                                                <span>{{ $article['read_time'] }}</span>
                                            </span>
                                        </div>

                                        <!-- Title -->
                                        <h3
                                            class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-emerald-600 transition-colors">
                                            <a href="{{ route('news.show', $article['slug']) }}">{{ $article['title'] }}</a>
                                        </h3>

                                        <!-- Excerpt -->
                                        <p class="text-gray-600 mb-4 line-clamp-3">{{ $article['excerpt'] }}</p>

                                        <!-- Author & Actions -->
                                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                            <div class="flex items-center space-x-3">
                                                <a href="{{ url('/') }}">
                                                    <img src="{{ $glowPosterLogo }}"
                                                        alt="{{ $glowPosterName }}" class="w-10 h-10 rounded-full object-cover">
                                                </a>
                                                <div>
                                                    <a href="{{ url('/') }}" class="text-sm font-semibold text-gray-900 hover:text-emerald-600 transition-colors">
                                                        {{ $glowPosterName }}</a>
                                                    <p class="text-xs text-gray-500">{{ $glowPosterName }}</p>
                                                </div>
                                            </div>

                                            <div class="flex items-center space-x-3 text-gray-500">
                                                <button class="hover:text-red-500 transition-colors">
                                                    <i class="fas fa-heart"></i>
                                                    <span class="text-xs ml-1">{{ $article['likes'] }}</span>
                                                </button>
                                                <button class="hover:text-emerald-600 transition-colors">
                                                    <i class="fas fa-comment"></i>
                                                    <span class="text-xs ml-1">{{ $article['comments_count'] }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <!-- Pagination Placeholder -->
                        <div class="mt-12 flex justify-center">
                            {{ $newsArticles->links() }}
                        </div>
                    @else
                        <!-- No Results -->
                        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                            <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">No articles found</h3>
                            <p class="text-gray-600 mb-6">Try adjusting your search or filters</p>
                            <button wire:click="$set('searchQuery', ''); $set('selectedCategory', 'all')"
                                class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-full transition-colors">
                                Clear All Filters
                            </button>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>

    <!-- Newsletter CTA -->
    <section class="py-20 bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <i class="fas fa-newspaper text-6xl mb-6 opacity-80"></i>
                <h2 class="text-4xl md:text-5xl font-bold mb-6">Never Miss an Update</h2>
                <p class="text-xl text-emerald-100 mb-8 leading-relaxed">
                    Subscribe to our newsletter and get the latest news, interviews, and exclusive content delivered
                    straight to your inbox.
                </p>
                <form
                    class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-4 max-w-xl mx-auto">
                    <input type="email" placeholder="Enter your email address"
                        class="w-full sm:flex-1 px-6 py-4 rounded-full text-gray-900 focus:outline-none focus:ring-4 focus:ring-emerald-300">
                    <button
                        class="w-full sm:w-auto px-8 py-4 bg-white text-emerald-600 font-bold rounded-full hover:bg-emerald-50 transition-colors shadow-lg">
                        Subscribe Now
                    </button>
                </form>
                <p class="text-sm text-emerald-200 mt-4">
                    <i class="fas fa-lock mr-1"></i> We respect your privacy. Unsubscribe anytime.
                </p>
            </div>
        </div>
    </section>
</div>
