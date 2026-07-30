<?php

namespace App\Livewire\Page;

use App\Models\News\News;
use App\Models\News\NewsCategory;
use App\Models\Blog\Post; 
use App\Models\Blog\Category; 
use App\Models\Podcast\Episode;
use App\Models\Show\Show as ProgramShow;
use App\Models\Show\ScheduleSlot;
use App\Models\Event\Event;
use App\Models\Setting;
use App\Support\Seo;
use Carbon\Carbon;
use Livewire\Component;

class HomePage extends Component
{
    public $featuredShows = [];
    public $latestPodcastEpisodes = [];
    public $latestNews = [];
    public $latestBlogPosts = []; 
    public $trendingBlogPosts = []; 
    public $upcomingEvents = [];
    public $stats = [];
    public $testimonials = [];
    public $breakingNews = null;
    public $trendingNews = [];
    public $featuredNews = [];
    public $mostViewedNews = [];
    public $otherNews = [];
    public $newsBatch = 1;
    public $newsBatchSize = 6;
    public $newsHasMore = true;
    public $homeContent = [];
    public $currentShow = null;
    public $nextShow = null;

    public function hydrate()
    {
        $this->normalizeFeaturedShows();
    }

    public function mount()
    {
        $this->loadRealNews();
        $this->loadNewsShowcase();
        $this->loadRealPodcasts();
        $this->loadCurrentShow();
        $this->loadUpcomingEvents();
        $this->loadHomeContent();
    }

    private function normalizeFeaturedShows(): void
    {
        if (!is_array($this->featuredShows)) {
            $this->loadRealPodcasts();
            return;
        }

        if (empty($this->featuredShows)) {
            return;
        }

        $first = $this->featuredShows[array_key_first($this->featuredShows)] ?? null;
        if (is_array($first) || $first instanceof \ArrayAccess) {
            $hasSlugKey = is_array($first)
                ? array_key_exists('slug', $first)
                : isset($first['slug']);

            if (!$hasSlugKey) {
                $this->loadRealPodcasts();
            }

            return;
        }

        if (is_object($first)) {
            if (!isset($first->slug)) {
                $this->loadRealPodcasts();
            }

            return;
        }

        if (is_numeric($first)) {
            $this->loadRealPodcasts();
        }
    }

    private function loadRealNews()
    {
        // Get Breaking News
        $this->breakingNews = News::with(['category', 'author'])
            ->published()
            ->breaking()
            ->latest('published_at')
            ->first();

        // Get Latest News (6 most recent)
        $this->latestNews = News::with(['category', 'author'])
            ->published()
            ->latest('published_at')
            ->take(6)
            ->get()
            ->map(function ($news) {
                return [
                    'id' => $news->id,
                    'slug' => $news->slug,
                    'title' => $news->title,
                    'excerpt' => $news->excerpt,
                    'image' => $news->featured_image ?? 'https://images.unsplash.com/photo-1478737270239-2f02b77fc618?w=800&h=600&fit=crop',
                    'category' => $news->category?->name ?? 'News',
                    'date' => $news->time_ago,
                    'author' => $news->author?->name ?? 'Glow FM',
                    'read_time' => $news->read_time,
                    'views' => number_format($news->views),
                    'likes' => $news->likes,
                ];
            })
            ->toArray();

        $this->trendingNews = [];
    }

    private function loadNewsShowcase(): void
    {
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

        $this->featuredNews = $featured->map(fn ($news) => $this->mapNewsCard($news))->toArray();
        $this->mostViewedNews = [];

        $this->newsBatch = 1;
        [$other, $hasMore] = $this->fetchOtherNewsBatch($this->getExcludedNewsIds(), $this->newsBatch);
        $this->otherNews = $other;
        $this->newsHasMore = $hasMore;
    }

