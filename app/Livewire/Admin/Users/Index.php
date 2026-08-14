<?php

namespace App\Livewire\Admin\Users;

use App\Livewire\Concerns\RemembersAdminPagination;
use App\Models\Team\Department;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use RemembersAdminPagination, WithPagination;

    public $search = '';
    public $status = 'all';
    public $role = '';
    public $departmentId = '';
    public $sortBy = 'latest';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'all'],
        'role' => ['except' => ''],
        'departmentId' => ['except' => ''],
        'sortBy' => ['except' => 'latest'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedRole(): void
    {
        $this->resetPage();
    }

    public function updatedDepartmentId(): void
    {
        $this->resetPage();
    }

    public function updatedSortBy(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'status', 'role', 'departmentId', 'sortBy']);
        $this->status = 'all';
        $this->sortBy = 'latest';
        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        $user = User::with(['staffMember.user', 'staffMember.oap'])->findOrFail($id);
        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot deactivate your own account.');
            return;
        }

        $activate = !$user->is_active;

        if (!$activate && $this->isLastActiveAdmin($user)) {
            session()->flash('error', 'You cannot deactivate the last active administrator.');
            return;
        }

        if ($user->staffMember) {
            if ($activate) {
                $user->staffMember->reactivateForStaff();
            } else {
                $user->staffMember->deactivateForOffboarding();
            }
        } else {
            $user->forceFill(['is_active' => $activate])->save();
        }

        session()->flash('success', $activate
            ? 'User activated. Their linked staff profile is visible again.'
            : 'User deactivated. Their linked staff profile was removed from the active directory.');
    }

    public function deleteUser($id)
    {
        $user = User::with(['staffMember.user', 'staffMember.oap'])->findOrFail($id);

        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }

        if ($this->isLastActiveAdmin($user)) {
            session()->flash('error', 'You cannot delete the last active administrator.');
            return;
        }

        if ($user->staffMember?->is_active) {
            $user->staffMember->deactivateForOffboarding();
        }

        $user->delete();

        session()->flash('success', 'User deleted successfully. Any linked staff profile was deactivated and preserved.');
        $this->resetPage();
    }

    public function getUsersProperty()
    {
        return User::query()
            ->with(['department', 'teamRole', 'staffMember'])
            ->when($this->search, function ($query) {
                $search = trim($this->search);

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%")
                        ->orWhereHas('department', function ($department) use ($search) {
                            $department->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('teamRole', function ($role) use ($search) {
                            $role->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($this->status === 'active', fn ($query) => $query->where('is_active', true))
            ->when($this->status === 'inactive', fn ($query) => $query->where('is_active', false))
            ->when($this->role, fn ($query) => $query->where('role', $this->role))
            ->when($this->departmentId, fn ($query) => $query->where('department_id', $this->departmentId))
            ->when($this->sortBy === 'oldest', fn ($query) => $query->oldest())
            ->when($this->sortBy === 'name_asc', fn ($query) => $query->orderBy('name'))
            ->when($this->sortBy === 'name_desc', fn ($query) => $query->orderByDesc('name'))
            ->when($this->sortBy === 'latest', fn ($query) => $query->latest())
            ->paginate(12);
    }

    public function getDepartmentsProperty()
    {
        return Department::query()->orderBy('name')->get(['id', 'name']);
    }

    public function getStatsProperty(): array
    {
        return [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'admins' => User::where('is_active', true)->where('role', 'admin')->count(),
            'linked_staff' => User::whereHas('staffMember', fn ($query) => $query->where('is_active', true))->count(),
        ];
    }

    public function getHasFiltersProperty(): bool
    {
        return filled($this->search)
            || $this->status !== 'all'
            || filled($this->role)
            || filled($this->departmentId)
            || $this->sortBy !== 'latest';
    }

    private function isLastActiveAdmin(User $user): bool
    {
        return $user->is_active
            && $user->role === 'admin'
            && User::query()->where('is_active', true)->where('role', 'admin')->count() <= 1;
    }

    public function render()
    {
        return view('livewire.admin.users.index', [
            'users' => $this->users,
            'departments' => $this->departments,
            'stats' => $this->stats,
            'hasFilters' => $this->hasFilters,
        ])->layout('layouts.admin', ['header' => 'User Management']);
    }
}
