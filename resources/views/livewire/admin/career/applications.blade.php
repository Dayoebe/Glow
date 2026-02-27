<div>
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600">Total</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600">New</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $stats['new'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600">Reviewing</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['reviewing'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600">Shortlisted</p>
            <p class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['shortlisted'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600">Hired</p>
            <p class="text-2xl font-bold text-purple-600 mt-1">{{ $stats['hired'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search name, email, role, code..."
                class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">

            <select wire:model.live="filterPosition" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">All Positions</option>
                @foreach($positions as $position)
                    <option value="{{ $position->id }}">{{ $position->title }}</option>
                @endforeach
            </select>

            <select wire:model.live="filterStatus" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">All Status</option>
                <option value="new">New</option>
                <option value="reviewing">Reviewing</option>
                <option value="shortlisted">Shortlisted</option>
                <option value="rejected">Rejected</option>
                <option value="hired">Hired</option>
                <option value="archived">Archived</option>
            </select>

            <a href="{{ route('admin.careers.index') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold">
                <i class="fas fa-briefcase mr-2"></i>
                Manage Roles
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resume</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($applications as $application)
                        @php
                            $resumeUrl = \Illuminate\Support\Str::startsWith($application->resume_path, ['http://', 'https://'])
                                ? $application->resume_path
                                : \Illuminate\Support\Facades\Storage::url($application->resume_path);
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <p class="text-sm font-semibold text-gray-900">{{ $application->full_name }}</p>
                                <p class="text-xs text-gray-600">{{ $application->email }}</p>
                                @if($application->phone)
                                    <p class="text-xs text-gray-500">{{ $application->phone }}</p>
                                @endif
                                <p class="text-xs text-gray-400 mt-1">{{ $application->application_code }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <p class="font-medium">{{ $application->position?->title ?: 'Position removed' }}</p>
                                @if($application->years_experience !== null)
                                    <p class="text-xs text-gray-500">{{ $application->years_experience }} year(s) experience</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ $resumeUrl }}" target="_blank" class="inline-flex items-center text-emerald-600 hover:text-emerald-800">
                                    <i class="fas fa-file-arrow-down mr-2"></i>
                                    View Resume
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded {{ $application->status === 'new' ? 'bg-emerald-100 text-emerald-700' : ($application->status === 'reviewing' ? 'bg-blue-100 text-blue-700' : ($application->status === 'shortlisted' ? 'bg-amber-100 text-amber-700' : ($application->status === 'hired' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700'))) }}">
                                    {{ \Illuminate\Support\Str::of($application->status)->replace('-', ' ')->title() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $application->created_at->format('M d, Y') }}
                                <p class="text-xs text-gray-500">{{ $application->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center rounded-lg border border-gray-200 bg-gray-50 px-2 py-1 gap-2">
                                    <select wire:change="setStatus({{ $application->id }}, $event.target.value)"
                                        class="text-xs border border-gray-300 rounded px-2 py-1 bg-white">
                                        <option value="{{ $application->status }}">{{ \Illuminate\Support\Str::of($application->status)->title() }}</option>
                                        @foreach(['new', 'reviewing', 'shortlisted', 'rejected', 'hired', 'archived'] as $status)
                                            @if($status !== $application->status)
                                                <option value="{{ $status }}">{{ \Illuminate\Support\Str::of($status)->title() }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <button wire:click="openNotesModal({{ $application->id }})" class="text-blue-600 hover:text-blue-800" title="Notes">
                                        <i class="fas fa-note-sticky"></i>
                                    </button>
                                    <button wire:click="deleteApplication({{ $application->id }})" class="text-red-600 hover:text-red-800" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">No applications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $applications->links() }}
        </div>
    </div>

    @if($showNotesModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeNotesModal"></div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <h3 class="text-lg font-semibold text-gray-900">Application Notes</h3>
                        <textarea rows="8" wire:model="admin_notes"
                            class="mt-4 w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            placeholder="Add private notes about this applicant..."></textarea>
                        @error('admin_notes') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="saveNotes" type="button"
                            class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent px-4 py-2 bg-emerald-600 text-white font-semibold hover:bg-emerald-700 sm:ml-3">
                            Save Notes
                        </button>
                        <button wire:click="closeNotesModal" type="button"
                            class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 bg-white text-gray-700 font-semibold hover:bg-gray-50">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
