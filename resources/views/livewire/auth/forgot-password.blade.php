<x-auth.shell
    title="Reset your password"
    subtitle="Enter the email linked to your Glow account and we will send you a secure reset link."
    eyebrow="Account recovery"
    icon="fas fa-key"
>
    @if (session('status'))
        <div class="mb-6 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"
            role="status">
            <i class="fas fa-paper-plane mt-0.5 text-green-600" aria-hidden="true"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="sendResetLink" class="space-y-5">
        <div>
            <label for="forgot-email" class="mb-2 block text-xs font-extrabold uppercase tracking-[0.1em] text-slate-600">
                Email address
            </label>
            <input
                wire:model="email"
                type="email"
                id="forgot-email"
                class="h-12 w-full rounded-lg border bg-white px-4 text-sm text-[#07162f] shadow-sm outline-none transition placeholder:text-slate-400 focus:border-[#f26a2e] focus:ring-4 focus:ring-orange-100 @error('email') border-red-400 @else border-slate-300 @enderror"
                placeholder="you@example.com"
                autocomplete="email"
                autofocus
                @error('email') aria-invalid="true" aria-describedby="forgot-email-error" @enderror
            >
            @error('email')
                <p id="forgot-email-error" class="mt-2 flex items-center gap-1.5 text-xs font-semibold text-red-600">
                    <i class="fas fa-circle-exclamation text-[10px]" aria-hidden="true"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <button
            type="submit"
            class="flex h-12 w-full items-center justify-center rounded-lg bg-[#f26a2e] px-5 text-sm font-extrabold text-white shadow-[0_12px_28px_rgba(242,106,46,0.24)] transition hover:bg-[#fb5d55] disabled:cursor-not-allowed disabled:opacity-60"
            wire:loading.attr="disabled"
            wire:target="sendResetLink"
        >
            <span wire:loading.remove wire:target="sendResetLink">Email me a reset link</span>
            <span wire:loading wire:target="sendResetLink" class="flex items-center justify-center gap-2">
                <i class="fas fa-circle-notch animate-spin text-xs" aria-hidden="true"></i>
                Sending reset link...
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
