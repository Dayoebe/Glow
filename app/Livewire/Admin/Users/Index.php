<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
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
        $user = User::with(['staffMember.user', 'staffMember.oap'])->findOrFail($id);
        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot deactivate your own account.');
            return;
        }

        $activate = !$user->is_active;

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
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }

        $user->delete();

        session()->flash('success', 'User deleted successfully.');
        $this->resetPage();
    }

    public function getUsersProperty()
    {
        return User::query()
            ->with(['department', 'teamRole'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('role', 'like', "%{$this->search}%")
                    ->orWhereHas('department', function ($dept) {
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
        return view('livewire.admin.users.index', [
            'users' => $this->users,
        ])->layout('layouts.admin', ['header' => 'User Management']);
    }
}
