<?php

namespace App\Livewire\Page;

use App\Models\News\News;
use App\Models\News\NewsCategory;
use Livewire\Component;
use Livewire\WithPagination;

class NewsPage extends Component
{
    use WithPagination;

    public $view = 'grid';
    public $selectedCategory = 'all';
    public $searchQuery = '';
    public $sortBy = 'latest';

    protected $queryString = [
        'selectedCategory' => ['except' => 'all'],
        'searchQuery' => ['except' => ''],
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

    public function getFeaturedNewsProperty()
    {
        $news = News::with(['category', 'author'])
            ->published()
            ->featured()
            ->latest('published_at')
            ->first();
            
        return $news ? $this->formatNewsItem($news) : null;
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
        return view('livewire.page.news-page', [
            'newsArticles' => $this->newsArticles,
            'featuredNews' => $this->featuredNews,
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
        ])->layout('layouts.app', ['title' => 'News & Updates - Glow FM']);
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
                'role' => ucfirst($news->author->role ?? 'Author'),
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
