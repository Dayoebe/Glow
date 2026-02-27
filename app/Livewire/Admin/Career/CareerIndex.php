<?php

namespace App\Livewire\Admin\Career;

use App\Models\Career\CareerPosition;
use Livewire\Component;
use Livewire\WithPagination;

class CareerIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterDepartment = '';
    public $filterType = '';
    public $showDeleteModal = false;
    public $positionToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterDepartment' => ['except' => ''],
        'filterType' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterDepartment()
    {
        $this->resetPage();
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function confirmDelete(int $positionId): void
    {
        $this->positionToDelete = $positionId;
        $this->showDeleteModal = true;
    }

    public function deletePosition(): void
    {
        if (!$this->positionToDelete) {
            return;
        }

        $position = CareerPosition::find($this->positionToDelete);
        if ($position) {
            $position->delete();
            session()->flash('success', 'Career position deleted successfully.');
        }

        $this->showDeleteModal = false;
        $this->positionToDelete = null;
    }

    public function togglePublish(int $positionId): void
    {
        $position = CareerPosition::find($positionId);
        if (!$position) {
            return;
        }

        $position->is_published = !$position->is_published;
        $position->published_at = $position->is_published ? ($position->published_at ?: now()) : null;
        $position->updated_by = auth()->id();
        $position->save();

        session()->flash('success', $position->is_published
            ? 'Position published successfully.'
            : 'Position moved to draft.');
    }

    public function toggleFeatured(int $positionId): void
    {
        $position = CareerPosition::find($positionId);
        if (!$position) {
            return;
        }

        $position->is_featured = !$position->is_featured;
        $position->updated_by = auth()->id();
        $position->save();

        session()->flash('success', 'Featured flag updated.');
    }

    public function setStatus(int $positionId, string $status): void
    {
        if (!in_array($status, ['open', 'closed', 'paused'], true)) {
            return;
        }

        $position = CareerPosition::find($positionId);
        if (!$position) {
            return;
        }

        $position->status = $status;
        $position->updated_by = auth()->id();
        $position->save();

        session()->flash('success', 'Position status updated.');
    }

    public function getPositionsProperty()
    {
        $query = CareerPosition::query()
            ->with(['creator'])
            ->withCount('applications')
            ->latest('created_at');

        if (!empty($this->search)) {
            $query->search($this->search);
        }

        if (!empty($this->filterDepartment)) {
            $query->where('department', $this->filterDepartment);
        }

        if (!empty($this->filterType)) {
            $query->where('employment_type', $this->filterType);
        }

        if ($this->filterStatus === 'published') {
            $query->where('is_published', true);
        } elseif ($this->filterStatus === 'draft') {
            $query->where('is_published', false);
        } elseif ($this->filterStatus === 'featured') {
            $query->where('is_featured', true);
        } elseif (in_array($this->filterStatus, ['open', 'closed', 'paused'], true)) {
            $query->where('status', $this->filterStatus);
        }

        return $query->paginate(10);
    }

    public function getStatsProperty(): array
    {
        return [
            'total' => CareerPosition::count(),
            'published' => CareerPosition::where('is_published', true)->count(),
            'draft' => CareerPosition::where('is_published', false)->count(),
            'open' => CareerPosition::where('status', 'open')->count(),
            'applications' => \App\Models\Career\CareerApplication::count(),
        ];
    }

    public function getDepartmentOptionsProperty()
    {
        return CareerPosition::query()
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');
    }

    public function getTypeOptionsProperty()
    {
        return CareerPosition::query()
            ->whereNotNull('employment_type')
            ->distinct()
            ->orderBy('employment_type')
            ->pluck('employment_type');
    }

    public function render()
    {
        return view('livewire.admin.career.index', [
            'positions' => $this->positions,
            'stats' => $this->stats,
            'departmentOptions' => $this->departmentOptions,
            'typeOptions' => $this->typeOptions,
        ])->layout('layouts.admin', [
            'header' => 'Career Positions',
        ]);
    }
}
