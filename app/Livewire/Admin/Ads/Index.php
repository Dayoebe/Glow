<?php

namespace App\Livewire\Admin\Ads;

use App\Livewire\Concerns\RemembersAdminPagination;
use App\Models\Ads\Ad;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use RemembersAdminPagination, WithPagination;

    public $search = '';
    public $filterPlacement = '';

    protected $queryString = ['search', 'filterPlacement'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterPlacement()
    {
        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->is_active = !$ad->is_active;
        $ad->save();

        session()->flash('success', 'Ad status updated.');
    }

    public function deleteAd($id)
    {
        $ad = Ad::find($id);
        if ($ad) {
            $ad->delete();
            session()->flash('success', 'Ad deleted.');
        }
    }

    public function getAdsProperty()
    {
        return Ad::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->when($this->filterPlacement, function ($query) {
                $query->where('placement', $this->filterPlacement);
            })
            ->orderByDesc('priority')
            ->latest()
            ->paginate(12);
    }

    public function render()
    {
        return view('livewire.admin.ads.index', [
            'ads' => $this->ads,
        ])->layout('layouts.admin', ['header' => 'Jingles & Ads']);
    }
}
