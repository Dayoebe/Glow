<div class="min-h-screen bg-[#f6f2e9] text-[#0b1830]">
    <section class="relative overflow-hidden bg-[#07172f] text-white">
        <div class="absolute inset-y-0 right-0 hidden w-2/5 border-l border-white/10 lg:block" aria-hidden="true">
            <div class="absolute inset-0 opacity-20"
                style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,.35) 1px, transparent 0); background-size: 24px 24px;">
            </div>
        </div>

        <div class="relative mx-auto grid max-w-7xl gap-12 px-5 py-16 sm:px-8 lg:grid-cols-[1fr_22rem] lg:px-10 lg:py-24">
            <div class="max-w-3xl">
                <p class="mb-5 text-xs font-bold uppercase tracking-[0.24em] text-[#ff8a2a]">Glow audio</p>
                <h1 class="font-display text-5xl font-semibold leading-[0.96] tracking-tight sm:text-6xl lg:text-7xl">
                    Stories made to stay with you.
                </h1>
                <p class="mt-7 max-w-2xl text-lg leading-8 text-slate-300">
                    Hear original conversations, interviews, culture, public affairs and the voices shaping Akure and Ondo State.
                </p>
                <div class="mt-9 flex flex-wrap gap-3">
                    <a href="#latest-episodes"
                        class="inline-flex items-center gap-3 bg-[#f36b21] px-6 py-3.5 text-sm font-bold text-white transition hover:bg-[#ff7d32] focus:outline-none focus:ring-2 focus:ring-white">
                        <i class="fas fa-play text-xs" aria-hidden="true"></i>
                        Start listening
                    </a>
                    <a href="{{ route('podcasts.feed') }}"
                        class="inline-flex items-center gap-3 border border-white/30 px-6 py-3.5 text-sm font-bold text-white transition hover:border-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white">
                        <i class="fas fa-rss text-xs" aria-hidden="true"></i>
                        Podcast RSS
                    </a>
                </div>
            </div>

            <div class="self-end border-t border-white/20 pt-6 lg:border-l lg:border-t-0 lg:pl-8 lg:pt-0">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">What you will hear</p>
                <ul class="mt-5 space-y-4 text-sm text-slate-200">
                    <li class="flex items-center gap-3"><span class="h-px w-6 bg-[#f36b21]"></span>News and public affairs</li>
                    <li class="flex items-center gap-3"><span class="h-px w-6 bg-[#f36b21]"></span>Music and culture</li>
                    <li class="flex items-center gap-3"><span class="h-px w-6 bg-[#f36b21]"></span>People and community</li>
                </ul>
            </div>
        </div>
    </section>

    @if($featuredShows->isNotEmpty())
        <section class="border-b border-[#0b1830]/10 bg-white py-14 lg:py-20">
            <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">
                <div class="mb-8 flex items-end justify-between gap-6">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#d95318]">Editor’s selection</p>
                        <h2 class="font-display mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Featured podcasts</h2>
                    </div>
                    <span class="hidden text-sm text-slate-500 sm:block">{{ $featuredShows->count() }} selected series</span>
                </div>

                <div class="grid gap-px overflow-hidden border border-[#0b1830]/10 bg-[#0b1830]/10 md:grid-cols-3">
                    @foreach($featuredShows as $show)
                        <a href="{{ route('podcasts.show', $show->slug) }}"
                            class="group flex h-full flex-col bg-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#f36b21]">
                            <div class="relative aspect-square overflow-hidden bg-slate-100">
                                <x-initials-image
                                    :src="$show->cover_image"
                                    :title="$show->title"
                                    imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                                    fallbackClass="bg-[#17375f]"
                                    textClass="font-display text-5xl font-semibold text-white"
                                    :branded="true"
                                    placeholderType="Podcast"
                                    :placeholderSubtitle="'Hosted by ' . ($show->host_name ?: $show->host?->name ?: 'Glow FM')"
                                    :placeholderMeta="ucfirst($show->frequency ?: 'Weekly')"
                                />
                            </div>
                            <div class="flex flex-1 flex-col p-6">
                                <p class="text-[0.68rem] font-bold uppercase tracking-[0.18em] text-[#d95318]">
                                    {{ ucfirst($show->category) }}
                                </p>
                                <h3 class="font-display mt-2 text-2xl font-semibold leading-tight transition group-hover:text-[#d95318]">
                                    {{ $show->title }}
                                </h3>
                                <p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-600">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($show->description), 135) }}
                                </p>
                                <div class="mt-auto flex items-center justify-between border-t border-[#0b1830]/10 pt-5 text-xs text-slate-500">
                                    <span>{{ $show->host_name }}</span>
                                    <span class="inline-flex items-center gap-2 font-bold text-[#0b1830]">
                                        Explore <i class="fas fa-arrow-right text-[0.65rem]" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($latestEpisodes->isNotEmpty())
        <section id="latest-episodes" class="scroll-mt-28 py-14 lg:py-20">
            <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">
                <div class="grid gap-10 lg:grid-cols-[18rem_1fr]">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#d95318]">Fresh from the studio</p>
                        <h2 class="font-display mt-2 text-4xl font-semibold tracking-tight">Latest episodes</h2>
                        <p class="mt-4 text-sm leading-6 text-slate-600">
                            New conversations and catch-up listening, published by the Glow FM team.
                        </p>
                    </div>

                    <div class="divide-y divide-[#0b1830]/10 border-y border-[#0b1830]/10">
                        @foreach($latestEpisodes as $episode)
                            <a href="{{ route('podcasts.episode', [$episode->show->slug, $episode->slug]) }}"
                                class="group grid gap-5 py-6 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#f36b21] sm:grid-cols-[7.5rem_1fr_auto] sm:items-center">
                                <div class="relative aspect-square overflow-hidden bg-slate-200">
                                    <x-initials-image
                                        :src="$episode->cover_image ?? $episode->show->cover_image"
                                        :title="$episode->title"
                                        imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.04]"
                                        fallbackClass="bg-[#17375f]"
                                        textClass="font-display text-3xl font-semibold text-white"
                                        :branded="true"
                                        placeholderType="Episode"
                                        :placeholderSubtitle="'From ' . $episode->show->title"
                                        :placeholderMeta="$episode->published_at?->format('M j, Y')"
                                        :placeholderCompact="true"
                                    />
                                </div>
                                <div>
                                    <p class="text-[0.68rem] font-bold uppercase tracking-[0.16em] text-[#d95318]">{{ $episode->show->title }}</p>
                                    <h3 class="font-display mt-1 text-xl font-semibold leading-tight transition group-hover:text-[#d95318] sm:text-2xl">
                                        {{ $episode->title }}
                                    </h3>
                                    <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-600">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($episode->description), 155) }}
                                    </p>
                                    <p class="mt-3 text-xs text-slate-500">
                                        {{ $episode->published_at?->format('M j, Y') }} · {{ $episode->formatted_duration }}
                                    </p>
                                </div>
                                <span class="inline-flex h-11 w-11 items-center justify-center justify-self-start rounded-full bg-[#0b1830] text-white transition group-hover:bg-[#f36b21] sm:justify-self-end"
                                    aria-hidden="true">
                                    <i class="fas fa-play ml-0.5 text-xs"></i>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="border-y border-[#0b1830]/10 bg-white py-14 lg:py-20">
        <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">
            <div class="mb-9 grid gap-6 lg:grid-cols-[1fr_auto] lg:items-end">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#d95318]">The full collection</p>
                    <h2 class="font-display mt-2 text-4xl font-semibold tracking-tight">Find your next listen</h2>
                </div>

                <div class="grid gap-3 sm:grid-cols-[18rem_13rem]">
                    <label class="relative block">
                        <span class="sr-only">Search podcast shows</span>
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-xs text-slate-400" aria-hidden="true"></i>
                        <input type="search" wire:model.live.debounce.300ms="searchQuery"
                            placeholder="Search podcast shows"
                            class="w-full border border-[#0b1830]/20 bg-[#f6f2e9] py-3 pl-10 pr-4 text-sm text-[#0b1830] outline-none transition placeholder:text-slate-500 focus:border-[#f36b21] focus:ring-1 focus:ring-[#f36b21]">
                    </label>
                    <label>
                        <span class="sr-only">Filter by category</span>
                        <select wire:model.live="selectedCategory"
                            class="w-full border border-[#0b1830]/20 bg-[#f6f2e9] px-4 py-3 text-sm text-[#0b1830] outline-none transition focus:border-[#f36b21] focus:ring-1 focus:ring-[#f36b21]">
                            @foreach($categories as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
            </div>

            @if($shows->isNotEmpty())
                <div class="grid gap-x-7 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($shows as $show)
                        <a href="{{ route('podcasts.show', $show->slug) }}"
                            class="group block focus:outline-none focus:ring-2 focus:ring-[#f36b21] focus:ring-offset-4">
                            <div class="relative aspect-square overflow-hidden bg-slate-100">
                                <x-initials-image
                                    :src="$show->cover_image"
                                    :title="$show->title"
                                    imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.04]"
                                    fallbackClass="bg-[#17375f]"
                                    textClass="font-display text-4xl font-semibold text-white"
                                    :branded="true"
                                    placeholderType="Podcast"
                                    :placeholderSubtitle="'Hosted by ' . ($show->host_name ?: $show->host?->name ?: 'Glow FM')"
                                    :placeholderMeta="ucfirst($show->frequency ?: 'Weekly')"
                                />
                                @if($show->explicit)
                                    <span class="absolute right-3 top-3 bg-[#0b1830] px-2 py-1 text-[0.65rem] font-bold text-white">E</span>
                                @endif
                            </div>
                            <p class="mt-5 text-[0.68rem] font-bold uppercase tracking-[0.17em] text-[#d95318]">
                                {{ ucfirst($show->category) }}
                            </p>
                            <h3 class="font-display mt-1 text-xl font-semibold leading-tight transition group-hover:text-[#d95318]">
                                {{ $show->title }}
                            </h3>
                            <p class="mt-2 text-sm text-slate-600">{{ $show->host_name }}</p>
                            <p class="mt-3 text-xs text-slate-500">{{ $show->published_episodes_count }} {{ \Illuminate\Support\Str::plural('episode', $show->published_episodes_count) }}</p>
                        </a>
                    @endforeach
                </div>

                <div class="mt-12 border-t border-[#0b1830]/10 pt-8">
                    {{ $shows->links() }}
                </div>
            @else
                <div class="border border-dashed border-[#0b1830]/20 bg-[#f6f2e9] px-6 py-16 text-center">
                    <i class="fas fa-headphones text-3xl text-slate-300" aria-hidden="true"></i>
                    <h3 class="font-display mt-4 text-2xl font-semibold">No matching podcasts</h3>
                    <p class="mt-2 text-sm text-slate-600">Try another title, topic or category.</p>
                </div>
            @endif
        </div>
    </section>

    @if($trendingEpisodes->isNotEmpty())
        <section class="py-14 lg:py-20">
            <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">
                <div class="grid gap-10 lg:grid-cols-[18rem_1fr]">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#d95318]">Listener chart</p>
                        <h2 class="font-display mt-2 text-4xl font-semibold tracking-tight">Most played this month</h2>
                    </div>
                    <ol class="divide-y divide-[#0b1830]/10 border-y border-[#0b1830]/10">
                        @foreach($trendingEpisodes as $index => $episode)
                            <li>
                                <a href="{{ route('podcasts.episode', [$episode->show->slug, $episode->slug]) }}"
                                    class="group grid grid-cols-[2.5rem_1fr_auto] items-center gap-4 py-5 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#f36b21]">
                                    <span class="font-display text-2xl text-[#d95318]">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <span>
                                        <strong class="block font-display text-lg font-semibold leading-tight transition group-hover:text-[#d95318]">{{ $episode->title }}</strong>
                                        <small class="mt-1 block text-xs text-slate-500">{{ $episode->show->title }}</small>
                                    </span>
                                    <span class="hidden text-xs text-slate-500 sm:block">{{ number_format($episode->plays) }} plays</span>
                                </a>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </section>
    @endif

    <section class="bg-[#f36b21] text-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 px-5 py-12 sm:px-8 md:flex-row md:items-center md:justify-between lg:px-10">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#07172f]">Take Glow with you</p>
                <h2 class="font-display mt-2 text-3xl font-semibold">Follow every new episode.</h2>
            </div>
            <a href="{{ route('podcasts.feed') }}"
                class="inline-flex items-center justify-center gap-3 self-start bg-[#07172f] px-6 py-3.5 text-sm font-bold text-white transition hover:bg-[#112b50] focus:outline-none focus:ring-2 focus:ring-white md:self-auto">
                <i class="fas fa-rss text-xs" aria-hidden="true"></i>
                Open the RSS feed
            </a>
        </div>
    </section>
</div>
