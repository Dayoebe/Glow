<x-auth.shell
    title="Choose a new password"
    subtitle="Create a secure password for your Glow account. Use at least six characters and confirm it below."
    eyebrow="Secure your account"
    icon="fas fa-shield-halved"
>
    @if (session('status'))
        <div class="mb-6 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"
            role="status">
            <i class="fas fa-circle-check mt-0.5 text-green-600" aria-hidden="true"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="resetPassword" class="space-y-5">
        <div>
            <label for="reset-email" class="mb-2 block text-xs font-extrabold uppercase tracking-[0.1em] text-slate-600">
                Email address
            </label>
            <input
                wire:model="email"
                type="email"
                id="reset-email"
                class="h-12 w-full rounded-lg border bg-white px-4 text-sm text-[#07162f] shadow-sm outline-none transition placeholder:text-slate-400 focus:border-[#f26a2e] focus:ring-4 focus:ring-orange-100 @error('email') border-red-400 @else border-slate-300 @enderror"
                placeholder="you@example.com"
                autocomplete="email"
                @error('email') aria-invalid="true" aria-describedby="reset-email-error" @enderror
            >
            @error('email')
                <p id="reset-email-error" class="mt-2 flex items-center gap-1.5 text-xs font-semibold text-red-600">
                    <i class="fas fa-circle-exclamation text-[10px]" aria-hidden="true"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div x-data="{ showPassword: false }">
                <label for="reset-password" class="mb-2 block text-xs font-extrabold uppercase tracking-[0.1em] text-slate-600">
                    New password
                </label>
                <div class="relative">
                    <input
                        wire:model="password"
                        :type="showPassword ? 'text' : 'password'"
                        id="reset-password"
                        class="h-12 w-full rounded-lg border bg-white px-4 pr-11 text-sm text-[#07162f] shadow-sm outline-none transition placeholder:text-slate-400 focus:border-[#f26a2e] focus:ring-4 focus:ring-orange-100 @error('password') border-red-400 @else border-slate-300 @enderror"
                        placeholder="At least 6 characters"
                        autocomplete="new-password"
                        autofocus
                        @error('password') aria-invalid="true" aria-describedby="reset-password-error" @enderror
                    >
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        class="absolute right-1 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-md text-slate-400 transition hover:bg-orange-50 hover:text-[#f26a2e]"
                        :aria-label="showPassword ? 'Hide password' : 'Show password'"
                    >
                        <i class="fas text-xs" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'" aria-hidden="true"></i>
                    </button>
                </div>
                @error('password')
                    <p id="reset-password-error" class="mt-2 flex items-start gap-1.5 text-xs font-semibold text-red-600">
                        <i class="fas fa-circle-exclamation mt-0.5 text-[10px]" aria-hidden="true"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div x-data="{ showConfirm: false }">
                <label for="reset-password-confirmation" class="mb-2 block text-xs font-extrabold uppercase tracking-[0.1em] text-slate-600">
                    Confirm password
                </label>
                <div class="relative">
                    <input
                        wire:model="password_confirmation"
                        :type="showConfirm ? 'text' : 'password'"
                        id="reset-password-confirmation"
                        class="h-12 w-full rounded-lg border border-slate-300 bg-white px-4 pr-11 text-sm text-[#07162f] shadow-sm outline-none transition placeholder:text-slate-400 focus:border-[#f26a2e] focus:ring-4 focus:ring-orange-100"
                        placeholder="Repeat password"
                        autocomplete="new-password"
                    >
                    <button
                        type="button"
                        @click="showConfirm = !showConfirm"
                        class="absolute right-1 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-md text-slate-400 transition hover:bg-orange-50 hover:text-[#f26a2e]"
                        :aria-label="showConfirm ? 'Hide password confirmation' : 'Show password confirmation'"
                    >
                        <i class="fas text-xs" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>

        <button
            type="submit"
            class="flex h-12 w-full items-center justify-center rounded-lg bg-[#f26a2e] px-5 text-sm font-extrabold text-white shadow-[0_12px_28px_rgba(242,106,46,0.24)] transition hover:bg-[#fb5d55] disabled:cursor-not-allowed disabled:opacity-60"
            wire:loading.attr="disabled"
            wire:target="resetPassword"
        >
            <span wire:loading.remove wire:target="resetPassword">Set new password</span>
            <span wire:loading wire:target="resetPassword" class="flex items-center justify-center gap-2">
                <i class="fas fa-circle-notch animate-spin text-xs" aria-hidden="true"></i>
                Updating password...
            </span>
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" wire:navigate
            class="inline-flex items-center gap-2 text-sm font-extrabold text-[#07162f] transition hover:text-[#f26a2e]">
            <i class="fas fa-arrow-left text-[10px]" aria-hidden="true"></i>
            Back to sign in
        </a>
    </div>
</x-auth.shell>
