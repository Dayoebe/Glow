<?php

namespace App\Livewire\Admin\Vettas;

use App\Models\Vettas\VettasReservation;
use Livewire\Component;
use Livewire\WithPagination;

class Reservations extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatus = '';
    public string $filterTimeline = 'upcoming';
    public bool $showNotesModal = false;
    public ?int $notesReservationId = null;
    public string $admin_notes = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterTimeline' => ['except' => 'upcoming'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatingFilterTimeline(): void
    {
        $this->resetPage();
    }

    public function setStatus(int $reservationId, string $status): void
    {
        if (!array_key_exists($status, $this->statusOptions())) {
            return;
        }

        $reservation = VettasReservation::find($reservationId);
        if (!$reservation) {
            return;
        }

        $reservation->status = $status;
        $reservation->reviewed_by = auth()->id();
        $reservation->reviewed_at = now();
        $reservation->save();

        session()->flash('success', 'Reservation status updated.');
    }

    public function openNotesModal(int $reservationId): void
    {
        $reservation = VettasReservation::find($reservationId);
        if (!$reservation) {
            return;
        }

        $this->notesReservationId = $reservationId;
        $this->admin_notes = (string) $reservation->admin_notes;
        $this->showNotesModal = true;
    }

    public function saveNotes(): void
    {
        if (!$this->notesReservationId) {
            return;
        }

        $this->validate([
            'admin_notes' => 'nullable|string|max:5000',
        ]);

        $reservation = VettasReservation::find($this->notesReservationId);
        if (!$reservation) {
            return;
        }

        $reservation->admin_notes = $this->admin_notes ?: null;
        $reservation->reviewed_by = auth()->id();
        $reservation->reviewed_at = now();
        $reservation->save();

        $this->closeNotesModal();

        session()->flash('success', 'Reservation notes saved successfully.');
    }

    public function closeNotesModal(): void
    {
        $this->showNotesModal = false;
        $this->notesReservationId = null;
        $this->admin_notes = '';
    }

    public function deleteReservation(int $reservationId): void
    {
        $reservation = VettasReservation::find($reservationId);
        if (!$reservation) {
            return;
        }

        $reservation->delete();

        session()->flash('success', 'Reservation deleted successfully.');
    }

    public function getReservationsProperty()
    {
        $query = VettasReservation::query()
            ->with(['user', 'reviewedBy'])
            ->latest('created_at');

        if ($this->search !== '') {
            $query->search($this->search);
        }

        if ($this->filterStatus !== '') {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterTimeline === 'upcoming') {
            $query->whereDate('check_out_date', '>=', now()->toDateString());
        } elseif ($this->filterTimeline === 'past') {
            $query->whereDate('check_out_date', '<', now()->toDateString());
        }

        return $query->paginate(12);
    }

    public function getStatsProperty(): array
    {
        $today = now()->toDateString();

        return [
            'total' => VettasReservation::count(),
            'new' => VettasReservation::where('status', 'new')->count(),
            'confirmed' => VettasReservation::where('status', 'confirmed')->count(),
            'upcoming' => VettasReservation::whereDate('check_out_date', '>=', $today)->count(),
            'completed' => VettasReservation::where('status', 'completed')->count(),
        ];
    }

    public function getStatusOptionsProperty(): array
    {
        return $this->statusOptions();
    }

    private function statusOptions(): array
    {
        return [
            'new' => 'New',
            'contacted' => 'Contacted',
            'confirmed' => 'Confirmed',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];
    }

    public function render()
    {
        return view('livewire.admin.vettas.reservations', [
            'reservations' => $this->reservations,
            'stats' => $this->stats,
            'statusOptions' => $this->statusOptions,
        ])->layout('layouts.admin', [
            'header' => 'Vettas Reservations',
        ]);
    }
}
