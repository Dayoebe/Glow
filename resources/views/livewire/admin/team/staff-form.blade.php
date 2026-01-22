<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $isEditing ? 'Edit Staff' : 'Add Staff' }}</h3>
                <p class="text-sm text-gray-500">Manage your team members.</p>
            </div>
            <a href="{{ route('admin.team.staff') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                <i class="fas fa-arrow-left mr-2"></i>Back to Staff
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input type="text" wire:model="name"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                <select wire:model="department_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Select department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
                @error('department_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                @if(count($departments) === 0)
                    <p class="mt-2 text-xs text-amber-600">Create a department before assigning staff roles.</p>
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select wire:model="team_role_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Select role</option>
                    @foreach($teamRoles as $teamRole)
                        <option value="{{ $teamRole->id }}">{{ $teamRole->name }}</option>
                    @endforeach
                </select>
                @error('team_role_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Photo URL</label>
                <input type="text" wire:model="photo_url"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Photo</label>
                <input type="file" wire:model="photo_upload" accept="image/*"
                    class="w-full text-sm text-gray-600">
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
                @error('photo_upload') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                @if ($photo_upload)
                    <img src="{{ $photo_upload->temporaryUrl() }}" alt="Staff preview"
                        class="mt-3 h-32 w-32 rounded-lg object-cover border border-gray-200">
                    <p class="mt-1 text-xs text-emerald-600">Upload ready.</p>
                @endif
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" wire:model="email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                <input type="text" wire:model="phone"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Employment Status</label>
                <select wire:model="employment_status"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="full-time">Full-time</option>
                    <option value="part-time">Part-time</option>
                    <option value="contract">Contract</option>
                    <option value="freelance">Freelance</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Joined Date</label>
                <input type="date" wire:model="joined_date"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                <textarea rows="4" wire:model="bio"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>
        </div>

        <div class="mt-6 flex items-center space-x-4">
            <label class="flex items-center space-x-2 text-sm text-gray-700">
                <input type="checkbox" wire:model="is_active" class="rounded border-gray-300">
                <span>Active</span>
            </label>
        </div>

        <div class="mt-8">
            <h4 class="text-md font-semibold text-gray-900 mb-4">Social Links</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" wire:model="social_links.facebook" placeholder="Facebook URL"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <input type="text" wire:model="social_links.twitter" placeholder="Twitter URL"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <input type="text" wire:model="social_links.instagram" placeholder="Instagram URL"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <input type="text" wire:model="social_links.linkedin" placeholder="LinkedIn URL"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.team.staff') }}"
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                Cancel
            </a>
            <button wire:click="save"
                class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                {{ $isEditing ? 'Update Staff' : 'Create Staff' }}
            </button>
        </div>
    </div>
</div>
