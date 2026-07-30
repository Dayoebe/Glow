<div class="bg-[#f7f4ee] text-slate-950">
    @normalizeArray($featuredShows)
    @normalizeArray($latestPodcastEpisodes)
    @normalizeArray($latestNews)
    @normalizeArray($upcomingEvents)
    @normalizeArray($featuredNews)
    @normalizeArray($otherNews)
    @normalizeArray($homeContent)
    @normalizeArray($currentShow)
    @normalizeArray($nextShow)

    @php
        $newsPool = collect(array_merge($featuredNews, $latestNews, $otherNews))
            ->filter(fn ($story) => is_array($story) && !empty($story['slug']) && !empty($story['title']))
            ->unique(fn ($story) => $story['id'] ?? $story['slug'])
            ->values();

        $leadStory = $newsPool->first();
        $topStories = $newsPool->skip(1)->take(4)->values();
        $topStorySlugs = $topStories
            ->pluck('slug')
            ->when($leadStory, fn ($slugs) => $slugs->push($leadStory['slug']))
            ->filter()
            ->all();

        $latestStories = collect(array_merge($latestNews, $otherNews))
            ->filter(fn ($story) => is_array($story) && !empty($story['slug']) && !empty($story['title']))
            ->reject(fn ($story) => in_array($story['slug'], $topStorySlugs, true))
            ->unique(fn ($story) => $story['id'] ?? $story['slug'])
            ->take(4)
            ->values();

        $homepageEvents = collect($upcomingEvents)
            ->filter(fn ($event) => is_array($event) && !empty($event['slug']) && !empty($event['title']))
            ->take(3)
            ->values();
        $primaryEvent = $homepageEvents->first();
        $secondaryEvents = $homepageEvents->skip(1);

        $onAirTitle = $currentShow['title'] ?? 'Glow 99.1 FM';
        $onAirArtwork = $currentShow['image'] ?? asset('glowfm logo.jpeg');
    @endphp

    @if($breakingNews)
        <aside class="border-b border-orange-700 bg-[#e95516] text-white" aria-label="Breaking news">
            <div class="mx-auto flex max-w-[1440px] items-center gap-3 px-4 py-2.5 sm:px-6 lg:px-8">
                <span class="inline-flex shrink-0 items-center gap-2 text-[11px] font-extrabold uppercase tracking-[0.16em]">
                    <span class="h-2 w-2 rounded-full bg-white"></span>
                    Breaking
                </span>
                <span class="hidden h-4 w-px bg-white/35 sm:block"></span>
                <a
                    href="{{ route('news.show', $breakingNews->slug) }}"
                    class="min-w-0 flex-1 truncate text-sm font-semibold transition-opacity hover:opacity-80"
                >
                    {{ $breakingNews->title }}
                </a>
                <a
                    href="{{ route('news.show', $breakingNews->slug) }}"
                    class="inline-flex shrink-0 items-center gap-2 text-xs font-bold uppercase tracking-wider"
                >
                    Read
                    <i class="fas fa-arrow-right text-[10px]" aria-hidden="true"></i>
                </a>
            </div>
        </aside>
    @endif

    <section class="relative isolate overflow-hidden bg-[#07182b] text-white">
        <div
            class="absolute inset-0 -z-10 opacity-80"
            style="background-image: radial-gradient(circle at 78% 24%, rgba(243, 106, 33, .22), transparent 31%), radial-gradient(circle at 12% 90%, rgba(45, 87, 125, .36), transparent 34%);"
        ></div>
        <div class="absolute inset-x-0 bottom-0 -z-10 h-px bg-white/10"></div>

        <div class="mx-auto grid max-w-[1440px] gap-10 px-4 py-14 sm:px-6 sm:py-16 lg:grid-cols-[minmax(0,0.92fr)_minmax(480px,1.08fr)] lg:items-center lg:gap-16 lg:px-8 lg:py-20">
            <div class="max-w-2xl">
                <div class="mb-6 flex items-center gap-3">
                    <span class="inline-flex items-center gap-2 rounded-md border border-orange-400/40 bg-orange-500/10 px-3 py-1.5 text-xs font-extrabold uppercase tracking-[0.18em] text-orange-300">
                        <span class="h-2 w-2 rounded-full bg-orange-400"></span>
                        Live from Akure
                    </span>
                    <span class="text-xs font-semibold uppercase tracking-[0.16em] text-white/50">99.1 FM</span>
                </div>

                <h1 class="max-w-xl text-4xl font-black leading-[0.98] tracking-[-0.045em] text-white sm:text-5xl md:text-6xl lg:text-7xl">
                    {{ $homeContent['hero_title'] ?? 'Your Voice,' }}
                    <span class="mt-2 block text-[#f47a35]">{{ $homeContent['hero_highlight'] ?? 'Your Music' }}</span>
                </h1>

                <p class="mt-6 max-w-xl text-base leading-7 text-slate-300 sm:text-lg">
                    {{ $homeContent['hero_subtitle'] ?? 'Broadcasting the heartbeat of Akure, 24/7 on 99.1 FM.' }}
                </p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a
                        href="{{ route('listen.live') }}"
                        @click.prevent="startLive"
                        class="inline-flex min-h-12 items-center justify-center gap-3 rounded-lg bg-[#f36a21] px-6 py-3 text-sm font-extrabold text-white shadow-[0_12px_30px_rgba(243,106,33,0.24)] transition hover:bg-[#ff7a30] focus:outline-none focus:ring-2 focus:ring-orange-300 focus:ring-offset-2 focus:ring-offset-[#07182b]"
                    >
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white text-[#f36a21]">
                            <i class="fas fa-play ml-0.5 text-[10px]" aria-hidden="true"></i>
                        </span>
                        {{ $homeContent['primary_cta_text'] ?? 'Listen Live' }}
                    </a>
                    <a
                        href="{{ route('schedule') }}"
                        class="inline-flex min-h-12 items-center justify-center gap-3 rounded-lg border border-white/20 bg-white/[0.06] px-6 py-3 text-sm font-bold text-white transition hover:border-white/35 hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/60"
                    >
                        <i class="far fa-calendar-alt text-orange-300" aria-hidden="true"></i>
                        View today’s schedule
                    </a>
                </div>

                <div class="mt-9 flex flex-wrap gap-x-6 gap-y-3 border-t border-white/10 pt-5 text-sm">
                    <a href="{{ route('news') }}" class="font-semibold text-slate-300 transition hover:text-white">
                        Latest news <i class="fas fa-arrow-right ml-1 text-[10px] text-orange-400" aria-hidden="true"></i>
                    </a>
                    <a href="{{ route('podcasts.index') }}" class="font-semibold text-slate-300 transition hover:text-white">
                        Listen back <i class="fas fa-arrow-right ml-1 text-[10px] text-orange-400" aria-hidden="true"></i>
                    </a>
                    <a href="{{ route('shows.index') }}" class="font-semibold text-slate-300 transition hover:text-white">
                        Explore shows <i class="fas fa-arrow-right ml-1 text-[10px] text-orange-400" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <div wire:poll.visible.60s="refreshCurrentShow" class="relative">
                <div class="absolute -inset-5 -z-10 rounded-[2rem] bg-orange-500/10 blur-2xl"></div>
                <div class="overflow-hidden rounded-2xl border border-white/15 bg-[#0c2138] shadow-[0_28px_70px_rgba(0,0,0,0.35)]">
                    <div class="grid sm:grid-cols-[minmax(210px,0.84fr)_minmax(0,1.16fr)]">
                        <div class="relative min-h-[260px] overflow-hidden sm:min-h-[390px]">
                            <x-initials-image
                                :src="$onAirArtwork"
                                :title="$onAirTitle"
                                imgClass="absolute inset-0 h-full w-full object-cover"
                                fallbackClass="bg-[#102b48]"
                                textClass="text-5xl font-black text-white"
                                loading="eager"
                                fetchpriority="high"
                                width="640"
                                height="800"
                                sizes="(min-width: 1024px) 27vw, (min-width: 640px) 42vw, 92vw"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-[#07182b] via-transparent to-black/10"></div>
                            <span class="absolute left-4 top-4 inline-flex items-center gap-2 rounded-md bg-[#e95516] px-3 py-1.5 text-[11px] font-black uppercase tracking-[0.16em] text-white shadow-lg">
                                <span class="h-2 w-2 rounded-full bg-white"></span>
                                On air
                            </span>
                        </div>

                        <div class="flex flex-col justify-between p-6 sm:p-8">
                            <div>
                                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-orange-300">Now playing</p>
                                <h2 class="mt-3 text-3xl font-black leading-tight tracking-[-0.03em] text-white">
                                    @if(!empty($currentShow['slug']))
                                        <a href="{{ route('shows.show', $currentShow['slug']) }}" class="transition hover:text-orange-300">
                                            {{ $currentShow['title'] ?? 'Glow 99.1 FM' }}
                                        </a>
                                    @else
                                        {{ $currentShow['title'] ?? 'Live on Glow 99.1 FM' }}
                                    @endif
                                </h2>

                                @if(!empty($currentShow))
                                    <div class="mt-4 space-y-2 text-sm text-slate-300">
                                        <p class="flex items-center gap-2">
                                            <i class="fas fa-microphone-alt w-4 text-orange-400" aria-hidden="true"></i>
                                            @if(!empty($currentShow['host_slug']))
                                                <a href="{{ route('oaps.show', $currentShow['host_slug']) }}" class="transition hover:text-white">
                                                    {{ $currentShow['host'] ?? 'Host TBA' }}
                                                </a>
                                            @else
                                                <span>{{ $currentShow['host'] ?? 'Host TBA' }}</span>
                                            @endif
                                        </p>
                                        @if(!empty($currentShow['time']))
                                            <p class="flex items-center gap-2">
                                                <i class="far fa-clock w-4 text-orange-400" aria-hidden="true"></i>
                                                <span>{{ $currentShow['time'] }} WAT</span>
                                            </p>
                                        @endif
                                    </div>
                                @else
                                    <p class="mt-4 text-sm leading-6 text-slate-300">
                                        The live stream is always on. Check the schedule to see what’s playing next.
                                    </p>
                                @endif
                            </div>

                            <button
                                type="button"
                                @click="startLive"
                                class="mt-8 flex w-full items-center justify-between rounded-xl border border-white/10 bg-white/[0.06] p-3.5 text-left transition hover:border-orange-400/50 hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-orange-300"
                                aria-label="Play Glow 99.1 FM live"
                            >
                                <span class="flex items-center gap-3">
                                    <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-[#f36a21] text-white">
                                        <i class="fas fa-play ml-0.5 text-sm" aria-hidden="true"></i>
                                    </span>
                                    <span>
                                        <span class="block text-xs font-bold uppercase tracking-wider text-slate-400">Live stream</span>
                                        <span class="mt-0.5 block text-sm font-extrabold text-white">Play Glow FM</span>
                                    </span>
                                </span>
                                <i class="fas fa-volume-up text-orange-300" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-16 sm:py-20" aria-labelledby="top-stories-heading">
        <div class="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex items-end justify-between gap-6 border-b border-slate-200 pb-5">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#e95516]">Glow newsroom</p>
                    <h2 id="top-stories-heading" class="mt-2 text-3xl font-black tracking-[-0.035em] text-[#07182b] sm:text-4xl">
                        Top stories
                    </h2>
                </div>
                <a href="{{ route('news') }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#173b5f] transition hover:text-[#e95516]">
                    All news
                    <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                </a>
            </div>

            @if($leadStory)
                <div class="grid gap-7 lg:grid-cols-[minmax(0,1.42fr)_minmax(380px,0.88fr)]">
                    <article class="group overflow-hidden border-b border-slate-200 pb-6 lg:border-b-0 lg:border-r lg:pb-0 lg:pr-7">
                        <a
                            href="{{ route('news.show', $leadStory['slug']) }}"
                            class="relative block aspect-[16/10] overflow-hidden rounded-xl bg-slate-100"
                            aria-label="Read {{ $leadStory['title'] }}"
                        >
                            <x-initials-image
                                :src="$leadStory['image'] ?? null"
                                :title="$leadStory['title']"
                                imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.025]"
                                fallbackClass="bg-[#173b5f]"
                                textClass="text-5xl font-black text-white"
                            />
                        </a>
                        <div class="mt-5">
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-2 text-xs font-bold uppercase tracking-[0.12em]">
                                <span class="text-[#e95516]">{{ $leadStory['category'] ?? 'News' }}</span>
                                <span class="text-slate-300">/</span>
                                <span class="text-slate-500">{{ $leadStory['date'] ?? '' }}</span>
                                @if(!empty($leadStory['read_time']))
                                    <span class="text-slate-300">/</span>
                                    <span class="text-slate-500">{{ $leadStory['read_time'] }}</span>
                                @endif
                            </div>
                            <h3 class="mt-3 max-w-4xl text-3xl font-black leading-[1.08] tracking-[-0.035em] text-[#07182b] sm:text-4xl">
                                <a href="{{ route('news.show', $leadStory['slug']) }}" class="transition hover:text-[#d94e12]">
                                    {{ $leadStory['title'] }}
                                </a>
                            </h3>
                            @if(!empty($leadStory['excerpt']))
                                <p class="mt-4 max-w-3xl text-base leading-7 text-slate-600 line-clamp-2">
                                    {{ $leadStory['excerpt'] }}
                                </p>
                            @endif
                        </div>
                    </article>

                    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-1">
                        @foreach($topStories as $story)
                            @continueIfNotArray($story)
                            <article class="group grid grid-cols-[112px_minmax(0,1fr)] gap-4 border-b border-slate-200 pb-5 last:border-b-0 last:pb-0 sm:grid-cols-1 lg:grid-cols-[132px_minmax(0,1fr)]">
                                <a
                                    href="{{ route('news.show', $story['slug']) }}"
                                    class="relative block aspect-square overflow-hidden rounded-lg bg-slate-100 sm:aspect-[16/10] lg:aspect-square"
                                    aria-label="Read {{ $story['title'] }}"
                                >
                                    <x-initials-image
                                        :src="$story['image'] ?? null"
                                        :title="$story['title']"
                                        imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.04]"
                                        fallbackClass="bg-[#173b5f]"
                                        textClass="text-2xl font-black text-white"
                                    />
                                </a>
                                <div class="min-w-0 self-center">
                                    <p class="text-[11px] font-extrabold uppercase tracking-[0.15em] text-[#e95516]">
                                        {{ $story['category'] ?? 'News' }}
                                    </p>
                                    <h3 class="mt-2 text-lg font-black leading-snug tracking-[-0.02em] text-[#07182b] sm:text-xl lg:text-lg">
                                        <a href="{{ route('news.show', $story['slug']) }}" class="transition hover:text-[#d94e12]">
                                            {{ $story['title'] }}
                                        </a>
                                    </h3>
                                    <p class="mt-2 text-xs font-medium text-slate-500">
                                        {{ $story['date'] ?? '' }}
                                    </p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="rounded-xl border border-slate-200 bg-[#f7f4ee] px-6 py-12 text-center">
                    <p class="text-sm font-bold uppercase tracking-[0.15em] text-[#e95516]">Newsroom update</p>
                    <h3 class="mt-2 text-2xl font-black text-[#07182b]">Fresh stories are on the way.</h3>
                    <p class="mt-2 text-slate-600">Check back shortly for the latest from Glow FM.</p>
                </div>
            @endif

            @if($latestStories->isNotEmpty())
                <div class="mt-14 border-t-2 border-[#07182b] pt-5">
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-xl font-black tracking-[-0.02em] text-[#07182b]">Latest updates</h3>
                        <span class="hidden text-xs font-bold uppercase tracking-[0.14em] text-slate-400 sm:inline">From the Glow newsroom</span>
                    </div>
                    <div class="grid gap-x-8 gap-y-0 md:grid-cols-2">
                        @foreach($latestStories as $story)
                            @continueIfNotArray($story)
                            <article class="group grid grid-cols-[88px_minmax(0,1fr)] gap-4 border-b border-slate-200 py-5 first:pt-0 md:grid-cols-[104px_minmax(0,1fr)]">
                                <a
                                    href="{{ route('news.show', $story['slug']) }}"
                                    class="relative block aspect-square overflow-hidden rounded-lg bg-slate-100"
                                    aria-label="Read {{ $story['title'] }}"
                                >
                                    <x-initials-image
                                        :src="$story['image'] ?? null"
                                        :title="$story['title']"
                                        imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.04]"
                                        fallbackClass="bg-[#173b5f]"
                                        textClass="text-xl font-black text-white"
                                    />
                                </a>
                                <div class="min-w-0 self-center">
                                    <p class="text-[10px] font-extrabold uppercase tracking-[0.15em] text-[#e95516]">
                                        {{ $story['category'] ?? 'News' }}
                                    </p>
                                    <h4 class="mt-1.5 text-base font-black leading-snug text-[#07182b] sm:text-lg">
                                        <a href="{{ route('news.show', $story['slug']) }}" class="transition hover:text-[#d94e12]">
                                            {{ $story['title'] }}
                                        </a>
                                    </h4>
                                    <p class="mt-1 text-xs font-medium text-slate-500">{{ $story['date'] ?? '' }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <section class="border-y border-slate-200 bg-[#f7f4ee] py-7">
        <div class="mx-auto max-w-[1200px] px-4 sm:px-6 lg:px-8">
            <x-ad-slot placement="home" />
        </div>
    </section>

    <section class="bg-[#07182b] py-16 text-white sm:py-20" aria-labelledby="schedule-heading">
        <div class="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[minmax(260px,0.6fr)_minmax(0,1.4fr)] lg:items-end">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-orange-300">Today on 99.1 FM</p>
                    <h2 id="schedule-heading" class="mt-2 text-3xl font-black tracking-[-0.035em] sm:text-4xl">Now &amp; next</h2>
                    <p class="mt-4 max-w-md text-sm leading-6 text-slate-300">
                        Stay with Glow throughout the day for conversation, music, news and the voices shaping our community.
                    </p>
                    <a href="{{ route('schedule') }}" class="mt-6 inline-flex items-center gap-2 text-sm font-extrabold text-orange-300 transition hover:text-white">
                        Full weekly schedule
                        <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                    </a>
                </div>

                <div class="grid overflow-hidden rounded-xl border border-white/15 bg-white/[0.04] md:grid-cols-2">
                    <article class="relative p-6 sm:p-7 md:border-r md:border-white/10">
                        <div class="absolute inset-y-0 left-0 w-1 bg-[#f36a21]"></div>
                        <div class="flex items-center justify-between gap-3">
                            <p class="flex items-center gap-2 text-[11px] font-black uppercase tracking-[0.18em] text-orange-300">
                                <span class="h-2 w-2 rounded-full bg-orange-400"></span>
                                On now
                            </p>
                            <span class="text-xs font-bold text-slate-400">WAT</span>
                        </div>
                        <h3 class="mt-5 text-2xl font-black tracking-[-0.025em]">
                            @if(!empty($currentShow['slug']))
                                <a href="{{ route('shows.show', $currentShow['slug']) }}" class="transition hover:text-orange-300">
                                    {{ $currentShow['title'] ?? 'Glow 99.1 FM' }}
                                </a>
                            @else
                                {{ $currentShow['title'] ?? 'Glow 99.1 FM live' }}
                            @endif
                        </h3>
                        <p class="mt-2 text-sm text-slate-300">
                            {{ $currentShow['host'] ?? 'Live programming' }}
                            @if(!empty($currentShow['time']))
                                <span class="mx-2 text-slate-600">•</span>{{ $currentShow['time'] }}
                            @endif
                        </p>
                        <button type="button" @click="startLive" class="mt-5 inline-flex items-center gap-2 text-sm font-extrabold text-white transition hover:text-orange-300">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#f36a21]">
                                <i class="fas fa-play ml-0.5 text-[10px]" aria-hidden="true"></i>
                            </span>
                            Listen live
                        </button>
                    </article>

                    <article class="p-6 sm:p-7">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-slate-400">Up next</p>
                            @if(!empty($nextShow['day']))
                                <span class="text-xs font-bold text-orange-300">{{ $nextShow['day'] }}</span>
                            @endif
                        </div>
                        @if(!empty($nextShow))
                            <h3 class="mt-5 text-2xl font-black tracking-[-0.025em]">
                                @if(!empty($nextShow['slug']))
                                    <a href="{{ route('shows.show', $nextShow['slug']) }}" class="transition hover:text-orange-300">
                                        {{ $nextShow['title'] ?? 'Next programme' }}
                                    </a>
                                @else
                                    {{ $nextShow['title'] ?? 'Next programme' }}
                                @endif
                            </h3>
                            <p class="mt-2 text-sm text-slate-300">
                                {{ $nextShow['host'] ?? 'Host TBA' }}
                                @if(!empty($nextShow['time']))
                                    <span class="mx-2 text-slate-600">•</span>{{ $nextShow['time'] }}
                                @endif
                            </p>
                            @if(!empty($nextShow['slug']))
                                <a href="{{ route('shows.show', $nextShow['slug']) }}" class="mt-6 inline-flex items-center gap-2 text-sm font-bold text-orange-300 transition hover:text-white">
                                    Programme details <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                                </a>
                            @endif
                        @else
                            <h3 class="mt-5 text-2xl font-black tracking-[-0.025em]">More live radio follows</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-300">Browse the full schedule for upcoming programmes.</p>
                        @endif
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#f7f4ee] py-16 sm:py-20" aria-labelledby="listen-back-heading">
        <div class="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex items-end justify-between gap-6">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#e95516]">On demand</p>
                    <h2 id="listen-back-heading" class="mt-2 text-3xl font-black tracking-[-0.035em] text-[#07182b] sm:text-4xl">
                        Listen back
                    </h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600 sm:text-base">
                        Catch the conversations, interviews and moments you missed on air.
                    </p>
                </div>
                <a href="{{ route('podcasts.index') }}" class="hidden items-center gap-2 text-sm font-bold text-[#173b5f] transition hover:text-[#e95516] sm:inline-flex">
                    All podcasts
                    <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                </a>
            </div>

            @if(count($latestPodcastEpisodes) > 0)
                <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                    @foreach(array_slice($latestPodcastEpisodes, 0, 3) as $episode)
                        @continueIfNotArray($episode)
                        <article class="group grid grid-cols-[112px_minmax(0,1fr)] overflow-hidden rounded-xl border border-slate-200 bg-white sm:grid-cols-[148px_minmax(0,1fr)]">
                            <a
                                href="{{ route('podcasts.episode', [$episode['show_slug'], $episode['slug']]) }}"
                                class="relative block min-h-[160px] overflow-hidden bg-slate-100"
                                aria-label="Listen to {{ $episode['title'] }}"
                            >
                                <x-initials-image
                                    :src="$episode['image'] ?? null"
                                    :title="$episode['title'] ?? ''"
                                    imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.04]"
                                    fallbackClass="bg-[#173b5f]"
                                    textClass="text-3xl font-black text-white"
                                />
                                <span class="absolute bottom-3 left-3 flex h-9 w-9 items-center justify-center rounded-lg bg-[#f36a21] text-white shadow-lg">
                                    <i class="fas fa-play ml-0.5 text-[10px]" aria-hidden="true"></i>
                                </span>
                            </a>
                            <div class="flex min-w-0 flex-col justify-center p-4 sm:p-5">
                                <p class="truncate text-[10px] font-extrabold uppercase tracking-[0.14em] text-[#e95516]">
                                    {{ $episode['show_title'] ?? 'Glow podcast' }}
                                </p>
                                <h3 class="mt-2 text-base font-black leading-snug text-[#07182b] sm:text-lg">
                                    <a href="{{ route('podcasts.episode', [$episode['show_slug'], $episode['slug']]) }}" class="transition hover:text-[#d94e12]">
                                        {{ $episode['title'] }}
                                    </a>
                                </h3>
                                <div class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px] font-semibold text-slate-500">
                                    @if(!empty($episode['duration']))
                                        <span><i class="far fa-clock mr-1 text-[#e95516]" aria-hidden="true"></i>{{ $episode['duration'] }}</span>
                                    @endif
                                    <span>{{ $episode['published_at'] ?? '' }}</span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="rounded-xl border border-slate-200 bg-white p-8">
                    <p class="text-sm font-bold uppercase tracking-[0.15em] text-[#e95516]">Coming up</p>
                    <h3 class="mt-2 text-2xl font-black text-[#07182b]">New listen-back episodes are being prepared.</h3>
                    <p class="mt-2 text-slate-600">Browse our programmes while you wait for the next upload.</p>
                </div>
            @endif

            <a href="{{ route('podcasts.index') }}" class="mt-7 inline-flex items-center gap-2 text-sm font-extrabold text-[#173b5f] transition hover:text-[#e95516] sm:hidden">
                Browse all podcasts
                <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
            </a>
        </div>
    </section>

    @if(count($featuredShows) > 0)
        <section class="bg-white py-16 sm:py-20" aria-labelledby="featured-shows-heading">
            <div class="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-8">
                <div class="mb-8 flex items-end justify-between gap-6 border-b border-slate-200 pb-5">
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#e95516]">Meet the voices</p>
                        <h2 id="featured-shows-heading" class="mt-2 text-3xl font-black tracking-[-0.035em] text-[#07182b] sm:text-4xl">
                            Featured shows
                        </h2>
                    </div>
                    <a href="{{ route('shows.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#173b5f] transition hover:text-[#e95516]">
                        All shows
                        <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                    </a>
                </div>

                <div class="grid gap-6 md:grid-cols-3">
                    @foreach(array_slice($featuredShows, 0, 3) as $show)
                        @continueIfNotArray($show)
                        @php($showSlug = $show['slug'] ?? null)
                        <article class="group">
                            @if($showSlug)
                                <a
                                    href="{{ route('shows.show', $showSlug) }}"
                                    class="relative block aspect-[4/3] overflow-hidden rounded-xl bg-slate-100"
                                    aria-label="View {{ $show['title'] }}"
                                >
                            @else
                                <div class="relative block aspect-[4/3] overflow-hidden rounded-xl bg-slate-100">
                            @endif
                                <x-initials-image
                                    :src="$show['image'] ?? null"
                                    :title="$show['title'] ?? ''"
                                    imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.035]"
                                    fallbackClass="bg-[#173b5f]"
                                    textClass="text-4xl font-black text-white"
                                />
                                <div class="absolute inset-0 bg-gradient-to-t from-[#07182b]/85 via-transparent to-transparent"></div>
                                <span class="absolute bottom-4 left-4 text-[11px] font-extrabold uppercase tracking-[0.15em] text-orange-300">
                                    {{ $show['days'] ?? 'Weekly' }} · {{ $show['time'] ?? 'Schedule TBA' }}
                                </span>
                            @if($showSlug)
                                </a>
                            @else
                                </div>
                            @endif

                            <div class="pt-4">
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.15em] text-[#e95516]">{{ $show['category'] ?? 'Show' }}</p>
                                <h3 class="mt-1.5 text-xl font-black tracking-[-0.02em] text-[#07182b]">
                                    @if($showSlug)
                                        <a href="{{ route('shows.show', $showSlug) }}" class="transition hover:text-[#d94e12]">
                                            {{ $show['title'] }}
                                        </a>
                                    @else
                                        {{ $show['title'] }}
                                    @endif
                                </h3>
                                <p class="mt-2 text-sm text-slate-600">
                                    With
                                    @if(!empty($show['host_slug']))
                                        <a href="{{ route('oaps.show', $show['host_slug']) }}" class="font-bold text-[#173b5f] transition hover:text-[#e95516]">
                                            {{ $show['host'] }}
                                        </a>
                                    @else
                                        <span class="font-bold text-[#173b5f]">{{ $show['host'] ?? 'Host TBA' }}</span>
                                    @endif
                                </p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="border-t border-slate-200 bg-[#f7f4ee] py-16 sm:py-20" aria-labelledby="community-heading">
        <div class="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[minmax(280px,0.62fr)_minmax(0,1.38fr)]">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#e95516]">Beyond the studio</p>
                    <h2 id="community-heading" class="mt-2 text-3xl font-black tracking-[-0.035em] text-[#07182b] sm:text-4xl">
                        Glow in the community
                    </h2>
                    <p class="mt-4 max-w-md text-sm leading-6 text-slate-600 sm:text-base">
                        Join the experiences, conversations and gatherings bringing listeners together across Akure.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-4">
                        <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 text-sm font-extrabold text-[#173b5f] transition hover:text-[#e95516]">
                            Explore events
                            <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                        </a>
                        <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 text-sm font-extrabold text-[#173b5f] transition hover:text-[#e95516]">
                            Contact Glow
                            <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                @if($primaryEvent)
                    <div class="grid gap-5 md:grid-cols-[minmax(0,1.25fr)_minmax(240px,0.75fr)]">
                        <article class="group overflow-hidden rounded-xl border border-slate-200 bg-white">
                            <a
                                href="{{ route('events.show', $primaryEvent['slug']) }}"
                                class="relative block aspect-[16/9] overflow-hidden bg-slate-100"
                                aria-label="View {{ $primaryEvent['title'] }}"
                            >
                                <x-initials-image
                                    :src="$primaryEvent['image'] ?? null"
                                    :title="$primaryEvent['title']"
                                    imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                                    fallbackClass="bg-[#173b5f]"
                                    textClass="text-4xl font-black text-white"
                                />
                            </a>
                            <div class="p-5 sm:p-6">
                                <p class="text-[10px] font-extrabold uppercase tracking-[0.15em] text-[#e95516]">{{ $primaryEvent['category'] ?? 'Glow event' }}</p>
                                <h3 class="mt-2 text-2xl font-black leading-tight tracking-[-0.025em] text-[#07182b]">
                                    <a href="{{ route('events.show', $primaryEvent['slug']) }}" class="transition hover:text-[#d94e12]">
                                        {{ $primaryEvent['title'] }}
                                    </a>
                                </h3>
                                <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-xs font-semibold text-slate-500">
                                    <span><i class="far fa-calendar-alt mr-1.5 text-[#e95516]" aria-hidden="true"></i>{{ $primaryEvent['date'] }}</span>
                                    <span><i class="fas fa-map-marker-alt mr-1.5 text-[#e95516]" aria-hidden="true"></i>{{ $primaryEvent['location'] }}</span>
                                </div>
                            </div>
                        </article>

                        <div class="space-y-4">
                            @forelse($secondaryEvents as $event)
                                @continueIfNotArray($event)
                                <article class="group grid grid-cols-[92px_minmax(0,1fr)] gap-4 rounded-xl border border-slate-200 bg-white p-3">
                                    <a
                                        href="{{ route('events.show', $event['slug']) }}"
                                        class="relative block aspect-square overflow-hidden rounded-lg bg-slate-100"
                                        aria-label="View {{ $event['title'] }}"
                                    >
                                        <x-initials-image
                                            :src="$event['image'] ?? null"
                                            :title="$event['title']"
                                            imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.04]"
                                            fallbackClass="bg-[#173b5f]"
                                            textClass="text-xl font-black text-white"
                                        />
                                    </a>
                                    <div class="min-w-0 self-center">
                                        <p class="text-[10px] font-extrabold uppercase tracking-[0.14em] text-[#e95516]">{{ $event['date'] }}</p>
                                        <h3 class="mt-1.5 text-base font-black leading-snug text-[#07182b]">
                                            <a href="{{ route('events.show', $event['slug']) }}" class="transition hover:text-[#d94e12]">
                                                {{ $event['title'] }}
                                            </a>
                                        </h3>
                                        <p class="mt-1 truncate text-xs text-slate-500">{{ $event['location'] }}</p>
                                    </div>
                                </article>
                            @empty
                                <div class="rounded-xl border border-slate-200 bg-white p-5">
                                    <p class="text-sm font-bold text-[#07182b]">More community moments are coming.</p>
                                    <a href="{{ route('events.index') }}" class="mt-3 inline-flex items-center gap-2 text-xs font-extrabold text-[#e95516]">
                                        See all events <i class="fas fa-arrow-right text-[10px]" aria-hidden="true"></i>
                                    </a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @else
                    <div class="rounded-xl border border-slate-200 bg-white p-8 sm:p-10">
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#e95516]">Stay connected</p>
                        <h3 class="mt-3 text-2xl font-black tracking-[-0.025em] text-[#07182b]">The next Glow experience is being planned.</h3>
                        <p class="mt-3 max-w-xl text-sm leading-6 text-slate-600">
                            Follow our event page for announcements, live broadcasts and opportunities to meet the Glow FM team.
                        </p>
                        <a href="{{ route('events.index') }}" class="mt-5 inline-flex items-center gap-2 text-sm font-extrabold text-[#173b5f] transition hover:text-[#e95516]">
                            Visit events
                            <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section id="newsletter" class="scroll-mt-28 bg-[#e95516] py-12 text-white sm:py-14">
        <div class="mx-auto grid max-w-[1440px] gap-7 px-4 sm:px-6 lg:grid-cols-[minmax(0,0.85fr)_minmax(420px,1.15fr)] lg:items-center lg:gap-14 lg:px-8">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-orange-100">The Glow briefing</p>
                <h2 class="mt-2 text-3xl font-black tracking-[-0.035em] sm:text-4xl">Stay close to the stories.</h2>
                <p class="mt-3 max-w-xl text-sm leading-6 text-orange-50 sm:text-base">
                    Get newsroom highlights, programme updates and the best of Glow FM delivered to your inbox.
                </p>
            </div>

            <form method="POST" action="{{ route('newsletter.subscribe') }}" class="sm:flex sm:items-start sm:gap-3">
                @csrf
                <div class="flex-1">
                    <label for="homepage-newsletter-email" class="sr-only">Email address</label>
                    <input
                        id="homepage-newsletter-email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        placeholder="Your email address"
                        class="min-h-12 w-full rounded-lg border border-white/30 bg-white px-4 py-3 text-sm font-semibold text-[#07182b] placeholder:text-slate-400 focus:border-[#07182b] focus:outline-none focus:ring-2 focus:ring-[#07182b]/25"
                    >
                    @error('email')
                        <p class="mt-2 text-xs font-semibold text-white">{{ $message }}</p>
                    @enderror
                </div>
                <button
                    type="submit"
                    class="mt-3 inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-lg bg-[#07182b] px-6 py-3 text-sm font-extrabold text-white transition hover:bg-[#102b48] focus:outline-none focus:ring-2 focus:ring-white sm:mt-0 sm:w-auto"
                >
                    Subscribe
                    <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                </button>
            </form>
        </div>
    </section>
</div>
