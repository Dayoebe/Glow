<div class="min-h-screen bg-glow-ivory text-slate-950">
    @normalizeArray($featuredHero)

    @php
        $hasActiveFilters = filled($searchQuery) || $selectedCategory !== 'all' || filled($tag) || $sortBy !== 'latest';
        $breakingStory = $breakingNews->first();
    @endphp

    <header class="border-b border-white/10 bg-glow-midnight text-white">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-12">
            <div class="flex flex-col gap-7 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <div class="mb-4 flex items-center gap-3 text-xs font-bold uppercase tracking-[0.22em] text-orange-400">
                        <span class="h-px w-8 bg-orange-500"></span>
                        Glow FM Newsroom
                    </div>
                    <h1 class="font-editorial text-4xl font-bold tracking-tight sm:text-5xl">News &amp; Updates</h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-slate-300 sm:text-lg">
                        The latest stories from Ondo State, across Nigeria, and the conversations shaping our community.
                    </p>
                </div>

                <div class="flex items-center gap-3 border-l border-white/15 pl-4 text-sm text-slate-300">
                    <i class="far fa-calendar text-orange-400"></i>
                    <span>{{ now()->format('l, F j, Y') }}</span>
                </div>
            </div>

            @if($breakingStory)
                <a href="{{ route('news.show', $breakingStory->slug) }}"
                   class="mt-8 flex items-center gap-3 border-t border-white/10 pt-5 text-sm transition hover:text-orange-300">
                    <span class="shrink-0 bg-red-600 px-2.5 py-1 text-[11px] font-black uppercase tracking-[0.16em] text-white">
                        Breaking
                    </span>
                    <span class="line-clamp-1 font-semibold">{{ $breakingStory->title }}</span>
                    <i class="fas fa-arrow-right ml-auto text-xs text-orange-400"></i>
                </a>
            @endif
        </div>
    </header>

    <section class="border-b border-slate-200 bg-white" aria-label="News filters">
        <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <nav class="-mx-1 flex gap-1 overflow-x-auto px-1 pb-1 lg:pb-0" aria-label="News categories">
                    @foreach($categories as $category)
                        @continueIfNotArray($category)
                        <button type="button"
                                wire:click="$set('selectedCategory', '{{ $category['slug'] }}')"
                                wire:key="news-category-{{ $category['slug'] }}"
                                class="shrink-0 border-b-2 px-3 py-2 text-sm font-semibold transition {{ $selectedCategory === $category['slug'] ? 'border-orange-500 text-[#071a33]' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-900' }}">
                            {{ $category['name'] }}
                            <span class="ml-1 text-xs font-normal text-slate-400">{{ $category['count'] }}</span>
                        </button>
                    @endforeach
                </nav>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <label class="relative block sm:w-72">
                        <span class="sr-only">Search news</span>
                        <i class="fas fa-search pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                        <input type="search"
                               wire:model.live.debounce.400ms="searchQuery"
                               placeholder="Search the newsroom"
                               class="h-11 w-full border border-slate-300 bg-white pl-10 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                    </label>

                    <label class="relative block">
                        <span class="sr-only">Sort stories</span>
                        <select wire:model.live="sortBy"
                                class="h-11 min-w-36 border border-slate-300 bg-white px-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                            <option value="latest">Latest</option>
                            <option value="popular">Most viewed</option>
                            <option value="trending">Trending</option>
                        </select>
                    </label>
                </div>
            </div>

            @if($hasActiveFilters)
                <div class="mt-4 flex flex-wrap items-center gap-3 border-t border-slate-100 pt-4 text-sm">
                    <span class="text-slate-500">
                        {{ $newsArticles->total() }} {{ \Illuminate\Support\Str::plural('result', $newsArticles->total()) }}
                        @if($tag)
                            tagged <strong class="text-slate-900">#{{ $tag }}</strong>
                        @endif
                    </span>
                    <button type="button"
                            wire:click="$set('searchQuery', ''); $set('selectedCategory', 'all'); $set('tag', ''); $set('sortBy', 'latest')"
                            class="font-bold text-orange-600 transition hover:text-orange-700">
                        Clear filters
                    </button>
                </div>
            @endif
        </div>
    </section>

    @if($featuredHero && !$hasActiveFilters)
        <section class="bg-white">
            <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
                <div class="mb-6 flex items-end justify-between border-b border-slate-200 pb-4">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-orange-600">Editor&rsquo;s selection</p>
                        <h2 class="font-editorial mt-1 text-2xl font-bold tracking-tight text-[#071a33]">Top stories</h2>
                    </div>
                    <span class="hidden text-sm text-slate-500 sm:block">What matters now</span>
                </div>

                <div class="grid gap-7 lg:grid-cols-12">
                    <article class="group lg:col-span-8">
                        <a href="{{ route('news.show', $featuredHero['slug']) }}"
                           class="relative block aspect-[16/9] overflow-hidden bg-[#102b4e]"
                           aria-label="Read {{ $featuredHero['title'] }}">
                            <x-initials-image
                                :src="$featuredHero['featured_image'] ?? null"
                                :title="$featuredHero['title'] ?? ''"
                                imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.025]"
                                fallbackClass="bg-[#102b4e]"
                                textClass="text-5xl font-black text-white"
                                :branded="true"
                                placeholderType="Glow news"
                                :placeholderSubtitle="data_get($featuredHero, 'category.name', 'Top story')"
                                :placeholderMeta="data_get($featuredHero, 'published_at', data_get($featuredHero, 'date', ''))"
                                loading="eager"
                                fetchpriority="high"
                                width="1600"
                                height="900"
                                sizes="(min-width: 1024px) 64vw, 92vw"
                            />
                            <span class="absolute inset-0 bg-gradient-to-t from-[#071a33]/80 via-transparent to-transparent"></span>
                            <span class="absolute bottom-5 left-5 bg-orange-500 px-3 py-1.5 text-xs font-black uppercase tracking-[0.14em] text-[#071a33] sm:bottom-6 sm:left-6">
                                {{ $featuredHero['category']['name'] }}
                            </span>
                        </a>

                        <div class="pt-5">
                            <div class="mb-3 flex flex-wrap items-center gap-3 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <span>{{ \Carbon\Carbon::parse($featuredHero['published_at'])->format('M j, Y') }}</span>
                                <span class="h-1 w-1 rounded-full bg-orange-500"></span>
                                <span>{{ $featuredHero['read_time'] }}</span>
                            </div>
                            <h3 class="font-editorial max-w-4xl text-3xl font-bold leading-[1.12] tracking-tight text-[#071a33] sm:text-4xl">
                                <a href="{{ route('news.show', $featuredHero['slug']) }}"
                                   class="decoration-orange-500 decoration-2 underline-offset-4 transition hover:underline">
                                    {{ $featuredHero['title'] }}
                                </a>
                            </h3>
                            @if($featuredHero['excerpt'])
                                <p class="mt-4 max-w-3xl text-base leading-7 text-slate-600 sm:text-lg">
                                    {{ $featuredHero['excerpt'] }}
                                </p>
                            @endif
                        </div>
                    </article>

                    <div class="divide-y divide-slate-200 border-y border-slate-200 lg:col-span-4 lg:border-b-0 lg:border-t-0 lg:border-l lg:pl-7">
                        @foreach($featuredSecondary as $secondary)
                            @continueIfNotArray($secondary)
                            <article class="group py-6 first:pt-0 lg:first:pt-0">
                                <a href="{{ route('news.show', $secondary['slug']) }}"
                                   class="mb-4 block aspect-[16/9] overflow-hidden bg-[#102b4e]"
                                   aria-label="Read {{ $secondary['title'] }}">
                                    <x-initials-image
                                        :src="$secondary['featured_image'] ?? null"
                                        :title="$secondary['title'] ?? ''"
                                        imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                                        fallbackClass="bg-[#102b4e]"
                                        textClass="text-3xl font-black text-white"
                                        :branded="true"
                                        placeholderType="Glow news"
                                        :placeholderSubtitle="data_get($secondary, 'category.name', 'News')"
                                        :placeholderMeta="data_get($secondary, 'published_at', data_get($secondary, 'date', ''))"
                                        :placeholderCompact="true"
                                    />
                                </a>
                                <p class="mb-2 text-xs font-black uppercase tracking-[0.14em] text-orange-600">
                                    {{ $secondary['category']['name'] }}
                                </p>
                                <h3 class="font-editorial text-xl font-bold leading-snug text-[#071a33]">
                                    <a href="{{ route('news.show', $secondary['slug']) }}" class="transition hover:text-orange-600">
                                        {{ $secondary['title'] }}
                                    </a>
                                </h3>
                                <p class="mt-3 text-sm text-slate-500">{{ $secondary['read_time'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
        <x-ad-slot placement="news" />

        <div class="mt-8 grid gap-12 lg:grid-cols-[minmax(0,1fr)_19rem]">
            <section aria-labelledby="latest-news-heading">
                <div class="flex items-end justify-between border-b-2 border-[#071a33] pb-3">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-orange-600">
                            {{ $hasActiveFilters ? 'News search' : 'From the newsroom' }}
                        </p>
                        <h2 id="latest-news-heading" class="font-editorial mt-1 text-2xl font-bold tracking-tight text-[#071a33]">
                            @if($selectedCategory === 'all')
                                Latest news
                            @else
                                {{ data_get(collect($categories)->firstWhere('slug', $selectedCategory), 'name', 'News') }}
                            @endif
                        </h2>
                    </div>
                    <p class="hidden text-sm text-slate-500 sm:block">
                        {{ $newsArticles->total() }} {{ \Illuminate\Support\Str::plural('story', $newsArticles->total()) }}
                    </p>
                </div>

                <div wire:loading.delay class="w-full border-b border-slate-200 py-4 text-sm font-semibold text-orange-600">
                    <i class="fas fa-circle-notch mr-2 animate-spin"></i>Updating stories
                </div>

                @if(count($newsArticles) > 0)
                    <div class="divide-y divide-slate-200">
                        @foreach($newsArticles as $article)
                            @continueIfNotArray($article)
                            <article class="group grid gap-5 py-7 sm:grid-cols-[13rem_minmax(0,1fr)]">
                                <a href="{{ route('news.show', $article['slug']) }}"
                                   class="block aspect-[16/10] overflow-hidden bg-[#102b4e] sm:aspect-[4/3]"
                                   aria-label="Read {{ $article['title'] }}">
                                    <x-initials-image
                                        :src="$article['featured_image'] ?? null"
                                        :title="$article['title'] ?? ''"
                                        imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.035]"
                                        fallbackClass="bg-[#102b4e]"
                                        textClass="text-3xl font-black text-white"
                                        :branded="true"
                                        placeholderType="Glow news"
                                        :placeholderSubtitle="data_get($article, 'category.name', 'News')"
                                        :placeholderMeta="data_get($article, 'published_at', data_get($article, 'date', ''))"
                                        :placeholderCompact="true"
                                    />
                                </a>

                                <div class="flex min-w-0 flex-col">
                                    <p class="text-xs font-black uppercase tracking-[0.14em] text-orange-600">
                                        {{ $article['category']['name'] }}
                                    </p>
                                    <h3 class="font-editorial mt-2 text-2xl font-bold leading-tight text-[#071a33]">
                                        <a href="{{ route('news.show', $article['slug']) }}" class="transition hover:text-orange-600">
                                            {{ $article['title'] }}
                                        </a>
                                    </h3>
                                    @if($article['excerpt'])
                                        <p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-600 sm:text-base">
                                            {{ $article['excerpt'] }}
                                        </p>
                                    @endif
                                    <div class="mt-auto flex flex-wrap items-center gap-3 pt-4 text-xs font-medium text-slate-500">
                                        <time datetime="{{ \Carbon\Carbon::parse($article['published_at'])->toDateString() }}">
                                            {{ \Carbon\Carbon::parse($article['published_at'])->format('M j, Y') }}
                                        </time>
                                        <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                                        <span>{{ $article['read_time'] }}</span>
                                        <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                                        <span>{{ number_format($article['views']) }} views</span>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="mt-10 border-t border-slate-200 pt-8">
                        {{ $newsArticles->links() }}
                    </div>
                @else
                    <div class="border-b border-slate-200 py-20 text-center">
                        <span class="mx-auto flex h-14 w-14 items-center justify-center bg-slate-100 text-xl text-slate-400">
                            <i class="fas fa-search"></i>
                        </span>
                        <h3 class="mt-5 text-xl font-black text-[#071a33]">No stories matched your search</h3>
                        <p class="mt-2 text-slate-500">Try another term or reset the newsroom filters.</p>
                        <button type="button"
                                wire:click="$set('searchQuery', ''); $set('selectedCategory', 'all'); $set('tag', ''); $set('sortBy', 'latest')"
                                class="mt-6 border border-[#071a33] px-5 py-2.5 text-sm font-bold text-[#071a33] transition hover:bg-[#071a33] hover:text-white">
                            Reset filters
                        </button>
                    </div>
                @endif
            </section>

            @if($trendingNews->count() > 0)
                <aside class="hidden lg:block" aria-labelledby="trending-news-heading">
                    <div class="sticky top-24">
                        <div class="border-b-2 border-orange-500 pb-3">
                            <p class="text-xs font-black uppercase tracking-[0.2em] text-orange-600">Most read</p>
                            <h2 id="trending-news-heading" class="font-editorial mt-1 text-xl font-bold text-[#071a33]">Trending now</h2>
                        </div>

                        <ol class="divide-y divide-slate-200">
                            @foreach($trendingNews as $index => $trending)
                                <li class="grid grid-cols-[2rem_minmax(0,1fr)] gap-3 py-5">
                                    <span class="text-2xl font-black leading-none text-slate-300">
                                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <div>
                                        <a href="{{ route('news.show', $trending->slug) }}"
                                           class="font-bold leading-snug text-[#071a33] transition hover:text-orange-600">
                                            {{ $trending->title }}
                                        </a>
                                        <p class="mt-2 text-xs text-slate-500">
                                            {{ number_format($trending->views) }} views
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </aside>
            @endif
        </div>
    </main>
</div>
