<div class="min-h-screen bg-glow-ivory text-glow-ink">
    @php
        $hasActiveFilters = filled($searchQuery) || $selectedCategory !== 'all' || $sortBy !== 'latest';
        $sidebarTrending = $trendingPosts
            ->reject(fn ($trending) => $featuredPost && $trending->id === $featuredPost->id)
            ->values();
    @endphp

    <header class="border-b border-white/10 bg-glow-midnight text-white">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-12">
            <div class="max-w-3xl">
                <div class="mb-4 flex items-center gap-3 text-xs font-black uppercase tracking-[0.22em] text-glow-amber">
                    <span class="h-px w-8 bg-glow-orange"></span>
                    Ideas from Glow FM
                </div>
                <h1 class="font-editorial text-4xl font-bold tracking-tight sm:text-5xl">Stories &amp; Perspectives</h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-slate-300 sm:text-lg">
                    Thoughtful reads on music, culture, broadcasting, and the people shaping our community.
                </p>
            </div>
        </div>
    </header>

    <section class="border-b border-slate-200 bg-white" aria-label="Blog filters">
        <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <nav class="-mx-1 flex gap-1 overflow-x-auto px-1 pb-1 lg:pb-0" aria-label="Blog categories">
                    <button type="button"
                            wire:click="$set('selectedCategory', 'all')"
                            class="shrink-0 border-b-2 px-3 py-2 text-sm font-bold transition {{ $selectedCategory === 'all' ? 'border-glow-orange text-glow-ink' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-glow-ink' }}">
                        All articles
                    </button>
                    @foreach($categories as $category)
                        <button type="button"
                                wire:click="$set('selectedCategory', '{{ $category->slug }}')"
                                wire:key="blog-category-{{ $category->slug }}"
                                class="shrink-0 border-b-2 px-3 py-2 text-sm font-bold transition {{ $selectedCategory === $category->slug ? 'border-glow-orange text-glow-ink' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-glow-ink' }}">
                            {{ $category->name }}
                            <span class="ml-1 text-xs font-normal text-slate-400">{{ $category->posts_count }}</span>
                        </button>
                    @endforeach
                </nav>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <label class="relative block sm:w-72">
                        <span class="sr-only">Search articles</span>
                        <i class="fas fa-search pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400" aria-hidden="true"></i>
                        <input type="search"
                               wire:model.live.debounce.400ms="searchQuery"
                               placeholder="Search articles"
                               class="h-11 w-full border border-slate-300 bg-white pl-10 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-glow-orange focus:ring-2 focus:ring-orange-100">
                    </label>

                    <label>
                        <span class="sr-only">Sort articles</span>
                        <select wire:model.live="sortBy"
                                class="h-11 min-w-36 border border-slate-300 bg-white px-3 text-sm font-bold text-slate-700 outline-none transition focus:border-glow-orange focus:ring-2 focus:ring-orange-100">
                            <option value="latest">Latest</option>
                            <option value="popular">Most viewed</option>
                            <option value="trending">Trending</option>
                        </select>
                    </label>
                </div>
            </div>

            @if($hasActiveFilters)
                <div class="mt-4 flex flex-wrap items-center gap-3 border-t border-slate-100 pt-4 text-sm">
                    <span class="text-slate-500">{{ $posts->total() }} {{ \Illuminate\Support\Str::plural('article', $posts->total()) }}</span>
                    <button type="button"
                            wire:click="$set('searchQuery', ''); $set('selectedCategory', 'all'); $set('sortBy', 'latest')"
                            class="font-black text-glow-orange transition hover:text-glow-coral">
                        Clear filters
                    </button>
                </div>
            @endif
        </div>
    </section>

    @if($featuredPost && !$hasActiveFilters)
        <section class="bg-white">
            <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
                <div class="grid gap-8 lg:grid-cols-12 lg:items-center">
                    <article class="group lg:col-span-7">
                        <a href="{{ route('blog.show', $featuredPost->slug) }}"
                           class="relative block aspect-[16/10] overflow-hidden bg-glow-navy"
                           aria-label="Read {{ $featuredPost->title }}">
                            <x-initials-image
                                :src="$featuredPost->featured_image"
                                :title="$featuredPost->title"
                                imgClass="h-full w-full object-cover transition duration-700 group-hover:scale-[1.025]"
                                fallbackClass="bg-glow-navy"
                                textClass="text-5xl font-black text-white"
                                :branded="true"
                                placeholderType="Glow blog"
                                :placeholderSubtitle="$featuredPost->category?->name ?? 'Featured read'"
                                :placeholderMeta="$featuredPost->published_at?->format('M j, Y')"
                            />
                        </a>
                    </article>

                    <div class="lg:col-span-5 lg:pl-4">
                        <p class="public-kicker">Featured read</p>
                        <p class="mt-4 text-sm font-black uppercase tracking-[0.14em] text-glow-orange">
                            {{ $featuredPost->category?->name ?? 'Glow FM Blog' }}
                        </p>
                        <h2 class="font-editorial mt-3 text-3xl font-bold leading-[1.12] text-glow-ink sm:text-4xl">
                            <a href="{{ route('blog.show', $featuredPost->slug) }}"
                               class="decoration-glow-orange decoration-2 underline-offset-4 transition hover:underline">
                                {{ $featuredPost->title }}
                            </a>
                        </h2>
                        @if($featuredPost->excerpt)
                            <p class="mt-5 text-base leading-7 text-slate-600 sm:text-lg">{{ $featuredPost->excerpt }}</p>
                        @endif
                        <div class="mt-6 flex flex-wrap items-center gap-3 text-sm text-slate-500">
                            @if($featuredPost->author)
                                <a href="{{ route('staff.profile', ['type' => 'user', 'identifier' => $featuredPost->author->id]) }}"
                                   class="font-bold text-glow-ink transition hover:text-glow-orange">
                                    {{ $featuredPost->author->name }}
                                </a>
                                <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                            @endif
                            <span>{{ $featuredPost->published_at?->format('M j, Y') ?? 'Recently published' }}</span>
                            <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                            <span>{{ $featuredPost->read_time }}</span>
                        </div>
                        <a href="{{ route('blog.show', $featuredPost->slug) }}"
                           class="mt-7 inline-flex items-center gap-2 border-b-2 border-glow-orange pb-1 text-sm font-black text-glow-ink transition hover:text-glow-orange">
                            Read article <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
        <x-ad-slot placement="blog" />

        <div class="mt-8 grid gap-12 lg:grid-cols-[minmax(0,1fr)_19rem]">
            <section aria-labelledby="blog-feed-heading">
                <div class="flex items-end justify-between border-b-2 border-glow-ink pb-3">
                    <div>
                        <p class="public-kicker">{{ $hasActiveFilters ? 'Article search' : 'Latest thinking' }}</p>
                        <h2 id="blog-feed-heading" class="font-editorial mt-1 text-2xl font-bold text-glow-ink">
                            @if($selectedCategory === 'all')
                                Recent articles
                            @else
                                {{ $categories->firstWhere('slug', $selectedCategory)?->name ?? 'Articles' }}
                            @endif
                        </h2>
                    </div>
                    <span class="hidden text-sm text-slate-500 sm:block">
                        {{ $posts->total() }} {{ \Illuminate\Support\Str::plural('article', $posts->total()) }}
                    </span>
                </div>

                <div wire:loading.delay class="border-b border-slate-200 py-4 text-sm font-bold text-glow-orange">
                    <i class="fas fa-circle-notch mr-2 animate-spin" aria-hidden="true"></i>Updating articles
                </div>

                @if($posts->count() > 0)
                    <div class="divide-y divide-slate-200">
                        @foreach($posts as $post)
                            <article class="group grid gap-5 py-7 sm:grid-cols-[13rem_minmax(0,1fr)]">
                                <a href="{{ route('blog.show', $post->slug) }}"
                                   class="block aspect-[16/10] overflow-hidden bg-glow-navy sm:aspect-[4/3]"
                                   aria-label="Read {{ $post->title }}">
                                    <x-initials-image
                                        :src="$post->featured_image"
                                        :title="$post->title"
                                        imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.035]"
                                        fallbackClass="bg-glow-navy"
                                        textClass="text-3xl font-black text-white"
                                        :branded="true"
                                        placeholderType="Glow blog"
                                        :placeholderSubtitle="$post->category?->name ?? 'Article'"
                                        :placeholderMeta="$post->published_at?->format('M j, Y')"
                                        :placeholderCompact="true"
                                    />
                                </a>

                                <div class="flex min-w-0 flex-col">
                                    <div class="flex flex-wrap items-center gap-2 text-xs font-black uppercase tracking-[0.13em] text-glow-orange">
                                        <span>{{ $post->category?->name ?? 'Blog' }}</span>
                                        @if($post->series)
                                            <span class="text-slate-400">/ {{ $post->series }}</span>
                                        @endif
                                    </div>
                                    <h3 class="font-editorial mt-2 text-2xl font-bold leading-tight text-glow-ink">
                                        <a href="{{ route('blog.show', $post->slug) }}" class="transition hover:text-glow-orange">
                                            {{ $post->title }}
                                        </a>
                                    </h3>
                                    @if($post->excerpt)
                                        <p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-600 sm:text-base">{{ $post->excerpt }}</p>
                                    @endif
                                    <div class="mt-auto flex flex-wrap items-center gap-3 pt-4 text-xs font-medium text-slate-500">
                                        @if($post->author)
                                            <a href="{{ route('staff.profile', ['type' => 'user', 'identifier' => $post->author->id]) }}"
                                               class="font-bold text-glow-ink transition hover:text-glow-orange">
                                                {{ $post->author->name }}
                                            </a>
                                            <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                                        @endif
                                        <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('M j, Y') }}</time>
                                        <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                                        <span>{{ $post->read_time }}</span>
                                        <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                                        <span>{{ number_format($post->views) }} views</span>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="mt-10 border-t border-slate-200 pt-8">
                        {{ $posts->links() }}
                    </div>
                @else
                    <div class="border-b border-slate-200 py-20 text-center">
                        <span class="mx-auto flex h-14 w-14 items-center justify-center bg-slate-100 text-xl text-slate-400">
                            <i class="fas fa-search" aria-hidden="true"></i>
                        </span>
                        <h3 class="font-editorial mt-5 text-2xl font-bold text-glow-ink">No articles matched</h3>
                        <p class="mt-2 text-slate-500">Try another phrase or reset the filters.</p>
                        <button type="button"
                                wire:click="$set('searchQuery', ''); $set('selectedCategory', 'all'); $set('sortBy', 'latest')"
                                class="mt-6 border border-glow-ink px-5 py-2.5 text-sm font-black text-glow-ink transition hover:bg-glow-ink hover:text-white">
                            Reset filters
                        </button>
                    </div>
                @endif
            </section>

            @if($sidebarTrending->isNotEmpty())
                <aside class="hidden lg:block" aria-labelledby="trending-blog-heading">
                    <div class="sticky top-32">
                        <div class="border-b-2 border-glow-orange pb-3">
                            <p class="public-kicker">Reader interest</p>
                            <h2 id="trending-blog-heading" class="font-editorial mt-1 text-xl font-bold text-glow-ink">Trending reads</h2>
                        </div>
                        <ol class="divide-y divide-slate-200">
                            @foreach($sidebarTrending as $index => $trending)
                                <li class="grid grid-cols-[2rem_minmax(0,1fr)] gap-3 py-5">
                                    <span class="text-2xl font-black leading-none text-slate-300">
                                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <div>
                                        <a href="{{ route('blog.show', $trending->slug) }}"
                                           class="font-bold leading-snug text-glow-ink transition hover:text-glow-orange">
                                            {{ $trending->title }}
                                        </a>
                                        <p class="mt-2 text-xs text-slate-500">{{ number_format($trending->views) }} views</p>
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
