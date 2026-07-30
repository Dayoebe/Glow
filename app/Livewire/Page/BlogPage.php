<?php

namespace App\Livewire\Page;

use App\Models\Blog\Post;
use App\Models\Blog\Category;
use App\Support\Seo;
use Livewire\Component;
use Livewire\WithPagination;

class BlogPage extends Component
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

    public function getPostsProperty()
    {
        $query = Post::with(['category', 'author.staffMember'])
            ->published();

        if ($this->selectedCategory !== 'all') {
            $query->byCategory($this->selectedCategory);
        }

        if (!empty($this->searchQuery)) {
            $query->search($this->searchQuery);
        }

        if (
            $this->selectedCategory === 'all'
            && empty($this->searchQuery)
            && $this->sortBy === 'latest'
            && $this->featuredPost
        ) {
            $query->whereKeyNot($this->featuredPost->getKey());
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

    public function getFeaturedPostProperty()
    {
        $post = Post::with(['category', 'author.staffMember'])
            ->published()
            ->featured()
            ->latest('published_at')
            ->first();

        return $post;
    }

    public function getTrendingPostsProperty()
    {
        return Post::with(['category'])
            ->published()
            ->trending(7)
            ->take(5)
            ->get();
    }

    public function getCategoriesProperty()
    {
        return Category::active()
            ->withCount(['posts' => function ($query) {
                $query->published();
            }])
            ->get();
    }

    public function getPopularTagsProperty()
    {
        return Post::published()
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
        $posts = $this->posts;
        $categories = $this->categories;
        $currentPage = $posts->currentPage();
        $currentCategory = $this->selectedCategory !== 'all'
            ? $categories->firstWhere('slug', $this->selectedCategory)
            : null;
        $hasInvalidCategory = $this->selectedCategory !== 'all' && !$currentCategory;
        $hasNonIndexableFilters = filled($this->searchQuery)
            || $this->sortBy !== 'latest'
            || $this->view !== 'grid'
            || $hasInvalidCategory;

        $canonicalQuery = [];
        if ($currentCategory) {
            $canonicalQuery['selectedCategory'] = $currentCategory->slug;
        }
        if (!$hasNonIndexableFilters && $currentPage > 1) {
            $canonicalQuery['page'] = $currentPage;
        }

        $canonical = Seo::canonicalUrl(route('blog.index', [], false), $canonicalQuery);
        $pageLabel = $currentPage > 1 && !$hasNonIndexableFilters ? ' - Page ' . $currentPage : '';
        $landingTitle = $currentCategory
            ? $currentCategory->name . ' Articles - Glow 99.1 FM'
            : 'Glow FM Stories, Culture And Community Perspectives';
        $description = $currentCategory
            ? Seo::text(
                $currentCategory->description
                    ?: "Read {$currentCategory->name} stories, perspectives, and useful guides from Glow 99.1 FM in Akure, Ondo State.",
                165
            )
            : 'Read Glow 99.1 FM stories and perspectives on music, culture, broadcasting, community life, and the people shaping Akure and Ondo State.';
        $postItems = $posts->getCollection()
            ->take(40)
            ->values()
            ->map(fn (Post $post, int $index) => [
                '@type' => 'ListItem',
                'position' => ($posts->firstItem() ?? 1) + $index,
                'name' => $post->title,
                'url' => Seo::absoluteUrl(route('blog.show', $post->slug)),
                'description' => Seo::text($post->excerpt ?: $post->content, 140),
            ])
            ->all();

        return view('livewire.page.blog-page', [
            'posts' => $posts,
            'featuredPost' => $this->featuredPost,
            'trendingPosts' => $this->trendingPosts,
            'categories' => $categories,
        ])->layout('layouts.app', [
            'title' => $landingTitle . $pageLabel,
            'meta_title' => $landingTitle . $pageLabel,
            'meta_description' => $description,
            'canonical_url' => $canonical,
            'meta_robots' => $hasNonIndexableFilters
                ? config('seo.filtered_robots', 'noindex, follow, noarchive')
                : null,
            'structured_data' => Seo::siteGraph([
                'title' => $landingTitle . $pageLabel,
                'description' => $description,
                'url' => $canonical,
                'type' => 'CollectionPage',
                'mainEntity' => ['@id' => $canonical . '#article-list'],
                'breadcrumbs' => [
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'Blog', 'url' => route('blog.index')],
                    ...($currentCategory ? [[
                        'name' => $currentCategory->name,
                        'url' => $canonical,
                    ]] : []),
                ],
                'extra' => [
                    [
                        '@type' => 'ItemList',
                        '@id' => $canonical . '#article-list',
                        'name' => $landingTitle,
                        'numberOfItems' => count($postItems),
                        'itemListElement' => $postItems,
                    ],
                ],
            ]),
        ]);
    }
}
