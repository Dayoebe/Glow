<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class NewsComment extends Model
{
    protected $fillable = [
        'news_id', 'user_id', 'comment', 'is_approved', 'is_pinned',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_pinned' => 'boolean',
    ];

    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }
}