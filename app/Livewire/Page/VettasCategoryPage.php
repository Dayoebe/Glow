<?php

namespace App\Livewire\Page;

use App\Models\Vettas\VettasCategory;
use App\Support\Seo;
use Livewire\Component;

class VettasCategoryPage extends Component
{
    public VettasCategory $category;

    public function mount(VettasCategory $category): void
    {
        abort_unless($category->is_active && $category->photos()->published()->exists(), 404);
        $this->category = $category;
    }

    public function render()
    {
        $photos = $this->category->photos()->published()->ordered()->get();
        $relatedCategories = VettasCategory::query()->active()->whereKeyNot($this->category->id)
            ->whereHas('photos', fn ($query) => $query->published())->ordered()->take(4)->get();
        $title = $this->category->seo_title ?: $this->category->name . ' at Vettas Apartment';
        $description = $this->category->meta_description ?: ($this->category->description ?: 'Explore ' . $this->category->name . ' at Vettas Apartment and request availability for your stay.');
        $canonical = route('vettas.categories.show', $this->category);
        $graph = Seo::siteGraph([
            'title' => $title,
            'description' => $description,
            'url' => $canonical,
            'image' => $photos->first()?->public_image_url,
            'breadcrumbs' => [
                ['name' => 'Home', 'url' => route('home')],
                ['name' => 'Vettas Apartment', 'url' => route('vettas.index')],
                ['name' => $this->category->name, 'url' => $canonical],
            ],
        ]);

        if (!empty($this->category->faqs)) {
            $graph['@graph'][] = [
                '@type' => 'FAQPage', '@id' => $canonical . '#faq',
                'mainEntity' => collect($this->category->faqs)->map(fn ($faq) => [
                    '@type' => 'Question', 'name' => $faq['question'],
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
                ])->values()->all(),
            ];
        }

        return view('livewire.page.vettas-category-page', compact('photos', 'relatedCategories'))
            ->layout('layouts.app', [
                'title' => $title . ' - Glow FM', 'meta_title' => $title,
                'meta_description' => $description, 'canonical_url' => $canonical,
                'meta_image' => $photos->first()?->public_image_url,
                'meta_image_alt' => $photos->first()?->alt_text ?: $title,
                'structured_data' => $graph,
            ]);
    }
}
