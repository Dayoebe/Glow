<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $isEditing ? 'Edit Show' : 'Add Show' }}</h3>
                <p class="text-sm text-gray-500">Create and manage radio shows and programs.</p>
            </div>
            <a href="{{ route('admin.shows.index') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                <i class="fas fa-arrow-left mr-2"></i>Back to Shows
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                <input type="text" wire:model="title"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea rows="4" wire:model="description"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div
                x-data="{
                    selected: @js($category_choice ?: $category_id ?: ''),
                    showNew: @js($creating_category),
                    sync(value) {
                        this.selected = value;
                        this.showNew = value === '__new__';
                        if (this.showNew) {
                            $wire.set('category_choice', value);
                            $wire.set('category_id', '');
                            $wire.set('creating_category', true);
                            return;
                        }
                        $wire.set('category_choice', value);
                        $wire.set('category_id', value);
                        $wire.set('creating_category', false);
                    }
                }"
                x-init="sync(selected)"
            >
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select x-model="selected" @change="sync($event.target.value)"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Select category</option>
                    @foreach($allCategories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                    <option value="__new__">+ Add new category</option>
                </select>
                @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                <div class="mt-3 space-y-2" x-cloak x-show="showNew">
                        <label class="block text-xs font-medium text-gray-600">New category details</label>
                        <input type="text" wire:model="new_category_name" placeholder="Category name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @error('new_category_name') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                        <input type="text" wire:model="new_category_description" placeholder="Short description (optional)"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @error('new_category_description') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                        <button type="button" wire:click="createCategory"
                            class="inline-flex items-center px-3 py-2 text-xs font-semibold rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">
                            <i class="fas fa-plus mr-2"></i>Create Category
                        </button>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Primary Host</label>
                <select wire:model="primary_host_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Select OAP</option>
                    @foreach($allOaps as $oap)
                        <option value="{{ $oap->id }}">{{ $oap->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Format</label>
                <select wire:model="format"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="live">Live</option>
                    <option value="pre-recorded">Pre-recorded</option>
                    <option value="hybrid">Hybrid</option>
                    <option value="automated">Automated</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Duration (mins)</label>
                <input type="number" wire:model="typical_duration"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Content Rating</label>
                <select wire:model="content_rating"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="G">G</option>
                    <option value="PG">PG</option>
                    <option value="PG-13">PG-13</option>
                    <option value="18+">18+</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                <input type="text" wire:model="tags" placeholder="music, talk, news"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cover Image</label>
                <input type="file" wire:model="cover_image" class="w-full text-sm text-gray-600">
                @error('cover_image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                <input type="url" wire:model="cover_url" placeholder="https://example.com/image.jpg"
                    class="mt-3 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="md:col-span-2 flex items-center space-x-2">
                <input type="checkbox" wire:model="is_featured" class="rounded border-gray-300">
                <span class="text-sm text-gray-700">Featured show</span>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.shows.index') }}"
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                Cancel
            </a>
            <button wire:click="save"
                class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                {{ $isEditing ? 'Update Show' : 'Create Show' }}
            </button>
        </div>
    </div>
</div>
