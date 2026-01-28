@props([
    'src' => null,
    'title' => '',
    'imgClass' => '',
    'fallbackClass' => 'bg-emerald-700/90',
    'textClass' => 'text-3xl font-bold text-white',
])

@php
    $initials = collect(preg_split('/\s+/', trim($title ?? '')))
        ->filter()
        ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
        ->take(2)
        ->implode('');
@endphp

@if(!empty($src))
    <img src="{{ $src }}" alt="{{ $title }}" class="{{ $imgClass }}"
         onerror="this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');">
    <div class="hidden absolute inset-0 flex items-center justify-center {{ $fallbackClass }}">
        <span class="{{ $textClass }}">{{ $initials }}</span>
    </div>
@else
    <div class="absolute inset-0 flex items-center justify-center {{ $fallbackClass }}">
        <span class="{{ $textClass }}">{{ $initials }}</span>
    </div>
@endif
