<?php

namespace App\Livewire\Admin\Team;

use App\Models\Staff\StaffMember;
use App\Models\Team\Department;
use App\Models\Team\Role as TeamRole;
use Livewire\Component;
use Livewire\WithPagination;

class StaffIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'latest';
    public $departmentId = '';
    public $roleId = '';
    public $employmentStatus = '';
    public $oapStatus = 'all';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'latest'],
        'departmentId' => ['except' => ''],
        'roleId' => ['except' => ''],
        'employmentStatus' => ['except' => ''],
        'oapStatus' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function updatingRoleId()
    {
        $this->resetPage();
    }

    public function updatingEmploymentStatus()
    {
        $this->resetPage();
    }

    public function updatingOapStatus()
    {
        $this->resetPage();
    }

    public function updatedDepartmentId()
    {
        $this->roleId = '';
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset([
            'search',
            'sortBy',
            'departmentId',
            'roleId',
            'employmentStatus',
            'oapStatus',
        ]);

        $this->sortBy = 'latest';
        $this->oapStatus = 'all';
        $this->resetPage();
    }

    public function deactivateStaff($id)
    {
        $staff = StaffMember::with(['user', 'oap'])->findOrFail($id);

        if (!$staff->is_active) {
            return;
        }

        $staff->deactivateForOffboarding();
        session()->flash('success', 'Staff member deactivated and removed from the active staff directory. Reactivate their account from Users to add them back.');

        $this->resetPage();
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
        $query = StaffMember::query()
            ->with(['departmentRelation', 'teamRole', 'user', 'oap'])
            ->activeDirectory()
            ->when($this->search, function ($query) {
                $search = trim($this->search);

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%")
                        ->orWhere('department', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhereHas('departmentRelation', function ($dept) use ($search) {
                            $dept->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('teamRole', function ($role) use ($search) {
                            $role->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('oap', function ($oap) use ($search) {
                            $oap->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($this->departmentId, function ($query) {
                $query->where('department_id', $this->departmentId);
            })
            ->when($this->roleId, function ($query) {
                $query->where('team_role_id', $this->roleId);
            })
            ->when($this->employmentStatus, function ($query) {
                $query->where('employment_status', $this->employmentStatus);
            })
            ->when($this->oapStatus === 'linked', function ($query) {
                $query->whereHas('oap');
            })
            ->when($this->oapStatus === 'active', function ($query) {
                $query->whereHas('oap', function ($oap) {
                    $oap->where('is_active', true);
                });
            })
            ->when($this->oapStatus === 'inactive', function ($query) {
                $query->whereHas('oap', function ($oap) {
                    $oap->where('is_active', false);
                });
            })
            ->when($this->oapStatus === 'none', function ($query) {
                $query->whereDoesntHave('oap');
            });

        return $this->applySort($query)->paginate(12);
    }

    public function getDepartmentsProperty()
    {
        return Department::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function getTeamRolesProperty()
    {
        return TeamRole::query()
            ->where('is_active', true)
            ->when($this->departmentId, function ($query) {
                $query->where('department_id', $this->departmentId);
            })
            ->orderBy('name')
            ->get();
    }

    public function getHasFiltersProperty(): bool
    {
        return filled($this->search)
            || $this->sortBy !== 'latest'
            || filled($this->departmentId)
            || filled($this->roleId)
            || filled($this->employmentStatus)
            || $this->oapStatus !== 'all';
    }

    private function applySort($query)
    {
        return match ($this->sortBy) {
            'oldest' => $query->oldest(),
            'name_asc' => $query->orderBy('name'),
            'name_desc' => $query->orderByDesc('name'),
            'department' => $query->orderBy('department')->orderBy('name'),
            'role' => $query->orderBy('role')->orderBy('name'),
            default => $query->latest(),
        };
    }

    public function getStatsProperty(): array
    {
        $activeStaff = StaffMember::query()->activeDirectory();

        return [
            'total' => (clone $activeStaff)->count(),
            'departments' => (clone $activeStaff)->whereNotNull('department_id')->distinct()->count('department_id'),
            'oaps' => (clone $activeStaff)->whereHas('oap', fn ($query) => $query->where('is_active', true))->count(),
            'with_login' => (clone $activeStaff)->whereHas('user', fn ($query) => $query->where('is_active', true))->count(),
        ];
    }

    public function getEmploymentStatusesProperty(): array
    {
        return [
            'full-time' => 'Full-time',
            'part-time' => 'Part-time',
            'contract' => 'Contract',
            'freelance' => 'Freelance',
        ];
    }

    public function render()
    {
        return view('livewire.admin.team.staff-index', [
            'staffMembers' => $this->staff,
            'departments' => $this->departments,
            'teamRoles' => $this->teamRoles,
            'employmentStatuses' => $this->employmentStatuses,
            'hasFilters' => $this->hasFilters,
            'stats' => $this->stats,
        ])->layout('layouts.admin', ['header' => 'Staff']);
    }
}
