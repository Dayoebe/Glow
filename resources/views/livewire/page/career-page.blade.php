<div>
    <section class="relative overflow-hidden bg-gradient-to-br from-emerald-700 via-emerald-800 to-slate-900 py-20 text-white">
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 20%, rgba(255,255,255,.28), transparent 45%), radial-gradient(circle at 80% 0%, rgba(16,185,129,.35), transparent 35%);"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl">
                <p class="text-xs uppercase tracking-[0.35em] text-emerald-200 font-semibold">Join Glow FM</p>
                <h1 class="mt-4 text-4xl md:text-6xl font-black leading-tight">Build Your Career In Broadcasting</h1>
                <p class="mt-5 text-lg md:text-xl text-emerald-100/90 leading-relaxed">
                    Discover open roles across editorial, production, marketing, events, and operations.
                </p>
            </div>
        </div>
    </section>

    <section class="bg-white border-b border-gray-200">
        <div class="container mx-auto px-4 py-6">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
                <div class="md:col-span-2">
                    <label class="sr-only">Search careers</label>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by title, department, keyword..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="sr-only">Department</label>
                    <select wire:model.live="department"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}">{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="sr-only">Employment Type</label>
                    <select wire:model.live="employmentType"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">All Types</option>
                        @foreach($employmentTypes as $type)
                            <option value="{{ $type }}">{{ \Illuminate\Support\Str::of($type)->replace('-', ' ')->title() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="sr-only">Sort</label>
                    <select wire:model.live="sortBy"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="latest">Latest</option>
                        <option value="deadline">Closing Soon</option>
                        <option value="salary">Highest Salary</option>
                        <option value="oldest">Oldest</option>
                    </select>
                </div>
                <div>
                    <label class="sr-only">Workplace Type</label>
                    <select wire:model.live="workplaceType"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">All Workplace Types</option>
                        @foreach($workplaceTypes as $type)
                            <option value="{{ $type }}">{{ \Illuminate\Support\Str::of($type)->replace('-', ' ')->title() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            @if($featuredPositions->isNotEmpty())
                <div class="mb-10">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Featured Openings</h2>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                        @foreach($featuredPositions as $position)
                            <a href="{{ route('careers.show', $position->slug) }}"
                                class="group block rounded-2xl border border-emerald-200 bg-white p-6 shadow-sm hover:shadow-lg transition-all">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Featured</span>
                                    <span class="text-xs font-semibold {{ $position->isAcceptingApplications() ? 'text-emerald-600' : 'text-amber-600' }}">
                                        {{ $position->isAcceptingApplications() ? 'Accepting Applications' : 'Closed' }}
                                    </span>
                                </div>
                                <h3 class="mt-3 text-xl font-bold text-gray-900 group-hover:text-emerald-700 transition-colors">{{ $position->title }}</h3>
                                <p class="mt-2 text-sm text-gray-600 line-clamp-2">{{ $position->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($position->description), 150) }}</p>
                                <div class="mt-4 grid grid-cols-2 gap-3 text-xs text-gray-600">
                                    <div><i class="fas fa-briefcase mr-1 text-emerald-600"></i>{{ \Illuminate\Support\Str::of($position->employment_type)->replace('-', ' ')->title() }}</div>
                                    <div><i class="fas fa-location-dot mr-1 text-emerald-600"></i>{{ $position->location_label }}</div>
                                    <div><i class="fas fa-money-bill-wave mr-1 text-emerald-600"></i>{{ $position->salary_range_label }}</div>
                                    <div><i class="fas fa-clock mr-1 text-emerald-600"></i>{{ $position->application_deadline?->format('M d, Y') ?: 'No deadline' }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="flex items-center justify-between mb-5">
                <h2 class="text-2xl font-bold text-gray-900">Open Roles</h2>
                <p class="text-sm text-gray-600">{{ $positions->total() }} role(s) found</p>
            </div>

            @if($positions->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach($positions as $position)
                        <article class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all">
                            <div class="flex items-start justify-between gap-3">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                    {{ $position->department ?: 'General' }}
                                </span>
                                <span class="text-xs font-semibold {{ $position->status === 'open' ? 'text-emerald-600' : 'text-amber-600' }}">
                                    {{ \Illuminate\Support\Str::of($position->status)->title() }}
                                </span>
                            </div>

                            <h3 class="mt-4 text-xl font-bold text-gray-900">{{ $position->title }}</h3>
                            <p class="mt-2 text-sm text-gray-600 line-clamp-3">
                                {{ $position->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($position->description), 150) }}
                            </p>

                            <div class="mt-4 space-y-2 text-sm text-gray-600">
                                <p><i class="fas fa-briefcase mr-2 text-emerald-600"></i>{{ \Illuminate\Support\Str::of($position->employment_type)->replace('-', ' ')->title() }} • {{ \Illuminate\Support\Str::of($position->workplace_type)->replace('-', ' ')->title() }}</p>
                                <p><i class="fas fa-location-dot mr-2 text-emerald-600"></i>{{ $position->location_label }}</p>
                                <p><i class="fas fa-money-bill-wave mr-2 text-emerald-600"></i>{{ $position->salary_range_label }}</p>
                                <p><i class="fas fa-hourglass-half mr-2 text-emerald-600"></i>{{ $position->application_deadline?->format('M d, Y') ?: 'No deadline' }}</p>
                            </div>

                            <div class="mt-6 flex items-center justify-between">
                                {{-- <p class="text-xs text-gray-500">{{ $position->applications_count }} application(s)</p> --}}
                                <a href="{{ route('careers.show', $position->slug) }}"
                                    class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition-colors">
                                    {{ $position->isAcceptingApplications() ? 'Apply Now' : 'View Details' }}
                                    <i class="fas fa-arrow-right ml-2 text-xs"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $positions->links() }}
                </div>
            @else
                <div class="bg-white border border-dashed border-gray-300 rounded-2xl p-12 text-center">
                    <i class="fas fa-briefcase text-4xl text-gray-300"></i>
                    <h3 class="mt-4 text-xl font-bold text-gray-900">No roles match your filters</h3>
                    <p class="mt-2 text-gray-600">Try changing your search or filter options.</p>
                </div>
            @endif
        </div>
    </section>
</div>