    public function loadMoreNews(): void
    {
        if (!$this->newsHasMore) {
            return;
        }

        $nextBatch = $this->newsBatch + 1;
        [$other, $hasMore] = $this->fetchOtherNewsBatch($this->getExcludedNewsIds(), $nextBatch);

        $this->newsBatch = $nextBatch;
        $this->otherNews = array_values(array_merge($this->otherNews, $other));
        $this->newsHasMore = $hasMore;
    }

    private function fetchOtherNewsBatch(array $excludeIds, int $page): array
    {
        $offset = ($page - 1) * $this->newsBatchSize;

        $items = News::with(['category', 'author'])
            ->published()
            ->when(!empty($excludeIds), function ($query) use ($excludeIds) {
                $query->whereNotIn('id', $excludeIds);
            })
            ->latest('published_at')
            ->skip($offset)
            ->take($this->newsBatchSize + 1)
            ->get();

        $hasMore = $items->count() > $this->newsBatchSize;
        if ($hasMore) {
            $items = $items->take($this->newsBatchSize);
        }

        return [$items->map(fn ($news) => $this->mapNewsCard($news))->toArray(), $hasMore];
    }

    private function getExcludedNewsIds(): array
    {
        return collect($this->featuredNews)
            ->pluck('id')
            ->merge(collect($this->mostViewedNews)->pluck('id'))
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }

    private function mapNewsCard(News $news): array
    {
        return [
            'id' => $news->id,
            'slug' => $news->slug,
            'title' => $news->title,
            'excerpt' => $news->excerpt,
            'image' => $news->featured_image ?? 'https://images.unsplash.com/photo-1478737270239-2f02b77fc618?w=800&h=600&fit=crop',
            'category' => $news->category?->name ?? 'News',
            'date' => $news->time_ago,
            'author' => $news->author?->name ?? 'Glow FM',
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
            'category' => $news->category?->name ?? 'News',
            'date' => $news->time_ago,
            'views' => number_format($news->views),
            'image' => $news->featured_image ?? 'https://images.unsplash.com/photo-1478737270239-2f02b77fc618?w=800&h=600&fit=crop',
        ];
    }

