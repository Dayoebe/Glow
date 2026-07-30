<div class="bg-[#faf8f4] text-slate-950">
    <section class="bg-[#171742] text-white">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
            <nav class="mb-7 flex items-center gap-2 text-xs font-bold uppercase tracking-[0.16em] text-slate-400">
                <a href="{{ route('shows.index') }}" class="transition hover:text-orange-300">Shows</a>
                <i class="fas fa-chevron-right text-[9px]"></i>
                <span class="truncate text-white">{{ $show->title }}</span>
            </nav>

            <div class="grid gap-8 lg:grid-cols-[24rem_minmax(0,1fr)] lg:items-center">
                <div class="group relative aspect-square overflow-hidden rounded-2xl bg-[#23235b]">
                    <x-initials-image
                        :src="$show->cover_image"
                        :title="$show->title"
                        imgClass="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-[1.02]"
                        fallbackClass="bg-[#23235b]"
                        textClass="text-6xl font-black text-white"
                    />
                </div>

                <div>
                    <div class="flex flex-wrap items-center gap-3 text-xs font-bold uppercase tracking-[0.18em]">
                        <span class="text-orange-300">{{ $show->category?->name ?? 'Glow FM programme' }}</span>
                        @if($show->is_featured)
                            <span class="rounded-lg bg-white/10 px-2.5 py-1 text-white">Featured</span>
                        @endif
                    </div>
                    <h1 class="mt-4 text-4xl font-black leading-[0.98] tracking-[-0.04em] sm:text-6xl">{{ $show->title }}</h1>
                    <p class="mt-5 max-w-3xl text-base leading-7 text-slate-300 sm:text-lg">
                        {{ $programSummary ?: 'An active Glow 99.1 FM programme. Check the schedule below for airtime and presenter information.' }}
                    </p>

                    <div class="mt-6 flex flex-wrap gap-x-6 gap-y-3 text-sm text-slate-300">
                        <span><i class="fas fa-user mr-2 text-orange-400"></i>{{ $show->primaryHost?->name ?? 'Host TBA' }}</span>
                        <span><i class="fas fa-clock mr-2 text-orange-400"></i>{{ $show->typical_duration }} minutes</span>
                        @if($upcomingSlots->first())
                            <span><i class="fas fa-calendar mr-2 text-orange-400"></i>{{ ucfirst($upcomingSlots->first()->day_of_week) }} · {{ $upcomingSlots->first()->time_range }}</span>
                        @endif
                    </div>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <button type="button" @click="$store.radio.start()"
                            class="inline-flex items-center gap-2 rounded-xl bg-orange-500 px-5 py-3 text-sm font-black text-white transition hover:bg-orange-600">
                            <i class="fas fa-play text-xs"></i>
                            Listen live
                        </button>
                        <a href="{{ route('schedule') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-white/20 px-5 py-3 text-sm font-bold transition hover:bg-white/10">
                            View schedule
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 lg:grid-cols-[minmax(0,1fr)_20rem] lg:px-8 lg:py-16">
        <main class="min-w-0">
            <article class="max-w-3xl">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-orange-600">About the programme</p>
                <div class="prose prose-lg mt-5 max-w-none text-slate-700">
                    {!! $show->full_description
                        ? app(\App\Support\RichTextSanitizer::class)->sanitizeWithLineBreaks($show->full_description)
                        : nl2br(e($show->description)) !!}
                </div>
            </article>

            @if($latestEpisodes->count() > 0)
                <section class="mt-12 border-t border-slate-200 pt-10">
                    <div class="flex items-end justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.22em] text-orange-600">Listen back</p>
                            <h2 class="mt-2 text-3xl font-black tracking-[-0.025em] text-[#171742]">Recent episodes</h2>
                        </div>
                        <a href="{{ route('podcasts.index') }}" class="hidden text-sm font-bold text-orange-600 hover:text-orange-700 sm:block">All podcasts</a>
                    </div>
                    <div class="mt-6 divide-y divide-slate-200 rounded-2xl border border-slate-200 bg-white">
                        @foreach($latestEpisodes as $episode)
                            <article class="grid gap-3 p-5 sm:grid-cols-[3rem_minmax(0,1fr)_auto] sm:items-center sm:gap-4">
                                <span class="flex h-11 w-11 items-center justify-center rounded-full bg-orange-100 text-orange-600">
                                    <i class="fas fa-wave-square text-sm"></i>
                                </span>
                                <div class="min-w-0">
                                    <h3 class="font-black text-[#171742]">{{ $episode->title ?: $show->title }}</h3>
                                    <p class="mt-1 line-clamp-1 text-sm text-slate-500">{{ $episode->description ?: 'Listen back to this Glow FM episode.' }}</p>
                                </div>
                                <p class="text-xs font-semibold text-slate-400">
                                    {{ $episode->aired_at?->format('M j, Y') ?? 'Date unavailable' }}
                                </p>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($show->segments->count() > 0)
                <section class="mt-12 border-t border-slate-200 pt-10">
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-orange-600">Inside the show</p>
                    <h2 class="mt-2 text-3xl font-black tracking-[-0.025em] text-[#171742]">Regular segments</h2>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        @foreach($show->segments as $segment)
                            <article class="rounded-2xl border border-slate-200 bg-white p-5">
                                <h3 class="font-black text-[#171742]">{{ $segment->title }}</h3>
                                <p class="mt-1 text-xs font-bold uppercase tracking-wider text-orange-600">{{ $segment->time_range }} · {{ ucfirst($segment->type) }}</p>
                                @if($segment->description)
                                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ $segment->description }}</p>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            <section class="mt-12 border-t border-slate-200 pt-10">
                <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_minmax(17rem,0.8fr)]">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-orange-600">Your voice</p>
                        <h2 class="mt-2 text-3xl font-black tracking-[-0.025em] text-[#171742]">Rate this programme</h2>

                        @if (session()->has('success'))
                            <p class="mt-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 flash-auto-dismiss">{{ session('success') }}</p>
                        @endif
                        @if (session()->has('error'))
                            <p class="mt-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 flash-auto-dismiss">{{ session('error') }}</p>
                        @endif

                        <form wire:submit.prevent="submitReview" class="mt-6 space-y-5 rounded-2xl border border-slate-200 bg-white p-5">
                            <div>
                                <label class="text-sm font-bold text-[#171742]">Rating</label>
                                <div x-data="{ selected: @js($rating ?? 0), hover: 0 }" class="mt-2 flex items-center gap-2">
                                    <template x-for="star in [1,2,3,4,5]" :key="star">
                                        <button type="button"
                                            @mouseenter="hover = star" @mouseleave="hover = 0"
                                            @click="selected = star; $wire.set('rating', star)"
                                            class="text-2xl focus:outline-none" :aria-label="`Rate ${star} stars`">
                                            <i class="fas fa-star" :class="(hover >= star || selected >= star) ? 'text-orange-500' : 'text-slate-200'"></i>
                                        </button>
                                    </template>
                                </div>
                                @error('rating') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="show-review" class="text-sm font-bold text-[#171742]">Comment <span class="font-normal text-slate-400">(optional)</span></label>
                                <textarea id="show-review" wire:model="review" rows="4"
                                    class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                                    placeholder="What did you enjoy?"></textarea>
                                @error('review') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <button type="submit" class="rounded-xl bg-[#171742] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#23235b]">
                                Submit review
                            </button>
                        </form>
                    </div>

                    <div>
                        <div class="flex items-end justify-between">
                            <h3 class="text-xl font-black text-[#171742]">Listener reviews</h3>
                            <span class="text-sm font-bold text-orange-600">{{ number_format($averageRating, 1) }}/5</span>
                        </div>
                        <div class="mt-4 space-y-3">
                            @forelse($reviews->take(4) as $listenerReview)
                                <article class="rounded-xl border border-slate-200 bg-white p-4">
                                    <div class="flex items-center justify-between gap-3">
                                        <p class="text-sm font-bold text-[#171742]">{{ $listenerReview->user?->name ?? 'Anonymous listener' }}</p>
                                        <span class="text-xs font-black text-orange-600">{{ $listenerReview->rating }}/5</span>
                                    </div>
                                    @if($listenerReview->review)
                                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $listenerReview->review }}</p>
                                    @endif
                                </article>
                            @empty
                                <p class="rounded-xl border border-dashed border-slate-300 p-5 text-sm text-slate-500">No reviews yet. Be the first to share your thoughts.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <aside class="h-fit space-y-5 lg:sticky lg:top-28">
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-orange-600">Airtimes</p>
                <div class="mt-4 divide-y divide-slate-100">
                    @forelse($upcomingSlots as $slot)
                        <div class="flex items-center justify-between gap-4 py-3 first:pt-0 last:pb-0">
                            <div>
                                <p class="text-sm font-black text-[#171742]">{{ ucfirst($slot->day_of_week) }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">{{ $slot->oap?->name ?? 'Host TBA' }}</p>
                            </div>
                            <span class="text-xs font-bold text-orange-600">{{ $slot->time_range }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Schedule TBA.</p>
                    @endforelse
                </div>
            </div>

            @if($show->primaryHost)
                <div class="rounded-2xl bg-[#171742] p-5 text-white">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-orange-300">Presented by</p>
                    <div class="mt-4 flex items-center gap-3">
                        <div class="relative h-14 w-14 shrink-0 overflow-hidden rounded-full bg-white/10">
                            <x-initials-image
                                :src="$show->primaryHost->profile_photo"
                                :title="$show->primaryHost->name"
                                imgClass="absolute inset-0 h-full w-full object-cover"
                                fallbackClass="bg-[#23235b]"
                                textClass="text-sm font-black text-white"
                            />
                        </div>
                        <div>
                            <p class="font-black">{{ $show->primaryHost->name }}</p>
                            @if($show->primaryHost->slug)
                                <a href="{{ route('oaps.show', $show->primaryHost->slug) }}" class="mt-1 inline-block text-xs font-bold text-orange-300 hover:text-orange-200">Meet the presenter</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </aside>
    </div>
</div>
