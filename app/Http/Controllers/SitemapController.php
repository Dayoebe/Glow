<?php

namespace App\Http\Controllers;

use App\Models\Blog\Post as BlogPost;
use App\Models\Blog\Category as BlogCategory;
use App\Models\Event\Event;
use App\Models\Event\EventCategory;
use App\Models\News\News;
use App\Models\News\NewsCategory;
use App\Models\Podcast\Episode as PodcastEpisode;
use App\Models\Podcast\Show as PodcastShow;
use App\Models\Show\Category as ShowCategory;
use App\Models\Show\OAP;
use App\Models\Show\Show as RadioShow;
use App\Models\Staff\StaffMember;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index()
    {
        $cacheKey = 'sitemap.xml:' . request()->getSchemeAndHttpHost();

        $xml = Cache::remember($cacheKey, now()->addHours(6), function () {
            $staticUrls = [
                ['loc' => url('/'), 'changefreq' => 'daily', 'priority' => '1.0'],
                ['loc' => url('/about'), 'changefreq' => 'yearly', 'priority' => '0.6'],
                ['loc' => url('/contact'), 'changefreq' => 'yearly', 'priority' => '0.6'],
                ['loc' => url('/privacy-policy'), 'changefreq' => 'yearly', 'priority' => '0.3'],
                ['loc' => url('/shows'), 'changefreq' => 'weekly', 'priority' => '0.7'],
                ['loc' => url('/schedule'), 'changefreq' => 'weekly', 'priority' => '0.7'],
                ['loc' => url('/oaps'), 'changefreq' => 'weekly', 'priority' => '0.6'],
                ['loc' => url('/team'), 'changefreq' => 'weekly', 'priority' => '0.6'],
                ['loc' => url('/blog'), 'changefreq' => 'daily', 'priority' => '0.8'],
                ['loc' => url('/news'), 'changefreq' => 'daily', 'priority' => '0.8'],
                ['loc' => url('/events'), 'changefreq' => 'weekly', 'priority' => '0.7'],
                ['loc' => url('/podcasts'), 'changefreq' => 'weekly', 'priority' => '0.7'],
            ];

            $radioShowUrls = RadioShow::active()
                ->get(['slug', 'updated_at'])
                ->map(function ($show) {
                    return [
                        'loc' => route('shows.show', $show->slug),
                        'lastmod' => $show->updated_at?->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.6',
                    ];
                });

            $showCategoryUrls = ShowCategory::active()
                ->whereHas('shows', function ($query) {
                    $query->where('is_active', true);
                })
                ->get(['slug', 'updated_at'])
                ->map(function ($category) {
                    return [
                        'loc' => route('shows.index', ['selectedCategory' => $category->slug]),
                        'lastmod' => $category->updated_at?->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.5',
                    ];
                });

            $oapUrls = OAP::active()
                ->get(['slug', 'updated_at'])
                ->map(function ($oap) {
                    return [
                        'loc' => route('oaps.show', $oap->slug),
                        'lastmod' => $oap->updated_at?->toAtomString(),
                        'changefreq' => 'monthly',
                        'priority' => '0.5',
                    ];
                });

            $staffUrls = StaffMember::where('is_active', true)
                ->get(['slug', 'updated_at'])
                ->map(function ($staff) {
                    return [
                        'loc' => route('staff.show', $staff->slug),
                        'lastmod' => $staff->updated_at?->toAtomString(),
                        'changefreq' => 'monthly',
                        'priority' => '0.4',
                    ];
                });

            $newsUrls = News::published()
                ->get(['slug', 'published_at', 'updated_at'])
                ->map(function ($news) {
                    return [
                        'loc' => route('news.show', $news->slug),
                        'lastmod' => ($news->published_at ?? $news->updated_at)?->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.6',
                    ];
                });

            $newsCategoryUrls = NewsCategory::active()
                ->whereHas('news', function ($query) {
                    $query->published();
                })
                ->get(['slug', 'updated_at'])
                ->map(function ($category) {
                    return [
                        'loc' => route('news', ['selectedCategory' => $category->slug]),
                        'lastmod' => $category->updated_at?->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.5',
                    ];
                });

            $eventUrls = Event::published()
                ->get(['slug', 'published_at', 'updated_at'])
                ->map(function ($event) {
                    return [
                        'loc' => route('events.show', $event->slug),
                        'lastmod' => ($event->published_at ?? $event->updated_at)?->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.6',
                    ];
                });

            $eventCategoryUrls = EventCategory::active()
                ->whereHas('events', function ($query) {
                    $query->published();
                })
                ->get(['slug', 'updated_at'])
                ->map(function ($category) {
                    return [
                        'loc' => route('events.index', ['selectedCategory' => $category->slug]),
                        'lastmod' => $category->updated_at?->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.5',
                    ];
                });

            $blogUrls = BlogPost::published()
                ->get(['slug', 'published_at', 'updated_at'])
                ->map(function ($post) {
                    return [
                        'loc' => route('blog.show', $post->slug),
                        'lastmod' => ($post->published_at ?? $post->updated_at)?->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.6',
                    ];
                });

            $blogCategoryUrls = BlogCategory::active()
                ->whereHas('posts', function ($query) {
                    $query->published();
                })
                ->get(['slug', 'updated_at'])
                ->map(function ($category) {
                    return [
                        'loc' => route('blog.index', ['selectedCategory' => $category->slug]),
                        'lastmod' => $category->updated_at?->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.5',
                    ];
                });

            $podcastShowUrls = PodcastShow::active()
                ->get(['slug', 'updated_at'])
                ->map(function ($show) {
                    return [
                        'loc' => route('podcasts.show', $show->slug),
                        'lastmod' => $show->updated_at?->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.6',
                    ];
                });

            $podcastCategoryUrls = PodcastShow::active()
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->select('category')
                ->distinct()
                ->get()
                ->map(function ($row) {
                    return [
                        'loc' => route('podcasts.index', ['selectedCategory' => $row->category]),
                        'changefreq' => 'weekly',
                        'priority' => '0.5',
                    ];
                });

            $podcastEpisodeUrls = PodcastEpisode::published()
                ->whereHas('show', function ($query) {
                    $query->where('is_active', true);
                })
                ->with(['show:id,slug'])
                ->get(['id', 'show_id', 'slug', 'published_at', 'updated_at'])
                ->map(function ($episode) {
                    if (!$episode->show) {
                        return null;
                    }

                    return [
                        'loc' => route('podcasts.episode', [
                            'showSlug' => $episode->show->slug,
                            'episodeSlug' => $episode->slug,
                        ]),
                        'lastmod' => ($episode->published_at ?? $episode->updated_at)?->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority' => '0.5',
                    ];
                })
                ->filter();

            $urls = collect($staticUrls)
                ->concat($radioShowUrls)
                ->concat($showCategoryUrls)
                ->concat($oapUrls)
                ->concat($staffUrls)
                ->concat($newsUrls)
                ->concat($newsCategoryUrls)
                ->concat($eventUrls)
                ->concat($eventCategoryUrls)
                ->concat($blogUrls)
                ->concat($blogCategoryUrls)
                ->concat($podcastShowUrls)
                ->concat($podcastCategoryUrls)
                ->concat($podcastEpisodeUrls)
                ->values();

            return view('sitemap', ['urls' => $urls])->render();
        });

        return response($xml, 200)
            ->header('Content-Type', 'application/xml');
    }
}
