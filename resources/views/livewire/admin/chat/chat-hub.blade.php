<div class="space-y-3 sm:space-y-5" wire:poll.12s="refreshChat"
    x-data="{
        mobileConversation: false,
        isMobile: window.matchMedia('(max-width: 767px)').matches,
        notificationVisible: false,
        audioReady: false,
        audioContext: null,
        unlockAudio() {
            if (this.audioReady) return;
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            this.audioContext = this.audioContext || new AudioContext();
            if (this.audioContext.state === 'suspended') this.audioContext.resume();
            this.audioReady = true;
        },
        playNotification() {
            this.unlockAudio();
            if (!this.audioContext || this.audioContext.state !== 'running') return;
            [0, 0.16].forEach((delay, index) => {
                const oscillator = this.audioContext.createOscillator();
                const gain = this.audioContext.createGain();
                oscillator.type = 'sine';
                oscillator.frequency.value = index ? 880 : 660;
                gain.gain.setValueAtTime(0.0001, this.audioContext.currentTime + delay);
                gain.gain.exponentialRampToValueAtTime(0.12, this.audioContext.currentTime + delay + 0.015);
                gain.gain.exponentialRampToValueAtTime(0.0001, this.audioContext.currentTime + delay + 0.13);
                oscillator.connect(gain).connect(this.audioContext.destination);
                oscillator.start(this.audioContext.currentTime + delay);
                oscillator.stop(this.audioContext.currentTime + delay + 0.14);
            });
        },
        notifyIncoming() {
            this.notificationVisible = true;
            this.playNotification();
            window.clearTimeout(this.notificationTimer);
            this.notificationTimer = window.setTimeout(() => this.notificationVisible = false, 4500);
        }
    }"
    x-init="
        const media = window.matchMedia('(max-width: 767px)');
        const updateDevice = event => isMobile = event.matches;
        media.addEventListener?.('change', updateDevice);
        const unlock = () => unlockAudio();
        window.addEventListener('pointerdown', unlock, { once: true });
        window.addEventListener('keydown', unlock, { once: true });
    "
    x-on:chat-selected.window="mobileConversation = true"
    x-on:chat-notification.window="notifyIncoming()">
    <div x-cloak x-show="notificationVisible" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-y-3 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed left-3 right-3 top-[calc(env(safe-area-inset-top)+5.5rem)] z-[60] mx-auto max-w-sm sm:left-auto sm:right-6 sm:top-20">
        <button type="button" @click="notificationVisible = false; mobileConversation = false" class="flex w-full items-center gap-3 rounded-2xl border border-white/20 bg-[#082f36] p-3 text-left text-white shadow-2xl ring-1 ring-black/10">
            <span class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#25d366] text-lg"><i class="fas fa-comment-dots"></i><span class="absolute -right-0.5 -top-0.5 h-3 w-3 rounded-full border-2 border-[#082f36] bg-orange-400"></span></span>
            <span class="min-w-0 flex-1"><strong class="block text-sm font-black">New staff message</strong><span class="mt-0.5 block truncate text-xs text-slate-300">You have an unread message waiting for a reply.</span></span>
            <i class="fas fa-chevron-right text-xs text-slate-400"></i>
        </button>
    </div>
    @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>
    @endif

    <section class="relative hidden overflow-hidden rounded-3xl bg-[#082f36] p-7 text-white shadow-xl sm:block">
        <div class="absolute -right-16 -top-20 h-64 w-64 rounded-full bg-emerald-400/10"></div>
        <div class="absolute -bottom-20 left-1/3 h-44 w-44 rounded-full bg-orange-400/10"></div>
        <div class="relative flex items-center justify-between gap-3 sm:gap-5 lg:items-end">
            <div>
                <div class="mb-1.5 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-2.5 py-1 text-[9px] font-bold uppercase tracking-[.16em] text-emerald-200 sm:mb-3 sm:px-3 sm:text-xs sm:tracking-[.18em]">
                    <span class="h-2 w-2 animate-pulse rounded-full bg-emerald-400"></span> Team workspace
                </div>
                <h1 class="text-xl font-black tracking-tight sm:text-4xl">Stay in sync. Move faster.</h1>
                <p class="mt-1 hidden max-w-2xl text-sm leading-6 text-slate-300 sm:block">Private staff conversations and targeted station-wide announcements, saved securely with a complete date and time history.</p>
            </div>
            <div class="flex shrink-0 items-center gap-2 sm:gap-3">
                <div class="hidden rounded-2xl border border-white/10 bg-white/[.07] px-4 py-3 sm:block">
                    <p class="text-2xl font-black">{{ $totalUnread }}</p><p class="text-xs text-slate-300">Unread messages</p>
                </div>
                <button type="button" wire:click="$set('showBroadcastComposer', true)" class="inline-flex h-11 items-center gap-2 rounded-xl bg-[#ed5a1f] px-3.5 text-xs font-extrabold shadow-lg transition hover:bg-[#d94d16] sm:h-auto sm:rounded-2xl sm:px-5 sm:py-3 sm:text-sm">
                    <i class="fas fa-bullhorn"></i> <span class="hidden xs:inline sm:inline">New </span>broadcast
                </button>
            </div>
        </div>
    </section>

    <section class="h-[calc(100dvh-7.5rem)] min-h-[560px] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl shadow-slate-200/40 sm:h-[720px] sm:rounded-3xl lg:grid lg:grid-cols-[350px_minmax(0,1fr)]">
        <aside :class="mobileConversation ? 'hidden lg:flex' : 'flex'" class="h-full min-h-0 flex-col bg-white lg:border-r">
            <div class="flex shrink-0 items-center justify-between bg-[#008069] px-4 py-3 text-white sm:hidden">
                <div><div class="flex items-center gap-2"><h1 class="text-xl font-black">Staff Chat</h1>@if($totalUnread)<span class="flex h-5 min-w-5 items-center justify-center rounded-full bg-white px-1 text-[10px] font-black text-[#008069]">{{ $totalUnread }}</span>@endif</div><p class="mt-0.5 text-[11px] text-emerald-100">Private messages and team broadcasts</p></div>
                <button type="button" wire:click="$set('showBroadcastComposer', true)" aria-label="New broadcast" class="flex h-10 w-10 items-center justify-center rounded-full bg-white/15 transition active:bg-white/25"><i class="fas fa-bullhorn"></i></button>
            </div>
            <div class="border-b border-slate-200 bg-[#f0f2f5] p-3 sm:p-4">
                <label class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Start a private chat</label>
                <div class="mt-2 flex gap-2">
                    <select wire:model="directRecipientId" aria-label="Select a staff member" class="h-11 min-w-0 flex-1 rounded-xl border-slate-300 py-2 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">Select a staff member</option>
                        @foreach ($staff as $person)<option value="{{ $person->id }}">{{ $person->name }} · {{ $person->role_label }}</option>@endforeach
                    </select>
                    <button wire:click="startDirect" title="Start chat" class="h-11 w-11 shrink-0 rounded-xl bg-emerald-600 text-white transition hover:bg-emerald-700"><i class="fas fa-arrow-right"></i></button>
                </div>
                @error('directRecipientId')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-3 border-b border-slate-100 p-3 sm:p-4">
                <div class="relative"><i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i><input type="search" wire:model.live.debounce.250ms="search" placeholder="Search or start a new chat" class="w-full rounded-xl border-0 bg-[#f0f2f5] py-2.5 pl-9 text-sm focus:ring-2 focus:ring-[#00a884]/30"></div>
                <div class="flex gap-1 rounded-xl bg-[#f0f2f5] p-1">
                    @foreach (['all' => 'All', 'unread' => 'Unread', 'broadcasts' => 'Broadcasts'] as $value => $label)
                        <button wire:click="$set('filter', '{{ $value }}')" class="flex-1 rounded-lg px-2 py-2 text-xs font-bold transition {{ $filter === $value ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">{{ $label }}</button>
                    @endforeach
                </div>
            </div>

            <div class="min-h-0 flex-1 space-y-1 overflow-y-auto overscroll-contain px-2 pb-[max(1rem,env(safe-area-inset-bottom))]">
                @forelse ($conversations as $conversation)
                    @php $other = $conversation->participants->firstWhere('id', '!=', auth()->id()); @endphp
                    <button wire:key="conversation-{{ $conversation->id }}" wire:click="selectConversation({{ $conversation->id }})" @click="mobileConversation = true" class="group flex w-full gap-3 rounded-xl p-3 text-left transition {{ $selectedConversationId === $conversation->id ? 'bg-[#f0f2f5]' : 'hover:bg-[#f5f6f6]' }}">
                        <span class="relative flex h-12 w-12 shrink-0 items-center justify-center rounded-full {{ $conversation->type === 'broadcast' ? 'bg-[#00a884] text-white' : 'bg-slate-200 text-slate-600' }} font-black">
                            @if($conversation->type === 'broadcast')<i class="fas fa-bullhorn"></i>@else{{ strtoupper(substr($other?->name ?? '?', 0, 1)) }}@endif
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="flex items-center justify-between gap-2"><span class="truncate text-sm font-extrabold text-slate-900">{{ $this->conversationName($conversation) }}</span><span class="shrink-0 text-[10px] text-slate-400">{{ $conversation->last_message_at?->diffForHumans(null, true, true) }}</span></span>
                            <span class="mt-1 flex items-center gap-2"><span class="min-w-0 flex-1 truncate text-xs text-slate-500">{{ $conversation->latestMessage?->sender_id === auth()->id() ? 'You: ' : '' }}{{ $conversation->latestMessage?->body ?? 'Conversation started' }}</span>
                                @if($conversation->unread_count)<span class="flex h-5 min-w-5 items-center justify-center rounded-full bg-[#25d366] px-1 text-[10px] font-black text-white">{{ $conversation->unread_count }}</span>@endif
                            </span>
                            @if($conversation->is_pinned || $conversation->priority !== 'normal')<span class="mt-1.5 flex gap-2 text-[10px] font-bold uppercase tracking-wide {{ $conversation->priority === 'urgent' ? 'text-red-600' : 'text-amber-600' }}">@if($conversation->is_pinned)<span><i class="fas fa-thumbtack"></i> Pinned</span>@endif @if($conversation->priority !== 'normal')<span>{{ $conversation->priority }}</span>@endif</span>@endif
                        </span>
                    </button>
                @empty
                    <div class="px-6 py-14 text-center"><i class="far fa-comments text-3xl text-slate-300"></i><p class="mt-3 text-sm font-bold text-slate-700">No conversations yet</p><p class="mt-1 text-xs text-slate-500">Select a colleague above to say hello.</p></div>
                @endforelse
            </div>
        </aside>

        <main :class="mobileConversation ? 'flex' : 'hidden lg:flex'" class="h-full min-h-0 min-w-0 flex-col bg-white">
            @if ($selected)
                @php $other = $selected->participants->firstWhere('id', '!=', auth()->id()); $muted = (bool) $selected->participants->firstWhere('id', auth()->id())?->pivot?->is_muted; @endphp
                <header class="flex shrink-0 items-center justify-between gap-2 border-b border-black/10 bg-[#008069] px-2 py-2.5 text-white sm:gap-3 sm:bg-[#f0f2f5] sm:px-5 sm:py-3 sm:text-slate-900">
                    <div class="flex min-w-0 items-center gap-2 sm:gap-3"><button type="button" @click="mobileConversation = false" aria-label="Back to conversations" class="flex h-10 w-9 shrink-0 items-center justify-center text-white lg:hidden"><i class="fas fa-arrow-left"></i></button><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $selected->type === 'broadcast' ? 'bg-[#00a884] text-white' : 'bg-white/20 text-white sm:bg-slate-300 sm:text-slate-700' }} font-black sm:h-11 sm:w-11">@if($selected->type === 'broadcast')<i class="fas fa-bullhorn"></i>@else{{ strtoupper(substr($other?->name ?? '?', 0, 1)) }}@endif</span><div class="min-w-0"><h2 class="truncate text-sm font-bold sm:text-base">{{ $this->conversationName($selected) }}</h2><p class="truncate text-[11px] text-emerald-100 sm:text-xs sm:text-slate-500">{{ $selected->type === 'broadcast' ? $selected->participants->count().' recipients · '.$selected->priority.' priority' : ($other?->role_label ?? 'Staff member') }}</p></div></div>
                    <button wire:click="toggleMute" title="{{ $muted ? 'Unmute' : 'Mute' }} conversation" class="flex h-10 w-10 items-center justify-center rounded-full text-white/90 transition hover:bg-white/10 sm:text-slate-500 sm:hover:bg-slate-200"><i class="fas {{ $muted ? 'fa-bell-slash' : 'fa-bell' }}"></i></button>
                </header>

                <div id="chat-messages" class="min-h-0 flex-1 space-y-2 overflow-y-auto overscroll-contain bg-[#efeae2] bg-[radial-gradient(rgba(11,47,58,.055)_1px,transparent_1px)] bg-[size:18px_18px] p-3 sm:space-y-3 sm:p-6">
                    @php $lastMessageDate = null; @endphp
                    @foreach ($selected->messages as $message)
                        @if($lastMessageDate !== $message->created_at->toDateString())
                            <div class="sticky top-1 z-10 flex justify-center py-1"><span class="rounded-lg bg-white/90 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wide text-slate-500 shadow-sm">{{ $message->created_at->isToday() ? 'Today' : ($message->created_at->isYesterday() ? 'Yesterday' : $message->created_at->format('F j, Y')) }}</span></div>
                            @php $lastMessageDate = $message->created_at->toDateString(); @endphp
                        @endif
                        @php $mine = $message->sender_id === auth()->id(); @endphp
                        <div wire:key="message-{{ $message->id }}" class="flex {{ $mine ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[88%] sm:max-w-[72%]">
                                @unless($mine)<p class="mb-1 ml-1 text-[11px] font-extrabold text-slate-500">{{ $message->sender->name }}</p>@endunless
                                <div class="rounded-lg px-2.5 py-1.5 text-[13.5px] leading-5 text-slate-900 shadow-sm sm:px-3 sm:py-2 sm:text-sm {{ $mine ? 'rounded-tr-none bg-[#d9fdd3]' : 'rounded-tl-none bg-white' }}"><p class="whitespace-pre-wrap break-words">{{ $message->body }}</p><p class="mt-0.5 text-right text-[9.5px] leading-none text-slate-500" title="{{ $message->created_at->format('l, F j, Y \a\t g:i A') }}">{{ $message->created_at->format('g:i A') }} @if($message->edited_at)· edited @endif @if($mine)<i class="fas fa-check-double ml-1 text-[#53bdeb]"></i>@endif</p></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <form wire:submit="sendMessage" class="shrink-0 bg-[#f0f2f5] p-2 pb-[max(.5rem,env(safe-area-inset-bottom))] sm:p-3">
                    <div class="flex items-end gap-2"><div class="min-w-0 flex-1"><textarea wire:model="messageBody" rows="1" maxlength="5000" placeholder="Message" aria-label="Message" x-data="{ resize() { $el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 120) + 'px' } }" x-init="resize()" @input="resize()" @keydown.enter="if (!isMobile && !$event.shiftKey && !$event.isComposing) { $event.preventDefault(); $wire.sendMessage().then(() => { $el.style.height = 'auto' }) }" class="block max-h-[120px] min-h-11 w-full resize-none overflow-y-auto rounded-[1.4rem] border-0 bg-white px-4 py-3 text-[15px] leading-5 text-slate-900 shadow-sm placeholder:text-slate-500 focus:ring-1 focus:ring-[#00a884]/30 sm:min-h-12"></textarea>@error('messageBody')<p class="mt-1 px-2 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror</div><button type="submit" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-[#00a884] text-white shadow-sm transition hover:bg-[#008f72] disabled:opacity-60 sm:h-12 sm:w-12" title="Send message" wire:loading.attr="disabled" wire:target="sendMessage"><i wire:loading.remove wire:target="sendMessage" class="fas fa-paper-plane"></i><i wire:loading wire:target="sendMessage" class="fas fa-circle-notch fa-spin"></i></button></div>
                    <p class="mt-2 hidden text-[10px] text-slate-400 sm:block"><i class="fas fa-lock mr-1"></i>Visible only to people in this conversation. Messages are retained with timestamps.</p>
                </form>
            @else
                <div class="flex flex-1 items-center justify-center p-8 text-center"><div><span class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-emerald-50 text-3xl text-emerald-600"><i class="far fa-comments"></i></span><h2 class="mt-5 text-xl font-black text-slate-900">Your team conversations live here</h2><p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-slate-500">Choose an active colleague for a private chat, or create a broadcast for everyone or a hand-picked group.</p></div></div>
            @endif
        </main>
    </section>

    @if ($showBroadcastComposer)
        <div class="fixed inset-0 z-50 flex items-end justify-center bg-[#041d23]/80 p-0 backdrop-blur-md sm:items-center sm:p-6" wire:click.self="resetBroadcastForm">
            <div role="dialog" aria-modal="true" aria-labelledby="broadcast-heading" class="max-h-[96vh] w-full max-w-3xl overflow-hidden rounded-t-[2rem] bg-slate-50 shadow-[0_30px_100px_rgba(0,0,0,.4)] sm:max-h-[92vh] sm:rounded-[2rem]">
                <div class="relative overflow-hidden bg-[#082f36] px-5 py-6 text-white sm:px-8 sm:py-7">
                    <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full bg-emerald-400/10"></div>
                    <div class="relative flex items-start justify-between gap-5">
                        <div class="flex min-w-0 items-start gap-4">
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-orange-500 text-xl shadow-lg shadow-black/20"><i class="fas fa-bullhorn"></i></span>
                            <div><p class="text-[11px] font-black uppercase tracking-[.2em] text-emerald-300">Broadcast centre</p><h2 id="broadcast-heading" class="mt-1 text-2xl font-black tracking-tight sm:text-3xl">Create an announcement</h2><p class="mt-1.5 max-w-xl text-sm leading-5 text-slate-300">Share a clear update with the whole team or only the people who need it.</p></div>
                        </div>
                        <button type="button" wire:click="resetBroadcastForm" aria-label="Close broadcast composer" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-white/10 bg-white/10 text-slate-200 transition hover:bg-white/20 hover:text-white"><i class="fas fa-times"></i></button>
                    </div>
                </div>

                <form wire:submit="createBroadcast" class="max-h-[calc(96vh-136px)] overflow-y-auto sm:max-h-[calc(92vh-144px)]">
                    <div class="space-y-5 p-4 sm:p-7">
                        <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                            <div class="mb-4 flex items-center gap-3"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-sm text-emerald-700"><i class="fas fa-pen"></i></span><div><h3 class="text-sm font-black text-slate-900">Announcement details</h3><p class="text-xs text-slate-500">Keep the subject brief and the message easy to scan.</p></div></div>

                            <div>
                                <div class="flex items-center justify-between gap-3"><label for="broadcast-title" class="text-xs font-extrabold uppercase tracking-wider text-slate-600">Subject</label><span class="text-[10px] font-semibold text-slate-400">Maximum 120 characters</span></div>
                                <div class="relative mt-2">
                                    <i class="fas fa-heading pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                                    <input id="broadcast-title" type="text" wire:model="broadcastTitle" maxlength="120" autocomplete="off" placeholder="What does your team need to know?" class="h-[52px] w-full rounded-2xl border-2 {{ $errors->has('broadcastTitle') ? 'border-red-300 bg-red-50/40' : 'border-slate-200 bg-slate-50/70' }} py-3.5 pl-11 pr-4 text-[15px] font-semibold text-slate-900 placeholder:font-normal placeholder:text-slate-400 transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10">
                                </div>
                                @error('broadcastTitle')<p class="mt-2 flex items-center gap-1.5 text-xs font-bold text-red-600"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>@enderror
                            </div>

                            <div class="mt-5">
                                <div class="flex items-center justify-between gap-3"><label for="broadcast-message" class="text-xs font-extrabold uppercase tracking-wider text-slate-600">Message</label><span class="text-[10px] font-semibold text-slate-400">Required</span></div>
                                <div class="relative mt-2">
                                    <textarea id="broadcast-message" wire:model="broadcastBody" rows="6" maxlength="5000" placeholder="Write the announcement here. Include dates, times, locations and any action required…" class="min-h-36 w-full resize-y rounded-2xl border-2 {{ $errors->has('broadcastBody') ? 'border-red-300 bg-red-50/40' : 'border-slate-200 bg-slate-50/70' }} px-4 py-3.5 text-[15px] leading-6 text-slate-900 placeholder:text-slate-400 transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"></textarea>
                                </div>
                                @error('broadcastBody')<p class="mt-2 flex items-center gap-1.5 text-xs font-bold text-red-600"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>@enderror
                            </div>
                        </section>

                        <div class="grid gap-5 md:grid-cols-2">
                            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                                <label for="broadcast-priority" class="text-xs font-extrabold uppercase tracking-wider text-slate-600">Priority level</label>
                                <div class="relative mt-2"><i class="fas fa-signal pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i><select id="broadcast-priority" wire:model="broadcastPriority" class="h-[52px] w-full appearance-none rounded-2xl border-2 border-slate-200 bg-slate-50/70 py-3.5 pl-11 pr-10 text-sm font-bold text-slate-800 transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"><option value="normal">Normal update</option><option value="important">Important notice</option><option value="urgent">Urgent action</option></select><i class="fas fa-chevron-down pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i></div>
                                <p class="mt-2 text-xs leading-5 text-slate-500">Priority helps recipients understand how quickly they should respond.</p>
                            </section>

                            <label class="group flex cursor-pointer items-center gap-4 rounded-2xl border-2 p-4 shadow-sm transition sm:p-5 {{ $broadcastPinned ? 'border-orange-400 bg-orange-50' : 'border-slate-200 bg-white hover:border-slate-300' }}">
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $broadcastPinned ? 'bg-orange-500 text-white' : 'bg-slate-100 text-slate-500' }}"><i class="fas fa-thumbtack"></i></span>
                                <span class="min-w-0 flex-1"><strong class="block text-sm font-black text-slate-900">Pin announcement</strong><span class="mt-0.5 block text-xs leading-5 text-slate-500">Keep it above recent conversations.</span></span>
                                <span class="relative h-6 w-11 shrink-0 rounded-full transition {{ $broadcastPinned ? 'bg-orange-500' : 'bg-slate-300' }}"><input type="checkbox" wire:model.live="broadcastPinned" class="sr-only"><span class="absolute top-1 h-4 w-4 rounded-full bg-white shadow transition-all {{ $broadcastPinned ? 'left-6' : 'left-1' }}"></span></span>
                            </label>
                        </div>

                        <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                            <div class="flex items-center gap-3"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-orange-50 text-sm text-orange-600"><i class="fas fa-users"></i></span><div><h3 class="text-sm font-black text-slate-900">Choose recipients</h3><p class="text-xs text-slate-500">Control exactly who receives this announcement.</p></div></div>
                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                <label class="flex cursor-pointer items-center gap-3 rounded-2xl border-2 p-4 transition {{ $broadcastEveryone ? 'border-emerald-500 bg-emerald-50 ring-4 ring-emerald-500/5' : 'border-slate-200 hover:border-slate-300' }}"><input type="radio" wire:model.live="broadcastEveryone" value="1" class="h-4 w-4 border-slate-300 text-emerald-600 focus:ring-emerald-500"><span><strong class="block text-sm text-slate-900">Everyone</strong><span class="text-xs text-slate-500">All active dashboard staff</span></span></label>
                                <label class="flex cursor-pointer items-center gap-3 rounded-2xl border-2 p-4 transition {{ !$broadcastEveryone ? 'border-emerald-500 bg-emerald-50 ring-4 ring-emerald-500/5' : 'border-slate-200 hover:border-slate-300' }}"><input type="radio" wire:model.live="broadcastEveryone" value="0" class="h-4 w-4 border-slate-300 text-emerald-600 focus:ring-emerald-500"><span><strong class="block text-sm text-slate-900">Selected staff</strong><span class="text-xs text-slate-500">Create a targeted audience</span></span></label>
                            </div>
                            @if(!$broadcastEveryone)
                                <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200"><div class="border-b border-slate-200 bg-slate-50 px-4 py-2.5 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">Select team members</div><div class="max-h-52 divide-y divide-slate-100 overflow-y-auto p-1">@foreach($staff as $person)<label class="flex cursor-pointer items-center gap-3 rounded-xl px-3 py-3 transition hover:bg-emerald-50/70"><input type="checkbox" wire:model="broadcastRecipientIds" value="{{ $person->id }}" class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-xs font-black text-slate-600">{{ strtoupper(substr($person->name, 0, 1)) }}</span><span class="min-w-0 flex-1 truncate text-sm font-bold text-slate-700">{{ $person->name }}</span><span class="shrink-0 rounded-full bg-slate-100 px-2 py-1 text-[10px] font-bold text-slate-500">{{ $person->role_label }}</span></label>@endforeach</div></div>
                            @endif
                            @error('broadcastRecipientIds')<p class="mt-2 flex items-center gap-1.5 text-xs font-bold text-red-600"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>@enderror
                        </section>
                    </div>

                    <div class="sticky bottom-0 flex flex-col-reverse gap-3 border-t border-slate-200 bg-white/95 px-4 py-4 backdrop-blur sm:flex-row sm:items-center sm:justify-between sm:px-7">
                        <p class="hidden text-xs text-slate-500 sm:block"><i class="fas fa-lock mr-1.5 text-emerald-600"></i>Visible only to selected staff</p>
                        <div class="flex flex-col-reverse gap-3 sm:flex-row"><button type="button" wire:click="resetBroadcastForm" class="rounded-xl border-2 border-slate-200 px-5 py-3 text-sm font-extrabold text-slate-600 transition hover:bg-slate-50">Cancel</button><button type="submit" wire:loading.attr="disabled" wire:target="createBroadcast" class="inline-flex items-center justify-center rounded-xl bg-[#ed5a1f] px-6 py-3 text-sm font-extrabold text-white shadow-lg shadow-orange-200 transition hover:-translate-y-0.5 hover:bg-[#d94d16] disabled:cursor-wait disabled:opacity-70"><span wire:loading.remove wire:target="createBroadcast"><i class="fas fa-paper-plane mr-2"></i>Send broadcast</span><span wire:loading wire:target="createBroadcast"><i class="fas fa-circle-notch fa-spin mr-2"></i>Sending…</span></button></div>
                    </div>
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
