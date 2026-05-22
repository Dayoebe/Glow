<div class="bg-gray-50">
    <section class="bg-slate-950 text-white">
        <div class="container mx-auto px-4 py-16">
            <div class="max-w-4xl">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-emerald-300">Media Partnerships</p>
                <h1 class="mt-4 text-4xl font-black leading-tight md:text-6xl">Advertise With {{ $station['name'] }}</h1>
                <p class="mt-5 max-w-3xl text-lg leading-relaxed text-slate-200">
                    Reach audiences in Akure, Ondo State, and online through radio advertising, sponsored programs,
                    social media promotion, live coverage, interviews, jingles, podcasts, and Glow TV packages.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('contact', ['inquiry_type' => 'advertising']) }}"
                        class="inline-flex items-center rounded-full bg-emerald-500 px-6 py-3 font-bold text-white shadow-lg transition hover:bg-emerald-600">
                        <i class="fas fa-bullhorn mr-2"></i>
                        Start A Campaign
                    </a>
                    <a href="tel:{{ $station['phone'] }}"
                        class="inline-flex items-center rounded-full border border-white/25 px-6 py-3 font-semibold text-white transition hover:bg-white/10">
                        <i class="fas fa-phone mr-2"></i>
                        {{ $station['phone'] }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-14">
        <div class="container mx-auto px-4">
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach([
                    ['icon' => 'fas fa-radio', 'title' => 'Radio Advertising', 'body' => 'Commercial spots, campaign mentions, live reads, station IDs, and recurring airtime packages.'],
                    ['icon' => 'fas fa-microphone-lines', 'title' => 'Sponsored Programs', 'body' => 'Brand-supported shows, public affairs segments, health messages, youth-focused conversations, and community development features.'],
                    ['icon' => 'fas fa-video', 'title' => 'Glow TV Packages', 'body' => 'Video interviews, event coverage, studio conversations, and content packaged for digital audiences.'],
                    ['icon' => 'fas fa-bullseye', 'title' => 'Jingles And Production', 'body' => 'Audio branding, promo production, campaign scripts, and professional broadcast-ready placements.'],
                    ['icon' => 'fas fa-users-viewfinder', 'title' => 'Interviews And Features', 'body' => 'Guest appearances, executive interviews, product conversations, and issue-based advocacy slots.'],
                    ['icon' => 'fas fa-share-nodes', 'title' => 'Digital Promotion', 'body' => 'Website visibility, podcast mentions, social media amplification, and multimedia campaign support.'],
                ] as $offer)
                    <article class="rounded-2xl bg-white p-6 shadow-lg">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                            <i class="{{ $offer['icon'] }} text-xl"></i>
                        </div>
                        <h2 class="mt-5 text-xl font-bold text-slate-900">{{ $offer['title'] }}</h2>
                        <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $offer['body'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="pb-16">
        <div class="container mx-auto grid gap-8 px-4 lg:grid-cols-[1fr_22rem]">
            <div class="rounded-2xl bg-white p-6 shadow-lg">
                <h2 class="text-2xl font-bold text-slate-900">Who This Is For</h2>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    @foreach([
                        'Businesses targeting Akure and Ondo State audiences',
                        'Public agencies running awareness campaigns',
                        'Event organizers seeking coverage and publicity',
                        'Brands launching products, services, or community activations',
                        'Artists, creators, and entertainers promoting releases',
                        'NGOs and institutions sharing public-interest messages',
                    ] as $item)
                        <div class="flex gap-3 rounded-xl bg-slate-50 p-4">
                            <i class="fas fa-check-circle mt-1 text-emerald-600"></i>
                            <p class="text-sm font-medium text-slate-700">{{ $item }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <aside class="rounded-2xl bg-emerald-700 p-6 text-white shadow-lg">
                <h2 class="text-2xl font-bold">Request A Media Plan</h2>
                <p class="mt-3 text-sm leading-relaxed text-emerald-50">
                    Share your campaign goal, preferred dates, target audience, and budget range. The Glow 99.1 FM team
                    will respond with suitable placement options.
                </p>
                <div class="mt-6 space-y-3 text-sm">
                    <a href="mailto:{{ $station['email'] }}" class="flex items-center gap-3 rounded-xl bg-white/10 px-4 py-3 hover:bg-white/15">
                        <i class="fas fa-envelope"></i>
                        <span class="break-all">{{ $station['email'] }}</span>
                    </a>
                    <a href="tel:{{ $station['phone'] }}" class="flex items-center gap-3 rounded-xl bg-white/10 px-4 py-3 hover:bg-white/15">
                        <i class="fas fa-phone"></i>
                        <span>{{ $station['phone'] }}</span>
                    </a>
                    <a href="{{ route('contact') }}" class="flex items-center gap-3 rounded-xl bg-white px-4 py-3 font-bold text-emerald-800 hover:bg-emerald-50">
                        <i class="fas fa-paper-plane"></i>
                        <span>Contact Page</span>
                    </a>
                </div>
            </aside>
        </div>
    </section>
</div>
