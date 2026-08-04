<div class="min-h-screen bg-glow-ivory text-glow-ink">
    <header class="border-b border-white/10 bg-glow-midnight text-white">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
            <nav class="mb-8 flex flex-wrap items-center gap-2 text-xs font-bold uppercase tracking-[0.14em] text-slate-400"
                 aria-label="Breadcrumb">
                <a href="{{ route('podcasts.index') }}" class="transition hover:text-glow-amber">Podcasts</a>
                <i class="fas fa-chevron-right text-[8px] text-slate-600" aria-hidden="true"></i>
                <a href="{{ route('podcasts.show', $episode->show->slug) }}" class="transition hover:text-glow-amber">
                    {{ $episode->show->title }}
                </a>
                <i class="fas fa-chevron-right text-[8px] text-slate-600" aria-hidden="true"></i>
                <span class="text-white">Episode</span>
            </nav>

            <div class="grid items-center gap-8 md:grid-cols-[17rem_minmax(0,1fr)] lg:gap-12">
                <div class="mx-auto w-full max-w-[17rem] md:mx-0">
                    <div class="aspect-square overflow-hidden border border-white/15 bg-glow-navy shadow-[0_24px_60px_rgba(0,0,0,0.28)]">
                        <x-initials-image
                            :src="$episode->cover_image ?? $episode->show->cover_image"
                            :title="$episode->title"
                            imgClass="h-full w-full object-cover"
                            fallbackClass="bg-glow-navy"
                            textClass="text-5xl font-black text-white"
                            :branded="true"
                            placeholderType="Podcast episode"
                            :placeholderSubtitle="'From ' . $episode->show->title"
                            :placeholderMeta="$episode->published_at?->format('M j, Y')"
                        />
                    </div>
                </div>

                <div class="min-w-0">
                    <div class="mb-5 flex flex-wrap items-center gap-x-3 gap-y-2 text-xs font-black uppercase tracking-[0.15em]">
                        <span class="border-l-4 border-glow-orange pl-3 text-glow-amber">Glow FM Podcasts</span>
                        @if($episode->season_number)
                            <span class="text-slate-300">S{{ $episode->season_number }} E{{ $episode->episode_number }}</span>
                        @endif
                        <span class="text-slate-300">{{ ucfirst($episode->episode_type) }}</span>
                        @if($episode->explicit)
                            <span class="border border-red-400/60 px-2 py-0.5 text-red-300">Explicit</span>
                        @endif
                    </div>

                    <p class="text-sm font-bold text-glow-amber">
                        <a href="{{ route('podcasts.show', $episode->show->slug) }}" class="transition hover:text-white">
                            {{ $episode->show->title }}
                        </a>
                    </p>
                    <h1 class="font-editorial mt-3 max-w-4xl text-4xl font-bold leading-[1.08] tracking-tight sm:text-5xl lg:text-6xl">
                        {{ $episode->title }}
                    </h1>

                    @if($episode->description)
                        <p class="mt-5 max-w-3xl text-base leading-7 text-slate-300 sm:text-lg">
                            {{ $episode->description }}
                        </p>
                    @endif

                    <div class="mt-6 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-slate-300">
                        <time datetime="{{ $episode->published_at?->toAtomString() }}">
                            {{ $episode->published_at->format('M j, Y') }}
                        </time>
                        <span class="h-1 w-1 rounded-full bg-glow-orange"></span>
                        <span>{{ $episode->formatted_duration }}</span>
                        <span class="h-1 w-1 rounded-full bg-glow-orange"></span>
                        <span>{{ number_format($episode->plays) }} plays</span>
                        <span class="h-1 w-1 rounded-full bg-glow-orange"></span>
                        <span>{{ number_format($episode->downloads) }} downloads</span>
                    </div>

                    @if($episode->guests && count($episode->guests) > 0)
                        <p class="mt-5 text-sm leading-6 text-slate-300">
                            <span class="font-black uppercase tracking-[0.12em] text-white">Guests</span>
                            <span class="mx-2 text-glow-orange">/</span>
                            {{ implode(', ', $episode->guests) }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </header>

    @if($episode->has_playable_audio)
        <section class="sticky top-[calc(env(safe-area-inset-top)+4.5rem)] z-40 border-b border-slate-200 bg-glow-paper/95 shadow-[0_12px_32px_rgba(7,22,47,0.09)] backdrop-blur-xl lg:top-[6.75rem]"
                 aria-label="Episode audio player">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <div class="grid items-center gap-3 md:grid-cols-[minmax(0,1fr)_auto] md:gap-5">
                    <div class="min-w-0" wire:ignore>
                        <audio id="podcastPlayer"
                               class="h-11 w-full"
                               controls
                               preload="metadata"
                               controlsList="nodownload">
                            <source src="{{ $episode->public_audio_url }}" type="audio/{{ $episode->audio_format ?? 'mpeg' }}">
                            Your browser does not support the audio element.
                        </audio>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ $episode->public_audio_url }}"
                           download
                           target="_blank"
                           rel="noopener"
                           wire:click="trackDownload"
                           class="inline-flex h-10 items-center gap-2 bg-glow-orange px-4 text-sm font-black text-white transition hover:bg-glow-coral">
                            <i class="fas fa-download text-xs" aria-hidden="true"></i>
                            Download
                        </a>

                        <details class="group relative">
                            <summary class="inline-flex h-10 cursor-pointer list-none items-center gap-2 border border-slate-300 bg-white px-4 text-sm font-black text-glow-ink transition hover:border-glow-orange hover:text-glow-orange [&::-webkit-details-marker]:hidden">
                                <i class="fas fa-share-nodes text-xs" aria-hidden="true"></i>
                                Share
                                <i class="fas fa-chevron-down text-[9px] transition group-open:rotate-180" aria-hidden="true"></i>
                            </summary>
                            <div class="absolute right-0 z-50 mt-2 grid w-64 grid-cols-2 border border-slate-200 bg-white p-2 shadow-[0_20px_50px_rgba(7,22,47,0.18)] sm:left-0 sm:right-auto">
                                <button type="button" wire:click="shareEpisode('x')"
                                        class="flex items-center gap-2 px-3 py-2.5 text-left text-sm font-bold text-slate-700 transition hover:bg-slate-50 hover:text-glow-orange">
                                    <i class="fab fa-x-twitter w-4" aria-hidden="true"></i>X
                                </button>
                                <button type="button" wire:click="shareEpisode('facebook')"
                                        class="flex items-center gap-2 px-3 py-2.5 text-left text-sm font-bold text-slate-700 transition hover:bg-slate-50 hover:text-glow-orange">
                                    <i class="fab fa-facebook-f w-4" aria-hidden="true"></i>Facebook
                                </button>
                                <button type="button" wire:click="shareEpisode('linkedin')"
                                        class="flex items-center gap-2 px-3 py-2.5 text-left text-sm font-bold text-slate-700 transition hover:bg-slate-50 hover:text-glow-orange">
                                    <i class="fab fa-linkedin-in w-4" aria-hidden="true"></i>LinkedIn
                                </button>
                                <button type="button" wire:click="shareEpisode('whatsapp')"
                                        class="flex items-center gap-2 px-3 py-2.5 text-left text-sm font-bold text-slate-700 transition hover:bg-slate-50 hover:text-glow-orange">
                                    <i class="fab fa-whatsapp w-4" aria-hidden="true"></i>WhatsApp
                                </button>
                                <button type="button" wire:click="shareEpisode('telegram')"
                                        class="flex items-center gap-2 px-3 py-2.5 text-left text-sm font-bold text-slate-700 transition hover:bg-slate-50 hover:text-glow-orange">
                                    <i class="fab fa-telegram w-4" aria-hidden="true"></i>Telegram
                                </button>
                                <button type="button" wire:click="shareEpisode('reddit')"
                                        class="flex items-center gap-2 px-3 py-2.5 text-left text-sm font-bold text-slate-700 transition hover:bg-slate-50 hover:text-glow-orange">
                                    <i class="fab fa-reddit-alien w-4" aria-hidden="true"></i>Reddit
                                </button>
                                <button type="button" wire:click="shareEpisode('email')"
                                        class="flex items-center gap-2 px-3 py-2.5 text-left text-sm font-bold text-slate-700 transition hover:bg-slate-50 hover:text-glow-orange">
                                    <i class="fas fa-envelope w-4" aria-hidden="true"></i>Email
                                </button>
                                <button type="button"
                                        data-copy-link="{{ url()->current() }}"
                                        class="flex items-center gap-2 px-3 py-2.5 text-left text-sm font-bold text-slate-700 transition hover:bg-slate-50 hover:text-glow-orange">
                                    <i class="fas fa-link w-4" aria-hidden="true"></i><span data-copy-text>Copy link</span>
                                </button>
                            </div>
                        </details>

                        <span class="ml-auto text-xs font-semibold text-slate-500 md:ml-1">
                            {{ $episode->file_size_formatted }} · {{ strtoupper($episode->audio_format ?? 'MP3') }}
                        </span>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if(session()->has('success'))
        <div class="flash-auto-dismiss mx-auto mt-5 max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="border-l-4 border-emerald-500 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if($episode->has_video)
        <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16" aria-labelledby="episode-video-heading">
            <div class="mb-6 border-b-2 border-glow-ink pb-4">
                <p class="public-kicker">Watch</p>
                <h2 id="episode-video-heading" class="font-editorial mt-1 text-3xl font-bold text-glow-ink">Episode video</h2>
            </div>

            <div class="overflow-hidden bg-slate-950">
                @if($episode->video_type === 'youtube' && $episode->youtube_video_id)
                    <div class="relative aspect-video">
                        <iframe class="absolute inset-0 h-full w-full"
                                src="https://www.youtube.com/embed/{{ $episode->youtube_video_id }}"
                                title="Video for {{ $episode->title }}"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                                data-video-embed></iframe>
                    </div>
                @elseif($episode->video_type === 'vimeo')
                    <div class="relative aspect-video">
                        <iframe class="absolute inset-0 h-full w-full"
                                src="{{ str_replace('vimeo.com/', 'player.vimeo.com/video/', $episode->video_url) }}"
                                title="Video for {{ $episode->title }}"
                                frameborder="0"
                                allow="autoplay; fullscreen; picture-in-picture"
                                allowfullscreen
                                data-video-embed></iframe>
                    </div>
                @elseif($episode->video_type === 'upload')
                    <video id="podcastVideoPlayer" class="aspect-video w-full" controls preload="metadata">
                        <source src="{{ $episode->video_url }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @else
                    <div class="px-6 py-16 text-center text-white">
                        <i class="fas fa-arrow-up-right-from-square text-3xl text-glow-orange" aria-hidden="true"></i>
                        <p class="mt-4 text-slate-300">This episode video is hosted on an external platform.</p>
                        <a href="{{ $episode->video_url }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           data-video-external
                           class="mt-6 inline-flex h-12 items-center gap-2 bg-glow-orange px-6 font-black text-white transition hover:bg-glow-coral">
                            <i class="fas fa-play text-xs" aria-hidden="true"></i>Watch video
                        </a>
                    </div>
                @endif
            </div>
        </section>
    @endif

    @if(!empty($episode->platform_links))
        <section class="border-y border-slate-200 bg-white" aria-labelledby="episode-platforms-heading">
            <div class="mx-auto max-w-7xl px-4 py-9 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="public-kicker">Choose your app</p>
                        <h2 id="episode-platforms-heading" class="font-editorial mt-1 text-2xl font-bold text-glow-ink">
                            Listen on other platforms
                        </h2>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @foreach($episode->platform_links as $platform => $url)
                            @continue(blank($url))
                            <a href="{{ $url }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="group inline-flex h-11 items-center gap-2.5 border border-slate-300 bg-white px-4 text-sm font-black text-glow-ink transition hover:border-glow-orange hover:text-glow-orange">
                                @switch($platform)
                                    @case('spotify')
                                        <i class="fab fa-spotify text-lg text-green-500" aria-hidden="true"></i>
                                        @break
                                    @case('apple')
                                        <i class="fab fa-apple text-lg" aria-hidden="true"></i>
                                        @break
                                    @case('youtube_music')
                                        <i class="fab fa-youtube text-lg text-red-500" aria-hidden="true"></i>
                                        @break
                                    @case('audiomack')
                                        <i class="fas fa-music text-lg text-orange-500" aria-hidden="true"></i>
                                        @break
                                    @case('soundcloud')
                                        <i class="fab fa-soundcloud text-lg text-orange-600" aria-hidden="true"></i>
                                        @break
                                    @default
                                        <i class="fas fa-podcast text-lg text-glow-orange" aria-hidden="true"></i>
                                @endswitch
                                {{ ucfirst(str_replace('_', ' ', $platform)) }}
                                <i class="fas fa-arrow-up-right-from-square ml-1 text-[9px] text-slate-400 transition group-hover:text-glow-orange" aria-hidden="true"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <main class="mx-auto grid max-w-7xl gap-12 px-4 py-12 sm:px-6 lg:grid-cols-[minmax(0,1fr)_20rem] lg:px-8 lg:py-16">
        <div class="min-w-0 space-y-14">
            @if($episode->show_notes)
                <section class="border-t-2 border-glow-ink pt-7" aria-labelledby="show-notes-heading">
                    <p class="public-kicker">About this episode</p>
                    <h2 id="show-notes-heading" class="font-editorial mt-1 text-3xl font-bold text-glow-ink">Show notes</h2>
                    <div class="mt-6 text-[1.0625rem] leading-8 text-slate-700">
                        {!! nl2br(e($episode->show_notes)) !!}
                    </div>
                </section>
            @endif

            @if($episode->chapters && count($episode->chapters) > 0)
                <section class="border-t-2 border-glow-ink pt-7" aria-labelledby="episode-chapters-heading">
                    <p class="public-kicker">Jump to a moment</p>
                    <h2 id="episode-chapters-heading" class="font-editorial mt-1 text-3xl font-bold text-glow-ink">Chapters</h2>

                    <div class="mt-6 divide-y divide-slate-200 border-y border-slate-200">
                        @foreach($episode->chapters as $chapter)
                            @continueIfNotArray($chapter)
                            <button type="button"
                                    onclick="const player = document.getElementById('podcastPlayer'); if (player) { player.currentTime = {{ (int) $chapter['time'] }}; player.play(); }"
                                    class="group grid w-full grid-cols-[5rem_minmax(0,1fr)_auto] items-center gap-4 py-4 text-left transition hover:text-glow-orange"
                                    aria-label="Play {{ $chapter['title'] }} from {{ gmdate('H:i:s', $chapter['time']) }}">
                                <span class="font-mono text-xs font-bold text-glow-orange">{{ gmdate('H:i:s', $chapter['time']) }}</span>
                                <span class="font-bold text-glow-ink transition group-hover:text-glow-orange">{{ $chapter['title'] }}</span>
                                <i class="fas fa-play text-xs text-slate-300 transition group-hover:text-glow-orange" aria-hidden="true"></i>
                            </button>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($episode->transcript && count($episode->transcript) > 0)
                <section class="border-t-2 border-glow-ink pt-7" aria-labelledby="episode-transcript-heading">
                    <p class="public-kicker">Read along</p>
                    <h2 id="episode-transcript-heading" class="font-editorial mt-1 text-3xl font-bold text-glow-ink">Transcript</h2>

                    <div class="mt-6 max-h-[34rem] space-y-5 overflow-y-auto border-y border-slate-200 bg-white px-5 py-6 sm:px-6">
                        @foreach($episode->transcript as $line)
                            @continueIfNotArray($line)
                            <div class="grid gap-2 sm:grid-cols-[5rem_minmax(0,1fr)] sm:gap-4">
                                @if(isset($line['time']))
                                    <span class="font-mono text-xs font-bold text-glow-orange">{{ gmdate('H:i:s', $line['time']) }}</span>
                                @else
                                    <span class="hidden sm:block"></span>
                                @endif
                                <p class="leading-7 text-slate-700">{{ $line['text'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <section id="episode-discussion" class="border-t-2 border-glow-ink pt-7" aria-labelledby="episode-comments-heading">
                <p class="public-kicker">Listener discussion</p>
                <h2 id="episode-comments-heading" class="font-editorial mt-1 text-3xl font-bold text-glow-ink">
                    Comments <span class="text-slate-400">({{ $episode->comments->count() }})</span>
                </h2>

                <form id="episode-comment-form" wire:submit.prevent="submitComment" class="mt-7 border border-slate-200 bg-white p-5 sm:p-6">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <label for="episode-comment" class="font-black text-glow-ink">
                            {{ $replyTo ? 'Write your reply' : 'Join the conversation' }}
                        </label>
                        <div class="flex flex-wrap items-center gap-3">
                            @if($replyTo)
                                <button type="button"
                                        wire:click="$set('replyTo', null)"
                                        class="text-sm font-bold text-slate-500 transition hover:text-glow-orange">
                                    Cancel reply
                                </button>
                            @endif
                            @if($commentTimestamp)
                                <button type="button"
                                        wire:click="$set('commentTimestamp', null)"
                                        class="text-sm font-bold text-slate-500 transition hover:text-glow-orange">
                                    Clear timestamp
                                </button>
                            @endif
                        </div>
                    </div>

                    @if($replyTo)
                        <p class="mt-3 border-l-2 border-glow-orange pl-3 text-sm text-slate-600">
                            Your response will be added to this comment thread.
                        </p>
                    @endif

                    @if($commentTimestamp)
                        <p class="mt-3 text-sm font-semibold text-glow-orange">
                            <i class="fas fa-clock mr-1" aria-hidden="true"></i>
                            Timestamp: {{ gmdate('H:i:s', $commentTimestamp) }}
                        </p>
                    @endif

                    <textarea id="episode-comment"
                              wire:model="comment"
                              rows="4"
                              class="mt-4 w-full resize-none border border-slate-300 bg-glow-paper px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-glow-orange focus:ring-2 focus:ring-orange-100"
                              placeholder="{{ $replyTo ? 'Write a thoughtful reply...' : 'Share your thoughts about this episode...' }}"></textarea>
                    @error('comment')
                        <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        @if($episode->has_playable_audio)
                            <button type="button"
                                    onclick="addTimestamp()"
                                    class="text-left text-sm font-bold text-glow-orange transition hover:text-glow-coral">
                                <i class="fas fa-clock mr-1" aria-hidden="true"></i>Add current timestamp
                            </button>
                        @else
                            <span class="text-sm text-slate-400">Timestamps are available for audio episodes.</span>
                        @endif

                        <button type="submit"
                                wire:loading.attr="disabled"
                                wire:target="submitComment"
                                class="bg-glow-ink px-5 py-3 text-sm font-black text-white transition hover:bg-glow-orange disabled:cursor-wait disabled:opacity-60">
                            {{ $replyTo ? 'Post reply' : 'Post comment' }}
                        </button>
                    </div>
                </form>

                <div class="mt-8 divide-y divide-slate-200 border-t border-slate-200">
                    @forelse($episode->comments as $episodeComment)
                        <article class="py-7">
                            <div class="flex gap-4">
                                <img src="{{ $episodeComment->user?->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($episodeComment->user?->name ?? 'Anonymous') }}"
                                     alt="{{ $episodeComment->user?->name ?? 'Anonymous' }}"
                                     class="h-11 w-11 shrink-0 bg-glow-navy object-cover">
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-baseline justify-between gap-2">
                                        <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                                            <h3 class="font-black text-glow-ink">{{ $episodeComment->user?->name ?? 'Anonymous' }}</h3>
                                            <time class="text-xs text-slate-400">{{ $episodeComment->created_at->diffForHumans() }}</time>
                                        </div>
                                        <button type="button"
                                                wire:click="setReplyTo({{ $episodeComment->id }})"
                                                onclick="setTimeout(() => document.getElementById('episode-comment-form')?.scrollIntoView({ behavior: 'smooth', block: 'center' }), 120)"
                                                class="text-xs font-black uppercase tracking-[0.12em] text-slate-500 transition hover:text-glow-orange">
                                            Reply
                                        </button>
                                    </div>

                                    @if($episodeComment->timestamp)
                                        <button type="button"
                                                onclick="const player = document.getElementById('podcastPlayer'); if (player) { player.currentTime = {{ (int) $episodeComment->timestamp }}; player.play(); }"
                                                class="mt-2 inline-flex items-center gap-1.5 font-mono text-xs font-bold text-glow-orange">
                                            <i class="fas fa-play text-[9px]" aria-hidden="true"></i>{{ gmdate('H:i:s', $episodeComment->timestamp) }}
                                        </button>
                                    @endif

                                    <p class="mt-3 leading-7 text-slate-700">{{ $episodeComment->comment }}</p>

                                    @if($episodeComment->replies->count() > 0)
                                        <div class="mt-6 space-y-5 border-l-2 border-orange-200 pl-4 sm:pl-6">
                                            @foreach($episodeComment->replies as $reply)
                                                <div class="flex gap-3">
                                                    <img src="{{ $reply->user?->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($reply->user?->name ?? 'Anonymous') }}"
                                                         alt="{{ $reply->user?->name ?? 'Anonymous' }}"
                                                         class="h-9 w-9 shrink-0 bg-glow-navy object-cover">
                                                    <div class="min-w-0 flex-1">
                                                        <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                                                            <h4 class="text-sm font-black text-glow-ink">{{ $reply->user?->name ?? 'Anonymous' }}</h4>
                                                            <time class="text-xs text-slate-400">{{ $reply->created_at->diffForHumans() }}</time>
                                                        </div>
                                                        @if($reply->timestamp)
                                                            <button type="button"
                                                                    onclick="const player = document.getElementById('podcastPlayer'); if (player) { player.currentTime = {{ (int) $reply->timestamp }}; player.play(); }"
                                                                    class="mt-1 font-mono text-xs font-bold text-glow-orange">
                                                                {{ gmdate('H:i:s', $reply->timestamp) }}
                                                            </button>
                                                        @endif
                                                        <p class="mt-2 text-sm leading-6 text-slate-700">{{ $reply->comment }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="py-12 text-center text-slate-500">
                            No comments yet. Be the first to share your thoughts.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        <aside class="space-y-10">
            <section class="border-t-2 border-glow-orange pt-5" aria-labelledby="about-podcast-show-heading">
                <p class="public-kicker">From the series</p>
                <h2 id="about-podcast-show-heading" class="font-editorial mt-1 text-2xl font-bold text-glow-ink">About this show</h2>

                <a href="{{ route('podcasts.show', $episode->show->slug) }}"
                   class="group mt-5 block"
                   aria-label="View {{ $episode->show->title }}">
                    <div class="aspect-square overflow-hidden bg-glow-navy">
                        <x-initials-image
                            :src="$episode->show->cover_image"
                            :title="$episode->show->title"
                            imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.025]"
                            fallbackClass="bg-glow-navy"
                            textClass="text-4xl font-black text-white"
                            :branded="true"
                            placeholderType="Podcast"
                            :placeholderSubtitle="'Hosted by ' . ($episode->show->host_name ?: $episode->show->host?->name ?: 'Glow FM')"
                            :placeholderMeta="ucfirst($episode->show->frequency ?: 'Weekly')"
                        />
                    </div>
                </a>

                <h3 class="font-editorial mt-4 text-xl font-bold text-glow-ink">
                    <a href="{{ route('podcasts.show', $episode->show->slug) }}" class="transition hover:text-glow-orange">
                        {{ $episode->show->title }}
                    </a>
                </h3>
                @if($episode->show->description)
                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ Str::limit($episode->show->description, 150) }}</p>
                @endif
                <dl class="mt-4 space-y-2 border-t border-slate-200 pt-4 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Host</dt>
                        <dd class="text-right font-bold text-glow-ink">{{ $episode->show->host_name }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Episodes</dt>
                        <dd class="font-bold text-glow-ink">{{ $episode->show->total_episodes }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Subscribers</dt>
                        <dd class="font-bold text-glow-ink">{{ number_format($episode->show->subscribers) }}</dd>
                    </div>
                </dl>
            </section>

            @if($relatedEpisodes->count() > 0)
                <section class="border-t-2 border-glow-ink pt-5" aria-labelledby="related-episodes-heading">
                    <p class="public-kicker">Keep listening</p>
                    <h2 id="related-episodes-heading" class="font-editorial mt-1 text-2xl font-bold text-glow-ink">More episodes</h2>

                    <div class="mt-5 divide-y divide-slate-200 border-y border-slate-200">
                        @foreach($relatedEpisodes as $related)
                            <a href="{{ route('podcasts.episode', [$episode->show->slug, $related->slug]) }}"
                               class="group grid grid-cols-[4.5rem_minmax(0,1fr)] gap-3 py-4">
                                <div class="aspect-square overflow-hidden bg-glow-navy">
                                    <x-initials-image
                                        :src="$related->cover_image ?? $episode->show->cover_image"
                                        :title="$related->title"
                                        imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                                        fallbackClass="bg-glow-navy"
                                        textClass="text-lg font-black text-white"
                                        :branded="true"
                                        placeholderType="Episode"
                                        :placeholderSubtitle="'From ' . $episode->show->title"
                                        :placeholderMeta="$related->published_at?->format('M j, Y')"
                                        :placeholderCompact="true"
                                    />
                                </div>
                                <div class="min-w-0">
                                    <h3 class="line-clamp-2 text-sm font-black leading-snug text-glow-ink transition group-hover:text-glow-orange">
                                        {{ $related->title }}
                                    </h3>
                                    <p class="mt-2 text-xs text-slate-500">{{ $related->formatted_duration }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </aside>
    </main>

    @if($episode->has_playable_audio || $episode->has_video)
        <script>
            (function () {
                let lastTracked = 0;
                let qualifiedRecorded = false;
                let rawRecorded = false;
                const lifecycle = new AbortController();
                const listenerOptions = { signal: lifecycle.signal };

                const recordQualifiedPlay = () => {
                    if (qualifiedRecorded) return;
                    qualifiedRecorded = true;
                    @this.call('recordQualifiedPlay');
                };

                const recordRawPlay = () => {
                    if (rawRecorded) return;
                    rawRecorded = true;
                    @this.call('recordRawPlay');
                };

                const player = document.getElementById('podcastPlayer');
                if (player) {
                    player.addEventListener('play', function () {
                        recordRawPlay();
                    }, listenerOptions);

                    player.addEventListener('timeupdate', function () {
                        const currentTime = Math.floor(player.currentTime);
                        const duration = Math.floor(player.duration);

                        if (!qualifiedRecorded && currentTime >= 10) {
                            recordQualifiedPlay();
                        }

                        if (currentTime > 0 && currentTime % 30 === 0 && currentTime !== lastTracked) {
                            @this.call('updateProgress', currentTime, duration);
                            lastTracked = currentTime;
                        }
                    }, listenerOptions);

                    @if($currentPosition > 0)
                        player.addEventListener('loadedmetadata', function () {
                            player.currentTime = {{ $currentPosition }};
                        }, listenerOptions);
                    @endif
                }

                const videoPlayer = document.getElementById('podcastVideoPlayer');
                if (videoPlayer) {
                    videoPlayer.addEventListener('play', function () {
                        recordRawPlay();
                    }, listenerOptions);

                    videoPlayer.addEventListener('timeupdate', function () {
                        const currentTime = Math.floor(videoPlayer.currentTime);
                        const duration = Math.floor(videoPlayer.duration);

                        if (!qualifiedRecorded && currentTime >= 10) {
                            recordQualifiedPlay();
                        }

                        if (currentTime > 0 && currentTime % 30 === 0 && currentTime !== lastTracked) {
                            @this.call('updateProgress', currentTime, duration);
                            lastTracked = currentTime;
                        }
                    }, listenerOptions);
                }

                const externalVideo = document.querySelector('[data-video-external]');
                if (externalVideo) {
                    externalVideo.addEventListener('click', function () {
                        recordRawPlay();
                        recordQualifiedPlay();
                    }, { once: true, signal: lifecycle.signal });
                }

                const addTimestamp = function () {
                    if (!player) return;
                    const currentTime = Math.floor(player.currentTime);
                    @this.call('setCommentTime', currentTime);
                };

                window.addTimestamp = addTimestamp;

                document.addEventListener('livewire:navigating', function () {
                    lifecycle.abort();

                    if (window.addTimestamp === addTimestamp) {
                        delete window.addTimestamp;
                    }
                }, { once: true });
            })();
        </script>
    @endif
</div>
