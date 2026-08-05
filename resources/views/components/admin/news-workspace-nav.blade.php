@php
    $items = [
        ['route' => 'admin.news.index', 'match' => 'admin.news.index', 'label' => 'Articles', 'icon' => 'fa-layer-group'],
        ['route' => 'admin.news.categories', 'match' => 'admin.news.categories', 'label' => 'Categories', 'icon' => 'fa-tags'],
        ['route' => 'admin.news.analytics', 'match' => 'admin.news.analytics', 'label' => 'Analytics', 'icon' => 'fa-chart-column'],
    ];
@endphp

<nav class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-label="Newsroom workspace">
    <div class="flex flex-col gap-2 p-2 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex min-w-0 overflow-x-auto" role="list">
            @foreach($items as $item)
                @php
                    $active = request()->routeIs($item['match']);
                    if ($item['route'] === 'admin.news.index') {
                        $active = request()->routeIs('admin.news.index', 'admin.news.show', 'admin.news.edit', 'admin.news.create');
                    }
                @endphp
                <a href="{{ route($item['route']) }}"
                   class="inline-flex shrink-0 items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-extrabold transition {{ $active ? 'bg-[#0b2f3a] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-950' }}"
                   @if($active) aria-current="page" @endif>
                    <i class="fas {{ $item['icon'] }} text-xs {{ $active ? 'text-emerald-300' : 'text-slate-400' }}"></i>
                    {{ $item['label'] }}
                </a>
            @endforeach
            <a href="{{ route('news') }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex shrink-0 items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-extrabold text-slate-600 transition hover:bg-slate-100 hover:text-slate-950">
                <i class="fas fa-arrow-up-right-from-square text-xs text-slate-400"></i>
                Public newsroom
            </a>
        </div>
        @unless(request()->routeIs('admin.news.create'))
            <a href="{{ route('admin.news.create') }}"
               class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-[#ed5a1f] px-4 py-2.5 text-sm font-extrabold text-white transition hover:bg-[#d94d16]">
                <i class="fas fa-plus text-xs"></i>Write article
            </a>
        @endunless
    </div>
</nav>
