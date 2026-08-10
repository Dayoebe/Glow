<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <title>{{ ($code ?? 'Error') . ' ' . ($title ?? 'Something went wrong') }} | {{ config('app.name', 'Glow FM') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-white antialiased">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-32 -left-32 h-80 w-80 rounded-full bg-emerald-500/20 blur-3xl"></div>
        <div class="absolute top-24 -right-24 h-72 w-72 rounded-full bg-amber-400/20 blur-3xl"></div>
        <div class="absolute bottom-0 left-1/3 h-64 w-64 rounded-full bg-cyan-400/10 blur-3xl"></div>
    </div>

    <main class="relative flex min-h-screen items-center justify-center px-6 py-16">
        <div class="w-full max-w-3xl">
            <div class="rounded-3xl border border-white/10 bg-white/5 p-10 shadow-2xl shadow-black/40 backdrop-blur">
                <div class="flex flex-wrap items-center justify-between gap-6">
                    <div>
                        <p class="text-sm uppercase tracking-[0.35em] text-emerald-200/80">Glow FM</p>
                        <h1 class="mt-4 text-4xl font-semibold leading-tight md:text-5xl">
                            {{ $title ?? 'Something went wrong' }}
                        </h1>
                        <p class="mt-4 text-lg text-slate-200/90">
                            {{ $message ?? 'We hit an unexpected issue while loading this page.' }}
                        </p>
                        @if(!empty($hint))
                            <p class="mt-3 text-sm text-slate-300/80">{{ $hint }}</p>
                        @endif
                    </div>
                    <div class="flex h-28 w-28 items-center justify-center rounded-2xl border border-white/10 bg-slate-900/80">
                        <span class="text-4xl font-bold text-emerald-300">{{ $code ?? 'ERR' }}</span>
                    </div>
                </div>

                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="{{ $primaryUrl ?? url('/') }}"
                        class="inline-flex items-center gap-2 rounded-full bg-emerald-500 px-6 py-3 text-sm font-semibold text-slate-950 transition hover:bg-emerald-400">
                        <span>{{ $primaryText ?? 'Go Home' }}</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                    @if(!empty($secondaryUrl))
                        <a href="{{ $secondaryUrl }}"
                            class="inline-flex items-center gap-2 rounded-full border border-white/20 px-6 py-3 text-sm font-semibold text-white transition hover:border-white/50">
                            <i class="fas fa-rotate-left text-xs"></i>
                            <span>{{ $secondaryText ?? 'Go Back' }}</span>
                        </a>
                    @endif
                </div>

                <div class="mt-10 border-t border-white/10 pt-6 text-xs text-slate-400">
                    <p>If you keep seeing this, contact support at <a href="mailto:support@glowfm.com" class="text-emerald-200 hover:text-emerald-100">support@glowfm.com</a>.</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
