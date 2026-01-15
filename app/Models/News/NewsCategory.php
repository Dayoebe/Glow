<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

// ============================================
// NewsCategory Model
// ============================================
class NewsCategory extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'icon', 'color', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function news(): HasMany
    {
        return $this->hasMany(News::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getNewsCountAttribute(): int
    {
        return $this->news()->published()->count();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}

