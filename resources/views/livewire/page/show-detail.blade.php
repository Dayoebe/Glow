<div class="min-h-screen bg-gray-50">
    <section class="relative bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-900 text-white py-16">
        <div class="absolute inset-0 opacity-20 group">
            <x-initials-image
                :src="$show->cover_image"
                :title="$show->title"
                imgClass="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                fallbackClass="bg-emerald-800/60"
                textClass="text-6xl font-bold text-white/80"
            />
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>

        <div class="container mx-auto px-4 relative z-10">
            <x-ad-slot placement="show-detail" />
            <div class="max-w-4xl mx-auto">
                <nav class="flex items-center space-x-2 text-sm text-emerald-200 mb-6">
                    <a href="{{ route('shows.index') }}" class="hover:text-white">Shows</a>
                    <span>›</span>
                    <span class="text-white">{{ $show->title }}</span>
                </nav>

                <div class="flex flex-wrap items-center gap-3 mb-6">
                    <span class="px-4 py-2 bg-{{ $show->category?->color ?? 'emerald' }}-600 text-white font-bold rounded-full">
                        {{ $show->category?->name ?? 'Show' }}
                    </span>
                    @if($show->is_featured)
                        <span class="px-4 py-2 bg-purple-600 text-white font-bold rounded-full">
                            <i class="fas fa-star mr-2"></i>Featured
                        </span>
                    @endif
                    <span class="px-4 py-2 bg-white/10 text-white font-bold rounded-full">
                        <i class="fas fa-clock mr-2"></i>{{ $show->typical_duration }} mins
                    </span>
                </div>

                <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">{{ $show->title }}</h1>

                <div class="flex flex-wrap items-center gap-6 mb-8">
                    <div class="flex items-center space-x-3">
                        @if($show->primaryHost?->slug)
                            <a href="{{ route('oaps.show', $show->primaryHost->slug) }}"
                                class="relative w-12 h-12 rounded-full overflow-hidden border-2 border-emerald-300 group">
                                <x-initials-image
                                    :src="$show->primaryHost?->profile_photo"
                                    :title="$show->primaryHost?->name ?? $show->title"
                                    imgClass="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                    fallbackClass="bg-emerald-700/90"
                                    textClass="text-sm font-bold text-white"
                                />
                            </a>
                        @else
                            <div class="relative w-12 h-12 rounded-full overflow-hidden border-2 border-emerald-300">
                                <x-initials-image
                                    :src="$show->primaryHost?->profile_photo"
                                    :title="$show->primaryHost?->name ?? $show->title"
                                    imgClass="w-full h-full object-cover"
                                    fallbackClass="bg-emerald-700/90"
                                    textClass="text-sm font-bold text-white"
                                />
                            </div>
                        @endif
                        <div>
                            <p class="font-semibold">{{ $show->primaryHost?->name ?? 'Host TBA' }}</p>
                            <p class="text-sm text-emerald-200">{{ ucfirst($show->format) }} • {{ $show->content_rating }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-4 text-sm text-emerald-200">
                        <span><i class="fas fa-users mr-1"></i>{{ number_format($show->total_listeners) }} listeners</span>
                        <span><i class="fas fa-star mr-1"></i>{{ number_format($averageRating, 1) }} rating</span>
                        <span><i class="fas fa-comment mr-1"></i>{{ number_format($ratingCount) }} ratings</span>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('schedule') }}"
                        class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-full transition-colors">
                        <i class="fas fa-calendar mr-2"></i>View Schedule
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <main class="lg:col-span-8">
                <article class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-8 md:p-12">
                        <div class="prose prose-lg max-w-none mb-10">
                            {!! $show->full_description ?: nl2br(e($show->description)) !!}
                        </div>

                        @if($show->segments->count() > 0)
                            <div class="mb-12">
                                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                                    <i class="fas fa-layer-group text-emerald-600 mr-3"></i>
                                    Segments
                                </h3>
                                <div class="space-y-4">
                                    @foreach($show->segments as $segment)
                                        <div class="p-4 bg-gray-50 rounded-xl">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="font-semibold text-gray-900">{{ $segment->title }}</p>
                                                    <p class="text-sm text-gray-500">{{ $segment->time_range }} • {{ ucfirst($segment->type) }}</p>
                                                </div>
                                                <span class="text-xs text-gray-500">{{ $segment->duration }} mins</span>
                                            </div>
                                            @if($segment->description)
                                                <p class="text-sm text-gray-600 mt-2">{{ $segment->description }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="border-t border-gray-200 pt-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">Rate & Comment</h3>
                            @if (session()->has('success'))
                                <p class="mb-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-2">
                                    {{ session('success') }}
                                </p>
                            @endif
                            <form wire:submit.prevent="submitReview" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                                    <div
                                        x-data="{
                                            selected: @js($rating ?? 0),
                                            hover: 0,
                                            setRating(value) {
                                                this.selected = value;
                                                $wire.set('rating', value);
                                            }
                                        }"
                                        class="flex items-center space-x-2"
                                    >
                                        <template x-for="star in [1,2,3,4,5]" :key="star">
                                            <button type="button"
                                                class="text-2xl focus:outline-none"
                                                @mouseenter="hover = star"
                                                @mouseleave="hover = 0"
                                                @click="setRating(star)"
                                                :aria-label="`Rate ${star} star${star > 1 ? 's' : ''}`">
                                                <i class="fas fa-star"
                                                   :class="(hover >= star || selected >= star) ? 'text-emerald-500' : 'text-gray-300'"></i>
                                            </button>
                                        </template>
                                        <span class="text-sm text-gray-500" x-text="selected ? `${selected}/5` : 'Select rating'"></span>
                                    </div>
                                    @error('rating') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Comment (optional)</label>
                                    <textarea wire:model="review" rows="4"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                        placeholder="Share what you enjoyed about this show..."></textarea>
                                    @error('review') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <button type="submit"
                                    class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors">
                                    <i class="fas fa-star mr-2"></i>Submit Review
                                </button>
                            </form>
                        </div>

                        <div class="mt-10">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">Listener Reviews</h3>
                            <div class="space-y-4">
                                @forelse($reviews as $listenerReview)
                                    <div class="p-4 bg-gray-50 rounded-xl">
                                        <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $listenerReview->user?->name ?? 'Anonymous' }}</p>
                                            <p class="text-xs text-gray-500">{{ $listenerReview->created_at->format('M d, Y') }}</p>
                                        </div>
                                            <span class="text-sm font-semibold text-emerald-700">
                                                {{ $listenerReview->rating }}/5
                                            </span>
                                        </div>
                                        @if($listenerReview->review)
                                            <p class="text-sm text-gray-700 mt-2">{{ $listenerReview->review }}</p>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">No reviews yet. Be the first to review this show.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </article>
            </main>

            <aside class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-calendar-alt text-emerald-600 mr-2"></i>
                        Upcoming Slots
                    </h3>
                    <div class="space-y-3">
                        @forelse($upcomingSlots as $slot)
                            <div class="flex items-start space-x-3">
                                <div class="w-12 h-12 bg-emerald-100 text-emerald-700 rounded-lg flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr($slot->day_of_week, 0, 3)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $slot->time_range }}</p>
                                    <p class="text-xs text-gray-500">{{ $slot->oap?->name ?? 'Host TBA' }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No schedule slots available.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Show Info</h3>
                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex items-center justify-between">
                            <span>Format</span>
                            <span class="font-semibold text-gray-900">{{ ucfirst($show->format) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Duration</span>
                            <span class="font-semibold text-gray-900">{{ $show->typical_duration }} mins</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Content Rating</span>
                            <span class="font-semibold text-gray-900">{{ $show->content_rating }}</span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
