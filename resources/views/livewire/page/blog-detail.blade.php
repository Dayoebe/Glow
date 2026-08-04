<div
    x-data="{
        qualifiedViewRecorded: false,
        qualifiedViewTimer: null,
        qualifiedViewScrollHandler: null,
        init() {
            this.qualifiedViewScrollHandler = () => this.checkQualifiedViewScroll();
            this.qualifiedViewTimer = window.setTimeout(() => this.recordQualifiedView(), 10000);
            window.addEventListener('scroll', this.qualifiedViewScrollHandler, { passive: true });
            this.$nextTick(() => this.checkQualifiedViewScroll());
        },
        destroy() {
            this.stopQualifiedViewTracking();
        },
        checkQualifiedViewScroll() {
            const documentElement = document.documentElement;
            const scrollTop = window.scrollY || documentElement.scrollTop;
            const scrollHeight = documentElement.scrollHeight - window.innerHeight;

            if (scrollHeight > 0 && (scrollTop / scrollHeight) * 100 >= 25) {
                this.recordQualifiedView();
            }
        },
        recordQualifiedView() {
            if (this.qualifiedViewRecorded || !this.$root.isConnected) return;

            this.qualifiedViewRecorded = true;
            this.stopQualifiedViewTracking();
            $wire.recordQualifiedView();
        },
        stopQualifiedViewTracking() {
            if (this.qualifiedViewTimer !== null) {
                window.clearTimeout(this.qualifiedViewTimer);
                this.qualifiedViewTimer = null;
            }

            if (this.qualifiedViewScrollHandler !== null) {
                window.removeEventListener('scroll', this.qualifiedViewScrollHandler);
                this.qualifiedViewScrollHandler = null;
            }
        }
    }"
    data-qualified-view-tracker
    class="min-h-screen bg-glow-ivory text-glow-ink"
