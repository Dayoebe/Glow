@props([
    'src' => null,
    'title' => '',
    'imgClass' => '',
    'fallbackClass' => 'bg-emerald-700/90',
    'textClass' => 'text-3xl font-bold text-white',
    'alt' => null,
    'loading' => 'lazy',
    'decoding' => 'async',
    'fetchpriority' => null,
    'width' => null,
    'height' => null,
    'sizes' => null,
    'srcset' => null,
])

@php
    $imageSource = \App\Support\PublicImage::url($src);
    $altText = $alt ?? $title;

    $initials = collect(preg_split('/\s+/', trim($title ?? '')))
        ->filter()
        ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
        ->take(2)
        ->implode('');
@endphp

<span {{ $attributes->class('relative block h-full w-full') }}>
    @if($imageSource)
        <img src="{{ $imageSource }}" alt="{{ $altText }}" class="block {{ $imgClass }}"
             @if($loading) loading="{{ $loading }}" @endif
             @if($decoding) decoding="{{ $decoding }}" @endif
             @if($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
             @if($width) width="{{ $width }}" @endif
             @if($height) height="{{ $height }}" @endif
             @if($sizes) sizes="{{ $sizes }}" @endif
             @if($srcset) srcset="{{ $srcset }}" @endif
             onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
        <span class="hidden absolute inset-0 flex items-center justify-center {{ $fallbackClass }}">
            <span class="{{ $textClass }}">{{ $initials }}</span>
        </span>
    @else
        <span class="absolute inset-0 flex items-center justify-center {{ $fallbackClass }}">
            <span class="{{ $textClass }}">{{ $initials }}</span>
        </span>
    @endif
</span>
