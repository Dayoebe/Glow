<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Maintenance Mode</h3>
        <p class="text-sm text-gray-500">Toggle maintenance notices and default system messages.</p>

        <div class="mt-6 flex items-center space-x-3">
            <input type="checkbox" wire:model="maintenance_mode" id="maintenance_mode" class="rounded border-gray-300">
            <label for="maintenance_mode" class="text-sm text-gray-700">Enable maintenance mode banner</label>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Maintenance Message</label>
            <textarea rows="3" wire:model="maintenance_message"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900">System Defaults</h3>
        <p class="text-sm text-gray-500">Manage core system contact and analytics settings.</p>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Support Email</label>
                <input type="email" wire:model="support_email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Google Analytics ID</label>
                <input type="text" wire:model="analytics_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                <input type="text" wire:model="timezone"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
        </div>
    </div>

    <div class="flex justify-end">
        <button wire:click="save"
            class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg">
            Save System Settings
        </button>
    </div>

    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 z-50 bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif
</div>
