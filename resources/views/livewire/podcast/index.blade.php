<div class="min-h-screen bg-gray-50">
    
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-purple-600 via-purple-700 to-indigo-800 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0"
                style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
            </div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <i class="fas fa-podcast text-6xl mb-6 opacity-80"></i>
                <h1 class="text-5xl md:text-6xl font-bold mb-6">Glow FM Podcasts</h1>
                <p class="text-xl md:text-2xl text-purple-100 leading-relaxed">
                    Listen to exclusive shows, interviews, and behind-the-scenes content from your favorite station
                </p>
            </div>
        </div>
    </section>

    <!-- Featured Shows -->
    @if($featuredShows->count() > 0)
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-star text-yellow-500 mr-2"></i>
                    Featured Shows
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($featuredShows as $show)
                <a href="{{ route('podcasts.show', $show->slug) }}" 
                   class="group bg-gradient-to-br from-purple-50 to-indigo-50 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                    <div class="relative h-64">
                        <img src="{{ $show->cover_image }}" alt="{{ $show->title }}" 
                             class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <span class="px-3 py-1 bg-purple-600 text-white text-xs font-bold rounded-full">
                                {{ ucfirst($show->category) }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $show->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $show->description }}</p>
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span><i class="fas fa-microphone mr-1"></i>{{ $show->host_name }}</span>
                            <span><i class="fas fa-headphones mr-1"></i>{{ number_format($show->total_plays) }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Latest Episodes -->
    @if($latestEpisodes->count() > 0)
    <section class="py-12 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-clock text-purple-600 mr-2"></i>
                    Latest Episodes
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($latestEpisodes as $episode)
                <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 group">
                    <div class="relative h-48">
                        <img src="{{ $episode->cover_image ?? $episode->show->cover_image }}" 
                             alt="{{ $episode->title }}"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <a href="{{ route('podcasts.episode', [$episode->show->slug, $episode->slug]) }}" 
                               class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center text-white text-2xl transform scale-75 group-hover:scale-100 transition-transform">
                                <i class="fas fa-play ml-1"></i>
                            </a>
                        </div>
                        <div class="absolute top-3 right-3">
                            <span class="px-2 py-1 bg-black/70 text-white text-xs rounded-full">
                                {{ $episode->formatted_duration }}
                            </span>
                        </div>
                    </div>
                    <div class="p-5">
                        <p class="text-xs text-purple-600 font-semibold mb-2">{{ $episode->show->title }}</p>
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-purple-600 transition-colors">
                            {{ $episode->title }}
                        </h3>
                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $episode->description }}</p>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span><i class="fas fa-calendar mr-1"></i>{{ $episode->published_at->format('M d, Y') }}</span>
                            <span><i class="fas fa-headphones mr-1"></i>{{ number_format($episode->plays) }}</span>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- All Shows Section -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            
            <!-- Filters -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <h2 class="text-3xl font-bold text-gray-900">All Podcast Shows</h2>
                
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                    <!-- Search -->
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="searchQuery"
                               placeholder="Search podcasts..."
                               class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>

                    <!-- Category Filter -->
                    <select wire:model.live="selectedCategory"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        @foreach($categories as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Shows Grid -->
            @if($shows->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    @foreach($shows as $show)
                    <a href="{{ route('podcasts.show', $show->slug) }}" 
                       class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100">
                        <div class="relative h-48">
                            <img src="{{ $show->cover_image }}" alt="{{ $show->title }}" 
                                 class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute top-3 left-3">
                                <span class="px-3 py-1 bg-{{ $show->category === 'music' ? 'purple' : ($show->category === 'talk' ? 'blue' : 'emerald') }}-600 text-white text-xs font-bold rounded-full">
                                    {{ ucfirst($show->category) }}
                                </span>
                            </div>
                            @if($show->explicit)
                            <div class="absolute top-3 right-3">
                                <span class="px-2 py-1 bg-red-600 text-white text-xs font-bold rounded">E</span>
                            </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-purple-600 transition-colors">
                                {{ $show->title }}
                            </h3>
                            <p class="text-sm text-gray-600 mb-3">
                                <i class="fas fa-microphone mr-1 text-purple-600"></i>{{ $show->host_name }}
                            </p>
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span><i class="fas fa-list mr-1"></i>{{ $show->published_episodes_count }} episodes</span>
                                <span><i class="fas fa-users mr-1"></i>{{ number_format($show->subscribers) }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $shows->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-podcast text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600 text-lg">No podcasts found matching your criteria</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Trending Episodes Sidebar -->
    @if($trendingEpisodes->count() > 0)
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">
                <i class="fas fa-fire text-orange-500 mr-2"></i>
                Trending This Month
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                @foreach($trendingEpisodes as $index => $episode)
                <article class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow">
                    <div class="flex items-start space-x-3">
                        <span class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-orange-500 to-red-500 text-white rounded-lg flex items-center justify-center font-bold">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 mb-1 line-clamp-2">
                                <a href="{{ route('podcasts.episode', [$episode->show->slug, $episode->slug]) }}" class="hover:text-purple-600">
                                    {{ $episode->title }}
                                </a>
                            </h4>
                            <p class="text-xs text-gray-600 mb-2">{{ $episode->show->title }}</p>
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-headphones mr-1"></i>
                                {{ number_format($episode->plays) }} plays
                            </div>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-br from-purple-600 via-purple-700 to-indigo-800 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <i class="fas fa-rss text-6xl mb-6 opacity-80"></i>
                <h2 class="text-4xl md:text-5xl font-bold mb-6">Subscribe to Never Miss an Episode</h2>
                <p class="text-xl text-purple-100 mb-8 leading-relaxed">
                    Get notified when new episodes drop. Available on all major podcast platforms.
                </p>
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <a href="#" class="px-6 py-3 bg-white text-purple-600 font-bold rounded-lg hover:bg-purple-50 transition-colors">
                        <i class="fab fa-spotify mr-2"></i>Spotify
                    </a>
                    <a href="#" class="px-6 py-3 bg-white text-purple-600 font-bold rounded-lg hover:bg-purple-50 transition-colors">
                        <i class="fab fa-apple mr-2"></i>Apple Podcasts
                    </a>
                    <a href="#" class="px-6 py-3 bg-white text-purple-600 font-bold rounded-lg hover:bg-purple-50 transition-colors">
                        <i class="fab fa-google mr-2"></i>Google Podcasts
                    </a>
                    <a href="#" class="px-6 py-3 bg-white text-purple-600 font-bold rounded-lg hover:bg-purple-50 transition-colors">
                        <i class="fas fa-rss mr-2"></i>RSS Feed
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>