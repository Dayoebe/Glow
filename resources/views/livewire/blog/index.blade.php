<div class="min-h-screen bg-gray-50">
    
    <!-- Hero Section with Featured Post -->
    @if($featuredPost)
    <section class="relative bg-gradient-to-br from-purple-900 via-purple-800 to-indigo-900 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <img src="{{ $featuredPost->featured_image }}" class="w-full h-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        
        <div class="container mx-auto px-4 py-24 relative z-10">
            <div class="max-w-4xl">
                <span class="inline-block px-4 py-2 bg-yellow-400 text-purple-900 text-sm font-bold rounded-full mb-4">
                    ⭐ FEATURED POST
                </span>
                <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                    {{ $featuredPost->title }}
                </h1>
                <p class="text-xl text-purple-100 mb-8 leading-relaxed">
                    {{ $featuredPost->excerpt_preview }}
                </p>
                <div class="flex flex-wrap items-center gap-6 mb-8">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $featuredPost->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($featuredPost->author->name) }}" 
                             class="w-12 h-12 rounded-full border-2 border-purple-300">
                        <div>
                            <p class="font-semibold">{{ $featuredPost->author->name }}</p>
                            <p class="text-sm text-purple-200">{{ $featuredPost->published_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <span class="text-purple-200">•</span>
                    <span class="text-purple-200">{{ $featuredPost->read_time }}</span>
                    <span class="text-purple-200">•</span>
                    <span class="text-purple-200">{{ number_format($featuredPost->views) }} views</span>
                </div>
                <a href="{{ route('blog.show', $featuredPost->slug) }}" 
                   class="inline-flex items-center px-8 py-4 bg-white text-purple-900 font-bold rounded-full hover:bg-purple-100 transition-all transform hover:scale-105 shadow-xl">
                    Read Full Story
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Main Content -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-8">
                
                <!-- Sidebar -->
                <aside class="lg:w-80 space-y-6">
                    
                    <!-- Search -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-4">
                        <div class="relative mb-6">
                            <input type="text" 
                                   wire:model.live.debounce.500ms="searchQuery"
                                   placeholder="Search articles..."
                                   class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition-colors">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>

                        <!-- View Toggle -->
                        <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-200">
                            <span class="text-sm font-semibold text-gray-700">View:</span>
                            <div class="flex space-x-2">
                                <button wire:click="$set('view', 'grid')" 
                                        class="p-2 rounded-lg {{ $view === 'grid' ? 'bg-purple-100 text-purple-600' : 'bg-gray-100 text-gray-600' }}">
                                    <i class="fas fa-th"></i>
                                </button>
                                <button wire:click="$set('view', 'list')" 
                                        class="p-2 rounded-lg {{ $view === 'list' ? 'bg-purple-100 text-purple-600' : 'bg-gray-100 text-gray-600' }}">
                                    <i class="fas fa-list"></i>
                                </button>
                                <button wire:click="$set('view', 'magazine')" 
                                        class="p-2 rounded-lg {{ $view === 'magazine' ? 'bg-purple-100 text-purple-600' : 'bg-gray-100 text-gray-600' }}">
                                    <i class="fas fa-newspaper"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Sort -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Sort By</label>
                            <select wire:model.live="sortBy" 
                                    class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500">
                                <option value="latest">Latest Posts</option>
                                <option value="popular">Most Popular</option>
                                <option value="trending">Trending This Week</option>
                            </select>
                        </div>

                        <!-- Categories -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-folder text-purple-600 mr-2"></i>
                                Categories
                            </h3>
                            <div class="space-y-2">
                                <button wire:click="$set('selectedCategory', 'all')" 
                                        class="w-full text-left px-4 py-3 rounded-xl transition-all {{ $selectedCategory === 'all' ? 'bg-purple-50 text-purple-700 font-semibold' : 'hover:bg-gray-50' }}">
                                    <span>All Posts</span>
                                    <span class="float-right text-sm">{{ $posts->total() }}</span>
                                </button>
                                @foreach($categories as $category)
                                <button wire:click="$set('selectedCategory', '{{ $category->slug }}')" 
                                        class="w-full text-left px-4 py-3 rounded-xl transition-all {{ $selectedCategory === $category->slug ? 'bg-purple-50 text-purple-700 font-semibold' : 'hover:bg-gray-50' }}">
                                    <i class="{{ $category->icon }} text-{{ $category->color }}-500 mr-2"></i>
                                    <span>{{ $category->name }}</span>
                                    <span class="float-right text-sm bg-gray-100 px-2 py-1 rounded-full">{{ $category->posts_count }}</span>
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Trending Posts -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-fire text-orange-500 mr-2"></i>
                            Trending This Week
                        </h3>
                        <div class="space-y-4">
                            @foreach($trendingPosts as $index => $trending)
                            <a href="{{ route('blog.show', $trending->slug) }}" class="flex items-start space-x-3 group">
                                <span class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-orange-400 to-red-500 text-white rounded-lg flex items-center justify-center font-bold text-sm">
                                    {{ $index + 1 }}
                                </span>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-gray-900 group-hover:text-purple-600 line-clamp-2 transition-colors">
                                        {{ $trending->title }}
                                    </h4>
                                    <p class="text-xs text-gray-500 mt-1">{{ number_format($trending->views) }} views</p>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Popular Tags -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-tags text-purple-600 mr-2"></i>
                            Popular Tags
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($popularTags as $tag)
                            <a href="#" class="px-3 py-1.5 bg-gray-100 hover:bg-purple-100 text-gray-700 hover:text-purple-700 text-sm rounded-full transition-colors">
                                #{{ $tag }}
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Newsletter -->
                    <div class="bg-gradient-to-br from-purple-600 to-indigo-600 rounded-2xl shadow-lg p-6 text-white">
                        <i class="fas fa-envelope-open-text text-4xl mb-4"></i>
                        <h3 class="text-xl font-bold mb-2">Never Miss a Post</h3>
                        <p class="text-purple-100 text-sm mb-4">
                            Get weekly updates delivered to your inbox
                        </p>
                        <form class="space-y-3">
                            <input type="email" placeholder="Your email" 
                                   class="w-full px-4 py-2 rounded-lg text-gray-900 focus:outline-none">
                            <button class="w-full px-4 py-2 bg-white text-purple-600 font-semibold rounded-lg hover:bg-purple-50 transition-colors">
                                Subscribe
                            </button>
                        </form>
                    </div>
                </aside>

                <!-- Posts Grid/List -->
                <div class="flex-1">
                    
                    <!-- Filter Info -->
                    <div class="mb-8 flex items-center justify-between">
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900">
                                @if($searchQuery)
                                    Search: "{{ $searchQuery }}"
                                @elseif($selectedCategory !== 'all')
                                    {{ collect($categories)->firstWhere('slug', $selectedCategory)->name ?? 'Blog' }}
                                @else
                                    All Posts
                                @endif
                            </h2>
                            <p class="text-gray-600 mt-1">{{ $posts->total() }} articles found</p>
                        </div>
                        
                        @if($searchQuery || $selectedCategory !== 'all')
                        <button wire:click="$set('searchQuery', ''); $set('selectedCategory', 'all')" 
                                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-times mr-2"></i>Clear
                        </button>
                        @endif
                    </div>

                    @if($posts->count() > 0)
                        <!-- Grid View -->
                        @if($view === 'grid')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            @foreach($posts as $post)
                            <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                                <div class="relative h-56 overflow-hidden">
                                    <img src="{{ $post->featured_image }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                    <div class="absolute top-4 left-4">
                                        <span class="px-3 py-1 bg-{{ $post->category->color }}-600 text-white text-xs font-bold rounded-full">
                                            {{ $post->category->name }}
                                        </span>
                                    </div>
                                    @if($post->has_multimedia)
                                    <div class="absolute top-4 right-4 flex space-x-2">
                                        @if($post->video_url)
                                        <span class="w-8 h-8 bg-black/70 rounded-full flex items-center justify-center">
                                            <i class="fas fa-play text-white text-xs"></i>
                                        </span>
                                        @endif
                                        @if($post->gallery)
                                        <span class="w-8 h-8 bg-black/70 rounded-full flex items-center justify-center">
                                            <i class="fas fa-images text-white text-xs"></i>
                                        </span>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                                <div class="p-6">
                                    <div class="flex items-center space-x-4 text-sm text-gray-500 mb-3">
                                        <span>{{ $post->published_at->format('M d, Y') }}</span>
                                        <span>•</span>
                                        <span>{{ $post->read_time }}</span>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-purple-600 transition-colors">
                                        <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                    </h3>
                                    <p class="text-gray-600 mb-4 line-clamp-3">{{ $post->excerpt }}</p>
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <div class="flex items-center space-x-3">
                                            <img src="{{ $post->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->author->name) }}" 
                                                 class="w-10 h-10 rounded-full">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $post->author->name }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3 text-gray-500 text-sm">
                                            <span><i class="fas fa-eye mr-1"></i>{{ number_format($post->views) }}</span>
                                            <span><i class="fas fa-comment mr-1"></i>{{ $post->comments_count }}</span>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            @endforeach
                        </div>
                        @endif

                        <!-- List View -->
                        @if($view === 'list')
                        <div class="space-y-6">
                            @foreach($posts as $post)
                            <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all group">
                                <div class="flex flex-col md:flex-row">
                                    <div class="md:w-80 h-56 md:h-auto overflow-hidden flex-shrink-0">
                                        <img src="{{ $post->featured_image }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                    </div>
                                    <div class="p-6 flex-1">
                                        <div class="flex items-center space-x-4 mb-3">
                                            <span class="px-3 py-1 bg-{{ $post->category->color }}-100 text-{{ $post->category->color }}-700 text-xs font-bold rounded-full">
                                                {{ $post->category->name }}
                                            </span>
                                            <span class="text-sm text-gray-500">{{ $post->published_at->format('M d, Y') }}</span>
                                        </div>
                                        <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-purple-600 transition-colors">
                                            <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                        </h3>
                                        <p class="text-gray-600 mb-4 line-clamp-2">{{ $post->excerpt }}</p>
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <img src="{{ $post->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->author->name) }}" 
                                                     class="w-10 h-10 rounded-full">
                                                <span class="font-semibold text-gray-900">{{ $post->author->name }}</span>
                                            </div>
                                            <div class="flex items-center space-x-4 text-gray-500 text-sm">
                                                <span>{{ $post->read_time }}</span>
                                                <span><i class="fas fa-eye mr-1"></i>{{ number_format($post->views) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                            @endforeach
                        </div>
                        @endif

                        <!-- Magazine View -->
                        @if($view === 'magazine')
                        <div class="grid grid-cols-12 gap-6">
                            @foreach($posts as $index => $post)
                                @if($index === 0)
                                <article class="col-span-12 bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all group">
                                    <div class="grid md:grid-cols-2">
                                        <div class="h-96 overflow-hidden">
                                            <img src="{{ $post->featured_image }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                        </div>
                                        <div class="p-8 flex flex-col justify-center">
                                            <span class="px-3 py-1 bg-{{ $post->category->color }}-600 text-white text-xs font-bold rounded-full w-fit mb-4">
                                                {{ $post->category->name }}
                                            </span>
                                            <h3 class="text-3xl font-bold text-gray-900 mb-4 group-hover:text-purple-600 transition-colors">
                                                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                            </h3>
                                            <p class="text-gray-600 mb-6">{{ $post->excerpt }}</p>
                                            <div class="flex items-center space-x-4">
                                                <img src="{{ $post->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->author->name) }}" 
                                                     class="w-12 h-12 rounded-full">
                                                <div>
                                                    <p class="font-semibold text-gray-900">{{ $post->author->name }}</p>
                                                    <p class="text-sm text-gray-500">{{ $post->published_at->format('M d, Y') }} • {{ $post->read_time }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                                @else
                                <article class="col-span-12 md:col-span-6 bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all group">
                                    <div class="h-48 overflow-hidden">
                                        <img src="{{ $post->featured_image }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                    </div>
                                    <div class="p-6">
                                        <span class="px-3 py-1 bg-{{ $post->category->color }}-100 text-{{ $post->category->color }}-700 text-xs font-bold rounded-full">
                                            {{ $post->category->name }}
                                        </span>
                                        <h3 class="text-lg font-bold text-gray-900 mt-3 mb-2 line-clamp-2 group-hover:text-purple-600 transition-colors">
                                            <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                        </h3>
                                        <p class="text-sm text-gray-500">{{ $post->published_at->format('M d, Y') }} • {{ $post->read_time }}</p>
                                    </div>
                                </article>
                                @endif
                            @endforeach
                        </div>
                        @endif

                        <!-- Pagination -->
                        <div class="mt-12">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                            <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">No posts found</h3>
                            <p class="text-gray-600 mb-6">Try adjusting your search or filters</p>
                            <button wire:click="$set('searchQuery', ''); $set('selectedCategory', 'all')" 
                                    class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-full transition-colors">
                                Clear Filters
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>