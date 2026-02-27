<div>
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600">Total Roles</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600">Published</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $stats['published'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600">Drafts</p>
            <p class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['draft'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600">Open Roles</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['open'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600">Applications</p>
            <p class="text-2xl font-bold text-purple-600 mt-1">{{ $stats['applications'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search roles..."
                    class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">

                <select wire:model.live="filterDepartment"
                    class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">All Departments</option>
                    @foreach($departmentOptions as $department)
                        <option value="{{ $department }}">{{ $department }}</option>
                    @endforeach
                </select>

                <select wire:model.live="filterType"
                    class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">All Types</option>
                    @foreach($typeOptions as $type)
                        <option value="{{ $type }}">{{ \Illuminate\Support\Str::of($type)->replace('-', ' ')->title() }}</option>
                    @endforeach
                </select>

                <select wire:model.live="filterStatus"
                    class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">All Status</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                    <option value="open">Open</option>
                    <option value="closed">Closed</option>
                    <option value="paused">Paused</option>
                    <option value="featured">Featured</option>
                </select>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.careers.applications') }}"
                    class="inline-flex items-center px-4 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50">
                    <i class="fas fa-file-lines mr-2"></i>
                    Applications
                </a>
                <a href="{{ route('admin.careers.create') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg">
                    <i class="fas fa-plus mr-2"></i>
                    Add Role
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applications</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($positions as $position)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-gray-900">{{ $position->title }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $position->location_label }}</p>
                                <p class="text-xs text-gray-500">Deadline: {{ $position->application_deadline?->format('M d, Y') ?: 'None' }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $position->department ?: 'General' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ \Illuminate\Support\Str::of($position->employment_type)->replace('-', ' ')->title() }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $position->applications_count }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-2">
                                    <button wire:click="togglePublish({{ $position->id }})"
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded {{ $position->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $position->is_published ? 'Published' : 'Draft' }}
                                    </button>
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded {{ $position->status === 'open' ? 'bg-blue-100 text-blue-700' : ($position->status === 'paused' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                                        {{ \Illuminate\Support\Str::of($position->status)->title() }}
                                    </span>
                                    @if($position->is_featured)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-purple-100 text-purple-700">Featured</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <div class="inline-flex items-center rounded-lg border border-gray-200 bg-gray-50 px-2 py-1 gap-2">
                                    <a href="{{ route('admin.careers.edit', $position->id) }}" class="text-emerald-600 hover:text-emerald-800" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button wire:click="toggleFeatured({{ $position->id }})" class="text-purple-600 hover:text-purple-800" title="Toggle featured">
                                        <i class="fas fa-star"></i>
                                    </button>
                                    <button wire:click="setStatus({{ $position->id }}, 'open')" class="text-blue-600 hover:text-blue-800" title="Mark open">
                                        <i class="fas fa-unlock"></i>
                                    </button>
                                    <button wire:click="setStatus({{ $position->id }}, 'closed')" class="text-amber-600 hover:text-amber-800" title="Mark closed">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                    <button wire:click="confirmDelete({{ $position->id }})" class="text-red-600 hover:text-red-800" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">No career positions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $positions->links() }}
        </div>
    </div>

    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true" role="dialog">
            <div class="flex items-end justify-center min-h-screen px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('showDeleteModal', false)"></div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Delete Position</h3>
                        <p class="mt-2 text-sm text-gray-600">Are you sure you want to delete this position and all its applications?</p>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="deletePosition" type="button"
                            class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent px-4 py-2 bg-red-600 text-white font-semibold hover:bg-red-700 sm:ml-3">
                            Delete
                        </button>
                        <button wire:click="$set('showDeleteModal', false)" type="button"
                            class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 bg-white text-gray-700 font-semibold hover:bg-gray-50">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
