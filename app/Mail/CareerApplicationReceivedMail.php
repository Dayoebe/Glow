<?php

namespace App\Mail;

use App\Models\Career\CareerApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CareerApplicationReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $opportunityName;

    public function __construct(public CareerApplication $application)
    {
        $this->opportunityName = $this->resolveOpportunityName();
    }

    public function build(): self
    {
        return $this->subject('We received your Glow FM application — '.$this->opportunityName)
            ->view('emails.careers.application-received');
    }

    private function resolveOpportunityName(): string
    {
        return $this->application->position?->title ?? match ($this->application->application_type) {
            'internship' => 'Internship Programme',
            'volunteer' => 'Volunteer Programme',
            'marketer' => 'Advertiser/Marketer Opportunity',
            'academy' => 'Glow FM Academy',
            default => 'Career Opportunity',
        };
    }
}
