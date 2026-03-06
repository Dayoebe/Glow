<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ $isEditing ? 'Edit Photo' : 'Add Photo' }}
                </h3>
                <p class="text-sm text-gray-500">Upload a Vettas gallery image and assign it to a public category.</p>
            </div>
            <a href="{{ route('admin.vettas.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                <i class="fas fa-arrow-left mr-2"></i>Back to Gallery
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-[1.2fr_.8fr] gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" wire:model="title"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Caption</label>
                    <textarea wire:model="caption" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                    @error('caption') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea wire:model="description" rows="5"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select wire:model="category_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                    <input type="number" min="0" wire:model="display_order"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('display_order') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Photographer / Credit</label>
                    <input type="text" wire:model="photographer_name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('photographer_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                    <input type="text" wire:model="location"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('location') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Captured Date</label>
                    <input type="date" wire:model="captured_at"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('captured_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Publish Date</label>
                    <input type="datetime-local" wire:model="published_at"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('published_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alt Text</label>
                    <input type="text" wire:model="alt_text"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('alt_text') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Photo Upload</label>
                    <input type="file" wire:model="image" accept="image/*"
                        class="w-full px-4 py-2 border border-dashed border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <p class="mt-2 text-xs text-gray-500">Uploads will use Cloudinary when configured, otherwise they fall back to local public storage.</p>
                    @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                    @if($image)
                        <div class="mt-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500 mb-2">New Preview</p>
                            <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="w-full max-w-sm rounded-xl object-cover shadow-sm">
                        </div>
                    @elseif($existing_image)
                        <div class="mt-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500 mb-2">Current Image</p>
                            <img src="{{ $existing_image }}" alt="{{ $alt_text ?: $title }}" class="w-full max-w-sm rounded-xl object-cover shadow-sm">
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h4 class="text-sm font-semibold uppercase tracking-[0.22em] text-gray-500">Publishing</h4>
                <div class="mt-5 space-y-4">
                    <label class="flex items-center justify-between gap-4 rounded-lg border border-gray-200 px-4 py-3">
                        <div>
                            <p class="font-semibold text-gray-900">Publish now</p>
                            <p class="text-sm text-gray-500">Show this photo on the public Vettas page.</p>
                        </div>
                        <input type="checkbox" wire:model="is_published" class="rounded border-gray-300">
                    </label>

                    <label class="flex items-center justify-between gap-4 rounded-lg border border-gray-200 px-4 py-3">
                        <div>
                            <p class="font-semibold text-gray-900">Feature this photo</p>
                            <p class="text-sm text-gray-500">Pin it toward the top and show it in the hero collage.</p>
                        </div>
                        <input type="checkbox" wire:model="is_featured" class="rounded border-gray-300">
                    </label>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h4 class="text-sm font-semibold uppercase tracking-[0.22em] text-gray-500">Quick Category</h4>
                <p class="mt-2 text-sm text-gray-500">Need a new category without leaving this form?</p>

                <div class="mt-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
                        <input type="text" wire:model="new_category_name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @error('new_category_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category Description</label>
                        <textarea wire:model="new_category_description" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                        @error('new_category_description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <button type="button" wire:click="createCategory"
                        class="w-full px-4 py-2.5 border border-emerald-200 bg-emerald-50 text-emerald-700 font-semibold rounded-lg hover:bg-emerald-100">
                        Create Category
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.vettas.index') }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                        Cancel
                    </a>
                    <button wire:click="save"
                        class="px-5 py-2.5 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700">
                        {{ $isEditing ? 'Update Photo' : 'Save Photo' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
