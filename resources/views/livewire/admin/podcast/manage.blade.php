<div>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Shows</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_shows'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-podcast text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Episodes</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['total_episodes'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-microphone text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Plays</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ number_format($stats['total_plays']) }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-headphones text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Subscribers</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_subscribers']) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- View Tabs & Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <!-- Tabs -->
            <div class="flex space-x-2">
                <button wire:click="$set('view', 'shows')" 
                        class="px-6 py-2.5 rounded-lg font-semibold transition-colors {{ $view === 'shows' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i class="fas fa-podcast mr-2"></i>Shows
                </button>
                <button wire:click="$set('view', 'episodes')" 
                        class="px-6 py-2.5 rounded-lg font-semibold transition-colors {{ $view === 'episodes' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i class="fas fa-microphone mr-2"></i>Episodes
                </button>
            </div>

            <!-- Actions -->
            <div class="flex space-x-3">
                @if($view === 'shows')
                <button wire:click="openShowModal" 
                        class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add Show
                </button>
                @else
                <button wire:click="openEpisodeModal" 
                        class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add Episode
                </button>
                @endif
            </div>
        </div>

        <!-- Filters -->
        <div class="mt-4 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
            <div class="relative flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" 
                       placeholder="Search {{ $view }}..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>

            @if($view === 'episodes')
            <select wire:model.live="selectedShow" 
                    class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="">All Shows</option>
                @foreach($allShows as $show)
                <option value="{{ $show->id }}">{{ $show->title }}</option>
                @endforeach
            </select>
            @endif
        </div>
    </div>

    <!-- Shows View -->
    @if($view === 'shows')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($shows as $show)
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <div class="relative h-48">
                <img src="{{ $show->cover_image }}" alt="{{ $show->title }}" 
                     class="w-full h-full object-cover">
                @if($show->is_featured)
                <div class="absolute top-3 left-3">
                    <span class="px-3 py-1 bg-yellow-500 text-white text-xs font-bold rounded-full">
                        <i class="fas fa-star mr-1"></i>FEATURED
                    </span>
                </div>
                @endif
                <div class="absolute top-3 right-3">
                    <span class="px-3 py-1 bg-{{ $show->is_active ? 'green' : 'gray' }}-600 text-white text-xs font-bold rounded-full">
                        {{ $show->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            <div class="p-5">
                <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">{{ $show->title }}</h3>
                <p class="text-sm text-gray-600 mb-3">
                    <i class="fas fa-microphone mr-1 text-purple-600"></i>{{ $show->host_name }}
                </p>
                <p class="text-xs text-gray-600 mb-4 line-clamp-2">{{ $show->description }}</p>

                <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                    <span><i class="fas fa-list mr-1"></i>{{ $show->published_episodes_count }} episodes</span>
                    <span><i class="fas fa-users mr-1"></i>{{ number_format($show->subscribers) }}</span>
                </div>

                <div class="flex items-center space-x-2">
                    <button wire:click="openShowModal({{ $show->id }})" 
                            class="flex-1 px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-lg transition-colors">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </button>
                    <button wire:click="openEpisodeModal(null, {{ $show->id }})" 
                            class="flex-1 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors">
                        <i class="fas fa-plus mr-1"></i>Episode
                    </button>
                    <button wire:click="deleteShow({{ $show->id }})" 
                            onclick="return confirm('Delete this show and all its episodes?')"
                            class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition-colors">
                            <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $shows->links() }}
    </div>
    @endif

    <!-- Episodes View -->
    @if($view === 'episodes')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Episode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Show</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stats</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $canReview = $this->canReview();
                    @endphp
                    @foreach($episodes as $episode)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $episode->cover_image ?? $episode->show->cover_image }}" 
                                     class="w-16 h-16 rounded-lg object-cover">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $episode->title }}</p>
                                    @if($episode->season_number)
                                    <p class="text-xs text-gray-500">S{{ $episode->season_number }} E{{ $episode->episode_number }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500">{{ $episode->published_at?->format('M d, Y') ?? 'Not published' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ $episode->show->title }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-500">{{ $episode->formatted_duration }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-xs text-gray-500">
                                <div><i class="fas fa-headphones mr-1"></i>{{ number_format($episode->plays) }}</div>
                                <div><i class="fas fa-download mr-1"></i>{{ number_format($episode->downloads) }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button wire:click="togglePublish({{ $episode->id }})" 
                                    class="px-3 py-1 text-xs font-semibold rounded-full {{ $episode->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $episode->status === 'published' ? 'Published' : 'Draft' }}
                            </button>
                            @php
                                $approvalClass = match ($episode->approval_status) {
                                    'approved' => 'bg-emerald-100 text-emerald-700',
                                    'flagged' => 'bg-amber-100 text-amber-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="ml-2 inline-flex items-center px-2 py-1 text-xs font-medium rounded {{ $approvalClass }}">
                                <i class="fas fa-shield-check mr-1"></i>
                                {{ ucfirst($episode->approval_status ?? 'pending') }}
                            </span>
                            @if($episode->approval_reason)
                                <div class="text-xs text-gray-500 mt-1 line-clamp-2">Reason: {{ $episode->approval_reason }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end">
                                <div class="inline-flex items-center rounded-lg border border-gray-200 bg-gray-50">
                                    <div class="flex items-center gap-2 px-2 py-1">
                                        <a href="{{ route('podcasts.episode', [$episode->show->slug, $episode->slug]) }}"
                                           target="_blank"
                                           class="text-blue-600 hover:text-blue-900" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                    @if($canReview)
                                        <span class="mx-1 h-4 w-px bg-gray-200"></span>
                                        <div class="flex items-center gap-2 px-2 py-1">
                                            <button wire:click="startApproval({{ $episode->id }}, 'approved')"
                                                    class="text-emerald-600 hover:text-emerald-900" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button wire:click="startApproval({{ $episode->id }}, 'flagged')"
                                                    class="text-amber-600 hover:text-amber-900" title="Flag">
                                                <i class="fas fa-flag"></i>
                                            </button>
                                            <button wire:click="startApproval({{ $episode->id }}, 'rejected')"
                                                    class="text-red-600 hover:text-red-900" title="Reject">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        </div>
                                    @endif
                                    <span class="mx-1 h-4 w-px bg-gray-200"></span>
                                    <div class="flex items-center gap-2 px-2 py-1">
                                        <button wire:click="openEpisodeModal({{ $episode->id }})"
                                                class="text-purple-600 hover:text-purple-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="deleteEpisode({{ $episode->id }})"
                                                onclick="return confirm('Delete this episode?')"
                                                class="text-red-600 hover:text-red-900" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @if($approvalFormId === $episode->id)
                        <tr class="bg-emerald-50">
                            <td colspan="6" class="px-6 py-4">
                                <div class="flex flex-col gap-3">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ ucfirst($approvalAction) }} reason required
                                    </div>
                                    <textarea wire:model="approvalReason" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                        placeholder="Share why this episode is being {{ $approvalAction }}..."></textarea>
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
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t">
            {{ $episodes->links() }}
        </div>
    </div>
    @endif


    <!-- Modal for Show/Episode Form -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
            
            <div class="relative bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
                <!-- Header -->
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-5 sticky top-0 z-10">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-white">
                            {{ $editMode ? 'Edit' : 'Create' }} {{ ucfirst($modalType) }}
                        </h3>
                        <button wire:click="closeModal" class="text-white hover:text-gray-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <form wire:submit.prevent="save" class="p-6">
                    @if($modalType === 'show')
                        <!-- Show Form -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Show Title *</label>
                                <input type="text" wire:model="show_title" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                @error('show_title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Description *</label>
                                <textarea wire:model="show_description" rows="4"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                                @error('show_description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Host Name *</label>
                                <input type="text" wire:model="show_host_name" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                @error('show_host_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div
                                x-data="{
                                    selected: @js($show_category_choice ?: $show_category ?: 'music'),
                                    custom: @js($show_category_custom ?: ''),
                                    options: ['music', 'talk', 'interview', 'tech', 'lifestyle', 'education'],
                                    showNew: false,
                                    init() {
                                        if (this.selected && !this.options.includes(this.selected)) {
                                            this.custom = this.selected;
                                            this.selected = '__new__';
                                        }
                                        this.sync(this.selected);
                                    },
                                    sync(value) {
                                        this.selected = value;
                                        this.showNew = value === '__new__';
                                        if (this.showNew) {
                                            $wire.set('show_category_choice', value);
                                            $wire.set('show_category', this.custom || '');
                                            $wire.set('show_category_custom', this.custom || '');
                                            return;
                                        }
                                        $wire.set('show_category_choice', value);
                                        $wire.set('show_category', value);
                                        $wire.set('show_category_custom', '');
                                    },
                                    syncCustom(value) {
                                        this.custom = value;
                                        if (this.showNew) {
                                            $wire.set('show_category_custom', value);
                                            $wire.set('show_category', value);
                                        }
                                    }
                                }"
                                x-init="init()"
                            >
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Category *</label>
                                <select x-model="selected" @change="sync($event.target.value)"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    <option value="music">Music</option>
                                    <option value="talk">Talk Show</option>
                                    <option value="interview">Interviews</option>
                                    <option value="tech">Tech & Audio</option>
                                    <option value="lifestyle">Lifestyle</option>
                                    <option value="education">Educational</option>
                                    <option value="__new__">+ Add new category</option>
                                </select>
                                @error('show_category') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                <div class="mt-3 space-y-2" x-cloak x-show="showNew">
                                    <label class="block text-xs font-medium text-gray-600">New category name</label>
                                    <input type="text" x-model="custom" @input="syncCustom($event.target.value)"
                                           placeholder="Category name"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Frequency</label>
                                <select wire:model="show_frequency" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="biweekly">Bi-weekly</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tags (comma-separated)</label>
                                <input type="text" wire:model="show_tags" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                                       placeholder="music, entertainment, talk">
                            </div>

                            <div class="md:col-span-2"
                                x-data="{
                                    isUploading: false,
                                    progress: 0,
                                    uploadError: false
                                }"
                                x-on:livewire-upload-start="isUploading = true; progress = 0; uploadError = false"
                                x-on:livewire-upload-finish="isUploading = false; progress = 100"
                                x-on:livewire-upload-error="isUploading = false; uploadError = true"
                                x-on:livewire-upload-progress="progress = $event.detail.progress"
                            >
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Cover Image</label>
                                <input type="file" wire:model="show_cover" accept="image/*"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <p class="mt-1 text-xs text-gray-500">Max size 5MB. JPG, PNG, GIF, WEBP, BMP, SVG.</p>
                                <div class="mt-2" x-cloak x-show="isUploading">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-purple-600 h-2 rounded-full transition-all duration-200"
                                            :style="`width: ${progress}%`"></div>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-600">Uploading... <span x-text="progress"></span>%</p>
                                </div>
                                <div class="mt-2" x-cloak x-show="uploadError">
                                    <p class="text-xs text-red-600">Upload failed. Try a smaller image or a different format.</p>
                                </div>
                                @error('show_cover') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                
                                @php
                                    $showCoverIsPreviewable = false;
                                    if ($show_cover) {
                                        $showCoverExtension = strtolower($show_cover->getClientOriginalExtension() ?: $show_cover->extension());
                                        $showCoverIsPreviewable = !in_array($showCoverExtension, ['avif'], true);
                                    }
                                @endphp
                                @if($show_cover && $showCoverIsPreviewable)
                                <div class="mt-3">
                                    <img src="{{ $show_cover->temporaryUrl() }}" class="w-48 h-48 object-cover rounded-lg">
                                </div>
                                @elseif($show_cover)
                                <p class="mt-3 text-xs text-gray-500">Preview not available for this file type.</p>
                                @elseif($show_cover_url)
                                <div class="mt-3">
                                    <img src="{{ $show_cover_url }}" class="w-48 h-48 object-cover rounded-lg">
                                </div>
                                @elseif($existing_show_cover)
                                <div class="mt-3">
                                    <img src="{{ $existing_show_cover }}" class="w-48 h-48 object-cover rounded-lg">
                                </div>
                                @endif
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @if($show_cover)
                                        <button type="button" wire:click="clearShowCoverUpload"
                                            class="inline-flex items-center text-xs font-semibold text-gray-600 hover:text-gray-700">
                                            <i class="fas fa-times mr-1"></i>Remove selected image
                                        </button>
                                    @endif
                                    @if($show_cover_url)
                                        <button type="button" wire:click="clearShowCoverUrl"
                                            class="inline-flex items-center text-xs font-semibold text-gray-600 hover:text-gray-700">
                                            <i class="fas fa-times mr-1"></i>Clear image URL
                                        </button>
                                    @endif
                                    @if($existing_show_cover && !$show_cover && !$show_cover_url)
                                        <button type="button" wire:click="removeExistingShowCover"
                                            class="inline-flex items-center text-xs font-semibold text-red-600 hover:text-red-700">
                                            <i class="fas fa-times mr-1"></i>Remove current image
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" wire:model="show_explicit" class="w-5 h-5 text-purple-600 rounded">
                                    <span class="text-sm font-semibold text-gray-700">Explicit Content</span>
                                </label>
                            </div>
                        </div>
                    @else
                        
                    






                    <!-- Episode Form -->
<!-- Episode Form Section with Video & Links (Replace the existing episode form section) -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Select Show *</label>
        <select wire:model="episode_show_id" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            <option value="">Choose a show</option>
            @foreach($allShows as $show)
            <option value="{{ $show->id }}">{{ $show->title }}</option>
            @endforeach
        </select>
        @error('episode_show_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Episode Title *</label>
        <input type="text" wire:model="episode_title" 
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        @error('episode_title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Description *</label>
        <textarea wire:model="episode_description" rows="3"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
        @error('episode_description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Show Notes</label>
        <textarea wire:model="episode_show_notes" rows="4"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
    </div>

    <!-- Audio Section -->
    <div class="md:col-span-2 border-t pt-6">
        <h4 class="text-lg font-bold text-gray-900 mb-4">Audio Content</h4>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Audio File {{ $editMode ? '' : '*' }}</label>
        <input type="file" wire:model="episode_audio" accept="audio/*"
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        @error('episode_audio') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        <p class="mt-1 text-xs text-gray-500">Accepted formats: MP3, M4A, WAV, AAC, OGG (Max: 500MB)</p>
        
        @if($existing_episode_audio && !$episode_audio)
        <p class="mt-2 text-sm text-green-600">
            <i class="fas fa-check-circle mr-1"></i>Current file: {{ basename($existing_episode_audio) }}
        </p>
        @endif
        <div class="mt-2 flex flex-wrap gap-2">
            @if($episode_audio)
                <button type="button" wire:click="clearEpisodeAudioUpload"
                    class="inline-flex items-center text-xs font-semibold text-gray-600 hover:text-gray-700">
                    <i class="fas fa-times mr-1"></i>Remove selected audio
                </button>
            @endif
            @if($existing_episode_audio && !$episode_audio && empty($episode_audio_url))
                <button type="button" wire:click="removeExistingEpisodeAudio"
                    class="inline-flex items-center text-xs font-semibold text-red-600 hover:text-red-700">
                    <i class="fas fa-times mr-1"></i>Remove current audio
                </button>
            @endif
        </div>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Or Audio URL</label>
        <input type="url" wire:model="episode_audio_url" placeholder="https://example.com/audio.mp3"
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        @error('episode_audio_url') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        <p class="mt-1 text-xs text-gray-500">Provide a direct link to hosted audio file</p>
        @if($episode_audio_url)
            <button type="button" wire:click="clearEpisodeAudioUrl"
                class="mt-2 inline-flex items-center text-xs font-semibold text-gray-600 hover:text-gray-700">
                <i class="fas fa-times mr-1"></i>Clear audio URL
            </button>
        @endif
    </div>

    <!-- Video Section -->
    <div class="md:col-span-2 border-t pt-6">
        <h4 class="text-lg font-bold text-gray-900 mb-4">Video Content (Optional)</h4>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Video Type</label>
        <select wire:model="episode_video_type" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            <option value="youtube">YouTube</option>
            <option value="vimeo">Vimeo</option>
            <option value="upload">Upload File</option>
            <option value="other">Other</option>
        </select>
    </div>

    @if($episode_video_type === 'upload')
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Video File</label>
        <input type="file" wire:model="episode_video" accept="video/*"
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        @error('episode_video') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        <p class="mt-1 text-xs text-gray-500">Accepted formats: MP4, MOV, AVI, WMV (Max: 1GB)</p>
        @if($existing_episode_video && !$episode_video && empty($episode_video_url))
            <p class="mt-2 text-sm text-green-600">
                <i class="fas fa-check-circle mr-1"></i>Current video: {{ basename($existing_episode_video) }}
            </p>
        @endif
        <div class="mt-2 flex flex-wrap gap-2">
            @if($episode_video)
                <button type="button" wire:click="clearEpisodeVideoUpload"
                    class="inline-flex items-center text-xs font-semibold text-gray-600 hover:text-gray-700">
                    <i class="fas fa-times mr-1"></i>Remove selected video
                </button>
            @endif
            @if($existing_episode_video && !$episode_video && empty($episode_video_url))
                <button type="button" wire:click="removeExistingEpisodeVideo"
                    class="inline-flex items-center text-xs font-semibold text-red-600 hover:text-red-700">
                    <i class="fas fa-times mr-1"></i>Remove current video
                </button>
            @endif
        </div>
    </div>
    @else
    <div class="md:col-span-2">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Video URL</label>
        <input type="url" wire:model="episode_video_url" 
               placeholder="https://youtube.com/watch?v=... or https://vimeo.com/..."
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        @error('episode_video_url') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        <p class="mt-1 text-xs text-gray-500">Paste the full video URL</p>
        @if($existing_episode_video && !$episode_video_url && !$episode_video)
            <p class="mt-2 text-sm text-green-600">
                <i class="fas fa-check-circle mr-1"></i>Current video: {{ $existing_episode_video }}
            </p>
        @endif
        <div class="mt-2 flex flex-wrap gap-2">
            @if($episode_video_url)
                <button type="button" wire:click="clearEpisodeVideoUrl"
                    class="inline-flex items-center text-xs font-semibold text-gray-600 hover:text-gray-700">
                    <i class="fas fa-times mr-1"></i>Clear video URL
                </button>
            @endif
            @if($existing_episode_video && !$episode_video_url && !$episode_video)
                <button type="button" wire:click="removeExistingEpisodeVideo"
                    class="inline-flex items-center text-xs font-semibold text-red-600 hover:text-red-700">
                    <i class="fas fa-times mr-1"></i>Remove current video
                </button>
            @endif
        </div>
    </div>
    @endif

    <!-- External Platform Links -->
    <div class="md:col-span-2 border-t pt-6">
        <h4 class="text-lg font-bold text-gray-900 mb-4">
            <i class="fas fa-link mr-2 text-purple-600"></i>
            External Platform Links (Optional)
        </h4>
        <p class="text-sm text-gray-600 mb-4">Add links to where this episode is available on other platforms</p>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fab fa-spotify text-green-500 mr-1"></i> Spotify URL
        </label>
        <input type="url" wire:model="episode_spotify_url" 
               placeholder="https://open.spotify.com/episode/..."
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        @error('episode_spotify_url') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fab fa-apple text-gray-700 mr-1"></i> Apple Podcasts URL
        </label>
        <input type="url" wire:model="episode_apple_url" 
               placeholder="https://podcasts.apple.com/..."
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        @error('episode_apple_url') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fab fa-youtube text-red-500 mr-1"></i> YouTube Music URL
        </label>
        <input type="url" wire:model="episode_youtube_music_url" 
               placeholder="https://music.youtube.com/..."
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        @error('episode_youtube_music_url') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-music text-orange-500 mr-1"></i> Audiomack URL
        </label>
        <input type="url" wire:model="episode_audiomack_url" 
               placeholder="https://audiomack.com/..."
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        @error('episode_audiomack_url') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fab fa-soundcloud text-orange-600 mr-1"></i> SoundCloud URL
        </label>
        <input type="url" wire:model="episode_soundcloud_url" 
               placeholder="https://soundcloud.com/..."
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        @error('episode_soundcloud_url') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <!-- Custom Links -->
    <div class="md:col-span-2 bg-gray-50 rounded-lg p-4">
        <h5 class="text-sm font-bold text-gray-900 mb-3">Add Custom Platform Links</h5>
        
        @if(!empty($custom_links))
        <div class="mb-4 space-y-2">
            @foreach($custom_links as $name => $url)
            <div class="flex items-center justify-between bg-white px-3 py-2 rounded border">
                <div class="flex-1">
                    <span class="font-semibold text-sm">{{ $name }}:</span>
                    <a href="{{ $url }}" target="_blank" class="text-sm text-blue-600 hover:underline ml-2">
                        {{ Str::limit($url, 40) }}
                    </a>
                </div>
                <button type="button" wire:click="removeCustomLink('{{ $name }}')" 
                        class="text-red-600 hover:text-red-700 ml-2">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endforeach
        </div>
        @endif

        <div class="grid grid-cols-2 gap-3">
            <input type="text" wire:model="custom_link_name" 
                   placeholder="Platform name (e.g., Deezer)"
                   class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
            <input type="url" wire:model="custom_link_url" 
                   placeholder="https://..."
                   class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
        </div>
        <button type="button" wire:click="addCustomLink" 
                class="mt-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-semibold rounded-lg transition-colors">
            <i class="fas fa-plus mr-1"></i>Add Link
        </button>
    </div>

    <!-- Rest of the form fields -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Episode Number</label>
        <input type="number" wire:model="episode_number" 
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Season Number</label>
        <input type="number" wire:model="episode_season" 
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Duration (seconds) *</label>
        <input type="number" wire:model="episode_duration" 
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
               placeholder="e.g., 3600 for 1 hour">
        @error('episode_duration') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Episode Type</label>
        <select wire:model="episode_type" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            <option value="full">Full Episode</option>
            <option value="trailer">Trailer</option>
            <option value="bonus">Bonus</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
        <select wire:model="episode_status" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            <option value="draft">Draft</option>
            <option value="published">Published</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Guests (comma-separated)</label>
        <input type="text" wire:model="episode_guests" 
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
               placeholder="John Doe, Jane Smith">
    </div>

    <div class="md:col-span-2"
        x-data="{
            isUploading: false,
            progress: 0,
            uploadError: false
        }"
        x-on:livewire-upload-start="isUploading = true; progress = 0; uploadError = false"
        x-on:livewire-upload-finish="isUploading = false; progress = 100"
        x-on:livewire-upload-error="isUploading = false; uploadError = true"
        x-on:livewire-upload-progress="progress = $event.detail.progress"
    >
        <label class="block text-sm font-semibold text-gray-700 mb-2">Episode Cover (optional)</label>
        <input type="file" wire:model="episode_cover" accept="image/*"
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        <p class="mt-1 text-xs text-gray-500">Max size 5MB. JPG, PNG, GIF, WEBP, BMP, SVG.</p>
        <div class="mt-2" x-cloak x-show="isUploading">
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-purple-600 h-2 rounded-full transition-all duration-200"
                    :style="`width: ${progress}%`"></div>
            </div>
            <p class="mt-1 text-xs text-gray-600">Uploading... <span x-text="progress"></span>%</p>
        </div>
        <div class="mt-2" x-cloak x-show="uploadError">
            <p class="text-xs text-red-600">Upload failed. Try a smaller image or a different format.</p>
        </div>
        @error('episode_cover') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        
        @php
            $episodeCoverIsPreviewable = false;
            if ($episode_cover) {
                $episodeCoverExtension = strtolower($episode_cover->getClientOriginalExtension() ?: $episode_cover->extension());
                $episodeCoverIsPreviewable = !in_array($episodeCoverExtension, ['avif'], true);
            }
        @endphp
        @if($episode_cover && $episodeCoverIsPreviewable)
        <div class="mt-3">
            <img src="{{ $episode_cover->temporaryUrl() }}" class="w-48 h-48 object-cover rounded-lg">
        </div>
        @elseif($episode_cover)
        <p class="mt-3 text-xs text-gray-500">Preview not available for this file type.</p>
        @elseif($existing_episode_cover)
        <div class="mt-3">
            <img src="{{ $existing_episode_cover }}" class="w-48 h-48 object-cover rounded-lg">
        </div>
        @endif
        <div class="mt-2 flex flex-wrap gap-2">
            @if($episode_cover)
                <button type="button" wire:click="clearEpisodeCoverUpload"
                    class="inline-flex items-center text-xs font-semibold text-gray-600 hover:text-gray-700">
                    <i class="fas fa-times mr-1"></i>Remove selected image
                </button>
            @endif
            @if($episode_cover_url)
                <button type="button" wire:click="clearEpisodeCoverUrl"
                    class="inline-flex items-center text-xs font-semibold text-gray-600 hover:text-gray-700">
                    <i class="fas fa-times mr-1"></i>Clear image URL
                </button>
            @endif
            @if($existing_episode_cover && !$episode_cover && !$episode_cover_url)
                <button type="button" wire:click="removeExistingEpisodeCover"
                    class="inline-flex items-center text-xs font-semibold text-red-600 hover:text-red-700">
                    <i class="fas fa-times mr-1"></i>Remove current image
                </button>
            @endif
        </div>
    </div>

    <div class="md:col-span-2">
        <label class="flex items-center space-x-3">
            <input type="checkbox" wire:model="episode_explicit" class="w-5 h-5 text-purple-600 rounded">
            <span class="text-sm font-semibold text-gray-700">Explicit Content</span>
        </label>
    </div>
</div>
                    @endif

                    <!-- Footer -->
                    <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t">
                        <button type="button" wire:click="closeModal"
                                class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            {{ $editMode ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

</div>
