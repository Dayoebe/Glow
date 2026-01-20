<div class="min-h-screen bg-slate-950 flex items-center justify-center px-4 py-12">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-amber-500 rounded-full blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-cyan-500 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-md w-full">
        <div class="text-center mb-8">
            <div class="inline-flex items-center space-x-2 mb-4">
                <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z" />
                    </svg>
                </div>
                <span class="text-2xl font-bold text-white">Glow FM</span>
            </div>
            <h2 class="text-3xl font-bold text-white mb-2">Reset your password</h2>
            <p class="text-gray-400">We will email you a reset link.</p>
        </div>

        <div class="bg-slate-900 rounded-2xl border border-slate-800 p-8">
            @if (session('status'))
                <div class="mb-6 rounded-lg bg-emerald-900/40 border border-emerald-700 px-4 py-3 text-sm text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif

            <form wire:submit.prevent="sendResetLink" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                        Email Address
                    </label>
                    <input
                        wire:model="email"
                        type="email"
                        id="email"
                        class="w-full px-4 py-3 bg-slate-800 border @error('email') border-red-500 @else border-slate-700 @enderror rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                        placeholder="you@example.com"
                        autocomplete="email">
                    @error('email')
                        <p class="mt-2 text-sm text-red-400 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 rounded-lg transition transform hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="sendResetLink">Send reset link</span>
                    <span wire:loading wire:target="sendResetLink" class="flex items-center justify-center">
                        <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Sending...
                    </span>
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" wire:navigate
                    class="text-orange-500 hover:text-orange-400 font-medium transition">
                    Back to sign in
                </a>
            </div>
        </div>
    </div>
</div>
