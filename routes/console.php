<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Str;
use App\Mail\StaffBirthdayMail;
use App\Models\Career\CareerApplication;
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

Artisan::command('careers:migrate-resumes-to-private {--dry-run : Preview without writing changes} {--limit=0 : Limit applications processed} {--keep-public : Keep old public files after migration}', function () {
    $dryRun = (bool) $this->option('dry-run');
    $keepPublic = (bool) $this->option('keep-public');
    $limit = (int) $this->option('limit');

    $query = CareerApplication::query()
        ->whereNotNull('resume_path')
        ->where('resume_path', '!=', '')
        ->orderBy('id');

    if ($limit > 0) {
        $query->limit($limit);
    }

    $applications = $query->get();

    if ($applications->isEmpty()) {
        $this->info('No career applications with resume paths found.');
        return;
    }

    $stats = [
        'processed' => 0,
        'updated' => 0,
        'already_private' => 0,
        'external_skipped' => 0,
        'source_missing' => 0,
        'copy_failed' => 0,
    ];

    foreach ($applications as $application) {
        $stats['processed']++;
        $rawPath = trim((string) $application->resume_path);

        if ($rawPath === '') {
            $stats['source_missing']++;
            $this->warn("Application #{$application->id}: empty resume path.");
            continue;
        }

        // Parse old paths that might be full URLs, /storage URLs, or disk-relative.
        $candidatePath = $rawPath;
        if (Str::startsWith($rawPath, ['http://', 'https://'])) {
            $urlPath = trim((string) parse_url($rawPath, PHP_URL_PATH), '/');
            if ($urlPath === '' || (!Str::startsWith($urlPath, 'storage/') && !Str::startsWith($urlPath, 'uploads/'))) {
                $stats['external_skipped']++;
                $this->warn("Application #{$application->id}: external resume URL skipped ({$rawPath}).");
                continue;
            }
            $candidatePath = $urlPath;
        }

        if (Str::startsWith($candidatePath, 'private/careers/resumes/')) {
            if (Storage::disk('local')->exists($candidatePath)) {
                $stats['already_private']++;
                continue;
            }
        }

        if (Str::startsWith($candidatePath, '/storage/')) {
            $candidatePath = Str::after($candidatePath, '/storage/');
        } elseif (Str::startsWith($candidatePath, 'storage/')) {
            $candidatePath = Str::after($candidatePath, 'storage/');
        } elseif (Str::startsWith($candidatePath, 'public/')) {
            $candidatePath = Str::after($candidatePath, 'public/');
        }

        if (!Storage::disk('public')->exists($candidatePath)) {
            if (Storage::disk('local')->exists($candidatePath)) {
                if (!$dryRun) {
                    $application->resume_path = $candidatePath;
                    $application->save();
                }
                $stats['updated']++;
                $this->line("Application #{$application->id}: normalized existing local path.");
                continue;
            }

            $stats['source_missing']++;
            $this->warn("Application #{$application->id}: source file missing on public disk ({$candidatePath}).");
            continue;
        }

        $filename = pathinfo($candidatePath, PATHINFO_FILENAME);
        $extension = pathinfo($candidatePath, PATHINFO_EXTENSION);
        $safeName = Str::slug($filename ?: 'resume');
        $targetPath = 'private/careers/resumes/app-' . $application->id . '-' . $safeName
            . ($extension !== '' ? '.' . strtolower($extension) : '');

        if (!$dryRun) {
            $stream = Storage::disk('public')->readStream($candidatePath);
            if (!is_resource($stream)) {
                $stats['copy_failed']++;
                $this->error("Application #{$application->id}: failed to read source file.");
                continue;
            }

            $copied = Storage::disk('local')->writeStream($targetPath, $stream);
            if (is_resource($stream)) {
                fclose($stream);
            }

            if (!$copied) {
                $stats['copy_failed']++;
                $this->error("Application #{$application->id}: failed to copy file.");
                continue;
            }

            $application->resume_path = $targetPath;
            $application->save();

            if (!$keepPublic) {
                Storage::disk('public')->delete($candidatePath);
            }
        }

        $stats['updated']++;
        $this->line("Application #{$application->id}: migrated to {$targetPath}" . ($dryRun ? ' (dry-run)' : ''));
    }

    $this->newLine();
    $this->info('Migration summary:');
    foreach ($stats as $key => $value) {
        $this->line('- ' . str_replace('_', ' ', $key) . ': ' . $value);
    }
})->purpose('Move career resume files from public paths to private local storage.');
