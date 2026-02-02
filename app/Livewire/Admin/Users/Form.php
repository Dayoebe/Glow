<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Models\Team\Department;
use App\Models\Team\Role as TeamRole;
use App\Support\CloudinaryUploader;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;

class Form extends Component
{
    use WithFileUploads;

    public $userId = null;
    public $isEditing = false;

    public $name = '';
    public $email = '';
    public $avatar = '';
    public $avatar_upload;
    public $bio = '';
    public $role = 'user';
    public $department_id = '';
    public $team_role_id = '';
    public $password = '';
    public $password_confirmation = '';
    public $is_active = true;
    public $departments = [];
    public $teamRoles = [];

    protected function rules()
    {
        $rules = [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'avatar' => 'nullable|url|max:500',
            'avatar_upload' => 'nullable|image|max:5120',
            'bio' => 'nullable|string|max:2000',
            'role' => 'required|in:admin,staff,user',
            'department_id' => 'required|exists:team_departments,id',
            'team_role_id' => 'required|exists:team_roles,id',
            'is_active' => 'boolean',
        ];

        if ($this->isEditing) {
            $rules['email'] = 'required|email|unique:users,email,' . $this->userId;
            $rules['password'] = 'nullable|min:6|confirmed';
        } else {
            $rules['password'] = 'required|min:6|confirmed';
        }

        return $rules;
    }

    public function mount($userId = null)
    {
        $this->departments = Department::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        if ($userId) {
            $user = User::findOrFail($userId);
            $this->userId = $user->id;
            $this->isEditing = true;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->avatar = $user->avatar ?? '';
            $this->bio = $user->bio ?? '';
            $this->role = $user->role ?? 'user';
            $this->department_id = $user->department_id;
            $this->team_role_id = $user->team_role_id;
            $this->is_active = $user->is_active;
        }

        $this->teamRoles = $this->department_id
            ? TeamRole::query()
                ->where('is_active', true)
                ->where('department_id', $this->department_id)
                ->orderBy('name')
                ->get()
            : collect();
    }

    public function updatedAvatarUpload()
    {
        $this->resetErrorBag('avatar_upload');
        $this->avatar = '';
        $this->validateOnly('avatar_upload');
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

    public function save()
    {
        $this->validate();

        $validRole = TeamRole::query()
            ->where('id', $this->team_role_id)
            ->where('department_id', $this->department_id)
            ->exists();

        if (!$validRole) {
            $this->addError('team_role_id', 'Selected role does not belong to the chosen department.');
            return;
        }

        $avatarPath = $this->avatar;
        if ($this->avatar_upload) {
            $avatarPath = CloudinaryUploader::uploadImage($this->avatar_upload, 'users/avatars');
        }

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $avatarPath ?: null,
            'bio' => $this->bio ?: null,
            'role' => $this->role,
            'department_id' => $this->department_id,
            'team_role_id' => $this->team_role_id,
            'is_active' => $this->is_active,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->isEditing) {
            $user = User::findOrFail($this->userId);
            $user->update($data);
            $message = 'User updated successfully.';
        } else {
            $user = User::create($data);
            $message = 'User created successfully.';
        }

        if (in_array($this->role, ['admin', 'staff'], true)) {
            $guardName = method_exists($user, 'getDefaultGuardName')
                ? $user->getDefaultGuardName()
                : config('auth.defaults.guard', 'web');
            Role::findOrCreate($this->role, $guardName);
            app(PermissionRegistrar::class)->forgetCachedPermissions();
            $user->syncRoles([$this->role]);
        } else {
            $user->syncRoles([]);
        }

        return redirect()->route('admin.users.index')->with('success', $message);
    }

    public function render()
    {
        return view('livewire.admin.users.form')
            ->layout('layouts.admin', [
                'header' => $this->isEditing ? 'Edit User' : 'Add User',
            ]);
    }
}
