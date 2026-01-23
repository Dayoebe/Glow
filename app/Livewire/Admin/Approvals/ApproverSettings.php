<?php

namespace App\Livewire\Admin\Approvals;

use App\Models\Setting;
use App\Models\Staff\StaffMember;
use Livewire\Component;

class ApproverSettings extends Component
{
    public $staffMembers = [];
    public $approver_ids = [];

    public function mount()
    {
        $this->staffMembers = StaffMember::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $this->approver_ids = Setting::get('content_approvers.ids', []);
    }

    public function save()
    {
        $validIds = collect($this->approver_ids)
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $validIds = StaffMember::query()
            ->whereIn('id', $validIds)
            ->pluck('id')
            ->all();

        Setting::set('content_approvers', [
            'ids' => $validIds,
        ], 'content_approvers');

        $this->approver_ids = $validIds;

        session()->flash('success', 'Content approvers updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.approvals.approver-settings')
            ->layout('layouts.admin', ['header' => 'Content Approvers']);
    }
}
