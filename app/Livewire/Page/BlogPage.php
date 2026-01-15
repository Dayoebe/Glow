<?php

namespace App\Livewire\Page;

use App\Models\Blog\Post;
use App\Models\Blog\Category;
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
        $query = Post::with(['category', 'author'])
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

    public function getFeaturedPostProperty()
    {
        $post = Post::with(['category', 'author'])
            ->published()
            ->featured()
            ->latest('published_at')
            ->first();
            
        return $post; // Return the actual post model, not formatted array
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
        return view('livewire.page.blog-page', [
            'posts' => $this->posts,
            'featuredPost' => $this->featuredPost,
            'trendingPosts' => $this->trendingPosts,
            'categories' => $this->categories,
            'popularTags' => $this->popularTags,
        ])->layout('layouts.app', ['title' => 'Blog - Glow FM']);
    }
}