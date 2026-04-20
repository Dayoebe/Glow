<?php

namespace App\Livewire\Admin\Team;

use App\Models\Staff\StaffMember;
use Livewire\Component;
use Livewire\WithPagination;

class StaffIndex extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        $staff = StaffMember::with(['user', 'oap'])->findOrFail($id);

        if ($staff->is_active) {
            $staff->deactivateForOffboarding();
            session()->flash('success', 'Staff member marked inactive. Dashboard access disabled and OAP/program assignments removed.');
            return;
        }

        $staff->reactivateForStaff();
        session()->flash('success', 'Staff member reactivated. Reassign OAP and program duties manually if needed.');
    }

    public function deleteStaff($id)
    {
        $staff = StaffMember::find($id);
        if ($staff) {
            $staff->delete();
            session()->flash('success', 'Staff member deleted successfully.');
        }
    }

    public function getStaffProperty()
    {
        return StaffMember::query()
            ->with(['departmentRelation', 'teamRole', 'user', 'oap'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('role', 'like', "%{$this->search}%")
                    ->orWhere('department', 'like', "%{$this->search}%")
                    ->orWhereHas('departmentRelation', function ($dept) {
                        $dept->where('name', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('teamRole', function ($role) {
                        $role->where('name', 'like', "%{$this->search}%");
                    });
            })
            ->latest()
            ->paginate(12);
    }

    public function render()
    {
        return view('livewire.admin.team.staff-index', [
            'staffMembers' => $this->staff,
        ])->layout('layouts.admin', ['header' => 'Staff']);
    }
}
