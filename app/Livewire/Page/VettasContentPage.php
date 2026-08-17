<?php

namespace App\Livewire\Page;

use App\Models\Setting;
use App\Models\Vettas\VettasCategory;
use App\Models\Vettas\VettasPhoto;
use App\Support\VettasPageSettings;
use Livewire\Component;

class VettasContentPage extends Component
{
    public string $section = 'about';

    public function mount(string $section = 'about'): void
    {
        abort_unless(in_array($section, ['about', 'amenities', 'gallery', 'guide'], true), 404);
        $this->section = $section;
    }

    public function render()
    {
        $content = array_replace_recursive(VettasPageSettings::defaults(), Setting::get('vettas', []));
        $photos = VettasPhoto::query()->with('category')->published()
            ->whereHas('category', fn ($query) => $query->active())->ordered()->take(24)->get();
        $categories = VettasCategory::query()->active()
            ->whereHas('photos', fn ($query) => $query->published())->ordered()->get();
        $titles = [
            'about' => 'About Vettas Apartment',
            'amenities' => 'Amenities at Vettas Apartment',
            'gallery' => 'Vettas Apartment Gallery',
            'guide' => 'Plan Your Stay at Vettas Apartment',
        ];

        return view('livewire.page.vettas-content-page', compact('content', 'photos', 'categories'))
            ->layout('layouts.app', [
                'title' => $titles[$this->section] . ' - Glow FM',
                'meta_title' => $titles[$this->section] . ' - Glow FM',
                'meta_description' => $content['seo'][$this->section] ?? $content['about']['summary'],
                'meta_image' => $photos->first()?->public_image_url,
            ]);
    }
}
