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
            'header_title' => '',
            'header_subtitle' => '',
            'story_title' => '',
            'story_paragraphs' => [],
            'story_badges' => [],
            'mission_title' => '',
            'mission_body' => '',
            'vision_title' => '',
            'vision_body' => '',
            'values_title' => '',
            'values_subtitle' => '',
            'values' => [],
            'milestones_title' => '',
            'milestones_subtitle' => '',
            'milestones' => [],
            'team_title' => '',
            'team_subtitle' => '',
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
        return view('livewire.page.about-page')->layout('layouts.app', [
            'title' => 'About Us - Glow FM 99.1'
        ]);
    }
}
