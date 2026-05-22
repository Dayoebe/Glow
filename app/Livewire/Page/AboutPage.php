<?php

namespace App\Livewire\Page;

use App\Models\Setting;
use App\Support\Seo;
use Livewire\Component;

class AboutPage extends Component
{
    public $aboutContent = [];

    public function mount()
    {
        $defaults = [
            'header_title' => 'About Glow 99.1 FM',
            'header_subtitle' => 'Glow 99.1 FM is a radio station and digital media platform in Ijapo Estate, Akure, Ondo State, Nigeria.',
            'story_title' => 'Glow 99.1 FM Akure',
            'story_paragraphs' => [
                'Glow 99.1 FM serves Akure and Ondo State with radio broadcasting, digital news, live radio, podcasts, interviews, entertainment, sports, public affairs, Yoruba programming, and community updates.',
            ],
            'story_badges' => ['Akure radio', 'Ondo State news', 'Live radio'],
            'mission_title' => 'Our Mission',
            'mission_body' => 'To inform, empower, entertain, and connect listeners through credible broadcasting and digital media rooted in community service.',
            'vision_title' => 'Our Vision',
            'vision_body' => 'To be a trusted, innovative, and community-centered media voice from Akure with content that is useful locally and relevant digitally.',
            'values_title' => 'Our Core Values',
            'values_subtitle' => 'The principles behind our station and public service.',
            'values' => [],
            'milestones_title' => 'Our Journey',
            'milestones_subtitle' => 'Key public milestones and station updates.',
            'milestones' => [],
            'team_title' => 'Meet Our Leadership',
            'team_subtitle' => 'The team behind Glow 99.1 FM.',
            'team' => [],
            'achievements_title' => '',
            'achievements_subtitle' => '',
            'achievements' => [],
            'partners_title' => '',
            'partners_subtitle' => '',
            'partners' => [],
            'stats_title' => '',
            'stats_subtitle' => '',
            'stats' => [],
            'cta_title' => '',
            'cta_body' => '',
            'cta_primary_text' => '',
            'cta_primary_url' => '',
            'cta_secondary_text' => '',
            'cta_secondary_url' => '',
        ];

        $settings = Setting::get('website.about', []);
        $this->aboutContent = array_replace_recursive($defaults, $settings);
    }

    public function render()
    {
        $description = 'About Glow 99.1 FM, a radio station, live radio, podcast, and digital news platform in Akure, Ondo State, Nigeria.';

        return view('livewire.page.about-page')->layout('layouts.app', [
            'title' => 'About Glow 99.1 FM',
            'meta_title' => 'About Glow 99.1 FM Akure',
            'meta_description' => $description,
            'canonical_url' => route('about'),
            'structured_data' => Seo::siteGraph([
                'title' => 'About Glow 99.1 FM Akure',
                'description' => $description,
                'url' => route('about'),
                'breadcrumbs' => [
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'About', 'url' => route('about')],
                ],
            ]),
        ]);
    }
}
