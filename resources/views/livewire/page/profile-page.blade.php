<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <x-ad-slot placement="profile" />
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
                        <p class="text-sm text-gray-500">Update your personal details.</p>
                    </div>
                    <div class="w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center overflow-hidden">
                        @if($avatar)
                            <img src="{{ $avatar }}" alt="Profile" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user text-emerald-600"></i>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" wire:model="name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" wire:model="email"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Avatar URL</label>
                        <input type="text" wire:model="avatar"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @error('avatar') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button wire:click="save"
                        class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 z-50 bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg flash-auto-dismiss">
            {{ session('success') }}
        </div>
    @endif
</section>
