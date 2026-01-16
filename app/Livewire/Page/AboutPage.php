<?php

namespace App\Livewire\Page;

use App\Models\Setting;
use Livewire\Component;

class AboutPage extends Component
{
    public $aboutContent = [];

    public function mount()
    {
        $defaults = [
            'header_title' => 'About Glow FM',
            'header_subtitle' => 'Broadcasting excellence since 2010, bringing you the heartbeat of the city through music, conversation, and community connection.',
            'story_title' => 'Our Story',
            'story_paragraphs' => [
                'Glow FM 99.1 began in 2010 with a simple yet powerful vision: to create a radio station that truly reflects and celebrates our diverse community. What started as a small local broadcaster has grown into one of the region\'s most beloved and innovative radio stations.',
                'Over the past 15 years, we\'ve built a reputation for excellence in broadcasting, featuring the best music, the most engaging hosts, and the most compelling content. Our commitment to quality and community has earned us numerous awards and, most importantly, the loyalty of over 1 million monthly listeners.',
                'Today, Glow FM isn\'t just a radio station—it\'s a cultural institution. We\'re pioneers in digital broadcasting, community engagement, and innovative programming. Every day, we strive to entertain, inform, and inspire our listeners while staying true to our core values.',
            ],
            'story_badges' => [
                'Award-Winning',
                'Community-Focused',
                'Innovation Leaders',
            ],
            'mission_title' => 'Our Mission',
            'mission_body' => 'To deliver exceptional radio broadcasting that entertains, informs, and connects our community. We strive to be the voice of the people, celebrating local culture, music, and stories while maintaining the highest standards of quality and integrity in everything we do.',
            'vision_title' => 'Our Vision',
            'vision_body' => 'To be the leading radio station that shapes cultural conversations, discovers new talent, and pioneers innovative broadcasting technologies. We envision a future where Glow FM continues to be the heartbeat of our community and a model for excellence in radio worldwide.',
            'values_title' => 'Our Core Values',
            'values_subtitle' => 'The principles that guide our decisions and shape our culture',
            'values' => [
                [
                    'icon' => 'fas fa-heart',
                    'title' => 'Community First',
                    'description' => 'We put our listeners and local community at the heart of everything we do, creating content that resonates and connects.',
                    'color' => 'red',
                ],
                [
                    'icon' => 'fas fa-star',
                    'title' => 'Excellence',
                    'description' => 'We strive for excellence in broadcasting, from audio quality to content creation, delivering the best experience possible.',
                    'color' => 'yellow',
                ],
                [
                    'icon' => 'fas fa-lightbulb',
                    'title' => 'Innovation',
                    'description' => 'We embrace new technologies and creative approaches to stay ahead and provide cutting-edge entertainment.',
                    'color' => 'emerald',
                ],
                [
                    'icon' => 'fas fa-hands-helping',
                    'title' => 'Integrity',
                    'description' => 'We operate with honesty, transparency, and ethical standards in all our interactions and business practices.',
                    'color' => 'blue',
                ],
                [
                    'icon' => 'fas fa-users',
                    'title' => 'Diversity',
                    'description' => 'We celebrate diversity in music, voices, and perspectives, creating an inclusive platform for all.',
                    'color' => 'purple',
                ],
                [
                    'icon' => 'fas fa-rocket',
                    'title' => 'Passion',
                    'description' => 'Our team is driven by genuine passion for music, broadcasting, and creating memorable experiences for our audience.',
                    'color' => 'orange',
                ],
            ],
            'milestones_title' => 'Our Journey',
            'milestones_subtitle' => 'Key milestones in our story of growth and innovation',
            'milestones' => [
                [
                    'year' => '2010',
                    'title' => 'The Beginning',
                    'description' => 'Glow FM 99.1 launched with a vision to revolutionize radio broadcasting and bring fresh, engaging content to the community.',
                ],
                [
                    'year' => '2013',
                    'title' => 'Digital Expansion',
                    'description' => 'Launched our online streaming platform, mobile apps, and social media presence, reaching listeners beyond traditional radio.',
                ],
                [
                    'year' => '2017',
                    'title' => 'Award Recognition',
                    'description' => 'Won our first National Broadcasting Award for Best Radio Station, recognizing our innovative programming and community engagement.',
                ],
                [
                    'year' => '2020',
                    'title' => 'Studio Upgrade',
                    'description' => 'Invested in state-of-the-art broadcasting equipment and renovated our studios to enhance audio quality and production capabilities.',
                ],
                [
                    'year' => '2023',
                    'title' => 'Community Impact',
                    'description' => 'Reached 1 million monthly listeners and launched various community programs, charity initiatives, and local talent showcases.',
                ],
                [
                    'year' => '2025',
                    'title' => 'Innovation Leader',
                    'description' => 'Pioneered interactive broadcasting technology and became the region\'s most-listened-to radio station with cutting-edge content.',
                ],
            ],
            'team_title' => 'Meet Our Leadership',
            'team_subtitle' => 'The passionate team behind Glow FM\'s success',
            'team' => [
                [
                    'name' => 'Michael Rodriguez',
                    'position' => 'Station Manager',
                    'bio' => 'With over 20 years in broadcasting, Michael leads our vision and ensures Glow FM remains at the forefront of radio innovation.',
                    'image' => 'https://ui-avatars.com/api/?name=Michael+Rodriguez&background=10b981&color=fff&size=400',
                    'social' => [
                        'linkedin' => '#',
                        'twitter' => '#',
                        'email' => 'michael@glowfm.com',
                    ],
                ],
                [
                    'name' => 'Sarah Johnson',
                    'position' => 'Program Director',
                    'bio' => 'Sarah curates our diverse programming lineup, bringing fresh perspectives and ensuring quality content across all shows.',
                    'image' => 'https://ui-avatars.com/api/?name=Sarah+Johnson&background=f59e0b&color=fff&size=400',
                    'social' => [
                        'linkedin' => '#',
                        'twitter' => '#',
                        'email' => 'sarah@glowfm.com',
                    ],
                ],
                [
                    'name' => 'David Chen',
                    'position' => 'Technical Director',
                    'bio' => 'David oversees all technical operations, ensuring seamless broadcasting and maintaining our state-of-the-art equipment.',
                    'image' => 'https://ui-avatars.com/api/?name=David+Chen&background=6366f1&color=fff&size=400',
                    'social' => [
                        'linkedin' => '#',
                        'twitter' => '#',
                        'email' => 'david@glowfm.com',
                    ],
                ],
                [
                    'name' => 'Emily Martinez',
                    'position' => 'Marketing Director',
                    'bio' => 'Emily drives our brand strategy and community engagement, connecting Glow FM with audiences across all platforms.',
                    'image' => 'https://ui-avatars.com/api/?name=Emily+Martinez&background=ec4899&color=fff&size=400',
                    'social' => [
                        'linkedin' => '#',
                        'twitter' => '#',
                        'email' => 'emily@glowfm.com',
                    ],
                ],
            ],
            'achievements_title' => 'Awards & Recognition',
            'achievements_subtitle' => 'Honored for our commitment to excellence in broadcasting',
            'achievements' => [
                [
                    'year' => '2024',
                    'award' => 'Best Radio Station of the Year',
                    'organization' => 'National Broadcasting Awards',
                    'icon' => 'fas fa-trophy',
                ],
                [
                    'year' => '2023',
                    'award' => 'Excellence in Community Engagement',
                    'organization' => 'Media Excellence Awards',
                    'icon' => 'fas fa-award',
                ],
                [
                    'year' => '2023',
                    'award' => 'Top Morning Show',
                    'organization' => 'Radio Industry Awards',
                    'icon' => 'fas fa-medal',
                ],
                [
                    'year' => '2022',
                    'award' => 'Innovation in Broadcasting',
                    'organization' => 'Tech in Media Awards',
                    'icon' => 'fas fa-lightbulb',
                ],
                [
                    'year' => '2021',
                    'award' => 'Most Popular Radio Station',
                    'organization' => 'Listeners\' Choice Awards',
                    'icon' => 'fas fa-heart',
                ],
                [
                    'year' => '2020',
                    'award' => 'Outstanding Podcast Series',
                    'organization' => 'Digital Media Awards',
                    'icon' => 'fas fa-podcast',
                ],
            ],
            'partners_title' => 'Our Partners',
            'partners_subtitle' => 'Proud to work with industry-leading organizations',
            'partners' => [
                ['name' => 'Music Corp', 'logo' => 'https://via.placeholder.com/200x80/10b981/ffffff?text=Music+Corp'],
                ['name' => 'Tech Solutions', 'logo' => 'https://via.placeholder.com/200x80/f59e0b/ffffff?text=Tech+Solutions'],
                ['name' => 'Event Masters', 'logo' => 'https://via.placeholder.com/200x80/6366f1/ffffff?text=Event+Masters'],
                ['name' => 'Sound Systems', 'logo' => 'https://via.placeholder.com/200x80/ec4899/ffffff?text=Sound+Systems'],
                ['name' => 'Media Group', 'logo' => 'https://via.placeholder.com/200x80/8b5cf6/ffffff?text=Media+Group'],
                ['name' => 'Digital Network', 'logo' => 'https://via.placeholder.com/200x80/14b8a6/ffffff?text=Digital+Network'],
            ],
            'stats_title' => 'Glow FM By The Numbers',
            'stats_subtitle' => 'Our impact on the community in numbers',
            'stats' => [
                ['number' => '1M+', 'label' => 'Monthly Listeners'],
                ['number' => '50+', 'label' => 'Weekly Shows'],
                ['number' => '15+', 'label' => 'Years Experience'],
                ['number' => '25+', 'label' => 'Team Members'],
            ],
            'cta_title' => 'Join Our Community',
            'cta_body' => 'Become part of the Glow FM family. Whether you\'re a listener, advertiser, or potential team member, we\'d love to hear from you!',
            'cta_primary_text' => 'Contact Us',
            'cta_primary_url' => '/contact',
            'cta_secondary_text' => 'Listen Live',
            'cta_secondary_url' => '/',
        ];

        $settings = Setting::get('website.about', []);
        $this->aboutContent = array_replace_recursive($defaults, $settings);
    }

    public function render()
    {
        return view('livewire.page.about-page')->layout('layouts.app', [
            'title' => 'About Us - Glow FM 99.1'
        ]);
    }
}
