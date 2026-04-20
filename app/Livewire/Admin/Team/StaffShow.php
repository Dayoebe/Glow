<?php

namespace App\Livewire\Admin\Team;

use App\Models\Show\Show;
use App\Models\Staff\StaffMember;
use Livewire\Component;

class StaffShow extends Component
{
    public $staffId;

    public function mount($staffId): void
    {
        $this->staffId = $staffId;
    }

    public function toggleStatus(): void
    {
        $staff = StaffMember::with(['user', 'oap'])->findOrFail($this->staffId);

        if ($staff->is_active) {
            $staff->deactivateForOffboarding();
            session()->flash('success', 'Staff member marked inactive. Dashboard access disabled and OAP/program assignments removed.');
            return;
        }

        $staff->reactivateForStaff();
        session()->flash('success', 'Staff member reactivated. Reassign OAP and program duties manually if needed.');
    }

    public function getStaffProperty()
    {
        return StaffMember::query()
            ->with([
                'departmentRelation',
                'teamRole',
                'user',
                'oap.department',
                'oap.teamRole',
                'oap.shows.category',
                'oap.scheduleSlots.show',
            ])
            ->findOrFail($this->staffId);
    }

    public function render()
    {
        $staff = $this->staff;
        $coHostedShows = collect();

        if ($staff->oap) {
            $oapId = (int) $staff->oap->id;

            $coHostedShows = Show::query()
                ->with('category')
                ->whereNotNull('co_hosts')
                ->orderBy('title')
                ->get()
                ->filter(function (Show $show) use ($oapId) {
                    return collect($show->co_hosts ?: [])
                        ->map(fn ($hostId) => (int) $hostId)
                        ->contains($oapId);
                })
                ->values();
        }

        return view('livewire.admin.team.staff-show', [
            'staff' => $staff,
            'coHostedShows' => $coHostedShows,
        ])->layout('layouts.admin', ['header' => 'Staff Profile']);
    }
}
