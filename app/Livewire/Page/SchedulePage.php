<?php

namespace App\Livewire\Page;

use App\Models\Show\ScheduleSlot;
use App\Support\Seo;
use Livewire\Attributes\Url;
use Livewire\Component;

class SchedulePage extends Component
{
    private const DAYS = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    public $scheduleByDay = [];

    #[Url(as: 'day', except: '')]
    public string $selectedDay = '';

    public function mount()
    {
        $days = self::DAYS;
        $dayNumbers = array_flip($days);
        $today = now('Africa/Lagos')->startOfDay();
        $todayIndex = (int) $today->format('N') - 1;

        if (! in_array($this->selectedDay, $days, true)) {
            $this->selectedDay = strtolower($today->format('l'));
        }

        $occurrenceDates = collect($days)->mapWithKeys(function ($day) use ($dayNumbers, $today, $todayIndex) {
            $dayOffset = ($dayNumbers[$day] - $todayIndex + 7) % 7;

            return [$day => $today->copy()->addDays($dayOffset)];
        });

        $slots = ScheduleSlot::with(['show', 'oap'])
            ->active()
            ->whereHas('show', fn ($query) => $query->active())
            ->overlappingDates($today, $today->copy()->addDays(6))
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        $this->scheduleByDay = collect($days)->mapWithKeys(function ($day) use ($slots, $occurrenceDates) {
            return [
                $day => $slots->get($day, collect())
                    ->filter(fn ($slot) => $slot->isActiveOn($occurrenceDates[$day]))
                    ->values(),
            ];
        });
    }

    public function render()
    {
        $activeSlots = collect($this->scheduleByDay[$this->selectedDay] ?? []);
        $isExplicitDay = request()->filled('day') && in_array(request()->query('day'), self::DAYS, true);
        $canonicalUrl = $isExplicitDay
            ? Seo::canonicalUrl(route('schedule'), ['day' => $this->selectedDay])
            : route('schedule');
        $dayLabel = ucfirst($this->selectedDay);
        $description = $dayLabel.' radio schedule for Glow 99.1 FM Akure, with programme names, show times, hosts, and WAT broadcast times.';
        $scheduleItems = $activeSlots
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
            'activeSlots' => $activeSlots,
        ])->layout('layouts.app', [
            'title' => $dayLabel.' Schedule - Glow 99.1 FM',
            'meta_title' => $dayLabel.' Radio Schedule | Glow 99.1 FM',
            'meta_description' => $description,
            'canonical_url' => $canonicalUrl,
            'structured_data' => Seo::siteGraph([
                'title' => $dayLabel.' Radio Schedule | Glow 99.1 FM',
                'description' => $description,
                'url' => $canonicalUrl,
                'breadcrumbs' => [
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'Schedule', 'url' => route('schedule')],
                ],
                'extra' => [
                    [
                        '@type' => 'ItemList',
                        '@id' => $canonicalUrl.'#schedule-list',
                        'name' => $dayLabel.' programmes on Glow 99.1 FM',
                        'itemListElement' => $scheduleItems,
                    ],
                ],
            ]),
        ]);
    }
}
