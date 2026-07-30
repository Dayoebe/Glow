@props([
    'title',
    'subtitle',
    'eyebrow' => 'Glow account',
    'icon' => 'fas fa-user',
])

<section
    class="relative min-h-[calc(100svh-4.5rem)] overflow-hidden bg-[#fffaf3] px-4 py-8 sm:px-6 sm:py-12 lg:min-h-[calc(100svh-6.75rem)] lg:py-16"
>
    <div class="pointer-events-none absolute inset-0" aria-hidden="true">
        <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-orange-200/40 blur-3xl"></div>
        <div class="absolute -bottom-32 -left-28 h-80 w-80 rounded-full bg-slate-200/60 blur-3xl"></div>
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-orange-300 to-transparent"></div>
    </div>

    <div
        class="relative mx-auto grid w-full max-w-6xl overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-[0_30px_90px_rgba(7,22,47,0.13)] lg:grid-cols-[0.9fr_1.1fr]"
    >
        <aside class="relative hidden min-h-[42rem] overflow-hidden bg-[#07162f] p-10 text-white lg:flex lg:flex-col lg:justify-between xl:p-12">
            <div class="pointer-events-none absolute inset-0" aria-hidden="true">
                <div class="absolute -right-20 top-20 h-64 w-64 rounded-full border border-white/10"></div>
                <div class="absolute -right-8 top-48 h-40 w-40 rounded-full border border-orange-400/40"></div>
                <div class="absolute bottom-0 left-0 h-1.5 w-2/3 bg-[#f26a2e]"></div>
            </div>

            <div class="relative">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3" aria-label="Glow FM home">
                    <img
                        src="{{ asset('glowfm logo.jpeg') }}"
                        alt="Glow FM logo"
                        class="h-12 w-12 rounded-lg border border-white/15 bg-white object-contain p-0.5"
                    >
                    <span>
                        <span class="block text-lg font-extrabold tracking-[-0.03em]">Glow FM</span>
                        <span class="mt-0.5 block text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#ffb13b]">
                            99.1 MHz · Akure
                        </span>
                    </span>
                </a>
            </div>

            <div class="relative max-w-sm">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.22em] text-[#ffb13b]">Your Glow account</p>
                <h2 class="mt-4 font-display text-4xl font-bold leading-[1.08] tracking-[-0.035em] text-white xl:text-5xl">
                    Your station, now more personal.
                </h2>
                <p class="mt-5 text-sm leading-7 text-slate-300">
                    Follow the stories and programmes you care about, join the conversation, and keep Glow close wherever you are.
                </p>

                <ul class="mt-8 space-y-4 text-sm text-slate-200">
                    <li class="flex items-center gap-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-white/10 text-[11px] text-[#ffb13b]">
                            <i class="fas fa-bookmark" aria-hidden="true"></i>
                        </span>
                        Save and revisit stories
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-white/10 text-[11px] text-[#ffb13b]">
                            <i class="fas fa-comments" aria-hidden="true"></i>
                        </span>
                        Join conversations around Glow content
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-white/10 text-[11px] text-[#ffb13b]">
                            <i class="fas fa-headphones" aria-hidden="true"></i>
                        </span>
                        Keep listening while you browse
                    </li>
                </ul>
            </div>

            <p class="relative text-xs font-semibold text-slate-400">
                Live from Akure on <span class="text-white">99.1 FM</span>
            </p>
        </aside>

        <div class="flex items-center px-5 py-8 sm:px-10 sm:py-12 lg:px-12 xl:px-16">
            <div class="mx-auto w-full max-w-lg">
                <a href="{{ route('home') }}" class="mb-8 inline-flex items-center gap-2 lg:hidden" aria-label="Glow FM home">
                    <img
                        src="{{ asset('glowfm logo.jpeg') }}"
                        alt="Glow FM logo"
                        class="h-10 w-10 rounded-lg border border-slate-200 bg-white object-contain p-0.5"
                    >
                    <span>
                        <span class="block text-base font-extrabold tracking-[-0.03em] text-[#07162f]">Glow FM</span>
                        <span class="block text-[9px] font-extrabold uppercase tracking-[0.18em] text-[#f26a2e]">99.1 MHz</span>
                    </span>
                </a>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-orange-50 text-sm text-[#f26a2e]">
                    <i class="{{ $icon }}" aria-hidden="true"></i>
                </div>
                <p class="mt-5 text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#f26a2e]">{{ $eyebrow }}</p>
                <h1 class="mt-2 font-display text-3xl font-bold leading-tight tracking-[-0.035em] text-[#07162f] sm:text-4xl">
                    {{ $title }}
                </h1>
                <p class="mt-3 max-w-md text-sm leading-6 text-slate-600">{{ $subtitle }}</p>

                <div class="mt-8">
                    {{ $slot }}
                </div>

                <div class="mt-8 flex items-center justify-between gap-4 border-t border-slate-200 pt-5 text-xs text-slate-500">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-lock text-[10px] text-[#f26a2e]" aria-hidden="true"></i>
                        Secure account access
                    </span>
                    <a href="{{ route('home') }}" class="font-bold text-[#07162f] transition hover:text-[#f26a2e]">
                        Back to Glow FM
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
