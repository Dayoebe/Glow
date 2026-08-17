<?php

namespace App\Http\Controllers;

use App\Models\Blog\Post as BlogPost;
use App\Models\Blog\Category as BlogCategory;
use App\Models\Career\CareerPosition;
use App\Models\Event\Event;
use App\Models\Event\EventCategory;
use App\Models\News\News;
use App\Models\News\NewsCategory;
use App\Models\Podcast\Episode as PodcastEpisode;
use App\Models\Podcast\Show as PodcastShow;
use App\Models\Show\OAP;
use App\Models\Show\Category as ShowCategory;
use App\Models\Show\Show as RadioShow;
use App\Models\Staff\StaffMember;
use App\Support\PublicImage;
use App\Support\Seo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index()
    {
        $sections = [
            ['loc' => Seo::absoluteUrl(route('sitemap.pages'))],
            ['loc' => Seo::absoluteUrl(route('sitemap.articles')), 'lastmod' => $this->latestContentDate(News::published())],
            ['loc' => Seo::absoluteUrl(route('sitemap.news')), 'lastmod' => $this->latestContentDate(News::published()->where('published_at', '>=', now()->subDays(2)))],
            ['loc' => Seo::absoluteUrl(route('sitemap.programs')), 'lastmod' => $this->latestDate([
                RadioShow::active(),
                PodcastShow::active(),
                PodcastEpisode::published(),
            ])],
            ['loc' => Seo::absoluteUrl(route('sitemap.categories')), 'lastmod' => $this->latestDate([
                NewsCategory::active(),
                ShowCategory::active(),
                BlogCategory::active(),
                EventCategory::active(),
            ])],
            ['loc' => Seo::absoluteUrl(route('sitemap.images')), 'lastmod' => $this->latestDate([
                News::published(),
                RadioShow::active(),
                PodcastEpisode::published(),
            ])],
            ['loc' => Seo::absoluteUrl(route('sitemap.videos')), 'lastmod' => $this->latestDate([
                News::published()->whereNotNull('video_url'),
                PodcastEpisode::published()->whereNotNull('video_url'),
            ])],
        ];

        return $this->xml(view('sitemap-index', ['sections' => $sections])->render());
    }

    public function pages()
    {
        $urls = Cache::remember($this->cacheKey('pages'), now()->addMinutes(30), function () {
            $staticUrls = [
                ['loc' => Seo::absoluteUrl('/'), 'changefreq' => 'daily', 'priority' => '1.0'],
                ['loc' => Seo::absoluteUrl('/about'), 'changefreq' => 'yearly', 'priority' => '0.8'],
                ['loc' => Seo::absoluteUrl('/contact'), 'changefreq' => 'yearly', 'priority' => '0.8'],
                ['loc' => Seo::absoluteUrl('/listen-live'), 'changefreq' => 'daily', 'priority' => '0.8'],
                ['loc' => Seo::absoluteUrl('/advertise'), 'changefreq' => 'monthly', 'priority' => '0.6'],
                ['loc' => Seo::absoluteUrl('/privacy-policy'), 'changefreq' => 'yearly', 'priority' => '0.3'],
                ['loc' => Seo::absoluteUrl('/editorial-standards'), 'changefreq' => 'yearly', 'priority' => '0.6'],
                ['loc' => Seo::absoluteUrl('/shows'), 'changefreq' => 'weekly', 'priority' => '0.8'],
                ['loc' => Seo::absoluteUrl('/schedule'), 'changefreq' => 'weekly', 'priority' => '0.8'],
                ['loc' => Seo::absoluteUrl('/oaps'), 'changefreq' => 'weekly', 'priority' => '0.6'],
                ['loc' => Seo::absoluteUrl('/team'), 'changefreq' => 'weekly', 'priority' => '0.6'],
                ['loc' => Seo::absoluteUrl('/blog'), 'changefreq' => 'daily', 'priority' => '0.7'],
                ['loc' => Seo::absoluteUrl('/news'), 'changefreq' => 'daily', 'priority' => '0.9'],
                ['loc' => Seo::absoluteUrl('/events'), 'changefreq' => 'weekly', 'priority' => '0.7'],
                ['loc' => Seo::absoluteUrl('/podcasts'), 'changefreq' => 'weekly', 'priority' => '0.8'],
                ['loc' => Seo::absoluteUrl('/careers'), 'changefreq' => 'daily', 'priority' => '0.6'],
                ['loc' => Seo::absoluteUrl('/vettas'), 'changefreq' => 'weekly', 'priority' => '0.5'],
                ['loc' => Seo::absoluteUrl('/vettas/about'), 'changefreq' => 'monthly', 'priority' => '0.4'],
                ['loc' => Seo::absoluteUrl('/vettas/amenities'), 'changefreq' => 'monthly', 'priority' => '0.4'],
                ['loc' => Seo::absoluteUrl('/vettas/gallery'), 'changefreq' => 'weekly', 'priority' => '0.5'],
                ['loc' => Seo::absoluteUrl('/vettas/stay-guide'), 'changefreq' => 'monthly', 'priority' => '0.4'],
            ];

            $oapUrls = OAP::active()
                ->get(['slug', 'updated_at'])
                ->map(fn ($oap) => [
                    'loc' => route('oaps.show', $oap->slug),
                    'lastmod' => $oap->updated_at?->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority' => '0.5',
                ]);

            $staffUrls = StaffMember::where('is_active', true)
                ->get(['slug', 'updated_at'])
                ->map(fn ($staff) => [
                    'loc' => route('staff.show', $staff->slug),
                    'lastmod' => $staff->updated_at?->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority' => '0.4',
                ]);

            $eventUrls = Event::published()
                ->get(['slug', 'published_at', 'updated_at'])
                ->map(fn ($event) => [
                    'loc' => route('events.show', $event->slug),
                    'lastmod' => ($event->published_at ?? $event->updated_at)?->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.6',
                ]);

            $blogUrls = BlogPost::published()
                ->get(['slug', 'published_at', 'updated_at'])
                ->map(fn ($post) => [
                    'loc' => route('blog.show', $post->slug),
                    'lastmod' => ($post->published_at ?? $post->updated_at)?->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.6',
                ]);

            $careerUrls = CareerPosition::published()
                ->acceptingApplications()
                ->get(['slug', 'published_at', 'updated_at'])
                ->map(fn ($position) => [
                    'loc' => route('careers.show', $position->slug),
                    'lastmod' => ($position->published_at ?? $position->updated_at)?->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.5',
                ]);

            return collect($staticUrls)
                ->concat($oapUrls)
                ->concat($staffUrls)
                ->concat($eventUrls)
                ->concat($blogUrls)
                ->concat($careerUrls)
                ->values();
        });

        return $this->urlset($urls);
    }

    public function articles()
    {
        $urls = Cache::remember($this->cacheKey('articles'), now()->addMinutes(30), function () {
            return News::published()
                ->get(['slug', 'published_at', 'updated_at'])
                ->map(fn ($news) => [
                    'loc' => route('news.show', $news->slug),
                    'lastmod' => ($news->published_at ?? $news->updated_at)?->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ])
                ->values();
        });

        return $this->urlset($urls);
    }

    public function news()
    {
        $urls = Cache::remember($this->cacheKey('news'), now()->addMinutes(5), function () {
            return News::published()
                ->where('published_at', '>=', now()->subDays(2))
                ->latest('published_at')
                ->get(['slug', 'title', 'published_at', 'updated_at'])
                ->map(fn ($news) => [
                    'loc' => route('news.show', $news->slug),
                    'lastmod' => ($news->updated_at ?? $news->published_at)?->toAtomString(),
                    'publication_name' => Seo::BRAND_NAME,
                    'publication_language' => 'en',
                    'publication_date' => $news->published_at?->toAtomString(),
                    'title' => $news->title,
                ])
                ->values();
        });

        return $this->newsUrlset($urls);
    }

    public function programs()
    {
        $urls = Cache::remember($this->cacheKey('programs'), now()->addMinutes(30), function () {
            $radioShowUrls = RadioShow::active()
                ->get(['slug', 'updated_at'])
                ->map(fn ($show) => [
                    'loc' => route('shows.show', $show->slug),
                    'lastmod' => $show->updated_at?->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ]);

            $podcastShowUrls = PodcastShow::active()
                ->get(['slug', 'updated_at'])
                ->map(fn ($show) => [
                    'loc' => route('podcasts.show', $show->slug),
                    'lastmod' => $show->updated_at?->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.6',
                ]);

            $podcastEpisodeUrls = PodcastEpisode::published()
                ->whereHas('show', fn ($query) => $query->where('is_active', true))
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
                        'priority' => '0.6',
                    ];
                })
                ->filter();

            return collect()
                ->concat($radioShowUrls)
                ->concat($podcastShowUrls)
                ->concat($podcastEpisodeUrls)
                ->values();
        });

        return $this->urlset($urls);
    }

    public function categories()
    {
        $urls = Cache::remember($this->cacheKey('categories'), now()->addMinutes(30), function () {
            $news = NewsCategory::active()
                ->get(['slug', 'updated_at'])
                ->map(fn ($category) => [
                    'loc' => route('news', ['selectedCategory' => $category->slug]),
                    'lastmod' => $category->updated_at?->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.5',
                ]);

            $shows = ShowCategory::active()
                ->get(['slug', 'updated_at'])
                ->map(fn ($category) => [
                    'loc' => route('shows.index', ['selectedCategory' => $category->slug]),
                    'lastmod' => $category->updated_at?->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.5',
                ]);

            $blogs = BlogCategory::active()
                ->get(['slug', 'updated_at'])
                ->map(fn ($category) => [
                    'loc' => route('blog.index', ['selectedCategory' => $category->slug]),
                    'lastmod' => $category->updated_at?->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.4',
                ]);

            $events = EventCategory::active()
                ->get(['slug', 'updated_at'])
                ->map(fn ($category) => [
                    'loc' => route('events.index', ['selectedCategory' => $category->slug]),
                    'lastmod' => $category->updated_at?->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.4',
                ]);

            return collect()
                ->concat($news)
                ->concat($shows)
                ->concat($blogs)
                ->concat($events)
                ->values();
        });

        return $this->urlset($urls);
    }

    public function images()
    {
        $urls = Cache::remember($this->cacheKey('images'), now()->addMinutes(30), function () {
            $news = News::published()
                ->whereNotNull('featured_image')
                ->get(['slug', 'title', 'featured_image', 'published_at', 'updated_at'])
                ->map(fn ($item) => [
                    'loc' => route('news.show', $item->slug),
                    'lastmod' => ($item->published_at ?? $item->updated_at)?->toAtomString(),
                    'images' => [[
                        'loc' => Seo::absoluteUrl(PublicImage::url($item->featured_image)),
                        'title' => $item->title,
                    ]],
                ]);

            $shows = RadioShow::active()
                ->whereNotNull('cover_image')
                ->get(['slug', 'title', 'cover_image', 'updated_at'])
                ->map(fn ($item) => [
                    'loc' => route('shows.show', $item->slug),
                    'lastmod' => $item->updated_at?->toAtomString(),
                    'images' => [[
                        'loc' => Seo::absoluteUrl(PublicImage::url($item->cover_image)),
                        'title' => $item->title,
                    ]],
                ]);

            $podcasts = PodcastEpisode::published()
                ->whereHas('show', fn ($query) => $query->where('is_active', true))
                ->with('show:id,slug,cover_image')
                ->get(['id', 'show_id', 'slug', 'title', 'cover_image', 'published_at', 'updated_at'])
                ->map(function ($item) {
                    if (!$item->show) {
                        return null;
                    }

                    $image = $item->cover_image ?: $item->show->cover_image;
                    if (!$image) {
                        return null;
                    }

                    return [
                        'loc' => route('podcasts.episode', ['showSlug' => $item->show->slug, 'episodeSlug' => $item->slug]),
                        'lastmod' => ($item->published_at ?? $item->updated_at)?->toAtomString(),
                        'images' => [[
                            'loc' => Seo::absoluteUrl(PublicImage::url($image)),
                            'title' => $item->title,
                        ]],
                    ];
                })
                ->filter();

            return collect()
                ->concat($news)
                ->concat($shows)
                ->concat($podcasts)
                ->filter(fn ($url) => !empty(data_get($url, 'images.0.loc')))
                ->values();
        });

        return $this->urlset($urls, ['images' => true]);
    }

    public function videos()
    {
        $urls = Cache::remember($this->cacheKey('videos'), now()->addMinutes(30), function () {
            $news = News::published()
                ->whereNotNull('video_url')
                ->get(['slug', 'title', 'excerpt', 'content', 'featured_image', 'video_url', 'published_at', 'updated_at'])
                ->map(function ($item) {
                    $videoUrl = Seo::absoluteUrl($item->video_url);
                    $isDirectMedia = $videoUrl
                        && preg_match('/\.(?:mp4|m4v|webm|mov|ogv)(?:$|[?#])/i', $videoUrl) === 1;

                    return [
                        'loc' => route('news.show', $item->slug),
                        'lastmod' => ($item->published_at ?? $item->updated_at)?->toAtomString(),
                        'videos' => [[
                            'title' => $item->title,
                            'description' => $item->excerpt ?: Seo::text($item->content, 200),
                            'thumbnail_loc' => Seo::absoluteUrl(PublicImage::url($item->featured_image)),
                            'content_loc' => $isDirectMedia ? $videoUrl : null,
                            'player_loc' => !$isDirectMedia && $videoUrl ? Seo::videoEmbedUrl($videoUrl) : null,
                            'publication_date' => $item->published_at?->toAtomString(),
                        ]],
                    ];
                });

            $podcasts = PodcastEpisode::published()
                ->whereNotNull('video_url')
                ->whereHas('show', fn ($query) => $query->where('is_active', true))
                ->with('show:id,slug,cover_image')
                ->get(['id', 'show_id', 'slug', 'title', 'description', 'cover_image', 'video_url', 'published_at', 'updated_at'])
                ->map(function ($item) {
                    $videoUrl = Seo::absoluteUrl($item->video_url);
                    if (!$item->show || !$videoUrl) {
                        return null;
                    }

                    $isDirectMedia = preg_match('/\.(?:mp4|m4v|webm|mov|ogv)(?:$|[?#])/i', $videoUrl) === 1;

                    return [
                        'loc' => route('podcasts.episode', ['showSlug' => $item->show->slug, 'episodeSlug' => $item->slug]),
                        'lastmod' => ($item->published_at ?? $item->updated_at)?->toAtomString(),
                        'videos' => [[
                            'title' => $item->title,
                            'description' => Seo::text($item->description, 200),
                            'thumbnail_loc' => Seo::absoluteUrl(PublicImage::url($item->cover_image ?: $item->show->cover_image)),
                            'content_loc' => $isDirectMedia ? $videoUrl : null,
                            'player_loc' => $isDirectMedia ? null : Seo::videoEmbedUrl($videoUrl),
                            'publication_date' => $item->published_at?->toAtomString(),
                        ]],
                    ];
                })
                ->filter();

            return collect()
                ->concat($news)
                ->concat($podcasts)
                ->filter(fn ($url) => !empty(data_get($url, 'videos.0.thumbnail_loc')))
                ->filter(fn ($url) => !empty(data_get($url, 'videos.0.content_loc')) || !empty(data_get($url, 'videos.0.player_loc')))
                ->values();
        });

        return $this->urlset($urls, ['videos' => true]);
    }

    private function urlset($urls, array $options = [])
    {
        $urls = collect($urls)
            ->map(function ($url) {
                $url['loc'] = Seo::absoluteUrl($url['loc'] ?? null);

                return $url;
            })
            ->filter(fn ($url) => !empty($url['loc']))
            ->values();

        return $this->xml(view('sitemap-urlset', [
            'urls' => $urls,
            'includeImages' => $options['images'] ?? false,
            'includeVideos' => $options['videos'] ?? false,
        ])->render());
    }

    private function newsUrlset($urls)
    {
        $urls = collect($urls)
            ->map(function ($url) {
                $url['loc'] = Seo::absoluteUrl($url['loc'] ?? null);

                return $url;
            })
            ->filter(fn ($url) => !empty($url['loc']) && !empty($url['publication_date']) && !empty($url['title']))
            ->values();

        return $this->xml(view('sitemap-news', ['urls' => $urls])->render(), 300);
    }

    private function xml(string $xml, int $maxAge = 900)
    {
        $response = response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('Cache-Control', "public, max-age={$maxAge}, s-maxage={$maxAge}, stale-while-revalidate=3600")
            ->header('ETag', '"' . hash('sha256', $xml) . '"');

        $response->isNotModified(request());

        return $response;
    }

    private function cacheKey(string $section): string
    {
        return 'sitemap.' . $section;
    }

    private function latestContentDate($query): ?string
    {
        $value = (clone $query)->latest('updated_at')->value('updated_at');

        return $value ? Carbon::parse($value)->toAtomString() : null;
    }

    private function latestDate(array $queries): ?string
    {
        return collect($queries)
            ->map(fn ($query) => $this->latestContentDate($query))
            ->filter()
            ->sortDesc()
            ->first();
    }
}
