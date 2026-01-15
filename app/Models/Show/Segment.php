<?php

namespace App\Models\Show;

use Illuminate\Database\Eloquent\Model;

// ===== Show Segment Model =====
class Segment extends Model
{
    use HasFactory;

    protected $table = 'show_segments';

    protected $fillable = [
        'show_id', 'title', 'description', 'start_minute', 'duration', 'type', 'order', 'notes'
    ];

    protected $casts = ['notes' => 'array'];

    public function show()
    {
        return $this->belongsTo(Show::class, 'show_id');
    }

    public function getEndMinuteAttribute()
    {
        return $this->start_minute + $this->duration;
    }

    public function getTimeRangeAttribute()
    {
        $start = sprintf('%02d:%02d', floor($this->start_minute / 60), $this->start_minute % 60);
        $end = sprintf('%02d:%02d', floor($this->end_minute / 60), $this->end_minute % 60);
        return "{$start} - {$end}";
    }
}
