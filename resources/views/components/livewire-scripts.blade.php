@php
    /*
     * Livewire emits root-relative endpoints by default. Resolve both URLs
     * through Laravel so installations served from a subdirectory (such as
     * /Glow/public in XAMPP) keep Alpine and Livewire fully functional.
     */
    $frontendAssets = app(\Livewire\Mechanisms\FrontendAssets\FrontendAssets::class);
    $scriptUri = $frontendAssets->javaScriptRoute?->uri() ?? 'livewire/livewire.js';
    $assetUrl = url(ltrim($scriptUri, '/'));
    $updateUrl = url(ltrim(app('livewire')->getUpdateUri(), '/'));
    $livewireScripts = \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts([
        'url' => $assetUrl,
    ]);
    $livewireScripts = preg_replace(
        '/data-update-uri="[^"]*"/',
        'data-update-uri="' . e($updateUrl) . '"',
        $livewireScripts,
        1
    );
@endphp

{!! $livewireScripts !!}
