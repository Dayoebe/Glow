<div
    x-data="{
        imageViewerOpen: false,
        activeImage: null,
        openImage(image) {
            if (!image || !image.src) return;
            this.activeImage = image;
            this.imageViewerOpen = true;
            document.documentElement.classList.add('overflow-hidden');
        },
        closeImage() {
            this.imageViewerOpen = false;
            this.activeImage = null;
            document.documentElement.classList.remove('overflow-hidden');
        }
    }"
    @keydown.escape.window="closeImage()"
    class="min-h-screen bg-gray-50"
>
    
    <!-- Breaking News Banner -->
    @if($news->is_breaking)
    <div class="bg-gradient-to-r from-red-600 via-red-500 to-orange-500 text-white py-3 sticky top-0 z-50 shadow-lg animate-pulse">
        <div class="container mx-auto px-4 flex items-center justify-center space-x-3">
            <x-ad-slot placement="news-detail" />
            <span class="flex items-center space-x-2 font-bold text-lg">
                @if($news->breaking === 'urgent')
                <i class="fas fa-exclamation-triangle animate-bounce"></i>
                <span>🚨 URGENT</span>
                @else
                <i class="fas fa-bolt"></i>
                <span>⚡ BREAKING NEWS</span>
                @endif
            </span>
        </div>
    </div>
    @endif

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-900 text-white py-16">
        <div class="absolute inset-0 opacity-20">
            <x-initials-image
                :src="$news->featured_image"
                :title="$news->title"
                imgClass="w-full h-full object-cover"
                fallbackClass="bg-emerald-800/60"
                textClass="text-6xl font-bold text-white/80"
            />
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto">
                <!-- Breadcrumb -->
                <nav class="flex items-center space-x-2 text-sm text-emerald-200 mb-6">
                    <a href="{{ route('news') }}" class="hover:text-white">News</a>
                    <span>›</span>
                    <a href="{{ route('news') }}?selectedCategory={{ $news->category->slug }}" class="hover:text-white">
                        {{ $news->category->name }}
                    </a>
                    <span>›</span>
                    <span class="text-white">Article</span>
                </nav>

                <!-- Category Badge -->
                <div class="flex flex-wrap items-center gap-3 mb-6">
                    <span class="px-4 py-2 bg-{{ $news->category->slug === 'station-news' ? 'blue' : ($news->category->slug === 'music' ? 'purple' : ($news->category->slug === 'interviews' ? 'amber' : 'pink')) }}-600 text-white font-bold rounded-full">
                        {{ $news->category->name }}
                    </span>
                    @if($news->video_url)
                    <span class="px-4 py-2 bg-red-600 text-white font-bold rounded-full">
                        <i class="fas fa-play mr-2"></i>Video
                    </span>
                    @endif
                    @if($news->gallery && count($news->gallery) > 0)
                    <span class="px-4 py-2 bg-blue-600 text-white font-bold rounded-full">
                        <i class="fas fa-images mr-2"></i>{{ count($news->gallery) }} Photos
                    </span>
                    @endif
                </div>

                <!-- Title -->
                <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">{{ $news->title }}</h1>

                <!-- Meta Info -->
                <div class="flex flex-wrap items-center gap-6 mb-8">
                    @php
                        $stationSettings = \App\Models\Setting::get('station', []);
                        $stationName = data_get($stationSettings, 'name', 'Glow FM');
                        $stationLogoUrl = data_get($stationSettings, 'logo_url', '');
                        if (!empty($stationLogoUrl) && !\Illuminate\Support\Str::startsWith($stationLogoUrl, ['http://', 'https://'])) {
                            $stationLogoUrl = url($stationLogoUrl);
                        }
                    @endphp
                    <div class="flex items-center space-x-3">
                        @if (!empty($stationLogoUrl))
                            <img src="{{ $stationLogoUrl }}" alt="{{ $stationName }} logo"
                                 class="w-12 h-12 rounded-full border-2 border-emerald-300 object-contain bg-white">
                        @else
                            <div class="w-12 h-12 rounded-full border-2 border-emerald-300 bg-emerald-600 flex items-center justify-center">
                                <i class="fas fa-radio text-white"></i>
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold">{{ $stationName }}</p>
                            <p class="text-sm text-emerald-200">Official Update</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-4 text-sm text-emerald-200">
                        <span><i class="fas fa-calendar mr-1"></i>{{ $news->formatted_published_date }}</span>
                        <span><i class="fas fa-clock mr-1"></i>{{ $news->read_time }}</span>
                        <span><i class="fas fa-eye mr-1"></i>{{ number_format($news->views) }} views</span>
                        <span><i class="fas fa-share-alt mr-1"></i>{{ number_format($news->shares) }} shares</span>
                    </div>
                </div>

                <!-- Quick Reactions Preview -->
                <div class="flex items-center space-x-4 p-4 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20">
                    @foreach(['love' => '❤️', 'fire' => '🔥', 'wow' => '😮', 'insightful' => '💡'] as $type => $emoji)
                    <button wire:click="toggleReaction('{{ $type }}')" 
                            class="flex items-center space-x-2 px-3 py-2 rounded-lg transition-all {{ isset($userReactions[$type]) ? 'bg-white/20' : 'hover:bg-white/10' }}">
                        <span class="text-2xl">{{ $emoji }}</span>
                        <span class="font-semibold">{{ $reactions[$type] ?? 0 }}</span>
                    </button>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 gap-8 xl:grid-cols-[minmax(0,1fr)_21rem]">
            <!-- Article Content -->
            <main class="min-w-0">
                <article class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    
                    <!-- Featured Image -->
                    @php
                        $featuredImageViewer = [
                            'src' => $news->featured_image,
                            'title' => $news->title,
                            'label' => 'Featured image',
                        ];
                    @endphp
                    <div class="relative h-72 bg-emerald-900 sm:h-96">
                        @if($news->featured_image)
                        <button type="button"
                                @click='openImage(@json($featuredImageViewer))'
                                class="group relative block h-full w-full cursor-zoom-in overflow-hidden text-left"
                                aria-label="Open full image for {{ $news->title }}">
                            <x-initials-image
                                :src="$news->featured_image"
                                :title="$news->title"
                                imgClass="w-full h-full object-cover transition duration-500 group-hover:scale-105"
                                fallbackClass="bg-emerald-700/90"
                                textClass="text-5xl font-bold text-white"
                            />
                            <span class="absolute bottom-4 right-4 inline-flex items-center rounded-full bg-black/70 px-4 py-2 text-sm font-semibold text-white shadow-lg backdrop-blur-sm transition group-hover:bg-black/85">
                                <i class="fas fa-search-plus mr-2"></i>View full image
                            </span>
                        </button>
                        @else
                        <x-initials-image
                            :src="$news->featured_image"
                            :title="$news->title"
                            imgClass="w-full h-full object-cover"
                            fallbackClass="bg-emerald-700/90"
                            textClass="text-5xl font-bold text-white"
                        />
                        @endif
                    </div>

                    <!-- Video Embed -->
                    @if($news->video_url)
                    <div class="aspect-video">
                        <iframe src="{{ $news->video_url }}" 
                                class="w-full h-full" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                        </iframe>
                    </div>
                    @endif

                    <!-- Content -->
                    <div class="p-8 md:p-12">
                        <!-- Article Body -->
                        <div class="prose prose-lg max-w-none mb-12">
                            {!! $news->content !!}
                        </div>

                        <!-- Gallery -->
                        @if($news->gallery && count($news->gallery) > 0)
                        <div class="mb-12">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-images text-emerald-600 mr-3"></i>
                                Photo Gallery
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($news->gallery as $image)
                                @php
                                    $galleryImageViewer = [
                                        'src' => $image,
                                        'title' => $news->title,
                                        'label' => 'Gallery image ' . $loop->iteration,
                                    ];
                                @endphp
                                <button type="button"
                                        @click='openImage(@json($galleryImageViewer))'
                                        class="group relative h-64 w-full overflow-hidden rounded-xl text-left cursor-zoom-in"
                                        aria-label="Open gallery image {{ $loop->iteration }} for {{ $news->title }}">
                                    <x-initials-image
                                        :src="$image"
                                        :title="$news->title"
                                        imgClass="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-300"
                                        fallbackClass="bg-emerald-700/90"
                                        textClass="text-4xl font-bold text-white"
                                    />
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                        <i class="fas fa-search-plus text-white text-2xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                    </div>
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Tags -->
                        @if($news->tags && count($news->tags) > 0)
                        <div class="mb-12 pb-8 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-tags text-emerald-600 mr-2"></i>
                                Tags
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($news->tags as $tag)
                                <a href="{{ route('news', ['tag' => $tag]) }}" 
                                   class="px-4 py-2 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 rounded-full transition-colors">
                                    #{{ $tag }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Engagement Section -->
                        <div class="mb-12 pb-8 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">How do you feel about this article?</h3>
                            <div class="flex flex-wrap gap-3">
                                @foreach(['love' => '❤️ Love it', 'fire' => '🔥 Hot take', 'wow' => '😮 Surprising', 'insightful' => '💡 Insightful'] as $type => $label)
                                <button wire:click="toggleReaction('{{ $type }}')" 
                                        class="px-6 py-3 rounded-lg font-semibold transition-all {{ isset($userReactions[$type]) ? 'bg-emerald-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}">
                                    {{ $label }}
                                    @if(($reactions[$type] ?? 0) > 0)
                                    <span class="ml-2 px-2 py-1 bg-white/20 rounded-full text-sm">{{ $reactions[$type] }}</span>
                                    @endif
                                </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Share Section -->
                        <div class="mb-12 border-y border-gray-200 py-6 select-none">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <h3 class="text-lg font-bold text-gray-900">Share this story</h3>
                                <div class="flex flex-wrap gap-2">
                                    <button wire:click="shareNews('x')"
                                        class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-gray-900 text-white transition-colors hover:bg-black"
                                        aria-label="Share on X">
                                        <i class="fab fa-x-twitter"></i>
                                    </button>
                                    <button wire:click="shareNews('facebook')"
                                            class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-blue-600 text-white transition-colors hover:bg-blue-700"
                                            aria-label="Share on Facebook">
                                        <i class="fab fa-facebook"></i>
                                    </button>
                                    <button wire:click="shareNews('instagram')"
                                            class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-pink-500 text-white transition-colors hover:bg-pink-600"
                                            aria-label="Share on Instagram">
                                        <i class="fab fa-instagram"></i>
                                    </button>
                                    <button wire:click="shareNews('linkedin')"
                                            class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-blue-700 text-white transition-colors hover:bg-blue-800"
                                            aria-label="Share on LinkedIn">
                                        <i class="fab fa-linkedin"></i>
                                    </button>
                                    <button wire:click="shareNews('whatsapp')"
                                        class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-green-500 text-white transition-colors hover:bg-green-600"
                                        aria-label="Share on WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </button>
                                    <button wire:click="shareNews('telegram')"
                                        class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-blue-400 text-white transition-colors hover:bg-blue-500"
                                        aria-label="Share on Telegram">
                                        <i class="fab fa-telegram"></i>
                                    </button>
                                    <button wire:click="shareNews('reddit')"
                                        class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-orange-500 text-white transition-colors hover:bg-orange-600"
                                        aria-label="Share on Reddit">
                                        <i class="fab fa-reddit-alien"></i>
                                    </button>
                                    <button wire:click="shareNews('email')"
                                        class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-gray-200 text-gray-800 transition-colors hover:bg-gray-300"
                                        aria-label="Share by email">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                    <button type="button" data-copy-link="{{ url()->current() }}"
                                        class="inline-flex h-11 items-center space-x-2 rounded-full bg-gray-100 px-4 text-gray-800 transition-colors hover:bg-gray-200">
                                        <i class="fas fa-link"></i><span data-copy-text>Copy link</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Author Bio -->
                        <div class="mb-12">
                            <div class="flex items-start space-x-6 p-6 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl">
                                <div class="relative w-20 h-20 rounded-full overflow-hidden">
                                    <x-initials-image
                                        :src="$news->author->avatar ?? null"
                                        :title="$news->author->name ?? ''"
                                        imgClass="w-full h-full object-cover"
                                        fallbackClass="bg-emerald-700/90"
                                        textClass="text-xl font-bold text-white"
                                    />
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $news->author->name }}</h3>
                                    <p class="text-emerald-600 font-semibold mb-3">{{ $news->author->role_label ?? 'Author' }}</p>
                                    <p class="text-gray-700">Dedicated to bringing you the latest news and stories from Glow Media.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Comments Section -->
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-comments text-emerald-600 mr-3"></i>
                                Comments ({{ $news->comments_count }})
                            </h3>

                            <!-- Comment Form -->
                            <form wire:submit.prevent="submitComment" class="mb-8">
                                <div class="bg-gray-50 rounded-xl p-6">
                                    <textarea wire:model="comment" 
                                              rows="4"
                                              placeholder="Join the conversation..."
                                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-emerald-500 transition-colors"></textarea>
                                    @error('comment') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    
                                    <div class="mt-4 flex justify-end">
                                        <button type="submit" 
                                                class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors">
                                            <i class="fas fa-paper-plane mr-2"></i>Post Comment
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- Flash Messages -->
                            @if (session()->has('success'))
                            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg flex items-center flash-auto-dismiss">
                                <i class="fas fa-check-circle mr-3"></i>
                                {{ session('success') }}
                            </div>
                            @endif

                            <!-- Comments List -->
                            <div class="space-y-6">
                                @forelse($news->comments()->approved()->get() as $comment)
                                <div class="bg-gray-50 rounded-xl p-6">
                                    <div class="flex items-start space-x-4">
                                        <div class="relative w-12 h-12 rounded-full overflow-hidden">
                                            <x-initials-image
                                                :src="$comment->user?->avatar ?? null"
                                                :title="$comment->user?->name ?? 'Anonymous'"
                                                imgClass="w-full h-full object-cover"
                                                fallbackClass="bg-emerald-700/90"
                                                textClass="text-xs font-bold text-white"
                                            />
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <div>
                                                    <h4 class="font-bold text-gray-900">
                                                        {{ $comment->user?->name ?? 'Anonymous' }}
                                                        @if($comment->is_pinned)
                                                        <i class="fas fa-thumbtack text-emerald-600 ml-2" title="Pinned"></i>
                                                        @endif
                                                    </h4>
                                                    <p class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                            <p class="text-gray-700 leading-relaxed">{{ $comment->comment }}</p>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-12">
                                    <i class="fas fa-comments text-6xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-600 text-lg">Be the first to comment on this article!</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Related News -->
                @if($relatedNews->count() > 0)
                <div class="mt-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Related Stories</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedNews as $related)
                        <article class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all group">
                            <div class="h-48 overflow-hidden">
                                <div class="relative h-full">
                                    <x-initials-image
                                        :src="$related->featured_image"
                                        :title="$related->title"
                                        imgClass="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-300"
                                        fallbackClass="bg-emerald-700/90"
                                        textClass="text-3xl font-bold text-white"
                                    />
                                </div>
                            </div>
                            <div class="p-6">
                                <h4 class="font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-emerald-600 transition-colors">
                                    <a href="{{ route('news.show', $related->slug) }}">{{ $related->title }}</a>
                                </h4>
                                <p class="text-sm text-gray-600">{{ $related->read_time }}</p>
                            </div>
                        </article>
                        @endforeach
                    </div>
                </div>
                @endif
            </main>

            <!-- Right Sidebar -->
            <aside class="space-y-6">
                <div class="sticky top-24 space-y-6">
                    
                    <!-- Quick Stats -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-chart-line text-emerald-600 mr-2"></i>
                            Engagement
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Views</span>
                                <span class="font-bold text-gray-900">{{ number_format($news->views) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Shares</span>
                                <span class="font-bold text-gray-900">{{ number_format($news->shares) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Comments</span>
                                <span class="font-bold text-gray-900">{{ $news->comments_count }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Reactions</span>
                                <span class="font-bold text-gray-900">{{ array_sum($reactions) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="font-bold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-2">
                            <button wire:click="toggleBookmark" 
                                    class="w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 transition-colors flex items-center">
                                <i class="fas fa-bookmark {{ $isBookmarked ? 'text-yellow-600' : 'text-gray-400' }} mr-3"></i>
                                <span>{{ $isBookmarked ? 'Saved' : 'Save for Later' }}</span>
                            </button>
                            <button class="w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 transition-colors flex items-center">
                                <i class="fas fa-print text-gray-400 mr-3"></i>
                                <span>Print Article</span>
                            </button>
                            <button class="w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 transition-colors flex items-center">
                                <i class="fas fa-flag text-gray-400 mr-3"></i>
                                <span>Report Issue</span>
                            </button>
                        </div>
                    </div>

                    <!-- Newsletter -->
                    <div class="bg-gradient-to-br from-emerald-600 to-teal-600 rounded-xl shadow-lg p-6 text-white">
                        <i class="fas fa-envelope-open-text text-3xl mb-3"></i>
                        <h3 class="font-bold text-lg mb-2">Stay Updated</h3>
                        <p class="text-emerald-100 text-sm mb-4">
                            Get breaking news delivered to your inbox
                        </p>
                        <form class="space-y-3">
                            <input type="email" placeholder="Your email" 
                                   class="w-full px-4 py-2 rounded-lg text-gray-900 focus:outline-none">
                            <button class="w-full px-4 py-2 bg-white text-emerald-600 font-semibold rounded-lg hover:bg-emerald-50 transition-colors">
                                Subscribe
                            </button>
                        </form>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <div x-cloak x-show="imageViewerOpen" x-transition.opacity class="fixed inset-0 z-[90]" role="dialog" aria-modal="true" aria-label="Full news image">
        <div class="absolute inset-0 bg-slate-950/90 backdrop-blur-sm" @click="closeImage()"></div>

        <div class="relative flex min-h-screen items-center justify-center p-4 md:p-8" @click.self="closeImage()">
            <div class="relative w-full max-w-6xl">
                <button type="button"
                        @click="closeImage()"
                        class="absolute right-3 top-3 z-10 inline-flex h-11 w-11 items-center justify-center rounded-full bg-black/80 text-white transition hover:bg-black"
                        aria-label="Close full image">
                    <i class="fas fa-times"></i>
                </button>

                <div class="overflow-hidden rounded-xl bg-slate-950 shadow-2xl">
                    <img :src="activeImage ? activeImage.src : ''"
                         :alt="activeImage ? activeImage.title : ''"
                         class="max-h-[82vh] w-full object-contain">
                </div>

                <div class="mt-3 flex flex-col gap-1 text-white sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm font-semibold text-white/80" x-text="activeImage ? activeImage.label : ''"></p>
                    <p class="text-base font-bold" x-text="activeImage ? activeImage.title : ''"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        function setupQualifiedViewTracking() {
            let qualifiedRecorded = false;

            const recordQualified = () => {
                if (qualifiedRecorded) return;
                qualifiedRecorded = true;
                @this.call('recordQualifiedView');
            };

            const onScroll = () => {
                const doc = document.documentElement;
                const scrollTop = window.scrollY || doc.scrollTop;
                const scrollHeight = doc.scrollHeight - window.innerHeight;
                if (scrollHeight <= 0) return;
                const percent = (scrollTop / scrollHeight) * 100;
                if (percent >= 25) {
                    recordQualified();
                }
            };

            setTimeout(recordQualified, 10000);
            window.addEventListener('scroll', onScroll, { passive: true });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupQualifiedViewTracking, { once: true });
        } else {
            setupQualifiedViewTracking();
        }

        document.addEventListener('livewire:navigated', setupQualifiedViewTracking);
    })();
</script>
