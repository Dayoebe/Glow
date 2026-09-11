<div class="min-h-screen bg-[#f6f2e9] text-[#0b1830]">
    <section class="bg-[#07172f] text-white">
        <div class="mx-auto grid max-w-7xl gap-12 px-5 py-16 sm:px-8 lg:grid-cols-[1fr_21rem] lg:px-10 lg:py-24">
            <div class="max-w-3xl">
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#ff8a2a]">Careers at Glow</p>
                <h1 class="font-display mt-4 text-5xl font-semibold leading-[0.98] tracking-tight sm:text-6xl">
                    Do work that moves the community.
                </h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">
                    Explore current opportunities across broadcasting, editorial, production, commercial and station operations.
                </p>
            </div>
            <div class="self-end border-t border-white/20 pt-6 lg:border-l lg:border-t-0 lg:pl-8 lg:pt-0">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">Current openings</p>
                <p class="font-display mt-2 text-5xl font-semibold text-[#ff8a2a]">{{ number_format($positions->total()) }}</p>
                <p class="mt-2 text-sm leading-6 text-slate-300">
                    Every role listed here is currently accepting applications.
                </p>
            </div>
        </div>
    </section>

    <section class="border-b border-[#0b1830]/10 bg-[#f6f2e9]">
        <div class="mx-auto grid max-w-7xl gap-5 px-5 py-10 sm:px-8 md:grid-cols-2 xl:grid-cols-4 lg:px-10">
            <div class="bg-[#0b1830] p-7 text-white"><i class="fas fa-briefcase text-[#ff8a2a]"></i><h2 class="font-display mt-5 text-2xl font-semibold">Apply for a job</h2><p class="mt-2 text-sm leading-6 text-slate-300">Browse open paid positions and apply directly to the role that fits you.</p><a href="#open-positions" class="mt-5 inline-flex font-bold text-[#ff8a2a]">View open jobs <i class="fas fa-arrow-down ml-2 mt-1"></i></a></div>
            <div class="border border-[#0b1830]/10 bg-white p-7"><i class="fas fa-graduation-cap text-[#d95318]"></i><h2 class="font-display mt-5 text-2xl font-semibold">Become an intern</h2><p class="mt-2 text-sm leading-6 text-slate-600">Gain hands-on broadcasting and media experience while learning with our team.</p><a href="{{ route('careers.programmes.apply', 'internship') }}" class="mt-5 inline-flex font-bold text-[#d95318]">Apply for internship <i class="fas fa-arrow-right ml-2 mt-1"></i></a></div>
            <div class="border border-[#0b1830]/10 bg-white p-7"><i class="fas fa-hand-holding-heart text-[#d95318]"></i><h2 class="font-display mt-5 text-2xl font-semibold">Volunteer with us</h2><p class="mt-2 text-sm leading-6 text-slate-600">Contribute your skills and time to content, events and community impact.</p><a href="{{ route('careers.programmes.apply', 'volunteer') }}" class="mt-5 inline-flex font-bold text-[#d95318]">Apply as volunteer <i class="fas fa-arrow-right ml-2 mt-1"></i></a></div>
            <div class="border border-[#f36b21]/40 bg-[#fff8f2] p-7"><i class="fas fa-handshake text-[#d95318]"></i><h2 class="font-display mt-5 text-2xl font-semibold">Earn as a marketer</h2><p class="mt-2 text-sm leading-6 text-slate-600">Bring clients, adverts or programmes to Glow and earn an agreed percentage on successful paid business.</p><a href="{{ route('careers.programmes.apply', 'marketer') }}" class="mt-5 inline-flex font-bold text-[#d95318]">Register as a partner <i class="fas fa-arrow-right ml-2 mt-1"></i></a></div>
        </div>
    </section>

    <section class="border-b border-[#0b1830]/10 bg-white">
        <div class="mx-auto max-w-7xl px-5 py-6 sm:px-8 lg:px-10">
            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-[2fr_1fr_1fr_1fr_1fr]">
                <label class="relative block">
                    <span class="sr-only">Search careers</span>
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-xs text-slate-400" aria-hidden="true"></i>
                    <input type="search" wire:model.live.debounce.300ms="search"
                        placeholder="Search roles or keywords"
                        class="w-full border border-[#0b1830]/20 bg-[#f6f2e9] py-3 pl-10 pr-4 text-sm outline-none transition placeholder:text-slate-500 focus:border-[#f36b21] focus:ring-1 focus:ring-[#f36b21]">
                </label>
                <label>
                    <span class="sr-only">Department</span>
                    <select wire:model.live="department"
                        class="w-full border border-[#0b1830]/20 bg-[#f6f2e9] px-4 py-3 text-sm outline-none focus:border-[#f36b21] focus:ring-1 focus:ring-[#f36b21]">
                        <option value="">All departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}">{{ $dept }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    <span class="sr-only">Employment type</span>
                    <select wire:model.live="employmentType"
                        class="w-full border border-[#0b1830]/20 bg-[#f6f2e9] px-4 py-3 text-sm outline-none focus:border-[#f36b21] focus:ring-1 focus:ring-[#f36b21]">
                        <option value="">All job types</option>
                        @foreach($employmentTypes as $type)
                            <option value="{{ $type }}">{{ \Illuminate\Support\Str::of($type)->replace('-', ' ')->title() }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    <span class="sr-only">Workplace type</span>
                    <select wire:model.live="workplaceType"
                        class="w-full border border-[#0b1830]/20 bg-[#f6f2e9] px-4 py-3 text-sm outline-none focus:border-[#f36b21] focus:ring-1 focus:ring-[#f36b21]">
                        <option value="">Any workplace</option>
                        @foreach($workplaceTypes as $type)
                            <option value="{{ $type }}">{{ \Illuminate\Support\Str::of($type)->replace('-', ' ')->title() }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    <span class="sr-only">Sort roles</span>
                    <select wire:model.live="sortBy"
                        class="w-full border border-[#0b1830]/20 bg-[#f6f2e9] px-4 py-3 text-sm outline-none focus:border-[#f36b21] focus:ring-1 focus:ring-[#f36b21]">
                        <option value="latest">Newest first</option>
                        <option value="deadline">Closing soon</option>
                        <option value="salary">Salary</option>
                        <option value="oldest">Oldest first</option>
                    </select>
                </label>
            </div>

            @if($search !== '' || $department !== '' || $employmentType !== '' || $workplaceType !== '' || $sortBy !== 'latest')
                <button type="button" wire:click="clearFilters"
                    class="mt-4 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.12em] text-[#d95318] transition hover:text-[#0b1830]">
                    <i class="fas fa-times text-[0.65rem]" aria-hidden="true"></i>
                    Clear filters
                </button>
            @endif
        </div>
    </section>

    <main id="open-positions" class="scroll-mt-24 py-14 lg:py-20">
        <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">
            <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#d95318]">Open positions</p>
                    <h2 class="font-display mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Find your place at Glow</h2>
                </div>
                <p class="text-sm text-slate-500">{{ $positions->total() }} {{ \Illuminate\Support\Str::plural('role', $positions->total()) }} found</p>
            </div>

            @if($positions->isNotEmpty())
                <div class="divide-y divide-[#0b1830]/10 border-y border-[#0b1830]/10">
                    @foreach($positions as $position)
                        <article class="group grid gap-5 py-7 lg:grid-cols-[1fr_17rem_auto] lg:items-center">
                            <div>
                                <div class="flex flex-wrap items-center gap-3 text-[0.68rem] font-bold uppercase tracking-[0.14em]">
                                    <span class="text-[#d95318]">{{ $position->department ?: 'General' }}</span>
                                    @if($position->is_featured)
                                        <span class="border-l border-[#0b1830]/20 pl-3 text-slate-500">Featured</span>
                                    @endif
                                </div>
                                <h3 class="font-display mt-2 text-2xl font-semibold leading-tight transition group-hover:text-[#d95318] sm:text-3xl">
                                    <a href="{{ route('careers.show', $position->slug) }}"
                                        class="focus:outline-none focus:underline">{{ $position->title }}</a>
                                </h3>
                                <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600">
                                    {{ $position->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($position->description), 175) }}
                                </p>
                            </div>

                            <dl class="grid grid-cols-2 gap-x-5 gap-y-3 text-xs lg:grid-cols-1">
                                <div>
                                    <dt class="text-slate-500">Working arrangement</dt>
                                    <dd class="mt-1 font-semibold text-[#0b1830]">
                                        {{ \Illuminate\Support\Str::of($position->employment_type)->replace('-', ' ')->title() }}
                                        · {{ \Illuminate\Support\Str::of($position->workplace_type)->replace('-', ' ')->title() }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-slate-500">Location</dt>
                                    <dd class="mt-1 font-semibold text-[#0b1830]">{{ $position->location_label }}</dd>
                                </div>
                                <div>
                                    <dt class="text-slate-500">Apply by</dt>
                                    <dd class="mt-1 font-semibold text-[#0b1830]">{{ $position->application_deadline?->format('M j, Y') ?: 'Open until filled' }}</dd>
                                </div>
                            </dl>

                            <a href="{{ route('careers.show', $position->slug) }}"
                                class="inline-flex items-center gap-3 justify-self-start border border-[#0b1830] px-5 py-3 text-sm font-bold transition hover:bg-[#0b1830] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#f36b21] lg:justify-self-end">
                                View role
                                <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                            </a>
                        </article>
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $positions->links() }}
                </div>
            @else
                <div class="border border-dashed border-[#0b1830]/20 bg-white px-6 py-16 text-center">
                    <i class="fas fa-briefcase text-3xl text-slate-300" aria-hidden="true"></i>
                    <h3 class="font-display mt-4 text-2xl font-semibold">No roles match those filters</h3>
                    <p class="mt-2 text-sm text-slate-600">Clear a filter or try a broader search.</p>
                    <button type="button" wire:click="clearFilters"
                        class="mt-6 bg-[#0b1830] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#18375f]">
                        View all roles
                    </button>
                </div>
            @endif
        </div>
    </main>

    <section class="border-t border-[#0b1830]/10 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-5 px-5 py-10 sm:px-8 md:flex-row md:items-center md:justify-between lg:px-10">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#d95318]">Nothing suitable yet?</p>
                <p class="font-display mt-1 text-2xl font-semibold">Follow our latest opportunities.</p>
            </div>
            <a href="{{ route('contact') }}"
                class="inline-flex items-center gap-3 self-start text-sm font-bold text-[#0b1830] transition hover:text-[#d95318] md:self-auto">
                Contact the station
                <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
            </a>
        </div>
    </section>
</div>
