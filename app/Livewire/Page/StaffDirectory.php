<?php

namespace App\Livewire\Page;

use App\Models\Staff\StaffMember;
use Livewire\Component;
use Livewire\WithPagination;

class StaffDirectory extends Component
{
    use WithPagination;

    public $searchQuery = '';

    protected $queryString = [
        'searchQuery' => ['except' => ''],
    ];

    public function updatingSearchQuery()
    {
        $this->resetPage();
    }

    public function getStaffProfilesProperty()
    {
        $staff = StaffMember::query()
            ->with(['departmentRelation', 'teamRole'])
            ->where('is_active', true)
            ->when($this->searchQuery, function ($query) {
                $query->where(function ($search) {
                    $search->where('name', 'like', "%{$this->searchQuery}%")
                        ->orWhere('role', 'like', "%{$this->searchQuery}%")
                        ->orWhere('department', 'like', "%{$this->searchQuery}%")
                        ->orWhereHas('departmentRelation', function ($dept) {
                            $dept->where('name', 'like', "%{$this->searchQuery}%");
                        })
                        ->orWhereHas('teamRole', function ($role) {
                            $role->where('name', 'like', "%{$this->searchQuery}%");
                        });
                });
            })
            ->orderBy('name')
            ->paginate(12);

        $staff->getCollection()->transform(function ($staff) {
            return [
                'type' => 'staff',
                'id' => $staff->id,
                'name' => $staff->name,
                'slug' => $staff->slug,
                'role' => $staff->teamRole?->name ?? ($staff->role ?? 'Staff Member'),
                'department' => $staff->departmentRelation?->name ?? ($staff->department ?? 'General'),
                'photo' => $staff->photo_url,
                'bio' => $staff->bio,
                'email' => $staff->email,
                'phone' => $staff->phone,
                'social_links' => $staff->social_links ?? [],
                'profile_url' => route('staff.profile', ['type' => 'staff', 'identifier' => $staff->slug]),
            ];
        });

        return $staff;
    }

    public function render()
    {
        return view('livewire.page.staff-directory', [
            'staffProfiles' => $this->staffProfiles,
        ])->layout('layouts.app', ['title' => 'Staff Directory - Glow FM']);
    }
}
