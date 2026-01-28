<div>
    <section class="relative bg-gradient-to-br from-emerald-700 via-emerald-800 to-teal-800 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        <div class="container mx-auto px-4 relative z-10">
            <x-ad-slot placement="shows" />
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6">Shows & Programs</h1>
                <p class="text-xl md:text-2xl text-emerald-100 leading-relaxed">
                    Discover the daily lineup, hosts, and signature programs that power Glow FM.
                </p>
            </div>
        </div>
    </section>

    @if($featuredShow)
        <section class="py-12 bg-white">
            <div class="container mx-auto px-4">
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl overflow-hidden shadow-2xl">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                        <div class="relative h-96 lg:h-auto">
                            <x-initials-image
                                :src="$featuredShow->cover_image"
                                :title="$featuredShow->title"
                                imgClass="w-full h-full object-cover"
                                fallbackClass="bg-emerald-700/90"
                                textClass="text-5xl font-bold text-white"
                            />
                            <div class="absolute top-6 left-6">
                                <span class="px-4 py-2 bg-emerald-600 text-white text-sm font-bold rounded-full shadow-lg">
                                    <i class="fas fa-star mr-1"></i> FEATURED
                                </span>
                            </div>
                        </div>
                        <div class="p-8 lg:p-12 text-white flex flex-col justify-center">
                            <div class="flex items-center space-x-4 mb-4">
                                <span class="px-3 py-1 bg-emerald-600 text-white text-xs font-semibold rounded-full">
                                    {{ $featuredShow->category?->name ?? 'Show' }}
                                </span>
                                <span class="text-emerald-300 text-sm">
                                    <i class="fas fa-clock mr-1"></i> {{ $featuredShow->typical_duration }} mins
                                </span>
                                <span class="text-emerald-300 text-sm">
                                    <i class="fas fa-user mr-1"></i> {{ $featuredShow->primaryHost?->name ?? 'TBA' }}
                                </span>
                            </div>

                            <h2 class="text-3xl lg:text-4xl font-bold mb-4 leading-tight">{{ $featuredShow->title }}</h2>
                            <p class="text-gray-300 text-lg mb-6 leading-relaxed">{{ $featuredShow->description }}</p>

                            <a href="{{ route('shows.show', $featuredShow->slug) }}"
                                class="inline-flex items-center space-x-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-full transition-all duration-300 w-fit">
                                <span>View Show</span>
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
                <aside class="lg:col-span-1 space-y-8">
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-search text-emerald-600 mr-2"></i>
                            Search Shows
                        </h3>
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.500ms="searchQuery"
                                placeholder="Search shows..."
                                class="w-full px-4 py-3 pr-10 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-emerald-500 transition-colors">
                            <i class="fas fa-search absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

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
                </aside>

                <div class="lg:col-span-3">
                    <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-4 sm:space-y-0">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">
                                @if($selectedCategory === 'all')
                                    All Shows
                                @else
                                    {{ collect($categories)->firstWhere('slug', $selectedCategory)['name'] ?? 'Shows' }}
                                @endif
                            </h2>
                            <p class="text-gray-600 mt-1">{{ $shows->total() }} shows found</p>
                        </div>

                        <select wire:model.live="sortBy"
                            class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option value="featured">Featured</option>
                            <option value="latest">Latest</option>
                            <option value="popular">Most Listened</option>
                            <option value="title_asc">Title (A–Z)</option>
                            <option value="title_desc">Title (Z–A)</option>
                            <option value="host_asc">Host (A–Z)</option>
                            <option value="host_desc">Host (Z–A)</option>
                            <option value="day_asc">Day & Time (Earliest)</option>
                            <option value="day_desc">Day & Time (Latest)</option>
                            <option value="duration_asc">Duration (Shortest)</option>
                            <option value="duration_desc">Duration (Longest)</option>
                            <option value="category_asc">Category (A–Z)</option>
                            <option value="category_desc">Category (Z–A)</option>
                            <option value="format_asc">Format (A–Z)</option>
                            <option value="format_desc">Format (Z–A)</option>
                        </select>
                    </div>

                    @if($shows->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            @foreach($shows as $show)
                                <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group">
                                    <div class="relative h-52 overflow-hidden">
                                        <x-initials-image
                                            :src="$show->cover_image"
                                            :title="$show->title"
                                            imgClass="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                                            fallbackClass="bg-emerald-700/90"
                                            textClass="text-3xl font-bold text-white"
                                        />
                                        <div class="absolute top-4 left-4">
                                            <span class="px-3 py-1 bg-{{ $show->category?->color ?? 'emerald' }}-600 text-white text-xs font-semibold rounded-full">
                                                {{ $show->category?->name ?? 'Show' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="p-6">
                                        <div class="flex items-center space-x-4 text-sm text-gray-500 mb-3">
                                            <span class="flex items-center space-x-1">
                                                <i class="fas fa-clock text-xs"></i>
                                                <span>{{ $show->typical_duration }} mins</span>
                                            </span>
                                            <span class="flex items-center space-x-1">
                                                <i class="fas fa-user text-xs"></i>
                                                <span>{{ $show->primaryHost?->name ?? 'TBA' }}</span>
                                            </span>
                                        </div>

                                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-emerald-600 transition-colors">
                                            <a href="{{ route('shows.show', $show->slug) }}">{{ $show->title }}</a>
                                        </h3>

                                        <p class="text-gray-600 mb-4 line-clamp-3">{{ $show->description }}</p>

                                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                            <div class="flex items-center space-x-3">
                                                <img src="{{ $show->primaryHost?->profile_photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($show->primaryHost?->name ?? $show->title) . '&length=2' }}"
                                                    onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($show->primaryHost?->name ?? $show->title) }}&length=2';"
                                                    alt="{{ $show->primaryHost?->name ?? $show->title }}" class="w-10 h-10 rounded-full">
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $show->primaryHost?->name ?? 'Host TBA' }}</p>
                                                    <p class="text-xs text-gray-500">{{ ucfirst($show->format) }}</p>
                                                </div>
                                            </div>
                                            <a href="{{ route('shows.show', $show->slug) }}"
                                                class="text-emerald-600 hover:text-emerald-700 text-sm font-semibold">
                                                View
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                        <div class="mt-12 flex justify-center">
                            {{ $shows->links() }}
                        </div>
                    @else
                        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                            <i class="fas fa-microphone-alt text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">No shows found</h3>
                            <p class="text-gray-600 mb-6">Try adjusting your search or filters</p>
                            <button wire:click="$set('searchQuery', ''); $set('selectedCategory', 'all')"
                                class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-full transition-colors">
                                Clear Filters
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
