<div>
    <!-- Include Trix CSS -->
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    
    <style>
        trix-toolbar .trix-button-group button {
            background: white;
        }
        trix-editor {
            min-height: 400px;
            max-height: 600px;
            overflow-y: auto;
        }
    </style>

    <form wire:submit.prevent="update">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Title & Slug Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Post Details</h3>

                    <!-- Title -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model.live.debounce.300ms="title"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 transition-colors"
                            placeholder="Enter blog post title">
                        @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Slug -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Slug (URL)
                        </label>
                        <input type="text" wire:model="slug"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 transition-colors"
                            placeholder="auto-generated-from-title">
                        @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Excerpt -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Excerpt <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="excerpt" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 transition-colors"
                            placeholder="Brief summary of the post (max 500 characters)"></textarea>
                        @error('excerpt') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        <p class="mt-1 text-xs text-gray-500">{{ strlen($excerpt) }}/500 characters</p>
                    </div>
                </div>

                <!-- Content Card with Trix Editor -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Post Content</h3>
                    
                    <div wire:ignore>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Content <span class="text-red-500">*</span>
                        </label>
                        <input id="content" type="hidden" value="{{ $content }}">
                        <trix-editor input="content" class="trix-content border border-gray-300 rounded-lg"></trix-editor>
                    </div>
                    @error('content') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- SEO Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">SEO Settings</h3>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Meta Description
                        </label>
                        <textarea wire:model="meta_description" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 transition-colors"
                            placeholder="SEO meta description (max 200 characters)"></textarea>
                        @error('meta_description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        <p class="mt-1 text-xs text-gray-500">{{ strlen($meta_description) }}/500 characters</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Meta Keywords
                        </label>
                        <input type="text" wire:model="meta_keywords"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 transition-colors"
                            placeholder="keyword1, keyword2, keyword3">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tags
                        </label>
                        <input type="text" wire:model="tags"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 transition-colors"
                            placeholder="tag1, tag2, tag3">
                        <p class="mt-1 text-xs text-gray-500">Separate tags with commas</p>
                    </div>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="space-y-6">

                <!-- Actions Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Update</h3>

                    <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Status:</span>
                            <span class="font-semibold {{ $is_published ? 'text-green-600' : 'text-amber-600' }}">
                                {{ $is_published ? 'Published' : 'Draft' }}
                            </span>
                        </div>
                    </div>

                    <!-- Schedule Publishing -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-1 text-purple-600"></i>
                            {{ $is_published ? 'Published' : 'Schedule' }} Date
                        </label>
                        <input type="datetime-local" wire:model="published_at"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 transition-colors">
                        @error('published_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            @if($is_published)
                                <i class="fas fa-info-circle mr-1"></i>
                                Post is currently published
                            @else
                                <i class="fas fa-clock mr-1"></i>
                                Set future date to schedule
                            @endif
                        </p>
                    </div>

                    <!-- Series Settings -->
                    <div class="mb-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Series
                                </label>
                                <input type="text" 
                                       wire:model="series"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 transition-colors"
                                       placeholder="Series name">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Part #
                                </label>
                                <input type="number" 
                                       wire:model="series_order"
                                       min="1"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 transition-colors"
                                       placeholder="1">
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Optional: Add to a series</p>
                    </div>

                    <!-- Publish Toggle -->
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-4">
                        <div>
                            <p class="font-medium text-gray-900">Published</p>
                            <p class="text-sm text-gray-600">Make post public</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="is_published" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                        </label>
                    </div>

                    <div class="space-y-3">
                        <button type="submit"
                            class="w-full px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            Update Post
                        </button>
                        <a href="{{ route('admin.blog.index') }}"
                            class="block w-full px-4 py-3 bg-white border-2 border-gray-300 hover:border-gray-400 text-gray-700 font-semibold rounded-lg transition-colors text-center">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- Category Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Category</h3>

                    <div
                        x-data="{
                            selected: @js($category_choice ?: $category_id ?: ''),
                            showNew: false,
                            sync(value) {
                                this.selected = value;
                                this.showNew = value === '__new__';
                                if (this.showNew) {
                                    $wire.set('category_choice', value);
                                    $wire.set('category_id', '');
                                    return;
                                }
                                $wire.set('category_choice', value);
                                $wire.set('category_id', value);
                            }
                        }"
                        x-init="sync(selected)"
                    >
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Select Category <span class="text-red-500">*</span>
                        </label>
                        <select x-model="selected" @change="sync($event.target.value)"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 transition-colors">
                            <option value="">Choose a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                            <option value="__new__">+ Add new category</option>
                        </select>
                        @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        <div class="mt-3 space-y-2" x-cloak x-show="showNew">
                            <label class="block text-xs font-medium text-gray-600">New category details</label>
                            <input type="text" wire:model="new_category_name" placeholder="Category name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-purple-500 transition-colors">
                            @error('new_category_name') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                            <input type="text" wire:model="new_category_description" placeholder="Short description (optional)"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-purple-500 transition-colors">
                            @error('new_category_description') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                            <button type="button" wire:click="createCategory"
                                class="inline-flex items-center px-3 py-2 text-xs font-semibold rounded-lg bg-purple-600 text-white hover:bg-purple-700">
                                <i class="fas fa-plus mr-2"></i>Create Category
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Featured Image Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Featured Image</h3>

                    <!-- Current Image -->
                    @if($existing_image && !$featured_image && !$featured_image_url)
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Current Image</p>
                            <img src="{{ $existing_image }}" class="w-full h-48 object-cover rounded-lg">
                            <button type="button" wire:click="removeExistingImage"
                                class="mt-2 inline-flex items-center text-xs font-semibold text-red-600 hover:text-red-700">
                                <i class="fas fa-times mr-1"></i>Remove current image
                            </button>
                        </div>
                    @endif

                    <div class="mb-4"
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload New Image
                        </label>
                        <input type="file" wire:model="featured_image" accept="image/*"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 transition-colors">
                        <p class="mt-1 text-xs text-gray-500">Max size 2MB. JPG, PNG, GIF, WEBP, BMP, SVG.</p>
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
                        @error('featured_image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                        @php
                            $featuredImageIsPreviewable = false;
                            if ($featured_image) {
                                $featuredImageExtension = strtolower($featured_image->getClientOriginalExtension() ?: $featured_image->extension());
                                $featuredImageIsPreviewable = !in_array($featuredImageExtension, ['avif'], true);
                            }
                        @endphp
                        @if ($featured_image && $featuredImageIsPreviewable)
                            <div class="mt-3">
                                <img src="{{ $featured_image->temporaryUrl() }}"
                                    class="w-full h-48 object-cover rounded-lg">
                            </div>
                            <p class="mt-1 text-xs text-purple-600">Upload ready.</p>
                        @elseif ($featured_image)
                            <p class="mt-3 text-xs text-gray-500">Preview not available for this file type.</p>
                        @endif
                        @if ($featured_image)
                            <button type="button" wire:click="clearFeaturedImageUpload"
                                class="mt-2 inline-flex items-center text-xs font-semibold text-gray-600 hover:text-gray-700">
                                <i class="fas fa-times mr-1"></i>Remove selected image
                            </button>
                        @endif
                    </div>

                    <div class="text-center text-sm text-gray-500 mb-4">OR</div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Image URL
                        </label>
                        <input type="url" wire:model.live.debounce.300ms="featured_image_url"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 transition-colors"
                            placeholder="https://example.com/image.jpg">

                        @if ($featured_image_url && !$featured_image)
                            <div class="mt-3">
                                <img src="{{ $featured_image_url }}" class="w-full h-48 object-cover rounded-lg"
                                    onerror="this.src='https://via.placeholder.com/400x300?text=Invalid+Image+URL'">
                            </div>
                            <button type="button" wire:click="clearFeaturedImageUrl"
                                class="mt-2 inline-flex items-center text-xs font-semibold text-gray-600 hover:text-gray-700">
                                <i class="fas fa-times mr-1"></i>Clear image URL
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Media Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Media</h3>
                    
                    <!-- Video URL -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-video mr-2 text-purple-600"></i>
                            Video URL
                        </label>
                        <input type="url" 
                               wire:model="video_url"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 transition-colors"
                               placeholder="https://youtube.com/embed/...">
                        @error('video_url') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        @if ($video_url)
                            <button type="button" wire:click="clearVideoUrl"
                                class="mt-2 inline-flex items-center text-xs font-semibold text-gray-600 hover:text-gray-700">
                                <i class="fas fa-times mr-1"></i>Clear video URL
                            </button>
                        @endif
                    </div>

                    <!-- Audio URL -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-podcast mr-2 text-purple-600"></i>
                            Audio URL
                        </label>
                        <input type="url" 
                               wire:model="audio_url"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 transition-colors"
                               placeholder="https://soundcloud.com/...">
                        @error('audio_url') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Settings Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Settings</h3>

                    <!-- Featured Toggle -->
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-3">
                        <div>
                            <p class="font-medium text-gray-900">Featured Post</p>
                            <p class="text-sm text-gray-600">Show on homepage</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="is_featured" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                        </label>
                    </div>

                    <!-- Allow Comments -->
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">Allow Comments</p>
                            <p class="text-sm text-gray-600">Enable reader comments</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="allow_comments" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                        </label>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <script>
        document.addEventListener('trix-change', function(event) {
            @this.set('content', event.target.value);
        });
    </script>
</div>
