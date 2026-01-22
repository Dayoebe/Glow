<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $isEditing ? 'Edit OAP' : 'Add OAP' }}</h3>
                <p class="text-sm text-gray-500">Manage on-air personalities for shows.</p>
            </div>
            <a href="{{ route('admin.shows.oaps') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                <i class="fas fa-arrow-left mr-2"></i>Back to OAPs
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Staff Member</label>
                <select wire:model="staff_member_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Select staff member</option>
                    @foreach($staffMembers as $staffMember)
                        <option value="{{ $staffMember->id }}">{{ $staffMember->name }}</option>
                    @endforeach
                </select>
                @error('staff_member_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                @if(count($staffMembers) === 0)
                    <p class="mt-2 text-xs text-amber-600">Create a staff member before assigning an OAP.</p>
                @endif
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input type="text" wire:model="name"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                <textarea rows="3" wire:model="bio"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                @error('bio') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" wire:model="email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                <input type="text" wire:model="phone"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Specializations</label>
                <input type="text" wire:model="specializations" placeholder="news, sports, music"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="md:col-span-2"
                x-data="{
                    isUploading: false,
                    progress: 0,
                    uploadError: false
                }"
                x-on:livewire-upload-start="isUploading = true; progress = 0; uploadError = false"
                x-on:livewire-upload-finish="isUploading = false; progress = 100"
                x-on:livewire-upload-error="isUploading = false; uploadError = true"
                x-on:livewire-upload-progress="progress = $event.detail.progress"
            >
                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                <input type="file" wire:model="profile_photo_upload" accept="image/*" class="w-full text-sm text-gray-600">
                <p class="mt-1 text-xs text-gray-500">Max size 5MB. JPG, PNG, GIF, WEBP, BMP, SVG.</p>
                <div class="mt-2" x-cloak x-show="isUploading">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-emerald-600 h-2 rounded-full transition-all duration-200"
                            :style="`width: ${progress}%`"></div>
                    </div>
                    <p class="mt-1 text-xs text-gray-600">Uploading... <span x-text="progress"></span>%</p>
                </div>
                <div class="mt-2" x-cloak x-show="uploadError">
                    <p class="text-xs text-red-600">Upload failed. Try a smaller image or a different format.</p>
                </div>
                @error('profile_photo_upload') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                @if ($profile_photo_upload)
                    <img src="{{ $profile_photo_upload->temporaryUrl() }}" alt="Profile preview"
                        class="mt-3 h-32 w-32 rounded-lg object-cover border border-gray-200">
                    <p class="mt-1 text-xs text-emerald-600">Upload ready.</p>
                @endif
                <input type="url" wire:model="profile_photo" placeholder="https://example.com/photo.jpg"
                    class="mt-3 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.shows.oaps') }}"
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                Cancel
            </a>
            <button wire:click="save"
                class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                {{ $isEditing ? 'Update OAP' : 'Create OAP' }}
            </button>
        </div>
    </div>
</div>
