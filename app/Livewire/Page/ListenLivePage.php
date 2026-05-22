<?php

namespace App\Livewire\Page;

use App\Models\Show\ScheduleSlot;
use App\Support\Seo;
use Livewire\Component;

class ListenLivePage extends Component
{
    public function render()
    {
        $station = Seo::station();
        $today = strtolower(now('Africa/Lagos')->format('l'));
        $upcomingSlots = ScheduleSlot::with(['show', 'oap'])
            ->active()
            ->forDay($today)
            ->orderBy('start_time')
            ->take(8)
            ->get();

        $description = 'Listen live to Glow 99.1 FM from Akure, Ondo State, Nigeria, and find today\'s public show schedule, station frequency, stream access, and contact links.';

        return view('livewire.page.listen-live-page', [
            'station' => $station,
            'upcomingSlots' => $upcomingSlots,
        ])->layout('layouts.app', [
            'title' => 'Listen Live - Glow 99.1 FM',
            'meta_title' => 'Listen Live to Glow 99.1 FM Akure',
            'meta_description' => $description,
            'canonical_url' => route('listen.live'),
            'structured_data' => Seo::siteGraph([
                'title' => 'Listen Live to Glow 99.1 FM Akure',
                'description' => $description,
                'url' => route('listen.live'),
                'breadcrumbs' => [
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'Listen Live', 'url' => route('listen.live')],
                ],
            ]),
        ]);
    }
}
