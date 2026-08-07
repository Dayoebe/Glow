<nav class="flex flex-wrap gap-2 rounded-2xl border border-slate-200 bg-white p-2 shadow-sm" aria-label="Blog workspace">
    @foreach([
        ['route' => 'admin.blog.index', 'active' => 'admin.blog.index', 'icon' => 'fa-rectangle-list', 'label' => 'Posts'],
        ['route' => 'admin.blog.create', 'active' => 'admin.blog.create', 'icon' => 'fa-pen-nib', 'label' => 'Write'],
        ['route' => 'admin.blog.categories', 'active' => 'admin.blog.categories', 'icon' => 'fa-folder-tree', 'label' => 'Categories'],
        ['route' => 'admin.blog.analytics', 'active' => 'admin.blog.analytics', 'icon' => 'fa-chart-line', 'label' => 'Analytics'],
    ] as $item)
        <a href="{{ route($item['route']) }}"
            @class([
                'inline-flex items-center rounded-xl px-4 py-2.5 text-sm font-extrabold transition',
                'bg-[#0b2f3a] text-white shadow-sm' => request()->routeIs($item['active']),
                'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => !request()->routeIs($item['active']),
            ])>
            <i class="fas {{ $item['icon'] }} mr-2"></i>{{ $item['label'] }}
        </a>
    @endforeach
</nav>
