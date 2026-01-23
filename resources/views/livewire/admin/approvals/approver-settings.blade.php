<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Content Approvers</h3>
        <p class="text-sm text-gray-500">
            Choose staff members who can approve, flag, or reject content submissions.
        </p>

        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Approver List</label>
            <select wire:model="approver_ids" multiple
                class="w-full min-h-[12rem] px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @foreach($staffMembers as $member)
                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-2">Hold Ctrl (Windows) or Cmd (Mac) to select multiple approvers.</p>
        </div>

        <div class="mt-6 flex justify-end">
            <button wire:click="save"
                class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg">
                Save Approvers
            </button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 z-50 bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif
</div>
