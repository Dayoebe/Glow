<?php

namespace App\Livewire\Admin\Inbox;

use App\Livewire\Concerns\RemembersAdminPagination;
use App\Models\ContactMessage;
use App\Mail\ContactReplyMail;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class ContactInbox extends Component
{
    use RemembersAdminPagination, WithPagination;

    public $search = '';
    public $selectedMessageId = null;
    public $showMessageModal = false;
    public $replyMessage = '';
    public $adminNotes = '';
    public $status = 'new';
    public $message = null;

    protected $queryString = [
        'search',
        'message' => ['except' => null],
    ];

    public function mount(): void
    {
        $messageId = (int) $this->message;

        if ($messageId > 0) {
            $this->openMessage($messageId);
            $this->message = null;
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openMessage($id)
    {
        $this->selectedMessageId = $id;
        $this->showMessageModal = true;

        $message = ContactMessage::find($id);
        if ($message) {
            $this->adminNotes = $message->admin_notes ?? '';
            $this->status = $message->status ?? 'new';
            $this->replyMessage = '';
            $message->update(['is_read' => true]);
        }
    }

    public function closeMessage()
    {
        $this->showMessageModal = false;
        $this->selectedMessageId = null;
        $this->replyMessage = '';
    }

    public function markUnread($id)
    {
        ContactMessage::where('id', $id)->update(['is_read' => false]);
        session()->flash('success', 'Message marked as unread.');
    }

    public function deleteMessage($id)
    {
        $message = ContactMessage::find($id);
        if ($message) {
            $message->delete();
            session()->flash('success', 'Message deleted.');
        }

        if ($this->selectedMessageId === $id) {
            $this->closeMessage();
        }
    }

    public function getMessagesProperty()
    {
        return ContactMessage::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('subject', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(12);
    }

    public function getSelectedMessageProperty()
    {
        if (!$this->selectedMessageId) {
            return null;
        }

        return ContactMessage::find($this->selectedMessageId);
    }

    public function saveNotes()
    {
        if (!$this->selectedMessageId) {
            return;
        }

        ContactMessage::where('id', $this->selectedMessageId)->update([
            'admin_notes' => $this->adminNotes,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Notes updated.');
    }

    public function sendReply()
    {
        if (!$this->selectedMessageId || empty($this->replyMessage)) {
            return;
        }

        $message = ContactMessage::find($this->selectedMessageId);
        if (!$message) {
            return;
        }

        Mail::to($message->email)->send(new ContactReplyMail($message->name, $this->replyMessage));

        $message->update([
            'status' => 'replied',
            'replied_at' => now(),
            'is_read' => true,
        ]);

        $this->replyMessage = '';
        session()->flash('success', 'Reply sent.');
    }

    public function render()
    {
        return view('livewire.admin.inbox.contact-inbox', [
            'messages' => $this->messages,
            'selectedMessage' => $this->selectedMessage,
        ])->layout('layouts.admin', ['header' => 'Contact Inbox']);
    }
}
