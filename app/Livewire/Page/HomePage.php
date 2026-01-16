<?php

namespace App\Livewire\Page;

use App\Models\News\News;
use App\Models\News\NewsCategory;
use App\Models\Podcast\Show;
use App\Models\Blog\Post; 
use App\Models\Blog\Category; 
use App\Models\Podcast\Episode;
use App\Models\Event\Event;
use App\Models\Setting;
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
    public $homeContent = [];

    public function mount()
    {
        $this->loadRealNews();
        $this->loadRealPodcasts();
        $this->loadUpcomingEvents();
         $this->loadRealBlogPosts();
        $this->loadStats();
        $this->loadTestimonials();
        $this->loadHomeContent();
    }

    private function loadRealNews()
    {
        // Get Breaking News
        $this->breakingNews = News::with(['category', 'author'])
            ->published()
            ->breaking()
            ->latest('published_at')
            ->first();

        // Get Latest News (3 most recent)
        $this->latestNews = News::with(['category', 'author'])
            ->published()
            ->latest('published_at')
            ->take(3)
            ->get()
            ->map(function ($news) {
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
            })
            ->toArray();

        // Get Trending News (based on views in last 7 days)
        $this->trendingNews = News::with(['category'])
            ->published()
            ->trending(7)
            ->take(5)
            ->get()
            ->map(function ($news) {
                return [
                    'id' => $news->id,
                    'slug' => $news->slug,
                    'title' => $news->title,
                    'category' => $news->category->name,
                    'views' => $news->views,
                    'published_at' => $news->time_ago,
                ];
            })
            ->toArray();
    }

    private function loadRealPodcasts()
    {
        // Get Featured Shows (up to 3)
        $featuredShows = Show::with(['publishedEpisodes', 'host'])
            ->active()
            ->featured()
            ->take(3)
            ->get();

        // If no featured shows, get the most popular ones
        if ($featuredShows->isEmpty()) {
            $featuredShows = Show::with(['publishedEpisodes', 'host'])
                ->active()
                ->orderBy('total_plays', 'desc')
                ->take(3)
                ->get();
        }

        $this->featuredShows = $featuredShows->map(function ($show) {
            $latestEpisode = $show->publishedEpisodes()->latest('published_at')->first();
            
            return [
                'id' => $show->id,
                'slug' => $show->slug,
                'title' => $show->title,
                'host' => $show->host_name,
                'time' => $latestEpisode ? $latestEpisode->published_at->format('g:i A') : 'Coming Soon',
                'description' => $show->description,
                'image' => $show->cover_image ?? 'https://ui-avatars.com/api/?name=' . urlencode($show->title) . '&background=10b981&color=fff&size=400',
                'category' => ucfirst($show->category),
                'days' => ucfirst($show->frequency),
                'episodes_count' => $show->publishedEpisodes->count(),
                'total_plays' => number_format($show->total_plays),
                'subscribers' => number_format($show->subscribers),
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
                    'category' => $post->category->name,
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
        $totalBlogPosts = \App\Models\Blog\Post::published()->count(); // ADD THIS
        $totalPodcasts = Show::active()->count();
        $totalEpisodes = Episode::where('status', 'published')->count();
        $totalPodcastPlays = Episode::sum('plays');
        
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
                'number' => number_format($totalBlogPosts), // USE BLOG POSTS HERE
                'label' => 'Blog Articles',
                'icon' => 'fas fa-blog' // Changed from fa-newspaper to fa-blog
            ],
            [
                'number' => $totalPodcasts . '+',
                'label' => 'Podcast Shows',
                'icon' => 'fas fa-podcast'
            ],
        ];
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
                    'category' => $event->category->name,
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
            'now_playing_title' => 'Morning Vibes with MC Olumiko',
            'now_playing_time' => '6:00 AM - 10:00 AM',
        ];

        $settings = Setting::get('website.home', []);
        $this->homeContent = array_replace_recursive($defaults, $settings);
    }

    public function render()
    {
        return view('livewire.page.home-page')->layout('layouts.app', [
            'title' => 'Glow FM 99.1 - Your Voice, Your Music'
        ]);
    }
}
