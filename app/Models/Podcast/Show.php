<?php

namespace App\Models\Podcast;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

// ===== Show Model =====
class Show extends Model
{
    use HasFactory;

    protected $table = 'podcast_shows';

    protected $fillable = [
        'title', 'slug', 'description', 'cover_image', 'host_name', 'host_id',
        'co_hosts', 'category', 'frequency', 'language', 'is_active', 'is_featured',
        'explicit', 'tags', 'rss_feed_url', 'spotify_url', 'apple_url', 'google_url',
        'total_episodes', 'total_plays', 'subscribers'
    ];

    protected $casts = [
        'co_hosts' => 'array',
        'tags' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'explicit' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($show) {
            if (empty($show->slug)) {
                $show->slug = Str::slug($show->title);
            }
        });
    }

    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class, 'show_id');
    }

    public function publishedEpisodes()
    {
        return $this->hasMany(Episode::class, 'show_id')
            ->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'show_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'show_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getLatestEpisodeAttribute()
    {
        return $this->publishedEpisodes()->latest('published_at')->first();
    }

    public function incrementSubscribers()
    {
        $this->increment('subscribers');
    }

    public function decrementSubscribers()
    {
        $this->decrement('subscribers');
    }
}
