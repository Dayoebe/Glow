<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class News extends Model
{
    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'featured_image', 'gallery', 'video_url',
        'category_id', 'author_id', 'published_at', 'read_time', 'views', 'likes', 'shares',
        'raw_views',
        'is_featured', 'featured_position', 'is_published', 'breaking', 'breaking_until',
        'approval_status', 'approval_reason', 'reviewed_by', 'reviewed_at',
        'meta_description', 'meta_keywords', 'tags',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'breaking_until' => 'datetime',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'reviewed_at' => 'datetime',
        'views' => 'integer',
        'raw_views' => 'integer',
        'likes' => 'integer',
        'shares' => 'integer',
        'tags' => 'array',
        'gallery' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
            if (empty($news->read_time)) {
                $news->read_time = self::calculateReadTime($news->content);
            }
        });

        static::updating(function ($news) {
            if ($news->isDirty('title') && empty($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
            if ($news->isDirty('content')) {
                $news->read_time = self::calculateReadTime($news->content);
            }
        });
    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(NewsCategory::class, 'category_id');
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
        return $this->hasMany(NewsComment::class)->latest();
    }

    public function interactions(): HasMany
    {
        return $this->hasMany(NewsInteraction::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->where('approval_status', 'approved')
                    ->where('published_at', '<=', now());
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeBreaking($query)
    {
        return $query->where('breaking', '!=', 'no')
                    ->where(function($q) {
                        $q->whereNull('breaking_until')
                          ->orWhere('breaking_until', '>=', now());
                    });
    }

    public function scopeByCategory($query, $categorySlug)
    {
        return $query->whereHas('category', function($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function($q) use ($searchTerm) {
            $q->where('title', 'like', "%{$searchTerm}%")
              ->orWhere('excerpt', 'like', "%{$searchTerm}%")
              ->orWhere('content', 'like', "%{$searchTerm}%");
        });
    }

    public function scopeTrending($query, $days = 7)
    {
        return $query->withCount(['interactions as views_count' => function($q) use ($days) {
            $q->where('type', 'view')
              ->where('created_at', '>=', now()->subDays($days));
        }])->orderBy('views_count', 'desc');
    }

    // Helper Methods
    public static function calculateReadTime($content): string
    {
        $wordCount = str_word_count(strip_tags($content));
        $minutes = ceil($wordCount / 200);
        return $minutes . ' min read';
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

    public function incrementRawView(): void
    {
        $this->increment('raw_views');
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

    // Open Graph / Social Sharing Helper Methods
    public function getShareDescription(): string
    {
        $description = trim($this->excerpt ?? '');
        if (empty($description)) {
            $description = Str::limit(strip_tags($this->content ?? ''), 180);
        }
        return $description;
    }

    public function getShareImage(): string
    {
        $image = $this->featured_image ?? '';
        
        // Ensure absolute URL
        if (!empty($image) && !Str::startsWith($image, ['http://', 'https://'])) {
            $image = url($image);
        }
        
        return $image;
    }

    public function getShareUrl(): string
    {
        return route('news.show', $this->slug);
    }

    public function getFacebookShareUrl(): string
    {
        return 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($this->getShareUrl());
    }

    public function getTwitterShareUrl(): string
    {
        $text = $this->title . ' - ' . $this->getShareDescription();
        return 'https://x.com/intent/post?text=' . urlencode($text . ' ' . $this->getShareUrl());
    }

    public function getLinkedInShareUrl(): string
    {
        return 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($this->getShareUrl());
    }

    public function getWhatsAppShareUrl(): string
    {
        $text = $this->title . ' - ' . $this->getShareDescription();
        return 'https://wa.me/?text=' . urlencode($text . ' ' . $this->getShareUrl());
    }

    // Accessors
    public function getFormattedPublishedDateAttribute(): string
    {
        return $this->published_at?->format('M d, Y') ?? '';
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->published_at?->diffForHumans() ?? '';
    }

    public function getCommentsCountAttribute(): int
    {
        return $this->comments()->approved()->count();
    }

    public function getIsBreakingAttribute(): bool
    {
        if ($this->breaking === 'no') return false;
        if (!$this->breaking_until) return true;
        return $this->breaking_until >= now();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
