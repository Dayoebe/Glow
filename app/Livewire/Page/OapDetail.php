<?php

namespace App\Livewire\Page;

use App\Models\Show\OAP;
use App\Support\Seo;
use Illuminate\Support\Str;
use Livewire\Component;

class OapDetail extends Component
{
    public OAP $oap;

    public function mount($slug)
    {
        $this->oap = OAP::with([
            'shows' => fn ($query) => $query->active()->with('category')->orderBy('title'),
            'department',
            'teamRole',
        ])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function render()
    {
        $description = Str::limit(strip_tags($this->oap->bio ?? ''), 160);
        $description = $description ?: "Meet {$this->oap->name}, a Glow 99.1 FM presenter in Akure, and explore their programmes and profile.";
        $canonical = route('oaps.show', $this->oap->slug);
        $role = $this->oap->teamRole?->name ?? 'On-Air Personality';
        $title = "{$this->oap->name}, {$role} - Glow FM";
        $person = Seo::person([
            'name' => $this->oap->name,
            'role' => $role,
            'bio' => $this->oap->bio,
            'photo' => $this->oap->profile_photo,
            'social_links' => $this->oap->public_social_links,
        ], $canonical);

        return view('livewire.page.oap-detail', [
            'oap' => $this->oap,
        ])->layout('layouts.app', [
            'title' => $title,
            'meta_title' => $title,
            'meta_description' => $description,
            'meta_image' => $this->oap->profile_photo,
            'meta_image_alt' => $this->oap->name . ', Glow FM presenter',
            'meta_type' => 'profile',
            'canonical_url' => $canonical,
            'structured_data' => Seo::siteGraph([
                'title' => $title,
                'description' => $description,
                'url' => $canonical,
                'image' => $this->oap->profile_photo,
                'type' => 'ProfilePage',
                'breadcrumbs' => [
                    ['name' => 'Home', 'url' => route('home')],
                    ['name' => 'Presenters', 'url' => route('oaps.index')],
                    ['name' => $this->oap->name, 'url' => $canonical],
                ],
                'mainEntity' => $person,
            ]),
        ]);
    }
}
