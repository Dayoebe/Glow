<div
    class="min-h-screen bg-[#f6f2e9] text-[#0b1830]"
    x-data="{
        lightboxOpen: false,
        activePhoto: null,
        openLightbox(photo) {
            this.activePhoto = photo;
            this.lightboxOpen = true;
            document.body.classList.add('overflow-hidden');
        },
        closeLightbox() {
            this.lightboxOpen = false;
            this.activePhoto = null;
            document.body.classList.remove('overflow-hidden');
        }
    }"
    @keydown.escape.window="closeLightbox()"
>
    <section class="bg-[#07172f] text-white">
        <div class="mx-auto grid max-w-7xl gap-12 px-5 py-14 sm:px-8 lg:grid-cols-[0.9fr_1.1fr] lg:items-center lg:px-10 lg:py-20">
            <div class="max-w-2xl">
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#ff8a2a]">Stay with Vettas</p>
                <h1 class="font-display mt-4 text-5xl font-semibold leading-[0.98] tracking-tight sm:text-6xl">Space to arrive, settle and breathe.</h1>
                <p class="mt-6 text-lg leading-8 text-slate-300">
                    A furnished private stay designed for comfort, ease and a slower pace.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="#reservation"
                        class="inline-flex items-center gap-3 bg-[#f36b21] px-6 py-3.5 text-sm font-bold text-white transition hover:bg-[#ff7d32] focus:outline-none focus:ring-2 focus:ring-white">
                        Request a reservation
                        <i class="fas fa-arrow-down text-xs" aria-hidden="true"></i>
                    </a>
                    <a href="#gallery"
                        class="inline-flex items-center gap-3 border border-white/30 px-6 py-3.5 text-sm font-bold text-white transition hover:border-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white">
                        View the gallery
                    </a>
                </div>
                <p class="mt-6 text-xs text-slate-400">{{ number_format($photos->total()) }} published {{ \Illuminate\Support\Str::plural('photo', $photos->total()) }}</p>
            </div>

            @if($featuredPhotos->isNotEmpty())
                <div class="grid grid-cols-2 gap-3">
                    @foreach($featuredPhotos->take(3) as $index => $featuredPhoto)
                        @php
                            $featuredImageUrl = $featuredPhoto->public_image_url;
                            $featuredLightbox = [
                                'image_path' => $featuredImageUrl,
                                'alt_text' => $featuredPhoto->alt_text ?: $featuredPhoto->title,
                                'title' => $featuredPhoto->title,
                                'caption' => $featuredPhoto->caption,
                                'description' => $featuredPhoto->description,
                                'category' => $featuredPhoto->category?->name,
                                'location' => $featuredPhoto->location,
                                'credit' => $featuredPhoto->photographer_name ?: 'Glow FM Media Team',
                                'display_date' => $featuredPhoto->display_date,
                            ];
                        @endphp
                        <button type="button" @click='openLightbox(@json($featuredLightbox))'
                            class="group relative overflow-hidden bg-white/5 text-left focus:outline-none focus:ring-2 focus:ring-[#ff8a2a] {{ $index === 0 ? 'col-span-2 aspect-[16/8]' : 'aspect-square' }}">
                            <span class="absolute inset-0 flex items-center justify-center bg-[#142b4b] text-3xl text-white/20" aria-hidden="true">
                                <i class="fas fa-image"></i>
                            </span>
                            @if($featuredImageUrl)
                                <img src="{{ $featuredImageUrl }}"
                                    alt="{{ $featuredPhoto->alt_text ?: $featuredPhoto->title }}"
                                    onerror="this.style.display='none'"
                                    class="relative h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]">
                            @endif
                            <span class="absolute inset-0 bg-gradient-to-t from-[#07172f]/85 via-transparent to-transparent" aria-hidden="true"></span>
                            <span class="absolute inset-x-0 bottom-0 p-4 sm:p-5">
                                <small class="text-[0.65rem] font-bold uppercase tracking-[0.17em] text-[#ff9b4b]">{{ $featuredPhoto->category?->name ?? 'Vettas' }}</small>
                                <strong class="font-display mt-1 block text-lg font-semibold text-white sm:text-xl">{{ $featuredPhoto->title }}</strong>
                            </span>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="border-b border-[#0b1830]/10 bg-white py-14 lg:py-20">
        <div class="mx-auto grid max-w-7xl gap-12 px-5 sm:px-8 lg:grid-cols-[1fr_21rem] lg:px-10">
            <article>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#d95318]">{{ $aboutContent['eyebrow'] ?? 'About Vettas' }}</p>
                <h2 class="font-display mt-2 max-w-3xl text-4xl font-semibold leading-tight tracking-tight">
                    {{ $aboutContent['title'] ?? 'A private stay built around comfort and ease' }}
                </h2>
                <p class="mt-6 max-w-3xl text-base leading-8 text-slate-700">{{ $aboutContent['summary'] ?? '' }}</p>

                @if(!empty($aboutContent['highlights']))
                    <ul class="mt-8 grid gap-px border border-[#0b1830]/10 bg-[#0b1830]/10 sm:grid-cols-3">
                        @foreach($aboutContent['highlights'] as $highlight)
                            <li class="flex items-start gap-3 bg-white p-5 text-sm font-semibold leading-6">
                                <i class="fas fa-check mt-1 text-xs text-[#d95318]" aria-hidden="true"></i>
                                {{ $highlight }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </article>

            @if($hasContactDetails)
                <aside class="self-start border-t-4 border-[#f36b21] bg-[#07172f] p-6 text-white">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#ff8a2a]">Enquiries</p>
                    <h2 class="font-display mt-2 text-2xl font-semibold">{{ $contactContent['title'] ?? 'Book or make an enquiry' }}</h2>
                    @if(!empty($contactContent['intro']))
                        <p class="mt-3 text-sm leading-6 text-slate-300">{{ $contactContent['intro'] }}</p>
                    @endif

                    @if(!empty($contactMethods))
                        <div class="mt-5 divide-y divide-white/10 border-y border-white/10">
                            @foreach($contactMethods as $method)
                                <a href="{{ $method['href'] }}"
                                    @if(str_starts_with($method['href'], 'http')) target="_blank" rel="noopener noreferrer" @endif
                                    class="flex items-center justify-between gap-4 py-4 text-sm transition hover:text-[#ff8a2a]">
                                    <span>
                                        <small class="block text-[0.65rem] uppercase tracking-[0.14em] text-slate-400">{{ $method['label'] }}</small>
                                        <strong class="mt-1 block break-all">{{ $method['value'] }}</strong>
                                    </span>
                                    <i class="{{ $method['icon'] }} text-xs" aria-hidden="true"></i>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($contactContent['address']))
                        <div class="mt-5 text-sm leading-6">
                            <p class="text-xs text-slate-400">Address</p>
                            <p class="mt-1">{{ $contactContent['address'] }}</p>
                        </div>
                    @endif
                    @if(!empty($contactContent['hours']))
                        <div class="mt-4 text-sm leading-6">
                            <p class="text-xs text-slate-400">Availability</p>
                            <p class="mt-1">{{ $contactContent['hours'] }}</p>
                        </div>
                    @endif

                    @if($websiteLink || $instagramLink)
                        <div class="mt-6 flex flex-wrap gap-2 border-t border-white/10 pt-5">
                            @if($websiteLink)
                                <a href="{{ $websiteLink }}" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex h-10 w-10 items-center justify-center border border-white/20 transition hover:border-[#ff8a2a] hover:text-[#ff8a2a]"
                                    aria-label="Vettas website"><i class="fas fa-globe" aria-hidden="true"></i></a>
                            @endif
                            @if($instagramLink)
                                <a href="{{ $instagramLink }}" target="_blank" rel="noopener noreferrer"
                                    class="inline-flex h-10 w-10 items-center justify-center border border-white/20 transition hover:border-[#ff8a2a] hover:text-[#ff8a2a]"
                                    aria-label="Vettas on Instagram"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                            @endif
                        </div>
                    @endif
                </aside>
            @endif
        </div>
    </section>

    <section id="reservation" class="scroll-mt-28 py-14 lg:py-20">
        <div class="mx-auto grid max-w-7xl gap-10 px-5 sm:px-8 lg:grid-cols-[21rem_1fr] lg:px-10">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#d95318]">Reservation request</p>
                <h2 class="font-display mt-2 text-4xl font-semibold leading-tight tracking-tight">Tell us when you’d like to stay.</h2>
                <p class="mt-5 text-sm leading-7 text-slate-600">
                    Send your preferred dates and guest details. The Vettas team will confirm availability, pricing and next steps directly with you.
                </p>
                <div class="mt-7 border-l-2 border-[#f36b21] pl-5 text-sm leading-6 text-slate-600">
                    A reservation request is not a confirmed booking until the team contacts you.
                </div>
            </div>

            <div class="border border-[#0b1830]/10 bg-white p-6 sm:p-8">
                @if(session()->has('vettas_reservation_success'))
                    <div class="mb-6 border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm leading-6 text-emerald-800" role="status">
                        {{ session('vettas_reservation_success') }}
                    </div>
                @endif

                <form wire:submit.prevent="submitReservation" class="grid gap-5 md:grid-cols-2">
                    <label class="block">
                        <span class="text-sm font-semibold">Full name <span class="text-[#d95318]">*</span></span>
                        <input type="text" wire:model="full_name" autocomplete="name"
                            class="mt-2 w-full border border-[#0b1830]/20 px-4 py-3 text-sm outline-none focus:border-[#f36b21] focus:ring-1 focus:ring-[#f36b21]">
                        @error('full_name') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="block">
                        <span class="text-sm font-semibold">Email <span class="text-[#d95318]">*</span></span>
                        <input type="email" wire:model="email" autocomplete="email"
                            class="mt-2 w-full border border-[#0b1830]/20 px-4 py-3 text-sm outline-none focus:border-[#f36b21] focus:ring-1 focus:ring-[#f36b21]">
                        @error('email') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="block">
                        <span class="text-sm font-semibold">Phone <span class="text-[#d95318]">*</span></span>
                        <input type="tel" wire:model="phone" autocomplete="tel"
                            class="mt-2 w-full border border-[#0b1830]/20 px-4 py-3 text-sm outline-none focus:border-[#f36b21] focus:ring-1 focus:ring-[#f36b21]">
                        @error('phone') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="block">
                        <span class="text-sm font-semibold">Guests <span class="text-[#d95318]">*</span></span>
                        <input type="number" min="1" max="12" wire:model="guest_count"
                            class="mt-2 w-full border border-[#0b1830]/20 px-4 py-3 text-sm outline-none focus:border-[#f36b21] focus:ring-1 focus:ring-[#f36b21]">
                        @error('guest_count') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="block">
                        <span class="text-sm font-semibold">Check-in <span class="text-[#d95318]">*</span></span>
                        <input type="date" wire:model="check_in_date" min="{{ now()->toDateString() }}"
                            class="mt-2 w-full border border-[#0b1830]/20 px-4 py-3 text-sm outline-none focus:border-[#f36b21] focus:ring-1 focus:ring-[#f36b21]">
                        @error('check_in_date') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="block">
                        <span class="text-sm font-semibold">Check-out <span class="text-[#d95318]">*</span></span>
                        <input type="date" wire:model="check_out_date" min="{{ now()->addDay()->toDateString() }}"
                            class="mt-2 w-full border border-[#0b1830]/20 px-4 py-3 text-sm outline-none focus:border-[#f36b21] focus:ring-1 focus:ring-[#f36b21]">
                        @error('check_out_date') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="block md:col-span-2">
                        <span class="text-sm font-semibold">Special requests</span>
                        <textarea rows="5" wire:model="special_requests"
                            placeholder="Arrival time, accessibility needs or anything else we should know"
                            class="mt-2 w-full border border-[#0b1830]/20 px-4 py-3 text-sm outline-none focus:border-[#f36b21] focus:ring-1 focus:ring-[#f36b21]"></textarea>
                        @error('special_requests') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <div class="md:col-span-2">
                        <button type="submit" wire:loading.attr="disabled" wire:target="submitReservation"
                            class="inline-flex items-center justify-center gap-3 bg-[#f36b21] px-6 py-3.5 text-sm font-bold text-white transition hover:bg-[#d95318] disabled:cursor-wait disabled:opacity-70">
                            <span wire:loading.remove wire:target="submitReservation">Send reservation request</span>
                            <span wire:loading wire:target="submitReservation">Sending…</span>
                            <i wire:loading.remove wire:target="submitReservation" class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section id="gallery" class="scroll-mt-28 border-y border-[#0b1830]/10 bg-white">
        <div class="mx-auto max-w-7xl px-5 py-8 sm:px-8 lg:px-10">
            <div class="flex gap-2 overflow-x-auto pb-2" aria-label="Gallery categories">
                <button type="button" wire:click="filterByCategory('')"
                    class="shrink-0 border px-4 py-2.5 text-xs font-bold transition {{ $category === '' ? 'border-[#0b1830] bg-[#0b1830] text-white' : 'border-[#0b1830]/20 text-[#0b1830] hover:border-[#f36b21] hover:text-[#d95318]' }}">
                    All photos
                </button>
                @foreach($categories as $categoryItem)
                    <button type="button" wire:click="filterByCategory('{{ $categoryItem->slug }}')"
                        class="shrink-0 border px-4 py-2.5 text-xs font-bold transition {{ $category === $categoryItem->slug ? 'border-[#0b1830] bg-[#0b1830] text-white' : 'border-[#0b1830]/20 text-[#0b1830] hover:border-[#f36b21] hover:text-[#d95318]' }}">
                        {{ $categoryItem->name }} <span class="ml-1 opacity-60">{{ $categoryItem->published_photos_count }}</span>
                    </button>
                @endforeach
            </div>

            <div class="mt-5 grid gap-3 sm:grid-cols-[1fr_13rem_auto]">
                <label class="relative block">
                    <span class="sr-only">Search gallery</span>
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-xs text-slate-400" aria-hidden="true"></i>
                    <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search the gallery"
                        class="w-full border border-[#0b1830]/20 bg-[#f6f2e9] py-3 pl-10 pr-4 text-sm outline-none focus:border-[#f36b21] focus:ring-1 focus:ring-[#f36b21]">
                </label>
                <label>
                    <span class="sr-only">Sort gallery</span>
                    <select wire:model.live="sortBy"
                        class="w-full border border-[#0b1830]/20 bg-[#f6f2e9] px-4 py-3 text-sm outline-none focus:border-[#f36b21] focus:ring-1 focus:ring-[#f36b21]">
                        <option value="latest">Latest first</option>
                        <option value="oldest">Oldest first</option>
                        <option value="category">By category</option>
                        <option value="featured">Featured first</option>
                    </select>
                </label>
                @if($category !== '' || $search !== '' || $sortBy !== 'latest')
                    <button type="button" wire:click="resetFilters"
                        class="border border-[#0b1830]/20 px-4 py-3 text-xs font-bold transition hover:border-[#f36b21] hover:text-[#d95318]">
                        Clear filters
                    </button>
                @endif
            </div>
        </div>
    </section>

    <section class="py-14 lg:py-20">
        <div class="mx-auto max-w-7xl px-5 sm:px-8 lg:px-10">
            <div class="mb-8 flex items-end justify-between gap-5">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#d95318]">Gallery</p>
                    <h2 class="font-display mt-2 text-3xl font-semibold sm:text-4xl">{{ $activeCategory?->name ?? 'Inside Vettas' }}</h2>
                </div>
                <p class="text-sm text-slate-500">{{ $photos->total() }} {{ \Illuminate\Support\Str::plural('photo', $photos->total()) }}</p>
            </div>

            @if($photos->count() > 0)
                <div class="grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($photos as $photo)
                        @php
                            $photoImageUrl = $photo->public_image_url;
                            $photoLightbox = [
                                'image_path' => $photoImageUrl,
                                'alt_text' => $photo->alt_text ?: $photo->title,
                                'title' => $photo->title,
                                'caption' => $photo->caption,
                                'description' => $photo->description,
                                'category' => $photo->category?->name,
                                'location' => $photo->location,
                                'credit' => $photo->photographer_name ?: 'Glow FM Media Team',
                                'display_date' => $photo->display_date,
                            ];
                        @endphp
                        <article class="group">
                            <button type="button" @click='openLightbox(@json($photoLightbox))'
                                class="relative block aspect-[4/5] w-full overflow-hidden bg-slate-200 text-left focus:outline-none focus:ring-2 focus:ring-[#f36b21] focus:ring-offset-4">
                                <span class="absolute inset-0 flex items-center justify-center bg-[#dfe4e8] text-3xl text-[#0b1830]/20" aria-hidden="true">
                                    <i class="fas fa-image"></i>
                                </span>
                                @if($photoImageUrl)
                                    <img src="{{ $photoImageUrl }}" alt="{{ $photo->alt_text ?: $photo->title }}" loading="lazy"
                                        onerror="this.style.display='none'"
                                        class="relative h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]">
                                @endif
                                <span class="absolute inset-0 bg-gradient-to-t from-[#07172f]/75 via-transparent to-transparent" aria-hidden="true"></span>
                                <span class="absolute left-4 top-4 bg-white px-3 py-1.5 text-[0.65rem] font-bold uppercase tracking-[0.13em] text-[#0b1830]">
                                    {{ $photo->category?->name ?? 'Gallery' }}
                                </span>
                                @if($photo->is_featured)
                                    <span class="absolute right-4 top-4 bg-[#f36b21] px-3 py-1.5 text-[0.65rem] font-bold uppercase tracking-[0.13em] text-white">Featured</span>
                                @endif
                                <span class="absolute inset-x-0 bottom-0 p-5 text-white">
                                    <strong class="font-display block text-2xl font-semibold">{{ $photo->title }}</strong>
                                    @if($photo->location || $photo->display_date)
                                        <small class="mt-2 block text-xs text-slate-200">{{ collect([$photo->location, $photo->display_date])->filter()->join(' · ') }}</small>
                                    @endif
                                </span>
                            </button>
                            @if($photo->caption)
                                <p class="mt-4 line-clamp-2 text-sm leading-6 text-slate-600">{{ $photo->caption }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>
                <div class="mt-12 border-t border-[#0b1830]/10 pt-8">{{ $photos->links() }}</div>
            @else
                <div class="border border-dashed border-[#0b1830]/20 bg-white px-6 py-16 text-center">
                    <i class="fas fa-camera text-3xl text-slate-300" aria-hidden="true"></i>
                    <h3 class="font-display mt-4 text-2xl font-semibold">No photos match this view</h3>
                    <p class="mt-2 text-sm text-slate-600">Try another category or search phrase.</p>
                    <button type="button" wire:click="resetFilters" class="mt-6 bg-[#0b1830] px-6 py-3 text-sm font-bold text-white">View all photos</button>
                </div>
            @endif
        </div>
    </section>

    <div x-cloak x-show="lightboxOpen" x-transition.opacity class="fixed inset-0 z-[100]" role="dialog" aria-modal="true" aria-label="Photo details">
        <button type="button" class="absolute inset-0 h-full w-full bg-[#020817]/90" @click="closeLightbox()" aria-label="Close photo"></button>
        <div class="relative flex min-h-screen items-center justify-center p-4 sm:p-8">
            <div class="relative grid max-h-[92vh] w-full max-w-6xl overflow-y-auto bg-white lg:grid-cols-[1.35fr_0.65fr]">
                <button type="button" @click="closeLightbox()"
                    class="absolute right-3 top-3 z-10 inline-flex h-11 w-11 items-center justify-center bg-[#07172f] text-white transition hover:bg-[#f36b21]"
                    aria-label="Close photo details">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
                <div class="relative flex min-h-[20rem] items-center justify-center bg-[#020817]">
                    <span class="absolute inset-0 flex items-center justify-center text-4xl text-white/20" aria-hidden="true">
                        <i class="fas fa-image"></i>
                    </span>
                    <img :src="activePhoto ? activePhoto.image_path : ''" :alt="activePhoto ? activePhoto.alt_text : ''"
                        x-on:load="$el.style.display = 'block'" x-on:error="$el.style.display = 'none'"
                        class="relative max-h-[86vh] w-full object-contain">
                </div>
                <div class="p-6 sm:p-8">
                    <div class="flex flex-wrap gap-3 text-[0.65rem] font-bold uppercase tracking-[0.16em] text-[#d95318]">
                        <template x-if="activePhoto && activePhoto.category"><span x-text="activePhoto.category"></span></template>
                        <template x-if="activePhoto && activePhoto.display_date"><span x-text="activePhoto.display_date"></span></template>
                    </div>
                    <h3 class="font-display mt-3 text-3xl font-semibold" x-text="activePhoto ? activePhoto.title : ''"></h3>
                    <template x-if="activePhoto && activePhoto.caption">
                        <p class="mt-4 text-base leading-7 text-slate-600" x-text="activePhoto.caption"></p>
                    </template>
                    <dl class="mt-7 divide-y divide-[#0b1830]/10 border-y border-[#0b1830]/10 text-sm">
                        <div class="py-4">
                            <dt class="text-xs text-slate-500">Credit</dt>
                            <dd class="mt-1 font-semibold" x-text="activePhoto ? activePhoto.credit : ''"></dd>
                        </div>
                        <template x-if="activePhoto && activePhoto.location">
                            <div class="py-4">
                                <dt class="text-xs text-slate-500">Location</dt>
                                <dd class="mt-1 font-semibold" x-text="activePhoto.location"></dd>
                            </div>
                        </template>
                    </dl>
                    <template x-if="activePhoto && activePhoto.description">
                        <p class="mt-6 text-sm leading-7 text-slate-600" x-text="activePhoto.description"></p>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
