<?php

namespace App\Livewire\Page;

use App\Models\Show\OAP;
use App\Models\Staff\StaffMember;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;

class StaffProfile extends Component
{
    public array $profile = [];

    public function mount(string $type, string $identifier)
    {
        if ($type === 'staff') {
            $staff = StaffMember::where('slug', $identifier)
                ->where('is_active', true)
                ->with(['departmentRelation', 'teamRole'])
                ->firstOrFail();

            $this->profile = [
                'name' => $staff->name,
                'role' => $staff->teamRole?->name ?? ($staff->role ?? 'Staff Member'),
                'department' => $staff->departmentRelation?->name ?? ($staff->department ?? 'General'),
                'bio' => $staff->bio,
                'photo' => $staff->photo_url,
                'email' => $staff->email,
                'phone' => $staff->phone,
                'social_links' => $staff->social_links ?? [],
                'type_label' => 'Staff',
            ];
        } elseif ($type === 'oap') {
            $oap = OAP::with(['department', 'teamRole'])
                ->active()
                ->where('slug', $identifier)
                ->firstOrFail();

            $this->profile = [
                'name' => $oap->name,
                'role' => $oap->teamRole?->name ?? 'On-Air Personality',
                'department' => $oap->department?->name ?? 'Broadcast',
                'bio' => $oap->bio,
                'photo' => $oap->profile_photo,
                'email' => $oap->email,
                'phone' => $oap->phone,
                'social_links' => $oap->social_media ?? [],
                'type_label' => 'On-Air Personality',
            ];
        } elseif ($type === 'user') {
            $user = User::with(['department', 'teamRole'])
                ->where('is_active', true)
                ->findOrFail($identifier);

            $this->profile = [
                'name' => $user->name,
                'role' => $user->teamRole?->name ?? ucfirst($user->role),
                'department' => $user->department?->name ?? 'General',
                'bio' => null,
                'photo' => $user->avatar,
                'email' => $user->email,
                'phone' => null,
                'social_links' => [],
                'type_label' => 'Staff',
            ];
        } else {
            abort(404);
        }
    }

    public function render()
    {
        $description = Str::limit(strip_tags($this->profile['bio'] ?? ''), 160);

        return view('livewire.page.staff-profile', [
            'profile' => $this->profile,
        ])->layout('layouts.app', [
            'title' => $this->profile['name'] . ' - Glow FM',
            'meta_description' => $description ?: 'Meet our team at Glow FM.',
            'meta_image' => $this->profile['photo'],
            'meta_type' => 'profile',
        ]);
    }
}
