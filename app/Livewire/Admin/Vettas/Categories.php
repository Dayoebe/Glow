<?php

namespace App\Livewire\Admin\Vettas;

use App\Models\Vettas\VettasCategory;
use Livewire\Component;
use Livewire\WithPagination;

class Categories extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $showDeleteModal = false;
    public ?int $categoryToDelete = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $categoryId): void
    {
        $this->categoryToDelete = $categoryId;
        $this->showDeleteModal = true;
    }

    public function deleteCategory(): void
    {
        if (!$this->categoryToDelete) {
            return;
        }

        $category = VettasCategory::find($this->categoryToDelete);

        if ($category) {
            if ($category->photos()->count() > 0) {
                session()->flash('error', 'Cannot delete a category that still has photos assigned to it.');
            } else {
                $category->delete();
                session()->flash('success', 'Vettas category deleted successfully.');
            }
        }

        $this->showDeleteModal = false;
        $this->categoryToDelete = null;
    }

    public function toggleStatus(int $categoryId): void
    {
        $category = VettasCategory::findOrFail($categoryId);
        $category->is_active = !$category->is_active;
        $category->save();

        session()->flash('success', $category->is_active
            ? 'Category activated successfully.'
            : 'Category deactivated successfully.');
    }

    public function getCategoriesProperty()
    {
        $query = VettasCategory::query()->withCount('photos');

        if ($this->search !== '') {
            $query->where(function ($builder) {
                $builder->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhere('slug', 'like', '%' . $this->search . '%');
            });
        }

        return $query->ordered()->paginate(10);
    }

    public function getStatsProperty(): array
    {
        return [
            'total' => VettasCategory::count(),
            'active' => VettasCategory::where('is_active', true)->count(),
            'inactive' => VettasCategory::where('is_active', false)->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.vettas.categories', [
            'categories' => $this->categories,
            'stats' => $this->stats,
        ])->layout('layouts.admin', [
            'header' => 'Vettas Categories',
        ]);
    }
}
