<?php

namespace App\Livewire\Admin\Show;

use App\Models\Show\OAP;
use App\Models\Show\ScheduleSlot;
use App\Models\Show\Show;
use Livewire\Component;

class ScheduleForm extends Component
{
    public $slotId = null;
    public $isEditing = false;

    public $schedule_show_id = '';
    public $schedule_oap_id = '';
    public $schedule_recurrence_type = 'weekly';
    public $schedule_day_of_week = 'monday';
    public $schedule_days = [];
    public $schedule_start_time = '';
    public $schedule_end_time = '';
    public $schedule_start_date = '';
    public $schedule_end_date = '';
    public $schedule_is_recurring = true;
    public $schedule_status = 'active';
    public $schedule_notes = '';
    public $conflictWarnings = [];

    public $allShows = [];
    public $allOaps = [];

    public $daysOfWeek = [
        'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday',
    ];

    protected function rules()
    {
        $rules = [
            'schedule_show_id' => 'required|exists:shows,id',
            'schedule_start_time' => 'required|date_format:H:i',
            'schedule_end_time' => 'required|date_format:H:i|after:schedule_start_time',
            'schedule_start_date' => 'nullable|date',
            'schedule_end_date' => 'nullable|date|after_or_equal:schedule_start_date',
            'schedule_is_recurring' => 'boolean',
            'schedule_status' => 'required',
            'schedule_notes' => 'nullable|string',
        ];

        if (!$this->isEditing) {
            $rules['schedule_recurrence_type'] = 'required|in:daily,weekly,selected';
            if ($this->schedule_recurrence_type === 'weekly') {
                $rules['schedule_day_of_week'] = 'required|in:' . implode(',', $this->daysOfWeek);
            }
            if ($this->schedule_recurrence_type === 'selected') {
                $rules['schedule_days'] = 'required|array|min:1';
                $rules['schedule_days.*'] = 'in:' . implode(',', $this->daysOfWeek);
            }
        } else {
            $rules['schedule_day_of_week'] = 'required|in:' . implode(',', $this->daysOfWeek);
        }

        return $rules;
    }

    public function mount($slotId = null)
    {
        $this->allShows = Show::orderBy('title')->get();
        $this->allOaps = OAP::active()->get();

        if ($slotId) {
            $slot = ScheduleSlot::findOrFail($slotId);
            $this->slotId = $slot->id;
            $this->isEditing = true;
            $this->schedule_show_id = $slot->show_id;
            $this->schedule_oap_id = $slot->oap_id;
            $this->schedule_day_of_week = $slot->day_of_week;
            $this->schedule_recurrence_type = 'weekly';
            $this->schedule_days = [$slot->day_of_week];
            $this->schedule_start_time = $slot->start_time;
            $this->schedule_end_time = $slot->end_time;
            $this->schedule_start_date = $slot->start_date?->format('Y-m-d') ?? '';
            $this->schedule_end_date = $slot->end_date?->format('Y-m-d') ?? '';
            $this->schedule_is_recurring = $slot->is_recurring;
            $this->schedule_status = $slot->status;
            $this->schedule_notes = $slot->notes;
        }

        $this->updateConflictWarnings();
    }

    public function updatedScheduleRecurrenceType($value)
    {
        if ($value === 'daily') {
            $this->schedule_days = $this->daysOfWeek;
        } elseif ($value === 'selected' && empty($this->schedule_days)) {
            $this->schedule_days = [];
        } elseif ($value === 'weekly') {
            $this->schedule_days = [];
        }

        $this->updateConflictWarnings();
    }

    public function updated($name, $value)
    {
        if (str_starts_with($name, 'schedule_')) {
            $this->updateConflictWarnings();
        }
    }

    private function previewDays(): array
    {
        if ($this->schedule_recurrence_type === 'daily') {
            return $this->daysOfWeek;
        }

        if ($this->schedule_recurrence_type === 'selected') {
            return array_values(array_unique($this->schedule_days));
        }

        return [$this->schedule_day_of_week];
    }

