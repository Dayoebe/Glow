<x-auth.shell
    title="Welcome back"
    subtitle="Sign in to continue to your saved stories, conversations and personalised Glow experience."
    eyebrow="Listener sign in"
    icon="fas fa-right-to-bracket"
>
    @if (session('status'))
        <div class="mb-6 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"
            role="status">
            <i class="fas fa-circle-check mt-0.5 text-green-600" aria-hidden="true"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="login" class="space-y-5">
        <div>
            <label for="login-email" class="mb-2 block text-xs font-extrabold uppercase tracking-[0.1em] text-slate-600">
                Email address
            </label>
            <input
                wire:model="email"
                type="email"
                id="login-email"
                class="h-12 w-full rounded-lg border bg-white px-4 text-sm text-[#07162f] shadow-sm outline-none transition placeholder:text-slate-400 focus:border-[#f26a2e] focus:ring-4 focus:ring-orange-100 @error('email') border-red-400 @else border-slate-300 @enderror"
                placeholder="you@example.com"
                autocomplete="email"
                autofocus
                @error('email') aria-invalid="true" aria-describedby="login-email-error" @enderror
            >
            @error('email')
                <p id="login-email-error" class="mt-2 flex items-center gap-1.5 text-xs font-semibold text-red-600">
                    <i class="fas fa-circle-exclamation text-[10px]" aria-hidden="true"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div x-data="{ showPassword: false }">
            <label for="login-password" class="mb-2 block text-xs font-extrabold uppercase tracking-[0.1em] text-slate-600">
                Password
            </label>
            <div class="relative">
                <input
                    wire:model="password"
                    :type="showPassword ? 'text' : 'password'"
                    id="login-password"
                    class="h-12 w-full rounded-lg border bg-white px-4 pr-12 text-sm text-[#07162f] shadow-sm outline-none transition placeholder:text-slate-400 focus:border-[#f26a2e] focus:ring-4 focus:ring-orange-100 @error('password') border-red-400 @else border-slate-300 @enderror"
                    placeholder="Enter your password"
                    autocomplete="current-password"
                    @error('password') aria-invalid="true" aria-describedby="login-password-error" @enderror
                >
                <button
                    type="button"
                    @click="showPassword = !showPassword"
                    class="absolute right-1.5 top-1/2 flex h-9 w-10 -translate-y-1/2 items-center justify-center rounded-md text-slate-400 transition hover:bg-orange-50 hover:text-[#f26a2e]"
                    :aria-label="showPassword ? 'Hide password' : 'Show password'"
                >
                    <i class="fas text-xs" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'" aria-hidden="true"></i>
                </button>
            </div>
            @error('password')
                <p id="login-password-error" class="mt-2 flex items-center gap-1.5 text-xs font-semibold text-red-600">
                    <i class="fas fa-circle-exclamation text-[10px]" aria-hidden="true"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3">
            <label class="flex cursor-pointer items-center gap-2.5 text-sm font-semibold text-slate-600">
                <input
                    wire:model="remember"
                    type="checkbox"
                    class="h-4 w-4 rounded border-slate-300 text-[#f26a2e] accent-[#f26a2e] focus:ring-[#f26a2e]"
                >
                Remember me
            </label>
            <a href="{{ route('password.request') }}" wire:navigate
                class="text-sm font-extrabold text-[#f26a2e] transition hover:text-[#fb5d55]">
                Forgot password?
            </a>
        </div>

        <button
            type="submit"
            class="flex h-12 w-full items-center justify-center rounded-lg bg-[#f26a2e] px-5 text-sm font-extrabold text-white shadow-[0_12px_28px_rgba(242,106,46,0.24)] transition hover:bg-[#fb5d55] disabled:cursor-not-allowed disabled:opacity-60"
            wire:loading.attr="disabled"
            wire:target="login"
        >
            <span wire:loading.remove wire:target="login">Sign in</span>
            <span wire:loading wire:target="login" class="flex items-center justify-center gap-2">
                <i class="fas fa-circle-notch animate-spin text-xs" aria-hidden="true"></i>
                Signing in...
            </span>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-600">
        New to Glow?
        <a href="{{ route('register') }}" wire:navigate
            class="ml-1 font-extrabold text-[#f26a2e] transition hover:text-[#fb5d55]">
            Create an account
        </a>
    </p>
</x-auth.shell>
