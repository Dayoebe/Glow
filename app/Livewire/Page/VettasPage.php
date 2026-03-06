<?php

namespace App\Livewire\Page;

use App\Models\Vettas\VettasCategory;
use App\Models\Vettas\VettasPhoto;
use Livewire\Component;
use Livewire\WithPagination;

class VettasPage extends Component
{
    use WithPagination;

    public string $category = '';

    protected $queryString = [
        'category' => ['except' => ''],
    ];

    public function updatingCategory(): void
    {
        $this->resetPage();
    }

    public function filterByCategory(string $slug = ''): void
    {
        $this->category = $slug;
        $this->resetPage();
    }

    public function getCategoriesProperty()
    {
        return VettasCategory::query()
            ->active()
            ->whereHas('photos', fn ($query) => $query->published())
            ->withCount([
                'photos as published_photos_count' => fn ($query) => $query->published(),
            ])
            ->ordered()
            ->get();
    }

    public function getFeaturedPhotosProperty()
    {
        return VettasPhoto::query()
            ->with('category')
            ->published()
            ->featured()
            ->whereHas('category', fn ($query) => $query->active())
            ->ordered()
            ->take(4)
            ->get();
    }

    public function getPhotosProperty()
    {
        $query = VettasPhoto::query()
            ->with('category')
            ->published()
            ->whereHas('category', fn ($builder) => $builder->active());

        if ($this->category !== '') {
            $query->whereHas('category', function ($builder) {
                $builder->where('slug', $this->category);
            });
        }

        return $query->ordered()->paginate(12);
    }

    public function render()
    {
        $activeCategory = $this->categories->firstWhere('slug', $this->category);

        return view('livewire.page.vettas-page', [
            'photos' => $this->photos,
            'categories' => $this->categories,
            'featuredPhotos' => $this->featuredPhotos,
            'activeCategory' => $activeCategory,
        ])->layout('layouts.app', [
            'title' => 'Vettas - Glow FM',
            'meta_title' => 'Vettas - Glow FM',
            'meta_description' => 'Explore the Vettas gallery on Glow FM with curated photos organized by category.',
            'meta_image' => $this->featuredPhotos->first()?->image_path,
        ]);
    }
}
