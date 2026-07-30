<?php

namespace App\Livewire\Page;

use App\Models\Setting;
use App\Models\ContactMessage;
use App\Mail\ContactSubmittedMail;
use App\Support\Seo;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ContactPage extends Component
{
    // Form fields
    public $name = '';
    public $email = '';
    public $phone = '';
    public $subject = '';
    public $message = '';
    public $inquiry_type = 'general';

    // Success/Error messages
    public $successMessage = '';
    public $errorMessage = '';

    public $contactContent = [];

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'phone' => 'nullable|min:10',
        'subject' => 'required|min:5',
        'message' => 'required|min:10',
        'inquiry_type' => 'required',
    ];

    protected $messages = [
        'name.required' => 'Please enter your name.',
        'name.min' => 'Name must be at least 3 characters.',
        'email.required' => 'Please enter your email address.',
        'email.email' => 'Please enter a valid email address.',
        'subject.required' => 'Please enter a subject.',
        'subject.min' => 'Subject must be at least 5 characters.',
        'message.required' => 'Please enter your message.',
        'message.min' => 'Message must be at least 10 characters.',
    ];

    public function mount()
    {
        $station = Seo::station();
        $requestedInquiry = request()->query('inquiry_type');
        $allowedInquiryTypes = ['general', 'advertising', 'programming', 'technical', 'events', 'careers', 'feedback'];
        if (is_string($requestedInquiry) && in_array($requestedInquiry, $allowedInquiryTypes, true)) {
            $this->inquiry_type = $requestedInquiry;
        }

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
                    'answer' => 'Use the Listen Live button on this website to hear the Glow 99.1 FM stream.',
                ],
                [
                    'question' => 'How do I request a song?',
                    'answer' => 'Use this contact form or call the station and include the song title and artist name in your request.',
                ],
                [
                    'question' => 'Can I visit the studio?',
                    'answer' => 'Use this contact form to ask about a studio visit. The station team will confirm whether a visit can be accommodated.',
                ],
                [
                    'question' => 'How do I advertise on Glow FM?',
                    'answer' => 'Select Advertising in this form or call the station. Include your campaign goal, preferred dates, intended audience, and budget range.',
                ],
                [
                    'question' => 'Are you hiring?',
                    'answer' => 'Current opportunities are published on the Glow FM careers page. Applications should be submitted through the instructions on an active listing.',
                ],
                [
                    'question' => 'How can I sponsor an event?',
                    'answer' => 'Select Events in this contact form and share the event, proposed partnership, dates, and contact details.',
                ],
            ],
            'socials' => [],
        ];

        $settings = Setting::get('website.contact', []);
        $this->contactContent = array_replace_recursive($defaults, $settings);

        $this->contactContent['socials'] = collect((array) data_get($this->contactContent, 'socials', []))
            ->filter(function ($social) {
                if (!is_array($social)) {
                    return false;
                }

                $url = trim((string) ($social['url'] ?? ''));

                return $url !== ''
                    && $url !== '#'
                    && (str_starts_with($url, 'https://') || str_starts_with($url, 'http://'));
            })
            ->values()
            ->all();

        $this->contactContent['faqs'] = collect((array) data_get($this->contactContent, 'faqs', []))
            ->map(function ($faq) {
                if (!is_array($faq)) {
                    return $faq;
                }

                $answer = strtolower((string) ($faq['answer'] ?? ''));

                if (str_contains($answer, 'available on ios and android') || str_contains($answer, 'downloading our mobile app')) {
                    $faq['answer'] = 'Use the Listen Live button on this website to hear the Glow 99.1 FM stream.';
                } elseif (str_contains($answer, 'social media channels')) {
                    $faq['answer'] = 'Use this contact form or call the station and include the song title and artist name in your request.';
                } elseif (str_contains($answer, 'offer studio tours')) {
                    $faq['answer'] = 'Use this contact form to ask about a studio visit. The station team will confirm whether a visit can be accommodated.';
                } elseif (str_contains($answer, 'glow tv packages')) {
                    $faq['answer'] = 'Select Advertising in this form or call the station. Include your campaign goal, preferred dates, intended audience, and budget range.';
                } elseif (str_contains($answer, 'careers@glowfm.com')) {
                    $faq['answer'] = 'Current opportunities are published on the Glow FM careers page. Applications should be submitted through the instructions on an active listing.';
                } elseif (str_contains($answer, 'events@glowfm.com')) {
                    $faq['answer'] = 'Select Events in this contact form and share the event, proposed partnership, dates, and contact details.';
                }

                return $faq;
            })
            ->values()
            ->all();
    }

    public function submitForm()
    {
        $this->validate();

        $record = ContactMessage::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'subject' => $this->subject,
            'inquiry_type' => $this->inquiry_type,
            'message' => $this->message,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $notifyEmail = Setting::get('system.support_email', Setting::get('station.email', config('mail.from.address')));
        if ($notifyEmail) {
            Mail::to($notifyEmail)->send(new ContactSubmittedMail($record));
        }

        return redirect()->route('contact.success');
    }

    public function render()
    {
        $station = Seo::station();
        $description = 'Contact Glow 99.1 FM in Ijapo Estate, Akure, Ondo State, Nigeria for listener support, news tips, advertising, programs, podcasts, and media partnerships.';

        return view('livewire.page.contact-page')->layout('layouts.app', [
            'title' => 'Contact Glow 99.1 FM',
            'meta_title' => 'Contact Glow 99.1 FM Akure',
            'meta_description' => $description,
            'canonical_url' => route('contact'),
            'structured_data' => Seo::siteGraph([
                'title' => 'Contact Glow 99.1 FM Akure',
                'description' => $description,
                'url' => route('contact'),
                'breadcrumbs' => [
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'Contact', 'url' => route('contact')],
                ],
                'extra' => [
                    [
                        '@type' => 'ContactPage',
                        '@id' => route('contact') . '#contact-page',
                        'name' => 'Contact Glow 99.1 FM',
                        'url' => route('contact'),
                        'about' => ['@id' => $station['url'] . '/#radio-station'],
                    ],
                ],
            ]),
        ]);
    }
}
