<?php

namespace App\Livewire\Admin\Blog;

use App\Models\Blog\Post;
use App\Models\Blog\Category;
use Livewire\Component;
use Livewire\WithPagination;

class BlogIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterCategory = '';
    public $filterStatus = '';
    public $showDeleteModal = false;
    public $postToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterCategory' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterCategory()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function confirmDelete($postId)
    {
        $this->postToDelete = $postId;
        $this->showDeleteModal = true;
    }

    public function deletePost()
    {
        if ($this->postToDelete) {
            $post = Post::find($this->postToDelete);
            
            if ($post) {
                // Delete associated data
                $post->comments()->delete();
                $post->interactions()->delete();
                $post->delete();
                
                session()->flash('success', 'Blog post deleted successfully!');
            }
        }

        $this->showDeleteModal = false;
        $this->postToDelete = null;
    }

    public function togglePublish($postId)
    {
        $post = Post::find($postId);
        
        if ($post) {
            $post->is_published = !$post->is_published;
            
            if ($post->is_published && !$post->published_at) {
                $post->published_at = now();
            }
            
            $post->save();
            
            $status = $post->is_published ? 'published' : 'unpublished';
            session()->flash('success', "Blog post {$status} successfully!");
        }
    }

    public function toggleFeatured($postId)
    {
        $post = Post::find($postId);
        
        if ($post) {
            // If making this featured, unfeatured all others
            if (!$post->is_featured) {
                Post::where('is_featured', true)->update(['is_featured' => false]);
            }
            
            $post->is_featured = !$post->is_featured;
            $post->save();
            
            session()->flash('success', 'Featured status updated successfully!');
        }
    }

    public function getPostsProperty()
    {
        $query = Post::with(['category', 'author'])
            ->latest('created_at');

        if (!empty($this->search)) {
            $query->search($this->search);
        }

        if (!empty($this->filterCategory)) {
            $query->where('category_id', $this->filterCategory);
        }

        if ($this->filterStatus === 'published') {
            $query->where('is_published', true);
        } elseif ($this->filterStatus === 'draft') {
            $query->where('is_published', false);
        } elseif ($this->filterStatus === 'featured') {
            $query->where('is_featured', true);
        }

        return $query->paginate(10);
    }

    public function getCategoriesProperty()
    {
        return Category::active()->get();
    }

    public function getStatsProperty()
    {
        return [
            'total' => Post::count(),
            'published' => Post::where('is_published', true)->count(),
            'draft' => Post::where('is_published', false)->count(),
            'featured' => Post::where('is_featured', true)->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.blog.index', [
            'posts' => $this->posts,
            'categories' => $this->categories,
            'stats' => $this->stats,
        ])->layout('layouts.admin', [
            'header' => 'Blog Management'
        ]);
    }
}