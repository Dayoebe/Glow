<?php

namespace App\Support;

class VettasPageSettings
{
    public static function defaults(): array
    {
        return [
            'about' => [
                'eyebrow' => 'About Vettas',
                'title' => 'A private stay built around comfort and ease',
                'summary' => 'Vettas Apartment offers a calm, fully furnished stay for guests who want comfort, privacy, and convenience in one place. Whether you are visiting for business, a short getaway, or a longer stay, each space is prepared to feel welcoming, secure, and easy to settle into.',
                'highlights' => [
                    'Fully furnished apartment spaces',
                    'Comfortable private environment',
                    'Ideal for short and extended stays',
                ],
            ],
            'contact' => [
                'title' => 'Book or Make an Enquiry',
                'intro' => 'Need availability, pricing, directions, or a quick answer before booking? Reach out through any of the contact options below.',
                'phone' => '',
                'whatsapp' => '',
                'email' => '',
                'address' => '',
                'hours' => '',
                'booking_note' => 'Use the contact details below for reservations, availability checks, and general enquiries.',
                'instagram' => '',
                'website' => '',
            ],
        ];
    }
}
