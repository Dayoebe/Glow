<?php

namespace App\Livewire\Admin\Approvals;

use App\Models\Setting;
use App\Models\Staff\StaffMember;
use Livewire\Component;

class ApproverSettings extends Component
{
    public $staffMembers = [];
    public $approver_ids = [];
    public bool $news_approval_mail_enabled = true;

    public function mount()
    {
        $this->staffMembers = StaffMember::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $this->approver_ids = Setting::get('content_approvers.ids', []);
        $this->news_approval_mail_enabled = (bool) Setting::get('content_approvers.news_approval_mail_enabled', true);
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

        $settings = (array) Setting::get('content_approvers', []);

        Setting::set('content_approvers', array_replace($settings, [
            'ids' => $validIds,
            'news_approval_mail_enabled' => (bool) $this->news_approval_mail_enabled,
        ]), 'content_approvers');

        $this->approver_ids = $validIds;

        session()->flash('success', 'Content approvers and notification settings updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.approvals.approver-settings')
            ->layout('layouts.admin', ['header' => 'Content Approvers']);
    }
}
