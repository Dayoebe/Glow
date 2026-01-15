<?php

namespace App\Models\Blog;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $table = 'blog_posts';

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'featured_image', 'gallery', 
        'video_url', 'audio_url', 'category_id', 'author_id', 'published_at', 
        'read_time', 'views', 'shares', 'is_featured', 'is_published', 
        'meta_description', 'meta_keywords', 'tags', 'series', 'series_order', 
        'allow_comments'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'allow_comments' => 'boolean',
        'views' => 'integer',
        'shares' => 'integer',
        'gallery' => 'array',
        'tags' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            if (empty($post->read_time)) {
                $post->read_time = self::calculateReadTime($post->content);
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            if ($post->isDirty('content')) {
                $post->read_time = self::calculateReadTime($post->content);
            }
        });
    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id')->latest();
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id')
                    ->where('is_approved', true)
                    ->whereNull('parent_id')
                    ->latest();
    }

    public function interactions(): HasMany
    {
        return $this->hasMany(Interaction::class, 'post_id');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->where('published_at', '<=', now());
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

    public function scopeBySeries($query, $series)
    {
        return $query->where('series', $series)->orderBy('series_order');
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
            'ip_address' => request()->ip(),
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
            'ip_address' => request()->ip(),
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
            'clap' => $this->getReactionCount('clap'),
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
        return $this->comments()->where('is_approved', true)->count();
    }

    public function getHasMultimediaAttribute(): bool
    {
        return !empty($this->video_url) || !empty($this->audio_url) || !empty($this->gallery);
    }

    public function getExcerptPreviewAttribute(): string
    {
        return Str::limit($this->excerpt, 150);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
