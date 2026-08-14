<?php

namespace App\Livewire\Admin\Team;

use App\Livewire\Concerns\RemembersAdminPagination;
use App\Models\Team\Role;
use Livewire\Component;
use Livewire\WithPagination;

class RolesIndex extends Component
{
    use RemembersAdminPagination, WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        $role = Role::findOrFail($id);
        $role->is_active = !$role->is_active;
        $role->save();

        session()->flash('success', 'Role status updated.');
    }

    public function deleteRole($id)
    {
        $role = Role::find($id);
        if ($role) {
            $role->delete();
            session()->flash('success', 'Role deleted successfully.');
        }
    }

    public function getRolesProperty()
    {
        return Role::query()
            ->with('department')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%")
                    ->orWhereHas('department', function ($dept) {
                        $dept->where('name', 'like', "%{$this->search}%");
                    });
            })
            ->latest()
            ->paginate(12);
    }

    public function render()
    {
        return view('livewire.admin.team.roles-index', [
            'roles' => $this->roles,
        ])->layout('layouts.admin', ['header' => 'Roles']);
    }
}
