<div class="space-y-6">
    <section class="relative overflow-hidden rounded-2xl bg-[#0b2f3a] px-6 py-7 text-white shadow-sm sm:px-8">
        <div class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-emerald-400/10"></div>
        <div class="pointer-events-none absolute -bottom-24 right-28 h-56 w-56 rounded-full bg-orange-400/10"></div>

        <div class="relative flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div class="max-w-2xl">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-300">People &amp; culture</p>
                <h2 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl">Your active team, at a glance.</h2>
                <p class="mt-3 max-w-xl text-sm leading-6 text-slate-300">
                    Manage current team members, their responsibilities and dashboard access. Deactivated staff stay out of this directory until their user account is activated again.
                </p>
            </div>

            <a href="{{ route('admin.team.staff.create') }}"
                class="inline-flex w-full items-center justify-center rounded-xl bg-[#ed5a1f] px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-black/10 transition hover:bg-[#d94d16] sm:w-auto">
                <i class="fas fa-user-plus mr-2" aria-hidden="true"></i>
                Add staff member
            </a>
        </div>

        <div class="relative mt-7 grid grid-cols-2 gap-3 lg:grid-cols-4">
            @foreach([
                ['label' => 'Active staff', 'value' => $stats['total'], 'icon' => 'fa-users'],
                ['label' => 'Departments', 'value' => $stats['departments'], 'icon' => 'fa-building'],
                ['label' => 'Active OAPs', 'value' => $stats['oaps'], 'icon' => 'fa-microphone'],
                ['label' => 'Dashboard access', 'value' => $stats['with_login'], 'icon' => 'fa-shield-alt'],
            ] as $stat)
                <div class="rounded-xl border border-white/10 bg-white/[0.07] p-4 backdrop-blur-sm">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-2xl font-black">{{ number_format($stat['value']) }}</p>
                            <p class="mt-1 text-xs font-semibold text-slate-300">{{ $stat['label'] }}</p>
                        </div>
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/10 text-emerald-300">
                            <i class="fas {{ $stat['icon'] }}" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-end">
            <div class="min-w-0 flex-1">
                <label for="staff-search" class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Find a team member</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400" aria-hidden="true"></i>
                    <input id="staff-search" type="search" wire:model.live.debounce.300ms="search"
                        placeholder="Search by name, role, email or department"
                        class="w-full rounded-xl border-slate-300 py-2.5 pl-10 pr-4 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 xl:w-[680px]">
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Department</label>
                    <select wire:model.live="departmentId" class="w-full rounded-xl border-slate-300 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">All</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Role</label>
                    <select wire:model.live="roleId" class="w-full rounded-xl border-slate-300 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">All</option>
                        @foreach($teamRoles as $teamRole)
                            <option value="{{ $teamRole->id }}">{{ $teamRole->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Employment</label>
                    <select wire:model.live="employmentStatus" class="w-full rounded-xl border-slate-300 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">All</option>
                        @foreach($employmentStatuses as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Sort</label>
                    <select wire:model.live="sortBy" class="w-full rounded-xl border-slate-300 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="latest">Newest</option>
                        <option value="oldest">Oldest</option>
                        <option value="name_asc">Name A–Z</option>
                        <option value="name_desc">Name Z–A</option>
                        <option value="department">Department</option>
                        <option value="role">Role</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4 text-xs text-slate-500">
            <span><strong class="text-slate-800">{{ $staffMembers->total() }}</strong> active {{ \Illuminate\Support\Str::plural('staff member', $staffMembers->total()) }}</span>
            @if($hasFilters)
                <button wire:click="clearFilters" class="font-bold text-[#d94d16] transition hover:text-[#b83c0f]">
                    <i class="fas fa-rotate-left mr-1" aria-hidden="true"></i>Reset filters
                </button>
            @endif
        </div>
    </section>

    <section class="grid grid-cols-1 gap-5 md:grid-cols-2 2xl:grid-cols-3">
        @forelse($staffMembers as $staff)
            <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">
                <div class="h-1 bg-gradient-to-r from-emerald-500 via-[#0b2f3a] to-[#ed5a1f]"></div>
                <div class="p-5">
                    <div class="flex items-start gap-4">
                        <div class="h-16 w-16 shrink-0 overflow-hidden rounded-2xl bg-emerald-50 ring-1 ring-emerald-100">
                            @if($staff->photo_url)
                                <img src="{{ $staff->photo_url }}" alt="{{ $staff->name }}" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full w-full items-center justify-center text-xl font-black text-emerald-700">
                                    {{ \Illuminate\Support\Str::of($staff->name)->explode(' ')->filter()->take(2)->map(fn ($part) => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($part, 0, 1)))->implode('') }}
                                </div>
                            @endif
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="truncate text-lg font-black text-slate-900">{{ $staff->name }}</h3>
                                    <p class="mt-0.5 truncate text-sm font-semibold text-emerald-700">{{ $staff->teamRole?->name ?? ($staff->role ?: 'Staff member') }}</p>
                                </div>
                                <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wide text-emerald-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Active
                                </span>
                            </div>
                            <p class="mt-2 text-xs font-semibold text-slate-500">
                                <i class="far fa-building mr-1.5 text-slate-400" aria-hidden="true"></i>{{ $staff->departmentRelation?->name ?? ($staff->department ?: 'General') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-3 rounded-xl bg-slate-50 p-3 text-xs">
                        <div class="min-w-0">
                            <p class="font-bold uppercase tracking-wide text-slate-400">Email</p>
                            <p class="mt-1 truncate font-semibold text-slate-700" title="{{ $staff->email }}">{{ $staff->email ?: 'Not provided' }}</p>
                        </div>
                        <div class="min-w-0 border-l border-slate-200 pl-3">
                            <p class="font-bold uppercase tracking-wide text-slate-400">Employment</p>
                            <p class="mt-1 truncate font-semibold capitalize text-slate-700">{{ $staff->employment_status ?: 'Not set' }}</p>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        @if($staff->user?->is_active)
                            <span class="rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-bold text-blue-700"><i class="fas fa-shield-alt mr-1" aria-hidden="true"></i>Dashboard</span>
                        @else
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-bold text-slate-600">No dashboard login</span>
                        @endif
                        @if($staff->oap?->is_active)
                            <span class="rounded-full bg-orange-50 px-2.5 py-1 text-[11px] font-bold text-orange-700"><i class="fas fa-microphone mr-1" aria-hidden="true"></i>OAP</span>
                        @endif
                    </div>

                    <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                        <div class="flex items-center gap-4 text-sm font-bold">
                            <a href="{{ route('admin.team.staff.show', $staff->id) }}" class="text-slate-700 transition hover:text-emerald-700">View</a>
                            <a href="{{ route('admin.team.staff.edit', $staff->id) }}" class="text-emerald-700 transition hover:text-emerald-900">Edit</a>
                        </div>
                        <button wire:click="deactivateStaff({{ $staff->id }})"
                            wire:confirm="Deactivate {{ $staff->name }}? Their dashboard access will be disabled and OAP/program assignments removed."
                            class="rounded-lg px-2.5 py-1.5 text-xs font-bold text-red-600 transition hover:bg-red-50 hover:text-red-700">
                            <i class="fas fa-user-slash mr-1" aria-hidden="true"></i>Deactivate
                        </button>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-xl text-slate-400">
                    <i class="fas fa-users" aria-hidden="true"></i>
                </span>
                <h3 class="mt-4 text-lg font-black text-slate-900">No active staff found</h3>
                <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">
                    {{ $hasFilters ? 'Try resetting the filters to see more team members.' : 'Add a staff member or activate a linked account from User Management.' }}
                </p>
                @if($hasFilters)
                    <button wire:click="clearFilters" class="mt-5 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-bold text-white hover:bg-slate-800">Reset filters</button>
                @else
                    <a href="{{ route('admin.team.staff.create') }}" class="mt-5 inline-flex rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-emerald-700">Add staff member</a>
                @endif
            </div>
        @endforelse
    </section>

    @if($staffMembers->hasPages())
        <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
            {{ $staffMembers->links() }}
        </div>
    @endif

    @if(session()->has('success'))
        <div class="flash-auto-dismiss fixed bottom-4 right-4 z-50 max-w-sm rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-xl">
            <i class="fas fa-check-circle mr-2" aria-hidden="true"></i>{{ session('success') }}
        </div>
    @endif
</div>
