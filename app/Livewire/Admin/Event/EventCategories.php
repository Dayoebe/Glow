<?php

namespace App\Livewire\Admin\Event;

use App\Models\Event\EventCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class EventCategories extends Component
{
    use WithPagination;

    public $name = '';
    public $slug = '';
    public $description = '';
    public $icon = 'fas fa-calendar-alt';
    public $color = 'amber';
    public $is_active = true;

    public $editingCategoryId = null;
    public $isEditing = false;

    public $showFormModal = false;
    public $showDeleteModal = false;
    public $categoryToDelete = null;

    public $search = '';

    public $availableIcons = [
        'fas fa-calendar-alt',
        'fas fa-ticket-alt',
        'fas fa-music',
        'fas fa-microphone',
        'fas fa-users',
        'fas fa-star',
        'fas fa-map-marker-alt',
        'fas fa-bullhorn',
        'fas fa-glass-cheers',
        'fas fa-heart',
    ];

    public $availableColors = [
        'amber' => 'Amber',
        'emerald' => 'Emerald',
        'purple' => 'Purple',
        'blue' => 'Blue',
        'red' => 'Red',
        'pink' => 'Pink',
        'indigo' => 'Indigo',
        'teal' => 'Teal',
    ];

    protected $rules = [
        'name' => 'required|min:3|max:100',
        'slug' => 'required|max:100',
        'description' => 'nullable|max:255',
        'icon' => 'required',
        'color' => 'required',
        'is_active' => 'boolean',
    ];

    public function updatedName($value)
    {
        if (!$this->isEditing || empty($this->slug)) {
            $this->slug = Str::slug($value);
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function openEditModal($categoryId)
    {
        $category = EventCategory::findOrFail($categoryId);

        $this->editingCategoryId = $category->id;
        $this->isEditing = true;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description;
        $this->icon = $category->icon;
        $this->color = $category->color;
        $this->is_active = $category->is_active;

        $this->showFormModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $this->update();
        } else {
            $this->create();
        }
    }

    protected function create()
    {
        if (EventCategory::where('slug', $this->slug)->exists()) {
            $this->addError('slug', 'A category with this slug already exists.');
            return;
        }

        EventCategory::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'icon' => $this->icon,
            'color' => $this->color,
            'is_active' => $this->is_active,
        ]);

        session()->flash('success', 'Category created successfully!');
        $this->closeModal();
    }

    protected function update()
    {
        $category = EventCategory::findOrFail($this->editingCategoryId);

        if (EventCategory::where('slug', $this->slug)
            ->where('id', '!=', $this->editingCategoryId)
            ->exists()) {
            $this->addError('slug', 'A category with this slug already exists.');
            return;
        }

        $category->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'icon' => $this->icon,
            'color' => $this->color,
            'is_active' => $this->is_active,
        ]);

        session()->flash('success', 'Category updated successfully!');
        $this->closeModal();
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

    public function closeModal()
    {
        $this->showFormModal = false;
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->reset([
            'name',
            'slug',
            'description',
            'icon',
            'color',
            'is_active',
            'editingCategoryId',
            'isEditing',
        ]);
        $this->icon = 'fas fa-calendar-alt';
        $this->color = 'amber';
        $this->is_active = true;
        $this->resetErrorBag();
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
