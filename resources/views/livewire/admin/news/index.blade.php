<div>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Articles</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-newspaper text-blue-600 text-xl"></i>
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
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['featured'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-star text-purple-600 text-xl"></i>
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
                        placeholder="Search articles..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>

                <select wire:model.live="filterCategory" 
                    class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="filterStatus" 
                    class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
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
            <a href="{{ route('admin.news.create') }}" 
                class="inline-flex items-center justify-center px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors duration-150">
                <i class="fas fa-plus mr-2"></i>
                Add News Article
            </a>
        </div>
    </div>

    <!-- News Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            @php
                $canReview = $this->canReview();
            @endphp
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Article
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
                    @forelse($newsArticles as $article)
                        @php
                            $canManage = $this->canManageNews($article);
                            $showView = $article->approval_status === 'approved';
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    @if($article->featured_image)
                                        <img src="{{ $article->featured_image }}" alt="{{ $article->title }}" 
                                            class="w-16 h-16 rounded-lg object-cover">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $article->title }}</p>
                                        <p class="text-xs text-gray-500">{{ $article->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $article->category->color }}-100 text-{{ $article->category->color }}-700">
                                    {{ $article->category->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img src="{{ $article->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($article->author->name) }}" 
                                        alt="{{ $article->author->name }}" 
                                        class="w-8 h-8 rounded-full mr-2">
                                    <span class="text-sm text-gray-900">{{ $article->author->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    <div><i class="fas fa-eye mr-1"></i> {{ number_format($article->views) }}</div>
                                    <div><i class="fas fa-comment mr-1"></i> {{ number_format($article->comments_count) }}</div>
                                    <div><i class="fas fa-face-smile mr-1"></i> {{ number_format($article->reactions_count) }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    @if($canReview)
                                        <button wire:click="togglePublish({{ $article->id }})" 
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium rounded {{ $article->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                            <i class="fas {{ $article->is_published ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                                            {{ $article->is_published ? 'Published' : 'Draft' }}
                                        </button>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded {{ $article->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                            <i class="fas {{ $article->is_published ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                                            {{ $article->is_published ? 'Published' : 'Draft' }}
                                        </span>
                                    @endif
                                    @php
                                        $approvalClass = match ($article->approval_status) {
                                            'approved' => 'bg-emerald-100 text-emerald-700',
                                            'flagged' => 'bg-amber-100 text-amber-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                            default => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded {{ $approvalClass }}">
                                        <i class="fas fa-shield-check mr-1"></i>
                                        {{ ucfirst($article->approval_status ?? 'pending') }}
                                    </span>
                                    @if($article->approval_reason)
                                        <span class="text-xs text-gray-500 line-clamp-2">Reason: {{ $article->approval_reason }}</span>
                                    @endif
                                    @if($canReview && $article->is_featured)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-purple-100 text-purple-700">
                                            <i class="fas fa-star mr-1"></i> Featured
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end">
                                    <div class="inline-flex items-center rounded-lg border border-gray-200 bg-gray-50">
                                        @if($showView)
                                            <div class="flex items-center gap-2 px-2 py-1">
                                                <a href="{{ route('news.show', $article->slug) }}" target="_blank"
                                                    class="text-blue-600 hover:text-blue-900" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        @endif
                                        @if($canReview)
                                            <span class="mx-1 h-4 w-px bg-gray-200"></span>
                                            <div class="flex items-center gap-2 px-2 py-1">
                                                <button wire:click="startApproval({{ $article->id }}, 'approved')"
                                                    class="text-emerald-600 hover:text-emerald-900" title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button wire:click="startApproval({{ $article->id }}, 'flagged')"
                                                    class="text-amber-600 hover:text-amber-900" title="Flag">
                                                    <i class="fas fa-flag"></i>
                                                </button>
                                                <button wire:click="startApproval({{ $article->id }}, 'rejected')"
                                                    class="text-red-600 hover:text-red-900" title="Reject">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            </div>
                                        @endif
                                        @if($canManage)
                                            <span class="mx-1 h-4 w-px bg-gray-200"></span>
                                            <div class="flex items-center gap-2 px-2 py-1">
                                                <a href="{{ route('admin.news.edit', $article->id) }}"
                                                    class="text-emerald-600 hover:text-emerald-900" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($canReview)
                                                    <button wire:click="toggleFeatured({{ $article->id }})"
                                                        class="text-purple-600 hover:text-purple-900" title="Toggle Featured">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                @endif
                                                <button wire:click="deleteNews({{ $article->id }})"
                                                    onclick="if (!confirm('Delete this article? This action cannot be undone.')) { event.stopImmediatePropagation(); return false; }"
                                                    class="text-red-600 hover:text-red-900" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @if($approvalFormId === $article->id)
                            <tr class="bg-emerald-50">
                                <td colspan="6" class="px-6 py-4">
                                    <div class="flex flex-col gap-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ ucfirst($approvalAction) }} reason required
                                        </div>
                                        <textarea wire:model="approvalReason" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                            placeholder="Share why this article is being {{ $approvalAction }}..."></textarea>
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
                                <p class="text-gray-500">No news articles found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $newsArticles->links() }}
        </div>
    </div>

</div>
