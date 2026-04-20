<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="relative flex-1 max-w-md">
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Search staff..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                @if($hasFilters)
                    <button wire:click="clearFilters"
                        class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-100 transition-colors duration-150">
                        Clear
                    </button>
                @endif
                <a href="{{ route('admin.team.staff.create') }}"
                    class="inline-flex items-center justify-center px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors duration-150">
                    <i class="fas fa-plus mr-2"></i>
                    Add Staff
                </a>
            </div>
        </div>

        <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-3">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Sort by</label>
                <select wire:model.live="sortBy"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="latest">Newest first</option>
                    <option value="oldest">Oldest first</option>
                    <option value="name_asc">Name A-Z</option>
                    <option value="name_desc">Name Z-A</option>
                    <option value="department">Department</option>
                    <option value="role">Role</option>
                    <option value="active_first">Active first</option>
                    <option value="inactive_first">Inactive first</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Category</label>
                <select wire:model.live="departmentId"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">All departments</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Role</label>
                <select wire:model.live="roleId"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">All roles</option>
                    @foreach($teamRoles as $teamRole)
                        <option value="{{ $teamRole->id }}">{{ $teamRole->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Status</label>
                <select wire:model.live="status"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="all">All staff</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Employment</label>
                <select wire:model.live="employmentStatus"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">All types</option>
                    @foreach($employmentStatuses as $employmentValue => $employmentLabel)
                        <option value="{{ $employmentValue }}">{{ $employmentLabel }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">OAP</label>
                <select wire:model.live="oapStatus"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="all">All OAP states</option>
                    <option value="linked">Linked OAP</option>
                    <option value="active">Active OAP</option>
                    <option value="inactive">Inactive OAP</option>
                    <option value="none">No OAP</option>
                </select>
            </div>
        </div>

        <div class="mt-4 flex flex-wrap items-center gap-2 text-xs text-gray-500">
            <span>{{ $staffMembers->total() }} {{ \Illuminate\Support\Str::plural('staff member', $staffMembers->total()) }}</span>
            @if($hasFilters)
                <span class="text-gray-300">|</span>
                <span>Filtered</span>
            @endif
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
                        @if($staff->is_active) onclick="return confirm('Mark this staff member inactive? Their dashboard access will be disabled and OAP/program assignments will be removed.')" @endif
                        class="px-3 py-1 rounded-full {{ $staff->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $staff->is_active ? 'Deactivate' : 'Reactivate' }}
                    </button>
                    <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700">
                        {{ $staff->employment_status }}
                    </span>
                </div>

                <div class="mt-3 flex flex-wrap gap-2 text-xs">
                    @if($staff->user)
                        <span class="px-3 py-1 rounded-full {{ $staff->user->is_active && $staff->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $staff->user->is_active && $staff->is_active ? 'Dashboard enabled' : 'Dashboard disabled' }}
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600">No login account</span>
                    @endif

                    @if($staff->oap)
                        <span class="px-3 py-1 rounded-full {{ $staff->oap->is_active ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $staff->oap->is_active ? 'OAP' : 'OAP inactive' }}
                        </span>
                    @endif
                </div>

                <div class="mt-4 flex items-center justify-end space-x-3 text-sm">
                    <a href="{{ route('admin.team.staff.show', $staff->id) }}" class="text-gray-600 hover:text-gray-900">View</a>
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
