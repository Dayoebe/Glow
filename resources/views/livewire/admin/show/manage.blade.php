<div>
    <!-- Tabs -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.shows.index') }}"
                class="px-4 py-2 rounded-lg text-sm font-medium {{ $view === 'shows' ? 'bg-emerald-600 text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                Shows
            </a>
            <a href="{{ route('admin.shows.oaps') }}"
                class="px-4 py-2 rounded-lg text-sm font-medium {{ $view === 'oaps' ? 'bg-emerald-600 text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                OAPs
            </a>
            <a href="{{ route('admin.shows.schedule') }}"
                class="px-4 py-2 rounded-lg text-sm font-medium {{ $view === 'schedule' ? 'bg-emerald-600 text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                Schedule
            </a>
            <a href="{{ route('admin.shows.segments') }}"
                class="px-4 py-2 rounded-lg text-sm font-medium {{ $view === 'segments' ? 'bg-emerald-600 text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                Segments
            </a>
            <a href="{{ route('admin.shows.categories') }}"
                class="px-4 py-2 rounded-lg text-sm font-medium {{ $view === 'categories' ? 'bg-emerald-600 text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                Categories
            </a>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Search..."
                    class="w-full sm:w-64 pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
            @if($view === 'shows')
                <div class="flex items-center gap-2">
                    <select wire:model.live="showSort"
                        class="w-full sm:w-44 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="latest">Newest</option>
                        <option value="title">Title</option>
                        <option value="host">Host</option>
                        <option value="category">Category</option>
                        <option value="day">Day</option>
                        <option value="duration">Duration</option>
                        <option value="featured">Featured</option>
                    </select>
                    <select wire:model.live="showSortDirection"
                        class="w-full sm:w-28 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="asc">Asc</option>
                        <option value="desc">Desc</option>
                    </select>
                </div>
            @elseif($view === 'schedule')
                <div class="flex items-center gap-2">
                    <select wire:model.live="scheduleSort"
                        class="w-full sm:w-44 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="day">Day</option>
                        <option value="show">Show</option>
                        <option value="host">Host</option>
                        <option value="time">Start Time</option>
                        <option value="status">Status</option>
                    </select>
                    <select wire:model.live="scheduleSortDirection"
                        class="w-full sm:w-28 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="asc">Asc</option>
                        <option value="desc">Desc</option>
                    </select>
                </div>
            @endif
            @php
                $addRoute = route('admin.shows.create');
                $addLabel = 'Show';

                if ($view === 'oaps') {
                    $addRoute = route('admin.shows.oaps.create');
                    $addLabel = 'OAP';
                } elseif ($view === 'categories') {
                    $addRoute = route('admin.shows.categories.create');
                    $addLabel = 'Category';
                } elseif ($view === 'schedule') {
                    $addRoute = route('admin.shows.schedule.create');
                    $addLabel = 'Slot';
                } elseif ($view === 'segments') {
                    $addRoute = route('admin.shows.segments.create');
                    $addLabel = 'Segment';
                }
            @endphp
            <a href="{{ $addRoute }}"
                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg">
                <i class="fas fa-plus mr-2"></i>
                Add {{ $addLabel }}
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600 mb-1">Total Shows</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_shows'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600 mb-1">Active Shows</p>
            <p class="text-2xl font-bold text-emerald-600">{{ $stats['active_shows'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600 mb-1">Total OAPs</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_oaps'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600 mb-1">Categories</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_categories'] }}</p>
        </div>
    </div>

    <!-- Shows -->
    @if($view === 'shows')
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($shows as $show)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="h-40 bg-gray-100 overflow-hidden">
                        @if($show->cover_image)
                            <img src="{{ $show->cover_image }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <i class="fas fa-image text-3xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $show->title }}</h3>
                            @if($show->is_featured)
                                <span class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded-full">Featured</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 line-clamp-2">{{ $show->description }}</p>
                        <div class="mt-3 text-xs text-gray-500 space-y-1">
                            <div><i class="fas fa-layer-group mr-1"></i>{{ $show->category?->name ?? 'Uncategorized' }}</div>
                            <div><i class="fas fa-user mr-1"></i>{{ $show->primaryHost?->name ?? 'TBA' }}</div>
                            <div><i class="fas fa-clock mr-1"></i>{{ $show->typical_duration }} mins • {{ ucfirst($show->format) }}</div>
                        </div>
                        <div class="mt-4 flex items-center justify-end space-x-2">
                            <a href="{{ route('admin.shows.edit', $show->id) }}" class="text-emerald-600 hover:text-emerald-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button wire:click="delete('show', {{ $show->id }})" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-xl shadow-sm border border-gray-200 p-10 text-center text-gray-500">
                    No shows found.
                </div>
            @endforelse
        </div>
        <div class="mt-6">{{ $shows->links() }}</div>
    @endif

    <!-- OAPs -->
    @if($view === 'oaps')
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($oaps as $oap)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $oap->profile_photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($oap->name) }}"
                            class="w-12 h-12 rounded-full">
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-900 truncate">{{ $oap->name }}</p>
                            <p class="text-xs text-gray-500">{{ $oap->employment_status ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-3 line-clamp-2">{{ $oap->bio }}</p>
                    <div class="mt-4 flex items-center justify-end space-x-2">
                        <a href="{{ route('admin.shows.oaps.edit', $oap->id) }}" class="text-emerald-600 hover:text-emerald-900">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button wire:click="delete('oap', {{ $oap->id }})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-xl shadow-sm border border-gray-200 p-10 text-center text-gray-500">
                    No OAPs found.
                </div>
            @endforelse
        </div>
        <div class="mt-6">{{ $oaps->links() }}</div>
    @endif

    <!-- Schedule -->
    @if($view === 'schedule')
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Day</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Show</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">OAP</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($scheduleSlots as $slot)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-700">{{ ucfirst($slot->day_of_week) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $slot->time_range }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $slot->show->title }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $slot->oap?->name ?? 'TBA' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $slot->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($slot->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <a href="{{ route('admin.shows.schedule.edit', $slot->id) }}" class="text-emerald-600 hover:text-emerald-900 mr-2">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button wire:click="delete('schedule', {{ $slot->id }})" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">No schedule slots found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $scheduleSlots->links() }}
            </div>
        </div>
    @endif

    <!-- Segments -->
    @if($view === 'segments')
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Show</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($segments as $segment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $segment->show->title }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $segment->title }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $segment->time_range }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ ucfirst($segment->type) }}</td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <a href="{{ route('admin.shows.segments.edit', $segment->id) }}" class="text-emerald-600 hover:text-emerald-900 mr-2">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button wire:click="delete('segment', {{ $segment->id }})" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">No segments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $segments->links() }}
            </div>
        </div>
    @endif

    <!-- Categories -->
    @if($view === 'categories')
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shows</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($categories as $category)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-9 h-9 rounded-lg flex items-center justify-center bg-{{ $category->color }}-100">
                                            <i class="{{ $category->icon }} text-{{ $category->color }}-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $category->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $category->slug }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $category->shows_count }}</td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <a href="{{ route('admin.shows.categories.edit', $category->id) }}" class="text-emerald-600 hover:text-emerald-900 mr-2">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button wire:click="delete('category', {{ $category->id }})" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-gray-500">No categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $categories->links() }}
            </div>
        </div>
    @endif

</div>
