<div>
    <!-- Page Header -->
    <section class="relative bg-gradient-to-br from-amber-600 via-amber-700 to-orange-700 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0"
                style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
            </div>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <x-ad-slot placement="events" />
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6">Events & Experiences</h1>
                <p class="text-xl md:text-2xl text-amber-100 leading-relaxed">
                    Live shows, community meetups, and special experiences hosted by Glow FM.
                </p>
            </div>
        </div>
    </section>

    @if($featuredEvent)
        <section class="py-12 bg-white">
            <div class="container mx-auto px-4">
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl overflow-hidden shadow-2xl">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                        <div class="relative h-96 lg:h-auto">
                            <x-initials-image
                                :src="$featuredEvent['featured_image'] ?? null"
                                :title="$featuredEvent['title'] ?? ''"
                                imgClass="w-full h-full object-cover"
                                fallbackClass="bg-emerald-700/90"
                                textClass="text-4xl font-bold text-white"
                            />
                            <div class="absolute top-6 left-6">
                                <span class="px-4 py-2 bg-amber-600 text-white text-sm font-bold rounded-full shadow-lg">
                                    <i class="fas fa-star mr-1"></i> FEATURED
                                </span>
                            </div>
                        </div>
                        <div class="p-8 lg:p-12 text-white flex flex-col justify-center">
                            <div class="flex items-center space-x-4 mb-4">
                                <span class="px-3 py-1 bg-amber-600 text-white text-xs font-semibold rounded-full">
                                    {{ $featuredEvent['category']['name'] }}
                                </span>
                                <span class="text-amber-300 text-sm">
                                    <i class="fas fa-calendar mr-1"></i> {{ $featuredEvent['formatted_date'] }}
                                </span>
                                <span class="text-amber-300 text-sm">
                                    <i class="fas fa-clock mr-1"></i> {{ $featuredEvent['formatted_time'] }}
                                </span>
                            </div>

                            <h2 class="text-3xl lg:text-4xl font-bold mb-4 leading-tight">{{ $featuredEvent['title'] }}</h2>
                            <p class="text-gray-300 text-lg mb-6 leading-relaxed">{{ $featuredEvent['excerpt'] }}</p>

                            <div class="flex items-center space-x-4 mb-6">
                                <img src="{{ $featuredEvent['author']['avatar'] ?: 'https://ui-avatars.com/api/?name=' . urlencode($featuredEvent['author']['name']) . '&length=2' }}"
                                    onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($featuredEvent['author']['name']) }}&length=2';"
                                    alt="{{ $featuredEvent['author']['name'] }}"
                                    class="w-12 h-12 rounded-full border-2 border-amber-500">
                                <div>
                                    <p class="font-semibold">{{ $featuredEvent['author']['name'] }}</p>
                                    <p class="text-sm text-gray-400">{{ $featuredEvent['venue_name'] ?? 'Venue TBA' }}</p>
                                </div>
                            </div>

                            <a href="{{ route('events.show', $featuredEvent['slug']) }}"
                                class="inline-flex items-center space-x-2 px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-full transition-all duration-300 w-fit">
                                <span>View Event</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <aside class="lg:col-span-1 space-y-8">
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-search text-amber-600 mr-2"></i>
                            Search Events
                        </h3>
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.500ms="searchQuery"
                                placeholder="Search events..."
                                class="w-full px-4 py-3 pr-10 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-amber-500 transition-colors">
                            <i class="fas fa-search absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-folder text-amber-600 mr-2"></i>
                            Categories
                        </h3>
                        <div class="space-y-2">
                            @foreach($categories as $category)
                                <button wire:click="$set('selectedCategory', '{{ $category['slug'] }}')"
                                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $selectedCategory === $category['slug'] ? 'bg-amber-50 text-amber-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span class="flex items-center space-x-2">
                                        <i class="{{ $category['icon'] }} text-{{ $category['color'] }}-600"></i>
                                        <span>{{ $category['name'] }}</span>
                                    </span>
                                    <span class="text-sm bg-gray-100 px-2 py-1 rounded-full">{{ $category['count'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-clock text-amber-600 mr-2"></i>
                            Upcoming Events
                        </h3>
                        <div class="space-y-4">
                            @foreach($upcomingEvents as $upcoming)
                                <a href="{{ route('events.show', $upcoming->slug) }}" class="flex items-start space-x-3 group">
                                    <span class="flex-shrink-0 w-10 h-10 bg-amber-100 text-amber-700 rounded-lg flex items-center justify-center font-bold text-xs">
                                        {{ $upcoming->start_at?->format('M d') }}
                                    </span>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold text-gray-900 group-hover:text-amber-600 transition-colors line-clamp-2">
                                            {{ $upcoming->title }}
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-map-marker-alt mr-1"></i> {{ $upcoming->venue_name ?? 'TBA' }}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-tags text-amber-600 mr-2"></i>
                            Popular Tags
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($popularTags as $tag)
                                <a href="#"
                                    class="px-3 py-1.5 bg-gray-100 hover:bg-amber-100 text-gray-700 hover:text-amber-700 text-sm rounded-full transition-colors">
                                    #{{ $tag }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </aside>

                <!-- Events Grid -->
                <div class="lg:col-span-3">
                    <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-4 sm:space-y-0">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">
                                @if($selectedCategory === 'all')
                                    All Events
                                @else
                                    {{ collect($categories)->firstWhere('slug', $selectedCategory)['name'] ?? 'Events' }}
                                @endif
                            </h2>
                            <p class="text-gray-600 mt-1">{{ $events->total() }} events found</p>
                        </div>

                        <select wire:model.live="sortBy"
                            class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <option value="upcoming">Upcoming</option>
                            <option value="latest">Latest</option>
                            <option value="past">Past</option>
                            <option value="popular">Most Viewed</option>
                        </select>
                    </div>

                    @if($events->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            @foreach($events as $event)
                                <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group">
                                    <div class="relative h-56 overflow-hidden">
                                        <x-initials-image
                                            :src="$event->featured_image"
                                            :title="$event->title"
                                            imgClass="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                                            fallbackClass="bg-emerald-700/90"
                                            textClass="text-3xl font-bold text-white"
                                        />
                                        <div class="absolute top-4 left-4">
                                            <span class="px-3 py-1 bg-{{ $event->category->color }}-600 text-white text-xs font-semibold rounded-full">
                                                {{ $event->category->name }}
                                            </span>
                                        </div>
                                        <div class="absolute bottom-4 right-4 flex items-center space-x-2">
                                            <span class="px-2 py-1 bg-black/70 text-white text-xs rounded-full">
                                                <i class="fas fa-eye mr-1"></i> {{ number_format($event->views) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="p-6">
                                        <div class="flex items-center space-x-4 text-sm text-gray-500 mb-3">
                                            <span class="flex items-center space-x-1">
                                                <i class="fas fa-calendar text-xs"></i>
                                                <span>{{ $event->formatted_date }}</span>
                                            </span>
                                            <span class="flex items-center space-x-1">
                                                <i class="fas fa-clock text-xs"></i>
                                                <span>{{ $event->formatted_time }}</span>
                                            </span>
                                        </div>

                                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-amber-600 transition-colors">
                                            <a href="{{ route('events.show', $event->slug) }}">{{ $event->title }}</a>
                                        </h3>

                                        <p class="text-gray-600 mb-4 line-clamp-3">{{ $event->excerpt }}</p>

                                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                            <div class="flex items-center space-x-3">
                                                <img src="{{ $event->author->avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($event->author->name) . '&length=2' }}"
                                                    onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($event->author->name) }}&length=2';"
                                                    alt="{{ $event->author->name }}" class="w-10 h-10 rounded-full">
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $event->author->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $event->venue_name ?? 'Venue TBA' }}</p>
                                                </div>
                                            </div>

                                            <div class="flex items-center space-x-3 text-gray-500">
                                                <button class="hover:text-amber-600 transition-colors">
                                                    <i class="fas fa-bookmark"></i>
                                                </button>
                                                <button class="hover:text-amber-600 transition-colors">
                                                    <i class="fas fa-share-alt"></i>
                                                    <span class="text-xs ml-1">{{ number_format($event->shares) }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="mt-12 flex justify-center">
                            {{ $events->links() }}
                        </div>
                    @else
                        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                            <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">No events found</h3>
                            <p class="text-gray-600 mb-6">Try adjusting your search or filters</p>
                            <button wire:click="$set('searchQuery', ''); $set('selectedCategory', 'all')"
                                class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-full transition-colors">
                                Clear All Filters
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
