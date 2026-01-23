<div>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Posts</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-blog text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Published</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ $stats['published'] }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Drafts</p>
                    <p class="text-2xl font-bold text-amber-600">{{ $stats['draft'] }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-alt text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Featured</p>
                    <p class="text-2xl font-bold text-pink-600">{{ $stats['featured'] }}</p>
                </div>
                <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-star text-pink-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <!-- Search and Filters -->
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3 flex-1">
                <div class="relative flex-1 max-w-md">
                    <input type="text" wire:model.live.debounce.300ms="search" 
                        placeholder="Search posts..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>

                <select wire:model.live="filterCategory" 
                    class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="filterStatus" 
                    class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">All Status</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                    <option value="featured">Featured</option>
                    <option value="pending">Pending Approval</option>
                    <option value="approved">Approved</option>
                    <option value="flagged">Flagged</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>

            <!-- Add New Button -->
            <a href="{{ route('admin.blog.create') }}" 
                class="inline-flex items-center justify-center px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors duration-150">
                <i class="fas fa-plus mr-2"></i>
                Add Blog Post
            </a>
        </div>
    </div>

    <!-- Posts Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Post
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Category
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Author
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Stats
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($posts as $post)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    @if($post->featured_image)
                                        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" 
                                            class="w-16 h-16 rounded-lg object-cover">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $post->title }}</p>
                                        <p class="text-xs text-gray-500">{{ $post->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $post->category->color }}-100 text-{{ $post->category->color }}-700">
                                    {{ $post->category->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img src="{{ $post->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($post->author->name) }}" 
                                        alt="{{ $post->author->name }}" 
                                        class="w-8 h-8 rounded-full mr-2">
                                    <span class="text-sm text-gray-900">{{ $post->author->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    <div><i class="fas fa-eye mr-1"></i> {{ number_format($post->views) }}</div>
                                    <div><i class="fas fa-comment mr-1"></i> {{ $post->comments_count }}</div>
                                    <div><i class="fas fa-share mr-1"></i> {{ number_format($post->shares) }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    <button wire:click="togglePublish({{ $post->id }})" 
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded {{ $post->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                        <i class="fas {{ $post->is_published ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                                        {{ $post->is_published ? 'Published' : 'Draft' }}
                                    </button>
                                    @php
                                        $approvalClass = match ($post->approval_status) {
                                            'approved' => 'bg-emerald-100 text-emerald-700',
                                            'flagged' => 'bg-amber-100 text-amber-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                            default => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded {{ $approvalClass }}">
                                        <i class="fas fa-shield-check mr-1"></i>
                                        {{ ucfirst($post->approval_status ?? 'pending') }}
                                    </span>
                                    @if($post->approval_reason)
                                        <span class="text-xs text-gray-500 line-clamp-2">Reason: {{ $post->approval_reason }}</span>
                                    @endif
                                    @if($post->is_featured)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-pink-100 text-pink-700">
                                            <i class="fas fa-star mr-1"></i> Featured
                                        </span>
                                    @endif
                                    @if($post->series)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-blue-100 text-blue-700">
                                            <i class="fas fa-layer-group mr-1"></i> Series
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.blog.preview', $post->slug) }}" target="_blank"
                                        class="text-blue-600 hover:text-blue-900" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button wire:click="startApproval({{ $post->id }}, 'approved')"
                                        class="text-emerald-600 hover:text-emerald-900" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button wire:click="startApproval({{ $post->id }}, 'flagged')"
                                        class="text-amber-600 hover:text-amber-900" title="Flag">
                                        <i class="fas fa-flag"></i>
                                    </button>
                                    <button wire:click="startApproval({{ $post->id }}, 'rejected')"
                                        class="text-red-600 hover:text-red-900" title="Reject">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                    <a href="{{ route('admin.blog.edit', $post->id) }}" 
                                        class="text-purple-600 hover:text-purple-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button wire:click="toggleFeatured({{ $post->id }})" 
                                        class="text-pink-600 hover:text-pink-900" title="Toggle Featured">
                                        <i class="fas fa-star"></i>
                                    </button>
                                    <button wire:click="confirmDelete({{ $post->id }})" 
                                        class="text-red-600 hover:text-red-900" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @if($approvalFormId === $post->id)
                            <tr class="bg-emerald-50">
                                <td colspan="6" class="px-6 py-4">
                                    <div class="flex flex-col gap-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ ucfirst($approvalAction) }} reason required
                                        </div>
                                        <textarea wire:model="approvalReason" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                            placeholder="Share why this post is being {{ $approvalAction }}..."></textarea>
                                        @error('approvalReason')
                                            <p class="text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                        <div class="flex items-center gap-3">
                                            <button wire:click="submitApprovalForm" type="button"
                                                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg">
                                                Submit
                                            </button>
                                            <button wire:click="cancelApprovalForm" type="button"
                                                class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">No blog posts found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $posts->links() }}
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('showDeleteModal', false)"></div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-exclamation-triangle text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Delete Blog Post
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to delete this post? This action cannot be undone.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="deletePost" type="button" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                        <button wire:click="$set('showDeleteModal', false)" type="button" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
