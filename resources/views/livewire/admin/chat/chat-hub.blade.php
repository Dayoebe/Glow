<div class="space-y-5" wire:poll.12s>
    @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>
    @endif

    <section class="relative overflow-hidden rounded-3xl bg-[#082f36] p-5 text-white shadow-xl sm:p-7">
        <div class="absolute -right-16 -top-20 h-64 w-64 rounded-full bg-emerald-400/10"></div>
        <div class="absolute -bottom-20 left-1/3 h-44 w-44 rounded-full bg-orange-400/10"></div>
        <div class="relative flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs font-bold uppercase tracking-[.18em] text-emerald-200">
                    <span class="h-2 w-2 animate-pulse rounded-full bg-emerald-400"></span> Team workspace
                </div>
                <h1 class="text-3xl font-black tracking-tight sm:text-4xl">Stay in sync. Move faster.</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-300">Private staff conversations and targeted station-wide announcements, saved securely with a complete date and time history.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <div class="rounded-2xl border border-white/10 bg-white/[.07] px-4 py-3">
                    <p class="text-2xl font-black">{{ $totalUnread }}</p><p class="text-xs text-slate-300">Unread messages</p>
                </div>
                <button type="button" wire:click="$set('showBroadcastComposer', true)" class="inline-flex items-center gap-2 rounded-2xl bg-[#ed5a1f] px-5 py-3 text-sm font-extrabold shadow-lg transition hover:bg-[#d94d16]">
                    <i class="fas fa-bullhorn"></i> New broadcast
                </button>
            </div>
        </div>
    </section>

    <section class="grid min-h-[680px] overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-200/40 lg:grid-cols-[350px_minmax(0,1fr)]">
        <aside class="border-b border-slate-200 bg-slate-50/80 lg:border-b-0 lg:border-r">
            <div class="border-b border-slate-200 p-4">
                <label class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Start a private chat</label>
                <div class="mt-2 flex gap-2">
                    <select wire:model="directRecipientId" class="min-w-0 flex-1 rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Select a staff member</option>
                        @foreach ($staff as $person)<option value="{{ $person->id }}">{{ $person->name }} · {{ $person->role_label }}</option>@endforeach
                    </select>
                    <button wire:click="startDirect" title="Start chat" class="h-11 w-11 shrink-0 rounded-xl bg-emerald-600 text-white transition hover:bg-emerald-700"><i class="fas fa-arrow-right"></i></button>
                </div>
                @error('directRecipientId')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-3 p-4">
                <div class="relative"><i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i><input type="search" wire:model.live.debounce.250ms="search" placeholder="Search conversations" class="w-full rounded-xl border-slate-300 py-2.5 pl-9 text-sm focus:border-emerald-500 focus:ring-emerald-500"></div>
                <div class="flex gap-1 rounded-xl bg-slate-200/70 p-1">
                    @foreach (['all' => 'All', 'unread' => 'Unread', 'broadcasts' => 'Broadcasts'] as $value => $label)
                        <button wire:click="$set('filter', '{{ $value }}')" class="flex-1 rounded-lg px-2 py-2 text-xs font-bold transition {{ $filter === $value ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">{{ $label }}</button>
                    @endforeach
                </div>
            </div>

            <div class="max-h-[470px] space-y-1 overflow-y-auto px-2 pb-4 lg:max-h-[535px]">
                @forelse ($conversations as $conversation)
                    @php $other = $conversation->participants->firstWhere('id', '!=', auth()->id()); @endphp
                    <button wire:key="conversation-{{ $conversation->id }}" wire:click="selectConversation({{ $conversation->id }})" class="group flex w-full gap-3 rounded-2xl p-3 text-left transition {{ $selectedConversationId === $conversation->id ? 'bg-white shadow-md ring-1 ring-slate-200' : 'hover:bg-white/70' }}">
                        <span class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ $conversation->type === 'broadcast' ? 'bg-orange-100 text-orange-600' : 'bg-emerald-100 text-emerald-700' }} font-black">
                            @if($conversation->type === 'broadcast')<i class="fas fa-bullhorn"></i>@else{{ strtoupper(substr($other?->name ?? '?', 0, 1)) }}@endif
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="flex items-center justify-between gap-2"><span class="truncate text-sm font-extrabold text-slate-900">{{ $this->conversationName($conversation) }}</span><span class="shrink-0 text-[10px] text-slate-400">{{ $conversation->last_message_at?->diffForHumans(null, true, true) }}</span></span>
                            <span class="mt-1 flex items-center gap-2"><span class="min-w-0 flex-1 truncate text-xs text-slate-500">{{ $conversation->latestMessage?->sender_id === auth()->id() ? 'You: ' : '' }}{{ $conversation->latestMessage?->body ?? 'Conversation started' }}</span>
                                @if($conversation->unread_count)<span class="flex h-5 min-w-5 items-center justify-center rounded-full bg-[#ed5a1f] px-1 text-[10px] font-black text-white">{{ $conversation->unread_count }}</span>@endif
                            </span>
                            @if($conversation->is_pinned || $conversation->priority !== 'normal')<span class="mt-1.5 flex gap-2 text-[10px] font-bold uppercase tracking-wide {{ $conversation->priority === 'urgent' ? 'text-red-600' : 'text-amber-600' }}">@if($conversation->is_pinned)<span><i class="fas fa-thumbtack"></i> Pinned</span>@endif @if($conversation->priority !== 'normal')<span>{{ $conversation->priority }}</span>@endif</span>@endif
                        </span>
                    </button>
                @empty
                    <div class="px-6 py-14 text-center"><i class="far fa-comments text-3xl text-slate-300"></i><p class="mt-3 text-sm font-bold text-slate-700">No conversations yet</p><p class="mt-1 text-xs text-slate-500">Select a colleague above to say hello.</p></div>
                @endforelse
            </div>
        </aside>

        <main class="flex min-w-0 flex-col bg-white">
            @if ($selected)
                @php $other = $selected->participants->firstWhere('id', '!=', auth()->id()); $muted = (bool) $selected->participants->firstWhere('id', auth()->id())?->pivot?->is_muted; @endphp
                <header class="flex items-center justify-between gap-3 border-b border-slate-200 px-4 py-4 sm:px-6">
                    <div class="flex min-w-0 items-center gap-3"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl {{ $selected->type === 'broadcast' ? 'bg-orange-100 text-orange-600' : 'bg-emerald-100 text-emerald-700' }} font-black">@if($selected->type === 'broadcast')<i class="fas fa-bullhorn"></i>@else{{ strtoupper(substr($other?->name ?? '?', 0, 1)) }}@endif</span><div class="min-w-0"><h2 class="truncate text-base font-black text-slate-900">{{ $this->conversationName($selected) }}</h2><p class="truncate text-xs text-slate-500">{{ $selected->type === 'broadcast' ? $selected->participants->count().' recipients · '.$selected->priority.' priority' : ($other?->role_label ?? 'Staff member') }}</p></div></div>
                    <button wire:click="toggleMute" title="{{ $muted ? 'Unmute' : 'Mute' }} conversation" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition hover:bg-slate-50"><i class="fas {{ $muted ? 'fa-bell-slash' : 'fa-bell' }}"></i></button>
                </header>

                <div id="chat-messages" class="flex-1 space-y-5 overflow-y-auto bg-[radial-gradient(circle_at_top_right,_rgba(16,185,129,.06),_transparent_30%)] p-4 sm:p-6">
                    <div class="mx-auto flex max-w-sm items-center gap-3 text-[10px] font-bold uppercase tracking-widest text-slate-400"><span class="h-px flex-1 bg-slate-200"></span>Conversation history<span class="h-px flex-1 bg-slate-200"></span></div>
                    @foreach ($selected->messages as $message)
                        @php $mine = $message->sender_id === auth()->id(); @endphp
                        <div wire:key="message-{{ $message->id }}" class="flex {{ $mine ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[86%] sm:max-w-[72%]">
                                @unless($mine)<p class="mb-1 ml-1 text-[11px] font-extrabold text-slate-500">{{ $message->sender->name }}</p>@endunless
                                <div class="rounded-2xl px-4 py-3 text-sm leading-6 shadow-sm {{ $mine ? 'rounded-br-md bg-emerald-600 text-white' : 'rounded-bl-md border border-slate-200 bg-white text-slate-800' }}"><p class="whitespace-pre-wrap break-words">{{ $message->body }}</p></div>
                                <p class="mt-1 px-1 text-[10px] {{ $mine ? 'text-right' : '' }} text-slate-400" title="{{ $message->created_at->format('l, F j, Y \a\t g:i A') }}">{{ $message->created_at->format('M j, Y · g:i A') }} @if($message->edited_at)· edited @endif</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <form wire:submit="sendMessage" class="border-t border-slate-200 bg-white p-4 sm:p-5">
                    <div class="flex items-end gap-3"><div class="min-w-0 flex-1"><textarea wire:model="messageBody" rows="2" maxlength="5000" placeholder="Write a message…" class="w-full resize-none rounded-2xl border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>@error('messageBody')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror</div><button class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-lg shadow-emerald-200 transition hover:-translate-y-0.5 hover:bg-emerald-700" title="Send message"><i class="fas fa-paper-plane"></i></button></div>
                    <p class="mt-2 text-[10px] text-slate-400"><i class="fas fa-lock mr-1"></i>Visible only to people in this conversation. Messages are retained with timestamps.</p>
                </form>
            @else
                <div class="flex flex-1 items-center justify-center p-8 text-center"><div><span class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-emerald-50 text-3xl text-emerald-600"><i class="far fa-comments"></i></span><h2 class="mt-5 text-xl font-black text-slate-900">Your team conversations live here</h2><p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-slate-500">Choose an active colleague for a private chat, or create a broadcast for everyone or a hand-picked group.</p></div></div>
            @endif
        </main>
    </section>

    @if ($showBroadcastComposer)
        <div class="fixed inset-0 z-50 flex items-end justify-center bg-slate-950/60 p-0 backdrop-blur-sm sm:items-center sm:p-5" wire:click.self="resetBroadcastForm">
            <div class="max-h-[95vh] w-full max-w-2xl overflow-y-auto rounded-t-3xl bg-white shadow-2xl sm:rounded-3xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-5 sm:px-7"><div><p class="text-xs font-black uppercase tracking-[.16em] text-orange-600">Broadcast centre</p><h2 class="mt-1 text-2xl font-black text-slate-900">Send an announcement</h2></div><button wire:click="resetBroadcastForm" class="h-10 w-10 rounded-xl bg-slate-100 text-slate-500"><i class="fas fa-times"></i></button></div>
                <form wire:submit="createBroadcast" class="space-y-5 p-5 sm:p-7">
                    <div><label class="text-xs font-extrabold uppercase tracking-wide text-slate-600">Subject</label><input wire:model="broadcastTitle" maxlength="120" placeholder="e.g. Production meeting moved to 3 PM" class="mt-2 w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">@error('broadcastTitle')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <div><label class="text-xs font-extrabold uppercase tracking-wide text-slate-600">Message</label><textarea wire:model="broadcastBody" rows="5" maxlength="5000" placeholder="Share the details your team needs…" class="mt-2 w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500"></textarea>@error('broadcastBody')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <div class="grid gap-4 sm:grid-cols-2"><div><label class="text-xs font-extrabold uppercase tracking-wide text-slate-600">Priority</label><select wire:model="broadcastPriority" class="mt-2 w-full rounded-xl border-slate-300"><option value="normal">Normal</option><option value="important">Important</option><option value="urgent">Urgent</option></select></div><label class="mt-6 flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 p-3"><input type="checkbox" wire:model="broadcastPinned" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"><span><strong class="block text-sm text-slate-800">Pin announcement</strong><span class="text-xs text-slate-500">Keep it above recent chats</span></span></label></div>
                    <div class="rounded-2xl border border-slate-200 p-4"><p class="text-xs font-extrabold uppercase tracking-wide text-slate-600">Recipients</p><div class="mt-3 grid grid-cols-2 gap-2"><label class="rounded-xl border p-3 text-sm font-bold {{ $broadcastEveryone ? 'border-emerald-500 bg-emerald-50 text-emerald-800' : 'border-slate-200' }}"><input type="radio" wire:model.live="broadcastEveryone" value="1" class="mr-2 text-emerald-600">Everyone</label><label class="rounded-xl border p-3 text-sm font-bold {{ !$broadcastEveryone ? 'border-emerald-500 bg-emerald-50 text-emerald-800' : 'border-slate-200' }}"><input type="radio" wire:model.live="broadcastEveryone" value="0" class="mr-2 text-emerald-600">Selected staff</label></div>
                        @if(!$broadcastEveryone)<div class="mt-3 max-h-44 space-y-1 overflow-y-auto rounded-xl bg-slate-50 p-2">@foreach($staff as $person)<label class="flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-white"><input type="checkbox" wire:model="broadcastRecipientIds" value="{{ $person->id }}" class="rounded text-emerald-600"><span class="text-sm font-semibold text-slate-700">{{ $person->name }}</span><span class="ml-auto text-xs text-slate-400">{{ $person->role_label }}</span></label>@endforeach</div>@endif
                        @error('broadcastRecipientIds')<p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end"><button type="button" wire:click="resetBroadcastForm" class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-bold text-slate-600">Cancel</button><button class="rounded-xl bg-[#ed5a1f] px-6 py-3 text-sm font-extrabold text-white shadow-lg hover:bg-[#d94d16]"><i class="fas fa-paper-plane mr-2"></i>Send broadcast</button></div>
                </form>
            </div>
        </div>
    @endif
</div>

@script
<script>
    const scrollChat = () => { const el = document.getElementById('chat-messages'); if (el) el.scrollTop = el.scrollHeight; };
    scrollChat();
    $wire.on('message-sent', () => requestAnimationFrame(scrollChat));
    $wire.on('chat-selected', () => requestAnimationFrame(scrollChat));
</script>
@endscript
