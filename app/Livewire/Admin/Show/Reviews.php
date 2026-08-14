<?php

namespace App\Livewire\Admin\Show;

use App\Livewire\Concerns\RemembersAdminPagination;
use App\Models\Show\Review;
use App\Models\Show\Show;
use App\Models\Show\Category;
use App\Models\Show\OAP;
use App\Models\Show\ScheduleSlot;
use App\Models\Show\Segment;
use Livewire\Component;
use Livewire\WithPagination;

class Reviews extends Component
{
    use RemembersAdminPagination, WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterShow = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterShow' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterShow()
    {
        $this->resetPage();
    }

    public function toggleApproval($reviewId)
    {
        $review = Review::findOrFail($reviewId);

        $review->is_approved = !$review->is_approved;
        $review->save();

        session()->flash('success', 'Review visibility updated.');
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'filterStatus', 'filterShow']);
        $this->resetPage();
    }

    public function getReviewsProperty()
    {
        $query = Review::with(['show', 'user'])->latest();

        if ($this->filterShow) {
            $query->where('show_id', $this->filterShow);
        }

        if ($this->filterStatus === 'visible') {
            $query->where('is_approved', true);
        } elseif ($this->filterStatus === 'hidden') {
            $query->where('is_approved', false);
        }

        if (!empty($this->search)) {
            $query->where(function ($inner) {
                $inner->where('review', 'like', "%{$this->search}%")
                    ->orWhereHas('show', function ($showQuery) {
                        $showQuery->where('title', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('user', function ($userQuery) {
                        $userQuery->where('name', 'like', "%{$this->search}%");
                    });
            });
        }

        return $query->paginate(12);
    }

    public function getShowsProperty()
    {
        return Show::orderBy('title')->get();
    }

    public function getStatsProperty(): array
    {
        return [
            'total' => Review::count(),
            'visible' => Review::where('is_approved', true)->count(),
            'hidden' => Review::where('is_approved', false)->count(),
            'average' => round((float) Review::avg('rating'), 1),
        ];
    }

    public function getWorkspaceCountsProperty(): array
    {
        return [
            'shows' => Show::count(),
            'oaps' => OAP::count(),
            'schedule' => ScheduleSlot::active()->count(),
            'segments' => Segment::count(),
            'categories' => Category::count(),
            'reviews' => Review::count(),
        ];
    }

    public function getHasFiltersProperty(): bool
    {
        return filled($this->search) || filled($this->filterStatus) || filled($this->filterShow);
    }

    public function render()
    {
        return view('livewire.admin.show.reviews', [
            'reviews' => $this->reviews,
            'shows' => $this->shows,
            'stats' => $this->stats,
            'workspaceCounts' => $this->workspaceCounts,
            'hasFilters' => $this->hasFilters,
        ])->layout('layouts.admin', ['header' => 'Show Reviews']);
    }
}
