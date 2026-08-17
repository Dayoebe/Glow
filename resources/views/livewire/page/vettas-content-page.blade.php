<div class="min-h-screen bg-[#f6f2e9] text-[#0b1830]">
    <header class="bg-[#07172f] text-white">
        <div class="mx-auto max-w-7xl px-5 py-5 sm:px-8 lg:px-10">
            @include('livewire.page.vettas-nav')
            <div class="max-w-3xl py-14 lg:py-20">
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#ff8a2a]">Vettas Apartment</p>
                <h1 class="font-display mt-4 text-4xl font-semibold leading-tight sm:text-6xl">
                    {{ ['about' => 'Comfort feels better when it feels private.', 'amenities' => 'Everything you need to settle in.', 'gallery' => 'See the spaces before you arrive.', 'guide' => 'A simple, confident stay from enquiry to check-out.'][$section] }}
                </h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">{{ $content['about']['summary'] }}</p>
            </div>
        </div>
    </header>

    <main>
        @if($section === 'about')
            <section class="mx-auto grid max-w-7xl gap-12 px-5 py-16 sm:px-8 lg:grid-cols-[1.1fr_.9fr] lg:px-10 lg:py-24">
                <div><p class="text-xs font-bold uppercase tracking-widest text-[#d95318]">The Vettas experience</p><h2 class="font-display mt-3 text-4xl font-semibold">A place made for real rest, focused work and unhurried moments.</h2><p class="mt-6 text-lg leading-8 text-slate-700">Vettas brings together the privacy of an apartment and the ease guests expect from a thoughtfully prepared stay. It suits solo travellers, couples, families and professionals who want room to live—not only somewhere to sleep.</p><p class="mt-5 text-base leading-8 text-slate-600">From the first enquiry to check-out, the aim is clear communication, a welcoming environment and the flexibility to help every guest plan confidently.</p></div>
                <div class="grid gap-px bg-[#0b1830]/10 sm:grid-cols-2 lg:grid-cols-1">@foreach($content['about']['highlights'] as $highlight)<div class="bg-white p-6"><i class="fas fa-check-circle text-[#f36b21]"></i><strong class="ml-3">{{ $highlight }}</strong></div>@endforeach</div>
            </section>
        @elseif($section === 'amenities')
            @php($amenities = [['fa-couch','Furnished living','Comfortable spaces prepared for everyday living and relaxation.'],['fa-bed','Restful bedrooms','Private sleeping spaces designed to help you properly recharge.'],['fa-kitchen-set','Everyday convenience','Room to keep your stay flexible, comfortable and self-paced.'],['fa-wifi','Stay connected','A practical base for guests balancing leisure, communication and work.'],['fa-shield-halved','Privacy and ease','A calm apartment setting with guest comfort at the centre.'],['fa-calendar-days','Flexible stays','Suitable for short visits, business trips and extended stays.']])
            <section class="mx-auto max-w-7xl px-5 py-16 sm:px-8 lg:px-10 lg:py-24"><div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">@foreach($amenities as [$icon,$title,$copy])<article class="border border-[#0b1830]/10 bg-white p-7"><i class="fas {{ $icon }} text-2xl text-[#f36b21]"></i><h2 class="font-display mt-5 text-2xl font-semibold">{{ $title }}</h2><p class="mt-3 leading-7 text-slate-600">{{ $copy }}</p></article>@endforeach</div><p class="mt-8 border-l-4 border-[#f36b21] bg-white p-5 text-sm leading-6 text-slate-600">Specific availability and included services can vary. Ask the Vettas team to confirm the exact setup for your dates before payment.</p></section>
        @elseif($section === 'gallery')
            <section class="mx-auto max-w-7xl px-5 py-16 sm:px-8 lg:px-10 lg:py-24">
                @if($categories->isNotEmpty())<div class="mb-8 flex flex-wrap gap-2">@foreach($categories as $category)<span class="border border-[#0b1830]/15 bg-white px-4 py-2 text-sm font-semibold">{{ $category->name }}</span>@endforeach</div>@endif
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">@forelse($photos as $photo)<article class="overflow-hidden bg-white"><div class="aspect-[4/3] bg-[#142b4b]">@if($photo->public_image_url)<img src="{{ $photo->public_image_url }}" alt="{{ $photo->alt_text ?: $photo->title }}" class="h-full w-full object-cover" loading="lazy">@endif</div><div class="p-5"><small class="font-bold uppercase tracking-widest text-[#d95318]">{{ $photo->category?->name }}</small><h2 class="font-display mt-2 text-2xl font-semibold">{{ $photo->title }}</h2>@if($photo->caption)<p class="mt-2 text-sm leading-6 text-slate-600">{{ $photo->caption }}</p>@endif</div></article>@empty<div class="col-span-full border border-dashed border-[#0b1830]/20 bg-white p-12 text-center">New apartment photos are being prepared. Please check back soon.</div>@endforelse</div>
            </section>
        @else
            @php($steps = [['1','Send your dates','Tell us your preferred check-in, check-out and number of guests.'],['2','Confirm availability','The Vettas team will confirm the apartment setup, availability and price.'],['3','Complete your booking','Follow the confirmed payment and arrival instructions shared directly with you.'],['4','Arrive and settle in','Keep the contact details handy and enjoy your private stay.']])
            <section class="mx-auto max-w-5xl px-5 py-16 sm:px-8 lg:py-24"><div class="space-y-4">@foreach($steps as [$number,$title,$copy])<article class="grid gap-4 border border-[#0b1830]/10 bg-white p-6 sm:grid-cols-[4rem_1fr]"><span class="font-display text-4xl font-semibold text-[#f36b21]">{{ $number }}</span><div><h2 class="font-display text-2xl font-semibold">{{ $title }}</h2><p class="mt-2 leading-7 text-slate-600">{{ $copy }}</p></div></article>@endforeach</div><div class="mt-8 bg-[#07172f] p-8 text-white"><h2 class="font-display text-3xl font-semibold">Ready to check your dates?</h2><p class="mt-3 text-slate-300">A reservation request is not a confirmed booking. The team will reply with availability, pricing and next steps.</p><a href="{{ route('vettas.book') }}" class="mt-6 inline-flex bg-[#f36b21] px-6 py-3 font-bold">Request a reservation</a></div></section>
        @endif
    </main>
    <section class="bg-[#f36b21] text-white"><div class="mx-auto flex max-w-7xl flex-col gap-5 px-5 py-10 sm:px-8 md:flex-row md:items-center md:justify-between lg:px-10"><div><p class="text-sm font-bold uppercase tracking-widest">Your Vettas stay</p><h2 class="font-display mt-2 text-3xl font-semibold">See it. Plan it. Stay comfortably.</h2></div><a href="{{ route('vettas.book') }}" class="bg-white px-6 py-3 font-bold text-[#0b1830]">Check availability</a></div></section>
</div>
