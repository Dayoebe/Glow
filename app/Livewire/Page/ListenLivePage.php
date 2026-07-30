<?php

namespace App\Livewire\Page;

use App\Models\Show\ScheduleSlot;
use App\Support\Seo;
use Livewire\Component;

class ListenLivePage extends Component
{
    public function refreshSchedule(): void
    {
        // The schedule is derived in render; this targeted poll triggers a fresh WAT lookup.
    }

    public function render()
    {
        $station = Seo::station();
        $now = now('Africa/Lagos');
        $today = strtolower($now->format('l'));
        $time = $now->format('H:i:s');
        $todaySlots = ScheduleSlot::with(['show', 'oap'])
            ->active()
            ->whereHas('show', fn ($query) => $query->active())
            ->forDay($today)
            ->overlappingDates($now)
            ->orderBy('start_time')
            ->get()
            ->filter(fn ($slot) => $slot->isActiveOn($now))
            ->values();
        $currentSlot = $todaySlots->last(
            fn ($slot) => $slot->start_time <= $time && $slot->end_time > $time
        );
        $upcomingSlots = $todaySlots
            ->filter(fn ($slot) => $slot->start_time > $time)
            ->take(6)
            ->values();

        $description = 'Listen live to Glow 99.1 FM from Akure, Ondo State, Nigeria, and find today\'s public show schedule, station frequency, stream access, and contact links.';

        return view('livewire.page.listen-live-page', [
            'station' => $station,
            'currentSlot' => $currentSlot,
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
