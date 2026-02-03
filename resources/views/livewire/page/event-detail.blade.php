<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-amber-900 via-amber-800 to-orange-900 text-white py-16">
        <div class="absolute inset-0 opacity-20">
            <x-initials-image
                :src="$event->featured_image"
                :title="$event->title"
                imgClass="w-full h-full object-cover"
                fallbackClass="bg-amber-800/60"
                textClass="text-6xl font-bold text-white/80"
            />
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>

        <div class="container mx-auto px-4 relative z-10">
            <x-ad-slot placement="event-detail" />
            <div class="max-w-4xl mx-auto">
                <nav class="flex items-center space-x-2 text-sm text-amber-200 mb-6">
                    <a href="{{ route('events.index') }}" class="hover:text-white">Events</a>
                    <span>›</span>
                    <a href="{{ route('events.index') }}?selectedCategory={{ $event->category->slug }}" class="hover:text-white">
                        {{ $event->category->name }}
                    </a>
                    <span>›</span>
                    <span class="text-white">Event</span>
                </nav>

                <div class="flex flex-wrap items-center gap-3 mb-6">
                    <span class="px-4 py-2 bg-{{ $event->category->color }}-600 text-white font-bold rounded-full">
                        {{ $event->category->name }}
                    </span>
                    @if($event->is_featured)
                        <span class="px-4 py-2 bg-purple-600 text-white font-bold rounded-full">
                            <i class="fas fa-star mr-2"></i>Featured
                        </span>
                    @endif
                </div>

                <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">{{ $event->title }}</h1>

                <div class="flex flex-wrap items-center gap-6 mb-8">
                    <div class="flex items-center space-x-3">
                        <div class="relative w-12 h-12 rounded-full overflow-hidden border-2 border-amber-300">
                            <x-initials-image
                                :src="$event->author->avatar ?? null"
                                :title="$event->author->name ?? ''"
                                imgClass="w-full h-full object-cover"
                                fallbackClass="bg-amber-700/90"
                                textClass="text-sm font-bold text-white"
                            />
                        </div>
                        <div>
                            <p class="font-semibold">{{ $event->author->name }}</p>
                            <p class="text-sm text-amber-200">Organizer</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-4 text-sm text-amber-200">
                        <span><i class="fas fa-calendar mr-1"></i>{{ $event->formatted_date }}</span>
                        <span><i class="fas fa-clock mr-1"></i>{{ $event->formatted_time }}</span>
                        <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $event->venue_name ?? 'Venue TBA' }}</span>
                        <span><i class="fas fa-eye mr-1"></i>{{ number_format($event->views) }} views</span>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    @if($event->registration_url)
                        <a href="{{ $event->registration_url }}" target="_blank"
                            class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-full transition-colors">
                            <i class="fas fa-clipboard-check mr-2"></i>Register
                        </a>
                    @endif
                    @if($event->ticket_url)
                        <a href="{{ $event->ticket_url }}" target="_blank"
                            class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-full transition-colors">
                            <i class="fas fa-ticket-alt mr-2"></i>Get Tickets
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Actions -->
            <aside class="hidden lg:block lg:col-span-1">
                <div class="sticky top-24 space-y-3">
                    @foreach(['love' => ['❤️', 'Love'], 'fire' => ['🔥', 'Fire'], 'wow' => ['😮', 'Wow'], 'insightful' => ['💡', 'Smart']] as $type => $data)
                        <button wire:click="toggleReaction('{{ $type }}')"
                            class="group relative flex flex-col items-center justify-center w-14 h-14 rounded-full shadow-lg transition-all {{ isset($userReactions[$type]) ? 'bg-amber-100 ring-2 ring-amber-500' : 'bg-white hover:bg-gray-50' }}">
                            <span class="text-2xl">{{ $data[0] }}</span>
                            @if(($reactions[$type] ?? 0) > 0)
                                <span class="absolute -right-1 -top-1 w-6 h-6 bg-amber-600 text-white text-xs rounded-full flex items-center justify-center font-bold">
                                    {{ $reactions[$type] }}
                                </span>
                            @endif
                            <span class="absolute left-full ml-3 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 whitespace-nowrap transition-opacity">
                                {{ $data[1] }}
                            </span>
                        </button>
                    @endforeach

                    <button wire:click="toggleBookmark"
                        class="group relative flex flex-col items-center justify-center w-14 h-14 rounded-full shadow-lg transition-all {{ $isBookmarked ? 'bg-yellow-100 ring-2 ring-yellow-500' : 'bg-white hover:bg-gray-50' }}">
                        <i class="fas fa-bookmark text-xl {{ $isBookmarked ? 'text-yellow-600' : 'text-gray-600' }}"></i>
                        <span class="absolute left-full ml-3 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 whitespace-nowrap transition-opacity">
                            {{ $isBookmarked ? 'Saved' : 'Save' }}
                        </span>
                    </button>

                    <div class="relative group">
                        <button class="flex flex-col items-center justify-center w-14 h-14 bg-white hover:bg-gray-50 rounded-full shadow-lg transition-all">
                            <i class="fas fa-share-alt text-xl text-gray-600"></i>
                        </button>
                        <div class="absolute left-full ml-3 opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="bg-white rounded-lg shadow-xl p-3 space-y-2 whitespace-nowrap">
                                <button wire:click="shareEvent('x')" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fab fa-x-twitter text-gray-900"></i><span class="text-sm">X</span>
                                </button>
                                <button wire:click="shareEvent('facebook')" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fab fa-facebook text-blue-600"></i><span class="text-sm">Facebook</span>
                                </button>
                                <button wire:click="shareEvent('linkedin')" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fab fa-linkedin text-blue-700"></i><span class="text-sm">LinkedIn</span>
                                </button>
                                <button wire:click="shareEvent('whatsapp')" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fab fa-whatsapp text-green-500"></i><span class="text-sm">WhatsApp</span>
                                </button>
                                <button wire:click="shareEvent('telegram')" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fab fa-telegram text-blue-400"></i><span class="text-sm">Telegram</span>
                                </button>
                                <button wire:click="shareEvent('reddit')" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fab fa-reddit-alien text-orange-500"></i><span class="text-sm">Reddit</span>
                                </button>
                                <button wire:click="shareEvent('email')" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fas fa-envelope text-gray-600"></i><span class="text-sm">Email</span>
                                </button>
                                <button type="button" data-copy-link="{{ route('events.show', $event->slug) }}" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded w-full text-left">
                                    <i class="fas fa-link text-gray-600"></i><span class="text-sm" data-copy-text>Copy link</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Event Content -->
            <main class="lg:col-span-8">
                <article class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="relative h-96">
                        <x-initials-image
                            :src="$event->featured_image"
                            :title="$event->title"
                            imgClass="w-full h-full object-cover"
                            fallbackClass="bg-amber-700/90"
                            textClass="text-5xl font-bold text-white"
                        />
                    </div>

                    <div class="p-8 md:p-12">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                            <div class="p-4 bg-amber-50 rounded-xl">
                                <p class="text-sm text-amber-700 font-semibold mb-1">Date</p>
                                <p class="text-gray-900 font-bold">{{ $event->formatted_date }}</p>
                            </div>
                            <div class="p-4 bg-amber-50 rounded-xl">
                                <p class="text-sm text-amber-700 font-semibold mb-1">Time</p>
                                <p class="text-gray-900 font-bold">{{ $event->formatted_time }}</p>
                            </div>
                            <div class="p-4 bg-amber-50 rounded-xl">
                                <p class="text-sm text-amber-700 font-semibold mb-1">Location</p>
                                <p class="text-gray-900 font-bold">{{ $event->venue_name ?? 'Venue TBA' }}</p>
                                @if($event->venue_address)
                                    <p class="text-sm text-gray-600">{{ $event->venue_address }}</p>
                                @endif
                            </div>
                            <div class="p-4 bg-amber-50 rounded-xl">
                                <p class="text-sm text-amber-700 font-semibold mb-1">Price</p>
                                <p class="text-gray-900 font-bold">{{ $event->price ?? 'Free' }}</p>
                                @if($event->capacity)
                                    <p class="text-sm text-gray-600">{{ $event->capacity }} capacity</p>
                                @endif
                            </div>
                        </div>

                        <div class="prose prose-lg max-w-none mb-12">
                            {!! $event->content !!}
                        </div>

                        @if($event->tags && count($event->tags) > 0)
                            <div class="mb-12 pb-8 border-b border-gray-200">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-tags text-amber-600 mr-2"></i>
                                    Tags
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($event->tags as $tag)
                                        <a href="{{ route('events.index') }}?searchQuery={{ urlencode($tag) }}"
                                           class="px-4 py-2 bg-amber-100 hover:bg-amber-200 text-amber-700 rounded-full transition-colors">
                                            #{{ $tag }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mb-12 pb-8 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">How do you feel about this event?</h3>
                            <div class="flex flex-wrap gap-3">
                                @foreach(['love' => '❤️ Love it', 'fire' => '🔥 Excited', 'wow' => '😮 Can’t wait', 'insightful' => '💡 Interested'] as $type => $label)
                                    <button wire:click="toggleReaction('{{ $type }}')"
                                            class="px-6 py-3 rounded-lg font-semibold transition-all {{ isset($userReactions[$type]) ? 'bg-amber-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}">
                                        {{ $label }}
                                        @if(($reactions[$type] ?? 0) > 0)
                                            <span class="ml-2 px-2 py-1 bg-white/20 rounded-full text-sm">{{ $reactions[$type] }}</span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        @if($event->allow_comments)
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                    <i class="fas fa-comments text-amber-600 mr-3"></i>
                                    Comments ({{ $event->comments()->approved()->count() }})
                                </h3>

                                <form wire:submit.prevent="submitComment" class="mb-8">
                                    <div class="bg-gray-50 rounded-xl p-6">
                                        <textarea wire:model="comment" rows="4"
                                            placeholder="Share your thoughts..."
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-amber-500 transition-colors"></textarea>
                                        @error('comment') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                                        <div class="mt-4 flex justify-end">
                                            <button type="submit"
                                                class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition-colors">
                                                <i class="fas fa-paper-plane mr-2"></i>Post Comment
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                @if (session()->has('success'))
                                    <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg flex items-center flash-auto-dismiss">
                                        <i class="fas fa-check-circle mr-3"></i>
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <div class="space-y-6">
                                    @forelse($event->comments()->approved()->get() as $comment)
                                        <div class="bg-gray-50 rounded-xl p-6">
                                            <div class="flex items-start space-x-4">
                                                <div class="relative w-12 h-12 rounded-full overflow-hidden">
                                                    <x-initials-image
                                                        :src="$comment->user?->avatar ?? null"
                                                        :title="$comment->user?->name ?? 'Anonymous'"
                                                        imgClass="w-full h-full object-cover"
                                                        fallbackClass="bg-amber-700/90"
                                                        textClass="text-xs font-bold text-white"
                                                    />
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <div>
                                                            <h4 class="font-bold text-gray-900">{{ $comment->user?->name ?? 'Anonymous' }}</h4>
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
                                            <p class="text-gray-600 text-lg">Be the first to comment on this event!</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @endif
                    </div>
                </article>

                @if($relatedEvents->count() > 0)
                    <div class="mt-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Related Events</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($relatedEvents as $related)
                                <article class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all group">
                                    <div class="h-48 overflow-hidden">
                                        <div class="relative h-full">
                                            <x-initials-image
                                                :src="$related->featured_image"
                                                :title="$related->title"
                                                imgClass="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-300"
                                                fallbackClass="bg-amber-700/90"
                                                textClass="text-3xl font-bold text-white"
                                            />
                                        </div>
                                    </div>
                                    <div class="p-6">
                                        <h4 class="font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-amber-600 transition-colors">
                                            <a href="{{ route('events.show', $related->slug) }}">{{ $related->title }}</a>
                                        </h4>
                                        <p class="text-sm text-gray-600">{{ $related->formatted_date }}</p>
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
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-chart-line text-amber-600 mr-2"></i>
                            Engagement
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Views</span>
                                <span class="font-bold text-gray-900">{{ number_format($event->views) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Shares</span>
                                <span class="font-bold text-gray-900">{{ number_format($event->shares) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Reactions</span>
                                <span class="font-bold text-gray-900">{{ array_sum($reactions) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="font-bold text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-2">
                            <button wire:click="toggleBookmark"
                                    class="w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 transition-colors flex items-center">
                                <i class="fas fa-bookmark {{ $isBookmarked ? 'text-yellow-600' : 'text-gray-400' }} mr-3"></i>
                                <span>{{ $isBookmarked ? 'Saved' : 'Save for Later' }}</span>
                            </button>
                            @if($event->registration_url)
                                <a href="{{ $event->registration_url }}" target="_blank"
                                    class="w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 transition-colors flex items-center">
                                    <i class="fas fa-clipboard-check text-gray-400 mr-3"></i>
                                    <span>Register Now</span>
                                </a>
                            @endif
                            @if($event->ticket_url)
                                <a href="{{ $event->ticket_url }}" target="_blank"
                                    class="w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 transition-colors flex items-center">
                                    <i class="fas fa-ticket-alt text-gray-400 mr-3"></i>
                                    <span>Get Tickets</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
