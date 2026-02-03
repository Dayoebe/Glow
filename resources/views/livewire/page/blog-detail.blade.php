<div class="min-h-screen bg-gray-50">
    
    <!-- Breaking News Banner -->
    @if($post->is_featured)
    <div class="bg-gradient-to-r from-purple-600 via-purple-500 to-pink-500 text-white py-3 sticky top-0 z-50 shadow-lg">
        <div class="container mx-auto px-4 flex items-center justify-center space-x-3">
            <x-ad-slot placement="blog-detail" />
            <span class="flex items-center space-x-2 font-bold text-lg">
                <i class="fas fa-star animate-pulse"></i>
                <span>⭐ FEATURED POST</span>
            </span>
        </div>
    </div>
    @endif

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-purple-900 via-purple-800 to-indigo-900 text-white py-16">
        <div class="absolute inset-0 opacity-20">
            <x-initials-image
                :src="$post->featured_image"
                :title="$post->title"
                imgClass="w-full h-full object-cover"
                fallbackClass="bg-purple-800/60"
                textClass="text-6xl font-bold text-white/80"
            />
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto">
                <!-- Breadcrumb -->
                <nav class="flex items-center space-x-2 text-sm text-purple-200 mb-6">
                    <a href="{{ route('blog.index') }}" class="hover:text-white">Blog</a>
                    <span>›</span>
                    <a href="{{ route('blog.index') }}?selectedCategory={{ $post->category->slug }}" class="hover:text-white">
                        {{ $post->category->name }}
                    </a>
                    <span>›</span>
                    <span class="text-white">Article</span>
                </nav>

                <!-- Category Badge -->
                <div class="flex flex-wrap items-center gap-3 mb-6">
                    <span class="px-4 py-2 bg-{{ $post->category->color }}-600 text-white font-bold rounded-full">
                        {{ $post->category->name }}
                    </span>
                    @if($post->series)
                    <span class="px-4 py-2 bg-blue-600 text-white font-bold rounded-full">
                        <i class="fas fa-layer-group mr-2"></i>{{ $post->series }} Series
                    </span>
                    @endif
                    @if($post->video_url)
                    <span class="px-4 py-2 bg-red-600 text-white font-bold rounded-full">
                        <i class="fas fa-play mr-2"></i>Video
                    </span>
                    @endif
                    @if($post->audio_url)
                    <span class="px-4 py-2 bg-green-600 text-white font-bold rounded-full">
                        <i class="fas fa-podcast mr-2"></i>Podcast
                    </span>
                    @endif
                </div>

                <!-- Title -->
                <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">{{ $post->title }}</h1>

                <!-- Meta Info -->
                <div class="flex flex-wrap items-center gap-6 mb-8">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('staff.profile', ['type' => 'user', 'identifier' => $post->author->id]) }}">
                            <div class="relative w-12 h-12 rounded-full overflow-hidden border-2 border-purple-300">
                                <x-initials-image
                                    :src="$post->author->avatar ?? null"
                                    :title="$post->author->name ?? ''"
                                    imgClass="w-full h-full object-cover"
                                    fallbackClass="bg-purple-700/90"
                                    textClass="text-xs font-bold text-white"
                                />
                            </div>
                        </a>
                        <div>
                            <a href="{{ route('staff.profile', ['type' => 'user', 'identifier' => $post->author->id]) }}" class="font-semibold hover:text-purple-200 transition-colors">
                                {{ $post->author->name }}
                            </a>
                            <p class="text-sm text-purple-200">{{ ucfirst($post->author->role) }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-4 text-sm text-purple-200">
                        <span><i class="fas fa-calendar mr-1"></i>{{ $post->formatted_published_date }}</span>
                        <span><i class="fas fa-clock mr-1"></i>{{ $post->read_time }}</span>
                        <span><i class="fas fa-eye mr-1"></i>{{ number_format($post->views) }} views</span>
                        <span><i class="fas fa-share-alt mr-1"></i>{{ number_format($post->shares) }} shares</span>
                    </div>
                </div>

                <!-- Quick Reactions Preview -->
                <div class="flex items-center space-x-4 p-4 bg-white/10 backdrop-blur-sm rounded-xl border border-white/20">
                    @foreach(['love' => '❤️', 'fire' => '🔥', 'clap' => '👏', 'insightful' => '💡'] as $type => $emoji)
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
                    @foreach(['love' => ['❤️', 'Love'], 'fire' => ['🔥', 'Fire'], 'clap' => ['👏', 'Clap'], 'insightful' => ['💡', 'Smart']] as $type => $data)
                    <button wire:click="toggleReaction('{{ $type }}')" 
                            class="group relative flex flex-col items-center justify-center w-14 h-14 rounded-full shadow-lg transition-all {{ isset($userReactions[$type]) ? 'bg-purple-100 ring-2 ring-purple-500' : 'bg-white hover:bg-gray-50' }}">
                        <span class="text-2xl">{{ $data[0] }}</span>
                        @if(($reactions[$type] ?? 0) > 0)
                        <span class="absolute -right-1 -top-1 w-6 h-6 bg-purple-600 text-white text-xs rounded-full flex items-center justify-center font-bold">
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
                                <button wire:click="sharePost('x')" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fab fa-x-twitter text-gray-900"></i><span class="text-sm">X</span>
                                </button>
                                <button wire:click="sharePost('facebook')" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fab fa-facebook text-blue-600"></i><span class="text-sm">Facebook</span>
                                </button>
                                <button wire:click="sharePost('instagram')" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fab fa-instagram text-pink-500"></i><span class="text-sm">Instagram</span>
                                </button>
                                <button wire:click="sharePost('linkedin')" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fab fa-linkedin text-blue-700"></i><span class="text-sm">LinkedIn</span>
                                </button>
                                <button wire:click="sharePost('whatsapp')" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fab fa-whatsapp text-green-500"></i><span class="text-sm">WhatsApp</span>
                                </button>
                                <button wire:click="sharePost('telegram')" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fab fa-telegram text-blue-400"></i><span class="text-sm">Telegram</span>
                                </button>
                                <button wire:click="sharePost('reddit')" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fab fa-reddit-alien text-orange-500"></i><span class="text-sm">Reddit</span>
                                </button>
                                <button wire:click="sharePost('email')" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fas fa-envelope text-gray-600"></i><span class="text-sm">Email</span>
                                </button>
                                <button type="button" data-copy-link="{{ route('blog.show', $post->slug) }}" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
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
                            :src="$post->featured_image"
                            :title="$post->title"
                            imgClass="w-full h-full object-cover"
                            fallbackClass="bg-purple-700/90"
                            textClass="text-5xl font-bold text-white"
                        />
                    </div>

                    <!-- Video Embed -->
                    @if($post->video_url)
                    <div class="aspect-video">
                        <iframe src="{{ $post->video_url }}" 
                                class="w-full h-full" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                        </iframe>
                    </div>
                    @endif

                    <!-- Audio Player -->
                    @if($post->audio_url)
                    <div class="p-6 bg-gradient-to-r from-green-50 to-blue-50">
                        <div class="flex items-center space-x-4 mb-4">
                            <i class="fas fa-podcast text-3xl text-green-600"></i>
                            <div>
                                <h3 class="font-bold text-gray-900">Listen to this article</h3>
                                <p class="text-sm text-gray-600">Audio version available</p>
                            </div>
                        </div>
                        <audio controls class="w-full">
                            <source src="{{ $post->audio_url }}" type="audio/mpeg">
                        </audio>
                    </div>
                    @endif

                    <!-- Content -->
                    <div class="p-8 md:p-12">
                        <!-- Article Body -->
                        <div class="prose prose-lg max-w-none mb-12">
                            {!! $post->content !!}
                        </div>

                        <!-- Gallery -->
                        @if($post->gallery && count($post->gallery) > 0)
                        <div class="mb-12">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-images text-purple-600 mr-3"></i>
                                Photo Gallery
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($post->gallery as $image)
                                <div class="relative h-64 rounded-xl overflow-hidden group cursor-pointer">
                                    <x-initials-image
                                        :src="$image"
                                        :title="$post->title"
                                        imgClass="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-300"
                                        fallbackClass="bg-purple-700/90"
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
                        @if($post->tags && count($post->tags) > 0)
                        <div class="mb-12 pb-8 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-tags text-purple-600 mr-2"></i>
                                Tags
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($post->tags as $tag)
                                <a href="{{ route('blog.index') }}?search={{ urlencode($tag) }}" 
                                   class="px-4 py-2 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-full transition-colors">
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
                                @foreach(['love' => '❤️ Love it', 'fire' => '🔥 Hot take', 'clap' => '👏 Great read', 'insightful' => '💡 Insightful'] as $type => $label)
                                <button wire:click="toggleReaction('{{ $type }}')" 
                                        class="px-6 py-3 rounded-lg font-semibold transition-all {{ isset($userReactions[$type]) ? 'bg-purple-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}">
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
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Share this article</h3>
                            <div class="flex flex-wrap gap-3">
                                <button wire:click="sharePost('x')" 
                                        class="px-6 py-3 bg-gray-900 hover:bg-black text-white rounded-lg transition-colors flex items-center space-x-2">
                                    <i class="fab fa-x-twitter"></i><span>X</span>
                                </button>
                                <button wire:click="sharePost('facebook')" 
                                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center space-x-2">
                                    <i class="fab fa-facebook"></i><span>Facebook</span>
                                </button>
                                <button wire:click="sharePost('instagram')" 
                                        class="px-6 py-3 bg-pink-500 hover:bg-pink-600 text-white rounded-lg transition-colors flex items-center space-x-2">
                                    <i class="fab fa-instagram"></i><span>Instagram</span>
                                </button>
                                <button wire:click="sharePost('linkedin')" 
                                        class="px-6 py-3 bg-blue-700 hover:bg-blue-800 text-white rounded-lg transition-colors flex items-center space-x-2">
                                    <i class="fab fa-linkedin"></i><span>LinkedIn</span>
                                </button>
                                <button wire:click="sharePost('whatsapp')" 
                                        class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors flex items-center space-x-2">
                                    <i class="fab fa-whatsapp"></i><span>WhatsApp</span>
                                </button>
                                <button wire:click="sharePost('telegram')" 
                                        class="px-6 py-3 bg-blue-400 hover:bg-blue-500 text-white rounded-lg transition-colors flex items-center space-x-2">
                                    <i class="fab fa-telegram"></i><span>Telegram</span>
                                </button>
                                <button wire:click="sharePost('reddit')" 
                                        class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors flex items-center space-x-2">
                                    <i class="fab fa-reddit-alien"></i><span>Reddit</span>
                                </button>
                                <button wire:click="sharePost('email')" 
                                        class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-colors flex items-center space-x-2">
                                    <i class="fas fa-envelope"></i><span>Email</span>
                                </button>
                                <button type="button" data-copy-link="{{ route('blog.show', $post->slug) }}" 
                                        class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition-colors flex items-center space-x-2">
                                    <i class="fas fa-link"></i><span data-copy-text>Copy link</span>
                                </button>
                            </div>
                        </div>

                        <!-- Author Bio -->
                        <div class="mb-12">
                            <div class="flex items-start space-x-6 p-6 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl">
                                <div class="relative w-20 h-20 rounded-full overflow-hidden">
                                    <x-initials-image
                                        :src="$post->author->avatar ?? null"
                                        :title="$post->author->name ?? ''"
                                        imgClass="w-full h-full object-cover"
                                        fallbackClass="bg-purple-700/90"
                                        textClass="text-xl font-bold text-white"
                                    />
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $post->author->name }}</h3>
                                    <p class="text-purple-600 font-semibold mb-3">{{ ucfirst($post->author->role) }}</p>
                                    <p class="text-gray-700">Dedicated to bringing you the best content and stories from the world of music and entertainment.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Comments Section -->
                        @if($post->allow_comments)
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                <i class="fas fa-comments text-purple-600 mr-3"></i>
                                Comments ({{ $post->comments_count }})
                            </h3>

                            <!-- Comment Form -->
                            <form wire:submit.prevent="submitComment" class="mb-8">
                                <div class="bg-gray-50 rounded-xl p-6">
                                    <textarea wire:model="comment" 
                                              rows="4"
                                              placeholder="Join the conversation..."
                                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-500 transition-colors"></textarea>
                                    @error('comment') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    
                                    <div class="mt-4 flex justify-end">
                                        <button type="submit" 
                                                class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors">
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
                                @forelse($post->comments()->approved()->get() as $comment)
                                <div class="bg-gray-50 rounded-xl p-6">
                                    <div class="flex items-start space-x-4">
                                        <div class="relative w-12 h-12 rounded-full overflow-hidden">
                                            <x-initials-image
                                                :src="$comment->user?->avatar ?? null"
                                                :title="$comment->user?->name ?? 'Anonymous'"
                                                imgClass="w-full h-full object-cover"
                                                fallbackClass="bg-purple-700/90"
                                                textClass="text-xs font-bold text-white"
                                            />
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <div>
                                                    <h4 class="font-bold text-gray-900">
                                                        {{ $comment->user?->name ?? 'Anonymous' }}
                                                        @if($comment->is_pinned)
                                                        <i class="fas fa-thumbtack text-purple-600 ml-2" title="Pinned"></i>
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
                        @endif
                    </div>
                </article>

                <!-- Related Posts -->
                @if($relatedPosts->count() > 0)
                <div class="mt-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Related Articles</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedPosts as $related)
                        <article class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all group">
                            <div class="h-48 overflow-hidden">
                                <div class="relative h-full">
                                    <x-initials-image
                                        :src="$related->featured_image"
                                        :title="$related->title"
                                        imgClass="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-300"
                                        fallbackClass="bg-purple-700/90"
                                        textClass="text-3xl font-bold text-white"
                                    />
                                </div>
                            </div>
                            <div class="p-6">
                                <h4 class="font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-purple-600 transition-colors">
                                    <a href="{{ route('blog.show', $related->slug) }}">{{ $related->title }}</a>
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
                            <i class="fas fa-chart-line text-purple-600 mr-2"></i>
                            Engagement
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Views</span>
                                <span class="font-bold text-gray-900">{{ number_format($post->views) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Shares</span>
                                <span class="font-bold text-gray-900">{{ number_format($post->shares) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Comments</span>
                                <span class="font-bold text-gray-900">{{ $post->comments_count }}</span>
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

                    <!-- Series Navigation -->
                    @if($post->series && $seriesPosts->count() > 1)
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-layer-group text-purple-600 mr-2"></i>
                            {{ $post->series }} Series
                        </h3>
                        <div class="space-y-2">
                            @foreach($seriesPosts as $seriesPost)
                            <a href="{{ route('blog.show', $seriesPost->slug) }}" 
                               class="block p-2 rounded transition-all {{ $seriesPost->id === $post->id ? 'bg-purple-100 text-purple-700 font-semibold' : 'hover:bg-gray-50' }}">
                                <div class="flex items-center space-x-2">
                                    <span class="w-6 h-6 bg-purple-600 text-white text-xs rounded-full flex items-center justify-center">
                                        {{ $seriesPost->series_order }}
                                    </span>
                                    <span class="text-sm">{{ $seriesPost->title }}</span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Newsletter -->
                    <div class="bg-gradient-to-br from-purple-600 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
                        <i class="fas fa-envelope-open-text text-3xl mb-3"></i>
                        <h3 class="font-bold text-lg mb-2">Stay Updated</h3>
                        <p class="text-purple-100 text-sm mb-4">
                            Get new blog posts delivered to your inbox
                        </p>
                        <form class="space-y-3">
                            <input type="email" placeholder="Your email" 
                                   class="w-full px-4 py-2 rounded-lg text-gray-900 focus:outline-none">
                            <button class="w-full px-4 py-2 bg-white text-purple-600 font-semibold rounded-lg hover:bg-purple-50 transition-colors">
                                Subscribe
                            </button>
                        </form>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
