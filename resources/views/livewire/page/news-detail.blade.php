<div class="min-h-screen bg-gray-50">
    
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
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Floating Action Bar (Left) -->
            <aside class="hidden lg:block lg:col-span-1">
                <div class="sticky top-24 space-y-3">
                    <!-- Reactions -->
                    @foreach(['love' => ['❤️', 'Love'], 'fire' => ['🔥', 'Fire'], 'wow' => ['😮', 'Wow'], 'insightful' => ['💡', 'Smart']] as $type => $data)
                    <button wire:click="toggleReaction('{{ $type }}')" 
                            class="group relative flex flex-col items-center justify-center w-14 h-14 rounded-full shadow-lg transition-all {{ isset($userReactions[$type]) ? 'bg-emerald-100 ring-2 ring-emerald-500' : 'bg-white hover:bg-gray-50' }}">
                        <span class="text-2xl">{{ $data[0] }}</span>
                        @if(($reactions[$type] ?? 0) > 0)
                        <span class="absolute -right-1 -top-1 w-6 h-6 bg-emerald-600 text-white text-xs rounded-full flex items-center justify-center font-bold">
                            {{ $reactions[$type] }}
                        </span>
                        @endif
                        <span class="absolute left-full ml-3 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 whitespace-nowrap transition-opacity">
                            {{ $data[1] }}
                        </span>
                    </button>
                    @endforeach

                    <!-- Bookmark -->
                    <button wire:click="toggleBookmark" 
                            class="group relative flex flex-col items-center justify-center w-14 h-14 rounded-full shadow-lg transition-all {{ $isBookmarked ? 'bg-yellow-100 ring-2 ring-yellow-500' : 'bg-white hover:bg-gray-50' }}">
                        <i class="fas fa-bookmark text-xl {{ $isBookmarked ? 'text-yellow-600' : 'text-gray-600' }}"></i>
                        <span class="absolute left-full ml-3 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 whitespace-nowrap transition-opacity">
                            {{ $isBookmarked ? 'Saved' : 'Save' }}
                        </span>
                    </button>

                    <!-- Share -->
                    <div class="relative group">
                        <button class="flex flex-col items-center justify-center w-14 h-14 bg-white hover:bg-gray-50 rounded-full shadow-lg transition-all">
                            <i class="fas fa-share-alt text-xl text-gray-600"></i>
                        </button>
                        <div class="absolute left-full ml-3 opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="bg-white rounded-lg shadow-xl p-3 space-y-2 whitespace-nowrap">
                                <button wire:click="shareNews('x')" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fab fa-x-twitter text-gray-900"></i><span class="text-sm">X</span>
                                </button>
                                <button wire:click="shareNews('facebook')" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fab fa-facebook text-blue-600"></i><span class="text-sm">Facebook</span>
                                </button>
                                <button wire:click="shareNews('instagram')" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fab fa-instagram text-pink-500"></i><span class="text-sm">Instagram</span>
                                </button>
                                <button wire:click="shareNews('linkedin')" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fab fa-linkedin text-blue-700"></i><span class="text-sm">LinkedIn</span>
                                </button>
                                <button wire:click="shareNews('whatsapp')" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fab fa-whatsapp text-green-500"></i><span class="text-sm">WhatsApp</span>
                                </button>
                                <button wire:click="shareNews('telegram')" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fab fa-telegram text-blue-400"></i><span class="text-sm">Telegram</span>
                                </button>
                                <button wire:click="shareNews('reddit')" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fab fa-reddit-alien text-orange-500"></i><span class="text-sm">Reddit</span>
                                </button>
                                <button wire:click="shareNews('email')" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fas fa-envelope text-gray-600"></i><span class="text-sm">Email</span>
                                </button>
                                <button type="button" data-copy-link="{{ url()->current() }}" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fas fa-link text-gray-600"></i><span class="text-sm" data-copy-text>Copy link</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Article Content -->
            <main class="lg:col-span-8">
                <article class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    
                    <!-- Featured Image -->
                    <div class="relative h-96">
                        <x-initials-image
                            :src="$news->featured_image"
                            :title="$news->title"
                            imgClass="w-full h-full object-cover"
                            fallbackClass="bg-emerald-700/90"
                            textClass="text-5xl font-bold text-white"
                        />
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
                                <div class="relative h-64 rounded-xl overflow-hidden group cursor-pointer">
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
                                </div>
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
                        <div class="mb-12 pb-8 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Share this story</h3>
                            <div class="flex flex-wrap gap-3">
                                <button wire:click="shareNews('x')" 
                                    class="px-6 py-3 bg-gray-900 hover:bg-black text-white rounded-lg transition-colors flex items-center space-x-2">
                                    <i class="fab fa-x-twitter"></i><span>X</span>
                                </button>
                                <button wire:click="shareNews('facebook')" 
                                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center space-x-2">
                                    <i class="fab fa-facebook"></i><span>Facebook</span>
                                </button>
                                <button wire:click="shareNews('instagram')" 
                                        class="px-6 py-3 bg-pink-500 hover:bg-pink-600 text-white rounded-lg transition-colors flex items-center space-x-2">
                                    <i class="fab fa-instagram"></i><span>Instagram</span>
                                </button>
                                <button wire:click="shareNews('linkedin')" 
                                        class="px-6 py-3 bg-blue-700 hover:bg-blue-800 text-white rounded-lg transition-colors flex items-center space-x-2">
                                    <i class="fab fa-linkedin"></i><span>LinkedIn</span>
                                </button>
                                <button wire:click="shareNews('whatsapp')" 
                                    class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors flex items-center space-x-2">
                                    <i class="fab fa-whatsapp"></i><span>WhatsApp</span>
                                </button>
                                <button wire:click="shareNews('telegram')" 
                                    class="px-6 py-3 bg-blue-400 hover:bg-blue-500 text-white rounded-lg transition-colors flex items-center space-x-2">
                                    <i class="fab fa-telegram"></i><span>Telegram</span>
                                </button>
                                <button wire:click="shareNews('reddit')" 
                                    class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors flex items-center space-x-2">
                                    <i class="fab fa-reddit-alien"></i><span>Reddit</span>
                                </button>
                                <button wire:click="shareNews('email')" 
                                    class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-colors flex items-center space-x-2">
                                    <i class="fas fa-envelope"></i><span>Email</span>
                                </button>
                                <button type="button" data-copy-link="{{ url()->current() }}" 
                                    class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition-colors flex items-center space-x-2">
                                    <i class="fas fa-link"></i><span data-copy-text>Copy link</span>
                                </button>
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
                                    <p class="text-emerald-600 font-semibold mb-3">{{ ucfirst($news->author->role) }}</p>
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
            <aside class="lg:col-span-3 space-y-6">
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
</div>
