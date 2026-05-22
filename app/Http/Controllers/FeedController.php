<?php

namespace App\Http\Controllers;

use App\Models\News\News;
use App\Models\Podcast\Episode as PodcastEpisode;
use App\Models\Show\Show as RadioShow;
use App\Support\Seo;

class FeedController extends Controller
{
    public function news()
    {
        $items = News::published()
            ->with(['category', 'author'])
            ->latest('published_at')
            ->take(50)
            ->get();

        return $this->rss('feeds.news', compact('items'));
    }

    public function podcasts()
    {
        $items = PodcastEpisode::published()
            ->with(['show'])
            ->whereHas('show', fn ($query) => $query->active())
            ->latest('published_at')
            ->take(50)
            ->get();

        return $this->rss('feeds.podcasts', compact('items'));
    }

    public function shows()
    {
        $items = RadioShow::active()
            ->with(['category', 'primaryHost'])
            ->orderBy('title')
            ->take(100)
            ->get();

        return $this->rss('feeds.shows', compact('items'));
    }

    private function rss(string $view, array $data)
    {
        $data['station'] = Seo::station();

        return response(view($view, $data)->render(), 200)
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }
}
