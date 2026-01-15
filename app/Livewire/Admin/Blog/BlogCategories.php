<?php

namespace App\Livewire\Admin\Blog;

use App\Models\Blog\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class BlogCategories extends Component
{
    use WithPagination;

    // Form fields
    public $name = '';
    public $slug = '';
    public $description = '';
    public $icon = 'fas fa-newspaper';
    public $color = 'purple';
    public $is_active = true;

    // Edit mode
    public $editingCategoryId = null;
    public $isEditing = false;

    // Modal states
    public $showFormModal = false;
    public $showDeleteModal = false;
    public $categoryToDelete = null;

    // Search
    public $search = '';

    // Available icons and colors
    public $availableIcons = [
        'fas fa-newspaper',
        'fas fa-blog',
        'fas fa-pen-fancy',
        'fas fa-book-open',
        'fas fa-lightbulb',
        'fas fa-star',
        'fas fa-fire',
        'fas fa-heart',
        'fas fa-coffee',
        'fas fa-microphone',
    ];

    public $availableColors = [
        'purple' => 'Purple',
        'blue' => 'Blue',
        'emerald' => 'Emerald',
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
        $category = Category::findOrFail($categoryId);
        
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
        if (Category::where('slug', $this->slug)->exists()) {
            $this->addError('slug', 'A category with this slug already exists.');
            return;
        }

        Category::create([
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
        $category = Category::findOrFail($this->editingCategoryId);

        // Check for duplicate slug (except current category)
        if (Category::where('slug', $this->slug)
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
            $category = Category::find($this->categoryToDelete);
            
            if ($category) {
                // Check if category has posts
                if ($category->posts()->count() > 0) {
                    session()->flash('error', 'Cannot delete category with existing posts. Please reassign or delete the posts first.');
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
        $category = Category::findOrFail($categoryId);
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
        $this->color = 'purple';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function getCategoriesProperty()
    {
        $query = Category::withCount('posts');

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
            'total' => Category::count(),
            'active' => Category::where('is_active', true)->count(),
            'inactive' => Category::where('is_active', false)->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.blog.categories', [
            'categories' => $this->categories,
            'stats' => $this->stats,
        ])->layout('layouts.admin', [
            'header' => 'Blog Categories'
        ]);
    }
}