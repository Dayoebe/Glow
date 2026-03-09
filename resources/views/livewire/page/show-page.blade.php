<div x-data="{
    showModalOpen: false,
    activeShow: null,
    openShowModal(show) {
        this.activeShow = show;
        this.showModalOpen = true;
    },
    closeShowModal() {
        this.showModalOpen = false;
        this.activeShow = null;
    },
    initials(title) {
        if (!title) return 'S';
        return title
            .split(' ')
            .filter(Boolean)
            .slice(0, 2)
            .map(word => word.charAt(0))
            .join('')
            .toUpperCase();
    }
}" @keydown.escape.window="closeShowModal()">
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
                @php
                    $featuredShowModal = [
                        'title' => $featuredShow->title,
                        'description' => trim(strip_tags($featuredShow->full_description ?: $featuredShow->description)),
                        'cover_image' => $featuredShow->cover_image,
                        'has_image' => !empty($featuredShow->cover_image),
                        'category_name' => $featuredShow->category?->name ?? 'Show',
                        'host_name' => $featuredShow->primaryHost?->name ?? 'TBA',
                        'duration' => $featuredShow->typical_duration,
                        'format' => ucfirst((string) $featuredShow->format),
                        'listeners' => number_format((int) $featuredShow->total_listeners),
                        'rating' => $featuredShow->average_rating ? number_format((float) $featuredShow->average_rating, 1) : null,
                        'show_url' => route('shows.show', $featuredShow->slug),
                    ];
                @endphp
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl overflow-hidden shadow-2xl">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 group">
                        <div class="relative h-96 lg:h-auto overflow-hidden">
                            <button type="button"
                                @click='openShowModal(@json($featuredShowModal))'
                                class="block h-full w-full text-left cursor-zoom-in">
                                <x-initials-image
                                    :src="$featuredShow->cover_image"
                                    :title="$featuredShow->title"
                                    imgClass="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    fallbackClass="bg-emerald-700/90"
                                    textClass="text-5xl font-bold text-white"
                                />
                            </button>
                            <div class="absolute top-6 left-6">
                                <span class="px-4 py-2 bg-emerald-600 text-white text-sm font-bold rounded-full shadow-lg">
                                    <i class="fas fa-star mr-1"></i> FEATURED
                                </span>
                            </div>
                            <div class="absolute right-6 bottom-6">
                                <span class="inline-flex items-center rounded-full bg-black/60 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-white shadow-lg">
                                    <i class="fas fa-expand mr-2"></i> Quick View
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
                                @continueIfNotArray($category)
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
                                    {{ data_get(collect($categories)->firstWhere('slug', $selectedCategory), 'name', 'Shows') }}
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
                                @php
                                    $showModalData = [
                                        'title' => $show->title,
                                        'description' => trim(strip_tags($show->full_description ?: $show->description)),
                                        'cover_image' => $show->cover_image,
                                        'has_image' => !empty($show->cover_image),
                                        'category_name' => $show->category?->name ?? 'Show',
                                        'host_name' => $show->primaryHost?->name ?? 'TBA',
                                        'duration' => $show->typical_duration,
                                        'format' => ucfirst((string) $show->format),
                                        'listeners' => number_format((int) $show->total_listeners),
                                        'rating' => $show->average_rating ? number_format((float) $show->average_rating, 1) : null,
                                        'show_url' => route('shows.show', $show->slug),
                                    ];
                                @endphp
                                <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group">
                                    <div class="relative h-52 overflow-hidden">
                                        <button type="button"
                                            @click='openShowModal(@json($showModalData))'
                                            class="block h-full w-full text-left cursor-zoom-in">
                                            <x-initials-image
                                                :src="$show->cover_image"
                                                :title="$show->title"
                                                imgClass="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                                                fallbackClass="bg-emerald-700/90"
                                                textClass="text-3xl font-bold text-white"
                                            />
                                        </button>
                                        <div class="absolute top-4 left-4">
                                            <span class="px-3 py-1 bg-{{ $show->category?->color ?? 'emerald' }}-600 text-white text-xs font-semibold rounded-full">
                                                {{ $show->category?->name ?? 'Show' }}
                                            </span>
                                        </div>
                                        <div class="absolute right-4 bottom-4">
                                            <span class="inline-flex items-center rounded-full bg-black/60 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] text-white shadow-lg">
                                                <i class="fas fa-expand mr-2"></i> Preview
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
                                                <div class="relative w-10 h-10 rounded-full overflow-hidden">
                                                    <x-initials-image
                                                        :src="$show->primaryHost?->profile_photo"
                                                        :title="$show->primaryHost?->name ?? $show->title"
                                                        imgClass="w-full h-full object-cover"
                                                        fallbackClass="bg-emerald-700/90"
                                                        textClass="text-xs font-bold text-white"
                                                    />
                                                </div>
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

    <div x-cloak x-show="showModalOpen" x-transition.opacity class="fixed inset-0 z-[90]">
        <div class="absolute inset-0 bg-black/75 backdrop-blur-sm" @click="closeShowModal()"></div>

        <div class="relative flex min-h-screen items-center justify-center p-4 md:p-8">
            <div class="relative w-full max-w-6xl overflow-hidden rounded-[2rem] bg-white shadow-2xl">
                <button type="button" @click="closeShowModal()"
                    class="absolute right-4 top-4 z-10 inline-flex h-11 w-11 items-center justify-center rounded-full bg-slate-900/85 text-white transition hover:bg-slate-900">
                    <i class="fas fa-times"></i>
                </button>

                <div class="grid grid-cols-1 lg:grid-cols-[1.1fr_.9fr]">
                    <div class="bg-slate-950">
                        <template x-if="activeShow && activeShow.has_image">
                            <img :src="activeShow.cover_image"
                                :alt="activeShow.title"
                                class="h-full max-h-[82vh] w-full object-contain">
                        </template>

                        <template x-if="activeShow && !activeShow.has_image">
                            <div class="flex min-h-[24rem] items-center justify-center bg-gradient-to-br from-emerald-700 to-teal-800 text-7xl font-black text-white"
                                x-text="initials(activeShow.title)"></div>
                        </template>
                    </div>

                    <div class="flex flex-col p-6 md:p-8">
                        <div class="flex flex-wrap items-center gap-3 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-600">
                            <template x-if="activeShow && activeShow.category_name">
                                <span x-text="activeShow.category_name"></span>
                            </template>
                            <template x-if="activeShow && activeShow.format">
                                <span x-text="activeShow.format"></span>
                            </template>
                        </div>

                        <h3 class="mt-4 text-3xl font-black text-gray-900" x-text="activeShow ? activeShow.title : ''"></h3>

                        <template x-if="activeShow && activeShow.description">
                            <p class="mt-4 text-base leading-relaxed text-gray-600" x-text="activeShow.description"></p>
                        </template>

                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 rounded-2xl bg-gray-50 p-5 text-sm">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Host</p>
                                <p class="mt-1 font-semibold text-gray-900" x-text="activeShow ? activeShow.host_name : ''"></p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Duration</p>
                                <p class="mt-1 font-semibold text-gray-900">
                                    <span x-text="activeShow ? activeShow.duration : ''"></span> mins
                                </p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Listeners</p>
                                <p class="mt-1 font-semibold text-gray-900" x-text="activeShow ? activeShow.listeners : ''"></p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">Rating</p>
                                <p class="mt-1 font-semibold text-gray-900">
                                    <span x-text="activeShow && activeShow.rating ? activeShow.rating : 'Not rated yet'"></span>
                                </p>
                            </div>
                        </div>

                        <div class="mt-8">
                            <a :href="activeShow ? activeShow.show_url : '#'"
                                class="inline-flex items-center space-x-2 rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                <span>View Full Show</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