    private function loadRealPodcasts()
    {
        // Get Featured Shows (up to 3)
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

        // If no featured shows, get the most popular ones
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

        $this->featuredShows = $featuredShows->map(function ($show) {
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

        // Get Latest Podcast Episodes (6 most recent)
        $this->latestPodcastEpisodes = Episode::with(['show'])
            ->published()
            ->latest('published_at')
            ->take(6)
            ->get()
            ->map(function ($episode) {
                return [
                    'id' => $episode->id,
                    'slug' => $episode->slug,
                    'show_slug' => $episode->show?->slug ?? '',
                    'title' => $episode->title,
                    'description' => $episode->description,
                    'image' => $episode->cover_image ?? $episode->show?->cover_image ?? 'https://ui-avatars.com/api/?name=' . urlencode($episode->title) . '&background=6366f1&color=fff&size=400',
                    'show_title' => $episode->show?->title ?? 'Podcast',
                    'duration' => $episode->formatted_duration,
                    'published_at' => $episode->published_at->format('M d, Y'),
                    'plays' => number_format($episode->plays),
                    'season_episode' => $episode->season_number ? "S{$episode->season_number} E{$episode->episode_number}" : null,
                ];
            })
            ->toArray();
    }

    private function loadCurrentShow()
    {
        $timezone = 'Africa/Lagos'; // Enforce WAT
        $now = Carbon::now($timezone);
        $rangeEnd = $now->copy()->addDays(7);
        $day = strtolower($now->format('l'));
        $time = $now->format('H:i:s');
        $candidateDays = collect(range(0, 7))
            ->map(fn ($dayOffset) => strtolower($now->copy()->addDays($dayOffset)->format('l')))
            ->unique()
            ->values()
            ->all();

        $candidateSlots = ScheduleSlot::query()
            ->with(['show', 'oap'])
            ->active()
            ->whereHas('show', fn ($query) => $query->active())
            ->overlappingDates($now, $rangeEnd)
            ->whereIn('day_of_week', $candidateDays)
            ->orderBy('start_time')
            ->get();

        $currentSlot = $candidateSlots->last(
            fn ($slot) => $slot->day_of_week === $day
                && $slot->start_time <= $time
                && $slot->end_time > $time
                && $slot->isActiveOn($now)
        );

        if ($currentSlot) {
            $this->currentShow = [
                'title' => $currentSlot->show?->title ?? 'Untitled Show',
                'slug' => $currentSlot->show?->slug,
                'host' => $currentSlot->oap?->name ?? 'Host TBA',
                'host_slug' => $currentSlot->oap?->slug,
                'time' => $currentSlot->time_range,
                'image' => $currentSlot->show?->cover_image,
            ];

            $this->loadNextShow($now, $candidateSlots);

            return;
        }

        $this->currentShow = null;
        $this->loadNextShow($now, $candidateSlots);
    }

    private function loadNextShow(Carbon $now, $candidateSlots): void
    {
        $timezone = 'Africa/Lagos';
        $nextSlot = null;
        $nextStartsAt = null;
        $nextDayOffset = null;

        for ($dayOffset = 0; $dayOffset <= 7; $dayOffset++) {
            $candidateDate = $now->copy()->addDays($dayOffset);
            $candidateDay = strtolower($candidateDate->format('l'));
            $candidateDateString = $candidateDate->format('Y-m-d');

            $nextSlot = $candidateSlots->first(
                fn ($slot) => $slot->day_of_week === $candidateDay
                    && ($dayOffset > 0 || $slot->start_time > $now->format('H:i:s'))
                    && $slot->isActiveOn($candidateDate)
            );

            if ($nextSlot) {
                $nextStartsAt = Carbon::parse(
                    $candidateDateString . ' ' . $nextSlot->start_time,
                    $timezone
                );
                $nextDayOffset = $dayOffset;

                break;
            }
        }

        if (!$nextSlot || !$nextStartsAt || $nextDayOffset === null) {
            $this->nextShow = null;

            return;
        }

        $dayLabel = match ($nextDayOffset) {
            0 => 'Today',
            1 => 'Tomorrow',
            default => $nextStartsAt->format('l'),
        };

        $this->nextShow = [
            'title' => $nextSlot->show?->title ?? 'Untitled Show',
            'slug' => $nextSlot->show?->slug,
            'host' => $nextSlot->oap?->name ?? 'Host TBA',
            'host_slug' => $nextSlot->oap?->slug,
            'time' => $nextSlot->time_range,
            'day' => $dayLabel,
            'image' => $nextSlot->show?->cover_image,
        ];
    }

    private function loadRealBlogPosts()
    {
        // Get Latest Blog Posts (3 most recent)
        $this->latestBlogPosts = \App\Models\Blog\Post::with(['category', 'author'])
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
                    'category' => $post->category?->name ?? 'Blog',
                    'category_slug' => $post->category?->slug,
                    'category_color' => $post->category?->color ?? 'purple',
                    'date' => $post->published_at?->diffForHumans() ?? 'Unpublished',
                    'author' => $post->author?->name ?? 'Glow FM',
                    'read_time' => $post->read_time,
                    'views' => number_format($post->views),
                    'comments_count' => $post->comments_count,
                ];
            })
            ->toArray();

