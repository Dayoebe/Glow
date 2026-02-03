<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="relative flex-1 max-w-md">
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Search subscribers..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <button wire:click="exportCsv"
                class="inline-flex items-center justify-center px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors duration-150">
                <i class="fas fa-download mr-2"></i>
                Export CSV
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subscribed</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($subscriptions as $subscription)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $subscription->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $subscription->name ?? '—' }}</td>
                            <td class="px-6 py-4">
                                @if(!$subscription->confirmed_at)
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-amber-100 text-amber-700">
                                        Pending
                                    </span>
                                @else
                                    <button wire:click="toggleStatus({{ $subscription->id }})"
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded {{ $subscription->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $subscription->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ optional($subscription->subscribed_at)->format('M d, Y') ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <button wire:click="deleteSubscription({{ $subscription->id }})" onclick="return confirm('Delete this subscription?')"
                                    class="text-red-600 hover:text-red-800">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">No subscribers yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $subscriptions->links() }}
        </div>
    </div>

    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 z-50 bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg flash-auto-dismiss">
            {{ session('success') }}
        </div>
    @endif
</div>
