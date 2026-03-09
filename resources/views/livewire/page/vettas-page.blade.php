<div x-data="{
    lightboxOpen: false,
    activePhoto: null,
    openLightbox(photo) {
        this.activePhoto = photo;
        this.lightboxOpen = true;
    },
    closeLightbox() {
        this.lightboxOpen = false;
        this.activePhoto = null;
    }
}" @keydown.escape.window="closeLightbox()">
    <section class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-900 py-20 text-white">
        <div class="absolute inset-0 opacity-40" style="background-image: radial-gradient(circle at 15% 20%, rgba(16,185,129,.35), transparent 32%), radial-gradient(circle at 82% 12%, rgba(255,255,255,.18), transparent 26%), linear-gradient(120deg, rgba(255,255,255,.04) 0%, rgba(255,255,255,0) 38%);"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="grid grid-cols-1 xl:grid-cols-[1.1fr_.9fr] gap-10 items-center">
                <div class="max-w-3xl">
                    <p class="text-xs uppercase tracking-[0.38em] text-emerald-300 font-semibold">Luxury. Comfort. Privacy.</p>
                    <h1 class="mt-4 text-4xl md:text-6xl font-black leading-tight">Vettas Apartment</h1>
                    <p class="mt-5 text-lg md:text-xl text-slate-200 leading-relaxed">
                        Stay in style at our fully furnished apartments — where every moment feels like home.
                    </p>

                    <div class="mt-8 flex flex-wrap items-center gap-3 text-sm">
                        <span class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-4 py-2 font-semibold text-white/90">
                            <i class="fas fa-images mr-2 text-emerald-300"></i>{{ number_format($photos->total()) }} published photo{{ $photos->total() === 1 ? '' : 's' }}
                        </span>
                        @if($activeCategory)
                            <button type="button" wire:click="filterByCategory('')"
                                class="inline-flex items-center rounded-full border border-emerald-400/30 bg-emerald-500/15 px-4 py-2 font-semibold text-emerald-100 hover:bg-emerald-500/25 transition-colors">
                                Viewing {{ $activeCategory->name }}
                                <i class="fas fa-xmark ml-3 text-xs"></i>
                            </button>
                        @endif
                    </div>
                </div>

                @if($featuredPhotos->isNotEmpty())
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($featuredPhotos as $index => $featuredPhoto)
                            @php
                                $featuredLightbox = [
                                    'image_path' => $featuredPhoto->image_path,
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
                            <article class="group overflow-hidden rounded-3xl border border-white/10 bg-white/5 backdrop-blur-sm {{ $index === 0 ? 'col-span-2' : '' }}">
                                <button type="button"
                                    @click='openLightbox(@json($featuredLightbox))'
                                    class="relative block w-full text-left cursor-zoom-in {{ $index === 0 ? 'aspect-[16/10]' : 'aspect-[4/5]' }}">
                                    <img src="{{ $featuredPhoto->image_path }}"
                                        alt="{{ $featuredPhoto->alt_text ?: $featuredPhoto->title }}"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-900/10 to-transparent"></div>
                                    <div class="absolute inset-x-0 bottom-0 p-5">
                                        <div class="inline-flex items-center rounded-full bg-emerald-400/15 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-200">
                                            {{ $featuredPhoto->category?->name ?? 'Gallery' }}
                                        </div>
                                        <h2 class="mt-3 text-lg font-bold text-white {{ $index === 0 ? 'md:text-2xl' : '' }}">{{ $featuredPhoto->title }}</h2>
                                        @if($featuredPhoto->caption)
                                            <p class="mt-2 text-sm text-slate-200/90 line-clamp-2">{{ $featuredPhoto->caption }}</p>
                                        @endif
                                    </div>
                                </button>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="border-b border-gray-200 bg-white">
        <div class="container mx-auto px-4 py-5">
            <div class="flex flex-wrap items-center gap-3">
                <button type="button" wire:click="filterByCategory('')"
                    class="inline-flex items-center rounded-full border px-4 py-2 text-sm font-semibold transition-colors {{ $category === '' ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-gray-300 text-gray-700 hover:border-emerald-300 hover:text-emerald-700' }}">
                    All
                </button>
                @foreach($categories as $categoryItem)
                    <button type="button" wire:click="filterByCategory('{{ $categoryItem->slug }}')"
                        class="inline-flex items-center rounded-full border px-4 py-2 text-sm font-semibold transition-colors {{ $category === $categoryItem->slug ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-gray-300 text-gray-700 hover:border-emerald-300 hover:text-emerald-700' }}">
                        {{ $categoryItem->name }}
                        <span class="ml-2 rounded-full bg-black/10 px-2 py-0.5 text-xs {{ $category === $categoryItem->slug ? 'bg-white/15' : '' }}">
                            {{ $categoryItem->published_photos_count }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <div class="mb-6 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.28em] text-emerald-600">Gallery Feed</p>
                    <h2 class="mt-2 text-3xl font-black text-gray-900">
                        {{ $activeCategory ? $activeCategory->name : 'All Vettas Photos' }}
                    </h2>
                </div>
                <p class="text-sm text-gray-600">
                    {{ $photos->total() }} photo{{ $photos->total() === 1 ? '' : 's' }} available
                </p>
            </div>

            @if($photos->count() > 0)
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($photos as $photo)
                        @php
                            $photoLightbox = [
                                'image_path' => $photo->image_path,
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
                        <article class="group overflow-hidden rounded-[1.75rem] border border-gray-200 bg-white shadow-sm transition-all hover:-translate-y-1 hover:shadow-xl">
                            <button type="button"
                                @click='openLightbox(@json($photoLightbox))'
                                class="relative block w-full aspect-[4/5] overflow-hidden text-left cursor-zoom-in">
                                <img src="{{ $photo->image_path }}"
                                    alt="{{ $photo->alt_text ?: $photo->title }}"
                                    loading="lazy"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                <div class="absolute inset-x-0 top-0 flex items-start justify-between gap-3 p-4">
                                    <span class="inline-flex items-center rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-slate-900 shadow-sm">
                                        {{ $photo->category?->name ?? 'Gallery' }}
                                    </span>
                                    @if($photo->is_featured)
                                        <span class="inline-flex items-center rounded-full bg-emerald-600 px-3 py-1 text-xs font-semibold text-white shadow-sm">
                                            <i class="fas fa-star mr-1 text-[10px]"></i>Featured
                                        </span>
                                    @endif
                                </div>
                                <div class="absolute inset-x-0 bottom-0 h-28 bg-gradient-to-t from-slate-950/90 to-transparent"></div>
                            </button>

                            <div class="p-6">
                                <div class="flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.24em] text-gray-500">
                                    @if($photo->display_date)
                                        <span>{{ $photo->display_date }}</span>
                                    @endif
                                    @if($photo->location)
                                        <span>{{ $photo->location }}</span>
                                    @endif
                                </div>
                                <h3 class="mt-3 text-2xl font-black text-gray-900">{{ $photo->title }}</h3>
                                @if($photo->caption)
                                    <p class="mt-3 text-sm leading-relaxed text-gray-600">{{ $photo->caption }}</p>
                                @endif

                                <div class="mt-5 flex items-center justify-between gap-4 border-t border-gray-100 pt-4 text-sm">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.22em] text-gray-400">Credit</p>
                                        <p class="mt-1 font-semibold text-gray-800">{{ $photo->photographer_name ?: 'Glow FM Media Team' }}</p>
                                    </div>
                                    @if($photo->description)
                                        <p class="max-w-[12rem] text-right text-xs text-gray-500 line-clamp-3">{{ $photo->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $photos->links() }}
                </div>
            @else
                <div class="rounded-3xl border border-dashed border-gray-300 bg-white px-8 py-16 text-center">
                    <i class="fas fa-camera-retro text-4xl text-gray-300"></i>
                    <h3 class="mt-5 text-2xl font-bold text-gray-900">No photos match this view yet</h3>
                    <p class="mt-2 text-gray-600">Try another category or publish a few photos from the dashboard.</p>
                </div>
            @endif
        </div>
    </section>

    <div x-cloak x-show="lightboxOpen" x-transition.opacity class="fixed inset-0 z-[80]">
        <div class="absolute inset-0 bg-slate-950/85 backdrop-blur-sm" @click="closeLightbox()"></div>

        <div class="relative flex min-h-screen items-center justify-center p-4 md:p-8">
            <div class="relative w-full max-w-6xl overflow-hidden rounded-[2rem] bg-white shadow-2xl">
                <button type="button" @click="closeLightbox()"
                    class="absolute right-4 top-4 z-10 inline-flex h-11 w-11 items-center justify-center rounded-full bg-slate-900/80 text-white transition hover:bg-slate-900">
                    <i class="fas fa-times"></i>
                </button>

                <div class="grid grid-cols-1 lg:grid-cols-[1.3fr_.7fr]">
                    <div class="bg-slate-950">
                        <img :src="activePhoto ? activePhoto.image_path : ''"
                            :alt="activePhoto ? activePhoto.alt_text : ''"
                            class="h-full max-h-[82vh] w-full object-contain">
                    </div>

                    <div class="flex flex-col p-6 md:p-8">
                        <div class="flex flex-wrap items-center gap-3 text-xs font-semibold uppercase tracking-[0.22em] text-emerald-600">
                            <template x-if="activePhoto && activePhoto.category">
                                <span x-text="activePhoto.category"></span>
                            </template>
                            <template x-if="activePhoto && activePhoto.display_date">
                                <span x-text="activePhoto.display_date"></span>
                            </template>
                        </div>

                        <h3 class="mt-4 text-3xl font-black text-gray-900" x-text="activePhoto ? activePhoto.title : ''"></h3>

                        <template x-if="activePhoto && activePhoto.caption">
                            <p class="mt-4 text-base leading-relaxed text-gray-600" x-text="activePhoto.caption"></p>
                        </template>

                        <div class="mt-6 grid grid-cols-1 gap-4 rounded-2xl bg-gray-50 p-5 text-sm">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">Credit</p>
                                <p class="mt-1 font-semibold text-gray-900" x-text="activePhoto ? activePhoto.credit : ''"></p>
                            </div>

                            <template x-if="activePhoto && activePhoto.location">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">Location</p>
                                    <p class="mt-1 font-semibold text-gray-900" x-text="activePhoto.location"></p>
                                </div>
                            </template>
                        </div>

                        <template x-if="activePhoto && activePhoto.description">
                            <div class="mt-6">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">About This Photo</p>
                                <p class="mt-2 text-sm leading-relaxed text-gray-600" x-text="activePhoto.description"></p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
