<?php

namespace App\Models\Podcast;

use Illuminate\Database\Eloquent\Model;

// ===== Supporting Models =====
class Play extends Model
{
    protected $table = 'podcast_plays';
    public $timestamps = false;
    protected $fillable = [
        'episode_id', 'user_id', 'session_id', 'ip_address', 'listen_duration',
        'total_duration', 'completion_rate', 'last_position', 'device_type',
        'platform', 'user_agent', 'referer', 'country', 'started_at',
        'last_listened_at', 'completed'
    ];
    protected $casts = ['started_at' => 'datetime', 'last_listened_at' => 'datetime'];
}