    private function updateConflictWarnings(): void
    {
        $this->conflictWarnings = [];

        if (empty($this->schedule_start_time) || empty($this->schedule_end_time)) {
            return;
        }

        $days = $this->previewDays();
        if (empty($days)) {
            return;
        }

        foreach ($days as $day) {
            $conflictQuery = ScheduleSlot::with('show')
                ->where('day_of_week', $day);

            if ($this->isEditing) {
                $conflictQuery->where('id', '!=', $this->slotId);
            }

            $timeConflict = $conflictQuery->get()->first(function ($slot) use ($day) {
                return $slot->hasConflictWith(
                    $this->schedule_start_time,
                    $this->schedule_end_time,
                    $day
                );
            });

            if ($timeConflict) {
                $showTitle = $timeConflict->show?->title ?? 'another show';
                $this->conflictWarnings[] = "Time conflict on " . ucfirst($day) . " with {$showTitle}.";
            }

            if ($this->schedule_oap_id) {
                $oapConflict = ScheduleSlot::with('show')
                    ->where('oap_id', $this->schedule_oap_id)
                    ->where('day_of_week', $day)
                    ->when($this->isEditing, function ($query) {
                        $query->where('id', '!=', $this->slotId);
                    })
                    ->get()
                    ->first(function ($slot) use ($day) {
                        return $slot->hasConflictWith(
                            $this->schedule_start_time,
                            $this->schedule_end_time,
                            $day
                        );
                    });

                if ($oapConflict) {
                    $showTitle = $oapConflict->show?->title ?? 'another show';
                    $this->conflictWarnings[] = "OAP conflict on " . ucfirst($day) . " with {$showTitle}.";
                }
            }
        }
    }

    public function save()
    {
        $this->validate();

        $days = $this->resolveDays();

        foreach ($days as $day) {
            $conflictQuery = ScheduleSlot::where('day_of_week', $day);
            if ($this->isEditing) {
                $conflictQuery->where('id', '!=', $this->slotId);
            }

            $timeConflict = $conflictQuery->get()->first(function ($slot) use ($day) {
                return $slot->hasConflictWith(
                    $this->schedule_start_time,
                    $this->schedule_end_time,
                    $day
                );
            });

            if ($timeConflict) {
                $this->addError('schedule_start_time', 'This slot conflicts with another scheduled show.');
                return;
            }

            if ($this->schedule_oap_id) {
                $oapConflict = ScheduleSlot::where('oap_id', $this->schedule_oap_id)
                    ->where('day_of_week', $day)
                    ->when($this->isEditing, function ($query) {
                        $query->where('id', '!=', $this->slotId);
                    })
                    ->get()
                    ->first(function ($slot) use ($day) {
                        return $slot->hasConflictWith(
                            $this->schedule_start_time,
                            $this->schedule_end_time,
                            $day
                        );
                    });

                if ($oapConflict) {
                    $this->addError('schedule_oap_id', 'Selected OAP is already booked for this time.');
                    return;
                }
            }
        }

        $baseData = [
            'show_id' => $this->schedule_show_id,
            'oap_id' => $this->schedule_oap_id ?: null,
            'start_time' => $this->schedule_start_time,
            'end_time' => $this->schedule_end_time,
            'start_date' => $this->schedule_start_date ?: null,
            'end_date' => $this->schedule_end_date ?: null,
            'is_recurring' => $this->schedule_is_recurring,
            'status' => $this->schedule_status,
            'notes' => $this->schedule_notes ?: null,
        ];

        if ($this->isEditing && $this->schedule_recurrence_type === 'weekly') {
            ScheduleSlot::findOrFail($this->slotId)->update($baseData + [
                'day_of_week' => $this->schedule_day_of_week,
            ]);
            $message = 'Schedule updated successfully.';
        } else {
            if ($this->isEditing) {
                ScheduleSlot::findOrFail($this->slotId)->delete();
            }

            foreach ($days as $day) {
                ScheduleSlot::create($baseData + ['day_of_week' => $day]);
            }
            $message = $this->isEditing ? 'Schedule updated successfully.' : 'Schedule created successfully.';
        }

        return redirect()
            ->route('admin.shows.schedule')
            ->with('success', $message);
    }

    private function resolveDays(): array
    {
        if ($this->isEditing) {
            return [$this->schedule_day_of_week];
        }

        if ($this->schedule_recurrence_type === 'daily') {
            return $this->daysOfWeek;
        }

        if ($this->schedule_recurrence_type === 'selected') {
            return array_values(array_unique($this->schedule_days));
        }

        return [$this->schedule_day_of_week];
    }

    public function render()
    {
        return view('livewire.admin.show.schedule-form')
            ->layout('layouts.admin', [
                'header' => $this->isEditing ? 'Edit Schedule Slot' : 'Add Schedule Slot',
            ]);
    }
}
