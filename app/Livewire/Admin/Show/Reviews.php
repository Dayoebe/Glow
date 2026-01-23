<?php

namespace App\Livewire\Admin\Show;

use App\Models\Show\Review;
use App\Models\Show\Show;
use Livewire\Component;
use Livewire\WithPagination;

class Reviews extends Component
{
    use WithPagination;

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
        $review = Review::find($reviewId);
        if (!$review) {
            return;
        }

        $review->is_approved = !$review->is_approved;
        $review->save();

        session()->flash('success', 'Review visibility updated.');
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

    public function render()
    {
        return view('livewire.admin.show.reviews', [
            'reviews' => $this->reviews,
            'shows' => $this->shows,
        ])->layout('layouts.admin', ['header' => 'Show Reviews']);
    }
}
