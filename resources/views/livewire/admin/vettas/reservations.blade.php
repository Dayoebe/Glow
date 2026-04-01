<div>
    <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-5">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="mb-1 text-sm text-gray-600">Total Requests</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100">
                    <i class="fas fa-receipt text-xl text-slate-700"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="mb-1 text-sm text-gray-600">New</p>
                    <p class="text-2xl font-bold text-amber-600">{{ $stats['new'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-amber-100">
                    <i class="fas fa-bell text-xl text-amber-600"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="mb-1 text-sm text-gray-600">Confirmed</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ $stats['confirmed'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-emerald-100">
                    <i class="fas fa-circle-check text-xl text-emerald-600"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="mb-1 text-sm text-gray-600">Upcoming</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['upcoming'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100">
                    <i class="fas fa-calendar-day text-xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="mb-1 text-sm text-gray-600">Completed</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['completed'] }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100">
                    <i class="fas fa-bed text-xl text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-1 flex-col gap-3 sm:flex-row">
                <div class="relative max-w-md flex-1">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Search by guest, email, phone, or code..."
                        class="w-full rounded-lg border border-gray-300 py-2.5 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>

                <select wire:model.live="filterStatus"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">All Statuses</option>
                    @foreach($statusOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>

                <select wire:model.live="filterTimeline"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="upcoming">Upcoming Stays</option>
                    <option value="all">All Reservations</option>
                    <option value="past">Past Stays</option>
                </select>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.vettas.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-5 py-2.5 font-semibold text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-images mr-2"></i>
                    Gallery
                </a>
                <a href="{{ route('admin.vettas.settings') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-5 py-2.5 font-semibold text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-sliders mr-2"></i>
                    Settings
                </a>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Guest</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Stay</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Request</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Submitted</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($reservations as $reservation)
                        @php
                            $statusClasses = match ($reservation->status) {
                                'confirmed' => 'bg-emerald-100 text-emerald-700',
                                'completed' => 'bg-purple-100 text-purple-700',
                                'cancelled' => 'bg-red-100 text-red-700',
                                'contacted' => 'bg-blue-100 text-blue-700',
                                default => 'bg-amber-100 text-amber-700',
                            };
                        @endphp
                        <tr class="transition-colors duration-150 hover:bg-gray-50">
                            <td class="px-6 py-4 align-top">
                                <div class="space-y-1">
                                    <p class="text-sm font-semibold text-gray-900">{{ $reservation->full_name }}</p>
                                    <p class="text-xs text-gray-600">{{ $reservation->email }}</p>
                                    <p class="text-xs text-gray-500">{{ $reservation->phone }}</p>
                                    <p class="pt-1 text-xs font-medium text-gray-400">{{ $reservation->reservation_code }}</p>
                                    @if($reservation->user)
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-1 text-[11px] font-medium text-slate-600">
                                            Account: {{ $reservation->user->name }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="space-y-1 text-sm text-gray-700">
                                    <p><span class="font-medium text-gray-900">Check-in:</span> {{ $reservation->check_in_date?->format('M d, Y') }}</p>
                                    <p><span class="font-medium text-gray-900">Check-out:</span> {{ $reservation->check_out_date?->format('M d, Y') }}</p>
                                    <p><span class="font-medium text-gray-900">Guests:</span> {{ $reservation->guest_count }}</p>
                                    <p><span class="font-medium text-gray-900">Nights:</span> {{ $reservation->nights ?? 'N/A' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="space-y-2">
                                    <p class="max-w-sm text-sm text-gray-600">
                                        {{ $reservation->special_requests ?: 'No special requests provided.' }}
                                    </p>
                                    @if($reservation->admin_notes)
                                        <div class="rounded-lg bg-slate-50 px-3 py-2 text-xs text-slate-600">
                                            <span class="font-semibold text-slate-800">Admin note:</span>
                                            {{ \Illuminate\Support\Str::limit($reservation->admin_notes, 120) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <div class="flex flex-col gap-2">
                                    <span class="inline-flex w-fit items-center rounded px-2 py-1 text-xs font-medium {{ $statusClasses }}">
                                        {{ $statusOptions[$reservation->status] ?? \Illuminate\Support\Str::of($reservation->status)->title() }}
                                    </span>
                                    <select wire:change="setStatus({{ $reservation->id }}, $event.target.value)"
                                        class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                        <option value="{{ $reservation->status }}">{{ $statusOptions[$reservation->status] ?? \Illuminate\Support\Str::of($reservation->status)->title() }}</option>
                                        @foreach($statusOptions as $value => $label)
                                            @if($value !== $reservation->status)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top text-sm text-gray-700">
                                <p>{{ $reservation->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $reservation->created_at->diffForHumans() }}</p>
                                @if($reservation->reviewed_at)
                                    <p class="mt-2 text-xs text-gray-500">
                                        Reviewed {{ $reservation->reviewed_at->diffForHumans() }}
                                        @if($reservation->reviewedBy)
                                            by {{ $reservation->reviewedBy->name }}
                                        @endif
                                    </p>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-top text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <button wire:click="openNotesModal({{ $reservation->id }})"
                                        class="text-blue-600 hover:text-blue-800" title="Notes">
                                        <i class="fas fa-note-sticky"></i>
                                    </button>
                                    <button wire:click="deleteReservation({{ $reservation->id }})"
                                        class="text-red-600 hover:text-red-800" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                No reservations found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-200 px-6 py-4">
            {{ $reservations->links() }}
        </div>
    </div>

    @if($showNotesModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    wire:click="closeNotesModal"></div>

                <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>

                <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-note-sticky text-blue-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">Reservation Notes</h3>
                                <p class="mt-2 text-sm text-gray-500">Add internal notes for follow-up, availability, or confirmation updates.</p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <textarea wire:model="admin_notes" rows="7"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                placeholder="Add internal notes here..."></textarea>
                            @error('admin_notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button wire:click="saveNotes" type="button"
                            class="inline-flex w-full justify-center rounded-md border border-transparent bg-emerald-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-emerald-700 sm:ml-3 sm:w-auto sm:text-sm">
                            Save Notes
                        </button>
                        <button wire:click="closeNotesModal" type="button"
                            class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
