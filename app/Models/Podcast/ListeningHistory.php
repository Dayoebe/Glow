<?php

namespace App\Models\Podcast;

use Illuminate\Database\Eloquent\Model;



class ListeningHistory extends Model
{
    protected $table = 'podcast_listening_history';
    protected $fillable = ['user_id', 'episode_id', 'position', 'completed', 'last_listened_at'];
    protected $casts = ['completed' => 'boolean', 'last_listened_at' => 'datetime'];
}