<div class="space-y-6">
    <section class="relative overflow-hidden rounded-2xl bg-[#0b2f3a] px-6 py-7 text-white shadow-sm sm:px-8">
        <div class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-blue-400/10"></div>
        <div class="pointer-events-none absolute -bottom-24 right-28 h-56 w-56 rounded-full bg-orange-400/10"></div>

        <div class="relative flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div class="max-w-2xl">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-300">Access control</p>
                <h2 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl">User Management</h2>
                <p class="mt-3 max-w-xl text-sm leading-6 text-slate-300">
                    Control dashboard access, account roles and team assignments from one place. Linked staff profiles follow the user’s active status automatically.
                </p>
            </div>

            <a href="{{ route('admin.users.create') }}"
                class="inline-flex w-full items-center justify-center rounded-xl bg-[#ed5a1f] px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-black/10 transition hover:bg-[#d94d16] sm:w-auto">
                <i class="fas fa-user-plus mr-2" aria-hidden="true"></i>Add user
            </a>
        </div>

        <div class="relative mt-7 grid grid-cols-2 gap-3 lg:grid-cols-4">
            @foreach([
                ['label' => 'All accounts', 'value' => $stats['total'], 'icon' => 'fa-users'],
                ['label' => 'Active access', 'value' => $stats['active'], 'icon' => 'fa-user-check'],
                ['label' => 'Administrators', 'value' => $stats['admins'], 'icon' => 'fa-user-shield'],
                ['label' => 'Linked staff', 'value' => $stats['linked_staff'], 'icon' => 'fa-id-badge'],
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
                <label for="user-search" class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Find an account</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400" aria-hidden="true"></i>
                    <input id="user-search" type="search" wire:model.live.debounce.300ms="search"
                        placeholder="Search by name, email, role or department"
                        class="w-full rounded-xl border-slate-300 py-2.5 pl-10 pr-4 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 xl:w-[680px]">
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Status</label>
                    <select wire:model.live="status" class="w-full rounded-xl border-slate-300 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="all">All</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Account role</label>
                    <select wire:model.live="role" class="w-full rounded-xl border-slate-300 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">All</option>
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                        <option value="corp_member">Corp member</option>
                        <option value="intern">Intern</option>
                        <option value="user">User</option>
                    </select>
                </div>
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
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-500">Sort</label>
                    <select wire:model.live="sortBy" class="w-full rounded-xl border-slate-300 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="latest">Newest</option>
                        <option value="oldest">Oldest</option>
                        <option value="name_asc">Name A–Z</option>
                        <option value="name_desc">Name Z–A</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4 text-xs text-slate-500">
            <span><strong class="text-slate-800">{{ $users->total() }}</strong> {{ \Illuminate\Support\Str::plural('account', $users->total()) }}</span>
            @if($hasFilters)
                <button wire:click="clearFilters" class="font-bold text-[#d94d16] transition hover:text-[#b83c0f]">
                    <i class="fas fa-rotate-left mr-1" aria-hidden="true"></i>Reset filters
                </button>
            @endif
        </div>
    </section>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="hidden overflow-x-auto lg:block">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Account</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Access role</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Team assignment</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Staff profile</th>
                        <th class="px-6 py-3.5 text-left text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-6 py-3.5 text-right text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($users as $user)
                        <tr class="transition hover:bg-slate-50/70" wire:key="user-row-{{ $user->id }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-11 w-11 shrink-0 overflow-hidden rounded-xl bg-emerald-50 ring-1 ring-emerald-100">
                                        @if($user->avatar)
                                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center text-sm font-black text-emerald-700">
                                                {{ \Illuminate\Support\Str::of($user->name)->explode(' ')->filter()->take(2)->map(fn ($part) => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($part, 0, 1)))->implode('') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <p class="max-w-[220px] truncate text-sm font-extrabold text-slate-900">{{ $user->name }}</p>
                                            @if($user->id === auth()->id())
                                                <span class="rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-700">You</span>
                                            @endif
                                        </div>
                                        <p class="mt-0.5 max-w-[250px] truncate text-xs text-slate-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-bold {{ $user->role === 'admin' ? 'bg-violet-50 text-violet-700' : ($user->role === 'user' ? 'bg-slate-100 text-slate-700' : 'bg-blue-50 text-blue-700') }}">
                                    {{ $user->role_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <p class="font-semibold text-slate-800">{{ $user->department?->name ?? 'Unassigned' }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">{{ $user->teamRole?->name ?? 'No team role' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->staffMember)
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold {{ $user->staffMember->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                        <i class="fas fa-id-badge mr-1.5" aria-hidden="true"></i>{{ $user->staffMember->is_active ? 'Active staff' : 'Staff inactive' }}
                                    </span>
                                @else
                                    <span class="text-xs font-semibold text-slate-400">Not linked</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <button wire:click="toggleStatus({{ $user->id }})"
                                    @disabled($user->id === auth()->id())
                                    @if($user->is_active && $user->id !== auth()->id()) wire:confirm="Deactivate {{ $user->name }}? Their dashboard access and linked staff profile will be disabled." @endif
                                    class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-extrabold transition disabled:cursor-not-allowed disabled:opacity-60 {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>{{ $user->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="rounded-lg px-3 py-2 text-xs font-bold text-emerald-700 transition hover:bg-emerald-50">Edit</a>
                                    @if($user->id !== auth()->id())
                                        <button wire:click="deleteUser({{ $user->id }})"
                                            wire:confirm="Delete {{ $user->name }}? Their account will be removed and any linked staff profile will be deactivated."
                                            class="rounded-lg px-3 py-2 text-xs font-bold text-red-600 transition hover:bg-red-50">Delete</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-sm text-slate-500">No accounts match your filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="divide-y divide-slate-100 lg:hidden">
            @forelse($users as $user)
                <article class="p-5" wire:key="user-card-{{ $user->id }}">
                    <div class="flex items-start gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-emerald-50 text-sm font-black text-emerald-700 ring-1 ring-emerald-100">
                            @if($user->avatar)
                                <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                            @else
                                {{ \Illuminate\Support\Str::of($user->name)->explode(' ')->filter()->take(2)->map(fn ($part) => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($part, 0, 1)))->implode('') }}
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="truncate text-sm font-extrabold text-slate-900">{{ $user->name }}</h3>
                                <span class="shrink-0 rounded-full px-2 py-1 text-[10px] font-extrabold {{ $user->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                            </div>
                            <p class="mt-0.5 truncate text-xs text-slate-500">{{ $user->email }}</p>
                            <p class="mt-2 text-xs font-semibold text-slate-700">{{ $user->role_label }} · {{ $user->department?->name ?? 'Unassigned' }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3">
                        <span class="text-xs font-semibold text-slate-500">{{ $user->staffMember ? ($user->staffMember->is_active ? 'Active staff profile' : 'Inactive staff profile') : 'No staff profile' }}</span>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="rounded-lg px-2.5 py-1.5 text-xs font-bold text-emerald-700 hover:bg-emerald-50">Edit</a>
                            @if($user->id !== auth()->id())
                                <button wire:click="toggleStatus({{ $user->id }})"
                                    @if($user->is_active) wire:confirm="Deactivate {{ $user->name }}?" @endif
                                    class="rounded-lg px-2.5 py-1.5 text-xs font-bold {{ $user->is_active ? 'text-red-600 hover:bg-red-50' : 'text-emerald-700 hover:bg-emerald-50' }}">
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="px-6 py-16 text-center text-sm text-slate-500">No accounts match your filters.</div>
            @endforelse
        </div>

        @if($users->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">{{ $users->links() }}</div>
        @endif
    </section>

    @if(session()->has('success'))
        <div class="flash-auto-dismiss fixed bottom-4 right-4 z-50 max-w-sm rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-xl">
            <i class="fas fa-check-circle mr-2" aria-hidden="true"></i>{{ session('success') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="flash-auto-dismiss fixed bottom-4 right-4 z-50 max-w-sm rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white shadow-xl">
            <i class="fas fa-exclamation-circle mr-2" aria-hidden="true"></i>{{ session('error') }}
        </div>
    @endif
</div>