>
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
            <nav class="mb-8 flex flex-wrap items-center gap-2 text-xs font-bold uppercase tracking-[0.14em] text-slate-500"
                 aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="transition hover:text-glow-orange">Home</a>
                <i class="fas fa-chevron-right text-[8px] text-slate-300" aria-hidden="true"></i>
                <a href="{{ route('blog.index') }}" class="transition hover:text-glow-orange">Blog</a>
                <i class="fas fa-chevron-right text-[8px] text-slate-300" aria-hidden="true"></i>
                <a href="{{ route('blog.index', ['selectedCategory' => $post->category->slug]) }}"
                   class="text-glow-orange transition hover:text-glow-coral">
                    {{ $post->category->name }}
                </a>
            </nav>

            <div class="flex flex-wrap items-center gap-3">
                <span class="border-l-4 border-glow-orange pl-3 text-xs font-black uppercase tracking-[0.18em] text-glow-ink">
                    {{ $post->category->name }}
                </span>
                @if($post->is_featured)
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Featured</span>
                @endif
                @if($post->series)
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ $post->series }} series</span>
                @endif
                @if($post->video_url)
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        <i class="fas fa-play mr-1 text-glow-orange" aria-hidden="true"></i>Video
                    </span>
                @endif
                @if($post->audio_url)
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        <i class="fas fa-headphones mr-1 text-glow-orange" aria-hidden="true"></i>Audio
                    </span>
                @endif
            </div>

            <h1 class="font-editorial mt-6 max-w-5xl text-4xl font-bold leading-[1.06] tracking-tight text-glow-ink sm:text-5xl lg:text-6xl">
                {{ $post->title }}
            </h1>

            @if($post->excerpt)
                <p class="mt-6 max-w-3xl text-lg leading-8 text-slate-600 sm:text-xl">{{ $post->excerpt }}</p>
            @endif

            <div class="mt-8 flex flex-col gap-5 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('staff.profile', ['type' => 'user', 'identifier' => $post->author->id]) }}"
                       class="relative h-11 w-11 shrink-0 overflow-hidden bg-glow-navy"
                       aria-label="View {{ $post->author->name }}">
                        <x-initials-image
                            :src="$post->author->avatar"
                            :title="$post->author->name"
                            imgClass="h-full w-full object-cover"
                            fallbackClass="bg-glow-navy"
                            textClass="text-xs font-black text-white"
                        />
                    </a>
                    <div>
                        <a href="{{ route('staff.profile', ['type' => 'user', 'identifier' => $post->author->id]) }}"
                           class="font-black text-glow-ink transition hover:text-glow-orange">
                            {{ $post->author->name }}
                        </a>
                        <div class="mt-1 flex flex-wrap items-center gap-2 text-sm text-slate-500">
                            <time datetime="{{ $post->published_at?->toAtomString() }}">{{ $post->formatted_published_date }}</time>
                            <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                            <span>{{ $post->read_time }}</span>
                            <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                            <span>{{ number_format($post->views) }} views</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button"
                            wire:click="toggleBookmark"
                            class="inline-flex h-10 items-center gap-2 border border-slate-300 px-3.5 text-sm font-black text-slate-700 transition hover:border-glow-orange hover:text-glow-orange">
                        <i class="{{ $isBookmarked ? 'fas' : 'far' }} fa-bookmark" aria-hidden="true"></i>
                        {{ $isBookmarked ? 'Saved' : 'Save' }}
                    </button>
                    <button type="button"
                            data-copy-link="{{ route('blog.show', $post->slug) }}"
                            class="inline-flex h-10 items-center gap-2 border border-slate-300 px-3.5 text-sm font-black text-slate-700 transition hover:border-glow-orange hover:text-glow-orange">
                        <i class="fas fa-link" aria-hidden="true"></i>
                        <span data-copy-text>Copy link</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="mx-auto max-w-7xl px-4 pt-8 sm:px-6 lg:px-8 lg:pt-10">
        <figure class="aspect-[16/9] max-h-[680px] overflow-hidden bg-glow-navy">
            <x-initials-image
                :src="$post->featured_image"
                :title="$post->title"
                imgClass="h-full w-full object-cover"
                fallbackClass="bg-glow-navy"
                textClass="text-6xl font-black text-white"
                :branded="true"
                placeholderType="Glow blog"
                :placeholderSubtitle="$post->category?->name ?? 'Article'"
                :placeholderMeta="$post->published_at?->format('M j, Y')"
                loading="eager"
                fetchpriority="high"
                width="1600"
                height="900"
                sizes="(min-width: 1280px) 80rem, 100vw"
            />
        </figure>
    </div>

    <div class="mx-auto max-w-7xl px-4 pt-8 sm:px-6 lg:px-8">
        <x-ad-slot placement="blog-detail" />
    </div>

    <main class="px-4 pb-16 pt-10 sm:px-6 lg:px-8 lg:pb-24 lg:pt-14">
        <article class="mx-auto max-w-[760px]">
            @if($post->video_url)
                <div class="mb-10 aspect-video overflow-hidden bg-slate-950">
                    <iframe src="{{ $post->video_url }}"
                            title="Video for {{ $post->title }}"
                            class="h-full w-full"
                            frameborder="0"
                            loading="lazy"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                </div>
            @endif

            @if($post->audio_url)
                <section class="mb-10 border-y border-slate-200 bg-white px-5 py-5" aria-labelledby="blog-audio-heading">
                    <div class="mb-4 flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center bg-glow-orange text-white">
                            <i class="fas fa-headphones" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 id="blog-audio-heading" class="font-black text-glow-ink">Listen to this article</h2>
                            <p class="text-sm text-slate-500">Audio edition</p>
                        </div>
                    </div>
                    <audio controls preload="metadata" class="w-full">
                        <source src="{{ $post->audio_url }}" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                </section>
            @endif

            <div class="max-w-none text-[1.0625rem] leading-8 text-slate-700
                        [&_p]:mb-6
                        [&_h2]:font-editorial [&_h2]:mb-4 [&_h2]:mt-12 [&_h2]:text-3xl [&_h2]:font-bold [&_h2]:leading-tight [&_h2]:text-glow-ink
                        [&_h3]:font-editorial [&_h3]:mb-3 [&_h3]:mt-9 [&_h3]:text-2xl [&_h3]:font-bold [&_h3]:text-glow-ink
                        [&_h4]:mb-3 [&_h4]:mt-8 [&_h4]:text-xl [&_h4]:font-black [&_h4]:text-glow-ink
                        [&_a]:font-semibold [&_a]:text-glow-orange [&_a]:underline [&_a]:decoration-orange-300 [&_a]:underline-offset-4
                        [&_strong]:font-black [&_strong]:text-glow-ink
                        [&_ul]:my-6 [&_ul]:list-disc [&_ul]:space-y-2 [&_ul]:pl-6
                        [&_ol]:my-6 [&_ol]:list-decimal [&_ol]:space-y-2 [&_ol]:pl-6
                        [&_blockquote]:my-8 [&_blockquote]:border-l-4 [&_blockquote]:border-glow-orange [&_blockquote]:bg-white [&_blockquote]:px-6 [&_blockquote]:py-4 [&_blockquote]:font-editorial [&_blockquote]:text-xl [&_blockquote]:italic [&_blockquote]:text-glow-ink
                        [&_img]:my-8 [&_img]:h-auto [&_img]:w-full">
                {!! app(\App\Support\RichTextSanitizer::class)->sanitize($post->content) !!}
            </div>

            @if($post->gallery && count($post->gallery) > 0)
                <section class="mt-14 border-t border-slate-200 pt-8" aria-labelledby="blog-gallery-heading">
                    <p class="public-kicker">In pictures</p>
                    <h2 id="blog-gallery-heading" class="font-editorial mt-1 text-2xl font-bold text-glow-ink">Photo gallery</h2>
                    <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3">
                        @foreach($post->gallery as $image)
                            <div class="aspect-square overflow-hidden bg-glow-navy">
                                <x-initials-image
                                    :src="$image"
                                    :title="$post->title"
                                    imgClass="h-full w-full object-cover"
                                    fallbackClass="bg-glow-navy"
                                    textClass="text-3xl font-black text-white"
                                    :branded="true"
                                    placeholderType="Glow blog"
                                    :placeholderSubtitle="$related->category?->name ?? 'Article'"
                                    :placeholderMeta="$related->published_at?->format('M j, Y')"
                                />
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($post->tags && count($post->tags) > 0)
                <div class="mt-10 flex flex-wrap items-center gap-x-4 gap-y-2 border-t border-slate-200 pt-6">
                    <span class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Topics</span>
                    @foreach($post->tags as $tag)
                        <a href="{{ route('blog.index', ['searchQuery' => $tag]) }}"
                           class="text-sm font-bold text-glow-ink underline decoration-slate-300 underline-offset-4 transition hover:text-glow-orange hover:decoration-glow-orange">
                            #{{ $tag }}
                        </a>
                    @endforeach
                </div>
            @endif

            <section class="mt-10 border-y border-slate-200 py-7" aria-labelledby="blog-reactions-heading">
                <h2 id="blog-reactions-heading" class="text-sm font-black uppercase tracking-[0.16em] text-glow-ink">Your reaction</h2>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach(['love' => ['❤️', 'Love'], 'fire' => ['🔥', 'Hot'], 'clap' => ['👏', 'Great'], 'insightful' => ['💡', 'Useful']] as $type => $reaction)
                        <button type="button"
                                wire:click="toggleReaction('{{ $type }}')"
                                class="inline-flex h-10 items-center gap-2 border px-3 text-sm font-bold transition {{ isset($userReactions[$type]) ? 'border-glow-orange bg-orange-50 text-orange-700' : 'border-slate-300 bg-white text-slate-700 hover:border-glow-orange' }}">
                            <span aria-hidden="true">{{ $reaction[0] }}</span>
                            <span>{{ $reaction[1] }}</span>
                            <span class="text-xs text-slate-400">{{ $reactions[$type] ?? 0 }}</span>
                        </button>
                    @endforeach
                </div>

                <div class="mt-6 flex flex-wrap items-center gap-2">
                    <span class="mr-1 text-sm font-bold text-slate-500">Share</span>
                    @foreach([
                        'x' => 'fab fa-x-twitter',
                        'facebook' => 'fab fa-facebook-f',
                        'linkedin' => 'fab fa-linkedin-in',
                        'whatsapp' => 'fab fa-whatsapp',
                        'telegram' => 'fab fa-telegram',
                        'reddit' => 'fab fa-reddit-alien',
                        'email' => 'fas fa-envelope',
                    ] as $platform => $icon)
                        <button type="button"
                                wire:click="sharePost('{{ $platform }}')"
                                class="inline-flex h-10 w-10 items-center justify-center border border-slate-300 bg-white text-glow-ink transition hover:border-glow-orange hover:text-glow-orange"
                                aria-label="Share via {{ ucfirst($platform) }}">
                            <i class="{{ $icon }}" aria-hidden="true"></i>
                        </button>
                    @endforeach
                    <button type="button"
                            data-copy-link="{{ route('blog.show', $post->slug) }}"
                            class="inline-flex h-10 items-center gap-2 border border-slate-300 bg-white px-3 text-sm font-bold text-glow-ink transition hover:border-glow-orange hover:text-glow-orange">
                        <i class="fas fa-link" aria-hidden="true"></i><span data-copy-text>Copy link</span>
                    </button>
                </div>
            </section>

            <aside class="mt-10 border-l-4 border-glow-orange bg-white px-5 py-5 sm:px-6" aria-label="About the author">
                <div class="flex items-center gap-4">
                    <a href="{{ route('staff.profile', ['type' => 'user', 'identifier' => $post->author->id]) }}"
                       class="relative h-12 w-12 shrink-0 overflow-hidden bg-glow-navy">
                        <x-initials-image
                            :src="$post->author->avatar"
                            :title="$post->author->name"
                            imgClass="h-full w-full object-cover"
                            fallbackClass="bg-glow-navy"
                            textClass="text-sm font-black text-white"
                        />
                    </a>
                    <div>
                        <p class="public-kicker">Written by</p>
                        <h2 class="mt-1 font-black text-glow-ink">
                            <a href="{{ route('staff.profile', ['type' => 'user', 'identifier' => $post->author->id]) }}"
                               class="transition hover:text-glow-orange">
                                {{ $post->author->name }}
                            </a>
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $post->author->role_label ?? 'Author' }}</p>
                    </div>
                </div>
            </aside>

            @if($post->series && $seriesPosts->count() > 1)
                <section class="mt-12 border-t-2 border-glow-ink pt-7" aria-labelledby="blog-series-heading">
                    <p class="public-kicker">Continue the series</p>
                    <h2 id="blog-series-heading" class="font-editorial mt-1 text-2xl font-bold text-glow-ink">{{ $post->series }}</h2>
                    <ol class="mt-5 divide-y divide-slate-200 border-y border-slate-200">
                        @foreach($seriesPosts as $seriesPost)
                            <li>
                                <a href="{{ route('blog.show', $seriesPost->slug) }}"
                                   @if($seriesPost->id === $post->id) aria-current="page" @endif
                                   class="grid grid-cols-[2rem_minmax(0,1fr)] gap-3 py-4 text-sm transition {{ $seriesPost->id === $post->id ? 'font-black text-glow-orange' : 'font-bold text-glow-ink hover:text-glow-orange' }}">
                                    <span class="font-mono text-slate-400">{{ str_pad($seriesPost->series_order ?? $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                    <span>{{ $seriesPost->title }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ol>
                </section>
            @endif
        </article>

        @if($post->allow_comments)
            @php
                $approvedComments = $post->comments()
                    ->approved()
                    ->parentOnly()
                    ->with(['user', 'replies' => fn ($query) => $query->approved()->with('user')])
                    ->get();
            @endphp

            <section class="mx-auto mt-16 max-w-[760px] border-t-2 border-glow-ink pt-7" aria-labelledby="blog-comments-heading">
                <p class="public-kicker">Join the conversation</p>
                <h2 id="blog-comments-heading" class="font-editorial mt-1 text-2xl font-bold text-glow-ink">
                    Comments <span class="text-slate-400">({{ $post->comments_count }})</span>
                </h2>

                @if(session()->has('success'))
                    <div class="flash-auto-dismiss mt-6 border-l-4 border-emerald-500 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif

                <form id="blog-comment-form" wire:submit.prevent="submitComment" class="mt-7 border border-slate-200 bg-white p-5 sm:p-6">
                    <div class="flex items-center justify-between gap-3">
                        <label for="blog-comment" class="font-black text-glow-ink">
                            {{ $replyTo ? 'Write your reply' : 'Add your comment' }}
                        </label>
                        @if($replyTo)
                            <button type="button" wire:click="cancelReply" class="text-sm font-bold text-slate-500 transition hover:text-glow-orange">
                                Cancel reply
                            </button>
                        @endif
                    </div>
                    <textarea id="blog-comment"
                              wire:model="comment"
                              rows="4"
                              placeholder="{{ $replyTo ? 'Write a thoughtful reply...' : 'What do you think?' }}"
                              class="mt-4 w-full border border-slate-300 bg-glow-paper px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-glow-orange focus:ring-2 focus:ring-orange-100"></textarea>
                    @error('comment')
                        <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="mt-3 flex justify-end">
                        <button type="submit"
                                wire:loading.attr="disabled"
                                wire:target="submitComment"
                                class="bg-glow-ink px-5 py-3 text-sm font-black text-white transition hover:bg-glow-orange disabled:cursor-wait disabled:opacity-60">
                            {{ $replyTo ? 'Post reply' : 'Post comment' }}
                        </button>
                    </div>
                </form>

                <div class="mt-8 divide-y divide-slate-200 border-t border-slate-200">
                    @forelse($approvedComments as $approvedComment)
                        <article class="flex gap-4 py-7">
                            <div class="relative h-10 w-10 shrink-0 overflow-hidden bg-glow-navy">
                                <x-initials-image
                                    :src="$approvedComment->user?->avatar"
                                    :title="$approvedComment->user?->name ?? 'Anonymous'"
                                    imgClass="h-full w-full object-cover"
                                    fallbackClass="bg-glow-navy"
                                    textClass="text-xs font-black text-white"
                                />
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-start justify-between gap-2">
                                    <div>
                                        <h3 class="font-black text-glow-ink">
                                            {{ $approvedComment->user?->name ?? 'Anonymous' }}
                                            @if($approvedComment->is_pinned)
                                                <i class="fas fa-thumbtack ml-1 text-xs text-glow-orange" title="Pinned"></i>
                                            @endif
                                        </h3>
                                        <time class="text-xs text-slate-400">{{ $approvedComment->created_at->diffForHumans() }}</time>
                                    </div>
                                    <button type="button"
                                            wire:click="setReplyTo({{ $approvedComment->id }})"
                                            onclick="setTimeout(() => document.getElementById('blog-comment-form')?.scrollIntoView({ behavior: 'smooth', block: 'center' }), 120)"
                                            class="text-xs font-black uppercase tracking-[0.12em] text-slate-500 transition hover:text-glow-orange">
                                        Reply
                                    </button>
                                </div>
                                <p class="mt-3 leading-7 text-slate-700">{{ $approvedComment->comment }}</p>

                                @if($approvedComment->replies->count() > 0)
                                    <div class="mt-6 space-y-5 border-l-2 border-orange-200 pl-4 sm:pl-6">
                                        @foreach($approvedComment->replies as $reply)
                                            <div class="flex gap-3">
                                                <div class="relative h-9 w-9 shrink-0 overflow-hidden bg-glow-navy">
                                                    <x-initials-image
                                                        :src="$reply->user?->avatar"
                                                        :title="$reply->user?->name ?? 'Anonymous'"
                                                        imgClass="h-full w-full object-cover"
                                                        fallbackClass="bg-glow-navy"
                                                        textClass="text-xs font-black text-white"
                                                    />
                                                </div>
                                                <div>
                                                    <div class="flex flex-wrap items-baseline gap-x-3">
                                                        <h4 class="text-sm font-black text-glow-ink">{{ $reply->user?->name ?? 'Anonymous' }}</h4>
                                                        <time class="text-xs text-slate-400">{{ $reply->created_at->diffForHumans() }}</time>
                                                    </div>
                                                    <p class="mt-2 text-sm leading-6 text-slate-700">{{ $reply->comment }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="py-12 text-center text-slate-500">No comments yet. Start the conversation.</div>
                    @endforelse
                </div>
            </section>
        @endif

        @if($relatedPosts->count() > 0)
            <section class="mx-auto mt-16 max-w-7xl border-t-2 border-glow-ink pt-7" aria-labelledby="related-blog-heading">
                <div class="mb-6 flex items-end justify-between">
                    <div>
                        <p class="public-kicker">Keep reading</p>
                        <h2 id="related-blog-heading" class="font-editorial mt-1 text-2xl font-bold text-glow-ink">Related articles</h2>
                    </div>
                    <a href="{{ route('blog.index') }}" class="hidden text-sm font-black text-glow-ink transition hover:text-glow-orange sm:inline">
                        All articles <i class="fas fa-arrow-right ml-1 text-xs" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="grid gap-6 md:grid-cols-3">
                    @foreach($relatedPosts as $related)
                        <article class="group border-b border-slate-200 pb-5">
                            <a href="{{ route('blog.show', $related->slug) }}"
                               class="mb-4 block aspect-[16/10] overflow-hidden bg-glow-navy"
                               aria-label="Read {{ $related->title }}">
                                <x-initials-image
                                    :src="$related->featured_image"
                                    :title="$related->title"
                                    imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                                    fallbackClass="bg-glow-navy"
                                    textClass="text-3xl font-black text-white"
                                />
                            </a>
                            <p class="text-xs font-black uppercase tracking-[0.14em] text-glow-orange">{{ $related->category?->name ?? 'Blog' }}</p>
                            <h3 class="font-editorial mt-2 text-lg font-bold leading-snug text-glow-ink">
                                <a href="{{ route('blog.show', $related->slug) }}" class="transition hover:text-glow-orange">{{ $related->title }}</a>
                            </h3>
                            <p class="mt-3 text-xs text-slate-500">{{ $related->read_time }}</p>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </main>

</div>
