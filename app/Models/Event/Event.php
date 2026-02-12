<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'featured_image', 'gallery',
        'category_id', 'author_id', 'start_at', 'end_at', 'timezone',
        'venue_name', 'venue_address', 'city', 'state', 'country',
        'ticket_url', 'registration_url', 'capacity', 'price',
        'published_at', 'views', 'shares', 'is_featured', 'is_published',
        'approval_status', 'approval_reason', 'reviewed_by', 'reviewed_at',
        'allow_comments', 'meta_description', 'meta_keywords', 'tags',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'allow_comments' => 'boolean',
        'reviewed_at' => 'datetime',
        'views' => 'integer',
        'shares' => 'integer',
        'tags' => 'array',
        'gallery' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });

        static::updating(function ($event) {
            if ($event->isDirty('title') && empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(EventCategory::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(EventComment::class)->latest();
    }

    public function interactions(): HasMany
    {
        return $this->hasMany(EventInteraction::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(EventAttendance::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->where('approval_status', 'approved')
                    ->where(function ($q) {
                        $q->whereNull('published_at')
                          ->orWhere('published_at', '<=', now());
                    });
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_at', '>=', now());
    }

    public function scopePast($query)
    {
        return $query->where('start_at', '<', now());
    }

    public function scopeByCategory($query, $categorySlug)
    {
        return $query->whereHas('category', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('title', 'like', "%{$searchTerm}%")
              ->orWhere('excerpt', 'like', "%{$searchTerm}%")
              ->orWhere('content', 'like', "%{$searchTerm}%");
        });
    }

    public function scopeTrending($query, $days = 7)
    {
        return $query->withCount(['interactions as views_count' => function ($q) use ($days) {
            $q->where('type', 'view')
              ->where('created_at', '>=', now()->subDays($days));
        }])->orderBy('views_count', 'desc');
    }

    public function incrementViews(string $ipAddress, ?int $userId = null)
    {
        $recentView = $this->interactions()
            ->where('type', 'view')
            ->where('ip_address', $ipAddress)
            ->where('created_at', '>=', now()->subDay())
            ->first();

        if (!$recentView) {
            $this->increment('views');

            $this->interactions()->create([
                'user_id' => $userId,
                'ip_address' => $ipAddress,
                'type' => 'view',
                'user_agent' => request()->userAgent(),
                'referer' => request()->header('referer'),
            ]);
        }
    }

    public function trackShare(string $platform)
    {
        $this->increment('shares');

        $this->interactions()->create([
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'type' => 'share',
            'value' => $platform,
        ]);
    }

    public function toggleReaction(?int $userId, string $type)
    {
        if (!$userId) return false;

        $interaction = $this->interactions()
            ->where('user_id', $userId)
            ->where('type', 'reaction')
            ->where('value', $type)
            ->first();

        if ($interaction) {
            $interaction->delete();
            return false;
        }

        $this->interactions()->create([
            'user_id' => $userId,
            'type' => 'reaction',
            'value' => $type,
        ]);

        return true;
    }

    public function toggleBookmark(?int $userId, ?string $collection = null, ?string $notes = null)
    {
        if (!$userId) return false;

        $bookmark = $this->interactions()
            ->where('user_id', $userId)
            ->where('type', 'bookmark')
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return false;
        }

        $this->interactions()->create([
            'user_id' => $userId,
            'type' => 'bookmark',
            'collection' => $collection,
            'notes' => $notes,
        ]);

        return true;
    }

    public function getReactionCount($type)
    {
        return $this->interactions()
            ->where('type', 'reaction')
            ->where('value', $type)
            ->count();
    }

    public function getAllReactionCounts()
    {
        return [
            'love' => $this->getReactionCount('love'),
            'insightful' => $this->getReactionCount('insightful'),
            'fire' => $this->getReactionCount('fire'),
            'wow' => $this->getReactionCount('wow'),
        ];
    }

    public function isBookmarkedBy(?int $userId): bool
    {
        if (!$userId) return false;
        return $this->interactions()
            ->where('user_id', $userId)
            ->where('type', 'bookmark')
            ->exists();
    }

    public function hasReaction(?int $userId, string $type): bool
    {
        if (!$userId) return false;
        return $this->interactions()
            ->where('user_id', $userId)
            ->where('type', 'reaction')
            ->where('value', $type)
            ->exists();
    }

    public function getFormattedDateAttribute(): string
    {
        if (!$this->start_at) return '';
        if ($this->end_at && $this->start_at->toDateString() !== $this->end_at->toDateString()) {
            return $this->start_at->format('M d, Y') . ' - ' . $this->end_at->format('M d, Y');
        }
        return $this->start_at->format('M d, Y');
    }

    public function getFormattedTimeAttribute(): string
    {
        if (!$this->start_at) return '';
        $start = $this->start_at->format('g:i A');
        if ($this->end_at) {
            return $start . ' - ' . $this->end_at->format('g:i A');
        }
        return $start;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
