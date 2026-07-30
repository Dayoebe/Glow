<section class="min-h-[70vh] bg-glow-ivory py-10 sm:py-14 lg:py-20">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <x-ad-slot placement="user-settings" />

        <header class="mb-8 max-w-3xl sm:mb-10">
            <p class="public-kicker">Your account</p>
            <h1 class="mt-3 font-editorial text-4xl font-bold tracking-tight text-glow-ink sm:text-5xl">
                Password & security
            </h1>
            <p class="mt-3 max-w-2xl text-base leading-7 text-slate-600">
                Choose a strong password to help protect your Glow FM account.
            </p>
        </header>

        <div class="grid gap-6 lg:grid-cols-[17rem_minmax(0,1fr)] lg:gap-8">
            <aside class="overflow-hidden rounded-2xl bg-glow-ink text-white" aria-label="Account navigation">
                <div class="border-b border-white/10 p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex size-14 shrink-0 items-center justify-center overflow-hidden rounded-full border-2 border-glow-orange bg-white/10">
                            @if (auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="" class="h-full w-full object-cover">
                            @else
                                <span class="font-editorial text-xl font-bold text-white" aria-hidden="true">
                                    {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                                </span>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="truncate font-bold">{{ auth()->user()->name }}</p>
                            <p class="mt-0.5 truncate text-xs text-slate-300">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>

                <nav class="p-3" aria-label="Account settings">
                    <a href="{{ route('profile') }}" wire:navigate
                        class="flex min-h-12 items-center gap-3 rounded-xl px-4 text-sm font-semibold text-slate-300 transition hover:bg-white/10 hover:text-white">
                        <i class="fas fa-user w-5 text-center text-glow-orange" aria-hidden="true"></i>
                        Profile details
                    </a>
                    <a href="{{ route('settings') }}" wire:navigate aria-current="page"
                        class="mt-1 flex min-h-12 items-center gap-3 rounded-xl bg-white/10 px-4 text-sm font-bold text-white">
                        <i class="fas fa-shield-halved w-5 text-center text-glow-orange" aria-hidden="true"></i>
                        Password & security
                    </a>
                </nav>
            </aside>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-[0_18px_55px_rgba(7,22,47,0.08)] sm:p-8">
                <div class="border-b border-slate-200 pb-6">
                    <h2 class="font-editorial text-2xl font-bold text-glow-ink">Change your password</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Enter your current password before setting a new one.
                    </p>
                </div>

                @if (session()->has('success'))
                    <div class="mt-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900"
                        role="status" aria-live="polite">
                        <i class="fas fa-circle-check mt-0.5 text-emerald-600" aria-hidden="true"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form wire:submit.prevent="save" class="mt-6 space-y-6" novalidate>
                    <div>
                        <label for="current-password" class="mb-2 block text-sm font-bold text-glow-ink">
                            Current password
                        </label>
                        <input id="current-password" type="password" wire:model="current_password"
                            autocomplete="current-password"
                            @class([
                                'min-h-12 w-full rounded-xl border bg-white px-4 text-sm text-glow-ink outline-none transition focus:border-glow-orange focus:ring-4 focus:ring-orange-100',
                                'border-red-400' => $errors->has('current_password'),
                                'border-slate-300' => ! $errors->has('current_password'),
                            ])
                            aria-invalid="{{ $errors->has('current_password') ? 'true' : 'false' }}"
                            @error('current_password') aria-describedby="current-password-error" @enderror>
                        @error('current_password')
                            <p id="current-password-error" class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label for="new-password" class="mb-2 block text-sm font-bold text-glow-ink">
                                New password
                            </label>
                            <input id="new-password" type="password" wire:model="password"
                                autocomplete="new-password"
                                @class([
                                    'min-h-12 w-full rounded-xl border bg-white px-4 text-sm text-glow-ink outline-none transition focus:border-glow-orange focus:ring-4 focus:ring-orange-100',
                                    'border-red-400' => $errors->has('password'),
                                    'border-slate-300' => ! $errors->has('password'),
                                ])
                                aria-describedby="new-password-help{{ $errors->has('password') ? ' new-password-error' : '' }}"
                                aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}">
                            <p id="new-password-help" class="mt-2 text-xs leading-5 text-slate-500">
                                Use at least 8 characters.
                            </p>
                            @error('password')
                                <p id="new-password-error" class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password-confirmation" class="mb-2 block text-sm font-bold text-glow-ink">
                                Confirm new password
                            </label>
                            <input id="password-confirmation" type="password" wire:model="password_confirmation"
                                autocomplete="new-password"
                                class="min-h-12 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm text-glow-ink outline-none transition focus:border-glow-orange focus:ring-4 focus:ring-orange-100">
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex gap-3">
                            <i class="fas fa-lock mt-0.5 text-glow-orange" aria-hidden="true"></i>
                            <p class="text-xs leading-5 text-slate-600">
                                Your password is stored securely. Glow FM staff will never ask you to share it by email or phone.
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
                        <a href="{{ route('profile') }}" wire:navigate
                            class="inline-flex min-h-11 items-center justify-center text-sm font-bold text-glow-navy underline decoration-slate-300 underline-offset-4 transition hover:text-glow-orange">
                            Back to profile
                        </a>
                        <button type="submit" wire:loading.attr="disabled" wire:target="save"
                            class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-glow-orange px-6 text-sm font-extrabold text-white transition hover:bg-glow-coral disabled:cursor-wait disabled:opacity-60">
                            <span wire:loading.remove wire:target="save">Update password</span>
                            <span wire:loading.flex wire:target="save" class="items-center gap-2">
                                <i class="fas fa-circle-notch fa-spin" aria-hidden="true"></i>
                                Updating
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
