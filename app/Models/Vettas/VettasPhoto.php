<?php

namespace App\Models\Vettas;

use App\Models\User;
use Database\Factories\Vettas\VettasPhotoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VettasPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'caption',
        'description',
        'image_path',
        'alt_text',
        'photographer_name',
        'location',
        'captured_at',
        'display_order',
        'is_featured',
        'is_published',
        'published_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'captured_at' => 'date',
        'display_order' => 'integer',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (self $photo) {
            if ($photo->is_published && !$photo->published_at) {
                $photo->published_at = now();
            }

            if (!$photo->is_published) {
                $photo->published_at = null;
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(VettasCategory::class, 'category_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    protected static function newFactory(): VettasPhotoFactory
    {
        return VettasPhotoFactory::new();
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where(function ($inner) {
                $inner->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeSearch($query, string $searchTerm)
    {
        return $query->where(function ($inner) use ($searchTerm) {
            $inner->where('title', 'like', "%{$searchTerm}%")
                ->orWhere('caption', 'like', "%{$searchTerm}%")
                ->orWhere('description', 'like', "%{$searchTerm}%")
                ->orWhere('photographer_name', 'like', "%{$searchTerm}%")
                ->orWhere('location', 'like', "%{$searchTerm}%");
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderByDesc('is_featured')
            ->orderBy('display_order')
            ->orderByDesc('captured_at')
            ->orderByDesc('published_at')
            ->latest('id');
    }

    public function getDisplayDateAttribute(): ?string
    {
        if ($this->captured_at) {
            return $this->captured_at->format('M d, Y');
        }

        return $this->published_at?->format('M d, Y');
    }
}
