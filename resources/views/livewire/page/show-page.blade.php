<div class="bg-[#faf8f4] text-slate-950">
    <section class="relative overflow-hidden bg-[#171742] text-white">
        <div class="absolute inset-y-0 right-0 hidden w-1/2 bg-[radial-gradient(circle_at_center,rgba(255,99,31,0.22),transparent_68%)] lg:block"></div>
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 sm:py-18 lg:grid-cols-[minmax(0,1fr)_24rem] lg:items-center lg:px-8 lg:py-20">
            <div class="relative z-10">
                <p class="text-xs font-bold uppercase tracking-[0.28em] text-orange-400">Glow FM programmes</p>
                <h1 class="mt-4 max-w-3xl text-4xl font-black leading-[0.98] tracking-[-0.035em] sm:text-6xl">
                    Voices and shows that move Akure.
                </h1>
                <p class="mt-5 max-w-2xl text-base leading-7 text-slate-300 sm:text-lg">
                    Find your favourite presenters, discover new programmes and see when each show is next on 99.1 FM.
                </p>
                <div class="mt-7 flex flex-wrap gap-3">
                    <button type="button" @click="$store.radio.start()"
                        class="inline-flex items-center gap-2 rounded-xl bg-orange-500 px-5 py-3 text-sm font-bold text-white transition hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-300">
                        <i class="fas fa-play text-xs"></i>
                        Listen live
                    </button>
                    <a href="{{ route('schedule') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-white/20 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/10">
                        Full schedule
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

            <aside wire:poll.visible.60s="refreshSchedule"
                class="relative z-10 overflow-hidden rounded-2xl border border-white/12 bg-white/[0.07]">
                <div class="border-b border-white/10 px-5 py-4">
                    <p class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.22em] text-orange-300">
                        <span class="h-2 w-2 rounded-full bg-red-500"></span>
                        On air now
                    </p>
                </div>
                <div class="p-5">
                    @if($currentSlot)
                        <p class="text-2xl font-black leading-tight">{{ $currentSlot->show?->title ?? 'Glow FM Live' }}</p>
                        <p class="mt-2 text-sm text-slate-300">{{ $currentSlot->oap?->name ?? 'Glow FM' }}</p>
                        <p class="mt-4 text-sm font-semibold text-orange-300">{{ $currentSlot->time_range }} WAT</p>
                    @else
                        <p class="text-2xl font-black leading-tight">Glow FM Live</p>
                        <p class="mt-2 text-sm text-slate-300">Broadcasting from Akure on 99.1 FM.</p>
                    @endif
                    @if($nextSlot)
                        <div class="mt-5 border-t border-white/10 pt-4">
                            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Up next</p>
                            <div class="mt-2 flex items-end justify-between gap-4">
                                <div>
                                    <p class="font-bold">{{ $nextSlot->show?->title ?? 'Programme TBA' }}</p>
                                    <p class="mt-1 text-xs text-slate-400">{{ $nextSlot->oap?->name ?? 'Host TBA' }}</p>
                                </div>
                                <span class="shrink-0 text-xs font-bold text-orange-300">{{ $nextSlot->time_range }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </aside>
        </div>
    </section>

    @if($featuredShow)
        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
            <div class="grid overflow-hidden rounded-2xl border border-slate-200 bg-white lg:grid-cols-[minmax(18rem,0.85fr)_1.15fr]">
                <a href="{{ route('shows.show', $featuredShow->slug) }}"
                    class="group relative block min-h-72 overflow-hidden bg-[#171742]"
                    aria-label="View {{ $featuredShow->title }}">
                    <x-initials-image
                        :src="$featuredShow->cover_image"
                        :title="$featuredShow->title"
                        imgClass="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                        fallbackClass="bg-[#23235b]"
                        textClass="text-5xl font-black text-white"
                        :branded="true"
                        placeholderType="Featured show"
                        :placeholderSubtitle="'With ' . ($featuredShow->primaryHost?->name ?? 'Host TBA')"
                        :placeholderMeta="$featuredShow->scheduleSlots->first() ? ucfirst($featuredShow->scheduleSlots->first()->day_of_week) . ' · ' . $featuredShow->scheduleSlots->first()->time_range : 'Schedule TBA'"
                    />
                    <span class="absolute left-4 top-4 rounded-lg bg-orange-500 px-3 py-1.5 text-[11px] font-bold uppercase tracking-[0.18em] text-white">
                        Featured
                    </span>
                </a>
                <div class="flex flex-col justify-center p-6 sm:p-8 lg:p-10">
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-orange-600">
                        {{ $featuredShow->category?->name ?? 'Glow FM show' }}
                    </p>
                    <h2 class="mt-3 text-3xl font-black tracking-[-0.025em] text-[#171742] sm:text-4xl">
                        <a href="{{ route('shows.show', $featuredShow->slug) }}" class="hover:text-orange-600">
                            {{ $featuredShow->title }}
                        </a>
                    </h2>
                    <p class="mt-4 line-clamp-3 max-w-2xl leading-7 text-slate-600">{{ $featuredShow->description }}</p>
                    <div class="mt-5 flex flex-wrap gap-x-5 gap-y-2 text-sm text-slate-500">
                        <span><i class="fas fa-user mr-2 text-orange-500"></i>{{ $featuredShow->primaryHost?->name ?? 'Host TBA' }}</span>
                        @if($featuredShow->scheduleSlots->first())
                            <span><i class="fas fa-clock mr-2 text-orange-500"></i>{{ ucfirst($featuredShow->scheduleSlots->first()->day_of_week) }} · {{ $featuredShow->scheduleSlots->first()->time_range }}</span>
                        @endif
                    </div>
                    <a href="{{ route('shows.show', $featuredShow->slug) }}"
                        class="mt-7 inline-flex w-fit items-center gap-2 text-sm font-bold text-orange-600 hover:text-orange-700">
                        Explore programme
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <section class="border-y border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <x-ad-slot placement="shows" />
            <div class="grid gap-4 lg:grid-cols-[minmax(16rem,1fr)_auto] lg:items-center">
                <label class="relative block">
                    <span class="sr-only">Search programmes</span>
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                    <input type="search" wire:model.live.debounce.400ms="searchQuery"
                        placeholder="Search shows or topics"
                        class="h-12 w-full rounded-xl border border-slate-300 bg-white pl-11 pr-4 text-sm outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                </label>
                <label>
                    <span class="sr-only">Sort programmes</span>
                    <select wire:model.live="sortBy"
                        class="h-12 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        <option value="featured">Featured</option>
                        <option value="popular">Popular</option>
                        <option value="latest">Newest</option>
                        <option value="title_asc">A–Z</option>
                    </select>
                </label>
            </div>
            <div class="mt-4 flex gap-2 overflow-x-auto pb-1">
                @foreach($categories as $category)
                    @continueIfNotArray($category)
                    <button type="button" wire:click="$set('selectedCategory', '{{ $category['slug'] }}')"
                        class="shrink-0 rounded-lg border px-3.5 py-2 text-sm font-semibold transition
                            {{ $selectedCategory === $category['slug']
                                ? 'border-[#171742] bg-[#171742] text-white'
                                : 'border-slate-200 bg-white text-slate-600 hover:border-orange-300 hover:text-orange-600' }}">
                        {{ $category['name'] }}
                        <span class="ml-1 text-xs opacity-60">{{ $category['count'] }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
        <div class="mb-7 flex items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-orange-600">Programme guide</p>
                <h2 class="mt-2 text-3xl font-black tracking-[-0.025em] text-[#171742]">
                    {{ $selectedCategory === 'all' ? 'All shows' : data_get(collect($categories)->firstWhere('slug', $selectedCategory), 'name', 'Shows') }}
                </h2>
            </div>
            <p class="text-sm text-slate-500">{{ number_format($shows->total()) }} programmes</p>
        </div>

        <div wire:loading.delay class="mb-5 text-sm font-semibold text-orange-600">Updating programmes…</div>

        @if($shows->count() > 0)
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($shows as $show)
                    @php($nextAirtime = $show->scheduleSlots->first())
                    <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:-translate-y-0.5 hover:border-orange-200 hover:shadow-lg hover:shadow-slate-200/60">
                        <a href="{{ route('shows.show', $show->slug) }}"
                            class="relative block aspect-[4/3] overflow-hidden bg-[#171742]"
                            aria-label="View {{ $show->title }}">
                            <x-initials-image
                                :src="$show->cover_image"
                                :title="$show->title"
                                imgClass="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                                fallbackClass="bg-[#23235b]"
                                textClass="text-4xl font-black text-white"
                                :branded="true"
                                placeholderType="Radio show"
                                :placeholderSubtitle="'With ' . ($show->primaryHost?->name ?? 'Host TBA')"
                                :placeholderMeta="$nextAirtime ? ucfirst($nextAirtime->day_of_week) . ' · ' . $nextAirtime->time_range : 'Schedule TBA'"
                            />
                        </a>
                        <div class="p-5">
                            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-orange-600">
                                {{ $show->category?->name ?? 'Programme' }}
                            </p>
                            <h3 class="mt-2 text-xl font-black leading-tight text-[#171742]">
                                <a href="{{ route('shows.show', $show->slug) }}" class="transition hover:text-orange-600">
                                    {{ $show->title }}
                                </a>
                            </h3>
                            <p class="mt-2 text-sm font-medium text-slate-500">{{ $show->primaryHost?->name ?? 'Host TBA' }}</p>
                            <p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-600">{{ $show->description }}</p>
                            <div class="mt-5 flex items-center justify-between gap-3 border-t border-slate-100 pt-4">
                                <span class="text-xs font-semibold text-slate-500">
                                    @if($nextAirtime)
                                        {{ ucfirst($nextAirtime->day_of_week) }} · {{ $nextAirtime->time_range }}
                                    @else
                                        Schedule TBA
                                    @endif
                                </span>
                                <span class="text-xs font-bold text-orange-600">View show <i class="fas fa-arrow-right ml-1"></i></span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-10">{{ $shows->links() }}</div>
        @else
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center">
                <i class="fas fa-microphone-lines text-3xl text-slate-300"></i>
                <h3 class="mt-4 text-xl font-black text-[#171742]">No programmes found</h3>
                <p class="mt-2 text-sm text-slate-500">Try another category or a broader search.</p>
                <button type="button"
                    wire:click="$set('selectedCategory', 'all'); $set('searchQuery', ''); $set('sortBy', 'featured')"
                    class="mt-5 text-sm font-bold text-orange-600 hover:text-orange-700">
                    Clear filters
                </button>
            </div>
        @endif
    </section>
</div>
