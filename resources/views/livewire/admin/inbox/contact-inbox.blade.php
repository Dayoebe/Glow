<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="relative flex-1 max-w-md">
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Search messages..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            @forelse($messages as $message)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            <h3 class="text-sm font-semibold text-gray-900">{{ $message->subject }}</h3>
                            @if(!$message->is_read)
                                <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">New</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500">{{ $message->name }} • {{ $message->email }}</p>
                        <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $message->message }}</p>
                    </div>
                    <div class="flex flex-col items-end space-y-2 text-xs">
                        <span class="text-gray-400">{{ $message->created_at->diffForHumans() }}</span>
                        <div class="flex items-center space-x-2">
                            <button wire:click="openMessage({{ $message->id }})" class="text-emerald-600 hover:text-emerald-800">View</button>
                            @if($message->is_read)
                                <button wire:click="markUnread({{ $message->id }})" class="text-blue-600 hover:text-blue-800">Unread</button>
                            @endif
                            <button wire:click="deleteMessage({{ $message->id }})" onclick="return confirm('Delete this message?')"
                                class="text-red-600 hover:text-red-800">Delete</button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-gray-200 p-10 text-center">
                    <p class="text-gray-500">No messages yet.</p>
                </div>
            @endforelse

            <div class="mt-6">
                {{ $messages->links() }}
            </div>
        </div>

        <aside class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Inbox Tips</h3>
            <ul class="text-sm text-gray-600 space-y-3">
                <li>Click any message to read the full content.</li>
                <li>Unread messages are highlighted with a badge.</li>
                <li>Use search to filter by subject, name, or email.</li>
            </ul>
        </aside>
    </div>

    @if($showMessageModal && $selectedMessage)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeMessage"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-6 py-5">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ $selectedMessage->subject }}</h3>
                            <button wire:click="closeMessage" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="space-y-2 text-sm text-gray-600">
                            <p><strong>Name:</strong> {{ $selectedMessage->name }}</p>
                            <p><strong>Email:</strong> {{ $selectedMessage->email }}</p>
                            <p><strong>Phone:</strong> {{ $selectedMessage->phone ?? 'N/A' }}</p>
                            <p><strong>Inquiry:</strong> {{ $selectedMessage->inquiry_type ?? 'General' }}</p>
                            <p><strong>Status:</strong> {{ $selectedMessage->status ?? 'new' }}</p>
                            <p><strong>Received:</strong> {{ $selectedMessage->created_at->format('M d, Y g:i A') }}</p>
                        </div>
                        <div class="mt-4 text-sm text-gray-700 whitespace-pre-line">{{ $selectedMessage->message }}</div>

                        <div class="mt-6 border-t pt-4">
                            <h4 class="text-sm font-semibold text-gray-900 mb-2">Admin Notes</h4>
                            <textarea rows="3" wire:model="adminNotes"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                            <div class="mt-2 flex items-center space-x-3">
                                <label class="text-xs text-gray-600">Status</label>
                                <select wire:model="status" class="px-3 py-1 border border-gray-300 rounded-lg text-xs">
                                    <option value="new">New</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="replied">Replied</option>
                                    <option value="closed">Closed</option>
                                </select>
                                <button wire:click="saveNotes"
                                    class="px-3 py-1 bg-emerald-600 text-white text-xs rounded-lg">Save Notes</button>
                            </div>
                        </div>

                        <div class="mt-6 border-t pt-4">
                            <h4 class="text-sm font-semibold text-gray-900 mb-2">Reply</h4>
                            <textarea rows="4" wire:model="replyMessage"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                placeholder="Type your reply..."></textarea>
                            <div class="mt-2 flex justify-end">
                                <button wire:click="sendReply"
                                    class="px-4 py-2 bg-emerald-600 text-white text-sm rounded-lg">Send Reply</button>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-3">
                        <button wire:click="closeMessage" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 z-50 bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg flash-auto-dismiss">
            {{ session('success') }}
        </div>
    @endif
</div>
