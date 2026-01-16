<?php

namespace App\Livewire\Admin\Event;

use App\Models\Event\EventCategory;
use Illuminate\Support\Str;
use Livewire\Component;

class EventCategoryForm extends Component
{
    public $categoryId = null;
    public $isEditing = false;

    public $name = '';
    public $slug = '';
    public $description = '';
    public $icon = 'fas fa-calendar-alt';
    public $color = 'amber';
    public $is_active = true;

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

    public function mount($categoryId = null)
    {
        if ($categoryId) {
            $category = EventCategory::findOrFail($categoryId);
            $this->categoryId = $category->id;
            $this->isEditing = true;
            $this->name = $category->name;
            $this->slug = $category->slug;
            $this->description = $category->description;
            $this->icon = $category->icon;
            $this->color = $category->color;
            $this->is_active = $category->is_active;
        }
    }

    public function updatedName($value)
    {
        if (!$this->isEditing || empty($this->slug)) {
            $this->slug = Str::slug($value);
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $this->updateCategory();
        } else {
            $this->createCategory();
        }

        return redirect()->route('admin.events.categories')
            ->with('success', $this->isEditing ? 'Category updated successfully!' : 'Category created successfully!');
    }

    protected function createCategory()
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
    }

    protected function updateCategory()
    {
        $category = EventCategory::findOrFail($this->categoryId);

        if (EventCategory::where('slug', $this->slug)
            ->where('id', '!=', $this->categoryId)
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
    }

    public function render()
    {
        return view('livewire.admin.event.category-form')
            ->layout('layouts.admin', [
                'header' => $this->isEditing ? 'Edit Event Category' : 'Create Event Category',
            ]);
    }
}
