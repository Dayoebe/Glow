<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog\Post;
use App\Models\Event\Event;
use App\Models\News\News;
use App\Models\Podcast\Episode;
use App\Models\Setting;
use App\Models\Show\ScheduleSlot;
use App\Models\Show\Show as ProgramShow;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function show(Request $request)
    {
        $newsPage = max(1, (int) $request->query('news_page', 1));
        $newsBatchSize = (int) $request->query('news_batch_size', 6);
        $newsBatchSize = min(20, max(3, $newsBatchSize));

        $breakingNews = News::with(['category', 'author'])
            ->published()
            ->breaking()
            ->latest('published_at')
            ->first();

        $latestNews = News::with(['category', 'author'])
            ->published()
            ->latest('published_at')
            ->take(6)
            ->get()
            ->map(fn ($news) => $this->mapNewsCard($news))
            ->toArray();

        $trendingNews = News::with(['category'])
            ->published()
            ->trending(7)
            ->take(5)
            ->get()
            ->map(fn ($news) => [
                'id' => $news->id,
                'slug' => $news->slug,
                'title' => $news->title,
                'category' => $news->category->name,
                'views' => $news->views,
                'published_at' => $news->time_ago,
            ])
            ->toArray();

        $featured = News::with(['category', 'author'])
            ->published()
            ->featured()
            ->latest('published_at')
            ->take(3)
            ->get();

        if ($featured->isEmpty()) {
            $featured = News::with(['category', 'author'])
                ->published()
                ->latest('published_at')
                ->take(3)
                ->get();
        }

        $mostViewed = News::with(['category', 'author'])
            ->published()
            ->orderByDesc('views')
            ->take(5)
            ->get();

        $featuredNews = $featured->map(fn ($news) => $this->mapNewsCard($news))->toArray();
        $mostViewedNews = $mostViewed->map(fn ($news) => $this->mapNewsCompact($news))->toArray();

        $excludedIds = collect($featuredNews)
            ->pluck('id')
            ->merge(collect($mostViewedNews)->pluck('id'))
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        [$otherNews, $newsHasMore] = $this->fetchOtherNewsBatch($excludedIds, $newsPage, $newsBatchSize);

        $featuredShows = ProgramShow::with([
                'category',
                'primaryHost',
                'scheduleSlots' => function ($query) {
                    $query->active()
                        ->orderBy('day_of_week')
                        ->orderBy('start_time');
                },
            ])
            ->active()
            ->featured()
            ->take(3)
            ->get();

        if ($featuredShows->isEmpty()) {
            $featuredShows = ProgramShow::with([
                    'category',
                    'primaryHost',
                    'scheduleSlots' => function ($query) {
                        $query->active()
                            ->orderBy('day_of_week')
                            ->orderBy('start_time');
                    },
                ])
                ->active()
                ->orderBy('total_listeners', 'desc')
                ->take(3)
                ->get();
        }

        $featuredShows = $featuredShows->map(function ($show) {
            $slot = $show->scheduleSlots->first();

            return [
                'id' => $show->id,
                'slug' => $show->slug,
                'title' => $show->title,
                'host' => $show->primaryHost?->name ?? 'TBA',
                'host_slug' => $show->primaryHost?->slug,
                'time' => $slot?->time_range ?? 'Schedule TBA',
                'description' => $show->description,
                'image' => $show->cover_image ?? 'https://ui-avatars.com/api/?name=' . urlencode($show->title) . '&background=10b981&color=fff&size=400',
                'category' => $show->category?->name ?? 'Show',
                'days' => $slot ? ucfirst($slot->day_of_week) : 'Weekly',
            ];
        })->toArray();

        $latestPodcastEpisodes = Episode::with(['show'])
            ->published()
            ->latest('published_at')
            ->take(6)
            ->get()
            ->map(function ($episode) {
                return [
                    'id' => $episode->id,
                    'slug' => $episode->slug,
                    'show_slug' => $episode->show->slug,
                    'title' => $episode->title,
                    'description' => $episode->description,
                    'image' => $episode->cover_image ?? $episode->show->cover_image ?? 'https://ui-avatars.com/api/?name=' . urlencode($episode->title) . '&background=6366f1&color=fff&size=400',
                    'show_title' => $episode->show->title,
                    'duration' => $episode->formatted_duration,
                    'published_at' => $episode->published_at->format('M d, Y'),
                    'plays' => number_format($episode->plays),
                    'season_episode' => $episode->season_number ? "S{$episode->season_number} E{$episode->episode_number}" : null,
                ];
            })
            ->toArray();

        $currentShow = $this->getCurrentShow();
        $recommendations = $this->buildRecommendations($request->user()?->id);

        $upcomingEvents = Event::with(['category'])
            ->published()
            ->upcoming()
            ->orderBy('start_at')
            ->take(3)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'slug' => $event->slug,
                    'title' => $event->title,
                    'date' => $event->formatted_date,
                    'time' => $event->formatted_time,
                    'location' => $event->venue_name ?? 'Venue TBA',
                    'image' => $event->featured_image ?? 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?w=800&h=600&fit=crop',
                    'category' => $event->category->name,
                ];
            })
            ->toArray();

        $totalNews = News::published()->count();
        $totalBlogPosts = Post::published()->count();
        $totalPodcasts = ProgramShow::active()->count();
        $totalEpisodes = Episode::where('status', 'published')->count();

        $stats = [
            [
                'number' => '1M+',
                'label' => 'Monthly Listeners',
                'icon' => 'fas fa-users',
            ],
            [
                'number' => '24/7',
                'label' => 'Live Broadcasting',
                'icon' => 'fas fa-broadcast-tower',
            ],
            [
                'number' => number_format($totalNews),
                'label' => 'News Articles',
                'icon' => 'fas fa-newspaper',
            ],
            [
                'number' => number_format($totalBlogPosts),
                'label' => 'Blog Articles',
                'icon' => 'fas fa-blog',
            ],
            [
                'number' => $totalPodcasts . '+',
                'label' => 'Show Programs',
                'icon' => 'fas fa-microphone',
            ],
            [
                'number' => number_format($totalEpisodes),
                'label' => 'Podcast Episodes',
                'icon' => 'fas fa-podcast',
            ],
        ];

        $homeContent = $this->getHomeContent();

        $latestBlogPosts = Post::with(['category', 'author'])
            ->published()
            ->latest('published_at')
            ->take(3)
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'slug' => $post->slug,
                    'title' => $post->title,
                    'excerpt' => $post->excerpt,
                    'image' => $post->featured_image ?? 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=800&h=600&fit=crop',
                    'category' => $post->category->name,
                    'category_slug' => $post->category->slug,
                    'category_color' => $post->category->color,
                    'date' => $post->published_at?->diffForHumans() ?? 'Unpublished',
                    'author' => $post->author->name,
                    'read_time' => $post->read_time,
                    'views' => number_format($post->views),
                    'comments_count' => $post->comments_count,
                ];
            })
            ->toArray();

        $trendingBlogPosts = Post::with(['category'])
            ->published()
            ->trending(7)
            ->take(5)
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'slug' => $post->slug,
                    'title' => $post->title,
                    'category' => $post->category->name,
                    'views' => $post->views,
                    'published_at' => $post->published_at?->diffForHumans() ?? 'Unpublished',
                ];
            })
            ->toArray();

        return response()->json([
            'breakingNews' => $breakingNews ? $this->mapNewsCard($breakingNews) : null,
            'latestNews' => $latestNews,
            'trendingNews' => $trendingNews,
            'featuredNews' => $featuredNews,
            'mostViewedNews' => $mostViewedNews,
            'otherNews' => $otherNews,
            'newsPage' => $newsPage,
            'newsHasMore' => $newsHasMore,
            'featuredShows' => $featuredShows,
            'latestPodcastEpisodes' => $latestPodcastEpisodes,
            'latestBlogPosts' => $latestBlogPosts,
            'trendingBlogPosts' => $trendingBlogPosts,
            'upcomingEvents' => $upcomingEvents,
            'stats' => $stats,
            'homeContent' => $homeContent,
            'currentShow' => $currentShow,
            'recommendations' => $recommendations,
            'personalized' => !empty($recommendations),
        ]);
    }

    private function buildRecommendations(?int $userId): array
    {
        if (!$userId) {
            return [];
        }

        return [
            'news' => $this->recommendNews($userId),
            'blog' => $this->recommendBlog($userId),
            'events' => $this->recommendEvents($userId),
        ];
    }

    private function recommendNews(int $userId): array
    {
        $categoryIds = DB::table('news_interactions')
            ->join('news', 'news.id', '=', 'news_interactions.news_id')
            ->where('news_interactions.user_id', $userId)
            ->whereIn('news_interactions.type', ['view', 'reaction', 'bookmark', 'share'])
            ->groupBy('news.category_id')
            ->orderByRaw('count(*) desc')
            ->limit(2)
            ->pluck('news.category_id');

        if ($categoryIds->isEmpty()) {
            return [];
        }

        return News::with(['category', 'author'])
            ->published()
            ->whereIn('category_id', $categoryIds)
            ->latest('published_at')
            ->take(6)
            ->get()
            ->map(fn ($news) => $this->mapNewsCard($news))
            ->toArray();
    }

    private function recommendBlog(int $userId): array
    {
        $categoryIds = DB::table('blog_interactions')
            ->join('blog_posts', 'blog_posts.id', '=', 'blog_interactions.post_id')
            ->where('blog_interactions.user_id', $userId)
            ->whereIn('blog_interactions.type', ['view', 'reaction', 'bookmark', 'share'])
            ->groupBy('blog_posts.category_id')
            ->orderByRaw('count(*) desc')
            ->limit(2)
            ->pluck('blog_posts.category_id');

        if ($categoryIds->isEmpty()) {
            return [];
        }

        return Post::with(['category', 'author'])
            ->published()
            ->whereIn('category_id', $categoryIds)
            ->latest('published_at')
            ->take(4)
            ->get()
            ->map(fn ($post) => $this->mapBlogCard($post))
            ->toArray();
    }

    private function recommendEvents(int $userId): array
    {
        $categoryIds = DB::table('event_interactions')
            ->join('events', 'events.id', '=', 'event_interactions.event_id')
            ->where('event_interactions.user_id', $userId)
            ->whereIn('event_interactions.type', ['view', 'reaction', 'bookmark', 'share'])
            ->groupBy('events.category_id')
            ->orderByRaw('count(*) desc')
            ->limit(2)
            ->pluck('events.category_id');

        if ($categoryIds->isEmpty()) {
            return [];
        }

        return Event::with(['category'])
            ->published()
            ->whereIn('category_id', $categoryIds)
            ->upcoming()
            ->orderBy('start_at')
            ->take(4)
            ->get()
            ->map(fn ($event) => $this->mapEventCard($event))
            ->toArray();
    }

    private function fetchOtherNewsBatch(array $excludeIds, int $page, int $batchSize): array
    {
        $offset = ($page - 1) * $batchSize;

        $items = News::with(['category', 'author'])
            ->published()
            ->when(!empty($excludeIds), function ($query) use ($excludeIds) {
                $query->whereNotIn('id', $excludeIds);
            })
            ->latest('published_at')
            ->skip($offset)
            ->take($batchSize + 1)
            ->get();

        $hasMore = $items->count() > $batchSize;
        if ($hasMore) {
            $items = $items->take($batchSize);
        }

        return [$items->map(fn ($news) => $this->mapNewsCard($news))->toArray(), $hasMore];
    }

    private function mapNewsCard(News $news): array
    {
        return [
            'id' => $news->id,
            'slug' => $news->slug,
            'title' => $news->title,
            'excerpt' => $news->excerpt,
            'image' => $news->featured_image ?? 'https://images.unsplash.com/photo-1478737270239-2f02b77fc618?w=800&h=600&fit=crop',
            'category' => $news->category->name,
            'date' => $news->time_ago,
            'author' => $news->author->name,
            'read_time' => $news->read_time,
            'views' => number_format($news->views),
            'likes' => $news->likes,
        ];
    }

    private function mapNewsCompact(News $news): array
    {
        return [
            'id' => $news->id,
            'slug' => $news->slug,
            'title' => $news->title,
            'category' => $news->category->name,
            'date' => $news->time_ago,
            'views' => number_format($news->views),
            'image' => $news->featured_image ?? 'https://images.unsplash.com/photo-1478737270239-2f02b77fc618?w=800&h=600&fit=crop',
        ];
    }

    private function mapBlogCard(Post $post): array
    {
        return [
            'id' => $post->id,
            'slug' => $post->slug,
            'title' => $post->title,
            'excerpt' => $post->excerpt,
            'image' => $post->featured_image ?? 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=800&h=600&fit=crop',
            'category' => $post->category?->name,
            'date' => $post->published_at?->diffForHumans() ?? 'Unpublished',
            'read_time' => $post->read_time,
        ];
    }

    private function mapEventCard(Event $event): array
    {
        return [
            'id' => $event->id,
            'slug' => $event->slug,
            'title' => $event->title,
            'excerpt' => $event->excerpt,
            'image' => $event->featured_image ?? 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?w=800&h=600&fit=crop',
            'category' => $event->category?->name,
            'date' => $event->formatted_date,
            'time' => $event->formatted_time,
            'location' => $event->venue_name ?? $event->city ?? 'Venue TBA',
        ];
    }

    private function getCurrentShow(): ?array
    {
        $timezone = 'Africa/Lagos';
        $now = Carbon::now($timezone);
        $day = strtolower($now->format('l'));
        $time = $now->format('H:i:s');

        $currentSlot = ScheduleSlot::query()
            ->with(['show', 'oap'])
            ->active()
            ->forDay($day)
            ->where('start_time', '<=', $time)
            ->where('end_time', '>', $time)
            ->orderBy('start_time', 'desc')
            ->first();

        if ($currentSlot && !$currentSlot->isActiveOn($now)) {
            $currentSlot = null;
        }

        if (!$currentSlot) {
            return null;
        }

        return [
            'title' => $currentSlot->show?->title ?? 'Untitled Show',
            'slug' => $currentSlot->show?->slug,
            'host' => $currentSlot->oap?->name ?? 'Host TBA',
            'host_slug' => $currentSlot->oap?->slug,
            'time' => $currentSlot->time_range,
        ];
    }

    private function getHomeContent(): array
    {
        $defaults = [
            'hero_badge' => 'NOW LIVE ON AIR',
            'hero_title' => 'Your Voice,',
            'hero_highlight' => 'Your Music',
            'hero_subtitle' => 'Broadcasting the heartbeat of the city of Akure, 24/7 on 99.1 FM',
            'primary_cta_text' => 'Listen Live Now',
            'primary_cta_url' => Setting::get('station.stream_url', 'https://stream-176.zeno.fm/mwam2yirv1pvv'),
            'secondary_cta_text' => 'View Schedule',
            'secondary_cta_url' => '/shows',
            'now_playing_label' => 'Currently Playing',
            'now_playing_title' => 'Morning Vibes',
            'now_playing_time' => '6:00 AM - 10:00 AM',
        ];

        $settings = Setting::get('website.home', []);
        if (!is_array($settings)) {
            $settings = [];
        }
        $homeContent = array_replace_recursive($defaults, $settings);

        $stream = Setting::get('stream', []);
        if (!is_array($stream)) {
            $stream = [];
        }
        if (!empty($stream)) {
            $homeContent['now_playing_title'] = $stream['show_name'] ?? $homeContent['now_playing_title'];
            $homeContent['now_playing_time'] = $stream['show_time'] ?? $homeContent['now_playing_time'];
        }

        return $homeContent;
    }
}
