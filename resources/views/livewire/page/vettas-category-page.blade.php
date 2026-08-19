<div class="min-h-screen bg-[#f6f2e9] text-[#0b1830]">
    <header class="bg-[#07172f] text-white">
        <div class="mx-auto max-w-7xl px-5 pt-5 sm:px-8 lg:px-10">@include('livewire.page.vettas-nav')</div>
        <div class="mx-auto grid max-w-7xl gap-10 px-5 py-14 sm:px-8 lg:grid-cols-[1fr_.8fr] lg:items-center lg:px-10 lg:py-20">
            <div><nav class="text-xs text-slate-400" aria-label="Breadcrumb"><a href="{{ route('vettas.index') }}" class="hover:text-white">Vettas Apartment</a><span class="mx-2">/</span><span>{{ $category->name }}</span></nav><p class="mt-8 text-xs font-bold uppercase tracking-[0.24em] text-[#ff8a2a]">{{ $category->eyebrow ?: 'Explore Vettas' }}</p><h1 class="font-display mt-4 text-4xl font-semibold leading-tight sm:text-6xl">{{ $category->headline ?: $category->name . ' at Vettas Apartment' }}</h1><p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">{{ $category->description ?: 'Explore this part of Vettas Apartment through current published photos and useful stay information.' }}</p><a href="{{ route('vettas.book') }}" class="mt-8 inline-flex bg-[#f36b21] px-6 py-3.5 font-bold">Check availability</a></div>
            @if($photos->first()?->public_image_url)<img src="{{ $photos->first()->public_image_url }}" alt="{{ $photos->first()->alt_text ?: $photos->first()->title }}" class="aspect-[4/3] w-full object-cover">@endif
        </div>
    </header>

    @if(!empty($category->highlights))<section class="border-b border-[#0b1830]/10 bg-white"><div class="mx-auto grid max-w-7xl gap-px bg-[#0b1830]/10 sm:grid-cols-2 lg:grid-cols-4">@foreach($category->highlights as $highlight)<div class="flex gap-3 bg-white p-6 font-semibold"><i class="fas fa-check-circle mt-1 text-[#f36b21]"></i><span>{{ $highlight }}</span></div>@endforeach</div></section>@endif

    <main class="mx-auto max-w-7xl px-5 py-16 sm:px-8 lg:px-10 lg:py-24">
        <div class="max-w-3xl"><p class="text-xs font-bold uppercase tracking-widest text-[#d95318]">See the space</p><h2 class="font-display mt-3 text-4xl font-semibold">Inside {{ $category->name }}</h2><p class="mt-4 leading-7 text-slate-600">These are current published views from this part of Vettas Apartment. Select your dates and contact the team to confirm the exact space available for your stay.</p></div>
        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">@foreach($photos as $photo)<article class="overflow-hidden bg-white"><div class="aspect-[4/3] bg-[#142b4b]">@if($photo->public_image_url)<img loading="lazy" src="{{ $photo->public_image_url }}" alt="{{ $photo->alt_text ?: $photo->title }}" class="h-full w-full object-cover">@endif</div><div class="p-5"><h3 class="font-display text-2xl font-semibold">{{ $photo->title }}</h3>@if($photo->caption)<p class="mt-2 text-sm leading-6 text-slate-600">{{ $photo->caption }}</p>@endif</div></article>@endforeach</div>

        @if(!empty($category->faqs))<section class="mx-auto mt-20 max-w-4xl"><p class="text-xs font-bold uppercase tracking-widest text-[#d95318]">Helpful answers</p><h2 class="font-display mt-3 text-4xl font-semibold">Frequently asked questions</h2><div class="mt-8 divide-y divide-[#0b1830]/10 border-y border-[#0b1830]/10">@foreach($category->faqs as $faq)<article class="py-6"><h3 class="text-lg font-bold">{{ $faq['question'] }}</h3><p class="mt-3 leading-7 text-slate-600">{{ $faq['answer'] }}</p></article>@endforeach</div></section>@endif

        @if($relatedCategories->isNotEmpty())<section class="mt-20"><h2 class="font-display text-3xl font-semibold">Explore more of Vettas</h2><div class="mt-6 flex flex-wrap gap-3">@foreach($relatedCategories as $related)<a href="{{ route('vettas.categories.show', $related) }}" class="border border-[#0b1830]/15 bg-white px-5 py-3 font-semibold hover:border-[#f36b21]">{{ $related->name }} <i class="fas fa-arrow-right ml-2 text-xs text-[#f36b21]"></i></a>@endforeach</div></section>@endif
    </main>
</div>
