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

    public $showNotesModal = false;
    public $notesApplicationId = null;
    public $admin_notes = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterPosition' => ['except' => ''],
    ];

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

    public function setStatus(int $applicationId, string $status): void
    {
        if (!in_array($status, ['new', 'reviewing', 'shortlisted', 'rejected', 'hired', 'archived'], true)) {
            return;
        }

        $application = CareerApplication::find($applicationId);
        if (!$application) {
            return;
        }

        $application->status = $status;
        $application->reviewed_by = auth()->id();
        $application->reviewed_at = now();
        $application->save();

        session()->flash('success', 'Application status updated.');
    }

    public function openNotesModal(int $applicationId): void
    {
        $application = CareerApplication::find($applicationId);
        if (!$application) {
            return;
        }

        $this->notesApplicationId = $applicationId;
        $this->admin_notes = (string) $application->admin_notes;
        $this->showNotesModal = true;
    }

    public function saveNotes(): void
    {
        if (!$this->notesApplicationId) {
            return;
        }

        $this->validate([
            'admin_notes' => 'nullable|string|max:5000',
        ]);

        $application = CareerApplication::find($this->notesApplicationId);
        if (!$application) {
            return;
        }

        $application->admin_notes = $this->admin_notes ?: null;
        $application->reviewed_by = auth()->id();
        $application->reviewed_at = now();
        $application->save();

        $this->showNotesModal = false;
        $this->notesApplicationId = null;
        $this->admin_notes = '';

        session()->flash('success', 'Notes saved successfully.');
    }

    public function closeNotesModal(): void
    {
        $this->showNotesModal = false;
        $this->notesApplicationId = null;
        $this->admin_notes = '';
    }

    public function deleteApplication(int $applicationId): void
    {
        $application = CareerApplication::find($applicationId);
        if (!$application) {
            return;
        }

        $application->delete();
        session()->flash('success', 'Application deleted successfully.');
    }

    public function getApplicationsProperty()
    {
        $query = CareerApplication::query()
            ->with(['position', 'reviewedBy'])
            ->latest('created_at');

        if (!empty($this->search)) {
            $query->where(function ($inner) {
                $inner->where('full_name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('application_code', 'like', "%{$this->search}%")
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

        return $query->paginate(12);
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
            'total' => CareerApplication::count(),
            'new' => CareerApplication::where('status', 'new')->count(),
            'reviewing' => CareerApplication::where('status', 'reviewing')->count(),
            'shortlisted' => CareerApplication::where('status', 'shortlisted')->count(),
            'hired' => CareerApplication::where('status', 'hired')->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.career.applications', [
            'applications' => $this->applications,
            'positions' => $this->positions,
            'stats' => $this->stats,
        ])->layout('layouts.admin', [
            'header' => 'Career Applications',
        ]);
    }
}
