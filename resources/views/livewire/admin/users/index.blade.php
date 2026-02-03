<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="relative flex-1 max-w-md">
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Search users..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <a href="{{ route('admin.users.create') }}"
                class="inline-flex items-center justify-center px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors duration-150">
                <i class="fas fa-plus mr-2"></i>
                Add User
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Team Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-emerald-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ ucfirst($user->role ?? 'user') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $user->department?->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $user->teamRole?->name ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <button wire:click="toggleStatus({{ $user->id }})"
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right text-sm space-x-3">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-emerald-600 hover:text-emerald-800">Edit</a>
                                <button
                                    wire:click="deleteUser({{ $user->id }})"
                                    onclick="return confirm('Delete this user? This cannot be undone.') || event.stopImmediatePropagation()"
                                    class="text-red-600 hover:text-red-800"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>

    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 z-50 bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg flash-auto-dismiss">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed bottom-4 right-4 z-50 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg flash-auto-dismiss">
            {{ session('error') }}
        </div>
    @endif
</div>
