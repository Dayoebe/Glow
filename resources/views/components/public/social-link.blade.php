@props([
    'href',
    'label',
    'icon',
])

@php
    $socialUrl = trim((string) $href);
    $hasPublicUrl = \Illuminate\Support\Str::startsWith($socialUrl, ['https://', 'http://']);
@endphp

@if($hasPublicUrl)
    <a
        href="{{ $socialUrl }}"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="{{ $label }}"
        {{ $attributes->class('public-social-link') }}
    >
        <i class="{{ $icon }}" aria-hidden="true"></i>
    </a>
@endif
