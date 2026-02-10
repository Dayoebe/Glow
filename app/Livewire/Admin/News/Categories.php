<?php

namespace App\Livewire\Admin\News;

use App\Models\News\NewsCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class Categories extends Component
{
    use WithPagination;

    // Form fields
    public $name = '';
    public $slug = '';
    public $description = '';
    public $icon = 'fas fa-newspaper';
    public $color = 'blue';
    public $is_active = true;

    // Edit mode
    public $editingCategoryId = null;
    public $isEditing = false;

    // Modal states
    public $showFormModal = false;

    // Search
    public $search = '';

    // Available icons and colors
    public $availableIcons = [
        'fas fa-newspaper',
        'fas fa-music',
        'fas fa-microphone',
        'fas fa-calendar-alt',
        'fas fa-star',
        'fas fa-bullhorn',
        'fas fa-trophy',
        'fas fa-heart',
        'fas fa-fire',
        'fas fa-bolt',
    ];

    public $availableColors = [
        'blue' => 'Blue',
        'emerald' => 'Emerald',
        'purple' => 'Purple',
        'amber' => 'Amber',
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
        $category = NewsCategory::findOrFail($categoryId);
        
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
        // Check for duplicate slug
        if (NewsCategory::where('slug', $this->slug)->exists()) {
            $this->addError('slug', 'A category with this slug already exists.');
            return;
        }

        NewsCategory::create([
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
        $category = NewsCategory::findOrFail($this->editingCategoryId);

        // Check for duplicate slug (except current category)
        if (NewsCategory::where('slug', $this->slug)
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

    public function deleteCategory($categoryId)
    {
        $category = NewsCategory::find($categoryId);
        
        if (!$category) {
            session()->flash('error', 'Category not found.');
            return;
        }

        // Check if category has news
        if ($category->news()->count() > 0) {
            session()->flash('error', 'Cannot delete category with existing news articles. Please reassign or delete the articles first.');
            return;
        }

        $category->delete();
        session()->flash('success', 'Category deleted successfully!');
    }

    public function toggleStatus($categoryId)
    {
        $category = NewsCategory::findOrFail($categoryId);
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
        $this->icon = 'fas fa-newspaper';
        $this->color = 'blue';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function getCategoriesProperty()
    {
        $query = NewsCategory::withCount('news');

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        return $query->latest()->paginate(10);
    }

    public function getStatsProperty()
    {
        return [
            'total' => NewsCategory::count(),
            'active' => NewsCategory::where('is_active', true)->count(),
            'inactive' => NewsCategory::where('is_active', false)->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.news.categories', [
            'categories' => $this->categories,
            'stats' => $this->stats,
        ])->layout('layouts.admin', [
            'header' => 'News Categories'
        ]);
    }
}