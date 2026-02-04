<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Link User, Staff, and OAP</h3>
        <p class="mt-1 text-sm text-gray-500">
            Pick a staff profile as the base, then connect its User account and OAP profile.
        </p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        @if($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                <p class="font-semibold">Unable to save links.</p>
                <ul class="mt-1 list-disc pl-5 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Staff Member</label>
                <select wire:model.live="staff_member_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Select staff member</option>
                    @foreach($staffOptions as $staffOption)
                        <option value="{{ $staffOption->id }}">{{ $staffOption->name }}</option>
                    @endforeach
                </select>
                @error('staff_member_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">User Account (optional)</label>
                <select wire:model="user_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">No linked user</option>
                    @foreach($availableUsers as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('user_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">OAP Profile (optional)</label>
                <select wire:model="oap_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">No linked OAP</option>
                    @foreach($availableOaps as $oap)
                        <option value="{{ $oap->id }}">{{ $oap->name }}</option>
                    @endforeach
                </select>
                @error('oap_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-5 flex justify-end">
            <button wire:click="save" type="button" wire:loading.attr="disabled" wire:target="save"
                class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg disabled:opacity-60 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="save">Save Links</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="relative max-w-md">
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Search staff, user, or OAP..."
                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Staff</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Linked User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Linked OAP</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($staffMembers as $staff)
                        <tr>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $staff->name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $staff->teamRole?->name ?? ($staff->role ?: 'Staff') }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                @if($staff->user)
                                    <p>{{ $staff->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $staff->user->email }}</p>
                                @else
                                    <span class="text-gray-400">Not linked</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                @if($staff->oap)
                                    <p>{{ $staff->oap->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $staff->oap->email ?: 'No email' }}</p>
                                @else
                                    <span class="text-gray-400">Not linked</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-sm space-x-3">
                                <button wire:click="loadForEdit({{ $staff->id }})" class="text-emerald-600 hover:text-emerald-800">
                                    Load
                                </button>
                                @if($staff->user)
                                    <button wire:click="unlinkUser({{ $staff->id }})" class="text-amber-600 hover:text-amber-800">
                                        Unlink User
                                    </button>
                                @endif
                                @if($staff->oap)
                                    <button wire:click="unlinkOap({{ $staff->id }})" class="text-red-600 hover:text-red-800">
                                        Unlink OAP
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">No staff records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $staffMembers->links() }}
        </div>
    </div>

    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 z-50 bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg flash-auto-dismiss">
            {{ session('success') }}
        </div>
    @endif
</div>
