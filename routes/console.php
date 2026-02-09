<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;
use App\Mail\StaffBirthdayMail;
use App\Models\Setting;
use App\Models\Staff\StaffMember;
use App\Support\StaffBirthdayTemplate;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('staff:send-birthday-emails {--date=}', function () {
    $dateInput = $this->option('date');
    $date = $dateInput ? Carbon::parse($dateInput) : Carbon::today();

    $settings = array_replace(StaffBirthdayTemplate::defaults(), Setting::get('staff_birthdays', []));
    $station = Setting::get('station', []);
    $stationName = (string) ($station['name'] ?? config('app.name', 'Our Station'));
    $stationFrequency = (string) ($station['frequency'] ?? '');

    $query = StaffMember::query()
        ->with(['departmentRelation', 'teamRole'])
        ->where('is_active', true)
        ->whereNotNull('birth_month')
        ->whereNotNull('birth_day')
        ->whereNotNull('email')
        ->where('email', '!=', '');

    $query->where(function ($query) use ($date) {
        $query->where('birth_month', $date->month)
            ->where('birth_day', $date->day);

        if ($date->month === 2 && $date->day === 28 && !$date->isLeapYear()) {
            $query->orWhere(function ($subQuery) {
                $subQuery->where('birth_month', 2)
                    ->where('birth_day', 29);
            });
        }
    });

    $staffMembers = $query->get();

    if ($staffMembers->isEmpty()) {
        $this->info('No staff birthdays found for ' . $date->toDateString() . '.');
        return;
    }

    $sent = 0;
    $skipped = 0;

    foreach ($staffMembers as $staff) {
        $cacheKey = 'staff_birthday_email_sent:' . $date->toDateString() . ':' . $staff->id;
        if (!Cache::add($cacheKey, true, now()->addDays(2))) {
            $skipped++;
            continue;
        }

        $subject = StaffBirthdayTemplate::render((string) $settings['subject'], $staff, $station, $date);
        $message = StaffBirthdayTemplate::render((string) $settings['message'], $staff, $station, $date);

        Mail::to($staff->email)->send(
            new StaffBirthdayMail($staff, $subject, $message, $stationName, $stationFrequency)
        );

        $sent++;
    }

    $this->info("Birthday emails sent: {$sent}. Skipped: {$skipped}.");
})->purpose('Send automated birthday emails to active staff');

Schedule::command('staff:send-birthday-emails')
    ->dailyAt('07:00')
    ->timezone(config('app.timezone'));
