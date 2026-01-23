<?php

namespace App\Livewire\Admin\Blog;

use App\Models\Blog\Post;
use App\Models\Blog\Category;
use App\Models\Setting;
use App\Notifications\ContentApprovalUpdated;
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
            if (!$post->is_published && $post->approval_status !== 'approved') {
                session()->flash('error', 'This post must be approved before publishing.');
                return;
            }

            $post->is_published = !$post->is_published;
            
            if ($post->is_published && !$post->published_at) {
                $post->published_at = now();
            }
            
            $post->save();
            
            $status = $post->is_published ? 'published' : 'unpublished';
            session()->flash('success', "Blog post {$status} successfully!");
        }
    }

    public function startApproval($postId, $action)
    {
        if (!$this->canReview()) {
            session()->flash('error', 'You do not have permission to review content.');
            return;
        }

        if ($action === 'approved') {
            $this->applyApproval($postId, $action);
            return;
        }

        $this->approvalFormId = $postId;
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

    private function applyApproval(int $postId, string $action, ?string $reason = null): void
    {
        $post = Post::find($postId);
        if (!$post) {
            session()->flash('error', 'Post not found.');
            return;
        }

        $post->approval_status = $action;
        $post->approval_reason = $reason ?: null;
        $post->reviewed_by = auth()->id();
        $post->reviewed_at = now();

        if (in_array($action, ['flagged', 'rejected'], true)) {
            $post->is_published = false;
            $post->published_at = null;
        } elseif ($post->is_published && !$post->published_at) {
            $post->published_at = now();
        }

        $post->save();

        if ($post->author) {
            $post->author->notify(new ContentApprovalUpdated(
                'blog post',
                $post->title,
                $action,
                $reason ?: null
            ));
        }

        $this->approvalFormId = null;
        $this->approvalReason = '';
        $this->approvalAction = '';

        session()->flash('success', 'Approval status updated successfully.');
    }

    private function canReview(): bool
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
