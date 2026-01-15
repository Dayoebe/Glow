<?php

namespace App\Models\Podcast;

use Illuminate\Database\Eloquent\Model;


class Download extends Model
{
    protected $table = 'podcast_downloads';
    public $timestamps = false;
    protected $fillable = ['episode_id', 'user_id', 'ip_address', 'user_agent', 'downloaded_at'];
    protected $casts = ['downloaded_at' => 'datetime'];
}
