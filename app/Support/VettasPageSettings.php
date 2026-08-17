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
                'reservation_notification_email' => 'chairman@glowfmradio.com',
                'address' => '',
                'hours' => '',
                'booking_note' => 'Use the contact details below for reservations, availability checks, and general enquiries.',
                'instagram' => '',
                'website' => '',
            ],
            'promotion' => [
                'headline' => 'Your private, fully furnished stay in Akure',
                'short_caption' => 'Need a calm, comfortable apartment for your next stay? Discover Vettas Apartment, explore the spaces and request your dates today.',
                'long_caption' => 'Arrive, settle in and enjoy a private furnished apartment designed for comfortable short and extended stays. Explore Vettas Apartment, view the latest photos, check the amenities and send your preferred dates for an availability confirmation.',
                'hashtags' => '#VettasApartment #AkureApartments #StayInAkure #ShortLetAkure #VisitOndo',
            ],
            'seo' => [
                'about' => 'Learn about Vettas Apartment, a private furnished stay designed for comfort, ease and memorable visits.',
                'amenities' => 'Explore the comfort, convenience and guest amenities available at Vettas Apartment.',
                'gallery' => 'See published photos of the rooms and spaces at Vettas Apartment.',
                'guide' => 'Plan your arrival, stay and reservation at Vettas Apartment.',
            ],
        ];
    }
}
