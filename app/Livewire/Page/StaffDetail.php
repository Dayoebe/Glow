<?php

namespace App\Livewire\Page;

use App\Models\Staff\StaffMember;
use App\Support\Seo;
use Illuminate\Support\Str;
use Livewire\Component;

class StaffDetail extends Component
{
    public StaffMember $staff;

    public function mount($slug)
    {
        $this->staff = StaffMember::query()->activeDirectory()
            ->where('slug', $slug)
            ->with(['departmentRelation', 'teamRole'])
            ->firstOrFail();
    }

    public function render()
    {
        $description = Str::limit(strip_tags($this->staff->bio ?? ''), 160);
        $role = $this->staff->teamRole?->name ?? ($this->staff->role ?? 'Team Member');
        $description = $description ?: "Meet {$this->staff->name}, {$role} at Glow 99.1 FM in Akure, Ondo State.";
        $canonical = route('staff.show', $this->staff->slug);
        $title = "{$this->staff->name}, {$role} - Glow FM";
        $person = Seo::person([
            'name' => $this->staff->name,
            'role' => $role,
            'bio' => $this->staff->bio,
            'photo' => $this->staff->photo_url,
            'social_links' => $this->staff->public_social_links,
        ], $canonical);

        return view('livewire.page.staff-detail', [
            'staff' => $this->staff,
        ])->layout('layouts.app', [
            'title' => $title,
            'meta_title' => $title,
            'meta_description' => $description,
            'meta_image' => $this->staff->photo_url,
            'meta_image_alt' => $this->staff->name . ', ' . $role . ' at Glow FM',
            'meta_type' => 'profile',
            'canonical_url' => $canonical,
            'structured_data' => Seo::siteGraph([
                'title' => $title,
                'description' => $description,
                'url' => $canonical,
                'image' => $this->staff->photo_url,
                'type' => 'ProfilePage',
                'breadcrumbs' => [
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'Team', 'url' => route('staff.index')],
                    ['name' => $this->staff->name, 'url' => $canonical],
                ],
                'mainEntity' => $person,
            ]),
        ]);
    }
}
