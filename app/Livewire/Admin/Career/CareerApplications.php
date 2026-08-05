<?php

namespace App\Livewire\Admin\Career;

use App\Models\Career\CareerApplication;
use App\Models\Career\CareerPosition;
use Livewire\Component;
use Livewire\WithPagination;

class CareerApplications extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterPosition = '';
    public $applicationType = '';
    public $sortBy = 'newest';

    public $notesApplicationId = null;
    public $admin_notes = '';
    public $showApplicationModal = false;
    public $selectedApplicationId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterPosition' => ['except' => ''],
        'sortBy' => ['except' => 'newest'],
    ];

    public function mount(?string $type = null): void
    {
        if ($type !== null && in_array($type, ['job', 'internship', 'volunteer'], true)) {
            $this->applicationType = $type;
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterPosition()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'filterStatus', 'filterPosition', 'sortBy']);
        $this->sortBy = 'newest';
        $this->resetPage();
    }

    public function openApplication(int $applicationId): void
    {
        $application = CareerApplication::with(['position', 'reviewedBy'])->findOrFail($applicationId);

        $this->selectedApplicationId = $application->id;
        $this->notesApplicationId = $application->id;
        $this->admin_notes = (string) $application->admin_notes;
        $this->showApplicationModal = true;
    }

    public function closeApplication(): void
    {
        $this->showApplicationModal = false;
        $this->selectedApplicationId = null;
        $this->notesApplicationId = null;
        $this->admin_notes = '';
        $this->resetErrorBag('admin_notes');
    }

    public function setStatus(int $applicationId, string $status): void
    {
        if (!in_array($status, ['new', 'reviewing', 'shortlisted', 'rejected', 'hired', 'archived'], true)) {
            return;
        }

        $application = CareerApplication::findOrFail($applicationId);

        $application->status = $status;
        $application->reviewed_by = auth()->id();
        $application->reviewed_at = now();
        $application->save();

        session()->flash('success', 'Application status updated.');
    }

    public function saveNotes(): void
    {
        if (!$this->notesApplicationId) {
            return;
        }

        $this->validate([
            'admin_notes' => 'nullable|string|max:5000',
        ]);

        $application = CareerApplication::findOrFail($this->notesApplicationId);

        $application->admin_notes = $this->admin_notes ?: null;
        $application->reviewed_by = auth()->id();
        $application->reviewed_at = now();
        $application->save();

        session()->flash('success', 'Notes saved successfully.');
    }

    public function deleteApplication(int $applicationId): void
    {
        $application = CareerApplication::findOrFail($applicationId);

        $application->delete();
        if ((int) $this->selectedApplicationId === $applicationId) {
            $this->closeApplication();
        }
        session()->flash('success', 'Application deleted successfully.');
    }

    public function getApplicationsProperty()
    {
        $query = CareerApplication::query()
            ->with(['position', 'reviewedBy']);

        if ($this->applicationType !== '') {
            $query->where('application_type', $this->applicationType);
        }

        if (!empty($this->search)) {
            $query->where(function ($inner) {
                $inner->where('full_name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('application_code', 'like', "%{$this->search}%")
                    ->orWhere('phone', 'like', "%{$this->search}%")
                    ->orWhere('location', 'like', "%{$this->search}%")
                    ->orWhere('department', 'like', "%{$this->search}%")
                    ->orWhere('skills', 'like', "%{$this->search}%")
                    ->orWhereHas('position', function ($positionQuery) {
                        $positionQuery->where('title', 'like', "%{$this->search}%");
                    });
            });
        }

        if (!empty($this->filterStatus)) {
            $query->where('status', $this->filterStatus);
        }

        if (!empty($this->filterPosition)) {
            $query->where('career_position_id', $this->filterPosition);
        }

        match ($this->sortBy) {
            'oldest' => $query->oldest('created_at'),
            'name' => $query->orderBy('full_name'),
            'status' => $query->orderBy('status')->latest('created_at'),
            default => $query->latest('created_at'),
        };

        return $query->paginate(12);
    }

    public function getSelectedApplicationProperty(): ?CareerApplication
    {
        if (!$this->selectedApplicationId) {
            return null;
        }

        return CareerApplication::with(['position', 'reviewedBy'])->find($this->selectedApplicationId);
    }

    public function getPositionsProperty()
    {
        return CareerPosition::query()
            ->orderBy('title')
            ->get(['id', 'title']);
    }

    public function getStatsProperty(): array
    {
        return [
            'total' => $this->statsQuery()->count(),
            'new' => $this->statsQuery()->where('status', 'new')->count(),
            'reviewing' => $this->statsQuery()->where('status', 'reviewing')->count(),
            'shortlisted' => $this->statsQuery()->where('status', 'shortlisted')->count(),
            'hired' => $this->statsQuery()->where('status', 'hired')->count(),
            'rejected' => $this->statsQuery()->where('status', 'rejected')->count(),
        ];
    }

    public function getHasFiltersProperty(): bool
    {
        return filled($this->search)
            || filled($this->filterStatus)
            || filled($this->filterPosition)
            || $this->sortBy !== 'newest';
    }

    private function statsQuery()
    {
        return CareerApplication::query()->when(
            $this->applicationType !== '',
            fn ($query) => $query->where('application_type', $this->applicationType)
        );
    }

    public function render()
    {
        return view('livewire.admin.career.applications', [
            'applications' => $this->applications,
            'positions' => $this->positions,
            'stats' => $this->stats,
            'selectedApplication' => $this->selectedApplication,
            'hasFilters' => $this->hasFilters,
        ])->layout('layouts.admin', [
            'header' => $this->applicationType === '' ? 'Career Applications' : ucfirst($this->applicationType) . ' Applications',
        ]);
    }
}
