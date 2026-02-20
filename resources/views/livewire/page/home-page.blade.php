<div wire:poll.60s="refreshHomeData">
    @normalizeArray($featuredShows)
    @normalizeArray($latestPodcastEpisodes)
    @normalizeArray($latestNews)
    @normalizeArray($latestBlogPosts)
    @normalizeArray($trendingBlogPosts)
    @normalizeArray($upcomingEvents)
    @normalizeArray($stats)
    @normalizeArray($testimonials)
    @normalizeArray($trendingNews)
    @normalizeArray($featuredNews)
    @normalizeArray($mostViewedNews)
    @normalizeArray($otherNews)
    @normalizeArray($homeContent)
    @normalizeArray($currentShow)
    <!-- Breaking News Banner (if exists) -->
    @if($breakingNews)
    <div class="bg-red-600 text-white py-3 sticky top-28 z-40 shadow-lg animate-pulse">
        <div class="container mx-auto px-4">
            <div class="flex items-center space-x-4">
                <span class="flex-shrink-0 px-3 py-1 bg-white text-red-600 font-bold text-sm rounded-full">
                    <i class="fas fa-bolt mr-1"></i> BREAKING
                </span>
                <marquee class="flex-1 font-semibold">
                    {{ $breakingNews->title }} - {{ $breakingNews->excerpt }}
                </marquee>
                <a href="/news/{{ $breakingNews->slug }}" class="flex-shrink-0 px-4 py-1 bg-white/20 hover:bg-white/30 rounded-full text-sm font-semibold transition-colors">
                    Read More <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Hero Section -->
    <section class="relative bg-orange-500 text-white overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>

        <div class="container mx-auto px-4 py-20 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center space-x-2 glass-panel px-4 py-2 rounded-full mb-6">
                        <span class="w-3 h-3 bg-lime-500 rounded-full glass-glow"></span>
                        <span class="text-sm font-semibold">{{ $homeContent['hero_badge'] ?? 'NOW LIVE ON AIR' }}</span>
                    </div>
                    
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold mb-6 leading-tight">
                        {{ $homeContent['hero_title'] ?? 'Your Voice,' }}<br>
                        <span class="text-emerald-200">{{ $homeContent['hero_highlight'] ?? 'Your Music' }}</span>
                    </h1>
                    
                    <p class="text-xl md:text-2xl text-emerald-100 mb-8 leading-relaxed">
                        {{ $homeContent['hero_subtitle'] ?? 'Broadcasting the heartbeat of the city of Akure, 24/7 on 99.1 FM' }}
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="{{ $homeContent['primary_cta_url'] ?? 'https://stream-176.zeno.fm/mwam2yirv1pvv' }}" @click.prevent="startLive"
                            class="w-full sm:w-auto inline-flex items-center justify-center space-x-3 px-8 py-4 bg-white text-emerald-700 font-bold rounded-full shadow-2xl hover:shadow-3xl transform hover:scale-105 transition-all duration-300">
                            <i class="fas fa-play-circle text-2xl"></i>
                            <span>{{ $homeContent['primary_cta_text'] ?? 'Listen Live Now' }}</span>
                        </a>
                        <a href="{{ $homeContent['secondary_cta_url'] ?? '/shows' }}" class="w-full sm:w-auto inline-flex items-center justify-center space-x-3 px-8 py-4 bg-amber-700 backdrop-blur-sm text-white font-semibold rounded-full border-2 border-white/30 hover:bg-white/10 transition-all duration-300">
                            <i class="fas fa-calendar-alt"></i>
                            <span>{{ $homeContent['secondary_cta_text'] ?? 'View Schedule' }}</span>
                        </a>
                    </div>

                    <!-- Current Show Info -->
                    <div class="mt-12 p-6 glass-panel rounded-2xl border border-white/20">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 glass-panel rounded-xl flex items-center justify-center">
                                    <i class="fas fa-microphone-alt text-3xl"></i>
                                </div>
                            </div>
                            <div class="flex-1 text-left">
                                <p class="text-sm text-emerald-200 mb-1">Now Playing</p>
                                @if($currentShow)
                                    <h3 class="text-lg font-bold">
                                        @if(!empty($currentShow['slug']))
                                            <a href="{{ route('shows.show', $currentShow['slug']) }}" class="hover:text-white">
                                                {{ $currentShow['title'] }}
                                            </a>
                                        @else
                                            {{ $currentShow['title'] ?? 'On Air' }}
                                        @endif
                                    </h3>
                                    <p class="text-sm text-emerald-100">
                                        @if(!empty($currentShow['host_slug']))
                                            <a href="{{ route('oaps.show', $currentShow['host_slug']) }}" class="hover:text-white">
                                                {{ $currentShow['host'] }}
                                            </a>
                                        @else
                                            {{ $currentShow['host'] ?? 'Live' }}
                                        @endif
                                        @if(!empty($currentShow['time']))
                                            <span class="mx-2">•</span>{{ $currentShow['time'] }}
                                            <span class="ml-2 text-[10px] text-emerald-200 font-semibold">WAT</span>
                                        @endif
                                    </p>
                                @else
                                    <h3 class="text-lg font-bold">No show scheduled</h3>
                                    <p class="text-sm text-emerald-100">Check the weekly schedule</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Radio Visualization -->
                <div class="relative">
                    <div class="relative z-10">
                        <!-- Main Circle -->
                        <div class="w-80 h-80 md:w-96 md:h-96 mx-auto glass-orb glass-sheen rounded-full border-8 border-white/20 flex items-center justify-center relative">
                            <div class="w-64 h-64 md:w-80 md:h-80 bg-gradient-to-br from-slate-900 to-slate-950 rounded-full flex items-center justify-center shadow-2xl">
                                <div class="text-center">
                                    <i class="fas fa-radio text-8xl text-white mb-4"></i>
                                    <div class="text-4xl font-bold text-white">99.1</div>
                                    <div class="text-xl text-emerald-100">FM</div>
                                </div>
                            </div>
                            
                            <!-- Pulse Rings -->
                            <div class="absolute inset-0 rounded-full border-4 border-white/30 glass-ripple"></div>
                            <div class="absolute inset-0 rounded-full border-4 border-white/20 glass-glow"></div>
                        </div>

                        <!-- Floating Elements -->
                        <div class="absolute top-10 -left-10 w-24 h-24 glass-panel rounded-2xl flex items-center justify-center transform rotate-12 glass-float">
                            <i class="fas fa-music text-4xl text-white"></i>
                        </div>
                        <div class="absolute bottom-20 -right-10 w-20 h-20 glass-panel rounded-full flex items-center justify-center glass-float-slow">
                            <i class="fas fa-heart text-3xl text-red-400"></i>
                        </div>
                        <div class="absolute top-1/2 -right-20 w-16 h-16 glass-panel rounded-xl flex items-center justify-center transform -rotate-12 glass-float">
                            <i class="fas fa-star text-2xl text-yellow-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave Bottom -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 80C1200 80 1320 70 1380 65L1440 60V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
            </svg>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($stats as $stat)
                    @continueIfNotArray($stat)
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-2xl mb-4">
                            <i class="{{ $stat['icon'] }} text-3xl text-emerald-600"></i>
                        </div>
                        <div class="text-4xl font-bold text-gray-900 mb-2">{{ $stat['number'] }}</div>
                        <div class="text-gray-600 font-medium">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Trending News Ticker -->
    @if(count($trendingNews) > 0)
    <section class="py-4 bg-emerald-600 text-white">
        <div class="container mx-auto px-4">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 flex items-center space-x-2 font-bold">
                    <i class="fas fa-fire text-orange-300"></i>
                    <span>TRENDING:</span>
                </div>
                <div class="flex-1 overflow-hidden">
                    <div class="flex animate-scroll space-x-8">
                    @foreach($trendingNews as $trending)
                        @continueIfNotArray($trending)
                            <a href="/news/{{ $trending['slug'] }}" class="flex-shrink-0 hover:text-emerald-200 transition-colors">
                                <span class="font-semibold">{{ $trending['title'] }}</span>
                                <span class="text-emerald-200 text-sm ml-2">• {{ $trending['views'] }} views</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif


    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <x-ad-slot placement="home" />
            </div>
        </div>
    </section>

    <!-- Latest News Section - REAL DATA -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Latest News</h2>
                    <p class="text-xl text-gray-600">Stay updated with the latest from Glow FM</p>
                </div>
                <a href="/news" class="hidden md:inline-flex items-center space-x-2 text-emerald-600 font-semibold hover:text-emerald-700 transition-colors">
                    <span>View All</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            @if(count($latestNews) > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($latestNews as $news)
                        @continueIfNotArray($news)
                        <article class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden group">
                            <div class="relative h-56 overflow-hidden">
                                <x-initials-image
                                    :src="$news['image'] ?? null"
                                    :title="$news['title'] ?? ''"
                                    imgClass="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                                    fallbackClass="bg-emerald-700/90"
                                    textClass="text-3xl font-bold text-white"
                                />
                                <div class="absolute top-4 left-4">
                                    <span class="px-3 py-1 bg-emerald-600 text-white text-xs font-semibold rounded-full">
                                        {{ $news['category'] ?? 'News' }}
                                    </span>
                                </div>
                                <div class="absolute bottom-4 right-4 flex items-center space-x-2">
                                    <span class="px-2 py-1 bg-black/70 text-white text-xs rounded-full">
                                        <i class="fas fa-eye mr-1"></i> {{ $news['views'] }}
                                    </span>
                                    <span class="px-2 py-1 bg-black/70 text-white text-xs rounded-full">
                                        <i class="fas fa-heart mr-1"></i> {{ $news['likes'] }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center space-x-4 text-sm text-gray-500 mb-3">
                                    <span class="flex items-center space-x-1">
                                        <i class="fas fa-calendar text-xs"></i>
                                        <span>{{ $news['date'] }}</span>
                                    </span>
                                    <span class="flex items-center space-x-1">
                                        <i class="fas fa-clock text-xs"></i>
                                        <span>{{ $news['read_time'] }}</span>
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-emerald-600 transition-colors">
                                    <a href="/news/{{ $news['slug'] }}">{{ $news['title'] }}</a>
                                </h3>
                                <p class="text-gray-600 mb-4 line-clamp-3">{{ $news['excerpt'] }}</p>
                                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                    <span class="inline-flex items-center space-x-2 text-sm text-gray-600">
                                        <img src="{{ asset('glowfm logo.jpeg') }}" alt="Glow FM" class="w-5 h-5 rounded-full object-cover">
                                        <span>Glow FM</span>
                                    </span>
                                    <a href="/news/{{ $news['slug'] }}" class="inline-flex items-center space-x-2 text-emerald-600 font-semibold hover:text-emerald-700 transition-colors">
                                        <span>Read More</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <i class="fas fa-newspaper text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No News Available</h3>
                    <p class="text-gray-600">Check back soon for the latest updates!</p>
                </div>
            @endif

            <div class="text-center mt-12">
                <a href="/news" class="inline-flex items-center space-x-2 px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    <span>View All News</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Newsroom Spotlight Section -->
    <section class="relative py-24 bg-gradient-to-br from-amber-50 via-white to-emerald-50 overflow-hidden"
        x-data="{
            loading: false,
            initObserver() {
                const observer = new IntersectionObserver((entries) => {
                    if (!entries[0].isIntersecting) return;
                    if (this.loading || !$wire.get('newsHasMore')) return;
                    this.loading = true;
                    $wire.loadMoreNews().then(() => { this.loading = false; });
                }, { rootMargin: '200px' });
                observer.observe(this.$refs.newsSentinel);
            }
        }"
        x-init="initObserver()"
    >
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-200/40 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-amber-200/50 rounded-full blur-3xl"></div>
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 1px 1px, rgba(15, 23, 42, 0.12) 1px, transparent 0); background-size: 24px 24px;"></div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
                <div>
                    <p class="uppercase tracking-[0.3em] text-xs text-emerald-700 font-semibold">Newsroom</p>
                    <h2 class="text-4xl md:text-6xl font-black text-slate-900 mt-2" style="font-family: 'Fraunces', 'Iowan Old Style', 'Palatino Linotype', serif;">
                        Featured, Most Viewed, and Fresh Drops
                    </h2>
                    <p class="text-lg text-slate-600 mt-4 max-w-2xl">
                        Curated highlights and what the city is reading right now — refreshed in batches as you scroll.
                    </p>
                </div>
                <a href="/news" class="inline-flex items-center space-x-2 text-emerald-700 font-semibold hover:text-emerald-800 transition-colors">
                    <span>Explore Newsroom</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            @if(count($trendingNews) > 0)
                <div class="flex flex-wrap gap-3 mb-10">
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-semibold rounded-full bg-slate-900 text-white">
                        <i class="fas fa-fire text-amber-300"></i> Trending Now
                    </span>
                    @foreach($trendingNews as $trend)
                        @continueIfNotArray($trend)
                        <a href="/news/{{ $trend['slug'] ?? '#' }}" class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-semibold rounded-full bg-white/80 border border-emerald-100 text-emerald-800 hover:bg-emerald-600 hover:text-white transition-colors">
                            <span>{{ $trend['title'] }}</span>
                            <span class="text-[10px] opacity-70">{{ $trend['published_at'] }}</span>
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <div class="lg:col-span-8 space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl font-bold text-slate-900">Featured News</h3>
                        <span class="text-sm font-semibold text-emerald-700 bg-emerald-100/80 px-3 py-1 rounded-full">Editor's picks</span>
                    </div>

                    @php $mainFeatured = $featuredNews[0] ?? null; @endphp
                    @if($mainFeatured)
                        <article class="group relative overflow-hidden rounded-3xl shadow-xl bg-white">
                            <div class="relative h-80">
                                <x-initials-image
                                    :src="$mainFeatured['image'] ?? null"
                                    :title="$mainFeatured['title'] ?? ''"
                                    imgClass="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                                    fallbackClass="bg-emerald-700/90"
                                    textClass="text-4xl font-bold text-white"
                                />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                                <div class="absolute bottom-6 left-6 right-6">
                                    <span class="inline-flex items-center gap-2 px-3 py-1 text-xs font-semibold rounded-full bg-white/90 text-emerald-700">
                                        {{ $mainFeatured['category'] ?? 'News' }}
                                    </span>
                                    <h4 class="text-2xl md:text-3xl font-bold text-white mt-3">
                                        <a href="/news/{{ $mainFeatured['slug'] }}">{{ $mainFeatured['title'] }}</a>
                                    </h4>
                                    <p class="text-sm text-white/80 mt-2 line-clamp-2">{{ $mainFeatured['excerpt'] }}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100">
                                <div class="flex items-center gap-4 text-sm text-slate-500">
                                    <span class="inline-flex items-center gap-1"><i class="fas fa-clock text-xs"></i>{{ $mainFeatured['read_time'] }}</span>
                                    <span class="inline-flex items-center gap-1"><i class="fas fa-eye text-xs"></i>{{ $mainFeatured['views'] }}</span>
                                </div>
                                <a href="/news/{{ $mainFeatured['slug'] }}" class="inline-flex items-center gap-2 text-emerald-700 font-semibold">
                                    <span>Read Story</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                    @endif

                    @if(count($featuredNews) > 1)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach(array_slice($featuredNews, 1) as $news)
                                @continueIfNotArray($news)
                                <article class="group rounded-2xl overflow-hidden bg-white shadow-lg hover:shadow-2xl transition-all duration-300">
                                    <div class="relative h-48">
                                        <x-initials-image
                                            :src="$news['image'] ?? null"
                                            :title="$news['title'] ?? ''"
                                            imgClass="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                                            fallbackClass="bg-slate-800"
                                            textClass="text-2xl font-bold text-white"
                                        />
                                        <span class="absolute top-4 left-4 px-3 py-1 bg-emerald-600 text-white text-xs font-semibold rounded-full">
                                            {{ $news['category'] ?? 'News' }}
                                        </span>
                                    </div>
                                    <div class="p-5">
                                        <p class="text-xs uppercase tracking-widest text-emerald-700 font-semibold">{{ $news['date'] }}</p>
                                        <h4 class="text-lg font-bold text-slate-900 mt-2 group-hover:text-emerald-700 transition-colors">
                                            <a href="/news/{{ $news['slug'] }}">{{ $news['title'] }}</a>
                                        </h4>
                                        <p class="text-sm text-slate-600 mt-2 line-clamp-2">{{ $news['excerpt'] }}</p>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="lg:col-span-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-2xl font-bold text-slate-900">Most Viewed</h3>
                        <span class="text-xs font-semibold text-amber-700 bg-amber-100/80 px-3 py-1 rounded-full">Top reads</span>
                    </div>

                    <div class="space-y-4">
                        @forelse($mostViewedNews as $index => $news)
                            @continueIfNotArray($news)
                            <a href="/news/{{ $news['slug'] }}" class="group flex items-start gap-4 p-4 rounded-2xl bg-white/80 border border-white hover:bg-white shadow-sm hover:shadow-lg transition-all duration-300">
                                <div class="text-2xl font-black text-emerald-700/60 w-8">{{ $index + 1 }}</div>
                                <div class="flex-1">
                                    <p class="text-xs uppercase tracking-widest text-amber-700 font-semibold">{{ $news['category'] ?? 'News' }}</p>
                                    <h4 class="text-base font-bold text-slate-900 group-hover:text-emerald-700 transition-colors mt-1">
                                        {{ $news['title'] }}
                                    </h4>
                                    <div class="flex items-center gap-3 text-xs text-slate-500 mt-2">
                                        <span class="inline-flex items-center gap-1"><i class="fas fa-eye text-[10px]"></i>{{ $news['views'] }}</span>
                                        <span class="inline-flex items-center gap-1"><i class="fas fa-clock text-[10px]"></i>{{ $news['date'] }}</span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="p-6 rounded-2xl bg-white text-center text-slate-500">
                                No popular stories yet.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="mt-16">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-slate-900">More News</h3>
                    <span class="text-sm text-slate-500">Keep scrolling for more updates</span>
                </div>

                @if(count($otherNews) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($otherNews as $news)
                            @continueIfNotArray($news)
                            <article class="group rounded-2xl overflow-hidden bg-white shadow-md hover:shadow-xl transition-all duration-300">
                                <div class="relative h-48 overflow-hidden">
                                    <x-initials-image
                                        :src="$news['image'] ?? null"
                                        :title="$news['title'] ?? ''"
                                        imgClass="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                                        fallbackClass="bg-emerald-700/80"
                                        textClass="text-2xl font-bold text-white"
                                    />
                                    <div class="absolute top-4 left-4">
                                        <span class="px-3 py-1 bg-slate-900/80 text-white text-xs font-semibold rounded-full">
                                            {{ $news['category'] ?? 'News' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-5">
                                    <div class="flex items-center gap-3 text-xs text-slate-500">
                                        <span class="inline-flex items-center gap-1"><i class="fas fa-calendar text-[10px]"></i>{{ $news['date'] }}</span>
                                        <span class="inline-flex items-center gap-1"><i class="fas fa-clock text-[10px]"></i>{{ $news['read_time'] }}</span>
                                    </div>
                                    <h4 class="text-lg font-bold text-slate-900 mt-2 group-hover:text-emerald-700 transition-colors">
                                        <a href="/news/{{ $news['slug'] }}">{{ $news['title'] }}</a>
                                    </h4>
                                    <p class="text-sm text-slate-600 mt-2 line-clamp-2">{{ $news['excerpt'] }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-2xl shadow-lg p-10 text-center text-slate-500">
                        No additional news to show yet.
                    </div>
                @endif

                <div class="mt-10 flex items-center justify-center">
                    <div x-ref="newsSentinel" class="w-full max-w-sm">
                        @if($newsHasMore)
                            <div class="flex items-center justify-center gap-3 text-sm text-slate-500">
                                <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Loading more stories...</span>
                            </div>
                            <div wire:loading.flex wire:target="loadMoreNews" class="mt-3 justify-center text-xs text-emerald-700">
                                Fetching the next batch
                            </div>
                        @else
                            <div class="text-sm text-slate-500 text-center">You're all caught up.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Latest Blog Posts Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-12">
            <div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Latest Blog Posts</h2>
                <p class="text-xl text-gray-600">Deep insights and articles from our blog</p>
            </div>
            <a href="/blog" class="hidden md:inline-flex items-center space-x-2 text-purple-600 font-semibold hover:text-purple-700 transition-colors">
                <span>View All Posts</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        @if(count($latestBlogPosts) > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($latestBlogPosts as $post)
                    @continueIfNotArray($post)
                    <article class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 group">
                        <div class="relative h-56 overflow-hidden">
                            <x-initials-image
                                :src="$post['image'] ?? null"
                                :title="$post['title'] ?? ''"
                                imgClass="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                                fallbackClass="bg-purple-700/90"
                                textClass="text-3xl font-bold text-white"
                            />
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 bg-{{ $post['category_color'] ?? 'purple' }}-600 text-white text-xs font-semibold rounded-full">
                                    {{ $post['category'] ?? 'Blog' }}
                                </span>
                            </div>
                            <div class="absolute bottom-4 right-4 flex items-center space-x-2">
                                <span class="px-2 py-1 bg-black/70 text-white text-xs rounded-full">
                                    <i class="fas fa-eye mr-1"></i> {{ $post['views'] }}
                                </span>
                                <span class="px-2 py-1 bg-black/70 text-white text-xs rounded-full">
                                    <i class="fas fa-comment mr-1"></i> {{ $post['comments_count'] }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center space-x-4 text-sm text-gray-500 mb-3">
                                <span class="flex items-center space-x-1">
                                    <i class="fas fa-calendar text-xs"></i>
                                    <span>{{ $post['date'] }}</span>
                                </span>
                                <span class="flex items-center space-x-1">
                                    <i class="fas fa-clock text-xs"></i>
                                    <span>{{ $post['read_time'] }}</span>
                                </span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-purple-600 transition-colors">
                                <a href="/blog/{{ $post['slug'] }}">{{ $post['title'] }}</a>
                            </h3>
                            <p class="text-gray-600 mb-4 line-clamp-3">{{ $post['excerpt'] }}</p>
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <span class="text-sm text-gray-600">
                                    <i class="fas fa-user-circle mr-1"></i> {{ $post['author'] }}
                                </span>
                                <a href="/blog/{{ $post['slug'] }}" class="inline-flex items-center space-x-2 text-purple-600 font-semibold hover:text-purple-700 transition-colors">
                                    <span>Read More</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <i class="fas fa-blog text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No Blog Posts Available</h3>
                <p class="text-gray-600">Check back soon for insightful articles!</p>
            </div>
        @endif

        <div class="text-center mt-12">
            <a href="/blog" class="inline-flex items-center space-x-2 px-8 py-4 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                <span>View All Blog Posts</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- Trending Blog Posts Ticker -->
@if(count($trendingBlogPosts) > 0)
<section class="py-4 bg-purple-600 text-white">
    <div class="container mx-auto px-4">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0 flex items-center space-x-2 font-bold">
                <i class="fas fa-fire text-orange-300"></i>
                <span>TRENDING BLOG:</span>
            </div>
            <div class="flex-1 overflow-hidden">
                <div class="flex animate-scroll space-x-8">
                @foreach($trendingBlogPosts as $trending)
                    @continueIfNotArray($trending)
                    <a href="/blog/{{ $trending['slug'] }}" class="flex-shrink-0 hover:text-purple-200 transition-colors">
                            <span class="font-semibold">{{ $trending['title'] }}</span>
                            <span class="text-purple-200 text-sm ml-2">• {{ number_format($trending['views']) }} views</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

    <!-- Upcoming Events Section -->
    <section class="py-20 bg-amber-600 text-white relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">Upcoming Events</h2>
                <p class="text-xl text-emerald-100 max-w-2xl mx-auto">
                    Join us at our exclusive events and experience the magic of live entertainment
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($upcomingEvents as $event)
                    @continueIfNotArray($event)
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl overflow-hidden border border-white/20 hover:bg-white/20 transition-all duration-300 group">
                        <div class="relative h-48 overflow-hidden">
                            <x-initials-image
                                :src="$event['image'] ?? null"
                                :title="$event['title'] ?? ''"
                                imgClass="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                                fallbackClass="bg-emerald-700/90"
                                textClass="text-3xl font-bold text-white"
                            />
                            <div class="absolute top-4 right-4 bg-white text-emerald-600 px-3 py-1 rounded-full text-sm font-semibold">
                                <i class="fas fa-users mr-1"></i> {{ $event['attendees'] ?? '0' }}
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-2xl font-bold mb-3">{{ $event['title'] }}</h3>
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center space-x-2 text-emerald-100">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>{{ $event['date'] }}</span>
                                </div>
                                <div class="flex items-center space-x-2 text-emerald-100">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $event['time'] }}</span>
                                </div>
                                <div class="flex items-center space-x-2 text-emerald-100">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $event['location'] }}</span>
                                </div>
                            </div>
                            <a href="#" class="inline-flex items-center space-x-2 text-white font-semibold hover:text-emerald-200 transition-colors">
                                <span>Get Tickets</span>
                                <i class="fas fa-ticket-alt"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="/events" class="inline-flex items-center space-x-2 px-8 py-4 bg-white text-emerald-700 font-semibold rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    <span>View All Events</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Meet Our Podcast Section -->
<!-- Latest Podcast Episodes Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Latest Podcast Episodes</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Listen to our newest podcast episodes featuring amazing conversations and stories
            </p>
        </div>

        @if(count($latestPodcastEpisodes) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($latestPodcastEpisodes as $episode)
                @continueIfNotArray($episode)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                        <div class="relative h-48 overflow-hidden">
                            <x-initials-image
                                :src="$episode['image'] ?? null"
                                :title="$episode['title'] ?? ''"
                                imgClass="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                                fallbackClass="bg-purple-700/90"
                                textClass="text-3xl font-bold text-white"
                            />
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <a href="{{ route('podcasts.episode', [$episode['show_slug'], $episode['slug']]) }}" 
                                   class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center text-white text-2xl transform scale-75 group-hover:scale-100 transition-transform">
                                    <i class="fas fa-play ml-1"></i>
                                </a>
                            </div>
                            <div class="absolute top-3 right-3">
                                <span class="px-2 py-1 bg-black/70 text-white text-xs rounded-full">
                                    {{ $episode['duration'] }}
                                </span>
                            </div>
                            @if($episode['season_episode'])
                            <div class="absolute top-3 left-3">
                                <span class="px-2 py-1 bg-purple-600 text-white text-xs font-bold rounded-full">
                                    {{ $episode['season_episode'] }}
                                </span>
                            </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <p class="text-xs text-purple-600 font-semibold mb-2">{{ $episode['show_title'] }}</p>
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-purple-600 transition-colors">
                                <a href="{{ route('podcasts.episode', [$episode['show_slug'], $episode['slug']]) }}">
                                    {{ $episode['title'] }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $episode['description'] }}</p>
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span><i class="fas fa-calendar mr-1"></i>{{ $episode['published_at'] }}</span>
                                <span><i class="fas fa-headphones mr-1"></i>{{ $episode['plays'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-podcast text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 text-lg">No podcast episodes available yet. Check back soon!</p>
            </div>
        @endif

        <div class="text-center mt-12">
            <a href="{{ route('podcasts.index') }}" class="inline-flex items-center space-x-2 px-8 py-4 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                <span>Browse All Podcasts</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>



    <!-- Featured Shows Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Featured Shows</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Tune in to our most popular programs featuring the best Podcasts and music selection
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($featuredShows as $show)
                    @continueIfNotArray($show)
                    @php($showSlug = $show['slug'] ?? null)
                    <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2">
                        @if($showSlug)
                            <a href="{{ route('shows.show', $showSlug) }}" class="relative h-64 overflow-hidden block">
                        @else
                            <div class="relative h-64 overflow-hidden block">
                        @endif
                            <x-initials-image
                                :src="$show['image'] ?? null"
                                :title="$show['title'] ?? ''"
                                imgClass="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                                fallbackClass="bg-emerald-700/90"
                                textClass="text-4xl font-bold text-white"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                            <div class="absolute top-4 right-4">
                                <span class="px-3 py-1 bg-emerald-600 text-white text-xs font-semibold rounded-full">
                                    {{ $show['category'] ?? 'Show' }}
                                </span>
                            </div>
                            <div class="absolute bottom-4 left-4 right-4">
                                <h3 class="text-2xl font-bold text-white mb-1">{{ $show['title'] }}</h3>
                                <p class="text-emerald-300 text-sm">{{ $show['days'] }}</p>
                            </div>
                        @if($showSlug)
                            </a>
                        @else
                            </div>
                        @endif
                        <div class="p-6">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-microphone text-white"></i>
                                </div>
                                <div>
                                    @if(!empty($show['host_slug']))
                                        <a href="{{ route('oaps.show', $show['host_slug']) }}" class="font-semibold text-gray-900 hover:text-emerald-600 transition-colors">
                                            {{ $show['host'] }}
                                        </a>
                                    @else
                                        <p class="font-semibold text-gray-900">{{ $show['host'] }}</p>
                                    @endif
                                    <p class="text-sm text-emerald-600">{{ $show['time'] }}</p>
                                </div>
                            </div>
                            <p class="text-gray-600 mb-4">{{ $show['description'] }}</p>
                            @if($showSlug)
                                <a href="{{ route('shows.show', $showSlug) }}" class="inline-flex items-center space-x-2 text-emerald-600 font-semibold hover:text-emerald-700 transition-colors">
                                    <span>Learn More</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            @else
                                <span class="inline-flex items-center space-x-2 text-gray-400 font-semibold cursor-not-allowed">
                                    <span>Learn More</span>
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="/shows" class="inline-flex items-center space-x-2 px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    <span>View All Shows</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">What Our Listeners Say</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Real feedback from our amazing community of listeners
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($testimonials as $testimonial)
                    @continueIfNotArray($testimonial)
                    <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center mb-4">
                            @for($i = 0; $i < $testimonial['rating']; $i++)
                                <i class="fas fa-star text-yellow-400 text-xl"></i>
                            @endfor
                        </div>
                        <p class="text-gray-600 mb-6 italic leading-relaxed">
                            "{{ $testimonial['message'] }}"
                        </p>
                        <div class="flex items-center space-x-4">
                            <img src="{{ $testimonial['avatar'] }}" alt="{{ $testimonial['name'] }}" class="w-14 h-14 rounded-full">
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $testimonial['name'] }}</h4>
                                <p class="text-sm text-gray-600">{{ $testimonial['role'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-orange-400 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl md:text-5xl font-bold mb-6">Ready to Tune In?</h2>
                <p class="text-xl text-emerald-100 mb-8 leading-relaxed">
                    Join over 1 million listeners and experience the best radio station in the city. 
                    Listen live now or request your favorite song!
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="#" class="w-full sm:w-auto inline-flex items-center justify-center space-x-3 px-8 py-4 bg-white text-emerald-700 font-bold rounded-full shadow-2xl hover:shadow-3xl transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-play-circle text-2xl"></i>
                        <span>Start Listening</span>
                    </a>
                    <a href="/contact" class="w-full sm:w-auto inline-flex items-center justify-center space-x-3 px-8 py-4 bg-emerald-500/20 backdrop-blur-sm text-white font-semibold rounded-full border-2 border-white/30 hover:bg-white/10 transition-all duration-300">
                        <i class="fas fa-envelope"></i>
                        <span>Contact Us</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
