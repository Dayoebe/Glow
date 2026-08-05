<div class="space-y-6">
    @php
        $tabs = [
            ['key' => 'shows', 'label' => 'Shows', 'icon' => 'fa-radio', 'route' => route('admin.shows.index'), 'count' => $stats['total_shows']],
            ['key' => 'oaps', 'label' => 'OAPs', 'icon' => 'fa-microphone-lines', 'route' => route('admin.shows.oaps'), 'count' => $stats['total_oaps']],
            ['key' => 'schedule', 'label' => 'Schedule', 'icon' => 'fa-calendar-days', 'route' => route('admin.shows.schedule'), 'count' => $stats['active_slots']],
            ['key' => 'segments', 'label' => 'Segments', 'icon' => 'fa-list-ol', 'route' => route('admin.shows.segments'), 'count' => $stats['total_segments']],
            ['key' => 'categories', 'label' => 'Categories', 'icon' => 'fa-layer-group', 'route' => route('admin.shows.categories'), 'count' => $stats['total_categories']],
            ['key' => 'reviews', 'label' => 'Reviews', 'icon' => 'fa-star', 'route' => route('admin.shows.reviews'), 'count' => $stats['total_reviews']],
        ];

        $actions = [
            'shows' => ['route' => route('admin.shows.create'), 'label' => 'Create show'],
            'oaps' => ['route' => route('admin.shows.oaps.create'), 'label' => 'Add OAP'],
            'schedule' => ['route' => route('admin.shows.schedule.create'), 'label' => 'Add slot'],
            'segments' => ['route' => route('admin.shows.segments.create'), 'label' => 'Add segment'],
            'categories' => ['route' => route('admin.shows.categories.create'), 'label' => 'Add category'],
        ];
        $action = $actions[$view];

        $viewCopy = [
            'shows' => ['eyebrow' => 'Programme library', 'title' => 'Build shows people remember.', 'description' => 'Shape every programme, host pairing and format from one broadcast-ready workspace.'],
            'oaps' => ['eyebrow' => 'On-air talent', 'title' => 'Put the right voices on air.', 'description' => 'See talent availability, assignments and programme workload at a glance.'],
            'schedule' => ['eyebrow' => 'Broadcast clock', 'title' => 'Make every hour intentional.', 'description' => 'Review the weekly run, spot gaps and keep show and presenter assignments clear.'],
            'segments' => ['eyebrow' => 'Show rundown', 'title' => 'Design the rhythm of every show.', 'description' => 'Organize intros, interviews, music, calls and breaks into reliable rundowns.'],
            'categories' => ['eyebrow' => 'Content structure', 'title' => 'Keep the programme library organised.', 'description' => 'Create clear editorial lanes that make shows easier to manage and discover.'],
        ][$view];
    @endphp

    <section class="relative overflow-hidden rounded-2xl bg-[#0b2f3a] px-6 py-7 text-white shadow-sm sm:px-8">
        <div class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-emerald-400/10"></div>
        <div class="pointer-events-none absolute -bottom-28 right-32 h-60 w-60 rounded-full bg-orange-400/10"></div>

        <div class="relative flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div class="max-w-2xl">
                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-emerald-300">{{ $viewCopy['eyebrow'] }}</p>
                <h2 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl">{{ $viewCopy['title'] }}</h2>
                <p class="mt-3 max-w-xl text-sm leading-6 text-slate-300">{{ $viewCopy['description'] }}</p>
            </div>
            <a href="{{ $action['route'] }}" class="inline-flex w-full items-center justify-center rounded-xl bg-[#ed5a1f] px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-black/10 transition hover:bg-[#d94d16] sm:w-auto">
                <i class="fas fa-plus mr-2" aria-hidden="true"></i>{{ $action['label'] }}
            </a>
        </div>

        <div class="relative mt-7 grid grid-cols-2 gap-3 lg:grid-cols-4">
            @foreach([
                ['label' => 'Active shows', 'value' => $stats['active_shows'], 'icon' => 'fa-tower-broadcast'],
                ['label' => 'Active OAPs', 'value' => $stats['active_oaps'], 'icon' => 'fa-microphone'],
                ['label' => 'Live schedule slots', 'value' => $stats['active_slots'], 'icon' => 'fa-clock'],
                ['label' => 'Audience reviews', 'value' => $stats['total_reviews'], 'icon' => 'fa-star'],
            ] as $stat)
                <div class="rounded-xl border border-white/10 bg-white/[0.07] p-4 backdrop-blur-sm">
                    <div class="flex items-center justify-between gap-3">
                        <div><p class="text-2xl font-black">{{ number_format($stat['value']) }}</p><p class="mt-1 text-xs font-semibold text-slate-300">{{ $stat['label'] }}</p></div>
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/10 text-emerald-300"><i class="fas {{ $stat['icon'] }}" aria-hidden="true"></i></span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <nav class="overflow-x-auto rounded-2xl border border-slate-200 bg-white p-2 shadow-sm" aria-label="Shows and programmes sections">
        <div class="flex min-w-max gap-1">
            @foreach($tabs as $tab)
                <a href="{{ $tab['route'] }}" class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold transition {{ $view === $tab['key'] ? 'bg-[#0b2f3a] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="fas {{ $tab['icon'] }} text-xs {{ $view === $tab['key'] ? 'text-emerald-300' : 'text-slate-400' }}" aria-hidden="true"></i>
                    {{ $tab['label'] }}
                    <span class="rounded-full px-2 py-0.5 text-[10px] font-extrabold {{ $view === $tab['key'] ? 'bg-white/10 text-white' : 'bg-slate-100 text-slate-500' }}">{{ number_format($tab['count']) }}</span>
                </a>
            @endforeach
        </div>
    </nav>

    <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div class="min-w-0 flex-1 lg:max-w-xl">
                <label for="programme-search" class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Search {{ $view }}</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400" aria-hidden="true"></i>
                    <input id="programme-search" type="search" wire:model.live.debounce.300ms="search" placeholder="Search this workspace…" class="w-full rounded-xl border-slate-300 py-2.5 pl-10 pr-4 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
            </div>

            @if($view === 'shows' || $view === 'schedule')
                <div class="grid grid-cols-2 gap-3 sm:w-auto">
                    <div>
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Sort by</label>
                        @if($view === 'shows')
                            <select wire:model.live="showSort" class="w-full rounded-xl border-slate-300 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500 sm:w-44">
                                <option value="latest">Newest</option><option value="title">Title</option><option value="host">Host</option><option value="category">Category</option><option value="day">Broadcast day</option><option value="duration">Duration</option><option value="featured">Featured</option>
                            </select>
                        @else
                            <select wire:model.live="scheduleSort" class="w-full rounded-xl border-slate-300 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500 sm:w-44">
                                <option value="day">Day</option><option value="show">Show</option><option value="host">Host</option><option value="time">Start time</option><option value="status">Status</option>
                            </select>
                        @endif
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Direction</label>
                        <select wire:model.live="{{ $view === 'shows' ? 'showSortDirection' : 'scheduleSortDirection' }}" class="w-full rounded-xl border-slate-300 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500 sm:w-32">
                            <option value="asc">Ascending</option><option value="desc">Descending</option>
                        </select>
                    </div>
                </div>
            @endif
        </div>
    </section>

    @if($view === 'shows')
        <section class="grid grid-cols-1 gap-5 md:grid-cols-2 2xl:grid-cols-3">
            @forelse($shows as $show)
                <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md" wire:key="show-{{ $show->id }}">
                    <div class="relative h-48 overflow-hidden bg-slate-100">
                        <x-initials-image :src="$show->cover_image" :title="$show->title" imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]" fallbackClass="bg-[#173b5f]" textClass="text-4xl font-black text-white" />
                        <div class="absolute inset-x-0 top-0 flex items-start justify-between p-3">
                            <span class="rounded-full bg-black/60 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wide text-white backdrop-blur">{{ $show->category?->name ?? 'Uncategorised' }}</span>
                            <div class="flex gap-2">
                                @if($show->is_featured)<span class="rounded-full bg-orange-500 px-2.5 py-1 text-[10px] font-extrabold uppercase text-white"><i class="fas fa-star mr-1"></i>Featured</span>@endif
                                <span class="rounded-full px-2.5 py-1 text-[10px] font-extrabold uppercase {{ $show->is_active ? 'bg-emerald-500 text-white' : 'bg-slate-700 text-slate-200' }}">{{ $show->is_active ? 'Active' : 'Inactive' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-5">
                        <h3 class="truncate text-xl font-black text-slate-900">{{ $show->title }}</h3>
                        <p class="mt-2 line-clamp-2 min-h-[40px] text-sm leading-5 text-slate-500">{{ $show->description ?: 'No programme description yet.' }}</p>
                        <div class="mt-4 grid grid-cols-3 divide-x divide-slate-200 rounded-xl bg-slate-50 py-3 text-center">
                            <div class="px-2"><p class="text-xs font-black text-slate-800">{{ $show->typical_duration }}m</p><p class="mt-0.5 text-[10px] text-slate-400">Duration</p></div>
                            <div class="px-2"><p class="text-xs font-black text-slate-800">{{ $show->schedule_slots_count }}</p><p class="mt-0.5 text-[10px] text-slate-400">Slots</p></div>
                            <div class="px-2"><p class="text-xs font-black text-slate-800">{{ $show->segments_count }}</p><p class="mt-0.5 text-[10px] text-slate-400">Segments</p></div>
                        </div>
                        <div class="mt-4 flex items-center justify-between gap-4">
                            <div class="min-w-0"><p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Primary host</p><p class="mt-1 truncate text-sm font-bold text-slate-700"><i class="fas fa-microphone mr-1.5 text-emerald-600"></i>{{ $show->primaryHost?->name ?? 'Not assigned' }}</p></div>
                            <span class="shrink-0 rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-bold capitalize text-blue-700">{{ $show->format }}</span>
                        </div>
                        <div class="mt-5 flex items-center justify-end gap-2 border-t border-slate-100 pt-4">
                            <a href="{{ route('admin.shows.edit', $show->id) }}" class="rounded-lg px-3 py-2 text-xs font-bold text-emerald-700 hover:bg-emerald-50"><i class="fas fa-pen mr-1.5"></i>Edit</a>
                            <button wire:click="delete('show', {{ $show->id }})" wire:confirm="Delete {{ $show->title }} and its related programme data?" class="rounded-lg px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-50"><i class="fas fa-trash mr-1.5"></i>Delete</button>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-xl text-slate-400"><i class="fas fa-radio"></i></span>
                    <h3 class="mt-4 text-lg font-black text-slate-900">No shows found</h3>
                    <p class="mt-2 text-sm text-slate-500">Create the first show or try a different search.</p>
                </div>
            @endforelse
        </section>
        @if($shows->hasPages())<div class="rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">{{ $shows->links() }}</div>@endif
    @elseif($view === 'oaps')
        <section class="grid grid-cols-1 gap-5 md:grid-cols-2 2xl:grid-cols-3">
            @forelse($oaps as $oap)
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-emerald-200 hover:shadow-md" wire:key="oap-{{ $oap->id }}">
                    <div class="flex items-start gap-4">
                        <div class="h-16 w-16 shrink-0 overflow-hidden rounded-2xl bg-emerald-50 ring-1 ring-emerald-100">
                            <x-initials-image :src="$oap->profile_photo" :title="$oap->name" imgClass="h-full w-full object-cover" fallbackClass="bg-[#173b5f]" textClass="text-xl font-black text-white" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-2"><h3 class="truncate text-lg font-black text-slate-900">{{ $oap->name }}</h3><span class="rounded-full px-2 py-1 text-[10px] font-extrabold uppercase {{ $oap->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ $oap->is_active ? 'Active' : 'Inactive' }}</span></div>
                            <p class="mt-1 truncate text-xs font-semibold text-emerald-700">{{ $oap->teamRole?->name ?? $oap->employment_status ?? 'On-air personality' }}</p>
                            <p class="mt-1 truncate text-xs text-slate-500">{{ $oap->department?->name ?? 'No department' }}</p>
                        </div>
                    </div>
                    <p class="mt-4 line-clamp-2 min-h-[40px] text-sm leading-5 text-slate-500">{{ $oap->bio ?: 'No presenter biography yet.' }}</p>
                    <div class="mt-4 grid grid-cols-3 divide-x divide-slate-200 rounded-xl bg-slate-50 py-3 text-center">
                        <div><p class="text-sm font-black text-slate-800">{{ $oap->shows_count }}</p><p class="text-[10px] text-slate-400">Shows</p></div>
                        <div><p class="text-sm font-black text-slate-800">{{ $oap->schedule_slots_count }}</p><p class="text-[10px] text-slate-400">Slots</p></div>
                        <div><p class="text-sm font-black {{ $oap->available ? 'text-emerald-700' : 'text-orange-700' }}">{{ $oap->available ? 'Yes' : 'No' }}</p><p class="text-[10px] text-slate-400">Available</p></div>
                    </div>
                    <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                        <span class="truncate text-xs text-slate-500">{{ $oap->email ?: 'No email provided' }}</span>
                        <div class="flex gap-1"><a href="{{ route('admin.shows.oaps.edit', $oap->id) }}" class="rounded-lg px-3 py-2 text-xs font-bold text-emerald-700 hover:bg-emerald-50">Edit</a><button wire:click="delete('oap', {{ $oap->id }})" wire:confirm="Delete {{ $oap->name }}?" class="rounded-lg px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-50">Delete</button></div>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center text-sm text-slate-500">No OAP profiles found.</div>
            @endforelse
        </section>
        @if($oaps->hasPages())<div class="rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">{{ $oaps->links() }}</div>@endif
    @elseif($view === 'schedule')
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50"><tr><th class="px-5 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wide text-slate-500">Broadcast day</th><th class="px-5 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wide text-slate-500">Programme</th><th class="px-5 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wide text-slate-500">On air</th><th class="px-5 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wide text-slate-500">Presenter</th><th class="px-5 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wide text-slate-500">Run</th><th class="px-5 py-3.5 text-right text-[11px] font-extrabold uppercase tracking-wide text-slate-500">Actions</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($scheduleSlots as $slot)
                            <tr class="transition hover:bg-slate-50/70" wire:key="slot-{{ $slot->id }}">
                                <td class="px-5 py-4"><span class="inline-flex rounded-lg bg-[#0b2f3a] px-3 py-1.5 text-xs font-extrabold text-white">{{ ucfirst($slot->day_of_week) }}</span></td>
                                <td class="px-5 py-4"><p class="text-sm font-extrabold text-slate-900">{{ $slot->show?->title ?? 'Missing show' }}</p><p class="mt-0.5 text-xs text-slate-500">{{ $slot->show?->category?->name ?? 'Programme' }}</p></td>
                                <td class="px-5 py-4"><p class="text-sm font-bold text-slate-800">{{ $slot->time_range }}</p><p class="mt-0.5 text-xs text-slate-500">{{ $slot->duration }} minutes</p></td>
                                <td class="px-5 py-4 text-sm font-semibold text-slate-700">{{ $slot->oap?->name ?? 'Not assigned' }}</td>
                                <td class="px-5 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $slot->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ ucfirst($slot->status) }}</span><p class="mt-1.5 text-[10px] text-slate-400">{{ $slot->is_recurring ? 'Recurring' : 'One-off' }}</p></td>
                                <td class="px-5 py-4 text-right"><a href="{{ route('admin.shows.schedule.edit', $slot->id) }}" class="rounded-lg px-3 py-2 text-xs font-bold text-emerald-700 hover:bg-emerald-50">Edit</a><button wire:click="delete('schedule', {{ $slot->id }})" wire:confirm="Delete this {{ ucfirst($slot->day_of_week) }} schedule slot?" class="rounded-lg px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-50">Delete</button></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-16 text-center text-sm text-slate-500">No schedule slots found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($scheduleSlots->hasPages())<div class="border-t border-slate-200 px-5 py-4">{{ $scheduleSlots->links() }}</div>@endif
        </section>
    @elseif($view === 'segments')
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto"><table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50"><tr><th class="px-5 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wide text-slate-500">Order</th><th class="px-5 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wide text-slate-500">Show & segment</th><th class="px-5 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wide text-slate-500">Type</th><th class="px-5 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wide text-slate-500">Timing</th><th class="px-5 py-3.5 text-right text-[11px] font-extrabold uppercase tracking-wide text-slate-500">Actions</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($segments as $segment)
                        <tr class="hover:bg-slate-50/70" wire:key="segment-{{ $segment->id }}"><td class="px-5 py-4"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-xs font-black text-slate-600">{{ $segment->order }}</span></td><td class="px-5 py-4"><p class="text-sm font-extrabold text-slate-900">{{ $segment->title }}</p><p class="mt-0.5 text-xs text-slate-500">{{ $segment->show?->title ?? 'Missing show' }}</p></td><td class="px-5 py-4"><span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-bold capitalize text-blue-700">{{ $segment->type }}</span></td><td class="px-5 py-4"><p class="text-sm font-bold text-slate-800">{{ $segment->time_range }}</p><p class="text-xs text-slate-500">{{ $segment->duration }} min</p></td><td class="px-5 py-4 text-right"><a href="{{ route('admin.shows.segments.edit', $segment->id) }}" class="rounded-lg px-3 py-2 text-xs font-bold text-emerald-700 hover:bg-emerald-50">Edit</a><button wire:click="delete('segment', {{ $segment->id }})" wire:confirm="Delete {{ $segment->title }}?" class="rounded-lg px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-50">Delete</button></td></tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-16 text-center text-sm text-slate-500">No show segments found.</td></tr>
                    @endforelse
                </tbody>
            </table></div>
            @if($segments->hasPages())<div class="border-t border-slate-200 px-5 py-4">{{ $segments->links() }}</div>@endif
        </section>
    @elseif($view === 'categories')
        <section class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse($categories as $category)
                @php
                    [$categoryBg, $categoryText] = match($category->color) {
                        'red' => ['bg-red-50', 'text-red-600'], 'green' => ['bg-emerald-50', 'text-emerald-600'], 'purple' => ['bg-violet-50', 'text-violet-600'], 'orange' => ['bg-orange-50', 'text-orange-600'], default => ['bg-blue-50', 'text-blue-600'],
                    };
                @endphp
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-emerald-200 hover:shadow-md" wire:key="category-{{ $category->id }}">
                    <div class="flex items-start justify-between gap-4"><span class="flex h-12 w-12 items-center justify-center rounded-xl {{ $categoryBg }} {{ $categoryText }}"><i class="{{ $category->icon ?: 'fas fa-microphone' }} text-lg"></i></span><span class="rounded-full px-2.5 py-1 text-[10px] font-extrabold uppercase {{ $category->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ $category->is_active ? 'Active' : 'Inactive' }}</span></div>
                    <h3 class="mt-4 text-lg font-black text-slate-900">{{ $category->name }}</h3><p class="mt-1 text-xs font-semibold text-slate-400">/{{ $category->slug }}</p><p class="mt-3 line-clamp-2 min-h-[40px] text-sm text-slate-500">{{ $category->description ?: 'No category description yet.' }}</p>
                    <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4"><span class="text-sm font-black text-slate-800">{{ $category->shows_count }} {{ \Illuminate\Support\Str::plural('show', $category->shows_count) }}</span><div><a href="{{ route('admin.shows.categories.edit', $category->id) }}" class="rounded-lg px-3 py-2 text-xs font-bold text-emerald-700 hover:bg-emerald-50">Edit</a><button wire:click="delete('category', {{ $category->id }})" wire:confirm="Delete {{ $category->name }}?" class="rounded-lg px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-50">Delete</button></div></div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center text-sm text-slate-500">No show categories found.</div>
            @endforelse
        </section>
        @if($categories->hasPages())<div class="rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">{{ $categories->links() }}</div>@endif
    @endif

    @if(session()->has('success'))<div class="flash-auto-dismiss fixed bottom-4 right-4 z-50 max-w-sm rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-xl"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>@endif
    @if(session()->has('error'))<div class="flash-auto-dismiss fixed bottom-4 right-4 z-50 max-w-sm rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white shadow-xl"><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</div>@endif
</div>
