<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ContactSubmittedMail;
use App\Models\ContactMessage;
use App\Models\Setting;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        $station = Seo::station();
        $defaults = [
            'header_title' => 'Get In Touch',
            'header_subtitle' => 'We\'d love to hear from you! Whether you have a question, feedback, or just want to say hello, we\'re here to help.',
            'contact_info' => [
                'address' => $station['address'],
                'phone' => $station['phone'],
                'email' => $station['email'],
                'hours' => [
                    'weekdays' => '9AM - 6PM',
                    'saturday' => '10AM - 4PM',
                    'sunday' => '10AM - 4PM',
                ],
                'map_embed' => '',
            ],
            'departments' => [
                [
                    'name' => 'General Inquiries',
                    'icon' => 'fas fa-info-circle',
                    'email' => $station['email'],
                    'phone' => $station['phone'],
                    'description' => 'For general questions and information',
                    'color' => 'emerald',
                ],
                [
                    'name' => 'Advertising',
                    'icon' => 'fas fa-bullhorn',
                    'email' => $station['email'],
                    'phone' => $station['phone'],
                    'description' => 'Advertising and sponsorship opportunities',
                    'color' => 'blue',
                ],
                [
                    'name' => 'Programming',
                    'icon' => 'fas fa-microphone',
                    'email' => $station['email'],
                    'phone' => $station['phone'],
                    'description' => 'Show suggestions and program feedback',
                    'color' => 'amber',
                ],
                [
                    'name' => 'Technical Support',
                    'icon' => 'fas fa-headset',
                    'email' => $station['email'],
                    'phone' => $station['phone'],
                    'description' => 'Streaming issues and technical help',
                    'color' => 'purple',
                ],
                [
                    'name' => 'Events',
                    'icon' => 'fas fa-calendar-alt',
                    'email' => $station['email'],
                    'phone' => $station['phone'],
                    'description' => 'Event inquiries and partnerships',
                    'color' => 'pink',
                ],
                [
                    'name' => 'Careers',
                    'icon' => 'fas fa-briefcase',
                    'email' => $station['email'],
                    'phone' => $station['phone'],
                    'description' => 'Job opportunities and internships',
                    'color' => 'indigo',
                ],
            ],
            'faqs' => [
                [
                    'question' => 'How can I listen to Glow FM online?',
                    'answer' => 'You can listen to Glow FM through our website by clicking the "Listen Live" button, downloading our mobile app (available on iOS and Android), or using your favorite radio streaming app by searching for "Glow FM 99.1".',
                ],
                [
                    'question' => 'How do I request a song?',
                    'answer' => 'You can request songs through our website contact form, by calling the station, or by sending us a message on our social media channels. Make sure to include the song title and artist name!',
                ],
                [
                    'question' => 'Can I visit the studio?',
                    'answer' => 'Yes! We offer studio tours by appointment. Please contact us at least one week in advance to schedule your visit. Group tours for schools and organizations are also available.',
                ],
                [
                    'question' => 'How do I advertise on Glow FM?',
                    'answer' => 'For advertising opportunities, contact Glow 99.1 FM through the contact page or call the station to discuss radio spots, sponsored programs, social media promotion, live coverage, jingles, interviews, and Glow TV packages.',
                ],
                [
                    'question' => 'Are you hiring?',
                    'answer' => 'We\'re always looking for talented individuals! Check our careers page or send your resume and cover letter to careers@glowfm.com. We offer opportunities for DJs, producers, marketing professionals, and more.',
                ],
                [
                    'question' => 'How can I sponsor an event?',
                    'answer' => 'We love partnering with local businesses for events! Contact our events team at events@glowfm.com to discuss sponsorship opportunities and how we can work together to create memorable experiences.',
                ],
            ],
            'socials' => [
                ['name' => 'Facebook', 'icon' => 'fab fa-facebook-f', 'url' => '#', 'handle' => '@glowfm991', 'color' => 'blue'],
                ['name' => 'Twitter', 'icon' => 'fab fa-twitter', 'url' => '#', 'handle' => '@glowfm', 'color' => 'sky'],
                ['name' => 'Instagram', 'icon' => 'fab fa-instagram', 'url' => '#', 'handle' => '@glowfm991', 'color' => 'pink'],
                ['name' => 'YouTube', 'icon' => 'fab fa-youtube', 'url' => '#', 'handle' => 'Glow FM 99.1', 'color' => 'red'],
                ['name' => 'TikTok', 'icon' => 'fab fa-tiktok', 'url' => '#', 'handle' => '@glowfm', 'color' => 'slate'],
                ['name' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in', 'url' => '#', 'handle' => 'Glow FM', 'color' => 'indigo'],
            ],
        ];

        $settings = Setting::get('website.contact', []);
        $content = array_replace_recursive($defaults, is_array($settings) ? $settings : []);

        return response()->json([
            'data' => $content,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'nullable|min:10',
            'subject' => 'required|min:5',
            'message' => 'required|min:10',
            'inquiry_type' => 'required',
        ]);

        $record = ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'],
            'inquiry_type' => $validated['inquiry_type'],
            'message' => $validated['message'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $notifyEmail = Setting::get('system.support_email', Setting::get('station.email', config('mail.from.address')));
        if ($notifyEmail) {
            Mail::to($notifyEmail)->send(new ContactSubmittedMail($record));
        }

        return response()->json([
            'message' => 'Contact message received.',
        ], 201);
    }
}
