<div x-data="{ copied: '' }">
    @include('livewire.admin.vettas._nav')
    <div class="grid gap-6 xl:grid-cols-[1fr_22rem]">
        <section class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-widest text-emerald-600">Campaign message</p>
            <h2 class="mt-2 text-xl font-bold text-gray-900">Create one clear message people will want to share</h2>
            <div class="mt-6 space-y-5">
                <div><label class="mb-2 block text-sm font-medium text-gray-700">Campaign headline</label><input wire:model="promotion.headline" class="w-full rounded-lg border-gray-300" type="text">@error('promotion.headline')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                <div><label class="mb-2 block text-sm font-medium text-gray-700">Short caption</label><textarea wire:model="promotion.short_caption" rows="4" class="w-full rounded-lg border-gray-300"></textarea>@error('promotion.short_caption')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                <div><label class="mb-2 block text-sm font-medium text-gray-700">Long caption</label><textarea wire:model="promotion.long_caption" rows="7" class="w-full rounded-lg border-gray-300"></textarea>@error('promotion.long_caption')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                <div><label class="mb-2 block text-sm font-medium text-gray-700">Hashtags</label><textarea wire:model="promotion.hashtags" rows="3" class="w-full rounded-lg border-gray-300"></textarea></div>
                <button wire:click="save" class="rounded-lg bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-700">Save campaign kit</button>
            </div>
        </section>
        <aside class="space-y-5">
            <div class="rounded-xl bg-gray-900 p-6 text-white">
                <p class="text-xs font-bold uppercase tracking-widest text-orange-400">Share now</p>
                <h3 class="mt-2 text-xl font-bold">Send visitors to the apartment page</h3>
                @php($shareText = trim(($promotion['short_caption'] ?? '') . ' ' . route('vettas.index')))
                <div class="mt-5 grid gap-3">
                    <a target="_blank" href="https://wa.me/?text={{ urlencode($shareText) }}" class="rounded-lg bg-green-500 px-4 py-3 text-center font-semibold">Share on WhatsApp</a>
                    <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('vettas.index')) }}" class="rounded-lg bg-blue-600 px-4 py-3 text-center font-semibold">Share on Facebook</a>
                    <button type="button" @click="navigator.clipboard.writeText(@js($shareText)); copied = 'Link and caption copied'" class="rounded-lg border border-white/20 px-4 py-3 font-semibold hover:bg-white/10">Copy campaign message</button>
                    <p x-show="copied" x-text="copied" class="text-center text-xs text-emerald-300"></p>
                </div>
            </div>
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-5 text-sm leading-6 text-amber-900"><strong class="block">Promotion checklist</strong>Use strong room photos, publish guest-approved testimonials, post short video tours, include the booking link in every post, and answer enquiries quickly. Reach grows through consistent useful content; no tool can guarantee virality.</div>
        </aside>
    </div>
    @if(session()->has('success'))<div class="fixed bottom-4 right-4 z-50 rounded-lg bg-emerald-600 px-6 py-3 text-white shadow-lg">{{ session('success') }}</div>@endif
</div>
