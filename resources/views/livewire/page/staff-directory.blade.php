<div class="min-h-screen bg-[#f6f2e9] text-[#0b1830]">
    <section class="bg-[#07172f] text-white">
        <div class="mx-auto max-w-7xl px-5 py-16 sm:px-8 lg:px-10 lg:py-20">
            <x-ad-slot placement="staff-directory" />
            <div class="grid gap-8 lg:grid-cols-[1fr_20rem] lg:items-end">
                <div class="max-w-3xl">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#ff8a2a]">Inside Glow</p>
                    <h1 class="font-display mt-4 text-5xl font-semibold leading-none tracking-tight sm:text-6xl">Meet the team.</h1>
                    <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">
                        The people producing, reporting, building and supporting everything you experience from Glow FM.
                    </p>
                </div>
                <label class="relative block">
                    <span class="sr-only">Search the team</span>
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-xs text-slate-400" aria-hidden="true"></i>
                    <input type="search" wire:model.live.debounce.500ms="searchQuery" placeholder="Search people or departments"
                        class="w-full border border-white/25 bg-white/10 py-3.5 pl-10 pr-4 text-sm text-white outline-none transition placeholder:text-slate-400 focus:border-[#ff8a2a] focus:ring-1 focus:ring-[#ff8a2a]">
                </label>
            </div>
        </div>
    </section>

    <main class="py-14 lg:py-20">
        <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">
            @if($staffProfiles->count() > 0)
                <div class="mb-8 flex items-end justify-between gap-5">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#d95318]">Our people</p>
                        <h2 class="font-display mt-2 text-3xl font-semibold sm:text-4xl">The team behind the signal</h2>
                    </div>
                    <p class="hidden text-sm text-slate-500 sm:block">{{ $staffProfiles->total() }} team {{ \Illuminate\Support\Str::plural('member', $staffProfiles->total()) }}</p>
                </div>

                <div class="grid gap-x-7 gap-y-11 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($staffProfiles as $staff)
                        @continueIfNotArray($staff)
                        <article class="group">
                            <a href="{{ $staff['profile_url'] }}"
                                class="block focus:outline-none focus:ring-2 focus:ring-[#f36b21] focus:ring-offset-4">
                                <div class="aspect-[4/5] overflow-hidden bg-[#dfe4e8]">
                                    <x-initials-image
                                        :src="$staff['photo'] ?? null"
                                        :title="$staff['name'] ?? ''"
                                        imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                                        fallbackClass="h-full w-full bg-[#17375f]"
                                        textClass="text-5xl font-display font-semibold text-white"
                                    />
                                </div>
                                <p class="mt-5 text-[0.68rem] font-bold uppercase tracking-[0.16em] text-[#d95318]">{{ $staff['department'] }}</p>
                                <h3 class="font-display mt-1 text-2xl font-semibold leading-tight transition group-hover:text-[#d95318]">{{ $staff['name'] }}</h3>
                                <p class="mt-1 text-sm text-slate-600">{{ $staff['role'] }}</p>
                            </a>
                            @if($staff['bio'])
                                <p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-600">{{ strip_tags($staff['bio']) }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>

                <div class="mt-12 border-t border-[#0b1830]/10 pt-8">
                    {{ $staffProfiles->links() }}
                </div>
            @else
                <div class="border border-dashed border-[#0b1830]/20 bg-white px-6 py-16 text-center">
                    <i class="fas fa-users text-3xl text-slate-300" aria-hidden="true"></i>
                    <h2 class="font-display mt-4 text-2xl font-semibold">No team members found</h2>
                    <p class="mt-2 text-sm text-slate-600">Try a different name, role or department.</p>
                </div>
            @endif
        </div>
    </main>
</div>
