<div class="min-h-screen bg-[#f6f2e9] text-[#0b1830]">
    <section class="bg-[#07172f] text-white">
        <div class="mx-auto max-w-7xl px-5 py-6 sm:px-8 lg:px-10">
            <x-ad-slot placement="oap-detail" />
            <nav class="flex items-center gap-2 text-xs text-slate-400" aria-label="Breadcrumb">
                <a href="{{ route('oaps.index') }}" class="transition hover:text-white">Presenters</a>
                <i class="fas fa-chevron-right text-[0.55rem]" aria-hidden="true"></i>
                <span class="text-slate-200">{{ $oap->name }}</span>
            </nav>
        </div>

        <div class="mx-auto grid max-w-7xl gap-10 px-5 pb-16 sm:px-8 md:grid-cols-[18rem_1fr] lg:gap-16 lg:px-10 lg:pb-20">
            <div class="aspect-[4/5] overflow-hidden bg-white/5">
                <x-initials-image
                    :src="$oap->profile_photo"
                    :title="$oap->name"
                    imgClass="h-full w-full object-cover"
                    fallbackClass="h-full w-full bg-[#17375f]"
                    textClass="text-6xl font-display font-semibold text-white"
                />
            </div>
            <div class="self-center">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-[#ff8a2a]">{{ $oap->department?->name ?? 'Broadcast' }}</p>
                <h1 class="font-display mt-3 text-5xl font-semibold leading-none tracking-tight sm:text-6xl">{{ $oap->name }}</h1>
                <p class="mt-4 text-lg text-slate-300">{{ $oap->teamRole?->name ?? ($oap->employment_status ?? 'Presenter') }}</p>

                @php($socialLinks = array_filter($oap->public_social_links ?? []))
                @if(count($socialLinks))
                    <div class="mt-7 flex flex-wrap items-center gap-2">
                        @foreach($socialLinks as $platform => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                                class="inline-flex h-11 w-11 items-center justify-center border border-white/25 text-slate-300 transition hover:border-white hover:bg-white/10 hover:text-white"
                                aria-label="{{ ucfirst($platform) }}">
                                <i class="fab fa-{{ $platform === 'linkedin' ? 'linkedin-in' : $platform }}" aria-hidden="true"></i>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    <main class="py-14 lg:py-20">
        <div class="mx-auto grid max-w-7xl gap-12 px-5 sm:px-8 lg:grid-cols-[1fr_19rem] lg:px-10">
            <div>
                <section>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#d95318]">Behind the microphone</p>
                    <h2 class="font-display mt-2 text-3xl font-semibold">About {{ $oap->name }}</h2>
                    <p class="mt-6 max-w-3xl whitespace-pre-line text-base leading-8 text-slate-700">
                        {{ $oap->bio ?: 'More about this presenter is coming soon.' }}
                    </p>
                </section>

                <section class="mt-12 border-t border-[#0b1830]/10 pt-10">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#d95318]">Listen in</p>
                    <h2 class="font-display mt-2 text-3xl font-semibold">Shows</h2>
                    <div class="mt-6 divide-y divide-[#0b1830]/10 border-y border-[#0b1830]/10">
                        @forelse($oap->shows as $show)
                            <a href="{{ route('shows.show', $show->slug) }}"
                                class="group flex items-center justify-between gap-5 py-5 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#f36b21]">
                                <span>
                                    <small class="text-[0.68rem] font-bold uppercase tracking-[0.14em] text-[#d95318]">{{ $show->category?->name ?? 'Programme' }}</small>
                                    <strong class="font-display mt-1 block text-xl font-semibold transition group-hover:text-[#d95318]">{{ $show->title }}</strong>
                                    @if($show->description)
                                        <span class="mt-2 line-clamp-1 block text-sm text-slate-600">{{ strip_tags($show->description) }}</span>
                                    @endif
                                </span>
                                <i class="fas fa-arrow-right text-xs text-[#d95318]" aria-hidden="true"></i>
                            </a>
                        @empty
                            <p class="py-8 text-sm text-slate-500">No programmes are currently assigned.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <aside class="self-start border-t-4 border-[#f36b21] bg-white p-6">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#d95318]">Profile</p>
                <dl class="mt-4 divide-y divide-[#0b1830]/10 text-sm">
                    <div class="py-4">
                        <dt class="text-xs text-slate-500">Role</dt>
                        <dd class="mt-1 font-semibold">{{ $oap->teamRole?->name ?? ($oap->employment_status ?? 'Presenter') }}</dd>
                    </div>
                    <div class="py-4">
                        <dt class="text-xs text-slate-500">Department</dt>
                        <dd class="mt-1 font-semibold">{{ $oap->department?->name ?? 'Broadcast' }}</dd>
                    </div>
                    @if(!empty($oap->specializations))
                        <div class="py-4">
                            <dt class="text-xs text-slate-500">Specialities</dt>
                            <dd class="mt-2 flex flex-wrap gap-2">
                                @foreach($oap->specializations as $specialization)
                                    <span class="border border-[#0b1830]/15 px-2.5 py-1 text-xs">{{ $specialization }}</span>
                                @endforeach
                            </dd>
                        </div>
                    @endif
                    @if($oap->email)
                        <div class="py-4">
                            <dt class="text-xs text-slate-500">Email</dt>
                            <dd class="mt-1 break-all font-semibold"><a href="mailto:{{ $oap->email }}" class="hover:text-[#d95318]">{{ $oap->email }}</a></dd>
                        </div>
                    @endif
                    @if($oap->phone)
                        <div class="py-4">
                            <dt class="text-xs text-slate-500">Phone</dt>
                            <dd class="mt-1 font-semibold"><a href="tel:{{ preg_replace('/[^0-9+]/', '', $oap->phone) }}" class="hover:text-[#d95318]">{{ $oap->phone }}</a></dd>
                        </div>
                    @endif
                </dl>
            </aside>
        </div>
    </main>
</div>
