<?php

namespace App\Livewire;

use App\Models\ContactMessage;
use App\Models\NewsletterSubscription;
use App\Models\Setting;
use App\Models\Staff\StaffMember;
use App\Models\User;
use App\Models\Event\Event;
use App\Models\News\News;
use App\Models\Show\OAP;
use App\Models\Show\Review;
use App\Models\Show\ScheduleSlot;
use App\Models\Show\Show;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class Dashboard extends Component
{
    public $stats = [];
    public $recentActivities = [];
    public $topItems = [];
    public $upcomingShows = [];
    public $nowPlaying = [];
    public $currentShow = null;
    public $nowPlayingProgress = 0;
    public $showTimeline = [];
    public $currentDay = '';
    public $recentReviews = [];
    public $todaySchedule = [];
    public $nextBirthday = null;

    public function mount()
    {
        $user = auth()->user();
        if (!$user || (!$user->isAdmin() && !$user->isStaff())) {
            $this->redirect(route('home'), navigate: true);
            return;
        }

        $this->loadStats();
        $this->loadStream();
        $this->loadShows();
        $this->loadRecentActivities();
        $this->loadTopItems();
        $this->loadRecentReviews();
        $this->loadNextBirthday();
    }

    private function loadStats(): void
    {
        $periodDays = 30;
        $currentStart = now()->subDays($periodDays);
        $previousStart = now()->subDays($periodDays * 2);
        $previousEnd = $currentStart;

        $stats = [];

        $stats[] = $this->buildStat(
            'Total Users',
            User::count(),
            User::whereBetween('created_at', [$currentStart, now()])->count(),
            User::whereBetween('created_at', [$previousStart, $previousEnd])->count(),
            'fas fa-users',
            'emerald'
        );

        $stats[] = $this->buildStat(
            'Active Shows',
            Show::active()->count(),
            Show::active()->whereBetween('created_at', [$currentStart, now()])->count(),
            Show::active()->whereBetween('created_at', [$previousStart, $previousEnd])->count(),
            'fas fa-microphone',
            'amber'
        );

        $stats[] = $this->buildStat(
            'OAPs & Hosts',
            OAP::count(),
            OAP::whereBetween('created_at', [$currentStart, now()])->count(),
            OAP::whereBetween('created_at', [$previousStart, $previousEnd])->count(),
            'fas fa-user-tie',
            'blue'
        );

        $stats[] = $this->buildStat(
            'News Articles',
            News::count(),
            News::whereBetween('created_at', [$currentStart, now()])->count(),
            News::whereBetween('created_at', [$previousStart, $previousEnd])->count(),
            'fas fa-newspaper',
            'pink'
        );

        $this->stats = $stats;
    }

    private function buildStat(string $title, int $total, int $current, int $previous, string $icon, string $color): array
    {
        $change = '0%';
        $trend = 'neutral';

        if ($previous > 0) {
            $diff = $current - $previous;
            $percent = ($diff / $previous) * 100;
            $change = sprintf('%+.1f%%', $percent);
            $trend = $diff > 0 ? 'up' : ($diff < 0 ? 'down' : 'neutral');
        } elseif ($current > 0) {
            $change = 'New';
            $trend = 'up';
        }

        return [
            'title' => $title,
            'value' => number_format($total),
            'change' => $change,
            'trend' => $trend,
            'icon' => $icon,
            'color' => $color,
        ];
    }

    private function loadStream(): void
    {
        $station = Setting::get('station', []);
        $stream = Setting::get('stream', []);
        $streamUrl = $stream['stream_url'] ?? ($station['stream_url'] ?? '');
        $this->nowPlaying = [
            'title' => $stream['now_playing_title'] ?? 'Not set',
            'artist' => $stream['now_playing_artist'] ?? 'Not set',
            'status' => $stream['status_message'] ?? 'Status not set',
            'is_live' => $stream['is_live'] ?? false,
            'stream_url' => $streamUrl,
            'show_name' => $stream['show_name'] ?? null,
            'show_time' => $stream['show_time'] ?? null,
        ];
    }

    private function loadShows(): void
    {
        $systemSettings = Setting::get('system', []);
        $timezone = $this->resolveTimezone(
            data_get($systemSettings, 'timezone', config('app.timezone', 'UTC'))
        );
        $now = now($timezone);
        $this->currentDay = $now->format('l');
        $day = strtolower($now->format('l'));
        $time = $now->format('H:i:s');

        $slotsToday = ScheduleSlot::query()
            ->with(['show', 'oap'])
            ->active()
            ->forDay($day)
            ->orderBy('start_time')
            ->get()
            ->filter(function ($slot) use ($now) {
                return $slot->isActiveOn($now);
            })
            ->values();

        $currentSlot = $slotsToday->filter(function ($slot) use ($time) {
            return $slot->start_time <= $time && $slot->end_time > $time;
        })->last();

        $previousSlot = $slotsToday->filter(function ($slot) use ($time) {
            return $slot->end_time <= $time;
        })->last();

        $nextSlot = $slotsToday->first(function ($slot) use ($time) {
            return $slot->start_time > $time;
        });

        if ($currentSlot) {
            $this->currentShow = [
                'title' => $currentSlot->show?->title ?? 'Unknown',
                'host' => $currentSlot->oap?->name ?? 'Unknown',
                'time' => $currentSlot->time_range ?? 'Unknown',
                'status' => 'On Air',
            ];

            $start = Carbon::createFromFormat('H:i:s', $currentSlot->start_time, $timezone);
            $end = Carbon::createFromFormat('H:i:s', $currentSlot->end_time, $timezone);
            $total = $start->diffInSeconds($end);
            $elapsed = $start->diffInSeconds($now);
            $this->nowPlayingProgress = $total > 0 ? min(100, max(0, ($elapsed / $total) * 100)) : 0;
        } else {
            $stream = Setting::get('stream', []);
            $streamShowName = $stream['show_name'] ?? null;
            $streamShowHost = $stream['show_host'] ?? ($stream['now_playing_artist'] ?? null);
            $streamShowTime = $stream['show_time'] ?? null;
            $programIsUnknown = empty($streamShowName);
            $this->currentShow = [
                'title' => $streamShowName ?: 'Unknown',
                'host' => $streamShowHost ?: 'Unknown',
                'time' => $streamShowTime ?: 'Unknown',
                'status' => $programIsUnknown ? 'Unknown' : (($stream['is_live'] ?? false) ? 'Live' : 'Offline'),
            ];
            $this->nowPlayingProgress = 0;
        }

        $this->showTimeline = [
            $this->mapSlotToTimeline('Previous Show', $previousSlot),
            [
                'label' => 'Current Show',
                'title' => $this->currentShow['title'] ?? 'Unknown',
                'host' => $this->currentShow['host'] ?? 'Unknown',
                'time' => $this->currentShow['time'] ?? 'Unknown',
                'is_current' => true,
            ],
            $this->mapSlotToTimeline('Next Show', $nextSlot),
        ];

        $this->upcomingShows = $slotsToday
            ->filter(function ($slot) use ($time) {
                return $slot->start_time > $time;
            })
            ->take(3)
            ->map(function ($slot) {
                return [
                    'title' => $slot->show?->title ?? 'Unknown',
                    'host' => $slot->oap?->name ?? 'Unknown',
                    'time' => $slot->time_range ?? 'Unknown',
                ];
            })
            ->values()
            ->all();

        $this->todaySchedule = $slotsToday
            ->map(function ($slot) use ($time) {
                $isCurrent = $slot->start_time <= $time && $slot->end_time > $time;
                $isAired = $slot->end_time <= $time;
                $status = $isCurrent ? 'On Air' : ($isAired ? 'Aired' : 'Upcoming');
                return [
                    'title' => $slot->show?->title ?? 'Unknown',
                    'host' => $slot->oap?->name ?? 'Unknown',
                    'time' => $slot->time_range ?? 'Unknown',
                    'status' => $status,
                ];
            })
            ->values()
            ->all();
    }

    private function loadRecentActivities(): void
    {
        $activities = collect();

        $activities = $activities->merge($this->mapActivity(
            News::latest()->take(3)->get(),
            'News published',
            'title',
            'fas fa-newspaper',
            'emerald'
        ));

        $activities = $activities->merge($this->mapActivity(
            Event::latest()->take(3)->get(),
            'Event created',
            'title',
            'fas fa-calendar',
            'amber'
        ));

        $activities = $activities->merge($this->mapActivity(
            ContactMessage::latest()->take(3)->get(),
            'Contact message',
            'subject',
            'fas fa-comment',
            'blue'
        ));

        $activities = $activities->merge($this->mapActivity(
            NewsletterSubscription::latest()->take(3)->get(),
            'Newsletter signup',
            'email',
            'fas fa-envelope-open-text',
            'violet'
        ));

        $activities = $activities->merge($this->mapActivity(
            StaffMember::latest()->take(3)->get(),
            'Staff added',
            'name',
            'fas fa-user-plus',
            'pink'
        ));

        $this->recentActivities = $activities
            ->sortByDesc('time_raw')
            ->take(6)
            ->map(function ($activity) {
                $activity['time'] = Carbon::parse($activity['time_raw'])->diffForHumans();
                unset($activity['time_raw']);
                return $activity;
            })
            ->values()
            ->all();
    }

    private function mapActivity(Collection $items, string $title, string $field, string $icon, string $color): Collection
    {
        return $items->map(function ($item) use ($title, $field, $icon, $color) {
            return [
                'title' => $title,
                'description' => $item->{$field} ?: 'No details provided',
                'time_raw' => $item->created_at,
                'icon' => $icon,
                'color' => $color,
            ];
        });
    }

    private function loadTopItems(): void
    {
        $this->topItems = News::query()
            ->with('category')
            ->orderByDesc('views')
            ->take(5)
            ->get()
            ->map(function ($news) {
                return [
                    'title' => $news->title,
                    'subtitle' => $news->category?->name ?? 'Uncategorized',
                    'metric' => number_format($news->views ?? 0),
                ];
            })
            ->values()
            ->all();
    }

    private function loadRecentReviews(): void
    {
        $this->recentReviews = Review::with(['show', 'user'])
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($review) {
                return [
                    'show' => $review->show?->title ?? 'Unknown Show',
                    'user' => $review->user?->name ?? 'Listener',
                    'rating' => $review->rating,
                    'review' => $review->review,
                    'time' => $review->created_at?->diffForHumans() ?? '',
                ];
            })
            ->values()
            ->all();
    }

    private function loadNextBirthday(): void
    {
        $today = Carbon::today();

        $staffMembers = StaffMember::query()
            ->with(['departmentRelation', 'teamRole'])
            ->where('is_active', true)
            ->whereNotNull('birth_month')
            ->whereNotNull('birth_day')
            ->get();

        $next = null;

        foreach ($staffMembers as $staffMember) {
            $birthMonth = (int) $staffMember->birth_month;
            $birthDay = (int) $staffMember->birth_day;
            $birthYear = $staffMember->birth_year ? (int) $staffMember->birth_year : null;

            $nextBirthday = $this->resolveBirthdayForYear($today->year, $birthMonth, $birthDay);
            if ($nextBirthday->lt($today)) {
                $nextBirthday = $this->resolveBirthdayForYear($today->year + 1, $birthMonth, $birthDay);
            }

            if ($next && $nextBirthday->gte($next['next_birthday'])) {
                continue;
            }

            $daysUntil = $today->diffInDays($nextBirthday, false);
            $turning = $birthYear ? $nextBirthday->year - $birthYear : null;
            $turningDisplay = $turning !== null ? $turning . ' years' : '—';
            $turningNote = $turning !== null ? 'Age on next birthday' : 'Year not provided';

            $next = [
                'staff' => $staffMember,
                'initials' => $this->initialsFor($staffMember->name),
                'dob_display' => $this->formatDobDisplay($birthYear, $birthMonth, $birthDay),
                'next_birthday' => $nextBirthday,
                'days_until' => $daysUntil,
                'countdown' => $this->formatCountdown($daysUntil),
                'turning' => $turning,
                'turning_display' => $turningDisplay,
                'turning_note' => $turningNote,
            ];
        }

        $this->nextBirthday = $next;
    }

    private function resolveTimezone(?string $timezone): string
    {
        $timezone = trim((string) $timezone);
        if ($timezone === '' || strtoupper($timezone) === 'WAT' || strtoupper($timezone) === 'UTC') {
            return 'Africa/Lagos';
        }

        if (!in_array($timezone, timezone_identifiers_list(), true)) {
            return 'Africa/Lagos';
        }

        return $timezone;
    }

    private function resolveBirthdayForYear(int $year, int $month, int $day): Carbon
    {
        if ($month === 2 && $day === 29 && !Carbon::create($year)->isLeapYear()) {
            return Carbon::create($year, 2, 28);
        }

        return Carbon::create($year, $month, $day);
    }

    private function formatDobDisplay(?int $year, int $month, int $day): string
    {
        $displayYear = $year ?: 2000;
        $format = $year ? 'M j, Y' : 'M j';

        return Carbon::create($displayYear, $month, $day)->format($format);
    }

    private function formatCountdown(int $daysUntil): string
    {
        if ($daysUntil === 0) {
            return 'Today';
        }

        if ($daysUntil === 1) {
            return 'Tomorrow';
        }

        return $daysUntil . ' days';
    }

    private function initialsFor(?string $name): string
    {
        $name = trim((string) $name);
        if ($name === '') {
            return '';
        }

        $parts = preg_split('/\\s+/', $name, -1, PREG_SPLIT_NO_EMPTY);
        if (!$parts) {
            return '';
        }

        $first = strtoupper(substr($parts[0], 0, 1));
        $last = count($parts) > 1 ? strtoupper(substr($parts[count($parts) - 1], 0, 1)) : '';

        return $first . $last;
    }

    private function mapSlotToTimeline(string $label, ?ScheduleSlot $slot): array
    {
        return [
            'label' => $label,
            'title' => $slot?->show?->title ?? 'Unknown',
            'host' => $slot?->oap?->name ?? 'Unknown',
            'time' => $slot?->time_range ?? 'Unknown',
            'is_current' => false,
        ];
    }

    public function render()
    {
        return view('livewire.dashboard')->layout('layouts.admin', [
            'header' => 'Dashboard Overview'
        ]);
    }
}
