<div>
    @php
        $birthday = null;
        if ($staff->birth_month && $staff->birth_day && checkdate((int) $staff->birth_month, (int) $staff->birth_day, (int) ($staff->birth_year ?: 2000))) {
            $birthdayDate = \Illuminate\Support\Carbon::create((int) ($staff->birth_year ?: 2000), (int) $staff->birth_month, (int) $staff->birth_day);
            $birthday = $staff->birth_year ? $birthdayDate->format('M j, Y') : $birthdayDate->format('M j');
        }

        $socialLinks = collect($staff->social_links ?: [])->filter(fn ($link) => filled($link));
    @endphp

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 rounded-lg bg-emerald-100 flex items-center justify-center overflow-hidden">
                    @if($staff->photo_url)
                        <img src="{{ $staff->photo_url }}" alt="{{ $staff->name }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-user text-emerald-600 text-2xl"></i>
                    @endif
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $staff->name }}</h2>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $staff->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $staff->is_active ? 'Active Staff' : 'Inactive Staff' }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $staff->teamRole?->name ?? ($staff->role ?? 'Staff Member') }}
                        <span class="text-gray-300 mx-2">|</span>
                        {{ $staff->departmentRelation?->name ?? ($staff->department ?? 'General') }}
                    </p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('admin.team.staff') }}"
                    class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
                <a href="{{ route('admin.team.staff.edit', $staff->id) }}"
                    class="inline-flex items-center justify-center px-4 py-2 border border-emerald-200 rounded-lg text-emerald-700 hover:bg-emerald-50">
                    <i class="fas fa-pen mr-2"></i>Edit
                </a>
                <button wire:click="toggleStatus"
                    @if($staff->is_active) onclick="return confirm('Mark this staff member inactive? Their dashboard access will be disabled and OAP/program assignments will be removed.')" @endif
                    class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-white {{ $staff->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-emerald-600 hover:bg-emerald-700' }}">
                    <i class="fas {{ $staff->is_active ? 'fa-user-slash' : 'fa-user-check' }} mr-2"></i>
                    {{ $staff->is_active ? 'Deactivate' : 'Reactivate' }}
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Email</p>
                        <p class="font-medium text-gray-900 break-words">{{ $staff->email ?: 'Not set' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Phone</p>
                        <p class="font-medium text-gray-900">{{ $staff->phone ?: 'Not set' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Employment</p>
                        <p class="font-medium text-gray-900">{{ ucfirst(str_replace('-', ' ', $staff->employment_status)) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Joined</p>
                        <p class="font-medium text-gray-900">{{ $staff->joined_date?->format('M j, Y') ?? 'Not set' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Birthday</p>
                        <p class="font-medium text-gray-900">{{ $birthday ?: 'Not set' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Public Team Profile</p>
                        @if($staff->is_active)
                            <a href="{{ route('staff.show', $staff->slug) }}" target="_blank" class="font-medium text-emerald-600 hover:text-emerald-700">
                                Open profile
                            </a>
                        @else
                            <p class="font-medium text-gray-900">Hidden while inactive</p>
                        @endif
                    </div>
                </div>

                <div class="mt-6">
                    <p class="text-gray-500 text-sm mb-2">Bio</p>
                    <div class="prose max-w-none text-sm text-gray-700">
                        {!! nl2br(e($staff->bio ?: 'No bio has been added.')) !!}
                    </div>
                </div>

                <div class="mt-6">
                    <p class="text-gray-500 text-sm mb-2">Social Links</p>
                    @if($socialLinks->isNotEmpty())
                        <div class="flex flex-wrap gap-2">
                            @foreach($socialLinks as $network => $link)
                                <a href="{{ $link }}" target="_blank"
                                    class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold hover:bg-gray-200">
                                    {{ ucfirst($network) }}
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No social links added.</p>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Program Assignments</h3>

                @if($staff->oap)
                    <div class="flex flex-wrap gap-2 mb-5">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $staff->oap->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $staff->oap->is_active ? 'Active OAP' : 'Inactive OAP' }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $staff->oap->available ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $staff->oap->available ? 'Available' : 'Unavailable' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Primary Shows</h4>
                            <div class="space-y-3">
                                @forelse($staff->oap->shows as $show)
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $show->title }}</p>
                                        <p class="text-xs text-gray-500">{{ $show->category?->name ?? 'No category' }}</p>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">No primary shows assigned.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Co-host Shows</h4>
                            <div class="space-y-3">
                                @forelse($coHostedShows as $show)
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $show->title }}</p>
                                        <p class="text-xs text-gray-500">{{ $show->category?->name ?? 'No category' }}</p>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">No co-host shows assigned.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 border border-gray-200 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Schedule Slots</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @forelse($staff->oap->scheduleSlots as $slot)
                                <div class="rounded-lg bg-gray-50 border border-gray-200 p-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $slot->show?->title ?? 'Show removed' }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ ucfirst($slot->day_of_week) }} - {{ $slot->time_range }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">{{ ucfirst($slot->status) }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 md:col-span-2">No schedule slots assigned.</p>
                            @endforelse
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-500">This staff member is not linked to an OAP profile.</p>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Access</h3>
                <div class="space-y-4 text-sm">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-gray-600">Dashboard</span>
                        @if($staff->user)
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $staff->user->is_active && $staff->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $staff->user->is_active && $staff->is_active ? 'Enabled' : 'Disabled' }}
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-semibold">No account</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-gray-500">Linked User</p>
                        <p class="font-medium text-gray-900">{{ $staff->user?->name ?? 'No user account linked' }}</p>
                        @if($staff->user)
                            <p class="text-xs text-gray-500 break-words">{{ $staff->user->email }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-gray-500">Account Role</p>
                        <p class="font-medium text-gray-900">{{ $staff->user?->role_label ?? 'Not available' }}</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 border border-gray-200 p-3 text-xs text-gray-600">
                        Inactive staff cannot access the dashboard. Their API token is revoked when they are deactivated.
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Team</h3>
                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-gray-500">Department</p>
                        <p class="font-medium text-gray-900">{{ $staff->departmentRelation?->name ?? ($staff->department ?? 'General') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Role</p>
                        <p class="font-medium text-gray-900">{{ $staff->teamRole?->name ?? ($staff->role ?? 'Staff Member') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">OAP Profile</p>
                        <p class="font-medium text-gray-900">{{ $staff->oap?->name ?? 'Not linked' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 z-50 bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg flash-auto-dismiss">
            {{ session('success') }}
        </div>
    @endif
</div>
