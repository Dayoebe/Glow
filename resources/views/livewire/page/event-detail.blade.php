<div class="min-h-screen bg-glow-ivory text-glow-ink">
    <header class="border-b border-white/10 bg-glow-midnight text-white">
        <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
            <nav class="mb-8 flex flex-wrap items-center gap-2 text-xs font-bold uppercase tracking-[0.14em] text-slate-400"
                 aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="transition hover:text-glow-amber">Home</a>
                <i class="fas fa-chevron-right text-[8px] text-slate-600" aria-hidden="true"></i>
                <a href="{{ route('events.index') }}" class="transition hover:text-glow-amber">Events</a>
                <i class="fas fa-chevron-right text-[8px] text-slate-600" aria-hidden="true"></i>
                <a href="{{ route('events.index', ['selectedCategory' => $event->category->slug]) }}"
                   class="text-glow-amber transition hover:text-white">
                    {{ $event->category->name }}
                </a>
            </nav>

            <div class="flex flex-wrap items-center gap-3 text-xs font-black uppercase tracking-[0.16em]">
                <span class="border-l-4 border-glow-orange pl-3 text-glow-amber">{{ $event->category->name }}</span>
                @if($event->is_featured)
                    <span class="text-slate-300">Featured event</span>
                @endif
                @if($event->start_at?->isPast())
                    <span class="border border-white/20 px-2 py-1 text-slate-300">Past event</span>
                @endif
            </div>

            <h1 class="font-editorial mt-6 max-w-5xl text-4xl font-bold leading-[1.06] tracking-tight sm:text-5xl lg:text-6xl">
                {{ $event->title }}
            </h1>

            @if($event->excerpt)
                <p class="mt-6 max-w-3xl text-lg leading-8 text-slate-300 sm:text-xl">{{ $event->excerpt }}</p>
            @endif

            <div class="mt-8 grid gap-5 border-t border-white/15 pt-6 sm:grid-cols-3">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.14em] text-glow-amber">Date &amp; time</p>
                    <p class="mt-2 font-bold text-white">{{ $event->formatted_date }}</p>
                    <p class="mt-1 text-sm text-slate-300">{{ $event->formatted_time }}</p>
                </div>
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.14em] text-glow-amber">Venue</p>
                    <p class="mt-2 font-bold text-white">{{ $event->venue_name ?? 'Venue to be announced' }}</p>
                    @if($event->city || $event->state)
                        <p class="mt-1 text-sm text-slate-300">{{ collect([$event->city, $event->state])->filter()->join(', ') }}</p>
                    @endif
                </div>
                <div class="flex flex-wrap items-start gap-2 sm:justify-end">
                    @if($event->registration_url)
                        <a href="{{ $event->registration_url }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="inline-flex h-11 items-center gap-2 bg-glow-orange px-5 text-sm font-black text-white transition hover:bg-glow-coral">
                            Register <i class="fas fa-arrow-up-right-from-square text-[9px]" aria-hidden="true"></i>
                        </a>
                    @endif
                    @if($event->ticket_url)
                        <a href="{{ $event->ticket_url }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="inline-flex h-11 items-center gap-2 border border-white/30 px-5 text-sm font-black text-white transition hover:border-glow-orange hover:text-glow-amber">
                            Get tickets <i class="fas fa-arrow-up-right-from-square text-[9px]" aria-hidden="true"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <div class="mx-auto max-w-7xl px-4 pt-8 sm:px-6 lg:px-8 lg:pt-10">
        <figure class="aspect-[16/9] max-h-[680px] overflow-hidden bg-glow-navy">
            <x-initials-image
                :src="$event->featured_image"
                :title="$event->title"
                imgClass="h-full w-full object-cover"
                fallbackClass="bg-glow-navy"
                textClass="text-6xl font-black text-white"
                loading="eager"
                fetchpriority="high"
                width="1600"
                height="900"
                sizes="(min-width: 1280px) 80rem, 100vw"
            />
        </figure>
    </div>

    <div class="mx-auto max-w-7xl px-4 pt-8 sm:px-6 lg:px-8">
        <x-ad-slot placement="event-detail" />
    </div>

    <main class="mx-auto grid max-w-7xl gap-12 px-4 pb-16 pt-10 sm:px-6 lg:grid-cols-[minmax(0,1fr)_20rem] lg:px-8 lg:pb-24 lg:pt-14">
        <div class="min-w-0">
            <article class="mx-auto max-w-[760px]">
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
                    {!! app(\App\Support\RichTextSanitizer::class)->sanitize($event->content) !!}
                </div>

                @if($event->gallery && count($event->gallery) > 0)
                    <section class="mt-14 border-t border-slate-200 pt-8" aria-labelledby="event-gallery-heading">
                        <p class="public-kicker">Event gallery</p>
                        <h2 id="event-gallery-heading" class="font-editorial mt-1 text-2xl font-bold text-glow-ink">In pictures</h2>
                        <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3">
                            @foreach($event->gallery as $image)
                                <div class="aspect-square overflow-hidden bg-glow-navy">
                                    <x-initials-image
                                        :src="$image"
                                        :title="$event->title"
                                        imgClass="h-full w-full object-cover"
                                        fallbackClass="bg-glow-navy"
                                        textClass="text-3xl font-black text-white"
                                    />
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if($event->tags && count($event->tags) > 0)
                    <div class="mt-10 flex flex-wrap items-center gap-x-4 gap-y-2 border-t border-slate-200 pt-6">
                        <span class="text-xs font-black uppercase tracking-[0.16em] text-slate-500">Topics</span>
                        @foreach($event->tags as $tag)
                            <a href="{{ route('events.index', ['searchQuery' => $tag]) }}"
                               class="text-sm font-bold text-glow-ink underline decoration-slate-300 underline-offset-4 transition hover:text-glow-orange hover:decoration-glow-orange">
                                #{{ $tag }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <section class="mt-10 border-y border-slate-200 py-7" aria-labelledby="event-reactions-heading">
                    <h2 id="event-reactions-heading" class="text-sm font-black uppercase tracking-[0.16em] text-glow-ink">Interested in this event?</h2>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach(['love' => ['❤️', 'Love'], 'fire' => ['🔥', 'Excited'], 'wow' => ['😮', 'Can’t wait'], 'insightful' => ['💡', 'Interested']] as $type => $reaction)
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
                                    wire:click="shareEvent('{{ $platform }}')"
                                    class="inline-flex h-10 w-10 items-center justify-center border border-slate-300 bg-white text-glow-ink transition hover:border-glow-orange hover:text-glow-orange"
                                    aria-label="Share via {{ ucfirst($platform) }}">
                                <i class="{{ $icon }}" aria-hidden="true"></i>
                            </button>
                        @endforeach
                        <button type="button"
                                data-copy-link="{{ route('events.show', $event->slug) }}"
                                class="inline-flex h-10 items-center gap-2 border border-slate-300 bg-white px-3 text-sm font-bold text-glow-ink transition hover:border-glow-orange hover:text-glow-orange">
                            <i class="fas fa-link" aria-hidden="true"></i><span data-copy-text>Copy link</span>
                        </button>
                    </div>
                </section>

                <aside class="mt-10 border-l-4 border-glow-orange bg-white px-5 py-5 sm:px-6" aria-label="Event organizer">
                    <div class="flex items-center gap-4">
                        <div class="relative h-12 w-12 shrink-0 overflow-hidden bg-glow-navy">
                            <x-initials-image
                                :src="$event->author?->avatar"
                                :title="$event->author?->name ?? 'Glow FM'"
                                imgClass="h-full w-full object-cover"
                                fallbackClass="bg-glow-navy"
                                textClass="text-sm font-black text-white"
                            />
                        </div>
                        <div>
                            <p class="public-kicker">Organized by</p>
                            <h2 class="mt-1 font-black text-glow-ink">{{ $event->author?->name ?? 'Glow FM' }}</h2>
                            <p class="mt-1 text-sm text-slate-500">{{ $event->author?->role_label ?? 'Organizer' }}</p>
                        </div>
                    </div>
                </aside>
            </article>

            @if($event->allow_comments)
                @php
                    $approvedComments = $event->comments()
                        ->approved()
                        ->parentOnly()
                        ->with('user')
                        ->get();
                @endphp

                <section class="mx-auto mt-16 max-w-[760px] border-t-2 border-glow-ink pt-7" aria-labelledby="event-comments-heading">
                    <p class="public-kicker">Community conversation</p>
                    <h2 id="event-comments-heading" class="font-editorial mt-1 text-2xl font-bold text-glow-ink">
                        Comments <span class="text-slate-400">({{ $approvedComments->count() }})</span>
                    </h2>

                    @if(session()->has('success'))
                        <div class="flash-auto-dismiss mt-6 border-l-4 border-emerald-500 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="submitComment" class="mt-7 border border-slate-200 bg-white p-5 sm:p-6">
                        <label for="event-comment" class="font-black text-glow-ink">Add your comment</label>
                        <textarea id="event-comment"
                                  wire:model="comment"
                                  rows="4"
                                  placeholder="Share your thoughts about this event"
                                  class="mt-4 w-full border border-slate-300 bg-glow-paper px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-glow-orange focus:ring-2 focus:ring-orange-100"></textarea>
                        @error('comment')
                            <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                        <div class="mt-3 flex justify-end">
                            <button type="submit"
                                    wire:loading.attr="disabled"
                                    wire:target="submitComment"
                                    class="bg-glow-ink px-5 py-3 text-sm font-black text-white transition hover:bg-glow-orange disabled:cursor-wait disabled:opacity-60">
                                Post comment
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
                                    <h3 class="font-black text-glow-ink">{{ $approvedComment->user?->name ?? 'Anonymous' }}</h3>
                                    <time class="text-xs text-slate-400">{{ $approvedComment->created_at->diffForHumans() }}</time>
                                    <p class="mt-3 leading-7 text-slate-700">{{ $approvedComment->comment }}</p>
                                </div>
                            </article>
                        @empty
                            <div class="py-12 text-center text-slate-500">No comments yet. Start the conversation.</div>
                        @endforelse
                    </div>
                </section>
            @endif

            @if($relatedEvents->count() > 0)
                <section class="mt-16 border-t-2 border-glow-ink pt-7" aria-labelledby="related-events-heading">
                    <div class="mb-6 flex items-end justify-between">
                        <div>
                            <p class="public-kicker">You may also like</p>
                            <h2 id="related-events-heading" class="font-editorial mt-1 text-2xl font-bold text-glow-ink">Related events</h2>
                        </div>
                        <a href="{{ route('events.index') }}" class="hidden text-sm font-black text-glow-ink transition hover:text-glow-orange sm:inline">
                            All events <i class="fas fa-arrow-right ml-1 text-xs" aria-hidden="true"></i>
                        </a>
                    </div>
                    <div class="grid gap-6 md:grid-cols-3">
                        @foreach($relatedEvents as $related)
                            <article class="group border-b border-slate-200 pb-5">
                                <a href="{{ route('events.show', $related->slug) }}"
                                   class="mb-4 block aspect-[16/10] overflow-hidden bg-glow-navy"
                                   aria-label="View {{ $related->title }}">
                                    <x-initials-image
                                        :src="$related->featured_image"
                                        :title="$related->title"
                                        imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                                        fallbackClass="bg-glow-navy"
                                        textClass="text-3xl font-black text-white"
                                    />
                                </a>
                                <p class="text-xs font-black uppercase tracking-[0.14em] text-glow-orange">{{ $related->formatted_date }}</p>
                                <h3 class="font-editorial mt-2 text-lg font-bold leading-snug text-glow-ink">
                                    <a href="{{ route('events.show', $related->slug) }}" class="transition hover:text-glow-orange">{{ $related->title }}</a>
                                </h3>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <aside class="order-first lg:order-last">
            <div class="sticky top-32 border-t-2 border-glow-orange pt-5">
                <p class="public-kicker">Event essentials</p>
                <h2 class="font-editorial mt-1 text-2xl font-bold text-glow-ink">Plan your visit</h2>

                <dl class="mt-5 divide-y divide-slate-200 border-y border-slate-200">
                    <div class="py-4">
                        <dt class="text-xs font-black uppercase tracking-[0.13em] text-slate-400">Date</dt>
                        <dd class="mt-1 font-bold text-glow-ink">{{ $event->formatted_date }}</dd>
                    </div>
                    <div class="py-4">
                        <dt class="text-xs font-black uppercase tracking-[0.13em] text-slate-400">Time</dt>
                        <dd class="mt-1 font-bold text-glow-ink">{{ $event->formatted_time }}</dd>
                    </div>
                    <div class="py-4">
                        <dt class="text-xs font-black uppercase tracking-[0.13em] text-slate-400">Location</dt>
                        <dd class="mt-1 font-bold text-glow-ink">{{ $event->venue_name ?? 'Venue to be announced' }}</dd>
                        @if($event->venue_address)
                            <dd class="mt-1 text-sm leading-6 text-slate-500">{{ $event->venue_address }}</dd>
                        @endif
                    </div>
                    <div class="grid grid-cols-2 gap-4 py-4">
                        <div>
                            <dt class="text-xs font-black uppercase tracking-[0.13em] text-slate-400">Price</dt>
                            <dd class="mt-1 font-bold text-glow-ink">{{ $event->price ?: 'Free' }}</dd>
                        </div>
                        @if($event->capacity)
                            <div>
                                <dt class="text-xs font-black uppercase tracking-[0.13em] text-slate-400">Capacity</dt>
                                <dd class="mt-1 font-bold text-glow-ink">{{ number_format($event->capacity) }}</dd>
                            </div>
                        @endif
                    </div>
                </dl>

                <div class="mt-5 space-y-2">
                    @if($event->registration_url)
                        <a href="{{ $event->registration_url }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="flex h-12 w-full items-center justify-center gap-2 bg-glow-orange px-5 text-sm font-black text-white transition hover:bg-glow-coral">
                            Register now <i class="fas fa-arrow-up-right-from-square text-[9px]" aria-hidden="true"></i>
                        </a>
                    @endif
                    @if($event->ticket_url)
                        <a href="{{ $event->ticket_url }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="flex h-12 w-full items-center justify-center gap-2 border border-glow-ink px-5 text-sm font-black text-glow-ink transition hover:bg-glow-ink hover:text-white">
                            Get tickets <i class="fas fa-arrow-up-right-from-square text-[9px]" aria-hidden="true"></i>
                        </a>
                    @endif
                    <button type="button"
                            wire:click="toggleBookmark"
                            class="flex h-11 w-full items-center justify-center gap-2 border border-slate-300 bg-white px-4 text-sm font-black text-glow-ink transition hover:border-glow-orange hover:text-glow-orange">
                        <i class="{{ $isBookmarked ? 'fas' : 'far' }} fa-bookmark" aria-hidden="true"></i>
                        {{ $isBookmarked ? 'Saved event' : 'Save event' }}
                    </button>
                </div>
            </div>
        </aside>
    </main>
</div>
