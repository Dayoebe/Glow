<?php

namespace App\Livewire\Page;

use App\Models\Show\ScheduleSlot;
use App\Support\Seo;
use Livewire\Component;

class SchedulePage extends Component
{
    public $scheduleByDay = [];

    public function mount()
    {
        $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
        $slots = ScheduleSlot::with(['show', 'oap'])
            ->active()
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        $this->scheduleByDay = collect($days)->mapWithKeys(function ($day) use ($slots) {
            return [$day => $slots->get($day, collect())];
        });
    }

    public function render()
    {
        $description = 'Crawlable weekly radio schedule for Glow 99.1 FM Akure, with program names, show times, hosts, and WAT broadcast times.';
        $scheduleItems = collect($this->scheduleByDay)
            ->flatten(1)
            ->filter()
            ->take(50)
            ->values()
            ->map(fn ($slot, $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $slot->show?->title,
                'url' => $slot->show?->slug ? route('shows.show', $slot->show->slug) : route('schedule'),
                'description' => ucfirst($slot->day_of_week) . ' ' . $slot->time_range . ' WAT' . ($slot->oap?->name ? ' with ' . $slot->oap->name : ''),
            ])
            ->all();

        return view('livewire.page.schedule-page', [
            'scheduleByDay' => $this->scheduleByDay,
        ])->layout('layouts.app', [
            'title' => 'Weekly Schedule - Glow 99.1 FM',
            'meta_title' => 'Glow 99.1 FM Weekly Schedule',
            'meta_description' => $description,
            'canonical_url' => route('schedule'),
            'structured_data' => Seo::siteGraph([
                'title' => 'Glow 99.1 FM Weekly Schedule',
                'description' => $description,
                'url' => route('schedule'),
                'breadcrumbs' => [
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'Schedule', 'url' => route('schedule')],
                ],
                'extra' => [
                    [
                        '@type' => 'ItemList',
                        '@id' => route('schedule') . '#schedule-list',
                        'name' => 'Glow 99.1 FM Weekly Schedule',
                        'itemListElement' => $scheduleItems,
                    ],
                ],
            ]),
        ]);
    }
}
