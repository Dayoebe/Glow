<?php

namespace App\Livewire\Page;

use App\Support\Seo;
use Livewire\Component;

class AdvertisePage extends Component
{
    public function render()
    {
        $station = Seo::station();
        $description = 'Advertising and media partnership opportunities with Glow 99.1 FM Akure, including radio spots, sponsored programs, interviews, live coverage, jingles, social media promotion, and Glow TV packages.';

        return view('livewire.page.advertise-page', [
            'station' => $station,
        ])->layout('layouts.app', [
            'title' => 'Advertise With Glow 99.1 FM',
            'meta_title' => 'Advertise With Glow 99.1 FM Akure',
            'meta_description' => $description,
            'canonical_url' => route('advertise'),
            'structured_data' => Seo::siteGraph([
                'title' => 'Advertise With Glow 99.1 FM Akure',
                'description' => $description,
                'url' => route('advertise'),
                'breadcrumbs' => [
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'Advertise', 'url' => route('advertise')],
                ],
            ]),
        ]);
    }
}
