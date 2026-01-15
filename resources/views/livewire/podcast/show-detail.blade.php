<div class="min-h-screen bg-gray-50">
    
    <!-- Show Hero -->
    <section class="relative bg-gradient-to-br from-purple-900 via-purple-800 to-indigo-900 text-white py-16">
        <div class="absolute inset-0 opacity-20">
            @if($show->cover_image)
            <img src="{{ $show->cover_image }}" class="w-full h-full object-cover blur-xl">
            @endif
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    
                    <!-- Show Cover -->
                    <div class="md:col-span-1">
                        <div class="relative">
                            <img src="{{ $show->cover_image }}" alt="{{ $show->title }}" 
                                 class="w-full rounded-2xl shadow-2xl">
                            @if($show->explicit)
                            <div class="absolute top-4 right-4">
                                <span class="px-3 py-1 bg-red-600 text-white font-bold rounded-lg">EXPLICIT</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Show Info -->
                    <div class="md:col-span-2 flex flex-col justify-center">
                        <div class="mb-4">
                            <span class="px-4 py-2 bg-purple-600 text-white font-bold rounded-full text-sm">
                                {{ ucfirst($show->category) }}
                            </span>
                        </div>
                        
                        <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $show->title }}</h1>
                        
                        <p class="text-xl text-purple-100 mb-6 leading-relaxed">{{ $show->description }}</p>

                        <!-- Host Info -->
                        <div class="flex items-center space-x-4 mb-6">
                            @if($show->host)
                            <img src="{{ $show->host->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($show->host_name) }}" 
                                 class="w-12 h-12 rounded-full border-2 border-purple-300">
                            @endif
                            <div>
                                <p class="font-semibold">Hosted by {{ $show->host_name }}</p>
                                <p class="text-sm text-purple-200">{{ ucfirst($show->frequency) }} episodes</p>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="flex flex-wrap items-center gap-6 mb-6 text-sm">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-list"></i>
                                <span>{{ $show->total_episodes }} Episodes</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-users"></i>
                                <span>{{ number_format($show->subscribers) }} Subscribers</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-headphones"></i>
                                <span>{{ number_format($show->total_plays) }} Plays</span>
                            </div>
                            @if($show->average_rating > 0)
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-star text-yellow-400"></i>
                                <span>{{ number_format($show->average_rating, 1) }} / 5</span>
                            </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-wrap items-center gap-4">
                            <button wire:click="toggleSubscribe" 
                                    class="px-8 py-3 {{ $isSubscribed ? 'bg-white text-purple-600' : 'bg-purple-600 text-white' }} font-bold rounded-lg hover:opacity-90 transition-opacity">
                                <i class="fas {{ $isSubscribed ? 'fa-check' : 'fa-plus' }} mr-2"></i>
                                {{ $isSubscribed ? 'Subscribed' : 'Subscribe' }}
                            </button>
                            
                            @if(!$showReviewForm)
                            <button wire:click="$set('showReviewForm', true)" 
                                    class="px-6 py-3 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-lg transition-colors">
                                <i class="fas fa-star mr-2"></i>Rate Show
                            </button>
                            @endif

                            <!-- Platform Links -->
                            @if($show->spotify_url || $show->apple_url || $show->google_url)
                            <div class="flex items-center space-x-3">
                                @if($show->spotify_url)
                                <a href="{{ $show->spotify_url }}" target="_blank" 
                                   class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-colors">
                                    <i class="fab fa-spotify"></i>
                                </a>
                                @endif
                                @if($show->apple_url)
                                <a href="{{ $show->apple_url }}" target="_blank" 
                                   class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-colors">
                                    <i class="fab fa-apple"></i>
                                </a>
                                @endif
                                @if($show->google_url)
                                <a href="{{ $show->google_url }}" target="_blank" 
                                   class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-colors">
                                    <i class="fab fa-google"></i>
                                </a>
                                @endif
                            </div>
                            @endif
                        </div>

                        <!-- Flash Messages -->
                        @if (session()->has('success'))
                        <div class="mt-4 p-3 bg-green-500 text-white rounded-lg">
                            {{ session('success') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Review Form -->
    @if($showReviewForm && auth()->check())
    <section class="py-8 bg-white border-b">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <form wire:submit.prevent="submitReview" class="bg-purple-50 rounded-xl p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Rate This Show</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Your Rating</label>
                        <div class="flex items-center space-x-2">
                            @for($i = 1; $i <= 5; $i++)
                            <button type="button" wire:click="$set('rating', {{ $i }})" 
                                    class="text-3xl {{ $rating >= $i ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 transition-colors">
                                <i class="fas fa-star"></i>
                            </button>
                            @endfor
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Your Review (Optional)</label>
                        <textarea wire:model="review" rows="4" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                  placeholder="Share your thoughts about this podcast..."></textarea>
                    </div>

                    <div class="flex items-center space-x-3">
                        <button type="submit" 
                                class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors">
                            Submit Review
                        </button>
                        <button type="button" wire:click="$set('showReviewForm', false)" 
                                class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    @endif

    <!-- Episodes Section -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                
                <!-- Filters -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 space-y-4 sm:space-y-0">
                    <h2 class="text-3xl font-bold text-gray-900">Episodes</h2>
                    
                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Season Filter -->
                        @if($seasons->count() > 0)
                        <select wire:model.live="selectedSeason" 
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="all">All Seasons</option>
                            @foreach($seasons as $season)
                            <option value="{{ $season }}">Season {{ $season }}</option>
                            @endforeach
                        </select>
                        @endif

                        <!-- Sort Filter -->
                        <select wire:model.live="sortBy" 
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="latest">Latest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="popular">Most Popular</option>
                        </select>
                    </div>
                </div>

                <!-- Episodes List -->
                @if($episodes->count() > 0)
                <div class="space-y-4">
                    @foreach($episodes as $episode)
                    <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 group">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-0">
                            
                            <!-- Episode Cover -->
                            <div class="md:col-span-3 relative h-48 md:h-auto">
                                <img src="{{ $episode->cover_image ?? $show->cover_image }}" 
                                     alt="{{ $episode->title }}"
                                     class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <a href="{{ route('podcasts.episode', [$show->slug, $episode->slug]) }}" 
                                       class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center text-white text-2xl">
                                        <i class="fas fa-play ml-1"></i>
                                    </a>
                                </div>
                                <div class="absolute top-3 right-3">
                                    <span class="px-2 py-1 bg-black/70 text-white text-xs rounded-full">
                                        {{ $episode->formatted_duration }}
                                    </span>
                                </div>
                            </div>

                            <!-- Episode Info -->
                            <div class="md:col-span-9 p-6">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            @if($episode->season_number)
                                            <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded">
                                                S{{ $episode->season_number }} E{{ $episode->episode_number }}
                                            </span>
                                            @endif
                                            <span class="text-sm text-gray-500">
                                                {{ $episode->published_at->format('M d, Y') }}
                                            </span>
                                            @if($episode->explicit)
                                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded">E</span>
                                            @endif
                                        </div>
                                        
                                        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">
                                            <a href="{{ route('podcasts.episode', [$show->slug, $episode->slug]) }}">
                                                {{ $episode->title }}
                                            </a>
                                        </h3>
                                        
                                        <p class="text-gray-600 mb-4 line-clamp-2">{{ $episode->description }}</p>

                                        @if($episode->guests && count($episode->guests) > 0)
                                        <div class="mb-3">
                                            <span class="text-sm text-gray-500">
                                                <i class="fas fa-user-friends mr-1"></i>
                                                Guests: {{ implode(', ', $episode->guests) }}
                                            </span>
                                        </div>
                                        @endif

                                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                            <span><i class="fas fa-headphones mr-1"></i>{{ number_format($episode->plays) }} plays</span>
                                            <span><i class="fas fa-download mr-1"></i>{{ number_format($episode->downloads) }} downloads</span>
                                            <span><i class="fas fa-comments mr-1"></i>{{ $episode->comments->count() }} comments</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12 bg-white rounded-xl">
                    <i class="fas fa-podcast text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-600">No episodes available yet</p>
                </div>
                @endif

            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    @if($show->reviews->where('is_approved', true)->count() > 0)
    <section class="py-12 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Listener Reviews</h2>
                
                <div class="space-y-6">
                    @foreach($show->reviews()->where('is_approved', true)->latest()->take(5)->get() as $review)
                    <div class="bg-white rounded-xl p-6 shadow-md">
                        <div class="flex items-start space-x-4">
                            <img src="{{ $review->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) }}" 
                                 class="w-12 h-12 rounded-full">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <h4 class="font-bold text-gray-900">{{ $review->user->name }}</h4>
                                        <div class="flex items-center space-x-1 text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-gray-300' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                @if($review->review)
                                <p class="text-gray-700">{{ $review->review }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

</div>