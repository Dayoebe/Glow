<?php

namespace App\Livewire\Page;

use Livewire\Component;

class AboutPage extends Component
{
    public $milestones = [];
    public $values = [];
    public $team = [];
    public $achievements = [];
    public $partners = [];

    public function mount()
    {
        // Station History/Milestones
        $this->milestones = [
            [
                'year' => '2010',
                'title' => 'The Beginning',
                'description' => 'Glow FM 99.1 launched with a vision to revolutionize radio broadcasting and bring fresh, engaging content to the community.'
            ],
            [
                'year' => '2013',
                'title' => 'Digital Expansion',
                'description' => 'Launched our online streaming platform, mobile apps, and social media presence, reaching listeners beyond traditional radio.'
            ],
            [
                'year' => '2017',
                'title' => 'Award Recognition',
                'description' => 'Won our first National Broadcasting Award for Best Radio Station, recognizing our innovative programming and community engagement.'
            ],
            [
                'year' => '2020',
                'title' => 'Studio Upgrade',
                'description' => 'Invested in state-of-the-art broadcasting equipment and renovated our studios to enhance audio quality and production capabilities.'
            ],
            [
                'year' => '2023',
                'title' => 'Community Impact',
                'description' => 'Reached 1 million monthly listeners and launched various community programs, charity initiatives, and local talent showcases.'
            ],
            [
                'year' => '2025',
                'title' => 'Innovation Leader',
                'description' => 'Pioneered interactive broadcasting technology and became the region\'s most-listened-to radio station with cutting-edge content.'
            ],
        ];

        // Core Values
        $this->values = [
            [
                'icon' => 'fas fa-heart',
                'title' => 'Community First',
                'description' => 'We put our listeners and local community at the heart of everything we do, creating content that resonates and connects.',
                'color' => 'red'
            ],
            [
                'icon' => 'fas fa-star',
                'title' => 'Excellence',
                'description' => 'We strive for excellence in broadcasting, from audio quality to content creation, delivering the best experience possible.',
                'color' => 'yellow'
            ],
            [
                'icon' => 'fas fa-lightbulb',
                'title' => 'Innovation',
                'description' => 'We embrace new technologies and creative approaches to stay ahead and provide cutting-edge entertainment.',
                'color' => 'emerald'
            ],
            [
                'icon' => 'fas fa-hands-helping',
                'title' => 'Integrity',
                'description' => 'We operate with honesty, transparency, and ethical standards in all our interactions and business practices.',
                'color' => 'blue'
            ],
            [
                'icon' => 'fas fa-users',
                'title' => 'Diversity',
                'description' => 'We celebrate diversity in music, voices, and perspectives, creating an inclusive platform for all.',
                'color' => 'purple'
            ],
            [
                'icon' => 'fas fa-rocket',
                'title' => 'Passion',
                'description' => 'Our team is driven by genuine passion for music, broadcasting, and creating memorable experiences for our audience.',
                'color' => 'orange'
            ],
        ];

        // Leadership Team
        $this->team = [
            [
                'name' => 'Michael Rodriguez',
                'position' => 'Station Manager',
                'bio' => 'With over 20 years in broadcasting, Michael leads our vision and ensures Glow FM remains at the forefront of radio innovation.',
                'image' => 'https://ui-avatars.com/api/?name=Michael+Rodriguez&background=10b981&color=fff&size=400',
                'social' => [
                    'linkedin' => '#',
                    'twitter' => '#',
                    'email' => 'michael@glowfm.com'
                ]
            ],
            [
                'name' => 'Sarah Johnson',
                'position' => 'Program Director',
                'bio' => 'Sarah curates our diverse programming lineup, bringing fresh perspectives and ensuring quality content across all shows.',
                'image' => 'https://ui-avatars.com/api/?name=Sarah+Johnson&background=f59e0b&color=fff&size=400',
                'social' => [
                    'linkedin' => '#',
                    'twitter' => '#',
                    'email' => 'sarah@glowfm.com'
                ]
            ],
            [
                'name' => 'David Chen',
                'position' => 'Technical Director',
                'bio' => 'David oversees all technical operations, ensuring seamless broadcasting and maintaining our state-of-the-art equipment.',
                'image' => 'https://ui-avatars.com/api/?name=David+Chen&background=6366f1&color=fff&size=400',
                'social' => [
                    'linkedin' => '#',
                    'twitter' => '#',
                    'email' => 'david@glowfm.com'
                ]
            ],
            [
                'name' => 'Emily Martinez',
                'position' => 'Marketing Director',
                'bio' => 'Emily drives our brand strategy and community engagement, connecting Glow FM with audiences across all platforms.',
                'image' => 'https://ui-avatars.com/api/?name=Emily+Martinez&background=ec4899&color=fff&size=400',
                'social' => [
                    'linkedin' => '#',
                    'twitter' => '#',
                    'email' => 'emily@glowfm.com'
                ]
            ],
        ];

        // Achievements & Awards
        $this->achievements = [
            [
                'year' => '2024',
                'award' => 'Best Radio Station of the Year',
                'organization' => 'National Broadcasting Awards',
                'icon' => 'fas fa-trophy'
            ],
            [
                'year' => '2023',
                'award' => 'Excellence in Community Engagement',
                'organization' => 'Media Excellence Awards',
                'icon' => 'fas fa-award'
            ],
            [
                'year' => '2023',
                'award' => 'Top Morning Show',
                'organization' => 'Radio Industry Awards',
                'icon' => 'fas fa-medal'
            ],
            [
                'year' => '2022',
                'award' => 'Innovation in Broadcasting',
                'organization' => 'Tech in Media Awards',
                'icon' => 'fas fa-lightbulb'
            ],
            [
                'year' => '2021',
                'award' => 'Most Popular Radio Station',
                'organization' => 'Listeners\' Choice Awards',
                'icon' => 'fas fa-heart'
            ],
            [
                'year' => '2020',
                'award' => 'Outstanding Podcast Series',
                'organization' => 'Digital Media Awards',
                'icon' => 'fas fa-podcast'
            ],
        ];

        // Partners & Sponsors
        $this->partners = [
            ['name' => 'Music Corp', 'logo' => 'https://via.placeholder.com/200x80/10b981/ffffff?text=Music+Corp'],
            ['name' => 'Tech Solutions', 'logo' => 'https://via.placeholder.com/200x80/f59e0b/ffffff?text=Tech+Solutions'],
            ['name' => 'Event Masters', 'logo' => 'https://via.placeholder.com/200x80/6366f1/ffffff?text=Event+Masters'],
            ['name' => 'Sound Systems', 'logo' => 'https://via.placeholder.com/200x80/ec4899/ffffff?text=Sound+Systems'],
            ['name' => 'Media Group', 'logo' => 'https://via.placeholder.com/200x80/8b5cf6/ffffff?text=Media+Group'],
            ['name' => 'Digital Network', 'logo' => 'https://via.placeholder.com/200x80/14b8a6/ffffff?text=Digital+Network'],
        ];
    }

    public function render()
    {
        return view('livewire.page.about-page')->layout('layouts.app', [
            'title' => 'About Us - Glow FM 99.1'
        ]);
    }
}