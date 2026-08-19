<div class="min-h-screen bg-[#f6f2e9] text-[#0b1830]">
    @normalizeArray($profile)
    <section class="bg-[#07172f] text-white">
        <div class="mx-auto max-w-7xl px-5 py-6 sm:px-8 lg:px-10">
            <x-ad-slot placement="staff-profile" />
            <nav class="flex items-center gap-2 text-xs text-slate-400" aria-label="Breadcrumb">
                <a href="{{ route('staff.index') }}" class="transition hover:text-white">Team</a>
                <i class="fas fa-chevron-right text-[0.55rem]" aria-hidden="true"></i>
                <span class="text-slate-200">{{ $profile['name'] }}</span>
            </nav>
        </div>

        <div class="mx-auto grid max-w-7xl gap-10 px-5 pb-16 sm:px-8 md:grid-cols-[18rem_1fr] lg:gap-16 lg:px-10 lg:pb-20">
            <div class="aspect-[4/5] overflow-hidden bg-white/5">
                <x-initials-image
                    :src="$profile['photo'] ?? null"
                    :title="$profile['name'] ?? ''"
                    imgClass="h-full w-full object-cover"
                    fallbackClass="h-full w-full bg-[#17375f]"
                    textClass="text-6xl font-display font-semibold text-white"
                />
            </div>
            <div class="self-center">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-[#ff8a2a]">{{ $profile['department'] }}</p>
                <h1 class="font-display mt-3 text-5xl font-semibold leading-none tracking-tight sm:text-6xl">{{ $profile['name'] }}</h1>
                <p class="mt-4 text-lg text-slate-300">{{ $profile['role'] }}</p>
            </div>
        </div>
    </section>

    <main class="py-14 lg:py-20">
        <div class="mx-auto grid max-w-5xl gap-12 px-5 sm:px-8 lg:grid-cols-[1fr_18rem]">
            <section>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#d95318]">{{ $profile['type_label'] }}</p>
                <h2 class="font-display mt-2 text-3xl font-semibold">About {{ $profile['name'] }}</h2>
                <p class="mt-6 whitespace-pre-line text-base leading-8 text-slate-700">
                    {{ $profile['bio'] ?? 'More profile information is coming soon.' }}
                </p>
            </section>

            <aside class="self-start border-t-4 border-[#f36b21] bg-white p-6">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#d95318]">Details</p>
                <dl class="mt-4 divide-y divide-[#0b1830]/10 text-sm">
                    <div class="py-4">
                        <dt class="text-xs text-slate-500">Role</dt>
                        <dd class="mt-1 font-semibold">{{ $profile['role'] }}</dd>
                    </div>
                    <div class="py-4">
                        <dt class="text-xs text-slate-500">Department</dt>
                        <dd class="mt-1 font-semibold">{{ $profile['department'] }}</dd>
                    </div>
                    @if(!empty($profile['email']))
                        <div class="py-4">
                            <dt class="text-xs text-slate-500">Email</dt>
                            <dd class="mt-1 break-all font-semibold"><a href="mailto:{{ $profile['email'] }}" class="hover:text-[#d95318]">{{ $profile['email'] }}</a></dd>
                        </div>
                    @endif
                    @if(!empty($profile['phone']))
                        <div class="py-4">
                            <dt class="text-xs text-slate-500">Phone</dt>
                            <dd class="mt-1 font-semibold"><a href="tel:{{ preg_replace('/[^0-9+]/', '', $profile['phone']) }}" class="hover:text-[#d95318]">{{ $profile['phone'] }}</a></dd>
                        </div>
                    @endif
                </dl>

                @php($socialLinks = is_array($profile['social_links'] ?? null) ? array_filter($profile['social_links']) : [])
                @if(count($socialLinks))
                    <div class="mt-5 flex flex-wrap gap-2 border-t border-[#0b1830]/10 pt-5">
                        @foreach($socialLinks as $platform => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                                class="inline-flex h-10 w-10 items-center justify-center border border-[#0b1830]/15 text-slate-500 transition hover:border-[#f36b21] hover:text-[#d95318]"
                                aria-label="{{ ucfirst($platform) }}">
                                <i class="{{ $platform === 'website' ? 'fas fa-globe' : 'fab fa-' . ($platform === 'linkedin' ? 'linkedin-in' : $platform) }}" aria-hidden="true"></i>
                            </a>
                        @endforeach
                    </div>
                @endif
            </aside>
        </div>
    </main>
</div>
