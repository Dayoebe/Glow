<?php

namespace App\Livewire\Admin\Vettas;

use App\Models\Vettas\VettasCategory;
use App\Models\Vettas\VettasPhoto;
use App\Models\Vettas\VettasReservation;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterCategory = '';
    public string $filterStatus = '';
    public bool $showDeleteModal = false;
    public ?int $photoToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterCategory' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterCategory(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $photoId): void
    {
        $this->photoToDelete = $photoId;
        $this->showDeleteModal = true;
    }

    public function deletePhoto(): void
    {
        if (!$this->photoToDelete) {
            return;
        }

        $photo = VettasPhoto::find($this->photoToDelete);

        if ($photo) {
            $photo->delete();
            session()->flash('success', 'Photo deleted successfully.');
        }

        $this->showDeleteModal = false;
        $this->photoToDelete = null;
    }

    public function togglePublish(int $photoId): void
    {
        $photo = VettasPhoto::findOrFail($photoId);
        $photo->is_published = !$photo->is_published;
        $photo->published_at = $photo->is_published ? ($photo->published_at ?: now()) : null;
        $photo->updated_by = auth()->id();
        $photo->save();

        session()->flash('success', $photo->is_published
            ? 'Photo published successfully.'
            : 'Photo moved back to draft.');
    }

    public function toggleFeatured(int $photoId): void
    {
        $photo = VettasPhoto::findOrFail($photoId);
        $photo->is_featured = !$photo->is_featured;
        $photo->updated_by = auth()->id();
        $photo->save();

        session()->flash('success', 'Featured status updated successfully.');
    }

    public function getPhotosProperty()
    {
        $query = VettasPhoto::query()
            ->with('category')
            ->ordered();

        if ($this->search !== '') {
            $query->search($this->search);
        }

        if ($this->filterCategory !== '') {
            $query->where('category_id', $this->filterCategory);
        }

        if ($this->filterStatus === 'published') {
            $query->where('is_published', true);
        } elseif ($this->filterStatus === 'draft') {
            $query->where('is_published', false);
        } elseif ($this->filterStatus === 'featured') {
            $query->where('is_featured', true);
        }

        return $query->paginate(12);
    }

    public function getCategoriesProperty()
    {
        return VettasCategory::query()->ordered()->get();
    }

    public function getStatsProperty(): array
    {
        return [
            'total' => VettasPhoto::count(),
            'published' => VettasPhoto::where('is_published', true)->count(),
            'featured' => VettasPhoto::where('is_featured', true)->count(),
            'categories' => VettasCategory::count(),
            'reservations' => VettasReservation::count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.vettas.index', [
            'photos' => $this->photos,
            'categories' => $this->categories,
            'stats' => $this->stats,
        ])->layout('layouts.admin', [
            'header' => 'Vettas Gallery',
        ]);
    }
}
