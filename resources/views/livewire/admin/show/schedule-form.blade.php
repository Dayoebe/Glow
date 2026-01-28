<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $isEditing ? 'Edit Schedule Slot' : 'Add Schedule Slot' }}</h3>
                <p class="text-sm text-gray-500">Plan weekly programming slots.</p>
            </div>
            <a href="{{ route('admin.shows.schedule') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                <i class="fas fa-arrow-left mr-2"></i>Back to Schedule
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Show</label>
                <select wire:model.live="schedule_show_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Select show</option>
                    @foreach($allShows as $show)
                        <option value="{{ $show->id }}">{{ $show->title }}</option>
                    @endforeach
                </select>
                @error('schedule_show_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">OAP</label>
                <select wire:model.live="schedule_oap_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Select OAP</option>
                    @foreach($allOaps as $oap)
                        <option value="{{ $oap->id }}">{{ $oap->name }}</option>
                    @endforeach
                </select>
                @error('schedule_oap_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Frequency</label>
                <select wire:model.live="schedule_recurrence_type"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="selected">Selected Days</option>
                </select>
                @error('schedule_recurrence_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                @if($isEditing)
                    <p class="mt-1 text-xs text-amber-600">Changing frequency will replace this slot with new days.</p>
                @endif
            </div>
            @if($schedule_recurrence_type === 'weekly')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Day</label>
                    <select wire:model.live="schedule_day_of_week"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @foreach($daysOfWeek as $day)
                            <option value="{{ $day }}">{{ ucfirst($day) }}</option>
                        @endforeach
                    </select>
                    @error('schedule_day_of_week') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            @elseif($schedule_recurrence_type === 'selected')
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Days</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach($daysOfWeek as $day)
                            <label class="flex items-center space-x-2 text-sm text-gray-700">
                                <input type="checkbox" wire:model.live="schedule_days" value="{{ $day }}"
                                    class="rounded border-gray-300">
                                <span>{{ ucfirst($day) }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('schedule_days') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            @endif
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start</label>
                    <input type="time" wire:model.live="schedule_start_time"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('schedule_start_time') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End</label>
                    <input type="time" wire:model.live="schedule_end_time"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('schedule_end_time') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" wire:model.live="schedule_start_date"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" wire:model.live="schedule_end_date"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select wire:model.live="schedule_status"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="active">Active</option>
                    <option value="paused">Paused</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div class="flex items-center space-x-2 mt-6">
                <input type="checkbox" wire:model.live="schedule_is_recurring" class="rounded border-gray-300">
                <span class="text-sm text-gray-700">Recurring weekly</span>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea rows="3" wire:model.live="schedule_notes"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>
                </div>
            </div>
            <div class="lg:col-span-1">
                <div class="border border-emerald-100 bg-emerald-50 rounded-xl p-4 sticky top-28">
                    <h4 class="text-sm font-semibold text-emerald-800 mb-3">Live Preview</h4>
                    <div class="space-y-3 text-sm text-emerald-900">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-emerald-600">Show</p>
                            <p class="font-semibold">
                                {{ $allShows->firstWhere('id', $schedule_show_id)?->title ?? 'Select a show' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-emerald-600">OAP</p>
                            <p class="font-semibold">
                                {{ $allOaps->firstWhere('id', $schedule_oap_id)?->name ?? 'Not assigned' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-emerald-600">Frequency</p>
                            <p class="font-semibold capitalize">{{ $schedule_recurrence_type ?: 'weekly' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-emerald-600">Days</p>
                            @if($schedule_recurrence_type === 'daily')
                                <p class="font-semibold">Every day</p>
                            @elseif($schedule_recurrence_type === 'selected')
                                <p class="font-semibold">
                                    {{ !empty($schedule_days) ? collect($schedule_days)->map(fn ($d) => ucfirst($d))->implode(', ') : 'Pick days' }}
                                </p>
                            @else
                                <p class="font-semibold">{{ ucfirst($schedule_day_of_week) }}</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-emerald-600">Time</p>
                            <p class="font-semibold">
                                {{ $schedule_start_time ?: '--:--' }} - {{ $schedule_end_time ?: '--:--' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-emerald-600">Date Range</p>
                            <p class="font-semibold">
                                {{ $schedule_start_date ?: 'No start' }} → {{ $schedule_end_date ?: 'No end' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-emerald-600">Status</p>
                            <p class="font-semibold capitalize">{{ $schedule_status }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-emerald-600">Recurring</p>
                            <p class="font-semibold">{{ $schedule_is_recurring ? 'Yes' : 'No' }}</p>
                        </div>
                        @if(!empty($conflictWarnings))
                            <div class="border border-red-200 bg-red-50 text-red-700 rounded-lg p-3">
                                <p class="text-xs uppercase tracking-wide font-semibold text-red-600 mb-1">Conflicts</p>
                                <ul class="space-y-1 text-xs font-medium">
                                    @foreach($conflictWarnings as $warning)
                                        <li>{{ $warning }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(!empty($schedule_notes))
                            <div>
                                <p class="text-xs uppercase tracking-wide text-emerald-600">Notes</p>
                                <p class="font-semibold">{{ $schedule_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.shows.schedule') }}"
               class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                Cancel
            </a>
            <button wire:click="save"
                class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                {{ $isEditing ? 'Update Schedule' : 'Create Schedule' }}
            </button>
        </div>
    </div>
</div>
