<?php

namespace Tests\Feature;

use App\Livewire\Page\ShowPage;
use App\Models\Show\Category;
use App\Models\Show\ScheduleSlot;
use App\Models\Show\Show;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowPageScheduleSortingTest extends TestCase
{
    use RefreshDatabase;

    public function test_shows_are_sorted_from_monday_to_sunday_by_default(): void
    {
        $category = Category::create([
            'name' => 'Talk',
            'slug' => 'talk',
        ]);

        $shows = collect([
            'Sunday Show' => 'sunday',
            'Tuesday Show' => 'tuesday',
            'Monday Show' => 'monday',
        ])->mapWithKeys(function (string $day, string $title) use ($category) {
            $show = Show::create([
                'title' => $title,
                'description' => $title . ' description',
                'category_id' => $category->id,
            ]);

            ScheduleSlot::create([
                'show_id' => $show->id,
                'day_of_week' => $day,
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
                'status' => 'active',
            ]);

            return [$title => $show];
        });

        $unscheduled = Show::create([
            'title' => 'Unscheduled Show',
            'description' => 'No airtime yet',
            'category_id' => $category->id,
        ]);

        $component = app(ShowPage::class);

        $this->assertSame('schedule', $component->sortBy);
        $this->assertSame(
            ['Monday Show', 'Tuesday Show', 'Sunday Show', $unscheduled->title],
            $component->shows->pluck('title')->all()
        );
    }
}
