<?php

namespace App\Models\Podcast;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Episode extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'podcast_episodes';

    protected $fillable = [
        'show_id', 'title', 'slug', 'description', 'show_notes', 'cover_image',
        'audio_file', 'audio_format', 'video_url', 'video_type', 'duration', 'file_size', 
        'episode_number', 'season_number', 'episode_type', 'published_at', 'status', 
        'is_featured', 'explicit', 'guests', 'chapters', 'transcript', 'plays', 'downloads',
        'shares', 'average_listen_duration', 'tags',
        'spotify_url', 'apple_url', 'youtube_music_url', 'audiomack_url', 
        'soundcloud_url', 'custom_links'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'explicit' => 'boolean',
        'guests' => 'array',
        'chapters' => 'array',
        'transcript' => 'array',
        'tags' => 'array',
        'custom_links' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($episode) {
            if (empty($episode->slug)) {
                $episode->slug = Str::slug($episode->title);
            }
        });
    }

    // Relationships
    public function show()
    {
        return $this->belongsTo(Show::class, 'show_id');
    }

    public function plays()
    {
        return $this->hasMany(Play::class, 'episode_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'episode_id')->whereNull('parent_id')->where('is_approved', true);
    }

    public function allComments()
    {
        return $this->hasMany(Comment::class, 'episode_id');
    }

    public function listeningHistory()
    {
        return $this->hasMany(ListeningHistory::class, 'episode_id');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeBySeason($query, $season)
    {
        return $query->where('season_number', $season);
    }

    public function scopeByShow($query, $showId)
    {
        return $query->where('show_id', $showId);
    }

    // Analytics & Tracking
    public function trackPlay($userId = null, $duration = 0, $position = 0)
    {
        $sessionId = session()->getId() . '-' . now()->timestamp;
        
        Play::create([
            'episode_id' => $this->id,
            'user_id' => $userId,
            'session_id' => $sessionId,
            'ip_address' => request()->ip(),
            'listen_duration' => $duration,
            'total_duration' => $this->duration,
            'completion_rate' => $this->duration > 0 ? ($duration / $this->duration) * 100 : 0,
            'last_position' => $position,
            'device_type' => $this->detectDeviceType(),
            'platform' => 'web',
            'user_agent' => request()->userAgent(),
            'started_at' => now(),
            'last_listened_at' => now(),
            'completed' => $duration >= ($this->duration * 0.9),
        ]);

        $this->increment('plays');
        $this->show->increment('total_plays');
    }

    public function trackDownload()
    {
        Download::create([
            'episode_id' => $this->id,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'downloaded_at' => now(),
        ]);

        $this->increment('downloads');
    }

    // Accessors
    public function getFormattedDurationAttribute()
    {
        $hours = floor($this->duration / 3600);
        $minutes = floor(($this->duration % 3600) / 60);
        $seconds = $this->duration % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }
        return sprintf('%d:%02d', $minutes, $seconds);
    }

    public function getFileSizeFormattedAttribute()
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = $this->file_size;
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getPublishedDateAttribute()
    {
        return $this->published_at ? $this->published_at->format('M d, Y') : 'Not published';
    }

    // Helper to get YouTube video ID
    public function getYoutubeVideoIdAttribute()
    {
        if (!$this->video_url || $this->video_type !== 'youtube') {
            return null;
        }

        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->video_url, $matches);
        return $matches[1] ?? null;
    }

    // Check if episode has video
    public function getHasVideoAttribute()
    {
        return !empty($this->video_url);
    }

    // Get all available platform links
    public function getPlatformLinksAttribute()
    {
        $links = [];
        
        if ($this->spotify_url) $links['spotify'] = $this->spotify_url;
        if ($this->apple_url) $links['apple'] = $this->apple_url;
        if ($this->youtube_music_url) $links['youtube_music'] = $this->youtube_music_url;
        if ($this->audiomack_url) $links['audiomack'] = $this->audiomack_url;
        if ($this->soundcloud_url) $links['soundcloud'] = $this->soundcloud_url;
        
        if ($this->custom_links && is_array($this->custom_links)) {
            $links = array_merge($links, $this->custom_links);
        }
        
        return $links;
    }

    // Helpers
    private function detectDeviceType()
    {
        $userAgent = request()->userAgent();
        if (preg_match('/mobile/i', $userAgent)) return 'mobile';
        if (preg_match('/tablet/i', $userAgent)) return 'tablet';
        return 'desktop';
    }

    public function getUserProgressAttribute()
    {
        if (!auth()->check()) return null;

        return $this->listeningHistory()
            ->where('user_id', auth()->id())
            ->first();
    }
}