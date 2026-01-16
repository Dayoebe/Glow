<?php

namespace App\Livewire\Page;

use App\Models\Setting;
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
        $defaults = [
            'header_title' => 'Get In Touch',
            'header_subtitle' => 'We\'d love to hear from you! Whether you have a question, feedback, or just want to say hello, we\'re here to help.',
            'contact_info' => [
                'address' => '123 Radio Street, Broadcasting City, BC 12345',
                'phone' => '+1 (234) 567-890',
                'email' => 'info@glowfm.com',
                'hours' => [
                    'weekdays' => '9AM - 6PM',
                    'saturday' => '10AM - 4PM',
                    'sunday' => '10AM - 4PM',
                ],
                'map_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.2412648750455!2d-73.98731668459395!3d40.74844097932847!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259a9b3117469%3A0xd134e199a405a163!2sEmpire%20State%20Building!5e0!3m2!1sen!2sus!4v1234567890123!5m2!1sen!2sus',
            ],
            'departments' => [
                [
                    'name' => 'General Inquiries',
                    'icon' => 'fas fa-info-circle',
                    'email' => 'info@glowfm.com',
                    'phone' => '+1 (234) 567-890',
                    'description' => 'For general questions and information',
                    'color' => 'emerald',
                ],
                [
                    'name' => 'Advertising',
                    'icon' => 'fas fa-bullhorn',
                    'email' => 'advertising@glowfm.com',
                    'phone' => '+1 (234) 567-891',
                    'description' => 'Advertising and sponsorship opportunities',
                    'color' => 'blue',
                ],
                [
                    'name' => 'Programming',
                    'icon' => 'fas fa-microphone',
                    'email' => 'programming@glowfm.com',
                    'phone' => '+1 (234) 567-892',
                    'description' => 'Show suggestions and program feedback',
                    'color' => 'amber',
                ],
                [
                    'name' => 'Technical Support',
                    'icon' => 'fas fa-headset',
                    'email' => 'support@glowfm.com',
                    'phone' => '+1 (234) 567-893',
                    'description' => 'Streaming issues and technical help',
                    'color' => 'purple',
                ],
                [
                    'name' => 'Events',
                    'icon' => 'fas fa-calendar-alt',
                    'email' => 'events@glowfm.com',
                    'phone' => '+1 (234) 567-894',
                    'description' => 'Event inquiries and partnerships',
                    'color' => 'pink',
                ],
                [
                    'name' => 'Careers',
                    'icon' => 'fas fa-briefcase',
                    'email' => 'careers@glowfm.com',
                    'phone' => '+1 (234) 567-895',
                    'description' => 'Job opportunities and internships',
                    'color' => 'indigo',
                ],
            ],
            'faqs' => [
                [
                    'question' => 'How can I listen to Glow FM online?',
                    'answer' => 'You can listen to Glow FM through our website by clicking the \"Listen Live\" button, downloading our mobile app (available on iOS and Android), or using your favorite radio streaming app by searching for \"Glow FM 99.1\".',
                ],
                [
                    'question' => 'How do I request a song?',
                    'answer' => 'You can request songs through our website contact form, by calling our request line at +1 (234) 567-890, or by sending us a message on our social media channels. Make sure to include the song title and artist name!',
                ],
                [
                    'question' => 'Can I visit the studio?',
                    'answer' => 'Yes! We offer studio tours by appointment. Please contact us at least one week in advance to schedule your visit. Group tours for schools and organizations are also available.',
                ],
                [
                    'question' => 'How do I advertise on Glow FM?',
                    'answer' => 'For advertising opportunities, please contact our advertising department at advertising@glowfm.com or call +1 (234) 567-891. Our team will help you create a custom advertising package that fits your needs and budget.',
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
        $this->contactContent = array_replace_recursive($defaults, $settings);
    }

    public function submitForm()
    {
        $this->validate();

        // Here you would normally save to database or send email
        // For now, we'll just show a success message
        
        $this->successMessage = 'Thank you for contacting us! We\'ve received your message and will get back to you within 24 hours.';
        $this->errorMessage = '';

        // Reset form
        $this->reset(['name', 'email', 'phone', 'subject', 'message', 'inquiry_type']);

        // Scroll to success message
        $this->dispatch('scrollToTop');
    }

    public function render()
    {
        return view('livewire.page.contact-page')->layout('layouts.app', [
            'title' => 'Contact Us - Glow FM 99.1'
        ]);
    }
}
