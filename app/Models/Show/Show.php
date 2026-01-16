<?php

namespace App\Models\Show;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

// ===== Show Model =====
class Show extends Model
{
    use HasFactory;

    protected $table = 'shows';

    protected $fillable = [
        'title', 'slug', 'description', 'full_description', 'cover_image',
        'promotional_images', 'category_id', 'primary_host_id', 'co_hosts',
        'format', 'content_rating', 'typical_duration', 'is_active', 'is_featured',
        'allow_on_demand', 'tags', 'sponsors', 'social_media', 'website_url',
        'total_episodes', 'total_listeners', 'average_rating'
    ];

    protected $casts = [
        'promotional_images' => 'array',
        'co_hosts' => 'array',
        'tags' => 'array',
        'sponsors' => 'array',
        'social_media' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'allow_on_demand' => 'boolean',
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

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function primaryHost()
    {
        return $this->belongsTo(OAP::class, 'primary_host_id');
    }

    public function segments()
    {
        return $this->hasMany(Segment::class, 'show_id')->orderBy('order');
    }

    public function scheduleSlots()
    {
        return $this->hasMany(ScheduleSlot::class, 'show_id');
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class, 'show_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'show_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'show_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $categorySlug)
    {
        return $query->whereHas('category', function($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }

    public function getCoHostsListAttribute()
    {
        if (!$this->co_hosts) return collect();
        return OAP::whereIn('id', $this->co_hosts)->get();
    }

    public function getCurrentEpisodeAttribute()
    {
        return $this->episodes()
            ->where('status', 'live')
            ->first();
    }

    public function getNextEpisodeAttribute()
    {
        return $this->episodes()
            ->where('status', 'scheduled')
            ->where('aired_at', '>', now())
            ->orderBy('aired_at')
            ->first();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
