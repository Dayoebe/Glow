<div class="bg-[#f7f4ee] text-slate-950">
    @php
        $stationName = trim((string) data_get($station, 'name', 'Glow 99.1 FM'));
        $stationPhone = trim((string) data_get($station, 'phone', ''));
        $stationPhoneHref = preg_replace('/[^0-9+]/', '', $stationPhone);
        $stationEmail = trim((string) data_get($station, 'email', ''));
        $stationAddress = trim((string) data_get($station, 'address', ''));
    @endphp

    <section class="relative isolate overflow-hidden bg-[#07182b] text-white">
        <div
            class="absolute inset-0 -z-10"
            style="background-image: radial-gradient(circle at 84% 18%, rgba(243, 106, 33, .25), transparent 32%), radial-gradient(circle at 8% 92%, rgba(45, 87, 125, .4), transparent 34%);"
        ></div>
        <div class="mx-auto grid max-w-[1440px] gap-10 px-4 py-14 sm:px-6 sm:py-16 lg:grid-cols-[minmax(0,1.12fr)_minmax(320px,0.88fr)] lg:items-end lg:gap-16 lg:px-8 lg:py-20">
            <div class="max-w-4xl">
                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-orange-300">Advertising &amp; partnerships</p>
                <h1 class="mt-4 text-4xl font-black leading-[1.02] tracking-[-0.045em] sm:text-5xl lg:text-6xl">
                    Start a conversation with {{ $stationName }}
                </h1>
                <p class="mt-6 max-w-3xl text-base leading-7 text-slate-300 sm:text-lg">
                    Tell us what you want your campaign to achieve. The station team can explain the current opportunities, timing, and next steps relevant to your brief.
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a
                        href="{{ route('contact', ['inquiry_type' => 'advertising']) }}#contact-form"
                        class="inline-flex min-h-12 items-center justify-center gap-2 rounded-lg bg-[#f36a21] px-6 py-3 text-sm font-extrabold text-white transition hover:bg-[#ff7a30]"
                    >
                        Send a campaign brief
                        <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                    </a>
                    @if($stationPhone !== '')
                        <a
                            href="tel:{{ $stationPhoneHref }}"
                            class="inline-flex min-h-12 items-center justify-center gap-2 rounded-lg border border-white/20 bg-white/[0.06] px-6 py-3 text-sm font-extrabold text-white transition hover:bg-white/10"
                        >
                            <i class="fas fa-phone text-orange-300" aria-hidden="true"></i>
                            {{ $stationPhone }}
                        </a>
                    @endif
                </div>
            </div>

            <aside class="rounded-xl border border-white/15 bg-white/[0.06] p-6 sm:p-7">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-orange-300">A useful first message</p>
                <ul class="mt-5 space-y-4 text-sm text-slate-300">
                    <li class="flex gap-3">
                        <i class="fas fa-check mt-1 text-[10px] text-orange-300" aria-hidden="true"></i>
                        <span>Your campaign goal and intended audience</span>
                    </li>
                    <li class="flex gap-3">
                        <i class="fas fa-check mt-1 text-[10px] text-orange-300" aria-hidden="true"></i>
                        <span>Preferred dates or campaign period</span>
                    </li>
                    <li class="flex gap-3">
                        <i class="fas fa-check mt-1 text-[10px] text-orange-300" aria-hidden="true"></i>
                        <span>Location or market you want to reach</span>
                    </li>
                    <li class="flex gap-3">
                        <i class="fas fa-check mt-1 text-[10px] text-orange-300" aria-hidden="true"></i>
                        <span>Your contact details and budget range</span>
                    </li>
                </ul>
            </aside>
        </div>
    </section>

    <section class="bg-white py-16 sm:py-20" aria-labelledby="planning-heading">
        <div class="mx-auto grid max-w-[1200px] gap-10 px-4 sm:px-6 lg:grid-cols-[minmax(0,1fr)_minmax(320px,0.72fr)] lg:gap-14 lg:px-8">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#e95516]">Plan with clarity</p>
                <h2 id="planning-heading" class="mt-2 text-3xl font-black tracking-[-0.035em] text-[#07182b] sm:text-4xl">
                    Bring the brief. We’ll discuss what is available.
                </h2>
                <p class="mt-5 max-w-3xl text-base leading-7 text-slate-600">
                    Availability and placement depend on the campaign, station schedule, dates, and agreed terms. Contacting the team first gives you current information without assuming a package or price.
                </p>

                <div class="mt-9 divide-y divide-slate-200 border-y border-slate-200">
                    <article class="grid gap-3 py-6 sm:grid-cols-[44px_minmax(0,1fr)] sm:gap-5">
                        <span class="text-2xl font-black text-[#e95516]">01</span>
                        <div>
                            <h3 class="text-lg font-black text-[#07182b]">Share the objective</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Explain the message, intended audience, market, and action you want people to take.</p>
                        </div>
                    </article>
                    <article class="grid gap-3 py-6 sm:grid-cols-[44px_minmax(0,1fr)] sm:gap-5">
                        <span class="text-2xl font-black text-[#e95516]">02</span>
                        <div>
                            <h3 class="text-lg font-black text-[#07182b]">Discuss current options</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">The Glow FM team will respond with the formats and timing currently available for consideration.</p>
                        </div>
                    </article>
                    <article class="grid gap-3 py-6 sm:grid-cols-[44px_minmax(0,1fr)] sm:gap-5">
                        <span class="text-2xl font-black text-[#e95516]">03</span>
                        <div>
                            <h3 class="text-lg font-black text-[#07182b]">Confirm before placement</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">Dates, deliverables, approvals, and commercial terms should be agreed directly with the station.</p>
                        </div>
                    </article>
                </div>
            </div>

            <aside class="self-start rounded-xl bg-[#07182b] p-7 text-white sm:p-8">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-orange-300">Speak with Glow FM</p>
                <h2 class="mt-3 text-2xl font-black tracking-[-0.025em]">Ready to share your brief?</h2>
                <p class="mt-3 text-sm leading-6 text-slate-300">
                    Use the advertising option on the contact form so your message is clearly identified.
                </p>
                <a
                    href="{{ route('contact', ['inquiry_type' => 'advertising']) }}#contact-form"
                    class="mt-6 inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-lg bg-[#f36a21] px-5 py-3 text-sm font-extrabold text-white transition hover:bg-[#ff7a30]"
                >
                    Open contact form
                    <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                </a>

                @if($stationEmail !== '' || $stationPhone !== '' || $stationAddress !== '')
                    <div class="mt-6 divide-y divide-white/10 border-t border-white/10">
                        @if($stationEmail !== '')
                            <a href="mailto:{{ $stationEmail }}" class="flex min-w-0 items-center gap-3 py-4 text-sm text-slate-300 transition hover:text-white">
                                <i class="fas fa-envelope w-4 shrink-0 text-orange-300" aria-hidden="true"></i>
                                <span class="break-all">{{ $stationEmail }}</span>
                            </a>
                        @endif
                        @if($stationPhone !== '')
                            <a href="tel:{{ $stationPhoneHref }}" class="flex items-center gap-3 py-4 text-sm text-slate-300 transition hover:text-white">
                                <i class="fas fa-phone w-4 text-orange-300" aria-hidden="true"></i>
                                <span>{{ $stationPhone }}</span>
                            </a>
                        @endif
                        @if($stationAddress !== '')
                            <p class="flex gap-3 py-4 text-sm leading-6 text-slate-300">
                                <i class="fas fa-map-marker-alt mt-1 w-4 shrink-0 text-orange-300" aria-hidden="true"></i>
                                <span>{{ $stationAddress }}</span>
                            </p>
                        @endif
                    </div>
                @endif
            </aside>
        </div>
    </section>
</div>
