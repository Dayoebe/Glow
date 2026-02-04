<?php

namespace App\Livewire\Admin\Team;

use App\Models\Staff\StaffMember;
use App\Models\Team\Department;
use App\Models\Team\Role as TeamRole;
use App\Support\CloudinaryUploader;
use App\Support\PersonProfileSync;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class StaffForm extends Component
{
    use WithFileUploads;

    public $staffId = null;
    public $isEditing = false;

    public $user_id = '';
    public $name = '';
    public $role = '';
    public $department = '';
    public $department_id = '';
    public $team_role_id = '';
    public $bio = '';
    public $photo_url = '';
    public $photo_upload;
    public $email = '';
    public $phone = '';
    public $employment_status = 'full-time';
    public $is_active = true;
    public $joined_date = '';
    public $departments = [];
    public $teamRoles = [];
    public $users = [];

    public $social_links = [
        'facebook' => '',
        'twitter' => '',
        'instagram' => '',
        'linkedin' => '',
    ];

    protected $rules = [
        'user_id' => 'nullable|exists:users,id',
        'name' => 'required|min:3|max:255',
        'role' => 'nullable|max:255',
        'department' => 'nullable|max:255',
        'department_id' => 'required|exists:team_departments,id',
        'team_role_id' => 'required|exists:team_roles,id',
        'bio' => 'nullable',
        'photo_url' => 'nullable|url',
        'photo_upload' => 'nullable|image|max:5120',
        'email' => 'nullable|email',
        'joined_date' => 'nullable|date',
        'employment_status' => 'required',
        'is_active' => 'boolean',
    ];

    public function mount($staffId = null)
    {
        $this->loadUsers($staffId);
        $this->departments = Department::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        if ($staffId) {
            $staff = StaffMember::findOrFail($staffId);
            $this->staffId = $staff->id;
            $this->isEditing = true;
            $this->user_id = $staff->user_id;
            $this->name = $staff->name;
            $this->role = $staff->role;
            $this->department = $staff->department;
            $this->department_id = $staff->department_id;
            $this->team_role_id = $staff->team_role_id;
            $this->bio = $staff->bio;
            $this->photo_url = $staff->photo_url;
            $this->email = $staff->email;
            $this->phone = $staff->phone;
            $this->employment_status = $staff->employment_status;
            $this->is_active = $staff->is_active;
            $this->joined_date = $staff->joined_date?->format('Y-m-d') ?? '';
            $this->social_links = $staff->social_links ?? $this->social_links;
        }

        $this->teamRoles = $this->department_id
            ? TeamRole::query()
                ->where('is_active', true)
                ->where('department_id', $this->department_id)
                ->orderBy('name')
                ->get()
            : collect();
    }

    private function loadUsers($staffId = null)
    {
        $query = User::query()->orderBy('name');

        if ($staffId) {
            $query->where(function ($query) use ($staffId) {
                $query->whereDoesntHave('staffMember')
                    ->orWhereHas('staffMember', function ($staffQuery) use ($staffId) {
                        $staffQuery->where('id', $staffId);
                    });
            });
        } else {
            $query->whereDoesntHave('staffMember');
        }

        $this->users = $query->get();
    }

    public function updatedUserId()
    {
        if (!$this->user_id) {
            return;
        }

        $user = User::find($this->user_id);
        if (!$user) {
            return;
        }

        $this->name = $user->name ?: $this->name;
        $this->email = $user->email ?: $this->email;
        $this->photo_url = $user->avatar ?: $this->photo_url;
        $this->department_id = $user->department_id ?: $this->department_id;
        $this->team_role_id = $user->team_role_id ?: $this->team_role_id;

        if ($this->department_id) {
            $this->teamRoles = TeamRole::query()
                ->where('is_active', true)
                ->where('department_id', $this->department_id)
                ->orderBy('name')
                ->get();
        } else {
            $this->teamRoles = collect();
        }
    }

    public function updatedDepartmentId()
    {
        $this->team_role_id = '';
        $this->teamRoles = $this->department_id
            ? TeamRole::query()
                ->where('is_active', true)
                ->where('department_id', $this->department_id)
                ->orderBy('name')
                ->get()
            : collect();
    }

    public function updatedPhotoUpload()
    {
        $this->resetErrorBag('photo_upload');
        $this->photo_url = '';
        $this->validateOnly('photo_upload');
    }

    public function save()
    {
        $rules = $this->rules;
        $rules['user_id'] = 'nullable|exists:users,id|unique:staff_members,user_id' . ($this->staffId ? ',' . $this->staffId . ',id' : '');
        $this->validate($rules);

        $validRole = TeamRole::query()
            ->where('id', $this->team_role_id)
            ->where('department_id', $this->department_id)
            ->exists();

        if (!$validRole) {
            $this->addError('team_role_id', 'Selected role does not belong to the chosen department.');
            return;
        }

        $slug = Str::slug($this->name);
        $slugExists = StaffMember::where('slug', $slug)
            ->when($this->isEditing, function ($query) {
                $query->where('id', '!=', $this->staffId);
            })
            ->exists();

        if ($slugExists) {
            $this->addError('name', 'A staff member with a similar name already exists.');
            return;
        }

        $photoPath = $this->photo_url;
        if ($this->photo_upload) {
            $photoPath = CloudinaryUploader::uploadImage($this->photo_upload, 'staff/photos');
        }

        $departmentName = Department::find($this->department_id)?->name;
        $roleName = TeamRole::find($this->team_role_id)?->name;

        $data = [
            'user_id' => $this->user_id ?: null,
            'name' => $this->name,
            'slug' => $slug,
            'role' => $roleName ?? $this->role,
            'department' => $departmentName ?? $this->department,
            'department_id' => $this->department_id,
            'team_role_id' => $this->team_role_id,
            'bio' => $this->bio,
            'photo_url' => $photoPath,
            'email' => $this->email,
            'phone' => $this->phone,
            'employment_status' => $this->employment_status,
            'is_active' => $this->is_active,
            'joined_date' => $this->joined_date ?: null,
            'social_links' => $this->social_links,
        ];

        if ($this->isEditing) {
            $staff = StaffMember::findOrFail($this->staffId);
            $staff->update($data);
            $message = 'Staff member updated successfully.';
        } else {
            $staff = StaffMember::create($data);
            $message = 'Staff member created successfully.';
        }

        PersonProfileSync::fromStaff($staff);

        return redirect()->route('admin.team.staff')->with('success', $message);
    }

    public function render()
    {
        return view('livewire.admin.team.staff-form')
            ->layout('layouts.admin', [
                'header' => $this->isEditing ? 'Edit Staff' : 'Add Staff',
            ]);
    }
}
