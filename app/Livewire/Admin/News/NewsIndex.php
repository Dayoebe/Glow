<?php

namespace App\Livewire\Admin\News;

use App\Models\News\News;
use App\Models\News\NewsCategory;
use App\Models\Setting;
use App\Notifications\ContentApprovalUpdated;
use Livewire\Component;
use Livewire\WithPagination;

class NewsIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterCategory = '';
    public $filterStatus = '';
    public $approvalAction = '';
    public $approvalReason = '';
    public $approvalFormId = null;

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

    public function deleteNews($newsId)
    {
        $news = News::find($newsId);

        if ($news) {
            // Delete associated data
            $news->comments()->delete();
            $news->interactions()->delete();
            $news->delete();

            session()->flash('success', 'News article deleted successfully!');
        }
    }

    public function togglePublish($newsId)
    {
        $news = News::find($newsId);
        
        if ($news) {
            if (!$news->is_published && $news->approval_status !== 'approved') {
                session()->flash('error', 'This article must be approved before publishing.');
                return;
            }

            $news->is_published = !$news->is_published;
            
            if ($news->is_published && !$news->published_at) {
                $news->published_at = now();
            }
            
            $news->save();
            
            $status = $news->is_published ? 'published' : 'unpublished';
            session()->flash('success', "News article {$status} successfully!");
        }
    }

    public function startApproval($newsId, $action)
    {
        if (!$this->canReview()) {
            session()->flash('error', 'You do not have permission to review content.');
            return;
        }

        if ($action === 'approved') {
            $this->applyApproval($newsId, $action);
            return;
        }

        $this->approvalFormId = $newsId;
        $this->approvalAction = $action;
        $this->approvalReason = '';
    }

    public function submitApprovalForm()
    {
        if (!$this->canReview() || !$this->approvalFormId) {
            session()->flash('error', 'Unable to update approval status.');
            return;
        }

        $this->validate([
            'approvalReason' => 'required|min:5|max:1000',
        ]);

        $this->applyApproval($this->approvalFormId, $this->approvalAction, $this->approvalReason);
    }

    public function cancelApprovalForm()
    {
        $this->approvalFormId = null;
        $this->approvalAction = '';
        $this->approvalReason = '';
    }

    private function applyApproval(int $newsId, string $action, ?string $reason = null): void
    {
        $news = News::find($newsId);
        if (!$news) {
            session()->flash('error', 'Article not found.');
            return;
        }

        $update = [
            'approval_status' => $action,
            'approval_reason' => $reason ?: null,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ];

        if (in_array($action, ['flagged', 'rejected'], true)) {
            $update['is_published'] = false;
            $update['published_at'] = null;
        } elseif ($action === 'approved') {
            $update['is_published'] = true;
            $update['published_at'] = $news->published_at ?: now();
        }

        News::where('id', $newsId)->update($update);
        $news->refresh();

        if ($news->author) {
            $news->author->notify(new ContentApprovalUpdated(
                'news article',
                $news->title,
                $action,
                $reason ?: null
            ));
        }

        $this->approvalFormId = null;
        $this->approvalReason = '';
        $this->approvalAction = '';

        session()->flash('success', 'Approval status updated successfully.');
    }

    public function canReview(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        $approverIds = Setting::get('content_approvers.ids', []);
        return $user->staffMember && in_array($user->staffMember->id, $approverIds, true);
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

    public function setFeaturedPlacement($newsId, $placement)
    {
        $news = News::find($newsId);
        if (!$news) {
            return;
        }

        if ($placement === 'hero') {
            News::where('featured_position', 'hero')->update(['featured_position' => 'none']);
        }

        $news->featured_position = $placement;
        $news->is_featured = $placement !== 'none';
        $news->save();

        session()->flash('success', 'Featured placement updated successfully!');
    }

    public function getNewsProperty()
    {
        $query = News::with(['category', 'author'])
            ->withCount([
                'comments',
                'interactions as reactions_count' => function ($query) {
                    $query->where('type', 'reaction');
                },
            ])
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
        } elseif ($this->filterStatus === 'pending') {
            $query->where('approval_status', 'pending');
        } elseif ($this->filterStatus === 'approved') {
            $query->where('approval_status', 'approved');
        } elseif ($this->filterStatus === 'flagged') {
            $query->where('approval_status', 'flagged');
        } elseif ($this->filterStatus === 'rejected') {
            $query->where('approval_status', 'rejected');
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
