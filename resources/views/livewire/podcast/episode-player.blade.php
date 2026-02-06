<div class="min-h-screen bg-gray-50">

    <!-- Episode Header -->
    <section class="relative bg-gradient-to-br from-purple-900 via-purple-800 to-indigo-900 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <!-- Breadcrumb -->
                <nav class="flex items-center space-x-2 text-sm text-purple-200 mb-6">
                    <a href="{{ route('podcasts.index') }}" class="hover:text-white">Podcasts</a>
                    <span>›</span>
                    <a href="{{ route('podcasts.show', $episode->show->slug) }}" class="hover:text-white">
                        {{ $episode->show->title }}
                    </a>
                    <span>›</span>
                    <span class="text-white">Episode</span>
                </nav>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Episode Cover -->
                    <div class="md:col-span-1">
                        <img src="{{ $episode->cover_image ?? $episode->show->cover_image }}"
                            alt="{{ $episode->title }}" class="w-full rounded-xl shadow-2xl">
                    </div>

                    <!-- Episode Info -->
                    <div class="md:col-span-2">
                        <div class="flex items-center space-x-3 mb-3">
                            @if($episode->season_number)
                                <span class="px-3 py-1 bg-purple-600 text-white font-bold rounded-full text-sm">
                                    S{{ $episode->season_number }} E{{ $episode->episode_number }}
                                </span>
                            @endif
                            <span class="px-3 py-1 bg-white/20 text-white font-semibold rounded-full text-sm">
                                {{ ucfirst($episode->episode_type) }}
                            </span>
                            @if($episode->explicit)
                                <span class="px-3 py-1 bg-red-600 text-white font-bold rounded-full text-sm">EXPLICIT</span>
                            @endif
                        </div>

                        <h1 class="text-3xl md:text-4xl font-bold mb-4">{{ $episode->title }}</h1>

                        <p class="text-purple-100 text-lg mb-6 leading-relaxed">{{ $episode->description }}</p>

                        <div class="flex flex-wrap items-center gap-4 text-sm mb-6">
                            <span><i
                                    class="fas fa-calendar mr-1"></i>{{ $episode->published_at->format('M d, Y') }}</span>
                            <span><i class="fas fa-clock mr-1"></i>{{ $episode->formatted_duration }}</span>
                            <span><i class="fas fa-headphones mr-1"></i>{{ number_format($episode->plays) }}
                                plays</span>
                            <span><i class="fas fa-download mr-1"></i>{{ number_format($episode->downloads) }}</span>
                        </div>

                        @if($episode->guests && count($episode->guests) > 0)
                            <div class="mb-6">
                                <p class="text-sm text-purple-200 mb-2">Featured Guests:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($episode->guests as $guest)
                                        <span class="px-3 py-1 bg-white/20 text-white rounded-full text-sm">
                                            {{ $guest }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($episode->audio_file)
        <!-- Audio Player -->
        <section class="py-8 bg-white border-b sticky top-0 z-40 shadow-md">
            <div class="container mx-auto px-4">
                <div class="max-w-5xl mx-auto">
                    <div id="audioPlayerContainer">
                        <audio id="podcastPlayer" class="w-full" controls controlsList="nodownload">
                            <source src="{{ $episode->audio_file }}" type="audio/{{ $episode->audio_format ?? 'mpeg' }}">
                            Your browser does not support the audio element.
                        </audio>
                    </div>

                    <!-- Player Controls -->
                    <div class="mt-4 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <button wire:click="trackDownload"
                                class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors">
                                <i class="fas fa-download mr-2"></i>Download
                            </button>

                            <div class="relative group">
                                <button
                                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors">
                                    <i class="fas fa-share-alt mr-2"></i>Share
                                </button>
                                <div
                                    class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                                    <button wire:click="shareEpisode('x')"
                                        class="w-full px-4 py-2 text-left hover:bg-gray-100 rounded-t-lg flex items-center">
                                        <i class="fab fa-x-twitter text-gray-900 mr-2"></i>X
                                    </button>
                                    <button wire:click="shareEpisode('facebook')"
                                        class="w-full px-4 py-2 text-left hover:bg-gray-100 flex items-center">
                                        <i class="fab fa-facebook text-blue-600 mr-2"></i>Facebook
                                    </button>
                                    <button wire:click="shareEpisode('linkedin')"
                                        class="w-full px-4 py-2 text-left hover:bg-gray-100 flex items-center">
                                        <i class="fab fa-linkedin text-blue-700 mr-2"></i>LinkedIn
                                    </button>
                                    <button wire:click="shareEpisode('whatsapp')"
                                        class="w-full px-4 py-2 text-left hover:bg-gray-100 flex items-center">
                                        <i class="fab fa-whatsapp text-green-500 mr-2"></i>WhatsApp
                                    </button>
                                    <button wire:click="shareEpisode('telegram')"
                                        class="w-full px-4 py-2 text-left hover:bg-gray-100 flex items-center">
                                        <i class="fab fa-telegram text-blue-400 mr-2"></i>Telegram
                                    </button>
                                    <button wire:click="shareEpisode('reddit')"
                                        class="w-full px-4 py-2 text-left hover:bg-gray-100 flex items-center">
                                        <i class="fab fa-reddit-alien text-orange-500 mr-2"></i>Reddit
                                    </button>
                                    <button wire:click="shareEpisode('email')"
                                        class="w-full px-4 py-2 text-left hover:bg-gray-100 flex items-center">
                                        <i class="fas fa-envelope text-gray-600 mr-2"></i>Email
                                    </button>
                                    <button type="button" data-copy-link="{{ url()->current() }}"
                                        class="w-full px-4 py-2 text-left hover:bg-gray-100 rounded-b-lg flex items-center">
                                        <i class="fas fa-link text-gray-600 mr-2"></i><span data-copy-text>Copy link</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="text-sm text-gray-600">
                            <i class="fas fa-file-audio mr-1"></i>
                            {{ $episode->file_size_formatted }} • {{ strtoupper($episode->audio_format ?? 'MP3') }}
                        </div>
                    </div>

                    @if (session()->has('success'))
                        <div class="mt-4 p-3 bg-green-100 text-green-700 rounded-lg flash-auto-dismiss">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

<!-- Video Player (if video exists) -->
@if($episode->has_video)
<section class="py-8 bg-gray-100">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-video text-purple-600 mr-3"></i>
                Watch Video
            </h2>
            
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                @if($episode->video_type === 'youtube' && $episode->youtube_video_id)
                    <!-- YouTube Embed -->
                    <div class="relative pb-[56.25%]">
                        <button type="button"
                            class="absolute inset-0 z-10 flex items-center justify-center bg-black/60 text-white"
                            data-video-overlay>
                            <span class="inline-flex items-center px-5 py-3 bg-purple-600 hover:bg-purple-700 rounded-full font-semibold">
                                <i class="fas fa-play mr-2"></i>Start Video
                            </span>
                        </button>
                        <iframe
                            class="absolute top-0 left-0 w-full h-full hidden"
                            src="about:blank"
                            data-src="https://www.youtube.com/embed/{{ $episode->youtube_video_id }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            data-video-iframe>
                        </iframe>
                    </div>
                @elseif($episode->video_type === 'vimeo')
                    <!-- Vimeo Embed -->
                    <div class="relative pb-[56.25%]">
                        <button type="button"
                            class="absolute inset-0 z-10 flex items-center justify-center bg-black/60 text-white"
                            data-video-overlay>
                            <span class="inline-flex items-center px-5 py-3 bg-purple-600 hover:bg-purple-700 rounded-full font-semibold">
                                <i class="fas fa-play mr-2"></i>Start Video
                            </span>
                        </button>
                        <iframe
                            class="absolute top-0 left-0 w-full h-full hidden"
                            src="about:blank"
                            data-src="{{ str_replace('vimeo.com/', 'player.vimeo.com/video/', $episode->video_url) }}"
                            frameborder="0"
                            allow="autoplay; fullscreen; picture-in-picture"
                            allowfullscreen
                            data-video-iframe>
                        </iframe>
                    </div>
                @elseif($episode->video_type === 'upload')
                    <!-- Uploaded Video -->
                    <video id="podcastVideoPlayer" class="w-full" controls>
                        <source src="{{ $episode->video_url }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @else
                    <!-- Other Video Link -->
                    <div class="p-8 text-center">
                        <i class="fas fa-external-link-alt text-4xl text-purple-600 mb-4"></i>
                        <p class="text-gray-700 mb-4">Video available on external platform</p>
                        <a href="{{ $episode->video_url }}" target="_blank" data-video-external
                           class="inline-block px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors">
                            <i class="fas fa-play mr-2"></i>Watch Video
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

<!-- Platform Links (if any exist) -->
@if(!empty($episode->platform_links))
<section class="py-8 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-headphones text-purple-600 mr-3"></i>
                Listen On Other Platforms
            </h2>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach($episode->platform_links as $platform => $url)
                <a href="{{ $url }}" target="_blank" 
                   class="bg-white border-2 border-gray-200 hover:border-purple-600 rounded-xl p-6 text-center transition-all duration-300 group">
                    <div class="mb-3">
                        @switch($platform)
                            @case('spotify')
                                <i class="fab fa-spotify text-4xl text-green-500 group-hover:scale-110 transition-transform"></i>
                                @break
                            @case('apple')
                                <i class="fab fa-apple text-4xl text-gray-700 group-hover:scale-110 transition-transform"></i>
                                @break
                            @case('youtube_music')
                                <i class="fab fa-youtube text-4xl text-red-500 group-hover:scale-110 transition-transform"></i>
                                @break
                            @case('audiomack')
                                <i class="fas fa-music text-4xl text-orange-500 group-hover:scale-110 transition-transform"></i>
                                @break
                            @case('soundcloud')
                                <i class="fab fa-soundcloud text-4xl text-orange-600 group-hover:scale-110 transition-transform"></i>
                                @break
                            @default
                                <i class="fas fa-podcast text-4xl text-purple-600 group-hover:scale-110 transition-transform"></i>
                        @endswitch
                    </div>
                    <p class="text-sm font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $platform)) }}</p>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Main Column -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Show Notes -->
                @if($episode->show_notes)
                    <section class="bg-white rounded-xl shadow-md p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-sticky-note text-purple-600 mr-3"></i>
                            Show Notes
                        </h2>
                        <div class="prose prose-lg max-w-none">
                            {!! nl2br(e($episode->show_notes)) !!}
                        </div>
                    </section>
                @endif

                <!-- Chapters -->
                @if($episode->chapters && count($episode->chapters) > 0)
                    <section class="bg-white rounded-xl shadow-md p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-list-ol text-purple-600 mr-3"></i>
                            Chapters
                        </h2>
                        <div class="space-y-3">
                            @foreach($episode->chapters as $chapter)
                                @continueIfNotArray($chapter)
                                <div class="flex items-start space-x-3 p-3 hover:bg-purple-50 rounded-lg cursor-pointer transition-colors"
                                    onclick="document.getElementById('podcastPlayer').currentTime = {{ $chapter['time'] }}">
                                    <span
                                        class="font-mono text-sm text-purple-600 font-semibold">{{ gmdate('H:i:s', $chapter['time']) }}</span>
                                    <p class="flex-1 text-gray-700">{{ $chapter['title'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                <!-- Transcript -->
                @if($episode->transcript && count($episode->transcript) > 0)
                    <section class="bg-white rounded-xl shadow-md p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-file-alt text-purple-600 mr-3"></i>
                            Transcript
                        </h2>
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            @foreach($episode->transcript as $line)
                                @continueIfNotArray($line)
                                <div class="flex items-start space-x-3">
                                    @if(isset($line['time']))
                                        <span class="font-mono text-xs text-gray-500">{{ gmdate('H:i:s', $line['time']) }}</span>
                                    @endif
                                    <p class="flex-1 text-gray-700">{{ $line['text'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                <!-- Comments Section -->
                <section class="bg-white rounded-xl shadow-md p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-comments text-purple-600 mr-3"></i>
                        Comments ({{ $episode->comments->count() }})
                    </h2>

                    <!-- Comment Form -->
                    <form wire:submit.prevent="submitComment" class="mb-8">
                        <div class="bg-purple-50 rounded-xl p-6">
                            <div class="flex items-center justify-between mb-4">
                                <label class="font-semibold text-gray-900">Leave a Comment</label>
                                @if($commentTimestamp)
                                    <button type="button" wire:click="$set('commentTimestamp', null)"
                                        class="text-sm text-purple-600 hover:text-purple-700">
                                        <i class="fas fa-times mr-1"></i>Clear timestamp
                                    </button>
                                @endif
                            </div>

                            @if($commentTimestamp)
                                <div class="mb-3 text-sm text-purple-600">
                                    <i class="fas fa-clock mr-1"></i>
                                    Comment will be posted at {{ gmdate('H:i:s', $commentTimestamp) }}
                                </div>
                            @endif

                            <textarea wire:model="comment" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 resize-none"
                                placeholder="Share your thoughts..."></textarea>
                            @error('comment') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

                            <div class="mt-4 flex items-center justify-between">
                                @if($episode->audio_file)
                                    <button type="button" onclick="addTimestamp()"
                                        class="text-sm text-purple-600 hover:text-purple-700 font-semibold">
                                        <i class="fas fa-clock mr-1"></i>Add Current Timestamp
                                    </button>
                                @else
                                    <span class="text-sm text-gray-400">Timestamps available for audio only.</span>
                                @endif
                                <button type="submit"
                                    class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors">
                                    <i class="fas fa-paper-plane mr-2"></i>Post Comment
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Comments List -->
                    <div class="space-y-6">
                        @forelse($episode->comments as $comment)
                            <div class="bg-gray-50 rounded-xl p-6">
                                <div class="flex items-start space-x-4">
                                    <img src="{{ $comment->user?->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($comment->user?->name ?? 'Anonymous') }}"
                                        class="w-12 h-12 rounded-full">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <h4 class="font-bold text-gray-900">{{ $comment->user?->name ?? 'Anonymous' }}</h4>
                                                <p class="text-sm text-gray-500">
                                                    {{ $comment->created_at->diffForHumans() }}
                                                    @if($comment->timestamp)
                                                        <span class="ml-2">
                                                            <i
                                                                class="fas fa-clock mr-1"></i>{{ gmdate('H:i:s', $comment->timestamp) }}
                                                        </span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <p class="text-gray-700 mb-3">{{ $comment->comment }}</p>

                                        @if($comment->replies->count() > 0)
                                            <div class="mt-4 pl-4 border-l-2 border-purple-200 space-y-4">
                                                @foreach($comment->replies as $reply)
                                                    <div class="flex items-start space-x-3">
                                                        <img src="{{ $reply->user?->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($reply->user?->name ?? 'Anonymous') }}"
                                                            class="w-10 h-10 rounded-full">
                                                        <div class="flex-1">
                                                            <h5 class="font-semibold text-gray-900">{{ $reply->user?->name ?? 'Anonymous' }}</h5>
                                                            <p class="text-xs text-gray-500 mb-1">
                                                                {{ $reply->created_at->diffForHumans() }}</p>
                                                            <p class="text-gray-700">{{ $reply->comment }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-comments text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-600">No comments yet. Be the first to share your thoughts!</p>
                            </div>
                        @endforelse
                    </div>
                </section>

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">

                <!-- About Show -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="font-bold text-gray-900 mb-4">About This Show</h3>
                    <a href="{{ route('podcasts.show', $episode->show->slug) }}" class="block mb-4">
                        <img src="{{ $episode->show->cover_image }}"
                            class="w-full rounded-lg hover:opacity-90 transition-opacity">
                    </a>
                    <h4 class="text-lg font-bold text-gray-900 mb-2">
                        <a href="{{ route('podcasts.show', $episode->show->slug) }}" class="hover:text-purple-600">
                            {{ $episode->show->title }}
                        </a>
                    </h4>
                    <p class="text-sm text-gray-600 mb-3">{{ Str::limit($episode->show->description, 150) }}</p>
                    <div class="text-xs text-gray-500 space-y-1">
                        <p><i class="fas fa-microphone mr-1"></i>{{ $episode->show->host_name }}</p>
                        <p><i class="fas fa-list mr-1"></i>{{ $episode->show->total_episodes }} episodes</p>
                        <p><i class="fas fa-users mr-1"></i>{{ number_format($episode->show->subscribers) }} subscribers
                        </p>
                    </div>
                </div>

                <!-- More Episodes -->
                @if($relatedEpisodes->count() > 0)
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="font-bold text-gray-900 mb-4">More Episodes</h3>
                        <div class="space-y-4">
                            @foreach($relatedEpisodes as $related)
                                <a href="{{ route('podcasts.episode', [$episode->show->slug, $related->slug]) }}"
                                    class="block group">
                                    <div class="flex items-start space-x-3">
                                        <img src="{{ $related->cover_image ?? $episode->show->cover_image }}"
                                            class="w-16 h-16 rounded-lg object-cover">
                                        <div class="flex-1 min-w-0">
                                            <h4
                                                class="text-sm font-semibold text-gray-900 group-hover:text-purple-600 line-clamp-2 transition-colors">
                                                {{ $related->title }}
                                            </h4>
                                            <p class="text-xs text-gray-500 mt-1">{{ $related->formatted_duration }}</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    @if($episode->audio_file || $episode->has_video)
        <!-- JavaScript for Player -->
        <script>
            (function () {
                let lastTracked = 0;
                let qualifiedRecorded = false;
                let rawRecorded = false;

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
                    });

                    player.addEventListener('timeupdate', function () {
                        const currentTime = Math.floor(player.currentTime);
                        const duration = Math.floor(player.duration);

                        if (!qualifiedRecorded && currentTime >= 10) {
                            recordQualifiedPlay();
                        }

                        // Track progress every 30 seconds
                        if (currentTime > 0 && currentTime % 30 === 0 && currentTime !== lastTracked) {
                            @this.call('updateProgress', currentTime, duration);
                            lastTracked = currentTime;
                        }
                    });

                    // Resume from last position
                    @if($currentPosition > 0)
                        player.addEventListener('loadedmetadata', function () {
                            player.currentTime = {{ $currentPosition }};
                        });
                    @endif
                }

                const videoPlayer = document.getElementById('podcastVideoPlayer');
                if (videoPlayer) {
                    videoPlayer.addEventListener('play', function () {
                        recordRawPlay();
                    });

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
                    });
                }

                const overlay = document.querySelector('[data-video-overlay]');
                const iframe = document.querySelector('[data-video-iframe]');
                if (overlay && iframe) {
                    overlay.addEventListener('click', function () {
                        const src = iframe.getAttribute('data-src');
                        if (src) {
                            iframe.setAttribute('src', src);
                        }
                        overlay.classList.add('hidden');
                        iframe.classList.remove('hidden');
                        recordRawPlay();
                        recordQualifiedPlay();
                    }, { once: true });
                }

                const externalVideo = document.querySelector('[data-video-external]');
                if (externalVideo) {
                    externalVideo.addEventListener('click', function () {
                        recordRawPlay();
                        recordQualifiedPlay();
                    }, { once: true });
                }

                window.addTimestamp = function () {
                    if (!player) return;
                    const currentTime = Math.floor(player.currentTime);
                    @this.call('setCommentTime', currentTime);
                };
            })();
        </script>
    @endif

</div>
