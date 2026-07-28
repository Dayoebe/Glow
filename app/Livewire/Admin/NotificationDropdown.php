<?php

namespace App\Livewire\Admin;

use App\Models\ContactMessage;
use Livewire\Component;

class NotificationDropdown extends Component
{
    public int $unreadCount = 0;

    public array $messages = [];

    public function mount(): void
    {
        $this->loadNotifications();
    }

    public function loadNotifications(): void
    {
        $this->unreadCount = ContactMessage::where('is_read', false)->count();
        $this->messages = ContactMessage::query()
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get()
            ->map(fn (ContactMessage $message) => [
                'id' => $message->id,
                'name' => $message->name,
                'subject' => $message->subject,
                'received' => $message->created_at?->diffForHumans() ?? '',
            ])
            ->all();
    }

    public function render()
    {
        return view('livewire.admin.notification-dropdown');
    }
}
