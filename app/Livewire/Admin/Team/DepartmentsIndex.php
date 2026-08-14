<?php

namespace App\Livewire\Admin\Team;

use App\Livewire\Concerns\RemembersAdminPagination;
use App\Models\Team\Department;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentsIndex extends Component
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
        $department = Department::findOrFail($id);
        $department->is_active = !$department->is_active;
        $department->save();

        session()->flash('success', 'Department status updated.');
    }

    public function deleteDepartment($id)
    {
        $department = Department::find($id);
        if ($department) {
            $department->delete();
            session()->flash('success', 'Department deleted successfully.');
        }
    }

    public function getDepartmentsProperty()
    {
        return Department::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(12);
    }

    public function render()
    {
        return view('livewire.admin.team.departments-index', [
            'departments' => $this->departments,
        ])->layout('layouts.admin', ['header' => 'Departments']);
    }
}
