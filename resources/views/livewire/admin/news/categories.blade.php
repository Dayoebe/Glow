<div>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Categories</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-folder text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Active</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ $stats['active'] }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Inactive</p>
                    <p class="text-2xl font-bold text-gray-600">{{ $stats['inactive'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-gray-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <!-- Search -->
            <div class="relative flex-1 max-w-md">
                <input type="text" wire:model.live.debounce.300ms="search" 
                    placeholder="Search categories..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>

            <!-- Add Button -->
            <button wire:click="openCreateModal" 
                class="inline-flex items-center justify-center px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors duration-150">
                <i class="fas fa-plus mr-2"></i>
                Add Category
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-emerald-500 mr-3"></i>
                <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                <p class="text-red-800 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Categories Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Category
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Description
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            News Count
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
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-{{ $category->color }}-100 rounded-lg flex items-center justify-center">
                                        <i class="{{ $category->icon }} text-{{ $category->color }}-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $category->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $category->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600 max-w-xs truncate">
                                    {{ $category->description ?: 'No description' }}
                                </p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                    {{ $category->news_count }} articles
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="toggleStatus({{ $category->id }})" 
                                    class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full {{ $category->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    <i class="fas {{ $category->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    <button wire:click="openEditModal({{ $category->id }})" 
                                        class="text-emerald-600 hover:text-emerald-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button 
                                        wire:click="deleteCategory({{ $category->id }})"
                                        wire:confirm="Are you sure you want to delete this category? Categories with existing news articles cannot be deleted."
                                        class="text-red-600 hover:text-red-900" 
                                        title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                                    <p class="text-gray-500 text-lg font-medium mb-2">No categories found</p>
                                    <p class="text-gray-400 text-sm">{{ !empty($search) ? 'Try adjusting your search' : 'Get started by creating your first category' }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="bg-white px-6 py-4 border-t border-gray-200">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    <!-- Form Modal -->
    @if($showFormModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <!-- Header -->
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <i class="fas {{ $isEditing ? 'fa-edit' : 'fa-plus-circle' }} text-white text-lg"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white" id="modal-title">
                                {{ $isEditing ? 'Edit Category' : 'Create New Category' }}
                            </h3>
                        </div>
                        <button wire:click="closeModal" class="text-white hover:text-gray-200 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Form -->
                <form wire:submit.prevent="save">
                    <div class="px-6 py-6 space-y-6 max-h-[70vh] overflow-y-auto">
                        <!-- Category Name -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Category Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" wire:model.live="name" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                placeholder="e.g., Breaking News">
                            @error('name') 
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p> 
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div>
                            <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">
                                Slug (URL) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="slug" wire:model="slug" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-gray-50 font-mono text-sm"
                                placeholder="breaking-news">
                            @error('slug') 
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p> 
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea id="description" wire:model="description" rows="3"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-none"
                                placeholder="Brief description of this category..."></textarea>
                            @error('description') 
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p> 
                            @enderror
                        </div>

                        <!-- Icon Selector -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Icon <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-5 sm:grid-cols-10 gap-2">
                                @foreach($availableIcons as $iconOption)
                                    <button type="button" 
                                        wire:click="$set('icon', '{{ $iconOption }}')"
                                        class="relative p-3 border-2 rounded-lg transition-all duration-200 hover:scale-110 {{ $icon === $iconOption ? 'border-emerald-500 bg-emerald-50 shadow-md' : 'border-gray-200 hover:border-gray-300' }}">
                                        <i class="{{ $iconOption }} text-xl {{ $icon === $iconOption ? 'text-emerald-600' : 'text-gray-600' }}"></i>
                                        @if($icon === $iconOption)
                                            <div class="absolute -top-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full flex items-center justify-center">
                                                <i class="fas fa-check text-white text-xs"></i>
                                            </div>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                            @error('icon') 
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p> 
                            @enderror
                        </div>

                        <!-- Color Selector -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Color <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                @foreach($availableColors as $colorValue => $colorName)
                                    <button type="button" 
                                        wire:click="$set('color', '{{ $colorValue }}')"
                                        class="relative p-4 border-2 rounded-xl transition-all duration-200 {{ $color === $colorValue ? 'border-emerald-500 bg-emerald-50 shadow-md' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50' }}">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-lg bg-{{ $colorValue }}-500 shadow-sm"></div>
                                            <span class="text-sm font-medium text-gray-700">{{ $colorName }}</span>
                                        </div>
                                        @if($color === $colorValue)
                                            <div class="absolute top-2 right-2 w-5 h-5 bg-emerald-500 rounded-full flex items-center justify-center">
                                                <i class="fas fa-check text-white text-xs"></i>
                                            </div>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                            @error('color') 
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p> 
                            @enderror
                        </div>

                        <!-- Preview Card -->
                        <div class="mb-5 p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200">
                            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-3">Preview</p>
                            <div class="flex items-center space-x-3 bg-white p-4 rounded-lg shadow-sm">
                                <div class="w-12 h-12 bg-{{ $color }}-100 rounded-lg flex items-center justify-center">
                                    <i class="{{ $icon }} text-{{ $color }}-600 text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $name ?: 'Category Name' }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $slug ?: 'category-slug' }}
                                    </p>
                                </div>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">
                                    <i class="fas fa-check-circle mr-1"></i>Active
                                </span>
                            </div>
                        </div>

                        <!-- Status Toggle -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                    <i class="fas fa-toggle-on text-emerald-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Active Status</p>
                                    <p class="text-sm text-gray-600">Make this category available immediately</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="is_active" class="sr-only peer">
                                <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-200 rounded-full peer peer-checked:after:translate-x-7 peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-emerald-600 shadow-inner"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse sm:gap-3 border-t border-gray-200">
                        <button type="submit" 
                            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-700 text-base font-semibold text-white rounded-lg hover:from-emerald-700 hover:to-emerald-800 focus:outline-none focus:ring-4 focus:ring-emerald-200 transition-all shadow-md hover:shadow-lg">
                            <i class="fas {{ $isEditing ? 'fa-save' : 'fa-plus-circle' }} mr-2"></i>
                            {{ $isEditing ? 'Update Category' : 'Create Category' }}
                        </button>
                        <button type="button" wire:click="closeModal"
                            class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 bg-white border-2 border-gray-300 text-base font-semibold text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-gray-200 transition-all">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>