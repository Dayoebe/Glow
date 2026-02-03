<div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Stream Status</h3>
                    <p class="text-sm text-gray-500">Manage the live stream and now playing metadata.</p>
                </div>
                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $is_live ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                    {{ $is_live ? 'Live' : 'Offline' }}
                </span>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stream URL</label>
                    <input type="text" wire:model="stream_url"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Now Playing Title</label>
                    <input type="text" wire:model="now_playing_title"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Now Playing Artist</label>
                    <input type="text" wire:model="now_playing_artist"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Show Name</label>
                    <input type="text" wire:model="show_name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Show Time</label>
                    <input type="text" wire:model="show_time"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Message</label>
                    <input type="text" wire:model="status_message"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div class="flex items-center space-x-3">
                    <input type="checkbox" wire:model="is_live" id="is_live" class="rounded border-gray-300">
                    <label for="is_live" class="text-sm text-gray-700">Mark stream as live</label>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button wire:click="save"
                    class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg">
                    Save Stream Settings
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900">Preview</h3>
            <p class="text-sm text-gray-500 mb-4">Quick listen to the current stream.</p>
            <div class="bg-gray-900 text-white rounded-2xl p-4">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-lg bg-emerald-600 flex items-center justify-center">
                        <i class="fas fa-broadcast-tower"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold">{{ $show_name }}</p>
                        <p class="text-xs text-gray-300">{{ $show_time }}</p>
                        <p class="text-xs text-emerald-300 mt-1">{{ $now_playing_title }} • {{ $now_playing_artist }}</p>
                    </div>
                </div>
                <audio controls class="mt-4 w-full">
                    <source src="{{ $stream_url }}" type="audio/mpeg">
                </audio>
                <p class="text-xs text-gray-400 mt-2">{{ $status_message }}</p>
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 z-50 bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg flash-auto-dismiss">
            {{ session('success') }}
        </div>
    @endif
</div>
