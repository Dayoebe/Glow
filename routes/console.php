<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;
use App\Mail\StaffBirthdayMail;
use App\Models\Setting;
use App\Models\Show\ScheduleSlot;
use App\Models\Staff\StaffMember;
use App\Services\FcmService;
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

Artisan::command('push:now-playing', function () {
    $stream = Setting::get('stream', []);
    if (!is_array($stream)) {
        $stream = [];
    }

    $title = trim((string) ($stream['now_playing_title'] ?? $stream['show_name'] ?? ''));
    if ($title === '') {
        $this->info('No now playing title.');
        return;
    }

    $artist = trim((string) ($stream['now_playing_artist'] ?? ''));
    $status = trim((string) ($stream['status_message'] ?? ''));
    $signature = strtolower($title . '|' . $artist . '|' . $status);

    $cacheKey = 'push_now_playing_last';
    if (Cache::get($cacheKey) === $signature) {
        $this->info('Now playing unchanged.');
        return;
    }

    Cache::put($cacheKey, $signature, now()->addHours(6));

    $body = $artist !== '' ? "{$title} — {$artist}" : $title;
    if ($status !== '') {
        $body = "{$body} · {$status}";
    }

    $data = [
        'type' => 'now_playing',
        'title' => $title,
        'artist' => $artist ?: null,
        'stream_url' => $stream['stream_url'] ?? null,
    ];

    $result = app(FcmService::class)->sendToTopic('now_playing', 'Now Playing', $body, $data);

    $this->info($result['ok'] ? 'Now playing push sent.' : 'Now playing push failed.');
})->purpose('Send a now playing push notification when the track changes.');

Artisan::command('push:show-starting', function () {
    $timezone = config('app.timezone');
    $now = Carbon::now($timezone);
    $windowEnd = $now->copy()->addMinutes(10);
    $day = strtolower($now->format('l'));

    $slots = ScheduleSlot::with('show')
        ->active()
        ->forDay($day)
        ->get()
        ->filter(fn ($slot) => $slot->isActiveOn($now));

    $sent = 0;

    foreach ($slots as $slot) {
        if (!$slot->show) {
            continue;
        }

        $start = Carbon::parse($now->format('Y-m-d') . ' ' . $slot->start_time, $timezone);
        if (!$start->between($now, $windowEnd, true)) {
            continue;
        }

        $cacheKey = 'push_show_start:' . $slot->id . ':' . $start->format('Y-m-d H:i');
        if (!Cache::add($cacheKey, true, $now->copy()->addHours(6))) {
            continue;
        }

        $title = 'Show Starting';
        $body = $slot->show->title . ' starts at ' . $start->format('g:i A');
        $data = [
            'type' => 'show',
            'slug' => $slot->show->slug,
            'start_time' => $start->toDateTimeString(),
        ];

        $result = app(FcmService::class)->sendToTopic('shows', $title, $body, $data, $slot->show->cover_image);
        if ($result['ok']) {
            $sent++;
        }
    }

    $this->info("Show starting pushes sent: {$sent}.");
})->purpose('Notify listeners when a show is about to start.');

Schedule::command('push:now-playing')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->timezone(config('app.timezone'));

Schedule::command('push:show-starting')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->timezone(config('app.timezone'));
