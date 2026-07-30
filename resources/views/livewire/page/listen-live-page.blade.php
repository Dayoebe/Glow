<div class="bg-[#faf8f4] text-slate-950">
    <section class="relative overflow-hidden bg-[#171742] text-white">
        <div class="absolute -right-28 -top-28 h-96 w-96 rounded-full bg-orange-500/20 blur-3xl"></div>
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 sm:py-18 lg:grid-cols-[minmax(0,1fr)_28rem] lg:items-center lg:px-8 lg:py-20">
            <div class="relative z-10">
                <p class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.28em] text-orange-300">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-60 motion-reduce:animate-none"></span>
                        <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-red-500"></span>
                    </span>
                    Live from Akure
                </p>
                <h1 class="mt-4 max-w-3xl text-5xl font-black leading-[0.95] tracking-[-0.04em] sm:text-7xl">
                    This is {{ $station['display_name'] }}.
                </h1>
                <p class="mt-5 max-w-2xl text-base leading-7 text-slate-300 sm:text-lg">
                    News, conversation, music and the voices of Ondo State—broadcast live on {{ $station['display_frequency'] }} and everywhere you are.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <button type="button" @click="$store.radio.toggle()"
                        class="inline-flex min-w-40 items-center justify-center gap-3 rounded-xl bg-orange-500 px-6 py-3.5 text-sm font-black text-white transition hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-300">
                        <i x-show="!$store.radio.audioPlaying" class="fas fa-play" aria-hidden="true"></i>
                        <i x-cloak x-show="$store.radio.audioPlaying" class="fas fa-pause" aria-hidden="true"></i>
                        <span x-text="$store.radio.audioPlaying ? 'Pause live radio' : 'Play live radio'">Play live radio</span>
                    </button>
                    <a href="{{ route('schedule') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-white/20 px-5 py-3.5 text-sm font-bold transition hover:bg-white/10">
                        Weekly schedule
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

            <div wire:poll.visible.60s="refreshSchedule"
                class="relative z-10 overflow-hidden rounded-3xl border border-white/10 bg-white/[0.07] p-6 shadow-2xl shadow-black/20 sm:p-8">
                <div class="flex items-start gap-5">
                    <div class="relative h-20 w-20 shrink-0 overflow-hidden rounded-2xl bg-white p-2">
                        <img src="{{ $station['logo'] }}" alt="{{ $station['display_name'] }} logo" width="80" height="80"
                            loading="eager" decoding="async" class="h-full w-full object-contain">
                    </div>
                    <div class="min-w-0">
                        <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-orange-300">On air now</p>
                        <h2 class="mt-2 text-2xl font-black leading-tight">
                            {{ $currentSlot?->show?->title ?? 'Glow FM Live' }}
                        </h2>
                        <p class="mt-1 truncate text-sm text-slate-300">{{ $currentSlot?->oap?->name ?? $station['tagline'] }}</p>
                    </div>
                </div>

                <div class="mt-7 flex items-center gap-4">
                    <button type="button" @click="$store.radio.toggle()"
                        class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-white text-[#171742] transition hover:scale-105"
                        aria-label="Toggle live radio">
                        <i class="fas ml-0.5" :class="$store.radio.audioPlaying ? 'fa-pause' : 'fa-play'"></i>
                    </button>
                    <div class="min-w-0 flex-1">
                        <div class="h-1.5 overflow-hidden rounded-full bg-white/15">
                            <div class="h-full w-full bg-gradient-to-r from-orange-500 to-orange-300"></div>
                        </div>
                        <div class="mt-2 flex justify-between text-[11px] font-semibold text-slate-400">
                            <span>{{ $currentSlot?->time_range ?? 'Live now' }}</span>
                            <span>LIVE</span>
                        </div>
                    </div>
                </div>

                @if($upcomingSlots->first())
                    <div class="mt-7 border-t border-white/10 pt-5">
                        <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-slate-400">Up next</p>
                        <div class="mt-3 flex items-end justify-between gap-4">
                            <div>
                                <p class="font-bold">{{ $upcomingSlots->first()->show?->title ?? 'Programme TBA' }}</p>
                                <p class="mt-1 text-xs text-slate-400">{{ $upcomingSlots->first()->oap?->name ?? 'Host TBA' }}</p>
                            </div>
                            <span class="shrink-0 text-xs font-bold text-orange-300">{{ $upcomingSlots->first()->time_range }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
        <div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_20rem]">
            <main>
                <div class="flex items-end justify-between gap-4 border-b border-slate-200 pb-5">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-orange-600">Today on Glow</p>
                        <h2 class="mt-2 text-3xl font-black tracking-[-0.025em] text-[#171742]">Coming up next</h2>
                    </div>
                    <a href="{{ route('schedule') }}" class="hidden text-sm font-bold text-orange-600 hover:text-orange-700 sm:inline">
                        Full week <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>

                <div class="divide-y divide-slate-200">
                    @forelse($upcomingSlots as $slot)
                        <article class="grid gap-3 py-5 sm:grid-cols-[8rem_minmax(0,1fr)_auto] sm:items-center sm:gap-5">
                            <p class="text-sm font-black text-orange-600">{{ $slot->time_range }}</p>
                            <div class="min-w-0">
                                <h3 class="text-lg font-black text-[#171742]">
                                    @if($slot->show?->slug)
                                        <a href="{{ route('shows.show', $slot->show->slug) }}" class="hover:text-orange-600">
                                            {{ $slot->show->title }}
                                        </a>
                                    @else
                                        Programme TBA
                                    @endif
                                </h3>
                                <p class="mt-1 text-sm text-slate-500">{{ $slot->oap?->name ?? 'Host TBA' }}</p>
                            </div>
                            <span class="w-fit rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-600">
                                {{ ucfirst($slot->show?->format ?? 'Live') }}
                            </span>
                        </article>
                    @empty
                        <div class="py-12 text-center">
                            <i class="fas fa-moon text-3xl text-slate-300"></i>
                            <p class="mt-4 font-bold text-[#171742]">No more listed programmes today</p>
                            <p class="mt-2 text-sm text-slate-500">The live stream continues. Check the weekly schedule for tomorrow’s lineup.</p>
                        </div>
                    @endforelse
                </div>
            </main>

            <aside class="h-fit rounded-2xl border border-slate-200 bg-white p-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-orange-600">Tune in</p>
                <p class="mt-3 text-4xl font-black tracking-tight text-[#171742]">{{ $station['display_frequency'] }}</p>
                <dl class="mt-6 space-y-4 text-sm">
                    <div class="border-t border-slate-100 pt-4">
                        <dt class="font-bold text-slate-400">Broadcasting from</dt>
                        <dd class="mt-1 leading-6 text-slate-700">{{ $station['address'] }}</dd>
                    </div>
                    <div class="border-t border-slate-100 pt-4">
                        <dt class="font-bold text-slate-400">Call the studio</dt>
                        <dd class="mt-1"><a href="tel:{{ $station['phone'] }}" class="font-semibold text-orange-600 hover:text-orange-700">{{ $station['phone'] }}</a></dd>
                    </div>
                    <div class="border-t border-slate-100 pt-4">
                        <dt class="font-bold text-slate-400">Email</dt>
                        <dd class="mt-1"><a href="mailto:{{ $station['email'] }}" class="break-all font-semibold text-orange-600 hover:text-orange-700">{{ $station['email'] }}</a></dd>
                    </div>
                </dl>
                <a href="{{ route('podcasts.index') }}"
                    class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#171742] px-4 py-3 text-sm font-bold text-white transition hover:bg-[#23235b]">
                    <i class="fas fa-podcast text-orange-400"></i>
                    Listen back
                </a>
            </aside>
        </div>
    </section>
</div>
