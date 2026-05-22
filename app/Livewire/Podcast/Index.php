<?php

namespace App\Livewire\Podcast;

use App\Models\Podcast\Show;
use App\Models\Podcast\Episode;
use App\Support\Seo;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $selectedCategory = 'all';
    public $searchQuery = '';
    public $view = 'shows'; // shows or episodes

    protected $queryString = [
        'selectedCategory' => ['except' => 'all'],
        'searchQuery' => ['except' => ''],
        'view' => ['except' => 'shows'],
    ];

    public function updatingSearchQuery()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function getShowsProperty()
    {
        $query = Show::with(['host', 'publishedEpisodes'])
            ->withCount('publishedEpisodes')
            ->active();

        if ($this->selectedCategory !== 'all') {
            $query->byCategory($this->selectedCategory);
        }

        if (!empty($this->searchQuery)) {
            $query->where(function($q) {
                $q->where('title', 'like', "%{$this->searchQuery}%")
                  ->orWhere('description', 'like', "%{$this->searchQuery}%");
            });
        }

        return $query->orderBy('is_featured', 'desc')
            ->orderBy('total_plays', 'desc')
            ->paginate(12);
    }

    public function getLatestEpisodesProperty()
    {
        return Episode::with(['show'])
            ->published()
            ->latest('published_at')
            ->take(6)
            ->get();
    }

    public function getFeaturedShowsProperty()
    {
        return Show::with(['publishedEpisodes'])
            ->active()
            ->featured()
            ->take(3)
            ->get();
    }

    public function getTrendingEpisodesProperty()
    {
        return Episode::with(['show'])
            ->published()
            ->where('published_at', '>=', now()->subDays(30))
            ->orderBy('plays', 'desc')
            ->take(5)
            ->get();
    }

    public function getCategoriesProperty()
    {
        return [
            'all' => 'All Podcasts',
            'music' => 'Music',
            'talk' => 'Talk Show',
            'interview' => 'Interviews',
            'tech' => 'Tech & Audio',
            'lifestyle' => 'Lifestyle',
            'education' => 'Educational',
        ];
    }

    public function render()
    {
        $description = 'Glow 99.1 FM podcasts and audio/video episodes from Akure, including interviews, public affairs, entertainment, community conversations, and Glow TV-related digital media.';
        $shows = $this->shows;
        $showItems = $shows->getCollection()
            ->take(40)
            ->values()
            ->map(fn ($show, $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $show->title,
                'url' => route('podcasts.show', $show->slug),
                'description' => Seo::text($show->description, 140),
            ])
            ->all();

        return view('livewire.podcast.index', [
            'shows' => $shows,
            'latestEpisodes' => $this->latestEpisodes,
            'featuredShows' => $this->featuredShows,
            'trendingEpisodes' => $this->trendingEpisodes,
            'categories' => $this->categories,
        ])->layout('layouts.app', [
            'title' => 'Podcasts - Glow 99.1 FM',
            'meta_title' => 'Glow 99.1 FM Podcasts And Digital Episodes',
            'meta_description' => $description,
            'canonical_url' => route('podcasts.index'),
            'structured_data' => Seo::siteGraph([
                'title' => 'Glow 99.1 FM Podcasts',
                'description' => $description,
                'url' => route('podcasts.index'),
                'breadcrumbs' => [
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'Podcasts', 'url' => route('podcasts.index')],
                ],
                'extra' => [
                    [
                        '@type' => 'CollectionPage',
                        '@id' => route('podcasts.index') . '#collection',
                        'name' => 'Glow 99.1 FM Podcasts',
                        'hasPart' => [
                            '@type' => 'ItemList',
                            'itemListElement' => $showItems,
                        ],
                    ],
                ],
            ]),
        ]);
    }
}
