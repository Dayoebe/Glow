<?php

namespace App\Livewire\Page;

use App\Models\Career\CareerPosition;
use Livewire\Component;
use Livewire\WithPagination;

class CareerPage extends Component
{
    use WithPagination;

    public $search = '';
    public $department = '';
    public $employmentType = '';
    public $workplaceType = '';
    public $sortBy = 'latest';

    protected $queryString = [
        'search' => ['except' => ''],
        'department' => ['except' => ''],
        'employmentType' => ['except' => ''],
        'workplaceType' => ['except' => ''],
        'sortBy' => ['except' => 'latest'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDepartment()
    {
        $this->resetPage();
    }

    public function updatingEmploymentType()
    {
        $this->resetPage();
    }

    public function updatingWorkplaceType()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function getPositionsProperty()
    {
        $query = CareerPosition::query()
            ->published()
            ->withCount('applications');

        if (!empty($this->search)) {
            $query->search($this->search);
        }

        if (!empty($this->department)) {
            $query->where('department', $this->department);
        }

        if (!empty($this->employmentType)) {
            $query->where('employment_type', $this->employmentType);
        }

        if (!empty($this->workplaceType)) {
            $query->where('workplace_type', $this->workplaceType);
        }

        switch ($this->sortBy) {
            case 'deadline':
                $query->orderByRaw('application_deadline is null')
                    ->orderBy('application_deadline');
                break;
            case 'salary':
                $query->orderByDesc('max_salary')
                    ->orderByDesc('min_salary');
                break;
            case 'oldest':
                $query->orderBy('published_at');
                break;
            default:
                $query->latest('published_at')->latest('created_at');
                break;
        }

        return $query->paginate(9);
    }

    public function getFeaturedPositionsProperty()
    {
        return CareerPosition::query()
            ->published()
            ->featured()
            ->acceptingApplications()
            ->latest('published_at')
            ->take(2)
            ->get();
    }

    public function getDepartmentsProperty()
    {
        return CareerPosition::query()
            ->published()
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->orderBy('department')
            ->pluck('department')
            ->values();
    }

    public function getEmploymentTypesProperty()
    {
        return CareerPosition::query()
            ->published()
            ->whereNotNull('employment_type')
            ->distinct()
            ->orderBy('employment_type')
            ->pluck('employment_type')
            ->values();
    }

    public function getWorkplaceTypesProperty()
    {
        return CareerPosition::query()
            ->published()
            ->whereNotNull('workplace_type')
            ->distinct()
            ->orderBy('workplace_type')
            ->pluck('workplace_type')
            ->values();
    }

    public function render()
    {
        return view('livewire.page.career-page', [
            'positions' => $this->positions,
            'featuredPositions' => $this->featuredPositions,
            'departments' => $this->departments,
            'employmentTypes' => $this->employmentTypes,
            'workplaceTypes' => $this->workplaceTypes,
        ])->layout('layouts.app', [
            'title' => 'Careers - Glow FM',
        ]);
    }
}
