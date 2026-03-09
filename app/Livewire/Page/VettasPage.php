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
    public string $search = '';
    public string $sortBy = 'latest';

    protected $queryString = [
        'category' => ['except' => ''],
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'latest'],
    ];

    public function updatingCategory(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSortBy(): void
    {
        $this->resetPage();
    }

    public function filterByCategory(string $slug = ''): void
    {
        $this->category = $slug;
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['category', 'search', 'sortBy']);
        $this->sortBy = 'latest';
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
        return $this->applyPhotoFilters(
            VettasPhoto::query()
                ->with('category')
                ->published()
                ->featured()
                ->whereHas('category', fn ($query) => $query->active())
        )
            ->ordered()
            ->take(4)
            ->get();
    }

    public function getPhotosProperty()
    {
        $query = $this->applyPhotoFilters(
            VettasPhoto::query()
            ->with('category')
            ->published()
            ->whereHas('category', fn ($builder) => $builder->active())
        );

        $this->applyPhotoSorting($query);

        return $query->paginate(12);
    }

    private function applyPhotoFilters($query)
    {
        if ($this->category !== '') {
            $query->whereHas('category', function ($builder) {
                $builder->where('slug', $this->category);
            });
        }

        if ($this->search !== '') {
            $searchTerm = $this->search;

            $query->where(function ($builder) use ($searchTerm) {
                $builder->search($searchTerm)
                    ->orWhereHas('category', function ($categoryQuery) use ($searchTerm) {
                        $categoryQuery->where('name', 'like', "%{$searchTerm}%")
                            ->orWhere('description', 'like', "%{$searchTerm}%");
                    });
            });
        }

        return $query;
    }

    private function applyPhotoSorting($query): void
    {
        switch ($this->sortBy) {
            case 'oldest':
                $query->orderByRaw('captured_at is null')
                    ->orderBy('captured_at')
                    ->orderByRaw('published_at is null')
                    ->orderBy('published_at')
                    ->orderBy('id');
                break;

            case 'category':
                $query->leftJoin('vettas_categories as sort_categories', 'sort_categories.id', '=', 'vettas_photos.category_id')
                    ->select('vettas_photos.*')
                    ->orderBy('sort_categories.name')
                    ->orderByDesc('vettas_photos.is_featured')
                    ->orderBy('vettas_photos.display_order')
                    ->orderByDesc('vettas_photos.captured_at')
                    ->orderByDesc('vettas_photos.published_at')
                    ->orderByDesc('vettas_photos.id');
                break;

            case 'featured':
                $query->orderByDesc('is_featured')
                    ->orderBy('display_order')
                    ->orderByDesc('captured_at')
                    ->orderByDesc('published_at')
                    ->orderByDesc('id');
                break;

            default:
                $query->ordered();
                break;
        }
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
