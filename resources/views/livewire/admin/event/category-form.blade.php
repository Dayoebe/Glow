<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ $isEditing ? 'Edit Category' : 'Create Category' }}
                </h3>
                <p class="text-sm text-gray-500">Manage event categories used across the site.</p>
            </div>
            <a href="{{ route('admin.events.categories') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                <i class="fas fa-arrow-left mr-2"></i>Back to Categories
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input type="text" wire:model.live="name"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                <input type="text" wire:model="slug"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea wire:model="description" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"></textarea>
                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Icon</label>
                <select wire:model="icon"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                    @foreach($availableIcons as $iconOption)
                        <option value="{{ $iconOption }}">{{ $iconOption }}</option>
                    @endforeach
                </select>
                @error('icon') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                @if($icon)
                    <div class="mt-2 flex items-center space-x-2">
                        <span class="text-sm text-gray-600">Preview:</span>
                        <i class="{{ $icon }} text-lg"></i>
                    </div>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                <select wire:model="color"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                    @foreach($availableColors as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('color') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                @if($color)
                    <div class="mt-2 flex items-center space-x-2">
                        <span class="text-sm text-gray-600">Preview:</span>
                        <div class="w-6 h-6 rounded bg-{{ $color }}-500"></div>
                    </div>
                @endif
            </div>

            <div class="lg:col-span-2 flex items-center space-x-2">
                <input type="checkbox" wire:model="is_active" id="is_active" class="rounded border-gray-300">
                <label for="is_active" class="text-sm text-gray-700">Active</label>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.events.categories') }}"
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                Cancel
            </a>
            <button wire:click="save"
                    class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700">
                {{ $isEditing ? 'Update Category' : 'Create Category' }}
            </button>
        </div>
    </div>
</div>
