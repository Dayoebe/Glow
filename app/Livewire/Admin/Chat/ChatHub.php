<?php

namespace App\Livewire\Admin\Chat;

use App\Models\Chat\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ChatHub extends Component
{
    public ?int $selectedConversationId = null;
    public ?int $directRecipientId = null;
    public string $messageBody = '';
    public string $search = '';
    public string $peopleSearch = '';
    public string $filter = 'all';
    public bool $showBroadcastComposer = false;
    public string $broadcastTitle = '';
    public string $broadcastBody = '';
    public string $broadcastPriority = 'normal';
    public bool $broadcastPinned = false;
    public bool $broadcastEveryone = true;
    public array $broadcastRecipientIds = [];

    protected $queryString = ['selectedConversationId' => ['as' => 'conversation', 'except' => null]];

    public function mount(): void
    {
        $this->selectedConversationId = $this->selectedConversationId
            && $this->conversationForUser($this->selectedConversationId)
                ? $this->selectedConversationId
                : $this->conversations()->first()?->id;
        $this->markSelectedRead();
    }

    public function selectConversation(int $conversationId): void
    {
        abort_unless($this->conversationForUser($conversationId), 403);
        $this->selectedConversationId = $conversationId;
        $this->markSelectedRead();
        $this->dispatch('chat-selected');
    }

    public function startDirect(): void
    {
        $recipient = $this->eligibleStaffQuery()->whereKey($this->directRecipientId)->first();
        $this->addError('directRecipientId', 'Please select an active staff member.');
        if (!$recipient || $recipient->is(auth()->user())) return;
        $this->resetErrorBag('directRecipientId');

        $ids = [(int) auth()->id(), (int) $recipient->id];
        sort($ids);
        $key = implode(':', $ids);

        $conversation = DB::transaction(function () use ($ids, $key) {
            $conversation = Conversation::firstOrCreate(
                ['direct_key' => $key],
                ['type' => 'direct', 'created_by' => auth()->id(), 'last_message_at' => now()]
            );
            foreach ($ids as $id) {
                $conversation->participants()->syncWithoutDetaching([$id => ['joined_at' => now()]]);
            }
            return $conversation;
        });

        $this->directRecipientId = null;
        $this->selectConversation($conversation->id);
    }

    public function sendMessage(): void
    {
        $conversation = $this->conversationForUser($this->selectedConversationId);
        abort_unless($conversation, 403);
        $validated = $this->validate(['messageBody' => ['required', 'string', 'max:5000']]);

        DB::transaction(function () use ($conversation, $validated) {
            $conversation->messages()->create([
                'sender_id' => auth()->id(),
                'body' => trim($validated['messageBody']),
            ]);
            $conversation->update(['last_message_at' => now()]);
            $this->markRead($conversation->id);
        });

        $this->messageBody = '';
        $this->dispatch('message-sent');
    }

    public function createBroadcast(): void
    {
        $validated = $this->validate([
            'broadcastTitle' => ['required', 'string', 'max:120'],
            'broadcastBody' => ['required', 'string', 'max:5000'],
            'broadcastPriority' => ['required', Rule::in(['normal', 'important', 'urgent'])],
            'broadcastPinned' => ['boolean'],
            'broadcastEveryone' => ['boolean'],
            'broadcastRecipientIds' => [Rule::requiredIf(!$this->broadcastEveryone), 'array'],
            'broadcastRecipientIds.*' => ['integer'],
        ]);

        $eligibleIds = $this->eligibleStaffQuery()->pluck('id');
        $recipientIds = $this->broadcastEveryone
            ? $eligibleIds
            : $eligibleIds->intersect(array_map('intval', $validated['broadcastRecipientIds']));
        $recipientIds->push((int) auth()->id())->unique();

        if ($recipientIds->count() < 2) {
            $this->addError('broadcastRecipientIds', 'Choose at least one active staff recipient.');
            return;
        }

        $conversation = DB::transaction(function () use ($validated, $recipientIds) {
            $conversation = Conversation::create([
                'type' => 'broadcast',
                'created_by' => auth()->id(),
                'title' => trim($validated['broadcastTitle']),
                'priority' => $validated['broadcastPriority'],
                'is_pinned' => $validated['broadcastPinned'],
                'last_message_at' => now(),
            ]);
            $conversation->participants()->attach($recipientIds->mapWithKeys(fn ($id) => [$id => ['joined_at' => now(), 'last_read_at' => $id === (int) auth()->id() ? now() : null]])->all());
            $conversation->messages()->create(['sender_id' => auth()->id(), 'body' => trim($validated['broadcastBody'])]);
            return $conversation;
        });

        $this->resetBroadcastForm();
        $this->selectConversation($conversation->id);
        session()->flash('success', 'Broadcast sent to '.$recipientIds->count().' staff members.');
    }

    public function toggleMute(): void
    {
        $conversation = $this->conversationForUser($this->selectedConversationId);
        abort_unless($conversation, 403);
        $participant = DB::table('chat_participants')->where('conversation_id', $conversation->id)->where('user_id', auth()->id());
        $isMuted = (bool) $participant->value('is_muted');
        $participant->update(['is_muted' => !$isMuted, 'updated_at' => now()]);
    }

    public function resetBroadcastForm(): void
    {
        $this->reset(['showBroadcastComposer', 'broadcastTitle', 'broadcastBody', 'broadcastRecipientIds', 'broadcastPinned']);
        $this->broadcastPriority = 'normal';
        $this->broadcastEveryone = true;
        $this->resetErrorBag();
    }

    private function eligibleStaffQuery()
    {
        return User::query()->where('is_active', true)->whereIn('role', ['admin', 'staff', 'corp_member', 'intern']);
    }

    private function conversationForUser(?int $id): ?Conversation
    {
        if (!$id) return null;
        return Conversation::query()->whereKey($id)->whereHas('participants', fn ($q) => $q->where('users.id', auth()->id()))->first();
    }

    private function conversations()
    {
        return Conversation::query()
            ->whereHas('participants', fn ($q) => $q->where('users.id', auth()->id()))
            ->with(['participants.staffMember', 'latestMessage.sender'])
            ->withCount(['messages as unread_count' => fn ($q) => $q
                ->where('sender_id', '!=', auth()->id())
                ->whereRaw('chat_messages.created_at > COALESCE((SELECT last_read_at FROM chat_participants WHERE conversation_id = chat_conversations.id AND user_id = ?), ?)', [auth()->id(), '1970-01-01 00:00:00'])])
            ->orderByDesc('is_pinned')->orderByDesc('last_message_at')->get();
    }

    private function markSelectedRead(): void
    {
        if ($this->selectedConversationId) $this->markRead($this->selectedConversationId);
    }

    private function markRead(int $conversationId): void
    {
        DB::table('chat_participants')->where('conversation_id', $conversationId)->where('user_id', auth()->id())->update(['last_read_at' => now(), 'updated_at' => now()]);
    }

    public function render()
    {
        $conversations = $this->conversations();
        $filtered = $conversations->filter(function ($conversation) {
            if ($this->filter === 'unread' && !$conversation->unread_count) return false;
            if ($this->filter === 'broadcasts' && $conversation->type !== 'broadcast') return false;
            if (!$this->search) return true;
            return str_contains(strtolower($this->conversationName($conversation)), strtolower(trim($this->search)));
        });
        $selected = $this->conversationForUser($this->selectedConversationId);
        $selected?->load(['participants.staffMember', 'messages' => fn ($q) => $q->with('sender')->latest()->limit(150)]);
        if ($selected) $selected->setRelation('messages', $selected->messages->sortBy('created_at')->values());

        return view('livewire.admin.chat.chat-hub', [
            'conversations' => $filtered,
            'selected' => $selected,
            'staff' => $this->eligibleStaffQuery()->whereKeyNot(auth()->id())->when($this->peopleSearch, fn ($q) => $q->where('name', 'like', '%'.trim($this->peopleSearch).'%'))->orderBy('name')->get(),
            'totalUnread' => $conversations->sum('unread_count'),
        ])->layout('layouts.admin', ['header' => 'Staff Chat']);
    }

    public function conversationName(Conversation $conversation): string
    {
        if ($conversation->type === 'broadcast') return $conversation->title ?: 'Staff broadcast';
        return $conversation->participants->firstWhere('id', '!=', auth()->id())?->name ?: 'Direct message';
    }
}
