<?php

namespace App\Livewire\Admin\Vettas;

use App\Models\Vettas\VettasCategory;
use Illuminate\Support\Str;
use Livewire\Component;

class CategoryForm extends Component
{
    public ?int $categoryId = null;
    public bool $isEditing = false;

    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public int $sort_order = 0;
    public bool $is_active = true;

    protected function rules(): array
    {
        $rules = [
            'name' => 'required|string|min:2|max:100',
            'slug' => 'required|string|max:100|unique:vettas_categories,slug',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];

        if ($this->isEditing && $this->categoryId) {
            $rules['slug'] = 'required|string|max:100|unique:vettas_categories,slug,' . $this->categoryId;
        }

        return $rules;
    }

    public function mount($categoryId = null): void
    {
        if (!$categoryId) {
            return;
        }

        $category = VettasCategory::findOrFail($categoryId);

        $this->categoryId = $category->id;
        $this->isEditing = true;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = (string) ($category->description ?? '');
        $this->sort_order = (int) $category->sort_order;
        $this->is_active = (bool) $category->is_active;
    }

    public function updatedName(string $value): void
    {
        if (!$this->isEditing || $this->slug === '') {
            $this->slug = Str::slug($value);
        }
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->isEditing) {
            VettasCategory::findOrFail($this->categoryId)->update($validated);
        } else {
            VettasCategory::create($validated);
        }

        return redirect()->route('admin.vettas.categories')
            ->with('success', $this->isEditing
                ? 'Vettas category updated successfully.'
                : 'Vettas category created successfully.');
    }

    public function render()
    {
        return view('livewire.admin.vettas.category-form')
            ->layout('layouts.admin', [
                'header' => $this->isEditing ? 'Edit Vettas Category' : 'Create Vettas Category',
            ]);
    }
}
