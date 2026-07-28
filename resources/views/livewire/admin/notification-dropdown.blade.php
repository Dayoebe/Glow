<div wire:poll.15s="loadNotifications" x-data="{ open: false }" class="relative">
    <button type="button" @click="open = !open"
        class="relative flex h-11 w-11 items-center justify-center rounded-2xl text-gray-600 transition-colors duration-150 hover:bg-gray-100 hover:text-gray-900"
        :aria-expanded="open" aria-label="Open notifications">
        <i class="fas fa-bell text-xl"></i>
        @if($unreadCount > 0)
            <span class="absolute right-0 top-0 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-[10px] font-bold leading-none text-white">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div x-show="open" x-cloak @click.away="open = false" x-transition
        class="absolute right-0 z-[80] mt-2 w-[min(20rem,calc(100vw-2rem))] overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xl">
        <div class="flex items-center justify-between border-b border-gray-200 p-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Contact notifications</h3>
                <p class="mt-0.5 text-xs text-gray-500">
                    {{ $unreadCount === 1 ? '1 unread message' : "{$unreadCount} unread messages" }}
                </p>
            </div>
            @if($unreadCount > 0)
                <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-700">{{ $unreadCount }}</span>
            @endif
        </div>

        <div class="max-h-96 divide-y divide-gray-100 overflow-y-auto">
            @forelse($messages as $message)
                <a href="{{ route('admin.messages.inbox', ['message' => $message['id']]) }}"
                    class="flex items-start gap-3 p-4 transition-colors hover:bg-emerald-50">
                    <span class="mt-1 flex h-9 w-9 flex-none items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                        <i class="fas fa-envelope text-sm"></i>
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="block truncate text-sm font-semibold text-gray-900">{{ $message['subject'] }}</span>
                        <span class="mt-0.5 block truncate text-xs text-gray-600">From {{ $message['name'] }}</span>
                        <span class="mt-1 block text-xs text-gray-400">{{ $message['received'] }}</span>
                    </span>
                    <span class="mt-2 h-2 w-2 flex-none rounded-full bg-red-500"></span>
                </a>
            @empty
                <div class="px-5 py-8 text-center">
                    <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                        <i class="fas fa-check"></i>
                    </span>
                    <p class="mt-3 text-sm font-medium text-gray-700">You're all caught up</p>
                    <p class="mt-1 text-xs text-gray-500">There are no unread contact messages.</p>
                </div>
            @endforelse
        </div>

        <div class="border-t border-gray-200 bg-gray-50 p-3">
            <a href="{{ route('admin.messages.inbox') }}"
                class="block rounded-lg px-3 py-2 text-center text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100">
                View Contact Inbox
            </a>
        </div>
    </div>
</div>
