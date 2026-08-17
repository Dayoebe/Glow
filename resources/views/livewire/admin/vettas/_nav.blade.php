<nav class="mb-6 overflow-x-auto rounded-xl border border-gray-200 bg-white p-2 shadow-sm" aria-label="Vettas workspace">
    <div class="flex min-w-max gap-1">
        @foreach([
            ['admin.vettas.index', 'Gallery', 'fa-images'],
            ['admin.vettas.reservations', 'Reservations', 'fa-calendar-check'],
            ['admin.vettas.categories', 'Categories', 'fa-folder-open'],
            ['admin.vettas.create', 'Add Photo', 'fa-plus'],
            ['admin.vettas.settings', 'Page Content', 'fa-file-lines'],
            ['admin.vettas.promotion', 'Promotion Toolkit', 'fa-bullhorn'],
        ] as [$routeName, $label, $icon])
            <a href="{{ route($routeName) }}" class="inline-flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold {{ request()->routeIs($routeName) ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i class="fas {{ $icon }} mr-2"></i>{{ $label }}
            </a>
        @endforeach
        <a href="{{ route('vettas.index') }}" target="_blank" class="ml-2 inline-flex items-center rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"><i class="fas fa-arrow-up-right-from-square mr-2"></i>View website</a>
    </div>
</nav>
