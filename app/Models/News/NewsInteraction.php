<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class NewsInteraction extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'news_id', 'user_id', 'ip_address', 'type', 'value',
        'notes', 'collection', 'user_agent', 'referer',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($interaction) {
            $interaction->created_at = now();
        });
    }

    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeViews($query)
    {
        return $query->where('type', 'view');
    }

    public function scopeReactions($query)
    {
        return $query->where('type', 'reaction');
    }

    public function scopeBookmarks($query)
    {
        return $query->where('type', 'bookmark');
    }

    public function scopeShares($query)
    {
        return $query->where('type', 'share');
    }
}