@props([
    'title' => 'Glow FM',
    'type' => 'Glow FM',
    'subtitle' => null,
    'meta' => null,
    'compact' => false,
])

<span {{ $attributes->class([
    'absolute inset-0 isolate flex h-full w-full overflow-hidden bg-[#07172f] text-white',
    'flex-col justify-between p-3' => $compact,
    'flex-col justify-between p-5 sm:p-6' => !$compact,
]) }}>
    <span class="absolute -right-[18%] -top-[24%] h-[75%] w-[75%] rounded-full bg-[#f36b21]/25 blur-2xl" aria-hidden="true"></span>
    <span class="absolute -bottom-[28%] -left-[20%] h-[70%] w-[70%] rounded-full bg-sky-400/15 blur-2xl" aria-hidden="true"></span>
    <span class="absolute inset-0 opacity-[0.08]" aria-hidden="true"
          style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 18px 18px;"></span>

    <span class="relative flex items-center justify-between gap-2">
        <span class="inline-flex items-center gap-2">
            <img src="{{ asset('glowfm logo.jpeg') }}" alt="" class="rounded-full object-cover ring-1 ring-white/25 {{ $compact ? 'h-7 w-7' : 'h-9 w-9' }}">
            <span class="font-black uppercase tracking-[0.16em] {{ $compact ? 'text-[8px]' : 'text-[10px]' }}">Glow 99.1 FM</span>
        </span>
        <span class="rounded-full border border-white/20 bg-white/10 px-2 py-1 font-bold uppercase tracking-[0.12em] text-orange-200 {{ $compact ? 'text-[7px]' : 'text-[9px]' }}">
            {{ $type }}
        </span>
    </span>

    <span class="relative block min-w-0 py-2">
        <strong class="block font-display font-semibold leading-[1.05] tracking-tight {{ $compact ? 'line-clamp-2 text-sm' : 'line-clamp-3 text-2xl sm:text-3xl' }}">
            {{ $title ?: 'Glow FM' }}
        </strong>
        @if(filled($subtitle))
            <span class="mt-2 block truncate font-semibold text-slate-200 {{ $compact ? 'text-[9px]' : 'text-xs sm:text-sm' }}">
                {{ $subtitle }}
            </span>
        @endif
    </span>

    <span class="relative flex min-h-4 items-end justify-between gap-2">
        @if(filled($meta))
            <span class="line-clamp-1 font-bold uppercase tracking-[0.1em] text-orange-200 {{ $compact ? 'text-[7px]' : 'text-[9px] sm:text-[10px]' }}">
                {{ $meta }}
            </span>
        @else
            <span></span>
        @endif
        <span class="shrink-0 {{ $compact ? 'text-[8px]' : 'text-[10px]' }} font-black uppercase tracking-[0.12em] text-white/65">Your station, your voice</span>
    </span>
</span>
