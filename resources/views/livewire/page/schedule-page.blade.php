<div>
    @php
        $today = strtolower(now('Africa/Lagos')->format('l'));
    @endphp

    <section class="relative bg-gradient-to-br from-emerald-700 via-emerald-800 to-teal-800 text-white py-16">
        <div class="container mx-auto px-4">
            <x-ad-slot placement="schedule" />
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Weekly Schedule</h1>
                <p class="text-lg md:text-xl text-emerald-100">Your weekly guide to every show on Glow FM.</p>
            </div>
        </div>
    </section>

    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4" x-data="{ openDay: '{{ $today }}' }">
            <div class="mx-auto max-w-5xl space-y-4">
                @foreach($scheduleByDay as $day => $slots)
                    <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-lg shadow-slate-200/70">
                        <button
                            type="button"
                            class="flex w-full items-center justify-between gap-4 px-5 py-5 text-left transition hover:bg-emerald-50/70 sm:px-6"
                            @click="openDay = openDay === '{{ $day }}' ? null : '{{ $day }}'"
                            :aria-expanded="openDay === '{{ $day }}'"
                        >
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-xl font-bold text-slate-900">{{ ucfirst($day) }}</h3>
                                    @if($day === $today)
                                        <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-emerald-700">
                                            Today
                                        </span>
                                    @endif
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                        {{ $slots->count() }} {{ \Illuminate\Support\Str::plural('show', $slots->count()) }}
                                    </span>
                                </div>
                                <p class="mt-2 text-sm text-slate-500">
                                    {{ $slots->isNotEmpty() ? 'Tap to view all shows for ' . ucfirst($day) . '.' : 'No shows scheduled for ' . ucfirst($day) . '.' }}
                                </p>
                            </div>

                            <span
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-slate-100 text-slate-600 transition"
                                :class="openDay === '{{ $day }}' ? 'rotate-180 bg-emerald-100 text-emerald-700' : ''"
                            >
                                <i class="fas fa-chevron-down text-sm"></i>
                            </span>
                        </button>

                        <div x-cloak x-show="openDay === '{{ $day }}'" x-collapse>
                            <div class="border-t border-slate-200 px-5 pb-5 pt-4 sm:px-6 sm:pb-6">
                                <div class="space-y-3">
                                    @forelse($slots as $slot)
                                        <div class="flex flex-col gap-4 rounded-[1.5rem] bg-slate-50 p-4 sm:flex-row sm:items-start sm:justify-between">
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-emerald-700">{{ $slot->time_range }}</p>
                                                <p class="mt-1 text-lg font-semibold text-slate-900">
                                                    <a href="{{ route('shows.show', $slot->show->slug) }}" class="transition hover:text-emerald-600">
                                                        {{ $slot->show->title }}
                                                    </a>
                                                </p>
                                                <p class="mt-1 text-sm text-slate-600">{{ $slot->oap?->name ?? 'Host TBA' }}</p>
                                            </div>
                                            <span class="inline-flex w-fit rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                {{ ucfirst($slot->show->format) }}
                                            </span>
                                        </div>
                                    @empty
                                        <div class="rounded-[1.5rem] border border-dashed border-slate-200 bg-slate-50 px-4 py-5 text-sm text-slate-500">
                                            No shows scheduled.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
