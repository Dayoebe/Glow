<?php

namespace App\Livewire\Admin\Profile;

use App\Models\User;
use App\Support\CloudinaryUploader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfileForm extends Component
{
    use WithFileUploads;

    public $name = '';
    public $email = '';
    public $avatar = '';
    public $avatar_upload;
    public $bio = '';
    public $phone = '';
    public array $social_links = [];
    public array $profile_visibility = [];
    public $password = '';
    public $password_confirmation = '';
    public bool $hasStaffProfile = false;
    public ?string $publicProfileUrl = null;
    public ?string $departmentLabel = null;
    public ?string $roleLabel = null;

    protected function rules()
    {
        $userId = auth()->id();

        return [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'avatar' => 'nullable|url|max:500',
            'avatar_upload' => 'nullable|image|max:5120',
            'bio' => 'nullable|string|max:3000',
            'phone' => 'nullable|string|max:50',
            'social_links.facebook' => 'nullable|url|max:500',
            'social_links.instagram' => 'nullable|url|max:500',
            'social_links.twitter' => 'nullable|url|max:500',
            'social_links.linkedin' => 'nullable|url|max:500',
            'social_links.tiktok' => 'nullable|url|max:500',
            'social_links.youtube' => 'nullable|url|max:500',
            'social_links.website' => 'nullable|url|max:500',
            'profile_visibility.email' => 'boolean',
            'profile_visibility.phone' => 'boolean',
            'profile_visibility.social.facebook' => 'boolean',
            'profile_visibility.social.instagram' => 'boolean',
            'profile_visibility.social.twitter' => 'boolean',
            'profile_visibility.social.linkedin' => 'boolean',
            'profile_visibility.social.tiktok' => 'boolean',
            'profile_visibility.social.youtube' => 'boolean',
            'profile_visibility.social.website' => 'boolean',
            'password' => 'nullable|min:6|confirmed',
        ];
    }

    public function mount()
    {
        $user = User::query()->with(['staffMember.departmentRelation', 'staffMember.teamRole'])->findOrFail(auth()->id());

        $this->name = $user->name;
        $this->email = $user->email;
        $this->avatar = $user->avatar ?? '';
        $this->bio = $user->bio ?? '';
        $this->social_links = $this->emptySocialLinks();

        if ($user->staffMember) {
            $staff = $user->staffMember;
            $this->hasStaffProfile = true;
            $this->phone = $staff->phone ?? '';
            $this->social_links = array_replace($this->social_links, $staff->social_links ?? []);
            $this->profile_visibility = [
                'email' => $staff->isPubliclyVisible('email'),
                'phone' => $staff->isPubliclyVisible('phone'),
                'social' => collect(array_keys($this->emptySocialLinks()))
                    ->mapWithKeys(fn ($platform) => [$platform => $staff->isPubliclyVisible('social.' . $platform)])
                    ->all(),
            ];
            $this->departmentLabel = $staff->departmentRelation?->name ?: $staff->department;
            $this->roleLabel = $staff->teamRole?->name ?: $staff->role;
            $this->publicProfileUrl = $staff->is_active ? route('staff.show', $staff->slug) : null;
        }
    }

    public function updatedAvatarUpload(): void
    {
        $this->resetErrorBag('avatar_upload');
        $this->validateOnly('avatar_upload');
    }

    public function save()
    {
        $this->validate();

        $user = User::query()->with(['staffMember.user', 'staffMember.oap'])->findOrFail(auth()->id());

        $avatarPath = $this->avatar;
        if ($this->avatar_upload) {
            $avatarPath = CloudinaryUploader::uploadImage($this->avatar_upload, 'users/avatars');
        }

        $data = [
            'name' => trim($this->name),
            'email' => trim($this->email),
            'avatar' => $avatarPath ?: null,
            'bio' => $this->normalizeOptionalString($this->bio),
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        DB::transaction(function () use ($user, $data): void {
            $user->update($data);

            if ($user->staffMember) {
                $user->staffMember->update([
                    'phone' => $this->normalizeOptionalString($this->phone),
                    'social_links' => collect($this->social_links)
                        ->map(fn ($value) => trim((string) $value))->filter()->all(),
                    'profile_visibility' => [
                        'email' => (bool) ($this->profile_visibility['email'] ?? false),
                        'phone' => (bool) ($this->profile_visibility['phone'] ?? false),
                        ...collect($this->profile_visibility['social'] ?? [])
                            ->mapWithKeys(fn ($visible, $platform) => ['social.' . $platform => (bool) $visible])
                            ->all(),
                    ],
                ]);
            }
        });

        $this->avatar = $avatarPath ?: '';
        $this->reset('avatar_upload', 'password', 'password_confirmation');

        session()->flash('success', 'Profile updated successfully.');
    }

    private function emptySocialLinks(): array
    {
        return array_fill_keys(['facebook', 'instagram', 'twitter', 'linkedin', 'tiktok', 'youtube', 'website'], '');
    }

    private function normalizeOptionalString(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    public function render()
    {
        return view('livewire.admin.profile.form')
            ->layout('layouts.admin', [
                'header' => 'My Profile',
            ]);
    }
}
