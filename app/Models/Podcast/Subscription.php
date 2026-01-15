<?php

namespace App\Models\Podcast;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table = 'podcast_subscriptions';
    protected $fillable = ['user_id', 'show_id', 'notifications_enabled', 'subscribed_at'];
    protected $casts = ['subscribed_at' => 'datetime', 'notifications_enabled' => 'boolean'];
}
