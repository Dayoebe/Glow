<?php

namespace App\Livewire\Admin\Team;

use App\Livewire\Concerns\RemembersAdminPagination;
use App\Models\Show\OAP;
use Livewire\Component;
use Livewire\WithPagination;

class Oaps extends Component
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
        $oap = OAP::findOrFail($id);
        $oap->is_active = !$oap->is_active;
        $oap->save();

        session()->flash('success', 'OAP status updated.');
    }

    public function toggleAvailability($id)
    {
        $oap = OAP::findOrFail($id);
        $oap->available = !$oap->available;
        $oap->save();

        session()->flash('success', 'OAP availability updated.');
    }

    public function deleteOap($id)
    {
        $oap = OAP::find($id);
        if ($oap) {
            $oap->delete();
            session()->flash('success', 'OAP deleted successfully.');
        }
    }

    public function getOapsProperty()
    {
        return OAP::query()
            ->with(['department', 'teamRole'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('phone', 'like', "%{$this->search}%")
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
        return view('livewire.admin.team.oaps', [
            'oaps' => $this->oaps,
        ])->layout('layouts.admin', ['header' => 'OAPs & Hosts']);
    }
}
