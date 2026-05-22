<div class="bg-gray-50">
    <section class="bg-emerald-800 text-white">
        <div class="container mx-auto grid gap-10 px-4 py-16 lg:grid-cols-[1fr_24rem] lg:items-center">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-emerald-100">Live Radio</p>
                <h1 class="mt-4 text-4xl font-black leading-tight md:text-6xl">Listen Live To {{ $station['name'] }}</h1>
                <p class="mt-5 max-w-3xl text-lg leading-relaxed text-emerald-50">
                    Stream {{ $station['name'] }} from Akure, Ondo State, Nigeria. Tune in for Ondo State news,
                    public affairs, Yoruba programs, entertainment, sports, interviews, and community updates.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <button type="button" @click.prevent="$store.radio.start()"
                        class="inline-flex items-center rounded-full bg-white px-6 py-3 font-bold text-emerald-800 shadow-lg transition hover:bg-emerald-50">
                        <i class="fas fa-play-circle mr-2"></i>
                        Start Live Stream
                    </button>
                    <a href="{{ route('schedule') }}"
                        class="inline-flex items-center rounded-full border border-white/40 px-6 py-3 font-semibold text-white transition hover:bg-white/10">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        View Schedule
                    </a>
                </div>
            </div>

            <div class="rounded-2xl border border-white/15 bg-white/10 p-6 shadow-2xl backdrop-blur">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-100">On Air</p>
                <div class="mt-5 flex items-center gap-4">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-emerald-700">
                        <i class="fas fa-radio text-3xl"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-black">{{ $station['frequency'] }}</p>
                        <p class="text-sm text-emerald-100">{{ $station['tagline'] }}</p>
                    </div>
                </div>
                <div class="mt-6">
                    <audio controls preload="none" class="w-full">
                        <source src="{{ $station['stream_url'] }}" type="audio/mpeg">
                        Your browser does not support the live radio audio player.
                    </audio>
                </div>
                <p class="mt-4 text-sm text-emerald-50">
                    Online stream: <a href="{{ $station['stream_url'] }}" class="font-semibold underline">{{ $station['stream_url'] }}</a>
                </p>
            </div>
        </div>
    </section>

    <section class="py-14">
        <div class="container mx-auto grid gap-8 px-4 lg:grid-cols-[1fr_22rem]">
            <div class="rounded-2xl bg-white p-6 shadow-lg">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-700">Today</p>
                        <h2 class="mt-2 text-3xl font-bold text-slate-900">Today&apos;s Schedule</h2>
                    </div>
                    <a href="{{ route('schedule') }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                        Full weekly schedule
                    </a>
                </div>

                <div class="mt-6 space-y-3">
                    @forelse($upcomingSlots as $slot)
                        <div class="flex flex-col gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm font-semibold text-emerald-700">{{ $slot->time_range }} WAT</p>
                                <h3 class="mt-1 text-lg font-bold text-slate-900">
                                    <a href="{{ route('shows.show', $slot->show->slug) }}" class="hover:text-emerald-700">
                                        {{ $slot->show->title }}
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm text-slate-600">{{ $slot->oap?->name ?? 'Host TBA' }}</p>
                            </div>
                            <span class="inline-flex w-fit rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                {{ ucfirst($slot->show->format) }}
                            </span>
                        </div>
                    @empty
                        <p class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-5 text-slate-600">
                            No public schedule slots are listed for today.
                        </p>
                    @endforelse
                </div>
            </div>

            <aside class="space-y-4">
                <div class="rounded-2xl bg-white p-6 shadow-lg">
                    <h2 class="text-xl font-bold text-slate-900">Station Details</h2>
                    <dl class="mt-5 space-y-3 text-sm">
                        <div>
                            <dt class="font-semibold text-slate-500">Frequency</dt>
                            <dd class="mt-1 text-slate-900">{{ $station['frequency'] }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-slate-500">Location</dt>
                            <dd class="mt-1 text-slate-900">{{ $station['address'] }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-slate-500">Phone</dt>
                            <dd class="mt-1">
                                <a href="tel:{{ $station['phone'] }}" class="text-emerald-700 hover:text-emerald-800">{{ $station['phone'] }}</a>
                            </dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-slate-500">Email</dt>
                            <dd class="mt-1">
                                <a href="mailto:{{ $station['email'] }}" class="break-all text-emerald-700 hover:text-emerald-800">{{ $station['email'] }}</a>
                            </dd>
                        </div>
                    </dl>
                </div>
                <div class="rounded-2xl bg-slate-900 p-6 text-white shadow-lg">
                    <h2 class="text-xl font-bold">Listen Elsewhere</h2>
                    <p class="mt-3 text-sm leading-relaxed text-slate-300">
                        Search for Glow 99.1 FM or Glow FM Akure on supported radio streaming directories and podcast platforms.
                    </p>
                    <a href="{{ route('podcasts.index') }}"
                        class="mt-5 inline-flex items-center rounded-full bg-emerald-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-600">
                        <i class="fas fa-podcast mr-2"></i>
                        Podcasts
                    </a>
                </div>
            </aside>
        </div>
    </section>
</div>
