<div class="min-h-screen bg-[#f6f2e9] text-[#0b1830]">
    <section class="bg-[#07172f] text-white">
        <div class="mx-auto max-w-7xl px-5 py-6 sm:px-8 lg:px-10">
            <nav class="flex flex-wrap items-center gap-2 text-xs text-slate-400" aria-label="Breadcrumb">
                <a href="{{ route('podcasts.index') }}" class="transition hover:text-white">Podcasts</a>
                <i class="fas fa-chevron-right text-[0.55rem]" aria-hidden="true"></i>
                <span class="text-slate-200">{{ $show->title }}</span>
            </nav>
        </div>

        <div class="mx-auto grid max-w-7xl gap-10 px-5 pb-16 sm:px-8 md:grid-cols-[17rem_1fr] lg:gap-16 lg:px-10 lg:pb-24">
            <div class="relative self-start">
                <div class="aspect-square overflow-hidden bg-white/5">
                    <x-initials-image
                        :src="$show->cover_image"
                        :title="$show->title"
                        imgClass="h-full w-full object-cover"
                        fallbackClass="bg-[#17375f]"
                        textClass="font-display text-6xl font-semibold text-white"
                    />
                </div>
                @if($show->explicit)
                    <span class="absolute right-3 top-3 bg-[#07172f] px-2.5 py-1 text-[0.65rem] font-bold tracking-wide text-white">EXPLICIT</span>
                @endif
            </div>

            <div class="self-center">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-[#ff8a2a]">{{ ucfirst($show->category) }} podcast</p>
                <h1 class="font-display mt-3 max-w-4xl text-4xl font-semibold leading-[1.02] tracking-tight sm:text-5xl lg:text-6xl">
                    {{ $show->title }}
                </h1>
                <p class="mt-6 max-w-3xl text-lg leading-8 text-slate-300">
                    {{ \Illuminate\Support\Str::limit(strip_tags($show->description), 260) }}
                </p>

                <div class="mt-7 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-slate-300">
                    <span class="font-semibold text-white">Hosted by {{ $show->host_name }}</span>
                    <span>{{ $show->total_episodes }} {{ \Illuminate\Support\Str::plural('episode', $show->total_episodes) }}</span>
                    <span>{{ ucfirst($show->frequency) }}</span>
                    @if($show->average_rating > 0)
                        <span class="inline-flex items-center gap-2">
                            <i class="fas fa-star text-[#ff8a2a]" aria-hidden="true"></i>
                            {{ number_format($show->average_rating, 1) }}
                        </span>
                    @endif
                </div>

                <div class="mt-9 flex flex-wrap items-center gap-3">
                    @if($episodes->isNotEmpty())
                        <a href="{{ route('podcasts.episode', [$show->slug, $episodes->first()->slug]) }}"
                            class="inline-flex items-center gap-3 bg-[#f36b21] px-6 py-3.5 text-sm font-bold text-white transition hover:bg-[#ff7d32] focus:outline-none focus:ring-2 focus:ring-white">
                            <i class="fas fa-play text-xs" aria-hidden="true"></i>
                            Play latest episode
                        </a>
                    @endif

                    <button type="button" wire:click="toggleSubscribe"
                        class="inline-flex items-center gap-3 border border-white/30 px-6 py-3.5 text-sm font-bold text-white transition hover:border-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white">
                        <i class="fas {{ $isSubscribed ? 'fa-check' : 'fa-plus' }} text-xs" aria-hidden="true"></i>
                        {{ $isSubscribed ? 'Following' : 'Follow show' }}
                    </button>

                    @if($show->spotify_url || $show->apple_url || $show->google_url || $show->rss_feed_url)
                        <div class="flex items-center gap-1 border-l border-white/20 pl-3">
                            @if($show->spotify_url)
                                <a href="{{ $show->spotify_url }}" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex h-11 w-11 items-center justify-center text-slate-300 transition hover:bg-white/10 hover:text-white"
                                    aria-label="Listen on Spotify"><i class="fab fa-spotify" aria-hidden="true"></i></a>
                            @endif
                            @if($show->apple_url)
                                <a href="{{ $show->apple_url }}" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex h-11 w-11 items-center justify-center text-slate-300 transition hover:bg-white/10 hover:text-white"
                                    aria-label="Listen on Apple Podcasts"><i class="fab fa-apple" aria-hidden="true"></i></a>
                            @endif
                            @if($show->google_url)
                                <a href="{{ $show->google_url }}" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex h-11 w-11 items-center justify-center text-slate-300 transition hover:bg-white/10 hover:text-white"
                                    aria-label="Listen on Google Podcasts"><i class="fab fa-google" aria-hidden="true"></i></a>
                            @endif
                            @if($show->rss_feed_url)
                                <a href="{{ $show->rss_feed_url }}" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex h-11 w-11 items-center justify-center text-slate-300 transition hover:bg-white/10 hover:text-white"
                                    aria-label="Open RSS feed"><i class="fas fa-rss" aria-hidden="true"></i></a>
                            @endif
                        </div>
                    @endif
                </div>

                @if(session()->has('success'))
                    <div class="flash-auto-dismiss mt-5 border border-emerald-300/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100" role="status">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session()->has('error'))
                    <div class="flash-auto-dismiss mt-5 border border-red-300/30 bg-red-500/10 px-4 py-3 text-sm text-red-100" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>
    </section>

    @if($showReviewForm)
        <section class="border-b border-[#0b1830]/10 bg-white py-10">
            <div class="mx-auto max-w-3xl px-5 sm:px-8">
                <form wire:submit.prevent="submitReview" class="border-l-4 border-[#f36b21] bg-[#f6f2e9] p-6 sm:p-8">
                    <div class="flex items-start justify-between gap-6">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#d95318]">Listener feedback</p>
                            <h2 class="font-display mt-2 text-2xl font-semibold">Rate this podcast</h2>
                        </div>
                        <button type="button" wire:click="$set('showReviewForm', false)"
                            class="inline-flex h-9 w-9 items-center justify-center text-slate-500 transition hover:bg-white hover:text-[#0b1830]"
                            aria-label="Close review form">
                            <i class="fas fa-times" aria-hidden="true"></i>
                        </button>
                    </div>

                    <fieldset class="mt-6">
                        <legend class="text-sm font-semibold">Your rating</legend>
                        <div class="mt-2 flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" wire:click="$set('rating', {{ $i }})"
                                    class="p-1 text-2xl transition {{ $rating >= $i ? 'text-[#f36b21]' : 'text-slate-300 hover:text-[#f36b21]' }}"
                                    aria-label="{{ $i }} {{ \Illuminate\Support\Str::plural('star', $i) }}">
                                    <i class="fas fa-star" aria-hidden="true"></i>
                                </button>
                            @endfor
                        </div>
                    </fieldset>

                    <label class="mt-5 block">
                        <span class="text-sm font-semibold">Your review <span class="font-normal text-slate-500">(optional)</span></span>
                        <textarea wire:model="review" rows="4" maxlength="500"
                            class="mt-2 w-full border border-[#0b1830]/20 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#f36b21] focus:ring-1 focus:ring-[#f36b21]"
                            placeholder="What stood out to you?"></textarea>
                        @error('review') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>

                    <div class="mt-5 flex flex-wrap gap-3">
                        <button type="submit" class="bg-[#0b1830] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#18375f]">
                            Submit review
                        </button>
                        <button type="button" wire:click="$set('showReviewForm', false)"
                            class="border border-[#0b1830]/20 px-6 py-3 text-sm font-bold transition hover:border-[#0b1830]">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </section>
    @endif

    <main class="py-14 lg:py-20">
        <div class="mx-auto grid max-w-7xl gap-12 px-5 sm:px-8 lg:grid-cols-[1fr_19rem] lg:px-10">
            <div>
                <section>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#d95318]">About the show</p>
                    <h2 class="font-display mt-2 text-3xl font-semibold tracking-tight">The story behind the microphone</h2>
                    <p class="mt-5 max-w-3xl whitespace-pre-line text-base leading-8 text-slate-700">{{ strip_tags($show->description) }}</p>
                </section>

                <section class="mt-14 border-t border-[#0b1830]/10 pt-10">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#d95318]">Catch up</p>
                            <h2 class="font-display mt-2 text-3xl font-semibold tracking-tight">Episodes</h2>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            @if($seasons->isNotEmpty())
                                <label>
                                    <span class="sr-only">Season</span>
                                    <select wire:model.live="selectedSeason"
                                        class="border border-[#0b1830]/20 bg-white px-4 py-2.5 text-sm outline-none focus:border-[#f36b21] focus:ring-1 focus:ring-[#f36b21]">
                                        <option value="all">All seasons</option>
                                        @foreach($seasons as $season)
                                            <option value="{{ $season }}">Season {{ $season }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            @endif
                            <label>
                                <span class="sr-only">Episode order</span>
                                <select wire:model.live="sortBy"
                                    class="border border-[#0b1830]/20 bg-white px-4 py-2.5 text-sm outline-none focus:border-[#f36b21] focus:ring-1 focus:ring-[#f36b21]">
                                    <option value="latest">Latest first</option>
                                    <option value="oldest">Oldest first</option>
                                    <option value="popular">Most played</option>
                                </select>
                            </label>
                        </div>
                    </div>

                    @if($episodes->isNotEmpty())
                        <div class="mt-7 divide-y divide-[#0b1830]/10 border-y border-[#0b1830]/10">
                            @foreach($episodes as $episode)
                                <article class="group grid gap-5 py-6 sm:grid-cols-[8rem_1fr]">
                                    <a href="{{ route('podcasts.episode', [$show->slug, $episode->slug]) }}"
                                        class="relative aspect-square overflow-hidden bg-slate-200 focus:outline-none focus:ring-2 focus:ring-[#f36b21]">
                                        <x-initials-image
                                            :src="$episode->cover_image ?? $show->cover_image"
                                            :title="$episode->title"
                                            imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.04]"
                                            fallbackClass="bg-[#17375f]"
                                            textClass="font-display text-3xl font-semibold text-white"
                                        />
                                        <span class="absolute inset-0 flex items-center justify-center bg-[#07172f]/15 transition group-hover:bg-[#07172f]/35" aria-hidden="true">
                                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white text-[#07172f]">
                                                <i class="fas fa-play ml-0.5 text-xs"></i>
                                            </span>
                                        </span>
                                    </a>
                                    <div class="self-center">
                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[0.68rem] font-bold uppercase tracking-[0.13em] text-slate-500">
                                            <span>{{ $episode->published_at?->format('M j, Y') }}</span>
                                            @if($episode->season_number)
                                                <span>S{{ $episode->season_number }} · E{{ $episode->episode_number }}</span>
                                            @endif
                                            <span>{{ $episode->formatted_duration }}</span>
                                        </div>
                                        <h3 class="font-display mt-2 text-xl font-semibold leading-tight transition group-hover:text-[#d95318] sm:text-2xl">
                                            <a href="{{ route('podcasts.episode', [$show->slug, $episode->slug]) }}"
                                                class="focus:outline-none focus:underline">
                                                {{ $episode->title }}
                                            </a>
                                        </h3>
                                        <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-600">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($episode->description), 175) }}
                                        </p>
                                        <div class="mt-3 flex flex-wrap gap-4 text-xs text-slate-500">
                                            <span>{{ number_format($episode->plays) }} plays</span>
                                            @if($episode->guests && count($episode->guests))
                                                <span>With {{ implode(', ', array_slice($episode->guests, 0, 2)) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="mt-7 border border-dashed border-[#0b1830]/20 bg-white px-6 py-12 text-center">
                            <h3 class="font-display text-2xl font-semibold">No episodes here yet</h3>
                            <p class="mt-2 text-sm text-slate-600">Check back soon for the next release.</p>
                        </div>
                    @endif
                </section>

                @php($approvedReviews = $show->reviews->where('is_approved', true)->sortByDesc('created_at')->take(5))
                @if($approvedReviews->isNotEmpty())
                    <section class="mt-14 border-t border-[#0b1830]/10 pt-10">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#d95318]">From listeners</p>
                        <h2 class="font-display mt-2 text-3xl font-semibold tracking-tight">Reviews</h2>
                        <div class="mt-7 grid gap-px border border-[#0b1830]/10 bg-[#0b1830]/10 sm:grid-cols-2">
                            @foreach($approvedReviews as $review)
                                <article class="bg-white p-6">
                                    <div class="flex items-center justify-between gap-4">
                                        <p class="font-semibold">{{ $review->user?->name ?? 'Glow listener' }}</p>
                                        <span class="text-xs text-slate-500">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="mt-2 flex gap-1 text-xs text-[#f36b21]" aria-label="{{ $review->rating }} out of 5 stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-slate-200' }}" aria-hidden="true"></i>
                                        @endfor
                                    </div>
                                    @if($review->review)
                                        <p class="mt-4 text-sm leading-6 text-slate-600">{{ $review->review }}</p>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <aside class="self-start border-t-4 border-[#f36b21] bg-white p-6 lg:sticky lg:top-28">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#d95318]">Show details</p>
                <dl class="mt-5 divide-y divide-[#0b1830]/10 text-sm">
                    <div class="py-4">
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Host</dt>
                        <dd class="mt-1 font-semibold">{{ $show->host_name }}</dd>
                    </div>
                    <div class="py-4">
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Release pattern</dt>
                        <dd class="mt-1 font-semibold">{{ ucfirst($show->frequency) }}</dd>
                    </div>
                    <div class="py-4">
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Language</dt>
                        <dd class="mt-1 font-semibold">{{ ucfirst($show->language ?: 'English') }}</dd>
                    </div>
                    <div class="py-4">
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Total plays</dt>
                        <dd class="mt-1 font-semibold">{{ number_format($show->total_plays) }}</dd>
                    </div>
                </dl>

                @unless($showReviewForm)
                    <button type="button" wire:click="$set('showReviewForm', true)"
                        class="mt-5 inline-flex w-full items-center justify-center gap-2 border border-[#0b1830] px-5 py-3 text-sm font-bold transition hover:bg-[#0b1830] hover:text-white">
                        <i class="fas fa-star text-xs text-[#f36b21]" aria-hidden="true"></i>
                        Rate this show
                    </button>
                @endunless
            </aside>
        </div>
    </main>
</div>
