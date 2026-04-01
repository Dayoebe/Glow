<?php

namespace App\Mail;

use App\Models\Vettas\VettasReservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VettasReservationSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public VettasReservation $reservation;

    public function __construct(VettasReservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function build()
    {
        return $this->subject('New Vettas reservation request from ' . $this->reservation->full_name)
            ->replyTo($this->reservation->email, $this->reservation->full_name)
            ->view('emails.vettas.reservation-submitted');
    }
}
