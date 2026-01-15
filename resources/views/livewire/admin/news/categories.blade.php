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
                                    <button wire:click="confirmDelete({{ $category->id }})" 
                                        class="text-red-600 hover:text-red-900" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <i class="fas fa-folder-open text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">No categories found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $categories->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
@if($showFormModal)
<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity" 
             wire:click="closeModal"></div>
        
        <!-- Modal -->
        <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <form wire:submit.prevent="save">
                <!-- Header -->
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-folder-plus text-white text-lg"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-white" id="modal-title">
                                {{ $isEditing ? 'Edit Category' : 'Create New Category' }}
                            </h3>
                        </div>
                        <button type="button" wire:click="closeModal" 
                                class="text-white hover:text-gray-200 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="bg-white px-6 py-6 max-h-[calc(100vh-200px)] overflow-y-auto">
                    <!-- Name & Slug Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Category Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model.live="name"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                placeholder="e.g., Station News">
                            @error('name') 
                                <p class="mt-1.5 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p> 
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Slug <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="slug"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                placeholder="station-news">
                            @error('slug') 
                                <p class="mt-1.5 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p> 
                            @enderror
                            <p class="mt-1.5 text-xs text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>Auto-generated from name
                            </p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea wire:model="description" rows="3"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all resize-none"
                            placeholder="Brief description of the category"></textarea>
                        @error('description') 
                            <p class="mt-1.5 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p> 
                        @enderror
                    </div>

                    <!-- Icon Selection -->
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Icon <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-5 sm:grid-cols-10 gap-2">
                            @foreach($availableIcons as $iconOption)
                                <button type="button" 
                                    wire:click="$set('icon', '{{ $iconOption }}')"
                                    class="group relative p-3 border-2 rounded-xl transition-all duration-200 {{ $icon === $iconOption ? 'border-emerald-500 bg-emerald-50 shadow-md scale-105' : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50' }}">
                                    <i class="{{ $iconOption }} text-xl {{ $icon === $iconOption ? 'text-emerald-600' : 'text-gray-600 group-hover:text-emerald-500' }}"></i>
                                    @if($icon === $iconOption)
                                        <div class="absolute -top-1 -right-1 w-5 h-5 bg-emerald-500 rounded-full flex items-center justify-center">
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

                    <!-- Color Selection -->
                    <div class="mb-5">
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
                                    Delete Category
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to delete this category? This action cannot be undone. Categories with existing news articles cannot be deleted.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="deleteCategory" type="button" 
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