<div class="min-h-screen bg-glow-ivory text-glow-ink">
    @normalizeArray($featuredEvent)

    @php
        $hasActiveFilters = filled($searchQuery) || $selectedCategory !== 'all' || $sortBy !== 'upcoming';
        $sidebarUpcoming = $upcomingEvents
            ->reject(fn ($upcoming) => $featuredEvent && $upcoming->id === $featuredEvent['id'])
            ->values();
    @endphp

    <header class="border-b border-white/10 bg-glow-midnight text-white">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-12">
            <div class="max-w-3xl">
                <div class="mb-4 flex items-center gap-3 text-xs font-black uppercase tracking-[0.22em] text-glow-amber">
                    <span class="h-px w-8 bg-glow-orange"></span>
                    Meet. Listen. Belong.
                </div>
                <h1 class="font-editorial text-4xl font-bold tracking-tight sm:text-5xl">Events &amp; Experiences</h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-slate-300 sm:text-lg">
                    Live broadcasts, community gatherings, and memorable experiences from Glow FM.
                </p>
            </div>
        </div>
    </header>

    <section class="border-b border-slate-200 bg-white" aria-label="Event filters">
        <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <nav class="-mx-1 flex gap-1 overflow-x-auto px-1 pb-1 lg:pb-0" aria-label="Event categories">
                    @foreach($categories as $category)
                        @continueIfNotArray($category)
                        <a href="{{ route('events.index', $category['slug'] === 'all' ? [] : ['selectedCategory' => $category['slug']]) }}"
                                wire:click.prevent="$set('selectedCategory', '{{ $category['slug'] }}')"
                                wire:key="event-category-{{ $category['slug'] }}"
                                class="shrink-0 border-b-2 px-3 py-2 text-sm font-bold transition {{ $selectedCategory === $category['slug'] ? 'border-glow-orange text-glow-ink' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-glow-ink' }}">
                            {{ $category['name'] }}
                            <span class="ml-1 text-xs font-normal text-slate-400">{{ $category['count'] }}</span>
                        </a>
                    @endforeach
                </nav>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <label class="relative block sm:w-72">
                        <span class="sr-only">Search events</span>
                        <i class="fas fa-search pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400" aria-hidden="true"></i>
                        <input type="search"
                               wire:model.live.debounce.400ms="searchQuery"
                               placeholder="Search events"
                               class="h-11 w-full border border-slate-300 bg-white pl-10 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-glow-orange focus:ring-2 focus:ring-orange-100">
                    </label>

                    <label>
                        <span class="sr-only">Sort events</span>
                        <select wire:model.live="sortBy"
                                class="h-11 min-w-36 border border-slate-300 bg-white px-3 text-sm font-bold text-slate-700 outline-none transition focus:border-glow-orange focus:ring-2 focus:ring-orange-100">
                            <option value="upcoming">Upcoming</option>
                            <option value="latest">Latest dates</option>
                            <option value="past">Past events</option>
                            <option value="popular">Most viewed</option>
                        </select>
                    </label>
                </div>
            </div>

            @if($hasActiveFilters)
                <div class="mt-4 flex flex-wrap items-center gap-3 border-t border-slate-100 pt-4 text-sm">
                    <span class="text-slate-500">{{ $events->total() }} {{ \Illuminate\Support\Str::plural('event', $events->total()) }}</span>
                    <button type="button"
                            wire:click="$set('searchQuery', ''); $set('selectedCategory', 'all'); $set('sortBy', 'upcoming')"
                            class="font-black text-glow-orange transition hover:text-glow-coral">
                        Clear filters
                    </button>
                </div>
            @endif
        </div>
    </section>

    @if($featuredEvent && !$hasActiveFilters)
        <section class="bg-white">
            <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
                <div class="grid gap-8 lg:grid-cols-12 lg:items-center">
                    <article class="group lg:col-span-7">
                        <a href="{{ route('events.show', $featuredEvent['slug']) }}"
                           class="relative block aspect-[16/10] overflow-hidden bg-glow-navy"
                           aria-label="View {{ $featuredEvent['title'] }}">
                            <x-initials-image
                                :src="$featuredEvent['featured_image'] ?? null"
                                :title="$featuredEvent['title'] ?? ''"
                                imgClass="h-full w-full object-cover transition duration-700 group-hover:scale-[1.025]"
                                fallbackClass="bg-glow-navy"
                                textClass="text-5xl font-black text-white"
                            />
                            <span class="absolute bottom-5 left-5 bg-glow-orange px-3 py-1.5 text-xs font-black uppercase tracking-[0.14em] text-white">
                                Featured event
                            </span>
                        </a>
                    </article>

                    <div class="lg:col-span-5 lg:pl-4">
                        <p class="public-kicker">{{ $featuredEvent['category']['name'] }}</p>
                        <h2 class="font-editorial mt-3 text-3xl font-bold leading-[1.12] text-glow-ink sm:text-4xl">
                            <a href="{{ route('events.show', $featuredEvent['slug']) }}"
                               class="decoration-glow-orange decoration-2 underline-offset-4 transition hover:underline">
                                {{ $featuredEvent['title'] }}
                            </a>
                        </h2>
                        @if($featuredEvent['excerpt'])
                            <p class="mt-5 text-base leading-7 text-slate-600 sm:text-lg">{{ $featuredEvent['excerpt'] }}</p>
                        @endif
                        <dl class="mt-6 grid gap-3 border-y border-slate-200 py-5 text-sm sm:grid-cols-2">
                            <div>
                                <dt class="text-xs font-black uppercase tracking-[0.12em] text-slate-400">Date</dt>
                                <dd class="mt-1 font-bold text-glow-ink">{{ $featuredEvent['formatted_date'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-black uppercase tracking-[0.12em] text-slate-400">Time</dt>
                                <dd class="mt-1 font-bold text-glow-ink">{{ $featuredEvent['formatted_time'] }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-xs font-black uppercase tracking-[0.12em] text-slate-400">Venue</dt>
                                <dd class="mt-1 font-bold text-glow-ink">{{ $featuredEvent['venue_name'] ?? 'Venue to be announced' }}</dd>
                            </div>
                        </dl>
                        <a href="{{ route('events.show', $featuredEvent['slug']) }}"
                           class="mt-6 inline-flex items-center gap-2 border-b-2 border-glow-orange pb-1 text-sm font-black text-glow-ink transition hover:text-glow-orange">
                            View event <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
        <x-ad-slot placement="events" />

        <div class="mt-8 grid gap-12 lg:grid-cols-[minmax(0,1fr)_19rem]">
            <section aria-labelledby="events-feed-heading">
                <div class="flex items-end justify-between border-b-2 border-glow-ink pb-3">
                    <div>
                        <p class="public-kicker">{{ $hasActiveFilters ? 'Event search' : 'Plan ahead' }}</p>
                        <h2 id="events-feed-heading" class="font-editorial mt-1 text-2xl font-bold text-glow-ink">
                            @if($selectedCategory === 'all')
                                {{ $sortBy === 'past' ? 'Past events' : 'Upcoming events' }}
                            @else
                                {{ data_get(collect($categories)->firstWhere('slug', $selectedCategory), 'name', 'Events') }}
                            @endif
                        </h2>
                    </div>
                    <span class="hidden text-sm text-slate-500 sm:block">
                        {{ $events->total() }} {{ \Illuminate\Support\Str::plural('event', $events->total()) }}
                    </span>
                </div>

                <div wire:loading.delay class="border-b border-slate-200 py-4 text-sm font-bold text-glow-orange">
                    <i class="fas fa-circle-notch mr-2 animate-spin" aria-hidden="true"></i>Updating events
                </div>

                @if($events->count() > 0)
                    <div class="grid gap-x-7 gap-y-9 pt-7 md:grid-cols-2">
                        @foreach($events as $event)
                            <article class="group border-b border-slate-200 pb-6">
                                <a href="{{ route('events.show', $event->slug) }}"
                                   class="relative block aspect-[16/10] overflow-hidden bg-glow-navy"
                                   aria-label="View {{ $event->title }}">
                                    <x-initials-image
                                        :src="$event->featured_image"
                                        :title="$event->title"
                                        imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.035]"
                                        fallbackClass="bg-glow-navy"
                                        textClass="text-3xl font-black text-white"
                                    />
                                    @if($event->start_at)
                                        <span class="absolute bottom-0 left-0 bg-glow-ink px-3 py-2 text-xs font-black uppercase tracking-[0.12em] text-white">
                                            {{ $event->start_at->format('M j') }}
                                        </span>
                                    @endif
                                </a>

                                <p class="mt-4 text-xs font-black uppercase tracking-[0.14em] text-glow-orange">
                                    {{ $event->category?->name ?? 'Glow FM Event' }}
                                </p>
                                <h3 class="font-editorial mt-2 text-2xl font-bold leading-tight text-glow-ink">
                                    <a href="{{ route('events.show', $event->slug) }}" class="transition hover:text-glow-orange">
                                        {{ $event->title }}
                                    </a>
                                </h3>
                                @if($event->excerpt)
                                    <p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-600">{{ $event->excerpt }}</p>
                                @endif
                                <div class="mt-4 space-y-1.5 text-sm text-slate-500">
                                    <p><i class="far fa-clock mr-2 w-4 text-glow-orange" aria-hidden="true"></i>{{ $event->formatted_time }}</p>
                                    <p><i class="fas fa-location-dot mr-2 w-4 text-glow-orange" aria-hidden="true"></i>{{ $event->venue_name ?? 'Venue to be announced' }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="mt-10 border-t border-slate-200 pt-8">
                        {{ $events->links() }}
                    </div>
                @else
                    <div class="border-b border-slate-200 py-20 text-center">
                        <span class="mx-auto flex h-14 w-14 items-center justify-center bg-slate-100 text-xl text-slate-400">
                            <i class="far fa-calendar-xmark" aria-hidden="true"></i>
                        </span>
                        <h3 class="font-editorial mt-5 text-2xl font-bold text-glow-ink">No events matched</h3>
                        <p class="mt-2 text-slate-500">Try another phrase, category, or date view.</p>
                        <button type="button"
                                wire:click="$set('searchQuery', ''); $set('selectedCategory', 'all'); $set('sortBy', 'upcoming')"
                                class="mt-6 border border-glow-ink px-5 py-2.5 text-sm font-black text-glow-ink transition hover:bg-glow-ink hover:text-white">
                            Reset filters
                        </button>
                    </div>
                @endif
            </section>

            @if($sidebarUpcoming->isNotEmpty())
                <aside class="hidden lg:block" aria-labelledby="event-calendar-heading">
                    <div class="sticky top-32">
                        <div class="border-b-2 border-glow-orange pb-3">
                            <p class="public-kicker">Next on the calendar</p>
                            <h2 id="event-calendar-heading" class="font-editorial mt-1 text-xl font-bold text-glow-ink">Coming soon</h2>
                        </div>
                        <div class="divide-y divide-slate-200">
                            @foreach($sidebarUpcoming as $upcoming)
                                <a href="{{ route('events.show', $upcoming->slug) }}"
                                   class="group grid grid-cols-[3.25rem_minmax(0,1fr)] gap-3 py-5">
                                    <time datetime="{{ $upcoming->start_at?->toDateString() }}"
                                          class="flex h-12 flex-col items-center justify-center bg-glow-ink text-center text-white">
                                        <span class="text-[9px] font-black uppercase tracking-wider text-glow-amber">{{ $upcoming->start_at?->format('M') }}</span>
                                        <span class="text-lg font-black leading-none">{{ $upcoming->start_at?->format('d') }}</span>
                                    </time>
                                    <div>
                                        <h3 class="text-sm font-black leading-snug text-glow-ink transition group-hover:text-glow-orange">
                                            {{ $upcoming->title }}
                                        </h3>
                                        <p class="mt-2 line-clamp-1 text-xs text-slate-500">{{ $upcoming->venue_name ?? 'Venue TBA' }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </aside>
            @endif
        </div>
    </main>
</div>
