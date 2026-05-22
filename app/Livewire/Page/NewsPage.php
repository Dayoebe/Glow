<?php

namespace App\Livewire\Page;

use App\Models\News\News;
use App\Models\News\NewsCategory;
use App\Support\Seo;
use Livewire\Component;
use Livewire\WithPagination;

class NewsPage extends Component
{
    use WithPagination;

    public $view = 'grid';
    public $selectedCategory = 'all';
    public $searchQuery = '';
    public $tag = '';
    public $sortBy = 'latest';

    protected $queryString = [
        'selectedCategory' => ['except' => 'all'],
        'searchQuery' => ['except' => ''],
        'tag' => ['except' => ''],
        'view' => ['except' => 'grid'],
        'sortBy' => ['except' => 'latest'],
    ];

    public function updatingSearchQuery()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function updatingTag()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function getNewsArticlesProperty()
    {
        $query = News::with(['category', 'author'])
            ->published();

        if ($this->selectedCategory !== 'all') {
            $query->byCategory($this->selectedCategory);
        }

        if (!empty($this->searchQuery)) {
            $query->search($this->searchQuery);
        }

        if (!empty($this->tag)) {
            $query->whereJsonContains('tags', $this->tag);
        }

        switch ($this->sortBy) {
            case 'popular':
                $query->orderBy('views', 'desc');
                break;
            case 'trending':
                $query->trending(7);
                break;
            default:
                $query->latest('published_at');
        }

        return $query->paginate(9);
    }

    public function getFeaturedHeroProperty()
    {
        $query = News::with(['category', 'author'])
            ->published()
            ->featured();

        $hero = (clone $query)
            ->where('featured_position', 'hero')
            ->latest('published_at')
            ->first();

        if (!$hero) {
            $hero = (clone $query)->latest('published_at')->first();
        }

        return $hero ? $this->formatNewsItem($hero) : null;
    }

    public function getFeaturedSecondaryProperty()
    {
        $query = News::with(['category', 'author'])
            ->published()
            ->featured();

        $secondary = $query->where('featured_position', 'secondary')
            ->latest('published_at')
            ->take(2)
            ->get();

        if ($secondary->count() < 2) {
            $excludeIds = $secondary->pluck('id')->toArray();
            if ($this->featuredHero) {
                $excludeIds[] = $this->featuredHero['id'];
            }

            $fallback = News::with(['category', 'author'])
                ->published()
                ->featured()
                ->whereNotIn('id', $excludeIds)
                ->latest('published_at')
                ->take(2 - $secondary->count())
                ->get();

            $secondary = $secondary->concat($fallback);
        }

        return $secondary->map(fn ($news) => $this->formatNewsItem($news));
    }

    public function getFeaturedSidebarProperty()
    {
        $query = News::with(['category', 'author'])
            ->published()
            ->featured();

        $sidebar = $query->where('featured_position', 'sidebar')
            ->latest('published_at')
            ->take(4)
            ->get();

        if ($sidebar->isEmpty()) {
            $excludeIds = $this->featuredSecondary->pluck('id')->toArray();
            if ($this->featuredHero) {
                $excludeIds[] = $this->featuredHero['id'];
            }

            $sidebar = News::with(['category', 'author'])
                ->published()
                ->featured()
                ->whereNotIn('id', $excludeIds)
                ->latest('published_at')
                ->take(4)
                ->get();
        }

        return $sidebar->map(fn ($news) => $this->formatNewsItem($news));
    }

    public function getBreakingNewsProperty()
    {
        return News::with(['category', 'author'])
            ->published()
            ->breaking()
            ->latest('published_at')
            ->take(3)
            ->get();
    }

    public function getTrendingNewsProperty()
    {
        return News::published()
            ->trending(7)
            ->take(5)
            ->get();
    }

    public function getCategoriesProperty()
    {
        return NewsCategory::active()
            ->withCount(['news' => function ($query) {
                $query->published();
            }])
            ->get();
    }

    public function getPopularTagsProperty()
    {
        return News::published()
            ->whereNotNull('tags')
            ->get()
            ->pluck('tags')
            ->flatten()
            ->countBy()
            ->sortDesc()
            ->take(15)
            ->keys()
            ->toArray();
    }

    public function render()
    {
        $description = 'Latest news and updates from Glow 99.1 FM Akure, covering Ondo State, Nigerian politics, public affairs, community updates, entertainment, sports, health, and youth-focused stories.';
        $newsArticles = $this->newsArticles;
        $newsItems = $newsArticles->getCollection()
            ->take(40)
            ->values()
            ->map(fn ($news, $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $news['title'] ?? '',
                'url' => route('news.show', $news['slug'] ?? ''),
                'description' => Seo::text($news['excerpt'] ?? '', 140),
            ])
            ->all();

        return view('livewire.page.news-page', [
            'newsArticles' => $newsArticles,
            'featuredHero' => $this->featuredHero,
            'featuredSecondary' => $this->featuredSecondary,
            'featuredSidebar' => $this->featuredSidebar,
            'breakingNews' => $this->breakingNews,
            'trendingNews' => $this->trendingNews,
            'categories' => $this->categories->map(function ($cat) {
                return [
                    'slug' => $cat->slug,
                    'name' => $cat->name,
                    'count' => $cat->news_count,
                    'icon' => $cat->icon,
                    'color' => $cat->color,
                ];
            })->prepend([
                'slug' => 'all',
                'name' => 'All News',
                'count' => News::published()->count(),
                'icon' => 'fas fa-newspaper',
                'color' => 'emerald',
            ])->toArray(),
            'popularTags' => $this->popularTags,
        ])->layout('layouts.app', [
            'title' => 'News & Updates - Glow 99.1 FM',
            'meta_title' => 'Glow 99.1 FM News - Ondo State And Nigerian Updates',
            'meta_description' => $description,
            'canonical_url' => route('news'),
            'structured_data' => Seo::siteGraph([
                'title' => 'Glow 99.1 FM News',
                'description' => $description,
                'url' => route('news'),
                'breadcrumbs' => [
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'News', 'url' => route('news')],
                ],
                'extra' => [
                    [
                        '@type' => 'CollectionPage',
                        '@id' => route('news') . '#collection',
                        'name' => 'Glow 99.1 FM News',
                        'hasPart' => [
                            '@type' => 'ItemList',
                            'itemListElement' => $newsItems,
                        ],
                    ],
                ],
            ]),
        ]);
    }

    private function formatNewsItem($news)
    {
        return [
            'id' => $news->id,
            'title' => $news->title,
            'slug' => $news->slug,
            'excerpt' => $news->excerpt,
            'featured_image' => $news->featured_image,
            'category' => [
                'name' => $news->category->name,
                'slug' => $news->category->slug,
            ],
            'author' => [
                'id' => $news->author->id,
                'name' => $news->author->name,
                'avatar' => $news->author->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($news->author->name),
                'role' => $news->author->role_label ?? 'Author',
            ],
            'published_at' => $news->published_at->format('Y-m-d H:i:s'),
            'read_time' => $news->read_time,
            'views' => $news->views,
            'likes' => $news->likes,
            'shares' => $news->shares,
            'comments_count' => $news->comments_count,
        ];
    }
}
