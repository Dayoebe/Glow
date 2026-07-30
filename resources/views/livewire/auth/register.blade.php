<x-auth.shell
    title="Create your Glow account"
    subtitle="Join the Glow community to save stories, follow programmes and take part in conversations."
    eyebrow="Join the community"
    icon="fas fa-user-plus"
>
    <form wire:submit.prevent="register" class="space-y-5">
        <div>
            <label for="register-name" class="mb-2 block text-xs font-extrabold uppercase tracking-[0.1em] text-slate-600">
                Full name
            </label>
            <input
                wire:model="name"
                type="text"
                id="register-name"
                class="h-12 w-full rounded-lg border bg-white px-4 text-sm text-[#07162f] shadow-sm outline-none transition placeholder:text-slate-400 focus:border-[#f26a2e] focus:ring-4 focus:ring-orange-100 @error('name') border-red-400 @else border-slate-300 @enderror"
                placeholder="Your full name"
                autocomplete="name"
                autofocus
                @error('name') aria-invalid="true" aria-describedby="register-name-error" @enderror
            >
            @error('name')
                <p id="register-name-error" class="mt-2 flex items-center gap-1.5 text-xs font-semibold text-red-600">
                    <i class="fas fa-circle-exclamation text-[10px]" aria-hidden="true"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="register-email" class="mb-2 block text-xs font-extrabold uppercase tracking-[0.1em] text-slate-600">
                Email address
            </label>
            <input
                wire:model="email"
                type="email"
                id="register-email"
                class="h-12 w-full rounded-lg border bg-white px-4 text-sm text-[#07162f] shadow-sm outline-none transition placeholder:text-slate-400 focus:border-[#f26a2e] focus:ring-4 focus:ring-orange-100 @error('email') border-red-400 @else border-slate-300 @enderror"
                placeholder="you@example.com"
                autocomplete="email"
                @error('email') aria-invalid="true" aria-describedby="register-email-error" @enderror
            >
            @error('email')
                <p id="register-email-error" class="mt-2 flex items-center gap-1.5 text-xs font-semibold text-red-600">
                    <i class="fas fa-circle-exclamation text-[10px]" aria-hidden="true"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div x-data="{ showPassword: false }">
                <label for="register-password" class="mb-2 block text-xs font-extrabold uppercase tracking-[0.1em] text-slate-600">
                    Password
                </label>
                <div class="relative">
                    <input
                        wire:model="password"
                        :type="showPassword ? 'text' : 'password'"
                        id="register-password"
                        class="h-12 w-full rounded-lg border bg-white px-4 pr-11 text-sm text-[#07162f] shadow-sm outline-none transition placeholder:text-slate-400 focus:border-[#f26a2e] focus:ring-4 focus:ring-orange-100 @error('password') border-red-400 @else border-slate-300 @enderror"
                        placeholder="At least 6 characters"
                        autocomplete="new-password"
                        @error('password') aria-invalid="true" aria-describedby="register-password-error" @enderror
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
                    <p id="register-password-error" class="mt-2 flex items-start gap-1.5 text-xs font-semibold text-red-600">
                        <i class="fas fa-circle-exclamation mt-0.5 text-[10px]" aria-hidden="true"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div x-data="{ showConfirm: false }">
                <label for="register-password-confirmation" class="mb-2 block text-xs font-extrabold uppercase tracking-[0.1em] text-slate-600">
                    Confirm password
                </label>
                <div class="relative">
                    <input
                        wire:model="password_confirmation"
                        :type="showConfirm ? 'text' : 'password'"
                        id="register-password-confirmation"
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
            wire:target="register"
        >
            <span wire:loading.remove wire:target="register">Create account</span>
            <span wire:loading wire:target="register" class="flex items-center justify-center gap-2">
                <i class="fas fa-circle-notch animate-spin text-xs" aria-hidden="true"></i>
                Creating your account...
            </span>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-600">
        Already have a Glow account?
        <a href="{{ route('login') }}" wire:navigate
            class="ml-1 font-extrabold text-[#f26a2e] transition hover:text-[#fb5d55]">
            Sign in
        </a>
    </p>
</x-auth.shell>
