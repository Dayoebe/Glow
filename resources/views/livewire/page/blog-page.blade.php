<div>
    <!-- Page Header -->
    <section
        class="relative bg-gradient-to-br from-purple-600 via-purple-700 to-purple-800 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0"
                style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
            </div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <x-ad-slot placement="blog" />
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6">Blog & Articles</h1>
                <p class="text-xl md:text-2xl text-purple-100 leading-relaxed">
                    Deep insights, stories, and articles from Glow FM. Your source for music knowledge and entertainment wisdom.
                </p>
            </div>
        </div>
    </section>

    <!-- Featured Post -->
    @if($featuredPost)
        <section class="py-12 bg-white">
            <div class="container mx-auto px-4">
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl overflow-hidden shadow-2xl">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                        <!-- Image -->
                        <div class="relative h-96 lg:h-auto">
                            <a href="{{ route('blog.show', $featuredPost['slug']) }}">
                                <img src="{{ $featuredPost['featured_image'] }}" alt="{{ $featuredPost['title'] }}"
                                    class="w-full h-full object-cover">
                            </a>
                            <div class="absolute top-6 left-6">
                                <span class="px-4 py-2 bg-purple-600 text-white text-sm font-bold rounded-full shadow-lg">
                                    <i class="fas fa-star mr-1"></i> FEATURED
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-8 lg:p-12 text-white flex flex-col justify-center">
                            <div class="flex items-center space-x-4 mb-4">
                                <span class="px-3 py-1 bg-purple-600 text-white text-xs font-semibold rounded-full">
                                    {{ $featuredPost['category']['name'] }}
                                </span>
                                <span class="text-purple-300 text-sm">
                                    <i class="fas fa-clock mr-1"></i> {{ $featuredPost['read_time'] }}
                                </span>
                                <span class="text-purple-300 text-sm">
                                    <i class="fas fa-eye mr-1"></i> {{ number_format($featuredPost['views']) }} views
                                </span>
                            </div>

                            <h2 class="text-3xl lg:text-4xl font-bold mb-4 leading-tight">
                                <a href="{{ route('blog.show', $featuredPost['slug']) }}" class="hover:text-purple-200 transition-colors">
                                    {{ $featuredPost['title'] }}
                                </a>
                            </h2>

                            <p class="text-gray-300 text-lg mb-6 leading-relaxed">{{ $featuredPost['excerpt'] }}</p>

                            <div class="flex items-center space-x-4 mb-6">
                                <a href="{{ route('staff.profile', ['type' => 'user', 'identifier' => $featuredPost['author']['id']]) }}" class="flex items-center space-x-4">
                                    <img src="{{ $featuredPost['author']['avatar'] ?: 'https://ui-avatars.com/api/?name=' . urlencode($featuredPost['author']['name']) . '&length=2' }}"
                                        onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($featuredPost['author']['name']) }}&length=2';"
                                        alt="{{ $featuredPost['author']['name'] }}"
                                        class="w-12 h-12 rounded-full border-2 border-purple-500">
                                </a>
                                <div>
                                    <a href="{{ route('staff.profile', ['type' => 'user', 'identifier' => $featuredPost['author']['id']]) }}" class="font-semibold hover:text-purple-200 transition-colors">
                                        {{ $featuredPost['author']['name'] }}
                                    </a>
                                    <p class="text-sm text-gray-400">
                                        {{ \Carbon\Carbon::parse($featuredPost['published_at'])->format('M d, Y') }}</p>
                                </div>
                            </div>

                            <a href="/blog/{{ $featuredPost['slug'] }}"
                                class="inline-flex items-center space-x-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-full transition-all duration-300 w-fit">
                                <span>Read Full Article</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Main Content Area -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                <!-- Sidebar -->
                <aside class="lg:col-span-1 space-y-8">

                    <!-- Search -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-search text-purple-600 mr-2"></i>
                            Search Blog
                        </h3>
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.500ms="searchQuery"
                                placeholder="Search articles..."
                                class="w-full px-4 py-3 pr-10 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-purple-500 transition-colors">
                            <i
                                class="fas fa-search absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-folder text-purple-600 mr-2"></i>
                            Categories
                        </h3>
                        <div class="space-y-2">
                            @foreach($categories as $category)
                                <button wire:click="$set('selectedCategory', '{{ $category['slug'] }}')"
                                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $selectedCategory === $category['slug'] ? 'bg-purple-50 text-purple-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span class="flex items-center space-x-2">
                                        <i class="{{ $category['icon'] }} text-{{ $category['color'] }}-600"></i>
                                        <span>{{ $category['name'] }}</span>
                                    </span>
                                    <span class="text-sm bg-gray-100 px-2 py-1 rounded-full">{{ $category['count'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Trending Posts -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-fire text-red-600 mr-2"></i>
                            Trending Now
                        </h3>
                        <div class="space-y-4">
                            @foreach($trendingPosts as $index => $trending)
                                <a href="/blog/{{ $trending['slug'] }}" class="flex items-start space-x-3 group">
                                    <span
                                        class="flex-shrink-0 w-8 h-8 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center font-bold text-sm">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="flex-1">
                                        <h4
                                            class="text-sm font-semibold text-gray-900 group-hover:text-purple-600 transition-colors line-clamp-2">
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
                            <i class="fas fa-tags text-purple-600 mr-2"></i>
                            Popular Tags
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($popularTags as $tag)
                                <a href="#"
                                    class="px-3 py-1.5 bg-gray-100 hover:bg-purple-100 text-gray-700 hover:text-purple-700 text-sm rounded-full transition-colors">
                                    #{{ $tag }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Newsletter Signup -->
                    <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-2xl shadow-lg p-6 text-white">
                        <div class="text-center">
                            <i class="fas fa-envelope-open-text text-4xl mb-4"></i>
                            <h3 class="text-xl font-bold mb-2">Stay Updated</h3>
                            <p class="text-purple-100 text-sm mb-4">
                                Get the latest blog articles delivered to your inbox
                            </p>
                            <form class="space-y-3">
                                <input type="email" placeholder="Your email"
                                    class="w-full px-4 py-2 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-white">
                                <button
                                    class="w-full px-4 py-2 bg-white text-purple-600 font-semibold rounded-lg hover:bg-purple-50 transition-colors">
                                    Subscribe
                                </button>
                            </form>
                        </div>
                    </div>

                </aside>

                <!-- Posts Grid -->
                <div class="lg:col-span-3">

                    <!-- Filter Info -->
                    <div
                        class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-4 sm:space-y-0">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">
                                @if($selectedCategory === 'all')
                                    All Posts
                                @else
                                    {{ collect($categories)->firstWhere('slug', $selectedCategory)['name'] ?? 'Blog' }}
                                @endif
                            </h2>
                            <p class="text-gray-600 mt-1">{{ count($posts) }} articles found</p>
                        </div>

                        @if($searchQuery || $selectedCategory !== 'all')
                            <button wire:click="$set('searchQuery', ''); $set('selectedCategory', 'all')"
                                class="flex items-center space-x-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors">
                                <i class="fas fa-times"></i>
                                <span>Clear Filters</span>
                            </button>
                        @endif
                    </div>

                    <!-- Posts Grid -->
                    @if(count($posts) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            @foreach($posts as $post)
                                <article
                                    class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group">
                                    <!-- Image -->
                                    <div class="relative h-56 overflow-hidden">
                                        <a href="{{ route('blog.show', $post['slug']) }}">
                                            <img src="{{ $post['featured_image'] }}" alt="{{ $post['title'] }}"
                                                class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                        </a>
                                        <div class="absolute top-4 left-4">
                                            <span
                                                class="px-3 py-1 bg-{{ $post['category']['slug'] === 'music-insights' ? 'blue' : ($post['category']['slug'] === 'dj-tips' ? 'purple' : ($post['category']['slug'] === 'industry-news' ? 'amber' : 'pink')) }}-600 text-white text-xs font-semibold rounded-full">
                                                {{ $post['category']['name'] }}
                                            </span>
                                        </div>
                                        @if($post['series'])
                                        <div class="absolute top-4 right-4">
                                            <span
                                                class="px-2 py-1 bg-blue-600 text-white text-xs font-semibold rounded-full">
                                                <i class="fas fa-layer-group mr-1"></i> Series
                                            </span>
                                        </div>
                                        @endif
                                        <div class="absolute bottom-4 right-4 flex items-center space-x-2">
                                            <span class="px-2 py-1 bg-black/70 text-white text-xs rounded-full">
                                                <i class="fas fa-eye mr-1"></i> {{ number_format($post['views']) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-6">
                                        <!-- Meta Info -->
                                        <div class="flex items-center space-x-4 text-sm text-gray-500 mb-3">
                                            <span class="flex items-center space-x-1">
                                                <i class="fas fa-calendar text-xs"></i>
                                                <span>{{ \Carbon\Carbon::parse($post['published_at'])->format('M d, Y') }}</span>
                                            </span>
                                            <span class="flex items-center space-x-1">
                                                <i class="fas fa-clock text-xs"></i>
                                                <span>{{ $post['read_time'] }}</span>
                                            </span>
                                        </div>

                                        <!-- Title -->
                                        <h3
                                            class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-purple-600 transition-colors">
                                            <a href="{{ route('blog.show', $post['slug']) }}">{{ $post['title'] }}</a>
                                        </h3>

                                        <!-- Excerpt -->
                                        <p class="text-gray-600 mb-4 line-clamp-3">{{ $post['excerpt'] }}</p>

                                        <!-- Author & Actions -->
                                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                            <div class="flex items-center space-x-3">
                                                <a href="{{ route('staff.profile', ['type' => 'user', 'identifier' => $post['author']['id']]) }}">
                                                    <img src="{{ $post['author']['avatar'] ?: 'https://ui-avatars.com/api/?name=' . urlencode($post['author']['name']) . '&length=2' }}"
                                                        onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($post['author']['name']) }}&length=2';"
                                                        alt="{{ $post['author']['name'] }}" class="w-10 h-10 rounded-full">
                                                </a>
                                                <div>
                                                    <a href="{{ route('staff.profile', ['type' => 'user', 'identifier' => $post['author']['id']]) }}" class="text-sm font-semibold text-gray-900 hover:text-purple-600 transition-colors">
                                                        {{ $post['author']['name'] }}</a>
                                                    <p class="text-xs text-gray-500">{{ $post['author']['role'] }}</p>
                                                </div>
                                            </div>

                                            <div class="flex items-center space-x-3 text-gray-500">
                                                <button class="hover:text-red-500 transition-colors">
                                                    <i class="fas fa-heart"></i>
                                                    <span class="text-xs ml-1">{{ $post['likes'] ?? 0 }}</span>
                                                </button>
                                                <button class="hover:text-purple-600 transition-colors">
                                                    <i class="fas fa-comment"></i>
                                                    <span class="text-xs ml-1">{{ $post['comments_count'] }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <!-- Pagination Placeholder -->
                        <div class="mt-12 flex justify-center">
                            <div class="flex items-center space-x-2">
                                <button
                                    class="px-4 py-2 bg-white border-2 border-gray-300 rounded-lg hover:border-purple-500 hover:text-purple-600 transition-colors">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button class="px-4 py-2 bg-purple-600 text-white font-semibold rounded-lg">1</button>
                                <button
                                    class="px-4 py-2 bg-white border-2 border-gray-300 rounded-lg hover:border-purple-500 hover:text-purple-600 transition-colors">2</button>
                                <button
                                    class="px-4 py-2 bg-white border-2 border-gray-300 rounded-lg hover:border-purple-500 hover:text-purple-600 transition-colors">3</button>
                                <button
                                    class="px-4 py-2 bg-white border-2 border-gray-300 rounded-lg hover:border-purple-500 hover:text-purple-600 transition-colors">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    @else
                        <!-- No Results -->
                        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                            <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">No articles found</h3>
                            <p class="text-gray-600 mb-6">Try adjusting your search or filters</p>
                            <button wire:click="$set('searchQuery', ''); $set('selectedCategory', 'all')"
                                class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-full transition-colors">
                                Clear All Filters
                            </button>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>

    <!-- Newsletter CTA -->
    <section class="py-20 bg-gradient-to-br from-purple-600 via-purple-700 to-purple-800 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <i class="fas fa-book-open text-6xl mb-6 opacity-80"></i>
                <h2 class="text-4xl md:text-5xl font-bold mb-6">Never Miss an Insight</h2>
                <p class="text-xl text-purple-100 mb-8 leading-relaxed">
                    Subscribe to our blog and get deep insights, tutorials, and exclusive content delivered straight to your inbox.
                </p>
                <form
                    class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-4 max-w-xl mx-auto">
                    <input type="email" placeholder="Enter your email address"
                        class="w-full sm:flex-1 px-6 py-4 rounded-full text-gray-900 focus:outline-none focus:ring-4 focus:ring-purple-300">
                    <button
                        class="w-full sm:w-auto px-8 py-4 bg-white text-purple-600 font-bold rounded-full hover:bg-purple-50 transition-colors shadow-lg">
                        Subscribe Now
                    </button>
                </form>
                <p class="text-sm text-purple-200 mt-4">
                    <i class="fas fa-lock mr-1"></i> We respect your privacy. Unsubscribe anytime.
                </p>
            </div>
        </div>
    </section>
</div>
