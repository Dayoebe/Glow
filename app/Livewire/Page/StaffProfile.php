<?php

namespace App\Livewire\Page;

use App\Models\Show\OAP;
use App\Models\Staff\StaffMember;
use App\Support\Seo;
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
        } else {
            abort(404);
        }
    }

    public function render()
    {
        $description = Str::limit(strip_tags($this->profile['bio'] ?? ''), 160);
        $description = $description ?: "Meet {$this->profile['name']}, part of the Glow 99.1 FM team in Akure.";
        $canonical = request()->url();
        $title = "{$this->profile['name']}, {$this->profile['role']} - Glow FM";

        return view('livewire.page.staff-profile', [
            'profile' => $this->profile,
        ])->layout('layouts.app', [
            'title' => $title,
            'meta_title' => $title,
            'meta_description' => $description,
            'meta_image' => $this->profile['photo'],
            'meta_image_alt' => $this->profile['name'] . ', ' . $this->profile['role'] . ' at Glow FM',
            'meta_type' => 'profile',
            'canonical_url' => $canonical,
            'structured_data' => Seo::siteGraph([
                'title' => $title,
                'description' => $description,
                'url' => $canonical,
                'image' => $this->profile['photo'],
                'type' => 'ProfilePage',
                'breadcrumbs' => [
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'Team', 'url' => route('staff.index')],
                    ['name' => $this->profile['name'], 'url' => $canonical],
                ],
                'mainEntity' => Seo::person($this->profile, $canonical),
            ]),
        ]);
    }
}
