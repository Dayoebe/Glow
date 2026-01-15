<?php

namespace App\Models\Show;

use Illuminate\Database\Eloquent\Model;


class Subscription extends Model
{
    protected $table = 'show_subscriptions';
    protected $fillable = ['user_id', 'show_id', 'notifications_enabled', 'subscribed_at'];
    protected $casts = ['notifications_enabled' => 'boolean', 'subscribed_at' => 'datetime'];
}