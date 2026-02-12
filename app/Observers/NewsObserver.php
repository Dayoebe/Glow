<?php

namespace App\Observers;

use App\Models\News\News;
use App\Services\FcmService;
use Illuminate\Support\Facades\Cache;

class NewsObserver
{
    public function saved(News $news): void
    {
        if (!$news->wasRecentlyCreated && !$news->wasChanged(['breaking', 'is_published', 'approval_status', 'published_at', 'breaking_until'])) {
            return;
        }

        if (!$news->is_published || $news->approval_status !== 'approved') {
            return;
        }

        if ($news->published_at && $news->published_at->isFuture()) {
            return;
        }

        if ($news->breaking === 'no') {
            return;
        }

        if ($news->breaking_until && $news->breaking_until->isPast()) {
            return;
        }

        $cacheKey = 'push_breaking_sent:' . $news->id;
        if (!Cache::add($cacheKey, true, now()->addDays(7))) {
            return;
        }

        $title = 'Breaking News';
        $body = $news->title;
        $data = [
            'type' => 'news',
            'slug' => $news->slug,
        ];

        app(FcmService::class)->sendToTopic('breaking', $title, $body, $data, $news->featured_image);
    }
}
