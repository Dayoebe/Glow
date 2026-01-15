<div class="min-h-screen bg-gray-50">
    
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-gray-900 to-gray-800 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Breadcrumb -->
                <nav class="flex items-center space-x-2 text-sm text-gray-300 mb-6">
                    <a href="{{ route('blog.index') }}" class="hover:text-white">Blog</a>
                    <span>›</span>
                    <a href="{{ route('blog.index') }}?selectedCategory={{ $post->category->slug }}" class="hover:text-white">{{ $post->category->name }}</a>
                    <span>›</span>
                    <span class="text-white">Article</span>
                </nav>

                <!-- Category & Series -->
                <div class="flex flex-wrap items-center gap-3 mb-6">
                    <span class="px-4 py-2 bg-{{ $post->category->color }}-600 text-white font-bold rounded-full">
                        {{ $post->category->name }}
                    </span>
                    @if($post->series)
                    <span class="px-4 py-2 bg-purple-600 text-white font-bold rounded-full flex items-center">
                        <i class="fas fa-layer-group mr-2"></i>
                        Part {{ $post->series_order }} of {{ $seriesPosts->count() }}
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
                
                <!-- Excerpt -->
                <p class="text-xl text-gray-300 mb-8 leading-relaxed">{{ $post->excerpt }}</p>

                <!-- Author & Meta -->
                <div class="flex flex-wrap items-center gap-6">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $post->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->author->name) }}" 
                             class="w-14 h-14 rounded-full border-2 border-purple-400">
                        <div>
                            <p class="font-bold text-lg">{{ $post->author->name }}</p>
                            <p class="text-sm text-gray-300">{{ ucfirst($post->author->role) }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-300">
                        <span><i class="fas fa-calendar mr-2"></i>{{ $post->published_at->format('M d, Y') }}</span>
                        <span><i class="fas fa-clock mr-2"></i>{{ $post->read_time }}</span>
                        <span><i class="fas fa-eye mr-2"></i>{{ number_format($post->views) }} views</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Floating Reaction Bar (Left) -->
            <aside class="hidden lg:block lg:col-span-1">
                <div class="sticky top-24 space-y-4">
                    <!-- Reactions -->
                    @foreach(['love' => '❤️', 'fire' => '🔥', 'clap' => '👏', 'insightful' => '💡'] as $type => $emoji)
                    <button wire:click="toggleReaction('{{ $type }}')" 
                            class="group relative flex flex-col items-center w-12 h-12 rounded-full transition-all {{ isset($userReactions[$type]) ? 'bg-purple-100 text-purple-600' : 'bg-white hover:bg-gray-100' }} shadow-lg">
                        <span class="text-2xl">{{ $emoji }}</span>
                        <span class="absolute -right-1 -top-1 w-6 h-6 bg-purple-600 text-white text-xs rounded-full flex items-center justify-center font-bold">
                            {{ $reactions[$type] ?? 0 }}
                        </span>
                        <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 whitespace-nowrap transition-opacity">
                            {{ ucfirst($type) }}
                        </span>
                    </button>
                    @endforeach

                    <!-- Bookmark -->
                    <button wire:click="toggleBookmark" 
                            class="group relative flex flex-col items-center w-12 h-12 rounded-full transition-all {{ $isBookmarked ? 'bg-yellow-100 text-yellow-600' : 'bg-white hover:bg-gray-100' }} shadow-lg">
                        <i class="fas fa-bookmark text-xl mt-3"></i>
                        <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 whitespace-nowrap transition-opacity">
                            {{ $isBookmarked ? 'Saved' : 'Save' }}
                        </span>
                    </button>

                    <!-- Share -->
                    <button class="group relative flex flex-col items-center w-12 h-12 bg-white hover:bg-gray-100 rounded-full shadow-lg transition-all">
                        <i class="fas fa-share-alt text-xl text-gray-600 mt-3"></i>
                        <span class="absolute left-full ml-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 whitespace-nowrap transition-opacity">
                            Share
                        </span>
                    </button>
                </div>
            </aside>

            <!-- Article Content -->
            <main class="lg:col-span-8">
                <article class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    
                    <!-- Featured Image -->
                    @if($post->featured_image)
                    <div class="relative h-96">
                        <img src="{{ $post->featured_image }}" class="w-full h-full object-cover">
                    </div>
                    @endif

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

                    <!-- Content -->
                    <div class="p-8 md:p-12">
                        <!-- Audio Player -->
                        @if($post->audio_url)
                        <div class="mb-8 p-6 bg-gradient-to-r from-green-50 to-blue-50 rounded-xl">
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

                        <!-- Article Body -->
                        <div class="prose prose-lg max-w-none">
                            {!! $post->content !!}
                        </div>

                        <!-- Gallery -->
                        @if($post->gallery && count($post->gallery) > 0)
                        <div class="mt-12">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">Gallery</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($post->gallery as $image)
                                <div class="relative h-64 rounded-xl overflow-hidden group cursor-pointer">
                                    <img src="{{ $image }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-300">
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Tags -->
                        @if($post->tags && count($post->tags) > 0)
                        <div class="mt-12 pt-8 border-t border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Tags</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($post->tags as $tag)
                                <a href="#" class="px-4 py-2 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-full transition-colors">
                                    #{{ $tag }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Share Section -->
                        <div class="mt-12 pt-8 border-t border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Share this article</h3>
                            <div class="flex flex-wrap gap-3">
                                <button wire:click="sharePost('twitter')" 
                                        class="px-6 py-3 bg-sky-500 hover:bg-sky-600 text-white rounded-lg transition-colors">
                                    <i class="fab fa-twitter mr-2"></i>Twitter
                                </button>
                                <button wire:click="sharePost('facebook')" 
                                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                    <i class="fab fa-facebook mr-2"></i>Facebook
                                </button>
                                <button wire:click="sharePost('linkedin')" 
                                        class="px-6 py-3 bg-blue-700 hover:bg-blue-800 text-white rounded-lg transition-colors">
                                    <i class="fab fa-linkedin mr-2"></i>LinkedIn
                                </button>
                                <button wire:click="sharePost('whatsapp')" 
                                        class="px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors">
                                    <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                                </button>
                            </div>
                        </div>

                        <!-- Author Bio -->
                        <div class="mt-12 pt-8 border-t border-gray-200">
                            <div class="flex items-start space-x-6 p-6 bg-gradient-to-r from-purple-50 to-blue-50 rounded-xl">
                                <img src="{{ $post->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->author->name) }}" 
                                     class="w-24 h-24 rounded-full">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $post->author->name }}</h3>
                                    <p class="text-gray-600 mb-4">{{ ucfirst($post->author->role) }} at Glow FM</p>
                                    <p class="text-gray-700">Passionate about bringing you the best content and stories from the world of music and entertainment.</p>
                                </div>
                            </div>
                        </div>
                        <!-- Comments Section -->
                        @if($post->allow_comments)
                        <div class="mt-12 pt-8 border-t border-gray-200">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">
                                Discussion ({{ $post->comments_count }})
                            </h3>

                            <!-- Comment Form -->
                            @auth
                            <form wire:submit.prevent="submitComment" class="mb-8">
                                <div class="bg-gray-50 rounded-xl p-6">
                                    @if($replyTo)
                                    <div class="mb-4 p-3 bg-blue-100 rounded-lg flex items-center justify-between">
                                        <span class="text-sm text-blue-800">
                                            <i class="fas fa-reply mr-2"></i>Replying to comment
                                        </span>
                                        <button type="button" wire:click="cancelReply" class="text-blue-800 hover:text-blue-900">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    @endif
                                    
                                    <textarea wire:model="comment" 
                                              rows="4"
                                              placeholder="Share your thoughts..."
                                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-purple-500 transition-colors"></textarea>
                                    @error('comment') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                    
                                    <div class="mt-4 flex items-center justify-between">
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-lightbulb mr-1 text-yellow-500"></i>
                                            Be respectful and constructive
                                        </p>
                                        <button type="submit" 
                                                class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors">
                                            <i class="fas fa-paper-plane mr-2"></i>Post Comment
                                        </button>
                                    </div>
                                </div>
                            </form>
                            @else
                            <div class="mb-8 p-6 bg-gradient-to-r from-purple-50 to-blue-50 rounded-xl text-center">
                                <i class="fas fa-comments text-4xl text-purple-600 mb-4"></i>
                                <p class="text-gray-700 mb-4">Join the discussion! Please login to leave a comment.</p>
                                <a href="{{ route('login') }}" class="inline-block px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors">
                                    Login to Comment
                                </a>
                            </div>
                            @endauth

                            <!-- Flash Messages -->
                            @if (session()->has('success'))
                            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg flex items-center">
                                <i class="fas fa-check-circle mr-3"></i>
                                {{ session('success') }}
                            </div>
                            @endif

                            <!-- Comments List -->
                            <div class="space-y-6">
                                @forelse($post->approvedComments as $comment)
                                <div class="bg-gray-50 rounded-xl p-6" id="comment-{{ $comment->id }}">
                                    <div class="flex items-start space-x-4">
                                        <img src="{{ $comment->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name ?? $comment->author_name) }}" 
                                             class="w-12 h-12 rounded-full">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <div>
                                                    <h4 class="font-bold text-gray-900">
                                                        {{ $comment->user->name ?? $comment->author_name }}
                                                        @if($comment->is_pinned)
                                                        <i class="fas fa-thumbtack text-purple-600 ml-2" title="Pinned by author"></i>
                                                        @endif
                                                    </h4>
                                                    <p class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                                                </div>
                                                @auth
                                                <button wire:click="setReplyTo({{ $comment->id }})" 
                                                        class="text-sm text-purple-600 hover:text-purple-700 font-semibold">
                                                    <i class="fas fa-reply mr-1"></i>Reply
                                                </button>
                                                @endauth
                                            </div>
                                            <p class="text-gray-700 leading-relaxed">{{ $comment->comment }}</p>
                                            
                                            <!-- Replies -->
                                            @if($comment->replies->count() > 0)
                                            <div class="mt-4 space-y-4 pl-6 border-l-2 border-purple-200">
                                                @foreach($comment->replies->where('is_approved', true) as $reply)
                                                <div class="flex items-start space-x-4">
                                                    <img src="{{ $reply->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($reply->user->name ?? $reply->author_name) }}" 
                                                         class="w-10 h-10 rounded-full">
                                                    <div class="flex-1">
                                                        <h5 class="font-bold text-gray-900 text-sm">
                                                            {{ $reply->user->name ?? $reply->author_name }}
                                                        </h5>
                                                        <p class="text-xs text-gray-500 mb-2">{{ $reply->created_at->diffForHumans() }}</p>
                                                        <p class="text-gray-700 text-sm">{{ $reply->comment }}</p>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-12">
                                    <i class="fas fa-comments text-6xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-600 text-lg">No comments yet. Be the first to share your thoughts!</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                        @endif
                    </div>
                </article>

                <!-- Series Navigation -->
                @if($post->series && $seriesPosts->count() > 1)
                <div class="mt-8 bg-white rounded-2xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-layer-group text-purple-600 mr-3"></i>
                        {{ $post->series }} Series
                    </h3>
                    <div class="space-y-3">
                        @foreach($seriesPosts as $seriesPost)
                        <a href="{{ route('blog.show', $seriesPost->slug) }}" 
                           class="block p-4 rounded-lg transition-all {{ $seriesPost->id === $post->id ? 'bg-purple-100 border-2 border-purple-500' : 'bg-gray-50 hover:bg-gray-100' }}">
                            <div class="flex items-center space-x-4">
                                <span class="flex-shrink-0 w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                    {{ $seriesPost->series_order }}
                                </span>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900">{{ $seriesPost->title }}</h4>
                                    @if($seriesPost->id === $post->id)
                                    <p class="text-sm text-purple-600">Currently reading</p>
                                    @else
                                    <p class="text-sm text-gray-600">{{ $seriesPost->read_time }}</p>
                                    @endif
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Related Posts -->
                @if($relatedPosts->count() > 0)
                <div class="mt-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Related Articles</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedPosts as $related)
                        <article class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all group">
                            <div class="h-48 overflow-hidden">
                                <img src="{{ $related->featured_image }}" 
                                     class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-300">
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
                
                <!-- Reading Progress -->
                <div class="sticky top-24 space-y-6">
                    
                    <!-- Table of Contents (if exists) -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-list text-purple-600 mr-2"></i>
                            Quick Actions
                        </h3>
                        <div class="space-y-2">
                            <button wire:click="toggleBookmark" 
                                    class="w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 transition-colors flex items-center">
                                <i class="fas fa-bookmark {{ $isBookmarked ? 'text-yellow-600' : 'text-gray-400' }} mr-3"></i>
                                <span>{{ $isBookmarked ? 'Saved to Reading List' : 'Save for Later' }}</span>
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

                    <!-- Author's Posts -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="font-bold text-gray-900 mb-4">More from {{ $post->author->name }}</h3>
                        <div class="space-y-4">
                            @foreach(\App\Models\Blog\Post::where('author_id', $post->author_id)->where('id', '!=', $post->id)->published()->latest()->take(3)->get() as $authorPost)
                            <a href="{{ route('blog.show', $authorPost->slug) }}" class="block group">
                                <h4 class="text-sm font-semibold text-gray-900 group-hover:text-purple-600 line-clamp-2 mb-1">
                                    {{ $authorPost->title }}
                                </h4>
                                <p class="text-xs text-gray-500">{{ $authorPost->published_at->format('M d, Y') }}</p>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Newsletter -->
                    <div class="bg-gradient-to-br from-purple-600 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
                        <i class="fas fa-envelope-open-text text-3xl mb-3"></i>
                        <h3 class="font-bold text-lg mb-2">Get Weekly Insights</h3>
                        <p class="text-purple-100 text-sm mb-4">
                            Join {{ number_format(rand(1000, 5000)) }}+ subscribers
                        </p>
                        <form class="space-y-3">
                            <input type="email" 
                                   placeholder="Your email" 
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