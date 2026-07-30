<?php

namespace Tests\Unit\Show;

use App\Models\Show\ScheduleSlot;
use Carbon\Carbon;
use Tests\TestCase;

final class ScheduleSlotTest extends TestCase
{
    public function test_end_date_remains_active_for_the_entire_broadcast_day(): void
    {
        $slot = $this->slot([
            'day_of_week' => 'thursday',
            'start_date' => '2026-07-01',
            'end_date' => '2026-07-30',
        ]);

        $this->assertTrue($slot->isActiveOn(Carbon::parse('2026-07-30 23:59:59', 'Africa/Lagos')));
    }

    public function test_an_exception_cancels_only_that_occurrence(): void
    {
        $slot = $this->slot([
            'day_of_week' => 'thursday',
            'exceptions' => ['2026-07-30'],
        ]);

        $this->assertFalse($slot->isActiveOn('2026-07-30'));
        $this->assertTrue($slot->isActiveOn('2026-08-06'));
    }

    public function test_inactive_out_of_range_and_wrong_weekday_slots_are_rejected(): void
    {
        $this->assertFalse($this->slot(['status' => 'inactive'])->isActiveOn('2026-07-30'));
        $this->assertFalse($this->slot(['start_date' => '2026-08-01'])->isActiveOn('2026-07-30'));
        $this->assertFalse($this->slot(['end_date' => '2026-07-29'])->isActiveOn('2026-07-30'));
        $this->assertFalse($this->slot(['day_of_week' => 'friday'])->isActiveOn('2026-07-30'));
    }

    private function slot(array $attributes = []): ScheduleSlot
    {
        return new ScheduleSlot(array_merge([
            'day_of_week' => 'thursday',
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'status' => 'active',
            'exceptions' => [],
        ], $attributes));
    }
}
