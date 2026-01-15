<?php

namespace App\Livewire\Page;

use App\Models\News\News;
use App\Models\News\NewsCategory;
use App\Models\Podcast\Show;
use App\Models\Blog\Post; 
use App\Models\Blog\Category; 
use App\Models\Podcast\Episode;
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

    public function mount()
    {
        $this->loadRealNews();
        $this->loadRealPodcasts();
        $this->loadUpcomingEvents();
         $this->loadRealBlogPosts();
        $this->loadStats();
        $this->loadTestimonials();
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
                    'date' => $post->published_at->diffForHumans(),
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
                    'published_at' => $post->published_at->diffForHumans(),
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
        // This can be replaced with real events from database when you create an Events model
        $this->upcomingEvents = [
            [
                'id' => 1,
                'title' => 'Summer Beach Party 2025',
                'date' => 'July 15, 2025',
                'time' => '6:00 PM - 11:00 PM',
                'location' => 'Sunset Beach',
                'image' => 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=800&h=600&fit=crop',
                'attendees' => '500+'
            ],
            [
                'id' => 2,
                'title' => 'Live Concert: Acoustic Sessions',
                'date' => 'August 5, 2025',
                'time' => '8:00 PM - 10:00 PM',
                'location' => 'Glow FM Studio',
                'image' => 'https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?w=800&h=600&fit=crop',
                'attendees' => '100'
            ],
            [
                'id' => 3,
                'title' => 'DJ Workshop: Learn From The Pros',
                'date' => 'August 20, 2025',
                'time' => '2:00 PM - 6:00 PM',
                'location' => 'Broadcast Academy',
                'image' => 'https://images.unsplash.com/photo-1571330735066-03aaa9429d89?w=800&h=600&fit=crop',
                'attendees' => '50'
            ],
        ];
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
        $this->testimonials = [
            [
                'name' => 'John Anderson',
                'role' => 'Regular Listener',
                'message' => 'Glow FM has been my go-to station for years. The music selection is always on point and the DJs are amazing!',
                'rating' => 5,
                'avatar' => 'https://ui-avatars.com/api/?name=John+Anderson&background=10b981&color=fff'
            ],
            [
                'name' => 'Maria Garcia',
                'role' => 'Morning Commuter',
                'message' => 'MC Olumiko makes my morning drive so much better. Great energy and fantastic music to start the day!',
                'rating' => 5,
                'avatar' => 'https://ui-avatars.com/api/?name=Maria+Garcia&background=f59e0b&color=fff'
            ],
            [
                'name' => 'David Lee',
                'role' => 'Music Enthusiast',
                'message' => 'The variety of shows and music genres on Glow FM is incredible. There is something for everyone!',
                'rating' => 5,
                'avatar' => 'https://ui-avatars.com/api/?name=David+Lee&background=6366f1&color=fff'
            ],
        ];
    }

    public function render()
    {
        return view('livewire.page.home-page')->layout('layouts.app', [
            'title' => 'Glow FM 99.1 - Your Voice, Your Music'
        ]);
    }
}