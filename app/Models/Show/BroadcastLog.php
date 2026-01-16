<?php

namespace App\Models\Show;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// ===== Broadcast Log Model =====
class BroadcastLog extends Model
{
    use HasFactory;

    protected $table = 'broadcast_logs';

    protected $fillable = [
        'episode_id', 'show_id', 'start_time', 'end_time', 'duration',
        'type', 'content_description', 'metadata'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'metadata' => 'array',
    ];

    public function episode()
    {
        return $this->belongsTo(Episode::class);
    }

    public function show()
    {
        return $this->belongsTo(Show::class);
    }

    public static function logBroadcast($showId, $episodeId, $startTime, $endTime, $type = 'show')
    {
        return static::create([
            'show_id' => $showId,
            'episode_id' => $episodeId,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration' => Carbon::parse($startTime)->diffInSeconds($endTime),
            'type' => $type,
            'content_description' => Show::find($showId)->title ?? 'Unknown',
        ]);
    }
}
