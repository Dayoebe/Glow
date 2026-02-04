<?php

namespace App\Livewire\Admin\Team;

use App\Models\Show\OAP;
use App\Models\Staff\StaffMember;
use App\Models\Team\Department;
use App\Models\Team\Role as TeamRole;
use App\Support\CloudinaryUploader;
use App\Support\PersonProfileSync;
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
                'nullable',
                'exists:staff_members,id',
                Rule::unique('oaps', 'staff_member_id')->ignore($this->oapId),
            ],
            'name' => 'required|min:3|max:255',
            'bio' => 'nullable|string|max:5000',
            'profile_photo' => 'nullable|string|max:2048',
            'profile_photo_upload' => 'nullable|image|max:5120',
            'voice_sample_url' => 'nullable|url',
            'email' => 'nullable|email',
            'department_id' => 'nullable|exists:team_departments,id',
            'team_role_id' => 'nullable|exists:team_roles,id',
            'joined_date' => 'nullable|date',
            'employment_status' => 'required',
            'is_active' => 'boolean',
            'available' => 'boolean',
        ];
    }

    private function defaultSocialMedia(): array
    {
        return [
            'facebook' => '',
            'twitter' => '',
            'instagram' => '',
            'tiktok' => '',
            'linkedin' => '',
            'youtube' => '',
        ];
    }

    private function normalizedSocialMedia(?array $links): array
    {
        $defaults = $this->defaultSocialMedia();

        if (!is_array($links)) {
            return $defaults;
        }

        return [
            'facebook' => $links['facebook'] ?? $links['facebook_url'] ?? '',
            'twitter' => $links['twitter'] ?? $links['twitter_url'] ?? '',
            'instagram' => $links['instagram'] ?? $links['instagram_url'] ?? '',
            'tiktok' => $links['tiktok'] ?? $links['tiktok_url'] ?? '',
            'linkedin' => $links['linkedin'] ?? $links['linkedin_url'] ?? '',
            'youtube' => $links['youtube'] ?? $links['youtube_url'] ?? '',
        ];
    }

    private function setTeamRolesForDepartment(): void
    {
        $this->teamRoles = $this->department_id
            ? TeamRole::query()
                ->where('is_active', true)
                ->where('department_id', $this->department_id)
                ->orderBy('name')
                ->get()
            : collect();
    }

    private function normalizeOptionalString($value): ?string
    {
        $value = is_string($value) ? trim($value) : $value;

        return $value === '' ? null : $value;
    }

    public function mount($oapId = null)
    {
        $this->social_media = $this->defaultSocialMedia();
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
            $this->social_media = $this->normalizedSocialMedia($oap->social_media);
        }

        $this->loadStaffMembers();
        $this->setTeamRolesForDepartment();
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

    public function updatedStaffMemberId($value)
    {
        if (!$value) {
            return;
        }

        $staff = StaffMember::find($value);
        if (!$staff) {
            return;
        }

        $this->name = (string) ($staff->name ?? '');
        $this->bio = (string) ($staff->bio ?? '');
        $this->profile_photo = (string) ($staff->photo_url ?? '');
        $this->profile_photo_upload = null;
        $this->email = (string) ($staff->email ?? '');
        $this->phone = (string) ($staff->phone ?? '');
        $this->employment_status = (string) ($staff->employment_status ?: 'full-time');
        $this->joined_date = $staff->joined_date?->format('Y-m-d') ?? '';
        $this->department_id = $staff->department_id ?: '';
        $this->setTeamRolesForDepartment();
        $this->team_role_id = $staff->team_role_id ?: '';
        if (!$this->team_role_id && $this->department_id && !empty($staff->role)) {
            $matchedRole = TeamRole::query()
                ->where('is_active', true)
                ->where('department_id', $this->department_id)
                ->whereRaw('LOWER(name) = ?', [Str::lower(trim((string) $staff->role))])
                ->value('id');

            $this->team_role_id = $matchedRole ?: '';
        }
        $this->social_media = $this->normalizedSocialMedia($staff->social_links);
        $this->is_active = (bool) $staff->is_active;
        $this->available = true;

        if (empty($this->specializations) && !empty($staff->role)) {
            $this->specializations = $staff->role;
        }
    }

    public function updatedDepartmentId()
    {
        $this->team_role_id = '';
        $this->setTeamRolesForDepartment();
    }

    public function updatedProfilePhotoUpload()
    {
        $this->resetErrorBag('profile_photo_upload');
        $this->profile_photo = '';
        $this->validateOnly('profile_photo_upload');
    }

    public function save()
    {
        $this->staff_member_id = $this->staff_member_id ?: null;
        $this->bio = is_string($this->bio) ? trim($this->bio) : '';
        $this->profile_photo = $this->normalizeOptionalString($this->profile_photo);
        $this->voice_sample_url = $this->normalizeOptionalString($this->voice_sample_url);
        $this->specializations = $this->normalizeOptionalString($this->specializations);
        $this->email = $this->normalizeOptionalString($this->email);
        $this->phone = $this->normalizeOptionalString($this->phone);
        $this->department_id = $this->department_id ?: null;
        $this->team_role_id = $this->team_role_id ?: null;
        $this->joined_date = $this->joined_date ?: null;
        $this->validate();

        if ($this->team_role_id && !$this->department_id) {
            $this->addError('department_id', 'Select a department for the chosen role.');
            return;
        }

        if ($this->team_role_id && $this->department_id) {
            $validRole = TeamRole::query()
                ->where('id', $this->team_role_id)
                ->where('department_id', $this->department_id)
                ->exists();

            if (!$validRole) {
                $this->addError('team_role_id', 'Selected role does not belong to the chosen department.');
                return;
            }
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
            'staff_member_id' => $this->staff_member_id ?: null,
            'name' => $this->name,
            'slug' => $slug,
            'bio' => $this->bio,
            'profile_photo' => $photoPath,
            'voice_sample_url' => $this->voice_sample_url,
            'specializations' => !empty($this->specializations)
                ? array_map('trim', explode(',', $this->specializations))
                : null,
            'email' => $this->email,
            'department_id' => $this->department_id ?: null,
            'team_role_id' => $this->team_role_id ?: null,
            'phone' => $this->phone,
            'employment_status' => $this->employment_status,
            'is_active' => $this->is_active,
            'available' => $this->available,
            'joined_date' => $this->joined_date ?: null,
            'social_media' => $this->social_media,
        ];

        if ($this->isEditing) {
            $oap = OAP::findOrFail($this->oapId);
            $oap->update($data);
            $message = 'OAP updated successfully.';
        } else {
            $oap = OAP::create($data);
            $message = 'OAP created successfully.';
        }

        PersonProfileSync::fromOap($oap);

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
