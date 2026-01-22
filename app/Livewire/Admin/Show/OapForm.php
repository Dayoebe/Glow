<?php

namespace App\Livewire\Admin\Show;

use App\Models\Show\OAP;
use App\Models\Staff\StaffMember;
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
    public $specializations = '';
    public $email = '';
    public $phone = '';
    public $staffMembers = [];

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
            'specializations' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
        ];
    }

    public function mount($oapId = null)
    {
        if ($oapId) {
            $oap = OAP::findOrFail($oapId);
            $this->oapId = $oap->id;
            $this->isEditing = true;
            $this->staff_member_id = $oap->staff_member_id;
            $this->name = $oap->name;
            $this->bio = $oap->bio;
            $this->profile_photo = $oap->profile_photo;
            $this->specializations = $oap->specializations ? implode(', ', $oap->specializations) : '';
            $this->email = $oap->email;
            $this->phone = $oap->phone;
        }

        $this->loadStaffMembers();
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

        $photoPath = $this->profile_photo;
        if ($this->profile_photo_upload) {
            $photoPath = CloudinaryUploader::uploadImage($this->profile_photo_upload, 'oaps/photos');
        }

        $staff = $this->staff_member_id ? StaffMember::find($this->staff_member_id) : null;

        $data = [
            'staff_member_id' => $this->staff_member_id,
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'bio' => $this->bio,
            'profile_photo' => $photoPath,
            'specializations' => !empty($this->specializations)
                ? array_map('trim', explode(',', $this->specializations))
                : null,
            'email' => $this->email,
            'phone' => $this->phone,
        ];

        if ($staff?->department_id) {
            $data['department_id'] = $staff->department_id;
        }

        if ($staff?->team_role_id) {
            $data['team_role_id'] = $staff->team_role_id;
        }

        if ($staff?->employment_status) {
            $data['employment_status'] = $staff->employment_status;
        }

        if ($staff?->joined_date) {
            $data['joined_date'] = $staff->joined_date;
        }

        if ($this->isEditing) {
            OAP::findOrFail($this->oapId)->update($data);
            $message = 'OAP updated successfully.';
        } else {
            OAP::create($data);
            $message = 'OAP created successfully.';
        }

        return redirect()
            ->route('admin.shows.oaps')
            ->with('success', $message);
    }

    public function render()
    {
        return view('livewire.admin.show.oap-form')
            ->layout('layouts.admin', [
                'header' => $this->isEditing ? 'Edit OAP' : 'Add OAP',
            ]);
    }
}