        // Get Trending Blog Posts (based on views in last 7 days)
        $this->trendingBlogPosts = \App\Models\Blog\Post::with(['category'])
            ->published()
            ->trending(7)
            ->take(5)
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'slug' => $post->slug,
                    'title' => $post->title,
                    'category' => $post->category?->name ?? 'Blog',
                    'views' => $post->views,
                    'published_at' => $post->published_at?->diffForHumans() ?? 'Unpublished',
                ];
            })
            ->toArray();
    }

    // Update loadStats method to include blog count
    private function loadStats()
    {
        // Get real statistics from database
        $totalNews = News::published()->count();
        $totalBlogPosts = \App\Models\Blog\Post::published()->count();
        $totalPodcasts = ProgramShow::active()->count();
        $totalEpisodes = Episode::where('status', 'published')->count();
        
        $this->stats = [
            [
                'number' => '1M+',
                'label' => 'Monthly Listeners',
                'icon' => 'fas fa-users'
            ],
            [
                'number' => '24/7',
                'label' => 'Live Broadcasting',
                'icon' => 'fas fa-broadcast-tower'
            ],
            [
                'number' => number_format($totalNews),
                'label' => 'News Articles',
                'icon' => 'fas fa-newspaper'
            ],
            [
                'number' => number_format($totalBlogPosts),
                'label' => 'Blog Articles',
                'icon' => 'fas fa-blog'
            ],
            [
                'number' => $totalPodcasts . '+',
                'label' => 'Show Programs',
                'icon' => 'fas fa-microphone'
            ],
            [
                'number' => number_format($totalEpisodes),
                'label' => 'Podcast Episodes',
                'icon' => 'fas fa-podcast'
            ],
        ];
    }

    public function refreshHomeData()
    {
        $this->loadRealNews();
        $this->loadNewsShowcase();
        $this->loadRealPodcasts();
        $this->loadUpcomingEvents();
        $this->loadHomeContent();
    }

    public function refreshCurrentShow(): void
    {
        $this->loadCurrentShow();
    }

    private function loadUpcomingEvents()
    {
        $this->upcomingEvents = Event::with(['category'])
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
                    'category' => $event->category?->name ?? 'Event',
                ];
            })
            ->toArray();
    }

    // private function loadStats()
    // {
    //     // Get real statistics from database
    //     $totalNews = News::published()->count();
    //     $totalPodcasts = Show::active()->count();
    //     $totalEpisodes = Episode::where('status', 'published')->count();
    //     $totalPodcastPlays = Episode::sum('plays');
        
    //     $this->stats = [
    //         [
    //             'number' => '1M+',
    //             'label' => 'Monthly Listeners',
    //             'icon' => 'fas fa-users'
    //         ],
    //         [
    //             'number' => '24/7',
    //             'label' => 'Live Broadcasting',
    //             'icon' => 'fas fa-broadcast-tower'
    //         ],
    //         [
    //             'number' => $totalPodcasts . '+',
    //             'label' => 'Podcast Shows',
    //             'icon' => 'fas fa-podcast'
    //         ],
    //         [
    //             'number' => number_format($totalNews),
    //             'label' => 'News Articles',
    //             'icon' => 'fas fa-newspaper'
    //         ],
    //     ];
    // }

    private function loadTestimonials()
    {
        $this->testimonials = [];
    }

    private function loadHomeContent()
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
        $this->homeContent = array_replace_recursive($defaults, $settings);

        $stream = Setting::get('stream', []);
        if (!is_array($stream)) {
            $stream = [];
        }
        if (!empty($stream)) {
            $this->homeContent['now_playing_title'] = $stream['show_name'] ?? $this->homeContent['now_playing_title'];
            $this->homeContent['now_playing_time'] = $stream['show_time'] ?? $this->homeContent['now_playing_time'];
        }
    }

    public function render()
    {
        $description = 'Glow 99.1 FM is a radio station and digital news platform in Ijapo Estate, Akure, Ondo State, Nigeria, covering Ondo State news, live radio, podcasts, public affairs, entertainment, sports, and Yoruba programming.';

        return view('livewire.page.home-page')->layout('layouts.app', [
            'title' => 'Glow 99.1 FM - Your Station, Your Voice',
            'meta_title' => 'Glow 99.1 FM Akure - Your Station, Your Voice',
            'meta_description' => $description,
            'canonical_url' => route('home'),
            'structured_data' => Seo::siteGraph([
                'title' => 'Glow 99.1 FM Akure - Your Station, Your Voice',
                'description' => $description,
                'url' => route('home'),
            ]),
        ]);
    }
}
