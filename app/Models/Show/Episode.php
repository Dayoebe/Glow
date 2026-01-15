<?php

namespace App\Models\Show;

use Illuminate\Database\Eloquent\Model;

// ===== Episode Model =====
class Episode extends Model
{
    use HasFactory;

    protected $table = 'show_episodes';

    protected $fillable = [
        'show_id', 'schedule_slot_id', 'title', 'description', 'show_notes',
        'aired_at', 'actual_duration', 'recording_url', 'guests', 'segments_aired',
        'playlist', 'status', 'is_live', 'listeners', 'peak_listeners', 'average_rating'
    ];

    protected $casts = [
        'aired_at' => 'datetime',
        'guests' => 'array',
        'segments_aired' => 'array',
        'playlist' => 'array',
        'is_live' => 'boolean',
    ];

    public function show()
    {
        return $this->belongsTo(Show::class, 'show_id');
    }

    public function scheduleSlot()
    {
        return $this->belongsTo(ScheduleSlot::class, 'schedule_slot_id');
    }

    public function guestsList()
    {
        return $this->belongsToMany(Guest::class, 'episode_guests', 'episode_id', 'guest_id')
            ->withPivot('role', 'appearance_order', 'notes')
            ->orderBy('appearance_order');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeLive($query)
    {
        return $query->where('status', 'live')->where('is_live', true);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'scheduled')
            ->where('aired_at', '>', now())
            ->orderBy('aired_at');
    }

    public function scopePast($query)
    {
        return $query->where('status', 'completed')
            ->orderBy('aired_at', 'desc');
    }

    public function markAsLive()
    {
        $this->update([
            'status' => 'live',
            'is_live' => true,
        ]);
    }

    public function markAsCompleted($actualDuration = null)
    {
        $this->update([
            'status' => 'completed',
            'is_live' => false,
            'actual_duration' => $actualDuration ?? $this->actual_duration,
        ]);
    }

    public function incrementListeners()
    {
        $this->increment('listeners');
        
        if ($this->listeners > $this->peak_listeners) {
            $this->update(['peak_listeners' => $this->listeners]);
        }
    }

    public function decrementListeners()
    {
        if ($this->listeners > 0) {
            $this->decrement('listeners');
        }
    }

    public function getFormattedDateAttribute()
    {
        return $this->aired_at->format('l, F j, Y');
    }

    public function getFormattedTimeAttribute()
    {
        return $this->aired_at->format('g:i A');
    }

    public function getIsUpcomingAttribute()
    {
        return $this->status === 'scheduled' && $this->aired_at->isFuture();
    }

    public function getIsPastAttribute()
    {
        return $this->status === 'completed';
    }
}
