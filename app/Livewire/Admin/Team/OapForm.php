<?php

namespace App\Livewire\Admin\Team;

use App\Models\Show\OAP;
use App\Models\Staff\StaffMember;
use App\Models\Team\Department;
use App\Models\Team\Role as TeamRole;
use App\Support\CloudinaryUploader;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class OapForm extends Component
{
    use WithFileUploads;

    public $oapId = null;
    public $isEditing = false;

    public $staff_member_id = '';
    public $name = '';
    public $bio = '';
    public $profile_photo = '';
    public $profile_photo_upload;
    public $voice_sample_url = '';
    public $specializations = '';
    public $email = '';
    public $department_id = '';
    public $team_role_id = '';
    public $phone = '';
    public $employment_status = 'full-time';
    public $is_active = true;
    public $available = true;
    public $joined_date = '';
    public $departments = [];
    public $teamRoles = [];
    public $staffMembers = [];

    public $social_media = [
        'facebook' => '',
        'twitter' => '',
        'instagram' => '',
        'tiktok' => '',
        'linkedin' => '',
        'youtube' => '',
    ];

    protected function rules()
    {
        return [
            'staff_member_id' => [
                'required',
                'exists:staff_members,id',
                Rule::unique('oaps', 'staff_member_id')->ignore($this->oapId),
            ],
            'name' => 'required|min:3|max:255',
            'bio' => 'required|min:10',
            'profile_photo' => 'nullable|url',
            'profile_photo_upload' => 'nullable|image|max:5120',
            'voice_sample_url' => 'nullable|url',
            'email' => 'nullable|email',
            'department_id' => 'required|exists:team_departments,id',
            'team_role_id' => 'required|exists:team_roles,id',
            'joined_date' => 'nullable|date',
            'employment_status' => 'required',
            'is_active' => 'boolean',
            'available' => 'boolean',
        ];
    }

    public function mount($oapId = null)
    {
        $this->departments = Department::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        if ($oapId) {
            $oap = OAP::findOrFail($oapId);
            $this->oapId = $oap->id;
            $this->isEditing = true;
            $this->staff_member_id = $oap->staff_member_id;
            $this->name = $oap->name;
            $this->bio = $oap->bio;
            $this->profile_photo = $oap->profile_photo;
            $this->voice_sample_url = $oap->voice_sample_url;
            $this->specializations = $oap->specializations ? implode(', ', $oap->specializations) : '';
            $this->email = $oap->email;
            $this->department_id = $oap->department_id;
            $this->team_role_id = $oap->team_role_id;
            $this->phone = $oap->phone;
            $this->employment_status = $oap->employment_status;
            $this->is_active = $oap->is_active;
            $this->available = $oap->available;
            $this->joined_date = $oap->joined_date?->format('Y-m-d') ?? '';
            $this->social_media = $oap->social_media ?? $this->social_media;
        }

        $this->loadStaffMembers();

        $this->teamRoles = TeamRole::query()
            ->where('is_active', true)
            ->when($this->department_id, function ($query) {
                $query->where('department_id', $this->department_id);
            })
            ->orderBy('name')
            ->get();
    }

    private function loadStaffMembers()
    {
        $query = StaffMember::query()
            ->where('is_active', true)
            ->orderBy('name');

        if ($this->oapId) {
            $query->where(function ($query) {
                $query->whereDoesntHave('oap')
                    ->orWhereHas('oap', function ($oapQuery) {
                        $oapQuery->where('id', $this->oapId);
                    });
            });
        } else {
            $query->whereDoesntHave('oap');
        }

        $this->staffMembers = $query->get();
    }

    public function updatedStaffMemberId()
    {
        if (!$this->staff_member_id) {
            return;
        }

        $staff = StaffMember::find($this->staff_member_id);
        if (!$staff) {
            return;
        }

        $this->name = $staff->name;
        if (!empty($staff->bio)) {
            $this->bio = $staff->bio;
        }
        if (!empty($staff->photo_url)) {
            $this->profile_photo = $staff->photo_url;
        }
        if (!empty($staff->email)) {
            $this->email = $staff->email;
        }
        if (!empty($staff->phone)) {
            $this->phone = $staff->phone;
        }
        if (!empty($staff->employment_status)) {
            $this->employment_status = $staff->employment_status;
        }
        if (!empty($staff->joined_date)) {
            $this->joined_date = $staff->joined_date->format('Y-m-d');
        }
        if (!empty($staff->department_id)) {
            $this->department_id = $staff->department_id;
        }
        if (!empty($staff->team_role_id)) {
            $this->team_role_id = $staff->team_role_id;
        }
        if (!empty($staff->social_links) && is_array($staff->social_links)) {
            $this->social_media = array_merge($this->social_media, $staff->social_links);
        }

        $this->teamRoles = TeamRole::query()
            ->where('is_active', true)
            ->when($this->department_id, function ($query) {
                $query->where('department_id', $this->department_id);
            })
            ->orderBy('name')
            ->get();
    }

    public function updatedDepartmentId()
    {
        $this->team_role_id = '';
        $this->teamRoles = TeamRole::query()
            ->where('is_active', true)
            ->where('department_id', $this->department_id)
            ->orderBy('name')
            ->get();
    }

    public function updatedProfilePhotoUpload()
    {
        $this->resetErrorBag('profile_photo_upload');
        $this->profile_photo = '';
        $this->validateOnly('profile_photo_upload');
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

        $slug = Str::slug($this->name);
        $slugExists = OAP::where('slug', $slug)
            ->when($this->isEditing, function ($query) {
                $query->where('id', '!=', $this->oapId);
            })
            ->exists();

        if ($slugExists) {
            $this->addError('name', 'An OAP with a similar name already exists.');
            return;
        }

        $photoPath = $this->profile_photo;
        if ($this->profile_photo_upload) {
            $photoPath = CloudinaryUploader::uploadImage($this->profile_photo_upload, 'oaps/photos');
        }

        $data = [
            'staff_member_id' => $this->staff_member_id,
            'name' => $this->name,
            'slug' => $slug,
            'bio' => $this->bio,
            'profile_photo' => $photoPath,
            'voice_sample_url' => $this->voice_sample_url,
            'specializations' => !empty($this->specializations)
                ? array_map('trim', explode(',', $this->specializations))
                : null,
            'email' => $this->email,
            'department_id' => $this->department_id,
            'team_role_id' => $this->team_role_id,
            'phone' => $this->phone,
            'employment_status' => $this->employment_status,
            'is_active' => $this->is_active,
            'available' => $this->available,
            'joined_date' => $this->joined_date ?: null,
            'social_media' => $this->social_media,
        ];

        if ($this->isEditing) {
            OAP::findOrFail($this->oapId)->update($data);
            $message = 'OAP updated successfully.';
        } else {
            OAP::create($data);
            $message = 'OAP created successfully.';
        }

        return redirect()->route('admin.team.oaps')->with('success', $message);
    }

    public function render()
    {
        return view('livewire.admin.team.oap-form')
            ->layout('layouts.admin', [
                'header' => $this->isEditing ? 'Edit OAP' : 'Add OAP',
            ]);
    }
}
