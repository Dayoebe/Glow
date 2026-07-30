<div class="bg-[#faf8f4] text-slate-950">
    @php
        $today = strtolower(now('Africa/Lagos')->format('l'));
        $dayLabels = [
            'monday' => 'Mon', 'tuesday' => 'Tue', 'wednesday' => 'Wed',
            'thursday' => 'Thu', 'friday' => 'Fri', 'saturday' => 'Sat', 'sunday' => 'Sun',
        ];
    @endphp

    <section class="bg-[#171742] text-white">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-18">
            <x-ad-slot placement="schedule" />
            <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-end">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.28em] text-orange-300">99.1 FM Akure</p>
                    <h1 class="mt-4 text-4xl font-black tracking-[-0.035em] sm:text-6xl">Weekly schedule</h1>
                    <p class="mt-4 max-w-2xl text-base leading-7 text-slate-300 sm:text-lg">
                        Plan your listening week. All programme times are shown in West Africa Time.
                    </p>
                </div>
                <button type="button" @click="$store.radio.start()"
                    class="inline-flex w-fit items-center gap-2 rounded-xl bg-orange-500 px-5 py-3 text-sm font-bold text-white transition hover:bg-orange-600">
                    <i class="fas fa-play text-xs"></i>
                    Listen live
                </button>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
        <div class="overflow-x-auto border-b border-slate-200">
            <nav class="flex min-w-max gap-1" aria-label="Choose schedule day">
                @foreach($scheduleByDay as $day => $slots)
                    <a href="{{ route('schedule', ['day' => $day]) }}"
                        @if($selectedDay === $day) aria-current="page" @endif
                        class="relative px-4 py-3 text-sm font-bold transition sm:px-5 {{ $selectedDay === $day ? 'text-orange-600' : 'text-slate-500 hover:text-[#171742]' }}">
                        <span>{{ $dayLabels[$day] ?? ucfirst($day) }}</span>
                        @if($day === $today)
                            <span class="ml-1 text-[9px] font-black uppercase tracking-wider">Today</span>
                        @endif
                        @if($selectedDay === $day)
                            <span class="absolute inset-x-2 bottom-0 h-0.5 bg-orange-500"></span>
                        @endif
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="pt-8">
            <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-orange-600">{{ $selectedDay === $today ? 'Today on Glow' : 'Programme lineup' }}</p>
                    <h2 class="mt-2 text-3xl font-black tracking-[-0.025em] text-[#171742]">{{ ucfirst($selectedDay) }}</h2>
                </div>
                <p class="text-sm text-slate-500">{{ $activeSlots->count() }} {{ \Illuminate\Support\Str::plural('programme', $activeSlots->count()) }}</p>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                @forelse($activeSlots as $slot)
                    <article class="grid gap-3 border-b border-slate-100 px-5 py-5 last:border-0 sm:grid-cols-[9rem_minmax(0,1fr)_auto] sm:items-center sm:gap-6 sm:px-6">
                        <div>
                            <p class="text-sm font-black text-orange-600">{{ $slot->time_range }}</p>
                            <p class="mt-1 text-[11px] font-semibold uppercase tracking-wider text-slate-400">WAT</p>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-lg font-black text-[#171742]">
                                @if($slot->show?->slug)
                                    <a href="{{ route('shows.show', $slot->show->slug) }}" class="transition hover:text-orange-600">
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
                    <div class="px-6 py-14 text-center">
                        <i class="fas fa-calendar-xmark text-3xl text-slate-300"></i>
                        <p class="mt-4 font-bold text-[#171742]">No programmes listed</p>
                        <p class="mt-2 text-sm text-slate-500">The live stream may still be broadcasting. Check another day or listen now.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
