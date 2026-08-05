<div class="space-y-6">
    @php
        $tabs = [
            ['label' => 'Shows', 'icon' => 'fa-radio', 'route' => route('admin.shows.index'), 'count' => $workspaceCounts['shows']],
            ['label' => 'OAPs', 'icon' => 'fa-microphone-lines', 'route' => route('admin.shows.oaps'), 'count' => $workspaceCounts['oaps']],
            ['label' => 'Schedule', 'icon' => 'fa-calendar-days', 'route' => route('admin.shows.schedule'), 'count' => $workspaceCounts['schedule']],
            ['label' => 'Segments', 'icon' => 'fa-list-ol', 'route' => route('admin.shows.segments'), 'count' => $workspaceCounts['segments']],
            ['label' => 'Categories', 'icon' => 'fa-layer-group', 'route' => route('admin.shows.categories'), 'count' => $workspaceCounts['categories']],
            ['label' => 'Reviews', 'icon' => 'fa-star', 'route' => route('admin.shows.reviews'), 'count' => $workspaceCounts['reviews'], 'active' => true],
        ];
    @endphp

    <section class="relative overflow-hidden rounded-2xl bg-[#0b2f3a] px-6 py-7 text-white shadow-sm sm:px-8">
        <div class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-amber-400/10"></div>
        <div class="relative max-w-2xl">
            <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-amber-300">Audience voice</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl">Turn listener feedback into better radio.</h2>
            <p class="mt-3 max-w-xl text-sm leading-6 text-slate-300">Moderate show reviews, understand audience sentiment and keep public feedback useful.</p>
        </div>
        <div class="relative mt-7 grid grid-cols-2 gap-3 lg:grid-cols-4">
            @foreach([
                ['label' => 'All reviews', 'value' => $stats['total'], 'icon' => 'fa-comments'],
                ['label' => 'Visible', 'value' => $stats['visible'], 'icon' => 'fa-eye'],
                ['label' => 'Hidden', 'value' => $stats['hidden'], 'icon' => 'fa-eye-slash'],
                ['label' => 'Average rating', 'value' => number_format($stats['average'], 1), 'icon' => 'fa-star'],
            ] as $stat)
                <div class="rounded-xl border border-white/10 bg-white/[0.07] p-4"><div class="flex items-center justify-between"><div><p class="text-2xl font-black">{{ $stat['value'] }}</p><p class="mt-1 text-xs font-semibold text-slate-300">{{ $stat['label'] }}</p></div><span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/10 text-amber-300"><i class="fas {{ $stat['icon'] }}"></i></span></div></div>
            @endforeach
        </div>
    </section>

    <nav class="overflow-x-auto rounded-2xl border border-slate-200 bg-white p-2 shadow-sm" aria-label="Shows and programmes sections">
        <div class="flex min-w-max gap-1">
            @foreach($tabs as $tab)
                <a href="{{ $tab['route'] }}" class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold transition {{ !empty($tab['active']) ? 'bg-[#0b2f3a] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="fas {{ $tab['icon'] }} text-xs {{ !empty($tab['active']) ? 'text-amber-300' : 'text-slate-400' }}"></i>{{ $tab['label'] }}
                    <span class="rounded-full px-2 py-0.5 text-[10px] font-extrabold {{ !empty($tab['active']) ? 'bg-white/10 text-white' : 'bg-slate-100 text-slate-500' }}">{{ number_format($tab['count']) }}</span>
                </a>
            @endforeach
        </div>
    </nav>

    <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="grid gap-3 lg:grid-cols-[minmax(0,1fr)_220px_160px]">
            <div class="relative"><i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i><input type="search" wire:model.live.debounce.300ms="search" placeholder="Search comments, shows or reviewers…" class="w-full rounded-xl border-slate-300 py-2.5 pl-10 pr-4 text-sm focus:border-emerald-500 focus:ring-emerald-500"></div>
            <select wire:model.live="filterShow" class="w-full rounded-xl border-slate-300 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500"><option value="">All shows</option>@foreach($shows as $show)<option value="{{ $show->id }}">{{ $show->title }}</option>@endforeach</select>
            <select wire:model.live="filterStatus" class="w-full rounded-xl border-slate-300 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500"><option value="">All visibility</option><option value="visible">Visible</option><option value="hidden">Hidden</option></select>
        </div>
        @if($hasFilters)<div class="mt-4 border-t border-slate-100 pt-3 text-right"><button wire:click="clearFilters" class="text-xs font-bold text-[#d94d16] hover:text-[#b83c0f]"><i class="fas fa-rotate-left mr-1"></i>Reset filters</button></div>@endif
    </section>

    <section class="grid grid-cols-1 gap-4 xl:grid-cols-2">
        @forelse($reviews as $review)
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm" wire:key="review-{{ $review->id }}">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0"><p class="text-xs font-extrabold uppercase tracking-wide text-emerald-700">{{ $review->show?->title ?? 'Unknown show' }}</p><div class="mt-2 flex items-center gap-1 text-amber-400">@for($i = 1; $i <= 5; $i++)<i class="fas fa-star text-xs {{ $i <= $review->rating ? '' : 'text-slate-200' }}"></i>@endfor<span class="ml-1 text-xs font-black text-slate-700">{{ $review->rating }}/5</span></div></div>
                    <span class="shrink-0 rounded-full px-2.5 py-1 text-[10px] font-extrabold uppercase {{ $review->is_approved ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ $review->is_approved ? 'Public' : 'Hidden' }}</span>
                </div>
                <blockquote class="mt-4 min-h-[48px] text-sm leading-6 text-slate-600">“{{ $review->review ?: 'No written comment.' }}”</blockquote>
                <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                    <div class="flex min-w-0 items-center gap-3"><div class="flex h-9 w-9 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-slate-100 text-xs font-black text-slate-600">@if($review->user?->avatar)<img src="{{ $review->user->avatar }}" alt="{{ $review->user->name }}" class="h-full w-full object-cover">@else{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($review->user?->name ?? 'Guest', 0, 1)) }}@endif</div><div class="min-w-0"><p class="truncate text-sm font-bold text-slate-800">{{ $review->user?->name ?? 'Guest listener' }}</p><p class="text-[10px] text-slate-400">{{ $review->created_at?->diffForHumans() }}</p></div></div>
                    <button wire:click="toggleApproval({{ $review->id }})" wire:confirm="{{ $review->is_approved ? 'Hide this review from the public?' : 'Publish this review publicly?' }}" class="rounded-lg px-3 py-2 text-xs font-bold {{ $review->is_approved ? 'text-red-600 hover:bg-red-50' : 'text-emerald-700 hover:bg-emerald-50' }}">{{ $review->is_approved ? 'Hide review' : 'Publish review' }}</button>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center"><span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-xl text-slate-400"><i class="fas fa-comments"></i></span><h3 class="mt-4 text-lg font-black text-slate-900">No reviews found</h3><p class="mt-2 text-sm text-slate-500">New audience feedback will appear here.</p></div>
        @endforelse
    </section>

    @if($reviews->hasPages())<div class="rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">{{ $reviews->links() }}</div>@endif
    @if(session()->has('success'))<div class="flash-auto-dismiss fixed bottom-4 right-4 z-50 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-xl"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>@endif
</div>
