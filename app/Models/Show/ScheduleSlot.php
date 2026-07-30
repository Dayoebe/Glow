<?php

namespace App\Models\Show;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

// ===== Schedule Slot Model =====
class ScheduleSlot extends Model
{
    use HasFactory;

    protected $table = 'schedule_slots';

    protected $fillable = [
        'show_id', 'oap_id', 'day_of_week', 'start_time', 'end_time',
        'start_date', 'end_date', 'is_recurring', 'status', 'exceptions', 'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_recurring' => 'boolean',
        'exceptions' => 'array',
    ];

    public function show()
    {
        return $this->belongsTo(Show::class, 'show_id');
    }

    public function oap()
    {
        return $this->belongsTo(OAP::class, 'oap_id');
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class, 'schedule_slot_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForDay($query, $day)
    {
        return $query->where('day_of_week', strtolower($day));
    }

    public function scopeRecurring($query)
    {
        return $query->where('is_recurring', true);
    }

    public function scopeOverlappingDates($query, $startDate, $endDate = null)
    {
        $rangeStart = Carbon::parse($startDate)->toDateString();
        $rangeEnd = Carbon::parse($endDate ?? $startDate)->toDateString();

        return $query
            ->where(function ($query) use ($rangeEnd) {
                $query->whereNull('start_date')
                    ->orWhereDate('start_date', '<=', $rangeEnd);
            })
            ->where(function ($query) use ($rangeStart) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', $rangeStart);
            });
    }

    public function isActiveOn($date)
    {
        $date = Carbon::parse($date)->startOfDay();
        $dateString = $date->toDateString();

        if ($this->status !== 'active') {
            return false;
        }

        // Check if within date range
        if ($this->start_date && $dateString < $this->start_date->toDateString()) return false;
        if ($this->end_date && $dateString > $this->end_date->toDateString()) return false;

        // Check if date is in exceptions
        if ($this->exceptions && in_array($dateString, $this->exceptions, true)) {
            return false;
        }

        // Check day of week
        return strtolower($date->format('l')) === strtolower((string) $this->day_of_week);
    }

    public function hasConflictWith($startTime, $endTime, $dayOfWeek)
    {
        if ($this->day_of_week !== $dayOfWeek) return false;
        
        return !($endTime <= $this->start_time || $startTime >= $this->end_time);
    }

    public function getDurationAttribute()
    {
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);
        return $start->diffInMinutes($end);
    }

    public function getTimeRangeAttribute()
    {
        return Carbon::parse($this->start_time)->format('g:i A') . ' - ' . 
               Carbon::parse($this->end_time)->format('g:i A');
    }
}
