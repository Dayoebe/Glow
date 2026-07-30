@props(['placement' => 'global'])

@php
    $ad = \App\Models\Ads\Ad::active()
        ->where('placement', $placement)
        ->orderByDesc('priority')
        ->first();
@endphp

@if($ad)
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        @if($ad->type === 'html')
            <div class="p-4 text-sm text-gray-700">{!! $ad->html !!}</div>
        @else
            @if($ad->image_url)
                <a href="{{ $ad->link_url ?? '#' }}" {{ $ad->link_url ? 'target=_blank rel=noopener' : '' }}>
                    <img src="{{ $ad->image_url }}" alt="{{ $ad->name }}" loading="lazy" decoding="async"
                        class="w-full object-cover">
                </a>
            @endif
            @if($ad->button_text)
                <div class="p-4 flex items-center justify-between">
                    <span class="text-sm text-gray-600">{{ $ad->name }}</span>
                    @if($ad->link_url)
                        <a href="{{ $ad->link_url }}" target="_blank" rel="noopener"
                            class="px-3 py-1.5 bg-emerald-600 text-white text-xs rounded-lg">
                            {{ $ad->button_text }}
                        </a>
                    @endif
                </div>
            @endif
        @endif
    </div>
@endif
