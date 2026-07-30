<div
    x-data="{
        imageViewerOpen: false,
        activeImage: null,
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
            document.documentElement.classList.remove('overflow-hidden');
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
        },
        openImage(image) {
            if (!image || !image.src) return;
            this.activeImage = image;
            this.imageViewerOpen = true;
            document.documentElement.classList.add('overflow-hidden');
        },
        closeImage() {
            this.imageViewerOpen = false;
            this.activeImage = null;
            document.documentElement.classList.remove('overflow-hidden');
        }
    }"
    @keydown.escape.window="closeImage()"
    data-qualified-view-tracker
    class="min-h-screen bg-glow-ivory text-slate-950"
>
    @if($news->is_breaking)
        <div class="border-b border-red-500/30 bg-red-700 text-white">
            <div class="mx-auto flex max-w-7xl items-center gap-3 px-4 py-3 text-sm sm:px-6 lg:px-8">
                <span class="bg-white px-2 py-1 text-[10px] font-black uppercase tracking-[0.16em] text-red-700">
                    {{ $news->breaking === 'urgent' ? 'Urgent' : 'Breaking' }}
                </span>
                <span class="font-semibold">Developing story from the Glow FM newsroom</span>
            </div>
        </div>
    @endif

    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
            <nav class="mb-8 flex flex-wrap items-center gap-2 text-xs font-bold uppercase tracking-[0.14em] text-slate-500"
                 aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="transition hover:text-orange-600">Home</a>
                <i class="fas fa-chevron-right text-[8px] text-slate-300"></i>
                <a href="{{ route('news') }}" class="transition hover:text-orange-600">News</a>
                <i class="fas fa-chevron-right text-[8px] text-slate-300"></i>
                <a href="{{ route('news', ['selectedCategory' => $news->category->slug]) }}"
                   class="text-orange-600 transition hover:text-orange-700">
                    {{ $news->category->name }}
                </a>
            </nav>

            <div class="flex flex-wrap items-center gap-3">
                <span class="border-l-4 border-orange-500 pl-3 text-xs font-black uppercase tracking-[0.18em] text-[#071a33]">
                    {{ $news->category->name }}
                </span>
                @if($news->video_url)
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        <i class="fas fa-play mr-1.5 text-orange-500"></i>Video
                    </span>
                @endif
                @if($news->gallery && count($news->gallery) > 0)
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        <i class="far fa-images mr-1.5 text-orange-500"></i>{{ count($news->gallery) }} photos
                    </span>
                @endif
            </div>

            <h1 class="font-editorial mt-6 max-w-5xl text-4xl font-bold leading-[1.06] tracking-tight text-[#071a33] sm:text-5xl lg:text-6xl">
                {{ $news->title }}
            </h1>

            @if($news->excerpt)
                <p class="mt-6 max-w-3xl text-lg leading-8 text-slate-600 sm:text-xl">
                    {{ $news->excerpt }}
                </p>
            @endif

            <div class="mt-8 flex flex-col gap-5 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="font-bold text-[#071a33]">
                        By {{ $news->author?->name ?? 'Glow FM Newsroom' }}
                    </p>
                    <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-slate-500">
                        <time datetime="{{ $news->published_at?->toAtomString() }}">{{ $news->formatted_published_date }}</time>
                        <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                        <span>{{ $news->read_time }}</span>
                        <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                        <span>{{ number_format($news->views) }} views</span>
                        @if($news->updated_at && (!$news->published_at || $news->updated_at->gt($news->published_at)))
                            <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                            <span>Updated {{ $news->updated_at->format('M j, Y') }}</span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2" aria-label="Article actions">
                    <button type="button"
                            wire:click="toggleBookmark"
                            class="inline-flex h-10 items-center gap-2 border border-slate-300 px-3.5 text-sm font-bold text-slate-700 transition hover:border-orange-500 hover:text-orange-600"
                            aria-label="{{ $isBookmarked ? 'Remove bookmark' : 'Save article' }}">
                        <i class="{{ $isBookmarked ? 'fas' : 'far' }} fa-bookmark"></i>
                        <span class="hidden sm:inline">{{ $isBookmarked ? 'Saved' : 'Save' }}</span>
                    </button>
                    <button type="button"
                            data-copy-link="{{ url()->current() }}"
                            class="inline-flex h-10 items-center gap-2 border border-slate-300 px-3.5 text-sm font-bold text-slate-700 transition hover:border-orange-500 hover:text-orange-600">
                        <i class="fas fa-link"></i>
                        <span data-copy-text class="hidden sm:inline">Copy link</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    @php
        $featuredImageViewer = [
            'src' => $news->featured_image,
            'title' => $news->title,
            'label' => 'Featured image',
        ];
    @endphp

    <div class="mx-auto max-w-7xl px-4 pt-8 sm:px-6 lg:px-8 lg:pt-10">
        <figure class="overflow-hidden bg-[#102b4e]">
            @if($news->featured_image)
                <button type="button"
                        @click='openImage(@json($featuredImageViewer))'
                        class="group relative block aspect-[16/9] max-h-[680px] w-full cursor-zoom-in overflow-hidden text-left"
                        aria-label="Open full image for {{ $news->title }}">
                    <x-initials-image
                        :src="$news->featured_image"
                        :title="$news->title"
                        imgClass="h-full w-full object-cover transition duration-700 group-hover:scale-[1.015]"
                        fallbackClass="bg-[#102b4e]"
                        textClass="text-6xl font-black text-white"
                        loading="eager"
                        fetchpriority="high"
                        width="1600"
                        height="900"
                        sizes="(min-width: 1280px) 80rem, 100vw"
                    />
                    <span class="absolute bottom-4 right-4 inline-flex h-10 w-10 items-center justify-center bg-[#071a33]/85 text-sm text-white opacity-90 backdrop-blur-sm transition group-hover:bg-orange-500 group-hover:text-[#071a33]"
                          aria-hidden="true">
                        <i class="fas fa-expand"></i>
                    </span>
                </button>
            @else
                <div class="aspect-[16/9] max-h-[680px]">
                    <x-initials-image
                        :src="$news->featured_image"
                        :title="$news->title"
                        imgClass="h-full w-full object-cover"
                        fallbackClass="bg-[#102b4e]"
                        textClass="text-6xl font-black text-white"
                        loading="eager"
                        fetchpriority="high"
                        width="1600"
                        height="900"
                        sizes="(min-width: 1280px) 80rem, 100vw"
                    />
                </div>
            @endif
        </figure>
    </div>

    <div class="mx-auto max-w-7xl px-4 pt-8 sm:px-6 lg:px-8">
        <x-ad-slot placement="news-detail" />
    </div>

    <main class="px-4 pb-16 pt-10 sm:px-6 lg:px-8 lg:pb-24 lg:pt-14">
        <article class="mx-auto max-w-[760px]">
            @if($news->video_url)
                <div class="mb-10 aspect-video overflow-hidden bg-slate-950">
                    <iframe src="{{ $news->video_url }}"
                            title="Video for {{ $news->title }}"
                            class="h-full w-full"
                            frameborder="0"
                            loading="lazy"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                </div>
            @endif

            <div class="max-w-none text-[1.0625rem] leading-8 text-slate-700
                        [&_p]:mb-6
                        [&_h2]:font-editorial [&_h2]:mb-4 [&_h2]:mt-12 [&_h2]:text-3xl [&_h2]:font-bold [&_h2]:leading-tight [&_h2]:text-[#071a33]
                        [&_h3]:font-editorial [&_h3]:mb-3 [&_h3]:mt-9 [&_h3]:text-2xl [&_h3]:font-bold [&_h3]:text-[#071a33]
                        [&_h4]:mb-3 [&_h4]:mt-8 [&_h4]:text-xl [&_h4]:font-black [&_h4]:text-[#071a33]
                        [&_a]:font-semibold [&_a]:text-orange-600 [&_a]:underline [&_a]:decoration-orange-300 [&_a]:underline-offset-4
                        [&_strong]:font-black [&_strong]:text-[#071a33]
                        [&_ul]:my-6 [&_ul]:list-disc [&_ul]:space-y-2 [&_ul]:pl-6
                        [&_ol]:my-6 [&_ol]:list-decimal [&_ol]:space-y-2 [&_ol]:pl-6
                        [&_blockquote]:my-8 [&_blockquote]:border-l-4 [&_blockquote]:border-orange-500 [&_blockquote]:bg-white [&_blockquote]:px-6 [&_blockquote]:py-4 [&_blockquote]:font-editorial [&_blockquote]:text-xl [&_blockquote]:italic [&_blockquote]:text-[#071a33]
                        [&_img]:my-8 [&_img]:h-auto [&_img]:w-full
                        [&_figure]:my-8 [&_figcaption]:mt-2 [&_figcaption]:text-sm [&_figcaption]:text-slate-500">
                {!! app(\App\Support\RichTextSanitizer::class)->sanitize($news->content) !!}
            </div>

            @if($news->gallery && count($news->gallery) > 0)
                <section class="mt-14 border-t border-slate-200 pt-8" aria-labelledby="photo-gallery-heading">
                    <div class="mb-5 flex items-end justify-between">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-orange-600">In pictures</p>
                            <h2 id="photo-gallery-heading" class="font-editorial mt-1 text-2xl font-bold text-[#071a33]">Photo gallery</h2>
                        </div>
                        <span class="text-sm text-slate-500">{{ count($news->gallery) }} photos</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                        @foreach($news->gallery as $image)
                            @php
                                $galleryImageViewer = [
                                    'src' => $image,
                                    'title' => $news->title,
                                    'label' => 'Gallery image ' . $loop->iteration,
                                ];
                            @endphp
                            <button type="button"
                                    @click='openImage(@json($galleryImageViewer))'
                                    class="group relative aspect-square w-full cursor-zoom-in overflow-hidden bg-[#102b4e] text-left"
                                    aria-label="Open gallery image {{ $loop->iteration }} for {{ $news->title }}">
                                <x-initials-image
                                    :src="$image"
                                    :title="$news->title"
                                    imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                                    fallbackClass="bg-[#102b4e]"
                                    textClass="text-3xl font-black text-white"
                                />
                                <span class="absolute inset-0 flex items-center justify-center bg-[#071a33]/0 text-white opacity-0 transition group-hover:bg-[#071a33]/40 group-hover:opacity-100">
                                    <i class="fas fa-expand"></i>
                                </span>
                            </button>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($news->tags && count($news->tags) > 0)
                <div class="mt-10 flex flex-wrap items-center gap-x-4 gap-y-2 border-t border-slate-200 pt-6">
                    <span class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Filed under</span>
                    @foreach($news->tags as $tag)
                        <a href="{{ route('news', ['tag' => $tag]) }}"
                           class="text-sm font-bold text-[#071a33] underline decoration-slate-300 underline-offset-4 transition hover:text-orange-600 hover:decoration-orange-500">
                            #{{ $tag }}
                        </a>
                    @endforeach
                </div>
            @endif

            <section class="mt-10 border-y border-slate-200 py-7" aria-labelledby="article-reactions-heading">
                <div class="flex flex-col gap-6">
                    <div>
                        <h2 id="article-reactions-heading" class="text-sm font-black uppercase tracking-[0.16em] text-[#071a33]">
                            Your reaction
                        </h2>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach(['love' => ['❤️', 'Love'], 'fire' => ['🔥', 'Hot'], 'wow' => ['😮', 'Wow'], 'insightful' => ['💡', 'Useful']] as $type => $reaction)
                                <button type="button"
                                        wire:click="toggleReaction('{{ $type }}')"
                                        class="inline-flex h-10 items-center gap-2 border px-3 text-sm font-bold transition {{ isset($userReactions[$type]) ? 'border-orange-500 bg-orange-50 text-orange-700' : 'border-slate-300 bg-white text-slate-700 hover:border-orange-400' }}">
                                    <span aria-hidden="true">{{ $reaction[0] }}</span>
                                    <span>{{ $reaction[1] }}</span>
                                    <span class="text-xs text-slate-400">{{ $reactions[$type] ?? 0 }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <span class="mr-1 text-sm font-bold text-slate-500">Share</span>
                        <button type="button"
                                wire:click="shareNews('x')"
                                class="inline-flex h-10 w-10 items-center justify-center bg-[#071a33] text-white transition hover:bg-orange-500 hover:text-[#071a33]"
                                aria-label="Share on X">
                            <i class="fab fa-x-twitter"></i>
                        </button>
                        <button type="button"
                                wire:click="shareNews('facebook')"
                                class="inline-flex h-10 w-10 items-center justify-center border border-slate-300 bg-white text-[#071a33] transition hover:border-orange-500 hover:text-orange-600"
                                aria-label="Share on Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </button>
                        <button type="button"
                                wire:click="shareNews('whatsapp')"
                                class="inline-flex h-10 w-10 items-center justify-center border border-slate-300 bg-white text-[#071a33] transition hover:border-orange-500 hover:text-orange-600"
                                aria-label="Share on WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </button>
                        <button type="button"
                                data-copy-link="{{ url()->current() }}"
                                class="inline-flex h-10 items-center gap-2 border border-slate-300 bg-white px-3 text-sm font-bold text-[#071a33] transition hover:border-orange-500 hover:text-orange-600">
                            <i class="fas fa-link"></i>
                            <span data-copy-text>Copy link</span>
                        </button>
                    </div>
                </div>
            </section>

            <aside class="mt-10 border-l-4 border-orange-500 bg-white px-5 py-5 sm:px-6" aria-label="About the author">
                <div class="flex items-center gap-4">
                    <div class="relative h-12 w-12 shrink-0 overflow-hidden bg-[#102b4e]">
                        <x-initials-image
                            :src="$news->author?->avatar"
                            :title="$news->author?->name ?? 'Glow FM Newsroom'"
                            imgClass="h-full w-full object-cover"
                            fallbackClass="bg-[#102b4e]"
                            textClass="text-sm font-black text-white"
                        />
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.14em] text-orange-600">Written by</p>
                        <h2 class="mt-1 font-black text-[#071a33]">{{ $news->author?->name ?? 'Glow FM Newsroom' }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $news->author?->role_label ?? 'Glow FM Newsroom' }}</p>
                    </div>
                </div>
            </aside>

            @if($articleSummary || count($keyTakeaways) > 0 || count($articleFaqs) > 0)
                <section class="mt-14 border-t-2 border-[#071a33] pt-7" aria-labelledby="article-guide-heading">
                    <div class="mb-5">
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-orange-600">Story guide</p>
                        <h2 id="article-guide-heading" class="font-editorial mt-1 text-2xl font-bold text-[#071a33]">Understand this story</h2>
                    </div>

                    <div class="divide-y divide-slate-200 border-y border-slate-200">
                        @if($articleSummary)
                            <details class="group" open>
                                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 py-5 text-left">
                                    <span class="font-black text-[#071a33]">Summary</span>
                                    <i class="fas fa-chevron-down text-xs text-orange-500 transition group-open:rotate-180"></i>
                                </summary>
                                <div class="pb-6">
                                    <p class="text-base leading-7 text-slate-700">{{ $articleSummary }}</p>
                                </div>
                            </details>
                        @endif

                        @if(count($keyTakeaways) > 0)
                            <details class="group">
                                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 py-5 text-left">
                                    <span class="font-black text-[#071a33]">Key takeaways</span>
                                    <i class="fas fa-chevron-down text-xs text-orange-500 transition group-open:rotate-180"></i>
                                </summary>
                                <div class="pb-6">
                                    <ul class="space-y-3">
                                        @foreach($keyTakeaways as $takeaway)
                                            <li class="flex gap-3 text-slate-700">
                                                <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-orange-500"></span>
                                                <span class="leading-7">{{ $takeaway }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </details>
                        @endif

                        @if(count($articleFaqs) > 0)
                            <details class="group">
                                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 py-5 text-left">
                                    <span class="font-black text-[#071a33]">Article FAQ</span>
                                    <i class="fas fa-chevron-down text-xs text-orange-500 transition group-open:rotate-180"></i>
                                </summary>
                                <div class="space-y-5 pb-6">
                                    @foreach($articleFaqs as $faq)
                                        <div>
                                            <h3 class="font-bold text-[#071a33]">{{ $faq['question'] }}</h3>
                                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ $faq['answer'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </details>
                        @endif
                    </div>
                </section>
            @endif
        </article>

        <section class="mx-auto mt-16 max-w-[760px] border-t-2 border-[#071a33] pt-7" aria-labelledby="comments-heading">
            <div class="flex items-end justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-orange-600">Join the conversation</p>
                    <h2 id="comments-heading" class="font-editorial mt-1 text-2xl font-bold text-[#071a33]">
                        Comments <span class="text-slate-400">({{ $news->comments_count }})</span>
                    </h2>
                </div>
            </div>

            @if(session()->has('success'))
                <div class="flash-auto-dismiss mt-6 border-l-4 border-emerald-500 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif
            @if(session()->has('error'))
                <div class="flash-auto-dismiss mt-6 border-l-4 border-red-500 bg-red-50 px-4 py-3 text-sm font-semibold text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="submitComment" class="mt-7">
                <label for="news-comment" class="mb-2 block text-sm font-bold text-[#071a33]">Add your comment</label>
                <textarea id="news-comment"
                          wire:model="comment"
                          rows="4"
                          placeholder="What do you think about this story?"
                          class="w-full border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-orange-500 focus:ring-2 focus:ring-orange-100"></textarea>
                @error('comment')
                    <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                @enderror
                <div class="mt-3 flex justify-end">
                    <button type="submit"
                            wire:loading.attr="disabled"
                            wire:target="submitComment"
                            class="bg-[#071a33] px-5 py-3 text-sm font-black text-white transition hover:bg-orange-500 hover:text-[#071a33] disabled:cursor-wait disabled:opacity-60">
                        Post comment
                    </button>
                </div>
            </form>

            <div class="mt-10 divide-y divide-slate-200 border-t border-slate-200">
                @forelse($news->comments()->approved()->get() as $approvedComment)
                    <article class="flex gap-4 py-6">
                        <div class="relative h-10 w-10 shrink-0 overflow-hidden bg-[#102b4e]">
                            <x-initials-image
                                :src="$approvedComment->user?->avatar"
                                :title="$approvedComment->user?->name ?? 'Anonymous'"
                                imgClass="h-full w-full object-cover"
                                fallbackClass="bg-[#102b4e]"
                                textClass="text-xs font-black text-white"
                            />
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                                <h3 class="font-black text-[#071a33]">
                                    {{ $approvedComment->user?->name ?? 'Anonymous' }}
                                    @if($approvedComment->is_pinned)
                                        <i class="fas fa-thumbtack ml-1 text-xs text-orange-500" title="Pinned"></i>
                                    @endif
                                </h3>
                                <time class="text-xs text-slate-400">{{ $approvedComment->created_at->diffForHumans() }}</time>
                            </div>
                            <p class="mt-2 leading-7 text-slate-700">{{ $approvedComment->comment }}</p>
                        </div>
                    </article>
                @empty
                    <div class="py-10 text-center text-slate-500">
                        No comments yet. Start the conversation.
                    </div>
                @endforelse
            </div>
        </section>

        @if($relatedNews->count() > 0)
            <section class="mx-auto mt-16 max-w-7xl border-t-2 border-[#071a33] pt-7" aria-labelledby="related-news-heading">
                <div class="mb-6 flex items-end justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-orange-600">Keep reading</p>
                        <h2 id="related-news-heading" class="font-editorial mt-1 text-2xl font-bold text-[#071a33]">Related stories</h2>
                    </div>
                    <a href="{{ route('news') }}" class="hidden text-sm font-bold text-[#071a33] transition hover:text-orange-600 sm:inline">
                        More news <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="grid gap-6 md:grid-cols-3">
                    @foreach($relatedNews as $related)
                        <article class="group border-b border-slate-200 pb-5">
                            <a href="{{ route('news.show', $related->slug) }}"
                               class="mb-4 block aspect-[16/10] overflow-hidden bg-[#102b4e]"
                               aria-label="Read {{ $related->title }}">
                                <x-initials-image
                                    :src="$related->featured_image"
                                    :title="$related->title"
                                    imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                                    fallbackClass="bg-[#102b4e]"
                                    textClass="text-3xl font-black text-white"
                                />
                            </a>
                            <p class="text-xs font-black uppercase tracking-[0.14em] text-orange-600">
                                {{ $related->category?->name ?? 'News' }}
                            </p>
                            <h3 class="font-editorial mt-2 text-lg font-bold leading-snug text-[#071a33]">
                                <a href="{{ route('news.show', $related->slug) }}" class="transition hover:text-orange-600">
                                    {{ $related->title }}
                                </a>
                            </h3>
                            <p class="mt-3 text-xs text-slate-500">{{ $related->read_time }}</p>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </main>

    <div x-cloak
         x-show="imageViewerOpen"
         x-transition.opacity
         class="fixed inset-0 z-[90]"
         role="dialog"
         aria-modal="true"
         aria-label="Full news image">
        <div class="absolute inset-0 bg-slate-950/95 backdrop-blur-sm" @click="closeImage()"></div>

        <div class="relative flex min-h-screen items-center justify-center p-4 md:p-8" @click.self="closeImage()">
            <div class="relative w-full max-w-6xl">
                <button type="button"
                        @click="closeImage()"
                        class="absolute right-3 top-3 z-10 inline-flex h-11 w-11 items-center justify-center bg-[#071a33] text-white transition hover:bg-orange-500 hover:text-[#071a33]"
                        aria-label="Close full image">
                    <i class="fas fa-times"></i>
                </button>

                <div class="overflow-hidden bg-slate-950">
                    <img :src="activeImage ? activeImage.src : ''"
                         :alt="activeImage ? activeImage.title : ''"
                         class="max-h-[82vh] w-full object-contain">
                </div>

                <div class="mt-3 flex flex-col gap-1 text-white sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm font-semibold text-white/70" x-text="activeImage ? activeImage.label : ''"></p>
                    <p class="text-base font-bold" x-text="activeImage ? activeImage.title : ''"></p>
                </div>
            </div>
        </div>
    </div>

</div>
