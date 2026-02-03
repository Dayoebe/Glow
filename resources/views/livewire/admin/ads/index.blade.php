<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3 flex-1">
                <div class="relative flex-1 max-w-md">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Search ads..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <select wire:model.live="filterPlacement"
                    class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">All Placements</option>
                    <option value="global">Global</option>
                    <option value="home">Homepage</option>
                    <option value="news">News</option>
                    <option value="blog">Blog</option>
                    <option value="shows">Shows</option>
                    <option value="contact">Contact</option>
                </select>
            </div>
            <a href="{{ route('admin.ads.create') }}"
                class="inline-flex items-center justify-center px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors duration-150">
                <i class="fas fa-plus mr-2"></i>
                Add Ad
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Placement</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Schedule</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($ads as $ad)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center overflow-hidden">
                                        @if($ad->type === 'image' && $ad->image_url)
                                            <img src="{{ $ad->image_url }}" alt="{{ $ad->name }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-bullhorn text-emerald-600"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $ad->name }}</p>
                                        <p class="text-xs text-gray-500">Priority: {{ $ad->priority }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($ad->placement) }}</td>
                            <td class="px-6 py-4">
                                <button wire:click="toggleStatus({{ $ad->id }})"
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded {{ $ad->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $ad->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                <div>Start: {{ $ad->starts_at?->format('M d, Y') ?? 'Anytime' }}</div>
                                <div>End: {{ $ad->ends_at?->format('M d, Y') ?? 'No end' }}</div>
                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <a href="{{ route('admin.ads.edit', $ad->id) }}" class="text-emerald-600 hover:text-emerald-800">Edit</a>
                                <button wire:click="deleteAd({{ $ad->id }})" onclick="return confirm('Delete this ad?')"
                                    class="ml-3 text-red-600 hover:text-red-800">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">No ads created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $ads->links() }}
        </div>
    </div>

    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 z-50 bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg flash-auto-dismiss">
            {{ session('success') }}
        </div>
    @endif
</div>
