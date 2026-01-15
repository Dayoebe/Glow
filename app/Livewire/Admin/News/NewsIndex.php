<?php

namespace App\Livewire\Admin\News;

use App\Models\News\News;
use App\Models\News\NewsCategory;
use Livewire\Component;
use Livewire\WithPagination;

class NewsIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterCategory = '';
    public $filterStatus = '';
    public $showDeleteModal = false;
    public $newsToDelete = null;

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

    public function confirmDelete($newsId)
    {
        $this->newsToDelete = $newsId;
        $this->showDeleteModal = true;
    }

    public function deleteNews()
    {
        if ($this->newsToDelete) {
            $news = News::find($this->newsToDelete);
            
            if ($news) {
                // Delete associated data
                $news->comments()->delete();
                $news->interactions()->delete();
                $news->delete();
                
                session()->flash('success', 'News article deleted successfully!');
            }
        }

        $this->showDeleteModal = false;
        $this->newsToDelete = null;
    }

    public function togglePublish($newsId)
    {
        $news = News::find($newsId);
        
        if ($news) {
            $news->is_published = !$news->is_published;
            
            if ($news->is_published && !$news->published_at) {
                $news->published_at = now();
            }
            
            $news->save();
            
            $status = $news->is_published ? 'published' : 'unpublished';
            session()->flash('success', "News article {$status} successfully!");
        }
    }

    public function toggleFeatured($newsId)
    {
        $news = News::find($newsId);
        
        if ($news) {
            // If making this featured, unfeatured all others
            if (!$news->is_featured) {
                News::where('is_featured', true)->update(['is_featured' => false]);
            }
            
            $news->is_featured = !$news->is_featured;
            $news->save();
            
            session()->flash('success', 'Featured status updated successfully!');
        }
    }

    public function getNewsProperty()
    {
        $query = News::with(['category', 'author'])
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
        return NewsCategory::active()->get();
    }

    public function getStatsProperty()
    {
        return [
            'total' => News::count(),
            'published' => News::where('is_published', true)->count(),
            'draft' => News::where('is_published', false)->count(),
            'featured' => News::where('is_featured', true)->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.news.index', [
            'newsArticles' => $this->news,
            'categories' => $this->categories,
            'stats' => $this->stats,
        ])->layout('layouts.admin', [
            'header' => 'News Management'
        ]);
    }
}