@props([
    'href',
    'active' => false,
])

<a
    href="{{ $href }}"
    @if ($active) aria-current="page" @endif
    {{ $attributes->class([
        'public-nav-link',
        'public-nav-link-active' => $active,
    ]) }}
>
    {{ $slot }}
</a>
