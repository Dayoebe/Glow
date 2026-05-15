<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Content Approvers</h3>
        <p class="text-sm text-gray-500">
            Choose staff members who can approve, flag, or reject content submissions.
        </p>

        <div class="mt-6 rounded-xl border border-gray-200 bg-gray-50 p-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h4 class="text-sm font-semibold text-gray-900">News approval mail notification</h4>
                    <p class="mt-1 text-sm text-gray-500">
                        Send email to the news author when an approver approves, flags, or rejects a news article.
                    </p>
                </div>

                <label class="inline-flex cursor-pointer items-center gap-3">
                    <input type="checkbox" wire:model="news_approval_mail_enabled" class="sr-only peer">
                    <span class="relative h-7 w-12 rounded-full bg-gray-300 transition-colors after:absolute after:left-1 after:top-1 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-transform after:content-[''] peer-checked:bg-emerald-600 peer-checked:after:translate-x-5"></span>
                    <span class="text-sm font-semibold {{ $news_approval_mail_enabled ? 'text-emerald-700' : 'text-gray-500' }}">
                        {{ $news_approval_mail_enabled ? 'On' : 'Off' }}
                    </span>
                </label>
            </div>
        </div>

        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Approver List</label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($staffMembers as $member)
                    <label class="flex items-center gap-3 px-3 py-2 border border-gray-200 rounded-lg bg-white hover:bg-emerald-50">
                        <input type="checkbox" wire:model="approver_ids" value="{{ $member->id }}"
                            class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-gray-700">{{ $member->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="mt-6 bg-emerald-50 border border-emerald-100 rounded-lg p-4">
            <p class="text-sm font-semibold text-emerald-900 mb-2">Selected Approvers</p>
            @php
                $selected = $staffMembers->filter(fn ($member) => in_array($member->id, $approver_ids ?? [], true));
            @endphp
            @if($selected->isEmpty())
                <p class="text-sm text-emerald-700">No approvers selected yet.</p>
            @else
                <div class="flex flex-wrap gap-2">
                    @foreach($selected as $member)
                        <span class="px-3 py-1 text-xs font-semibold bg-white border border-emerald-200 text-emerald-700 rounded-full">
                            {{ $member->name }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="mt-6 flex justify-end">
            <button wire:click="save"
                class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg">
                Save Settings
            </button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 z-50 bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg flash-auto-dismiss">
            {{ session('success') }}
        </div>
    @endif
</div>
