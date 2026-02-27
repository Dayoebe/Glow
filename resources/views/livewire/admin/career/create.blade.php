<div>
    <form wire:submit.prevent="saveAsDraft">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Role Details</h3>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                        <input type="text" wire:model.live.debounce.300ms="title"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500"
                            placeholder="e.g. Senior Radio Producer">
                        @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                        <input type="text" wire:model="slug"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500"
                            placeholder="auto-generated-from-title">
                        @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Excerpt</label>
                        <textarea rows="3" wire:model="excerpt"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500"
                            placeholder="Short role summary for listings"></textarea>
                        @error('excerpt') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
                        <textarea rows="9" wire:model="description"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500"
                            placeholder="Provide complete role description"></textarea>
                        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Role Content</h3>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Responsibilities</label>
                        <textarea rows="7" wire:model="responsibilities"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500"
                            placeholder="List responsibilities"></textarea>
                        @error('responsibilities') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Requirements</label>
                        <textarea rows="7" wire:model="requirements"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500"
                            placeholder="List qualifications and requirements"></textarea>
                        @error('requirements') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Benefits</label>
                        <textarea rows="6" wire:model="benefits"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500"
                            placeholder="Compensation highlights, perks, and growth opportunities"></textarea>
                        @error('benefits') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Publish</h3>
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Status</span>
                            <span class="font-semibold {{ $is_published ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $is_published ? 'Published' : 'Draft' }}
                            </span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Publish Date</label>
                        <input type="datetime-local" wire:model="published_at"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500">
                        @error('published_at') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-3">
                        <button type="submit" class="w-full px-4 py-3 bg-gray-700 hover:bg-gray-800 text-white font-semibold rounded-lg">
                            <i class="fas fa-save mr-2"></i>
                            Save as Draft
                        </button>
                        <button type="button" wire:click="publishNow"
                            class="w-full px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Publish Now
                        </button>
                        <a href="{{ route('admin.careers.index') }}"
                            class="block text-center w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold">
                            Cancel
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900">Role Setup</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                        <input type="text" wire:model="department"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500"
                            placeholder="Programming, Marketing, Operations">
                        @error('department') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Employment Type <span class="text-red-500">*</span></label>
                            <select wire:model="employment_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500">
                                <option value="full-time">Full Time</option>
                                <option value="part-time">Part Time</option>
                                <option value="contract">Contract</option>
                                <option value="internship">Internship</option>
                                <option value="freelance">Freelance</option>
                                <option value="temporary">Temporary</option>
                            </select>
                            @error('employment_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Workplace Type <span class="text-red-500">*</span></label>
                            <select wire:model="workplace_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500">
                                <option value="onsite">On-site</option>
                                <option value="hybrid">Hybrid</option>
                                <option value="remote">Remote</option>
                            </select>
                            @error('workplace_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Experience Level <span class="text-red-500">*</span></label>
                        <select wire:model="experience_level" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500">
                            <option value="entry">Entry</option>
                            <option value="junior">Junior</option>
                            <option value="mid">Mid</option>
                            <option value="senior">Senior</option>
                            <option value="lead">Lead</option>
                            <option value="executive">Executive</option>
                        </select>
                        @error('experience_level') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                        <select wire:model="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500">
                            <option value="open">Open</option>
                            <option value="paused">Paused</option>
                            <option value="closed">Closed</option>
                        </select>
                        @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900">Location & Hiring</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Location Label</label>
                        <input type="text" wire:model="location"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500"
                            placeholder="Akure HQ, Remote, Lagos Office">
                        @error('location') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <input type="text" wire:model="city" placeholder="City"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500">
                        <input type="text" wire:model="state" placeholder="State"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500">
                        <input type="text" wire:model="country" placeholder="Country"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500">
                    </div>
                    @error('city') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    @error('state') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    @error('country') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deadline</label>
                            <input type="date" wire:model="application_deadline" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500">
                            @error('application_deadline') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                            <input type="date" wire:model="start_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500">
                            @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Openings</label>
                        <input type="number" min="1" wire:model="positions_available"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500">
                        @error('positions_available') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900">Compensation</h3>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Min Salary</label>
                            <input type="number" min="0" step="0.01" wire:model="min_salary"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500">
                            @error('min_salary') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Max Salary</label>
                            <input type="number" min="0" step="0.01" wire:model="max_salary"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500">
                            @error('max_salary') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                            <input type="text" wire:model="salary_currency"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500"
                                placeholder="NGN">
                            @error('salary_currency') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Period</label>
                            <select wire:model="salary_period" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-emerald-500">
                                <option value="hourly">Hourly</option>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                            @error('salary_period') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="space-y-3 pt-2">
                        <label class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">Allow Applications</p>
                                <p class="text-xs text-gray-500">Visitors can submit applications</p>
                            </div>
                            <input type="checkbox" wire:model="allow_applications" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        </label>

                        <label class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">Feature This Role</p>
                                <p class="text-xs text-gray-500">Highlight on public careers page</p>
                            </div>
                            <input type="checkbox" wire:model="is_featured" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
