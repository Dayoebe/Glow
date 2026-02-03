<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex flex-col sm:flex-row gap-3 flex-1">
                <div class="relative flex-1 max-w-md">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Search reviews, shows, or reviewers..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <select wire:model.live="filterShow"
                    class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">All Shows</option>
                    @foreach($shows as $show)
                        <option value="{{ $show->id }}">{{ $show->title }}</option>
                    @endforeach
                </select>
                <select wire:model.live="filterStatus"
                    class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">All Status</option>
                    <option value="visible">Visible</option>
                    <option value="hidden">Hidden</option>
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Show</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reviewer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reviews as $review)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-medium text-gray-900">{{ $review->show?->title ?? 'Unknown Show' }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img src="{{ $review->user?->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->user?->name ?? 'Guest') }}"
                                        class="w-8 h-8 rounded-full mr-2">
                                    <span class="text-sm text-gray-900">{{ $review->user?->name ?? 'Guest' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center text-amber-500">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? '' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700 line-clamp-2">{{ $review->review ?? 'No comment' }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded
                                    {{ $review->is_approved ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $review->is_approved ? 'Visible' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <button wire:click="toggleApproval({{ $review->id }})"
                                    class="text-emerald-600 hover:text-emerald-900 text-sm font-semibold">
                                    {{ $review->is_approved ? 'Hide' : 'Show' }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">No reviews found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $reviews->links() }}
        </div>
    </div>

    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 z-50 bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg flash-auto-dismiss">
            {{ session('success') }}
        </div>
    @endif
</div>
