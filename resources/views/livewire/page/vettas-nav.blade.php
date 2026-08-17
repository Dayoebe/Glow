<nav class="flex flex-col gap-4 border-b border-white/15 pb-5 lg:flex-row lg:items-center lg:justify-between" aria-label="Vettas navigation">
    <a href="{{ route('vettas.index') }}" class="font-display text-2xl font-semibold text-white">Vettas <span class="text-[#ff8a2a]">Apartment</span></a>
    <div class="flex flex-wrap items-center gap-x-5 gap-y-3 text-sm font-semibold text-slate-200">
        <a class="hover:text-white" href="{{ route('vettas.about') }}">About</a><a class="hover:text-white" href="{{ route('vettas.amenities') }}">Amenities</a><a class="hover:text-white" href="{{ route('vettas.gallery') }}">Gallery</a><a class="hover:text-white" href="{{ route('vettas.guide') }}">Stay Guide</a><a href="{{ route('vettas.book') }}" class="bg-[#f36b21] px-4 py-2 text-white">Book your stay</a>
    </div>
</nav>
