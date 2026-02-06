<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-2 md:space-y-0">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Staff Birthdays</h3>
                <p class="text-sm text-gray-500">Track birthdays and send greetings to your team.</p>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search Staff</label>
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Search by name..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Upcoming Range (days)</label>
                <input type="number" min="1" max="365" wire:model.live.debounce.300ms="rangeDays"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="flex items-end">
                <label class="flex items-center space-x-2 text-sm text-gray-700">
                    <input type="checkbox" wire:model.live="showInactive" class="rounded border-gray-300">
                    <span>Include inactive staff</span>
                </label>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h4 class="text-md font-semibold text-gray-900">Birthday Email Template</h4>
                <p class="text-sm text-gray-500">Customize the birthday email sent at 7:00 AM ({{ config('app.timezone') }}).</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                <input type="text" wire:model="email_subject"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @error('email_subject') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                <textarea rows="6" wire:model="email_body"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                @error('email_body') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preview Staff</label>
                    <select wire:model.live="test_staff_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">Auto select</option>
                        @foreach($previewStaffOptions as $option)
                            <option value="{{ $option->id }}">
                                {{ $option->name }}{{ $option->is_active ? '' : ' (inactive)' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Test Email</label>
                    <input type="email" wire:model="test_email"
                        placeholder="you@example.com"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    @error('test_email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <p class="text-xs uppercase tracking-wide text-gray-500">Preview Subject</p>
                <p class="text-sm text-gray-900 font-medium mt-1">{{ $previewSubject }}</p>
                <p class="text-xs uppercase tracking-wide text-gray-500 mt-4">Preview Message</p>
                <p class="text-sm text-gray-700 mt-1 whitespace-pre-line">{{ $previewBody }}</p>
            </div>
            <div class="text-xs text-gray-500">
                Available placeholders:
                <span class="font-mono">{first_name}</span>,
                <span class="font-mono">{name}</span>,
                <span class="font-mono">{station_name}</span>,
                <span class="font-mono">{station_frequency}</span>,
                <span class="font-mono">{role}</span>,
                <span class="font-mono">{department}</span>,
                <span class="font-mono">{today}</span>,
                <span class="font-mono">{year}</span>
            </div>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <button wire:click="sendTestEmail"
                    class="px-5 py-2.5 border border-emerald-600 text-emerald-700 rounded-lg hover:bg-emerald-50 font-semibold">
                    Send Test Email
                </button>
                <button wire:click="saveEmailSettings"
                    class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg">
                    Save Template
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <p class="text-xs uppercase tracking-wide text-gray-500">With DOB</p>
            <p class="text-2xl font-semibold text-gray-900 mt-2">{{ $birthdayRows->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <p class="text-xs uppercase tracking-wide text-gray-500">Upcoming ({{ $rangeDays }} days)</p>
            <p class="text-2xl font-semibold text-gray-900 mt-2">{{ $upcomingBirthdays->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <p class="text-xs uppercase tracking-wide text-gray-500">Missing DOB</p>
            <p class="text-2xl font-semibold text-gray-900 mt-2">{{ $missingDob->count() }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
            <h4 class="text-md font-semibold text-gray-900">Today's Birthdays</h4>
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-500">{{ now()->format('F j, Y') }}</span>
                <label class="flex items-center space-x-2 text-xs text-gray-600">
                    <input type="checkbox" wire:model="force_send" class="rounded border-gray-300">
                    <span>Force resend</span>
                </label>
                <button wire:click="sendTodayEmails"
                    class="px-4 py-2 text-xs font-semibold border border-emerald-600 text-emerald-700 rounded-lg hover:bg-emerald-50">
                    Send Today's Emails
                </button>
            </div>
        </div>
        @if($todayBirthdays->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($todayBirthdays as $row)
                    <div class="py-3 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $row['staff']->name }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $row['staff']->teamRole?->name ?? ($row['staff']->role ?? 'Staff Member') }}
                                · {{ $row['staff']->departmentRelation?->name ?? ($row['staff']->department ?? 'General') }}
                            </p>
                        </div>
                        <div class="text-sm text-gray-600">DOB: {{ $row['dob']->format('M j, Y') }}</div>
                        <div class="text-sm font-semibold text-emerald-600">Today</div>
                        <div>
                            @if($row['staff']->email)
                                <a href="mailto:{{ $row['staff']->email }}?subject={{ rawurlencode('Happy Birthday ' . $row['staff']->name . '!') }}"
                                   class="text-emerald-600 hover:text-emerald-800 text-sm">Send Email</a>
                            @else
                                <span class="text-xs text-gray-400">No email</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500">No birthdays today.</p>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-md font-semibold text-gray-900">Upcoming Birthdays (Next {{ $rangeDays }} days)</h4>
        </div>
        @if($upcomingBirthdays->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs uppercase tracking-wide text-gray-500 border-b">
                            <th class="py-2 pr-4">Staff</th>
                            <th class="py-2 pr-4">DOB</th>
                            <th class="py-2 pr-4">Next Birthday</th>
                            <th class="py-2 pr-4">Days Until</th>
                            <th class="py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($upcomingBirthdays as $row)
                            <tr>
                                <td class="py-3 pr-4">
                                    <p class="font-medium text-gray-900">{{ $row['staff']->name }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $row['staff']->teamRole?->name ?? ($row['staff']->role ?? 'Staff Member') }}
                                        · {{ $row['staff']->departmentRelation?->name ?? ($row['staff']->department ?? 'General') }}
                                    </p>
                                </td>
                                <td class="py-3 pr-4 text-gray-600">{{ $row['dob']->format('M j, Y') }}</td>
                                <td class="py-3 pr-4 text-gray-600">{{ $row['next_birthday']->format('M j, Y') }}</td>
                                <td class="py-3 pr-4">
                                    @if($row['days_until'] === 0)
                                        <span class="text-emerald-600 font-semibold">Today</span>
                                    @else
                                        <span class="text-gray-600">{{ $row['days_until'] }} days</span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('admin.team.staff.edit', $row['staff']->id) }}"
                                           class="text-emerald-600 hover:text-emerald-800">Edit</a>
                                        @if($row['staff']->email)
                                            <a href="mailto:{{ $row['staff']->email }}?subject={{ rawurlencode('Happy Birthday ' . $row['staff']->name . '!') }}"
                                               class="text-emerald-600 hover:text-emerald-800">Email</a>
                                        @else
                                            <span class="text-xs text-gray-400">No email</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-gray-500">No upcoming birthdays in this range.</p>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-md font-semibold text-gray-900">Missing Date of Birth</h4>
        </div>
        @if($missingDob->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($missingDob as $staff)
                    <div class="py-3 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $staff->name }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $staff->teamRole?->name ?? ($staff->role ?? 'Staff Member') }}
                                · {{ $staff->departmentRelation?->name ?? ($staff->department ?? 'General') }}
                            </p>
                        </div>
                        <a href="{{ route('admin.team.staff.edit', $staff->id) }}"
                           class="text-emerald-600 hover:text-emerald-800 text-sm">Add DOB</a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500">All staff have a date of birth recorded.</p>
        @endif
    </div>

    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 z-50 bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg flash-auto-dismiss">
            {{ session('success') }}
        </div>
    @endif
</div>
