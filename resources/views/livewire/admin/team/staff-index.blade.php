<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="relative flex-1 max-w-md">
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Search staff..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <a href="{{ route('admin.team.staff.create') }}"
                class="inline-flex items-center justify-center px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors duration-150">
                <i class="fas fa-plus mr-2"></i>
                Add Staff
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($staffMembers as $staff)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 rounded-lg bg-emerald-100 flex items-center justify-center overflow-hidden">
                        @if($staff->photo_url)
                            <img src="{{ $staff->photo_url }}" alt="{{ $staff->name }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user text-emerald-600 text-xl"></i>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $staff->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $staff->teamRole?->name ?? ($staff->role ?? 'Staff Member') }}</p>
                        <p class="text-xs text-gray-400">{{ $staff->departmentRelation?->name ?? ($staff->department ?? 'General') }}</p>
                    </div>
                </div>

                <div class="mt-4 space-y-2 text-sm text-gray-600">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-envelope text-emerald-500"></i>
                        <span>{{ $staff->email ?? 'No email' }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-phone text-emerald-500"></i>
                        <span>{{ $staff->phone ?? 'No phone' }}</span>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between text-xs">
                    <button wire:click="toggleStatus({{ $staff->id }})"
                        class="px-3 py-1 rounded-full {{ $staff->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $staff->is_active ? 'Active' : 'Inactive' }}
                    </button>
                    <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700">
                        {{ $staff->employment_status }}
                    </span>
                </div>

                <div class="mt-4 flex items-center justify-end space-x-3 text-sm">
                    <a href="{{ route('admin.team.staff.edit', $staff->id) }}" class="text-emerald-600 hover:text-emerald-800">Edit</a>
                    <button wire:click="deleteStaff({{ $staff->id }})" onclick="return confirm('Delete this staff member?')"
                        class="text-red-600 hover:text-red-800">Delete</button>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl border border-gray-200 p-10 text-center">
                <p class="text-gray-500">No staff members found.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $staffMembers->links() }}
    </div>

    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 z-50 bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg flash-auto-dismiss">
            {{ session('success') }}
        </div>
    @endif
</div>
