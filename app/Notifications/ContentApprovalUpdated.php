<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContentApprovalUpdated extends Notification
{
    use Queueable;

    public function __construct(
        public string $contentType,
        public string $title,
        public string $status,
        public ?string $reason = null,
        public bool $sendMail = true
    ) {
    }

    public function via(object $notifiable): array
    {
        return $this->sendMail ? ['mail', 'database'] : ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject("Content review update: {$this->title}")
            ->greeting("Hi {$notifiable->name},")
            ->line("Your {$this->contentType} \"{$this->title}\" has been {$this->status}.");

        if ($this->reason) {
            $message->line("Reason: {$this->reason}");
        }

        return $message->line('Thanks for contributing to Glow FM.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'content_type' => $this->contentType,
            'title' => $this->title,
            'status' => $this->status,
            'reason' => $this->reason,
        ];
    }
}
