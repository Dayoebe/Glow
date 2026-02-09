<?php

namespace App\Livewire\Admin\Team;

use App\Models\Staff\StaffMember;
use App\Models\Setting;
use App\Support\StaffBirthdayTemplate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class StaffBirthdays extends Component
{
    public $rangeDays = 30;
    public $showInactive = false;
    public $search = '';
    public $email_subject = '';
    public $email_body = '';
    public $test_email = '';
    public $test_staff_id = '';
    public $force_send = false;

    protected $queryString = [
        'rangeDays' => ['except' => 30],
        'showInactive' => ['except' => false],
        'search' => ['except' => ''],
    ];

    public function mount()
    {
        $defaults = StaffBirthdayTemplate::defaults();
        $settings = Setting::get('staff_birthdays', []);
        $data = array_replace($defaults, $settings);

        $this->email_subject = (string) ($data['subject'] ?? '');
        $this->email_body = (string) ($data['message'] ?? '');

        $user = auth()->user();
        if ($user && !empty($user->email)) {
            $this->test_email = (string) $user->email;
        }
    }

    public function saveEmailSettings()
    {
        $this->validate([
            'email_subject' => 'required|string|max:255',
            'email_body' => 'required|string',
        ]);

        Setting::set('staff_birthdays', [
            'subject' => $this->email_subject,
            'message' => $this->email_body,
        ], 'staff');

        session()->flash('success', 'Birthday email template updated successfully.');
    }

    public function sendTestEmail()
    {
        $this->validate([
            'email_subject' => 'required|string|max:255',
            'email_body' => 'required|string',
            'test_email' => 'required|email',
        ]);

        $staff = $this->previewStaff ?? new StaffMember(['name' => 'Team Member']);
        $station = Setting::get('station', []);
        $stationName = (string) ($station['name'] ?? config('app.name', 'Our Station'));
        $stationFrequency = (string) ($station['frequency'] ?? '');
        $date = Carbon::today();

        $subject = StaffBirthdayTemplate::render($this->email_subject, $staff, $station, $date);
        $message = StaffBirthdayTemplate::render($this->email_body, $staff, $station, $date);

        Mail::to($this->test_email)->send(
            new \App\Mail\StaffBirthdayMail($staff, $subject, $message, $stationName, $stationFrequency)
        );

        session()->flash('success', 'Test birthday email sent to ' . $this->test_email . '.');
    }

    public function updatedTestStaffId($value)
    {
        if (!$value) {
            return;
        }

        $staff = StaffMember::query()->find($value);
        if (!$staff) {
            return;
        }

        $this->test_email = (string) ($staff->email ?? '');
        $this->resetErrorBag('test_email');
    }

    public function sendTodayEmails()
    {
        $this->validate([
            'email_subject' => 'required|string|max:255',
            'email_body' => 'required|string',
        ]);

        $date = Carbon::today();
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
            session()->flash('success', 'No staff birthdays found for today.');
            return;
        }

        $sent = 0;
        $skipped = 0;

        foreach ($staffMembers as $staff) {
            $cacheKey = 'staff_birthday_email_sent:' . $date->toDateString() . ':' . $staff->id;
            if (!$this->force_send && !Cache::add($cacheKey, true, now()->addDays(2))) {
                $skipped++;
                continue;
            }

            $subject = StaffBirthdayTemplate::render($this->email_subject, $staff, $station, $date);
            $message = StaffBirthdayTemplate::render($this->email_body, $staff, $station, $date);

            Mail::to($staff->email)->send(
                new \App\Mail\StaffBirthdayMail($staff, $subject, $message, $stationName, $stationFrequency)
            );

            Cache::put($cacheKey, true, now()->addDays(2));
            $sent++;
        }

        session()->flash('success', "Birthday emails sent: {$sent}. Skipped: {$skipped}.");
    }

    public function getPreviewStaffOptionsProperty()
    {
        return StaffMember::query()
            ->orderBy('name')
            ->get(['id', 'name', 'is_active']);
    }

    public function getPreviewStaffProperty()
    {
        if ($this->test_staff_id) {
            return StaffMember::query()
                ->with(['departmentRelation', 'teamRole'])
                ->find($this->test_staff_id);
        }

        $staff = StaffMember::query()
            ->with(['departmentRelation', 'teamRole'])
            ->where('is_active', true)
            ->whereNotNull('birth_month')
            ->whereNotNull('birth_day')
            ->orderBy('name')
            ->first();

        if ($staff) {
            return $staff;
        }

        return StaffMember::query()
            ->with(['departmentRelation', 'teamRole'])
            ->orderBy('name')
            ->first();
    }

    public function getPreviewSubjectProperty()
    {
        $staff = $this->previewStaff ?? new StaffMember(['name' => 'Team Member']);
        $station = Setting::get('station', []);
        $template = $this->email_subject ?: StaffBirthdayTemplate::defaults()['subject'];

        return StaffBirthdayTemplate::render($template, $staff, $station, Carbon::today());
    }

    public function getPreviewBodyProperty()
    {
        $staff = $this->previewStaff ?? new StaffMember(['name' => 'Team Member']);
        $station = Setting::get('station', []);
        $template = $this->email_body ?: StaffBirthdayTemplate::defaults()['message'];

        return StaffBirthdayTemplate::render($template, $staff, $station, Carbon::today());
    }

    public function getBirthdayRowsProperty()
    {
        $today = Carbon::today();

        $staff = StaffMember::query()
            ->with(['departmentRelation', 'teamRole'])
            ->when(!$this->showInactive, function ($query) {
                $query->where('is_active', true);
            })
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->whereNotNull('birth_month')
            ->whereNotNull('birth_day')
            ->get();

        return $staff->map(function (StaffMember $staffMember) use ($today) {
            $birthMonth = (int) $staffMember->birth_month;
            $birthDay = (int) $staffMember->birth_day;
            $birthYear = $staffMember->birth_year ? (int) $staffMember->birth_year : null;

            $dobDisplay = $this->formatDobDisplay($birthYear, $birthMonth, $birthDay);

            $nextBirthday = $this->resolveBirthdayForYear($today->year, $birthMonth, $birthDay);
            if ($nextBirthday->lt($today)) {
                $nextBirthday = $this->resolveBirthdayForYear($today->year + 1, $birthMonth, $birthDay);
            }

            return [
                'staff' => $staffMember,
                'dob_display' => $dobDisplay,
                'birth_year' => $birthYear,
                'next_birthday' => $nextBirthday,
                'days_until' => $today->diffInDays($nextBirthday, false),
            ];
        })->sortBy('next_birthday')->values();
    }

    public function getUpcomingBirthdaysProperty()
    {
        $today = Carbon::today();
        $range = max(1, (int) $this->rangeDays);
        $cutoff = $today->copy()->addDays($range);

        return $this->birthdayRows->filter(function (array $row) use ($cutoff) {
            return $row['next_birthday']->lte($cutoff);
        })->values();
    }

    public function getTodayBirthdaysProperty()
    {
        $today = Carbon::today();

        return $this->birthdayRows->filter(function (array $row) use ($today) {
            return $row['next_birthday']->isSameDay($today);
        })->values();
    }

    public function getMissingDobProperty()
    {
        return StaffMember::query()
            ->with(['departmentRelation', 'teamRole'])
            ->when(!$this->showInactive, function ($query) {
                $query->where('is_active', true);
            })
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->where(function ($query) {
                $query->whereNull('birth_month')
                    ->orWhereNull('birth_day');
            })
            ->orderBy('name')
            ->get();
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

    public function render()
    {
        return view('livewire.admin.team.staff-birthdays', [
            'birthdayRows' => $this->birthdayRows,
            'todayBirthdays' => $this->todayBirthdays,
            'upcomingBirthdays' => $this->upcomingBirthdays,
            'missingDob' => $this->missingDob,
            'previewStaffOptions' => $this->previewStaffOptions,
            'previewSubject' => $this->previewSubject,
            'previewBody' => $this->previewBody,
        ])->layout('layouts.admin', ['header' => 'Staff Birthdays']);
    }
}
