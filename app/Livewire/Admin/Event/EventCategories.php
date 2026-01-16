<?php

namespace App\Livewire\Admin\Event;

use App\Models\Event\EventCategory;
use Livewire\Component;
use Livewire\WithPagination;

class EventCategories extends Component
{
    use WithPagination;

    public $showDeleteModal = false;
    public $categoryToDelete = null;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($categoryId)
    {
        $this->categoryToDelete = $categoryId;
        $this->showDeleteModal = true;
    }

    public function deleteCategory()
    {
        if ($this->categoryToDelete) {
            $category = EventCategory::find($this->categoryToDelete);

            if ($category) {
                if ($category->events()->count() > 0) {
                    session()->flash('error', 'Cannot delete category with existing events. Please reassign or delete the events first.');
                } else {
                    $category->delete();
                    session()->flash('success', 'Category deleted successfully!');
                }
            }
        }

        $this->showDeleteModal = false;
        $this->categoryToDelete = null;
    }

    public function toggleStatus($categoryId)
    {
        $category = EventCategory::findOrFail($categoryId);
        $category->is_active = !$category->is_active;
        $category->save();

        $status = $category->is_active ? 'activated' : 'deactivated';
        session()->flash('success', "Category {$status} successfully!");
    }

    public function getCategoriesProperty()
    {
        $query = EventCategory::withCount('events');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        return $query->latest()->paginate(10);
    }

    public function getStatsProperty()
    {
        return [
            'total' => EventCategory::count(),
            'active' => EventCategory::where('is_active', true)->count(),
            'inactive' => EventCategory::where('is_active', false)->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.event.categories', [
            'categories' => $this->categories,
            'stats' => $this->stats,
        ])->layout('layouts.admin', [
            'header' => 'Event Categories'
        ]);
    }
}
