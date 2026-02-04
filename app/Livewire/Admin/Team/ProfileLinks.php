<?php

namespace App\Livewire\Admin\Team;

use App\Models\Show\OAP;
use App\Models\Staff\StaffMember;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ProfileLinks extends Component
{
    use WithPagination;

    public $staff_member_id = '';
    public $user_id = '';
    public $oap_id = '';
    public $search = '';

    protected $queryString = ['search'];

    protected function rules()
    {
        return [
            'staff_member_id' => 'required|exists:staff_members,id',
            'user_id' => 'nullable|exists:users,id',
            'oap_id' => 'nullable|exists:oaps,id',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStaffMemberId($value): void
    {
        if (!$value) {
            $this->user_id = '';
            $this->oap_id = '';

            return;
        }

        $staff = StaffMember::query()->with('oap')->find($value);
        if (!$staff) {
            return;
        }

        $this->user_id = $staff->user_id ?: '';
        $this->oap_id = $staff->oap?->id ?: '';
    }

    public function loadForEdit(int $staffId): void
    {
        $staff = StaffMember::query()->with('oap')->findOrFail($staffId);

        $this->staff_member_id = (string) $staff->id;
        $this->user_id = $staff->user_id ? (string) $staff->user_id : '';
        $this->oap_id = $staff->oap?->id ? (string) $staff->oap->id : '';
        $this->resetErrorBag();
    }

    public function unlinkUser(int $staffId): void
    {
        $staff = StaffMember::findOrFail($staffId);
        $staff->update(['user_id' => null]);

        if ((int) $this->staff_member_id === (int) $staffId) {
            $this->user_id = '';
        }

        session()->flash('success', 'User link removed.');
    }

    public function unlinkOap(int $staffId): void
    {
        OAP::query()
            ->where('staff_member_id', $staffId)
            ->get()
            ->each(function (OAP $oap): void {
                $oap->update(['staff_member_id' => null]);
            });

        if ((int) $this->staff_member_id === (int) $staffId) {
            $this->oap_id = '';
        }

        session()->flash('success', 'OAP link removed.');
    }

    public function save(): void
    {
        $this->validate();

        $staff = StaffMember::findOrFail($this->staff_member_id);
        $userId = $this->user_id ?: null;
        $oapId = $this->oap_id ?: null;

        if ($userId) {
            $inUseByAnotherStaff = StaffMember::query()
                ->where('user_id', $userId)
                ->where('id', '!=', $staff->id)
                ->exists();

            if ($inUseByAnotherStaff) {
                $this->addError('user_id', 'Selected user is already linked to another staff profile.');

                return;
            }
        }

        if ($oapId) {
            $selectedOap = OAP::findOrFail($oapId);
            if ($selectedOap->staff_member_id && (int) $selectedOap->staff_member_id !== (int) $staff->id) {
                $this->addError('oap_id', 'Selected OAP is already linked to another staff profile.');

                return;
            }
        }

        DB::transaction(function () use ($staff, $userId, $oapId): void {
            $staff->update(['user_id' => $userId]);

            OAP::query()
                ->where('staff_member_id', $staff->id)
                ->when($oapId, function ($query) use ($oapId) {
                    $query->where('id', '!=', $oapId);
                })
                ->get()
                ->each(function (OAP $oap): void {
                    $oap->update(['staff_member_id' => null]);
                });

            if ($oapId) {
                $oap = OAP::findOrFail($oapId);
                if ((int) $oap->staff_member_id !== (int) $staff->id) {
                    $oap->update(['staff_member_id' => $staff->id]);
                }
            }
        });

        session()->flash('success', 'Links saved successfully.');
    }

    public function getStaffMembersProperty()
    {
        return StaffMember::query()
            ->with(['user', 'oap', 'departmentRelation', 'teamRole'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($userQuery) {
                            $userQuery->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('oap', function ($oapQuery) {
                            $oapQuery->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->orderBy('name')
            ->paginate(12);
    }

    public function getStaffOptionsProperty()
    {
        return StaffMember::query()
            ->orderBy('name')
            ->get();
    }

    public function getAvailableUsersProperty()
    {
        return User::query()
            ->orderBy('name')
            ->where(function ($query) {
                $query->whereDoesntHave('staffMember');

                if ($this->staff_member_id) {
                    $query->orWhereHas('staffMember', function ($staffQuery) {
                        $staffQuery->where('id', $this->staff_member_id);
                    });
                }
            })
            ->get();
    }

    public function getAvailableOapsProperty()
    {
        return OAP::query()
            ->orderBy('name')
            ->where(function ($query) {
                $query->whereNull('staff_member_id');

                if ($this->staff_member_id) {
                    $query->orWhere('staff_member_id', $this->staff_member_id);
                }
            })
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.team.profile-links', [
            'staffMembers' => $this->staffMembers,
            'staffOptions' => $this->staffOptions,
            'availableUsers' => $this->availableUsers,
            'availableOaps' => $this->availableOaps,
        ])->layout('layouts.admin', ['header' => 'Profile Links']);
    }
}
